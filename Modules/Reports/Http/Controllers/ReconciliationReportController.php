<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Options;
use App\Utils\Helpers;
use App\PatBillDetail;
use App\PatBilling;
use App\Exports\ReconciliationReportExport;
use App\Exports\ReconciliationSummaryReportExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReconciliationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('reports::reconciliation.index',$data);
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchReconciliation(Request $request)
    {
        // dd($request->all());
        try{
            $datesql = "SELECT DISTINCT DATE_FORMAT(fldtime,'%Y-%m-%d') AS date FROM tblpatbilldetail where fldtime >= '".$request->eng_from_date."' and fldtime <= '".$request->eng_to_date."'";
            $datedata = DB::select($datesql);

            $html = '';
            if(isset($datedata) and count($datedata) > 0){
                foreach($datedata as $d){
                    // dd($d);
                    $totalsales = PatBilling::where('fldcomp',$request->department)->where('fldtime','LIKE',$d->date.'%')->where('fldsave','1')->sum('fldditemamt');
                    // dd($totalsales);
                    $paidsales = DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.fldbilltype','LIKE','cash')
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $creditsales = DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.payment_mode','LIKE','credit')
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    // echo $creditsales; exit;
                    $vatamt = DB::table('tblpatbilling as p')
                                    ->where('p.fldcomp',$request->department)
                                    ->where('p.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldtaxamt');
                    $bankamt = DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where('pb.payment_mode','LIKE','fonepay')
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');
                    $depositcashandcardamt = DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldbillno','LIKE','DEP%')
                                    ->where(function ($query) {
                                        $query->orWhere('pb.payment_mode','LIKE','cash')
                                            ->orWhere('pb.payment_mode','LIKE','card');
                                    })
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldreceivedamt');
                    $depositbankdamt = DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldbillno','LIKE','DEP%')
                                    ->where('pb.payment_mode','LIKE','fonepay')
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldreceivedamt');

                    $cashandcreditsales = DB::table('tblpatbilling as p')
                                    ->join('tblpatbilldetail as pb','pb.fldbillno','p.fldbillno')
                                    ->where(function ($query) {
                                        $query->orWhere('pb.payment_mode','LIKE','cash')
                                            ->orWhere('pb.payment_mode','LIKE','card');
                                    })
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->where('p.fldsave','1')
                                    ->sum('p.fldditemamt');

                    $depositadjustment = DB::table('tblpatbilldetail as pb')
                                    ->where('pb.fldcomp',$request->department)
                                    ->where('pb.fldtime','LIKE',$d->date.'%')
                                    ->sum('pb.fldprevdeposit');
                    $value1 = $cashandcreditsales+$bankamt;
                    $value2 = $depositcashandcardamt+$depositbankdamt;
                    $m3 = $paidsales-$depositadjustment;
                    $n3 = $value1 - $value2;
                    $html .='<tr>';
                    $html .='<td>'.$d->date.'</td>';
                    $html .='<td>'.$totalsales.'</td>';
                    $html .='<td>'.$creditsales.'</td>';
                    $html .='<td>'.$paidsales.'</td>';
                    $html .='<td>'.$vatamt.'</td>';
                    $html .='<td>'.$cashandcreditsales.'</td>';
                    $html .='<td>'.$bankamt.'</td>';
                    $html .='<td>'.$depositcashandcardamt.'</td>';
                    $html .='<td>'.$depositbankdamt.'</td>';
                    $html .='<td>'.$depositadjustment.'</td>';
                    $html .='<td></td>';
                    $html .='<td>'.$m3.'</td>';
                    $html .='<td>'.$n3.'</td>';
                    $html .='<td>'.($m3 - $n3).'</td>';
                    $html .='</tr>';
                }
            }
            
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
        
    }
   
    public function exportExcel(Request $request){
        // dd($request->all());
        try{
            $export = new ReconciliationReportExport($request->all());
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'ReconciliationReport.xlsx');

        }catch(\Exception $e){
            dd($e);
        }
    }

    public function exportSummaryExcel(Request $request){
        try{
            $export = new ReconciliationSummaryReportExport($request->all());
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'ReconciliationSummaryReport.xlsx');

        }catch(\Exception $e){
            dd($e);
        }
    }
}
