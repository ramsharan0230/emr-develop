<?php

namespace Modules\Laboratory\Http\Controllers;

use App\Test;
use App\PatLabTest;
use App\PatBilling;
use App\Utils\Helpers;
use App\HospitalDepartmentUsers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\Options;

/**
 * Class TestReceivingController
 * @package Modules\Laboratory\Http\Controllers
 */
class TestReceivingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = [
            'categories' => Helpers::getPathoCategory('Test'),
            'labTestPatients' => $this->_getAllTest($request),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
        return view('laboratory::tests.receiving', $data);
    }

    private function _getAllTest(Request $request)
    {
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');
        $encounterId = $request->get('encounter_id');
        $patient_id = $request->get('patient_id');
        $department = $request->get('department');
        $category = $request->get('category');
        $name = $request->get('patient_name');
        $patient_number = $request->get('patient_number');
        $showOtherLocation = $request->get('showOtherLocation', 'false');

        if(Options::get('test_receiving')=='Yes'){
            $data = PatLabTest::select("fldencounterval", "fldsampleid", "flduserid_sample", "fldtime_sample")
                ->with([
                    'patientEncounter:fldencounterval,fldpatientval,fldrank',
                    'patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank,fldptbirday,fldptsex',
                    'patientEncounter.consultant:fldencounterval,fldconsultname',
                ])->where([
                    ['fldstatus', '=', 'Sampled'],
                    ['fldsave_sample', '=', '1'],
                    ["fldtime_sample", ">=", "$fromdate 00:00:00"],
                    ["fldtime_sample", "<=", "$todate 23:59:59.999"],
                    // ['fldcomp_sample', 'like', Helpers::getCompName()],
                ]);
            // if ($showOtherLocation == 'false'){
            //     $data = $data->where(function($query) {
            //         $query->where('fldsamplelocation', 'Hospital')->orWhere('fldsamplelocation', '')->orWhereNull('fldsamplelocation');
            //     });
            // }
            // if ($category)
            //     $data = $data->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());
            if ($department){
                $data = $data->where('fldcomp_sample',$department);
            }
            if ($name){
                $data = $data->whereHas('patientEncounter.patientInfo', function ($q) use ($name) {
                    $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
                });
            }
            if ($patient_id){
                $data = $data->whereHas('patientEncounter.patientInfo', function ($q) use ($patient_id) {
                    $q->where('fldpatientval', 'like', '%' . $patient_id . '%');
                });
            }
            if ($patient_number){
                $data = $data->whereHas('patientEncounter.patientInfo', function ($q) use ($patient_number) {
                    $q->where('fldptcontact', 'like', '%' . $patient_number . '%');
                });
            }
            if ($encounterId){
                $data = $data->where('fldencounterval', 'LIKE', $encounterId . '%');
            }
            return $data->groupBy('fldencounterval')->orderBy('fldtime_sample','desc')->get();
        }else{
            return collect();
        }
    }

    public function getPatientDetail(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;
        // $showOtherLocation = $request->get('showOtherLocation', 'false');
        $samples = PatLabTest::select(
                                    'tblpatlabtest.fldencounterval', 
                                    'tblpatlabtest.fldid', 
                                    'tblpatlabtest.fldsampleid', 
                                    'tblpatlabtest.fldgroupid', 
                                    'tblpatlabtest.fldtestid', 
                                    'tblpatlabtest.fldstatus', 
                                    'tblpatlabtest.fldsave_report', 
                                    'tblpatlabtest.fldabnormal', 
                                    'tblpatlabtest.flvisible', 
                                    'tblpatlabtest.fldsampletype', 
                                    'tblpatlabtest.fldmethod', 
                                    'tblpatlabtest.fldtest_type', 
                                    'tblpatlabtest.fldcondition', 
                                    'tblpatlabtest.fldcomment', 
                                    'tblpatlabtest.fldtime_sample', 
                                    'tblpatlabtest.fldtime_report', 
                                    'tblpatlabtest.fldreportquali', 
                                    'tblpatlabtest.fldreportquanti',
                                    'tblpatbilling.flditemname'
                                )
                                ->join('tblpatbilling','tblpatbilling.fldid','=','tblpatlabtest.fldgroupid')
            ->where([
                ['tblpatlabtest.'.$field, '=', $value],
                ['tblpatlabtest.fldstatus', '=', 'Sampled']
            ])->get();

        return response()->json(
            compact('samples')
        );
    }

    public function updateTest(Request $request){
        $patbilling_id = PatLabTest::select('fldgroupid')->whereIn('fldid',$request->testids)->groupBy('fldgroupid')->pluck('fldgroupid')->toArray();
        // $patbilling_data = PatBilling::where([
        //     ['flditemtype', '=', 'Diagnostic Tests'],
        //     ['fldsample', '=', 'Sampled'],
        //     ['fldencounterval', '=', $patlabtest_data->fldencounterval],
        //     ['flditemqty', '>', 'fldretqty'],
        // ])->where(function($query) {
        //     $query->where('fldsave', '1')
        //         ->orWhere(function($q) {
        //             $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
        //         });
        // })->pluck('fldid')->toArray();
        try{
            \DB::beginTransaction();
            PatLabTest::whereIn('fldid', $request->testids)->update([
                'fldstatus' => $request->status_value,
                'fldtime_sample' => date('Y-m-d H:i:s')
            ]);
            if($request->status_value=='Rejected'){
                PatLabTest::destroy($request->testids);
            }
            PatBilling::whereIn('fldid', $patbilling_id)->update([
                'fldsample' => $request->status_value,
                'fldtime' => date('Y-m-d H:i:s')
            ]);
            \DB::commit();
            if($request->status_value=='Received'){
                return response()->json([
                    'status' => TRUE,
                    'message' => 'successfully Accepted the Information.',
                ]);
            }else{
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Rejected the Information.',
                ]);
            }
        } catch (Exception $e) {
            // \DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }
   
}
