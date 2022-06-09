<?php

namespace Modules\Inventory\Http\Controllers;

use App\Entry;
use App\MedicineBrand;
use App\SurgBrand;
use App\ExtraBrand;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends Controller
{

    public function expiryReport(Request $request){
        $expirydate = $request->get('date');
        // echo $expirydate; exit;
        try{
            $result = Entry::orderBy('fldstockid','ASC')->select('fldcomp','fldcategory','fldstockid','fldbatch','fldexpiry','fldstatus','fldqty','fldsellpr')->where('fldexpiry','<',$expirydate)->where('fldqty','>',0)->get();
            // dd($result);

            $data['result'] = $result;
            $data['date'] = $expirydate;
           
            return view('inventory::pdf.expiry-report', $data)/*->setPaper('a4')->stream('expiry-report.pdf')*/;
        }catch(\Exception $e){
            dd($e);
        }
        
    }

    public function underStockReport(){
        try{
            // select fldbrandid,fldmanufacturer,fldstandard,fldmaxqty,fldminqty,fldleadtime from tblmedbrand where fldactive='Active'
            $medecines = MedicineBrand::select('fldbrandid','fldmanufacturer','fldstandard','fldmaxqty','fldminqty','fldleadtime')->where('fldactive','Active')->get();
            $surgical = SurgBrand::select('fldbrandid','fldmanufacturer','fldstandard','fldmaxqty','fldminqty','fldleadtime')->where('fldactive','Active')->get();
            $extra = ExtraBrand::select('fldbrandid','fldmanufacturer','fldstandard','fldmaxqty','fldminqty','fldleadtime')->where('fldactive','Active')->get();

            $data['medicines'] = $medecines;
            $data['surgical'] = $surgical;
            $data['extra'] = $extra;
            return view('inventory::pdf.under-stock-report', $data)/*->setPaper('a4')->stream('under-stock-report.pdf')*/;
        }catch(\Exception $e){
            dd($e);
        }
        

    }
}
