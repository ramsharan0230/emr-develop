<?php

namespace Modules\Store\Http\Controllers;

use App\Demand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

class DemandFormController extends Controller
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
            //     ])->get(),
        ];

        return view('store::demandform', $data);
    }

    public function add(Request $request)
    {
        try {
            if($request->has('isPurchaseOrder')){
                $fldsave_order = 1;
            }else{
                $fldsave_order = 0;
            }
            if(!isset($request->fldsuppname)){
                $suppname = "General";
            }else{
                $suppname = $request->get('fldsuppname');
            }
            if($request->has('fldisgenericdemand')){
                $isGenericdemand = 1;
            }else{
                $isGenericdemand = 0;
            }
            $fldpurtype = $request->get('fldpurtype');
            $insertData = [
                'fldquotationno' => $request->get('fldquotationno'),
                'fldroute' => $request->get('fldroute'),
                'fldpurtype' => $request->get('fldpurtype'),
                'fldbillno' => $request->get('fldbillno'),
                'fldsuppname' => $suppname,
                'fldstockid' => $request->get('fldstockid'),
                'fldquantity' => $request->get('fldquantity'),
                'fldremqty' => $request->get('fldquantity'),
                'fldrate' => $request->get('fldrate'),
                'flduserid_order' => Helpers::getCurrentUserName(),
                'fldtime_order' => Helpers::dateNepToEng($request->get('fldorderdate'))->full_date . " " . date('H:i:s'),
                'fldcomp_order' => Helpers::getCompName(),
                'fldsave_order' => $fldsave_order,
                'xyz' => 0,
                'fldisgenericdemand' => $isGenericdemand,
                'fldbatch' => $request->get('fldbatch')
            ];
            if ($fldpurtype == 'Inside') {
                $insertData['fldordbranch'] = $request->get('fldordbranch');
                $insertData['fldordcomp'] = $request->get('fldordcomp');
                // $insertData['fldbatch'] = $request->get('fldbatch');
            }

            $data = \App\Demand::create($insertData);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully added data.',
                'data' => $data
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function updateQuantity(Request $request)
    {
        try {
            \App\Demand::where('fldid', $request->get('fldid'))->update([
                'fldquantity' => $request->get('fldquantity'),
                'fldremqty' => $request->get('fldquantity')
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function updateRate(Request $request)
    {
        try {
            \App\Demand::where('fldid', $request->get('fldid'))->update([
                'fldrate' => $request->get('fldrate')
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function delete(Request $request)
    {
        try {
            \App\Demand::where('fldid', $request->get('fldid'))->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }

    public function getSupplierStore(Request $request)
    {
        $department = $request->get('department');

        if ($department == 'Outside')
            $data = \App\Supplier::select('fldsuppname', 'fldsuppaddress')->where('fldactive', 'Active')->get();
        else
            $data = Helpers::getDepartmentAndComp();

        return response()->json($data);
    }

    public function finalsave(Request $request)
    {
        DB::beginTransaction();
        try {
            $fiscalYear = Helpers::getFiscalYear()->fldname;
            $time = date('Y-m-d H:i:s');
            if($request->fldpurtype == "Outside"){
                $prefix = "DEMO";
                $autoId = Helpers::getNextAutoId('PurchaseOutside', TRUE);
            }else{
                $prefix = "DEMI";
                $autoId = Helpers::getNextAutoId('PurchaseInside', TRUE);
            }
            // Helpers::getNextAutoId('QuotationNo', TRUE);
            $quotationno = $request->get('quotationno') ?: $autoId;
            $fldquotationno = $prefix."-".$fiscalYear."-".$quotationno;

            $checkAutoIdAlreadyUsed = Demand::where('fldquotationno',$fldquotationno)->get();
            if(count($checkAutoIdAlreadyUsed) > 0){
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'msg' => "Unable to save. Auto Id already used."
                ]);
            }

            \App\Demand::whereIn('fldid',$request->demandids)->whereNull('fldquotationno')->update([
                'fldquotationno' => $fldquotationno,
                'fldsave_order' => '1',
                'fldordersavedtime' => $time
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'fldquotationno' => $fldquotationno,
                'msg' => "Successfuly saved."
            ]);
            // return response()->json($fldquotationno);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'msg' => "Something went wrong..."
            ]);
        }
    }

    public function verify(Request $request)
    {
        $time = date('Y-m-d H:i:s');
        $quotationno = $request->get('quotationno');
        \App\Demand::where([
            'fldsave_order' => '0',
            'fldcomp_order' => Helpers::getCompName(),
            'fldquotationno' => $quotationno,
        ])->update([
            'flduserid_verify' => Helpers::getCurrentUserName(),
            'fldtime_verify' => $time,
            'fldcomp_verify' => Helpers::getCompName(),
        ]);

        return response()->json();
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

        return response()->json(
            \App\Demand::where($where)->get());
    }

    public function report(Request $request)
    {
        $quotationno = $request->get('fldquotationno');
        if ($quotationno) {
            $where = [
                // 'fldcomp_order' => Helpers::getCompName(),
                'fldquotationno' => $quotationno,
            ];
        } else {
            $where = [
                'fldsave_order' => '0',
                // 'fldcomp_order' => Helpers::getCompName(),
            ];
        }

        return view('store::layouts.pdf.demandformreport', [
            'orders' => \App\Demand::where($where)->get(),
            'quotationno' => $quotationno
        ]);
    }

    public function getSupplierDemands(Request $request){
        $demands = Demand::where([['fldsuppname',$request->supplierName],['fldsave_order',0],['fldquotationno',$request->quotationno]])->get();
        return response()->json($demands);
    }

    public function getPendingSupplierDemands(Request $request){
        $demands = Demand::where([
                                    ['fldsuppname',$request->supplierName],
                                    ['fldsave_order',1],
                                    ['fldquotationno',$request->quotationno],
                                    ['fldpono',null]
                                ])->get();
        return response()->json($demands);
    }

    public function getMedicineList(Request $request)
    {
        /*
            select tblmedbrand.fldbrandid From tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute='eye/ear')

            Select tblsurgbrand.fldbrandid From tblsurgbrand where tblsurgbrand.fldactive='Active' and tblsurgbrand.fldsurgid in(select tblsurgicals.fldsurgid from tblsurgicals where tblsurgicals.fldsurgcateg='msurg')

            Select tblextrabrand.fldbrandid From tblextrabrand where tblextrabrand.fldactive='Active'
        */

        $orderBy = $request->get('orderBy');
        $route = $request->get('route');
        $department = $request->get('department');
        $supplier = $request->get('supplier');
        $isStock = $request->get('isStock');
        $medcategory = Helpers::getParticularCategory($route);
        $col = ($orderBy == 'brand') ? 'fldbrand' : 'fldbrandid';

        $where = ['Active'];
        if ($medcategory != 'Extra Items')
            array_push($where, $route);

        $sql = "SELECT tblmedbrand.fldbrandid AS col FROM tblmedbrand WHERE tblmedbrand.fldactive=? AND tblmedbrand.flddrug IN(SELECT tbldrug.flddrug FROM tbldrug WHERE tbldrug.fldroute=?)";
        if ($medcategory == 'Surgicals')
            $sql = "SELECT tblsurgbrand.fldbrandid AS col FROM tblsurgbrand WHERE tblsurgbrand.fldactive=? AND tblsurgbrand.fldsurgid IN(SELECT tblsurgicals.fldsurgid FROM tblsurgicals WHERE tblsurgicals.fldsurgcateg=?)";
        elseif ($medcategory == 'Extra Items')
            $sql = "SELECT tblextrabrand.fldbrandid AS col FROM tblextrabrand WHERE tblextrabrand.fldactive=?";

        if ($department == 'Inside') {
            $fldcomp = $request->get('fldcomp');
            $sql = "SELECT tblentry.fldstockid AS col,fldqty,fldsellpr,fldbatch FROM tblentry WHERE tblentry.fldcomp=? AND tblentry.fldqty>0 AND tblentry.fldstockid IN(" . $sql . ") ORDER BY tblentry.fldstockid ASC";
            array_unshift($where, $fldcomp);
        }

        $data = \DB::select($sql, $where);
        return response()->json($data);
    }

    public function getUnsavedGeneralDemands(Request $request){
        try {
            $unsavedDemands = Demand::where([
                                                ['fldpurtype','Outside'],
                                                ['fldisgenericdemand',1],
                                                ['fldsave_order',0]
                                            ])->get();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully data retrieved.',
                'data' => $unsavedDemands,
            ]);
        } catch (Exception $e) {}

        return response()->json([
            'status' => FALSE,
            'message' => __('messages.error'),
        ]);
    }
}
