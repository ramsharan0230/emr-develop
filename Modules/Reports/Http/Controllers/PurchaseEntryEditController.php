<?php

namespace Modules\Reports\Http\Controllers;

use App\Adjustment;
use App\BulkSale;
use App\Entry;
use App\PatBillDetail;
use App\PatBilling;
use App\Purchase;
use App\PurchaseBill;
use App\StockReturn;
use App\Transfer;
use App\Utils\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseEntryEditController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'suppliers' => Purchase::select('fldsuppname')->distinct('fldsuppname')->groupBy('fldsuppname')->get()
        ];
        return view('reports::purchaseEntryEdit.index', $data);
    }

    public function getRefrence(Request $request)
    {
        if (!$request->get('supplier')) {
            return \response()->json(['error' => 'Please select supplier']);
        }
        $references = \App\Purchase::select('fldreference')->distinct('fldreference')->where([
            'fldsuppname' => $request->get('supplier'),
        ])->whereNotNull('fldreference')->groupBy('fldreference')->get();
        $html = '';
        if ($references) {
            $html .= '<option value="">--Select--</option>';
            foreach ($references as $ref) {
                $html .= '<option value="' . $ref->fldreference . '">' . $ref->fldreference . '</option>';
            }
        }
        return response()->json($html);
    }

    public function getPurchaseEntries(Request $request){
        try{
            $purchaseEntries = Purchase::select('tblentry.fldbatch as fldbatch','tblentry.fldexpiry as fldexpiry','tblentry.fldqty as entryqty',
                                            'tblpurchase.fldstockid as fldstockid','tblpurchase.fldnetcost as fldnetcost',
                                            'tblpurchase.flsuppcost as flsuppcost','tblpurchase.fldvatamt as fldvatamt','tblpurchase.fldcasdisc as fldcasdisc',
                                            'tblpurchase.fldcasbonus as fldcasbonus','tblpurchase.fldqtybonus as fldqtybonus','tblpurchase.fldcarcost as fldcarcost',
                                            'tblpurchase.fldtotalqty as fldtotalqty','tblpurchase.fldid as fldid','tblpurchase.fldsellprice as fldsellprice',
                                            'tblpurchase.fldtotalcost as fldtotalcost','tblpurchase.fldreturnqty as fldreturnqty')
                                            ->leftJoin('tblentry','tblpurchase.fldstockno','=','tblentry.fldstockno')
                                            ->where('tblentry.fldsav',1)
                                            ->where('tblpurchase.fldsuppname',$request->supplier)
                                            ->where('tblpurchase.fldreference',$request->reference)
                                            ->get();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Fetched reference data.',
                'purchaseEntries' => $purchaseEntries
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to get reference data.',
            ]);
        }
    }

    public function editPurchaseEntry(Request $request){
        try{

            $purchaseDetail = Purchase::where('fldid',$request->fldid)->first();
            return $this->checkItemDispensed($purchaseDetail);

        } catch (Exception $e){
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed.',
            ]);
        }
    }

    public function updatePurchaseEntry(Request $request)
    {
        DB::beginTransaction();
        try{
            $purchaseDetail = Purchase::where('fldid',$request->fldid)->first();

            $dispenseStatus =  $this->checkItemDispensed($purchaseDetail);
            $dispenseStatus = json_decode($dispenseStatus,true);
            if ( isset($dispenseStatus['isDispensed']) && $dispenseStatus['isDispensed'] == 'Yes' ) {
                return response()->json([
                    'status'=> FALSE,
                    'message' => 'Failed to update data.',
                ]);
            }

            $entryqty = $request->totalqty + $request->qtybonus;
            if($purchaseDetail){
                $purchaseDetail->update([
                    'flsuppcost' => $request->suppcost,
                    'fldcasdisc' => $request->cashdisc,
                    'fldcasbonus' => $request->cashbonus,
                    'fldqtybonus' => $request->qtybonus,
                    'fldcarcost' => $request->carcost,
                    'fldnetcost' => $request->netcost,
                    'fldsellprice' => $request->sellprice,
                    'fldtotalqty' => $request->totalqty,
                    'fldtotalcost' => $request->totalprice,
                    'fldvatamt' => $request->vatamt,
                    'fldbatch' => $request->batch,
                ]);
            }
            $entryDetail = Entry::where('fldstockno',$purchaseDetail->fldstockno)->first();
            if($entryDetail){
                $entryDetail->update([
                    'fldbatch' => $request->batch,
                    'fldexpiry' => $request->expiry,
                    'fldqty' => $entryqty,
                    'fldsellpr' => $request->sellprice,
                ]);
            }
            $purchaseEntriesByRef = Purchase::where('fldreference',$purchaseDetail->fldreference)->get();
            $purchaseBillDetail = PurchaseBill::where('fldreference',$purchaseDetail->fldreference)->first();
            $totalamt = 0;
            $totaltax = 0;
            foreach($purchaseEntriesByRef as $EntriyByRef){
                $totalamt += $EntriyByRef->fldtotalcost;
                $totaltax += $EntriyByRef->fldvatamt;
            }
            if($purchaseBillDetail){
                $purchaseBillDetail->update([
                    'fldcredit' => $totalamt,
                    'flddebit' => $totalamt,
                    'fldtotaltax' => $totaltax,
                    'fldtotalvat' => $totalamt,
                ]);
            }
            DB::commit();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfuly updated data.',
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update data.',
            ]);
        }
    }

    public function deletePurchaseEntry(Request $request)
    {
        try{

            $purchaseDetail = Purchase::where('fldid',$request->fldid)->first();
            return $this->checkItemDispensed($purchaseDetail);

        } catch (Exception $e){
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed.',
            ]);
        }
    }

    public function destoryPurchaseEntry(Request $request)
    {
        DB::beginTransaction();
        try{
            $purchaseDetail = Purchase::where('fldid',$request->fldid)->first();

                $stockno = $purchaseDetail->fldstockno;
                $reference = $purchaseDetail->fldreference;
                if($purchaseDetail){
                    $purchaseDetail->delete();
                }
                $entryDetail = Entry::where('fldstockno',$stockno)->first();
                if($entryDetail){
                    $entryDetail->delete();
                }
                $purchaseEntriesByRef = Purchase::where('fldreference',$reference)->get();
                $purchaseBillDetail = PurchaseBill::where('fldreference',$reference)->first();
                $totalamt = 0;
                $totaltax = 0;
                foreach($purchaseEntriesByRef as $EntriyByRef){
                    $totalamt += $EntriyByRef->fldtotalcost;
                    $totaltax += $EntriyByRef->fldvatamt;
                }
                if($purchaseBillDetail){
                    $purchaseBillDetail->update([
                        'fldcredit' => $totalamt,
                        'flddebit' => $totalamt,
                        'fldtotaltax' => $totaltax,
                        'fldtotalvat' => $totalamt,
                    ]);
                }
                DB::commit();
                return response()->json([
                    'status'=> TRUE,
                    'message' => 'Successfuly deleted data.',
                ]);

        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to delete data.',
            ]);
        }
    }

    private function checkItemDispensed($purchaseDetail)
    {
        try {

            // Check if transferred
            $transferData = Transfer::where('fldoldstockno', $purchaseDetail->fldstockno)
                                    // ->with('loopOldStockno')
                                    ->get();
            if(count($transferData) > 0){
                return response()->json([
                    'status'=> TRUE,
                    'isDispensed' => "Yes",
                    'message' => "Cannot edit this item."
                ]);
            }
            // Check if stock adjusted
            $adjustmentData = Adjustment::where('fldstockid', $purchaseDetail->fldstockno)
                                    ->get();
            if(count($adjustmentData) > 0){
                return response()->json([
                    'status'=> TRUE,
                    'isDispensed' => "Yes",
                    'message' => "Cannot edit this item."
                ]);
            }
            // Check if stock consumed
            $consumeData = BulkSale::where('fldstockid', $purchaseDetail->fldstockno)
                                    ->get();
            if(count($consumeData) > 0){
                return response()->json([
                    'status'=> TRUE,
                    'isDispensed' => "Yes",
                    'message' => "Cannot edit this item."
                ]);
            }
            // Check if stock returned
            $stockreturnData = StockReturn::where('fldstockid', $purchaseDetail->fldstockno)
                                    ->get();
            if(count($stockreturnData) > 0){
                return response()->json([
                    'status'=> TRUE,
                    'isDispensed' => "Yes",
                    'message' => "Cannot edit this item."
                ]);
            }

            // $fldstocknos = [];
            // array_push($fldstocknos,$purchaseDetail->fldstockno);
            // $flatten = $this->_flattened($transferData);
            // foreach($flatten as $flat){
            //     if(array_key_exists('fldstockno', $flat)){
            //         array_push($fldstocknos,$flat['fldstockno']);
            //     }
            // }
            // $fldstocknos = array_unique($fldstocknos);
            $entryqty = Entry::select(DB::raw('SUM(fldqty) as entry_qty'))->where('fldstockno',$purchaseDetail->fldstockno)->first()->entry_qty;
            // $adjustmentqty = Adjustment::select(DB::raw('SUM(fldcurrqty) as adjust_qty'))->whereIn('fldstockno',$fldstocknos)->first()->adjust_qty;
            $purchaseqty = $purchaseDetail->fldtotalqty + $purchaseDetail->fldqtybonus;
            $isDispensed = ($entryqty == $purchaseqty) ? "No" : "Yes";
            return response()->json([
                'status'=> TRUE,
                'isDispensed' => $isDispensed
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status'=> FALSE,
                'isDispensed' => "Yes",
                'message' => "Something went wrong."
            ]);
        }
    }

    protected function _flattened($array)
    {
        $flatArray = [];

        if (!is_array($array)) {
            $array = (array)$array;
        }

        foreach($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $flatArray = array_merge($flatArray, $this->_flattened($value));
            } else {
                $flatArray[0][$key] = $value;
            }
        }

        return $flatArray;
    }

    // public function checkIsTransfered($stockno){
    //     $transferData = Transfer::select('fldstockno','fldoldstockno')
    //                             ->where('fldoldstockno', $stockno)
    //                             ->first();
    //     if($transferData){
    //         return $transferData->fldstockno;
    //     }else{
    //         return 0;
    //     }
    // }
}
