<?php

namespace Modules\Store\Http\Controllers;

use App\BulkSale;
use App\Drug;
use App\Entry;
use App\Events\StockLive;
use App\MedicineBrand;
use App\Purchase;
use App\SurgBrand;
use App\Surgical;
use App\Target;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

/**
 * Class StockConsumeController
 * @package Modules\Store\Http\Controllers
 */
class StockConsumeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['targets'] = Target::select('flditem')->get();
        $data['routes'] = Drug::select('fldroute')->distinct()->orderby('fldroute', 'ASC')->get();
        $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        return view('store::stockconsume.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStockTarget(Request $request)
    {
        try {
//            INSERT INTO `tbltarget` ( `flditem` ) VALUES ( 'Hahahahaha' )
            $insertData['flditem'] = $request->flditem;
            $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            Target::insert($insertData);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully added data.',
            ]);

        } catch (\GearmanException $e) {
            return response()->json([
                'status' => false,
                'message' => __('messages.error'),
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStockConsumed(Request $request)
    {
        $stockConsumed = BulkSale::select(\DB::raw('GROUP_CONCAT(tblbulksale.fldid) as fldids'),'tblbulksale.fldstockno', 'tblbulksale.fldcategory', 'tblbulksale.fldstockid', 'tblbulksale.fldreference','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tblbulksale.fldqtydisp) as fldqtydisp'),\DB::raw('sum(tblbulksale.fldnetcost) as fldnetcost'))
                                    ->leftJoin('tblentry','tblentry.fldstockno','=','tblbulksale.fldstockno')
                                    ->where('tblbulksale.fldtarget', $request->target)
                                    ->where('tblbulksale.fldcomp', Helpers::getCompName())
                                    ->where('tblbulksale.fldsave',0)
                                    ->with('stock')
                                    ->groupBy(['tblbulksale.fldstockid','tblentry.fldbatch'])
                                    ->get();

        // $stockConsumed = BulkSale::select('fldid', 'fldstockno', 'fldcategory', 'fldstockid', 'fldqtydisp', 'fldreference', 'fldnetcost')
        //                             ->where('fldtarget', $request->target)
        //                             ->where('fldcomp', Helpers::getCompName())
        //                             ->where('fldsave',0)
        //                             ->with('stock')
        //                             ->get();

        $html = '';
        $count = 1;
        if (count($stockConsumed)) {
            foreach ($stockConsumed as $stock) {
                $html .= '<tr data-fldid="'.$stock->fldids.'">';
                $html .= '<td><input type="checkbox" class="js-stockconsume-select-class-checkbox" data-fldid="'.$stock->fldids.'"></td>';
                $html .= '<td>' . $stock->fldcategory . '</td>';
                $html .= '<td>' . $stock->fldstockid . '</td>';
                $html .= '<td>' . $stock->fldbatch . '</td>';
                $html .= '<td>' . $stock->fldexpiry . '</td>';
                $html .= '<td>' . $stock->fldqtydisp . '</td>';
                $html .= '<td>' . $stock->fldnetcost . '</td>';
                $html .= '</tr>';
                $count++;
            }
        }

        if($request->has('isFinalSave')){
            return $html;
        }else{
            return response()->json([
                'status' => TRUE,
                'data' => $html,
                'message' => 'Success.',
            ]);
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeRoute(Request $request)
    {
        try {
            $data['particulars'] = Entry::select('fldstockid as col')
                ->whereRaw('lower(fldstockid) like "%"')
                ->where('fldqty', '>', 0)
                ->where('fldcategory', $request->route)
                ->where('fldcomp', Helpers::getCompName())
                ->where('fldsav',1)
                ->orderBy('fldstockid', 'ASC')
                ->distinct('fldstockid')
                ->get();

            $html = '<option>--Select--</option>';
            if ($data['particulars']) {
                foreach ($data['particulars'] as $particular) {
                    $html .= '<option value="' . $particular->col . '">' . $particular->col . '</option>';
                }
            }
            return \response()->json($html);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

//     public function changeRoute(Request $request)
//     {
//         try {
//             if ($request->routeChanged == 'msurg' || $request->routeChanged == 'suture' || $request->routeChanged == 'ortho' || $request->routeChanged == 'extra') {
//                 $surgicals = Surgical::select('fldsurgid')->where('fldsurgcateg', $request->routeChanged)->pluck('fldsurgid');

//                 $surgicalBrand = SurgBrand::select('fldbrandid')
//                     ->where('fldactive', 'Active')
//                     ->whereIn('fldsurgid', $surgicals)
//                     ->pluck('fldbrandid');

//                 $data['medicines'] = Entry::select('fldstockid as col')
//                     ->whereRaw('lower(fldstockid) like "%"')
//                     ->where('fldqty', '>', 0)
// //                ->where('fldcomp', 'comp01')
//                     ->whereIn('fldstockid', $surgicalBrand)
//                     ->orderBy('fldstockid', 'ASC')
//                     ->get();

//             } else {
//                 $drug = Drug::select('flddrug')->where('fldroute', $request->routeChanged)->pluck('flddrug');

//                 $data['medicines'] = MedicineBrand::select('fldbrandid as col')
//                     ->whereRaw('lower(fldbrandid) like "%"')
//                     ->where('fldactive', 'Active')
//                     ->where('fldmaxqty', '<>', -1)
//                     //                ->where('fldcomp', 'comp01')
//                     ->whereIn('flddrug', $drug)
//                     ->orderBy('fldbrandid', 'ASC')
//                     ->get();
//             }
//             $html = '';
//             if ($data['medicines']) {
//                 foreach ($data['medicines'] as $medicine) {
//                     $html .= '<option value="' . $medicine->col . '">' . $medicine->col . '</option>';
//                 }
//             }

// //            $html = view('store::dynamic-views.med-list', $data)->render();
//             return \response()->json($html);
//         } catch (Exception $exception) {
//             dd($exception);
//         }
//     }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function changeStock(Request $request)
    {
        try {
            $data['batch'] = Entry::select('fldbatch as col')
                ->where('fldstockid', $request->particularSelect)
                ->where('fldcomp', Helpers::getCompName())
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->distinct('fldbatch')
                ->get();
            $html = '<option value=""> --Select--</option>';
            if ($data['batch']) {
                foreach ($data['batch'] as $batch) {
                    $html .= '<option value="' . $batch->col . '">' . $batch->col . '</option>';
                }
            }
            return \response()->json($html);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function batchChange(Request $request)
    {

        try {
            $entryDetails = Entry::where('fldstockid', $request->medicineSelect)
                ->where('fldbatch', $request->batch)
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->where('fldcomp', Helpers::getCompName())
                ->with('pendingStockConsume')
                ->get();

            if ($entryDetails) {
                $qty = 0;
                $expiry = "";
                foreach($entryDetails as $entryDetail){
                    $qty += $entryDetail->fldqty;
                    if(count($entryDetail->pendingStockConsume) > 0){
                        $qty = $qty - $entryDetail->pendingStockConsume->sum('fldqtydisp');
                    }
                    $expiry = $entryDetail->fldexpiry;
                }
                return \response()->json([
                    'expiry' => Carbon::parse($expiry)->format('Y-m-d'),
                    'qty' => $qty,
                ]);
            }

            return \response()->json('');

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStockConsumed(Request $request)
    {
        DB::beginTransaction();
        try {
            $entryDetails = Entry::where('fldstockid', $request->flditem)
                ->where('fldbatch', $request->batch)
                ->where('fldqty', '>', 0)
                ->where('fldsav',1)
                ->where('fldcomp', Helpers::getCompName())
                ->orderBy('fldstatus','asc')
                ->get();

            $qty = $request->consume_qty;
            foreach($entryDetails as $entryDetail){
                $tempqty = $entryDetail->fldqty;
                if(count($entryDetail->pendingStockConsume) > 0){
                    $tempqty = $tempqty - $entryDetail->pendingStockConsume->sum('fldqtydisp');
                }
                if($tempqty > 0){
                    if($qty > $tempqty){
                        $consumedQty = $tempqty;
                    }else{
                        $consumedQty = $qty;
                    }
                    $qty = $qty - $consumedQty;
                    if($consumedQty > 0){
                        $dataInsert['fldtarget'] = $request->target;
                        $dataInsert['fldbulktime'] = $request->date_target ? Helpers::dateNepToEng($request->date_target)->full_date :'';
                        $dataInsert['fldcategory'] = $request->route;
                        $dataInsert['fldstockno'] = $entryDetail->fldstockno;
                        $dataInsert['fldstockid'] = $request->flditem;
                        $dataInsert['fldnetcost'] = $entryDetail->fldsellpr * $consumedQty;
                        $dataInsert['fldqtydisp'] = $consumedQty;
                        $dataInsert['fldqtyret'] = 0;
                        $dataInsert['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                        $dataInsert['fldtime'] = date("Y-m-d H:i:s");
                        $dataInsert['fldcomp'] = Helpers::getCompName();
                        $dataInsert['fldsave'] = 0;
                        $dataInsert['fldreference'] = NULL;
                        $dataInsert['flduptime'] = NULL;
                        $dataInsert['xyz'] = 0;
                        $dataInsert['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        BulkSale::create($dataInsert);
                    }
                }
            }

            $stockConsumed = BulkSale::select(\DB::raw('GROUP_CONCAT(tblbulksale.fldid) as fldids'),'tblbulksale.fldstockno', 'tblbulksale.fldcategory', 'tblbulksale.fldstockid', 'tblbulksale.fldreference','tblentry.fldbatch','tblentry.fldexpiry',\DB::raw('sum(tblbulksale.fldqtydisp) as fldqtydisp'),\DB::raw('sum(tblbulksale.fldnetcost) as fldnetcost'))
                                    ->leftJoin('tblentry','tblentry.fldstockno','=','tblbulksale.fldstockno')
                                    ->where('tblbulksale.fldtarget', $request->target)
                                    ->where('tblbulksale.fldcomp', Helpers::getCompName())
                                    ->where('tblbulksale.fldsave',0)
                                    ->with('stock')
                                    ->groupBy(['tblbulksale.fldstockid','tblentry.fldbatch'])
                                    ->get();

            $html = '';
            $count = 1;
            if (count($stockConsumed)) {
                foreach ($stockConsumed as $stock) {
                    $html .= '<tr data-fldid="'.$stock->fldids.'">';
                    $html .= '<td><input type="checkbox" class="js-stockconsume-select-class-checkbox" data-fldid="'.$stock->fldids.'"></td>';
                    $html .= '<td>' . $stock->fldcategory . '</td>';
                    $html .= '<td>' . $stock->fldstockid . '</td>';
                    $html .= '<td>' . $stock->fldbatch . '</td>';
                    $html .= '<td>' . $stock->fldexpiry . '</td>';
                    $html .= '<td>' . $stock->fldqtydisp . '</td>';
                    $html .= '<td>' . $stock->fldnetcost . '</td>';
                    $html .= '</tr>';
                    $count++;
                }
            }
            DB::commit();
            return response()->json([
                'status' => TRUE,
                'data' => $html,
                'message' => 'Success.',
            ]);
        } catch (\GearmanException $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public  function finalSave(Request $request){
        if(!$request->get('consumeids'))
        {
            return \response()->json(['error','Please select row first!']);
        }

        try {
            DB::beginTransaction();
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $stockConsumeReference = 'BULF-'.$fiscalYear."-".Helpers::getNextAutoId('ReferenceNo',TRUE);
            if($request->consumeids){
                foreach ($request->consumeids as $stockconsume){
                    $consume = BulkSale::where('fldid',$stockconsume)->first();

                    $entry = Entry::where('fldstockno',$consume->fldstockno)->first();
                    if($entry) {
                        $quantity = ($entry->fldqty)-($consume->fldqtydisp);
                        $entry->update(['fldqty'=>$quantity]);
                    }

                    $consume->update([
                                        'fldsave'=>1,
                                        'fldreference'=>$stockConsumeReference
                                    ]);
                }
            }
            $html = $this->listStockConsumed($request);
            //event trigger for live stock
            event(new StockLive(1));
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
        }

        return response([
            'status' => true,
            'fldreference' => $stockConsumeReference,
            'html' => $html
        ]);
    }

    public function exportReport(Request  $request)
    {
        $data['stockConsumed'] = BulkSale::select('tblbulksale.fldtarget', 'tblbulksale.fldstockno', 'tblbulksale.fldcategory', 'tblbulksale.fldstockid',\DB::raw('sum(tblbulksale.fldqtydisp) as fldqtydisp'), 'tblbulksale.fldreference','tblentry.fldbatch','tblentry.fldexpiry')
                                        ->leftJoin('tblentry','tblentry.fldstockno','=','tblbulksale.fldstockno')
                                        ->where('fldreference', $request->fldreference)
                                        ->with('stock')
                                        ->groupBy(['tblbulksale.fldstockid','tblentry.fldbatch'])
                                        ->get();
        $data['certificate'] = "STOCK CONSUME";
        $data['fldreference'] = $request->fldreference;
        return view('store::stockconsume.stock-consume-report', $data);
    }
}
