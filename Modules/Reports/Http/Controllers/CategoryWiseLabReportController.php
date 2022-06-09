<?php

namespace Modules\Reports\Http\Controllers;

use App\Encounter;
use App\Exports\CategoryWiseLabReportExport;
use App\PatLabTest;
use App\Test;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Excel;
use PDF;

class CategoryWiseLabReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function categoryWiseLabReport(Request $request)
    {
        $data['testCategories'] = Test::select('fldcategory')->groupBy('fldcategory')->get();
        $data['htmlHead'] = $htmlHead = "";
        $data['htmlBody'] = $htmlBody = "";
        if($request->has('testCategory')){
            $data['testDatas'] = $testDatas = Test::where('fldcategory','like', $request->testCategory)
                                    ->with('subtests')->get();
            $htmlHead .= '<tr>
                            <th rowspan="2">&nbsp;</th>
                            <th rowspan="2">Patient Details</th>';
            foreach($testDatas as $testData){
                if(count($testData->subtests)>0){
                    $htmlHead .= '<th colspan="'.count($testData->subtests).'">'.$testData->fldtestid.'</th>';
                }else{
                    $htmlHead .= '<th rowspan="2">'.$testData->fldtestid.'</th>';
                }
            }
            $htmlHead .= '</tr>';
            $htmlHead .= '<tr>';
            foreach($testDatas as $testData){
                if(count($testData->subtests)>0){
                    foreach($testData->subtests as $subTest){
                        $htmlHead .= '<th>'.$subTest->fldsubtest.'</th>';
                    }
                }
            }
            $htmlHead .= '</tr>';
            $testDataIds = Test::where('fldcategory','like',$request->testCategory)
                                ->pluck('fldtestid')
                                ->toArray();
            $patLabTestData = PatLabTest::select('fldtestid', 'fldreportquali', 'fldreportquanti', 'fldencounterval','fldsampleid', 'fldtestunit')
                                        ->whereIn('fldtestid',$testDataIds)
                                        ->when($request->fromDate != null, function ($q) use ($request){
                                            return $q->where('fldtime_report','>=',$request->fromDate);
                                        })
                                        ->when($request->toDate != null, function ($q) use ($request){
                                            return $q->where('fldtime_report','<=',$request->toDate);
                                        })
                                        ->with(
                                            'patientEncounter:fldencounterval,fldpatientval,fldrank',
                                            'patientEncounter.patientInfo:fldptnamefir,fldmidname,fldptnamelast,fldrank,fldpatientval',
                                            'subTest:fldtestid,fldsubtest',
                                            'testLimit:fldtestid,fldsiunit,fldmetunit'
                                            )
                                        ->get()
                                        ->groupBy(['fldencounterval','fldsampleid','fldtestid']);
            if($request->has('submitType') && $request->submitType == "pdf"){
                $allData = $patLabTestData->toArray();
            }else{
                $options = ['path' => route('report.lab-category-wise',['fromDate' => $request->fromDate,'toDate' => $request->toDate,'testCategory' => $request->testCategory, 'submitType' => "refresh"])];
                $data['paginatedData'] = $paginatedData = $this->paginate($patLabTestData,$options);
                $allData = $paginatedData->toArray()['data'];
            }
            $i = 1;
            foreach($allData as $encounter_key=>$patLabTest){
                $patient_name = Encounter::select('fldencounterval','fldpatientval')->where('fldencounterval',$encounter_key)->with('patientInfo:fldptnamefir,fldmidname,fldptnamelast,fldrank,fldpatientval')->first();
                foreach($patLabTest as $sample_key=>$sample){
                    if(isset($patient_name->patientInfo)){
                        if(Options::get('system_patient_rank')  == 1 && (isset($patient_name->patientInfo)) && (isset($patient_name->patientInfo->fldrank) )){ 
                            $name = $patient_name->patientInfo->fldrank.' '.$patient_name->patientInfo->fldptnamefir.' '.$patient_name->patientInfo->fldmidname.' '.$patient_name->patientInfo->fldptnamelast;
                        }else{
                            $name = $patient_name->patientInfo->fldptnamefir.' '.$patient_name->patientInfo->fldmidname.' '.$patient_name->patientInfo->fldptnamelast;
                        }
                    }else{
                        $name = "N/A";
                    }
                    $htmlBody .= '<tr>
                                        <td>'.$i.'</td>
                                        <td>('.$encounter_key.') '.$name.' ('.$sample_key.')</td>';
                    foreach($testDatas as $testData){
                        if(count($testData->subtests)>0){
                            foreach($testData->subtests as $sub_test){
                                if(array_key_exists($testData->fldtestid,$patLabTest)){
                                    if(count($sample[$testData->fldtestid][0]['sub_test'])){
                                        $subTestPresent = false;
                                        foreach($sample[$testData->fldtestid][0]['sub_test'] as $st){
                                            if($st['fldsubtest'] == $sub_test->fldsubtest){
                                                if($st['fldreport'] != null){
                                                    $htmlBody .= '<td>'.$st['fldreport'].'</td>';
                                                }else{
                                                    $htmlBody .= '<td></td>';
                                                }
                                                $subTestPresent = true;
                                            }
                                        }
                                        if(!$subTestPresent){
                                            $htmlBody .= '<td></td>';
                                        }
                                    }else{
                                        $htmlBody .= '<td></td>';
                                    }
                                }else{
                                    $htmlBody .= '<td></td>';
                                }
                            }
                        }else{
                            if(array_key_exists($testData->fldtestid,$sample)){
                                if(count($sample[$testData->fldtestid][0]['test_limit'])>0){
                                    if($sample[$testData->fldtestid][0]['fldtestunit'] == "SI"){
                                        $unit = $sample[$testData->fldtestid][0]['test_limit'][0]['fldsiunit'];
                                    }else{
                                        $unit = $sample[$testData->fldtestid][0]['test_limit'][0]['fldmetunit'];
                                    }
                                }else{
                                    $unit = "";
                                }
                                $resultValue = $sample[$testData->fldtestid][0]['fldreportquali'] ?? $sample[$testData->fldtestid][0]['fldreportquanti'] ?? "";
                                $htmlBody .= '<td>'.$resultValue.' '.$unit.'</td>';
                            }else{
                                $htmlBody .= '<td></td>';
                            }
                        }
                    }
                    $htmlBody .= '</tr>';
                    $i++;
                }
            }
            $data['fromDate'] = $request->fromDate;
            $data['toDate'] = $request->toDate;
            $data['selectedTestCategory'] = $request->testCategory;
        }else{
            $data['fromDate'] = Carbon::now()->format('Y-m-d');
            $data['toDate'] = Carbon::now()->format('Y-m-d');
            $data['selectedTestCategory'] = "";
        }
        $data['htmlHead'] = $htmlHead;
        $data['htmlBody'] = $htmlBody;

        if(!$request->has('submitType') || ($request->has('submitType') && $request->submitType == "refresh")){
            return view('reports::CategoryWiseLabReport.index',$data);
        }else{
            $data['testCategory'] = $request->testCategory;
            return view('reports::CategoryWiseLabReport.categorywise-lab-report-pdf',$data);
            // $pdf = PDF::loadView('reports::CategoryWiseLabReport.categorywise-lab-report-pdf', 
            //             [
            //                 'htmlHead' => $htmlHead,
            //                 'htmlBody' => $htmlBody
            //             ])
            //             ->setPaper('a4', 'landscape');
            // return $pdf->stream();
        }
    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function exportLabCatReportCsv(Request $request){
        $export = new CategoryWiseLabReportExport($request->testCategory,$request->fromDate,$request->toDate);
        ob_end_clean();
        ob_start();
        return Excel::download($export, $request->testCategory.'Report.xlsx');
    }
}
