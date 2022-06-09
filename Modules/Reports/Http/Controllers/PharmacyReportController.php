<?php

namespace Modules\Reports\Http\Controllers;

use App\Entry;
use App\Exports\PurchaseEntryExport;
use App\HospitalDepartment;
use App\PatBillDetail;
use App\PatBilling;
use App\PurchaseBill;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PharmacyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
           
        ];
        return view('reports::pharmacy.index', $data);
    }

    public function getList(Request $request)
    {
        $data['finalfrom'] = $finalfrom = $request->from_date.' 00:00:00';
       
        
        $data['finalto'] = $finalto = $request->to_date.' 23:59:59';
      
        if (!$finalfrom || !$finalto) {
            return \response()->json(['error' => 'Please enter date']);
        }
       // dd($finalfrom);

        $data['cash'] = $cash =  PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldbilltype','Cash')
        ->where('tblpatbilldetail.fldsave','1')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();

        $data['credit'] = $credit =  PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldbilltype','Credit')
        ->where('tblpatbilldetail.fldsave','1')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();

        $data['refund'] = $refund =  PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%RET%')
        ->where('tblpatbilldetail.fldbilltype','Cash')
        ->where('tblpatbilldetail.fldsave','1')
        ->where(function ($query) {
            $query->orWhere('tblpatbilling.flditemtype', '=', 'Surgicals')
                ->orWhere('tblpatbilling.flditemtype', '=', 'Medicines')
                ->orWhere('tblpatbilling.flditemtype', '=', 'Extra Items');
        })
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();

        $data['refundcredit'] = $refund =  PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%RET%')
        ->where('tblpatbilldetail.fldbilltype','Credit')
        ->where('tblpatbilldetail.fldsave','1')
        ->where(function ($query) {
            $query->orWhere('tblpatbilling.flditemtype', '=', 'Surgicals')
                ->orWhere('tblpatbilling.flditemtype', '=', 'Medicines')
                ->orWhere('tblpatbilling.flditemtype', '=', 'Extra Items');
        })
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();

        $data['depositcash'] = $depositcash =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%DEP%')
        ->where('tblpatbilldetail.fldpayitemname','Pharmacy Deposit')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Cash')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();
        
        $data['depositrefundcash'] = $depositrefundcash =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%DEP%')
        ->where('tblpatbilldetail.fldpayitemname','Pharmacy Deposit Refund')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Cash')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();

        $data['depositcredit'] = $depositcredit =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%DEP%')
        ->where('tblpatbilldetail.fldpayitemname','Pharmacy Deposit')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Credit')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();
        
        $data['depositrefundcredit'] = $depositrefundcredit =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%DEP%')
        ->where('tblpatbilldetail.fldpayitemname','Pharmacy Deposit Refund')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Credit')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();

        $data['tobepaidbypatientcash'] = $tobepaidbypatientcash =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Cash')
        ->where('tblpatbilldetail.fldpayitemname','Discharge Clearence')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();
        $data['tobepaidbypatientcredit'] = $tobepaidbypatientcredit =  PatBillDetail::select(\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldbilltype','Credit')
        ->where('tblpatbilldetail.fldpayitemname','Discharge Clearence')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilldetail.fldtime','>=',$finalfrom)
                    ->where('tblpatbilldetail.fldtime','<=',$finalto);
        })
        ->get();


        $data['vatableamount']  =  PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldtaxamt','>','0')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();

        $data['nonvatableamount'] =   PatBillDetail::join('tblpatbilling','tblpatbilling.fldbillno','=','tblpatbilldetail.fldbillno' )
        ->select(\DB::raw('sum(tblpatbilldetail.flditemamt) as flditemamt'),\DB::raw('sum(tblpatbilldetail.fldtaxamt) as fldtaxamt'),\DB::raw('sum(tblpatbilldetail.flddiscountamt) as flddiscountamt'),\DB::raw('sum(tblpatbilldetail.fldchargedamt) as fldchargedamt'),\DB::raw('sum(tblpatbilldetail.fldreceivedamt) as fldreceivedamt'))
        ->where('tblpatbilldetail.fldbillno','LIKE','%PHM%')
        ->where('tblpatbilldetail.fldsave','1')
        ->where('tblpatbilldetail.fldtaxamt','<=','0')
        ->when(($finalfrom), function ($q) use ($finalfrom,$finalto){
            return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                    ->where('tblpatbilling.fldtime','<=',$finalto);
        })
        ->get();


     


        

        return view('reports::pharmacy.phamacy-sales', $data);
    }

    

}
