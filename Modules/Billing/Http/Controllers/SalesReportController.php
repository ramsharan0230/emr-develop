<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\Department;
use App\Encounter;
use App\Exports\SalesReportExport;
use App\HospitalDepartmentUsers;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\ServiceGroup;
use App\Utils\Helpers;
use Auth;
use Cache;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('billing::salesReport', $data);
    }

    public function searchSalesDetail(Request $request){
        try{
            // $salesData = $this->saleData($request);
            // echo count($salesData); exit;
            $salesData = DB::table('tblpatbilling as pb')
                            ->select('pb.fldtime', 'pb.fldbillno', 'p.fldpannumber', 'pb.flditemname', 'pb.flditemqty', 'pb.flditemrate', 'pb.fldditemamt', 'pb.fldtaxamt', 'pb.flddiscamt', 'pb.fldcomp',\DB::raw('CONCAT(p.fldptnamefir," " ,p.fldptnamelast) as patientname'))
                            ->join('tblencounter as e','e.fldencounterval','pb.fldencounterval')
                            ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                            ->where('pb.fldtime','>',$request->eng_from_date.' 00:00:00')
                            ->where('pb.fldtime','<',$request->eng_to_date.' 23:59:59')
                            ->where('pb.fldcomp',$request->department)
                            ->where('pb.fldsave','1')
                            ->whereNotNull('pb.fldbillno')
                            ->orderBy('pb.fldtime','ASC')
                            ->paginate(50);
            // dd($salesData);
            // echo count($salesData); exit;

            $html = '';
            if(isset($salesData) and count($salesData) > 0){
                foreach($salesData as $k=>$sales){
                    $nepalidate = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($sales->fldtime)));
                    $sn = $k+1;
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$nepalidate->year.'-'.$nepalidate->month.'-'.$nepalidate->date.'</td>';
                    $html .='<td>'.$sales->fldbillno.'</td>';
                    $html .='<td>'.strtoupper($sales->patientname).'</td>';
                    $html .='<td>'.$sales->fldpannumber.'</td>';
                    $html .='<td>'.$sales->flditemname.'</td>';
                    $html .='<td>'.$sales->flditemqty.'</td>';
                    $html .='<td>'.Helpers::numberFormat(($sales->flditemrate*$sales->flditemqty)).'</td>';
                    if($sales->fldtaxamt != 0){
                        $html .='<td></td>';
                        $html .='<td>'.Helpers::numberFormat(($sales->fldditemamt-$sales->fldtaxamt-$sales->flddiscamt)).'</td>';
                        $html .='<td>'.Helpers::numberFormat($sales->fldtaxamt).'</td>';
                    }else{
                        $html .='<td>'.Helpers::numberFormat($sales->fldditemamt).'</td>';
                        $html .='<td></td>';
                        $html .='<td></td>';
                    }
                    $html .='<td>'.Helpers::numberFormat($sales->flditemrate).'</td>';
                    $html .='<td></td>';
                    $html .='</tr>';
                }
                $html .= '<tr><td colspan="20">' . $salesData->appends(request()->all())->links() . '</td></tr>';
            }

           $data['html'] = $html;
           return $data;

        }catch(\Exception $e){
            dd($e);
            \Log::info($e->getMessage());
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function exportSalesData(Request $request){
        try{
           $salesData = DB::table('tblpatbilling as pb')
                            ->select('pb.fldtime', 'pb.fldbillno', 'p.fldpannumber', 'pb.flditemname', 'pb.flditemqty', 'pb.flditemrate', 'pb.fldditemamt', 'pb.fldtaxamt', 'pb.flddiscamt', 'pb.fldcomp',\DB::raw('CONCAT(p.fldptnamefir," " ,p.fldptnamelast) as patientname'))
                            ->join('tblencounter as e','e.fldencounterval','pb.fldencounterval')
                            ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
                            ->where('pb.fldtime','>',$request->eng_from_date.' 00:00:00')
                            ->where('pb.fldtime','<',$request->eng_to_date.' 23:59:59')
                            ->where('pb.fldcomp',$request->department)
                            ->where('pb.fldsave','1')
                            ->whereNotNull('pb.fldbillno')
                            ->orderBy('pb.fldtime','ASC')
                            ->get();
            $data['salesdata'] = $salesData;
            $data['month'] = '';
            $fromdatevalue = Helpers::dateEngToNepdash($request->eng_from_date);
            $nepalifromdate = $fromdatevalue->year . '-' . $fromdatevalue->month . '-' . $fromdatevalue->date;
            $nepalifrommonth = Helpers::getMonthFromNepaliDate($fromdatevalue->month);

            $todatevalue = Helpers::dateEngToNepdash($request->eng_to_date);
            $nepalitodate = $todatevalue->year . '-' . $todatevalue->month . '-' . $todatevalue->date;
            $nepalitomonth = Helpers::getMonthFromNepaliDate($todatevalue->month);

            if($nepalifrommonth == $nepalitomonth){
                $data['month'] = $nepalifrommonth;
            }
            $data['taxduration'] = DB::table('tblyear')->select('fldname')->where('fldfirst','<=',$request->eng_from_date)->where('fldlast','>=', $request->eng_from_date)->first();
            // dd($data['taxduration']);
            $data['year'] = $fromdatevalue->year;
            return view('billing::pdf.sales-report', $data);
        }catch(\Exception $e){
            dd($e);
            \Log::info($e->getMessage());
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }


    public function exportSalesDataToExcel(Request $request){
        $export = new SalesReportExport($request->all());
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'VatReport.xlsx');
    }

}
