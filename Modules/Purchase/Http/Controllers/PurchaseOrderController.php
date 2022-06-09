<?php

namespace Modules\Purchase\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'suppliers' => \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get(),
            'routes' => array_keys(array_slice(Helpers::getDispenserRoute(), 0, 12)),
            // 'orders' => \App\Demand::where([
            //         'fldsave_order' => '0',
            //         'fldcomp_order' => Helpers::getCompName(),
        	// 		'fldpurtype' => 'Outside',
            //     ])->get(),
        ];

        return view('purchase::purchaseorder', $data);
    }

    public function finalsave(Request $request)
    {
        DB::beginTransaction();
        try {
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $demandids = $request->demandids;
            $fldquotationno = Helpers::getNextAutoId('PurchaseOutside', TRUE);

            $quotationno = "DEMO-".$fiscalYear."-".$fldquotationno;
            $purchaseNo = "PO-".$fiscalYear."-".Helpers::getNextAutoId('PurchaseNo', TRUE);
            $checkAutoIdAlreadyUsed = Order::where('fldreference',$purchaseNo)->get();
            if(count($checkAutoIdAlreadyUsed) > 0){
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'msg' => "Unable to save. Auto Id already used."
                ]);
            }

            $itemData = $request->get('itemData');
            if ($itemData)
                $itemData = array_combine(array_column($itemData, 'demandid'), array_column($itemData, 'quantity'));

            $demands = \App\Demand::whereIn('fldid',$demandids)->get();

            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $orders = [];
            foreach ($demands as $demand) {
                $fldqty = isset($itemData[$demand->fldid]) ?  $itemData[$demand->fldid] : $demand->fldquantity;
                $orders[] = [
                    'fldsuppname' => $request->supplier,
                    'fldroute' => $demand->fldroute,
                    'flditemname' => $demand->fldstockid,
                    'fldqty' => $fldqty,
                    'fldrate' => $demand->fldrate,
                    'fldamt' => ($fldqty*$demand->fldrate),
                    'fldsav' => 1,
                    'fldreference' => $purchaseNo,
                    'flduserid' => $userid,
                    'fldorddate' => $time,
                    'fldcomp' => $computer,
                    'flddrug' => NULL,
                    'xyz' => 0,
                    'fldstatus' => 'Requested',
                    'fldlocat' => \Session::has('selected_user_hospital_department') ? \Session::get('selected_user_hospital_department')->name : NULL,
                    'fldactualorddt' => $demand->fldtime_order,
                    'flddelvdate' => NULL,
                    'fldpsno' => 'chlan',
                    'fldtax' => NULL,
                    'fldindivat' => NULL,
                    'fldinditotalvat' => NULL,
                    'fldvatamt' => NULL,
                    'fldremqty' => $fldqty,
                    'flduptime' => NULL,
                    'fldupuser' => NULL,
                    'fldolditemname' => NULL,
                ];
            }
            \App\Order::insert($orders);

            foreach($itemData as $itemKey => $item){
                $demandData = \App\Demand::where('fldid',$itemKey)->first();
                if($demandData->fldquantity == $demandData->fldremqty){
                    $remqty = $demandData->fldquantity - $item;
                }else{
                    $remqty = $demandData->fldremqty - $item;
                }

                if($demandData){
                    \App\Demand::where('fldid',$itemKey)->update([
                        'fldremqty' => $remqty,
                        'fldpono' => $purchaseNo,
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'quotationno' => $quotationno,
                'purchaseNo' => $purchaseNo,
                'msg' => "Successfuly saved."
            ]);
            // return response()->json(compact('quotationno', 'purchaseNo'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'msg' => "Something went wrong..."
            ]);
        }
    }

    public function printBill(Request $request)
    {
        $fldreference = $request->get('fldreference');
        return  view('purchase::pdf.purchasereport', [
            'orders' => \App\Order::where('fldreference', $fldreference)->get(),
        ]);
    }

    public function getQuotationNoOrders(Request $request)
    {
        $quotationno = $request->get('quotationno');
        $showall = $request->get('showall', 'false');

        $where = [
            // 'fldcomp_order' => Helpers::getCompName(),
            'fldquotationno' => $quotationno,
        ];
        if ($showall == 'false')
            $where['fldsave_order'] = '0';

        $result = \App\Demand::where($where)->where('fldremqty','!=',0)->get();
        // dd($result->toArray());
        return response()->json($result);
    }
}
