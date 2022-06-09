<?php

namespace Modules\Laboratory\Http\Controllers;

use App\Test;
use App\PatLabTest;
use App\PatBilling;
use App\UnsampledTest;
use App\Utils\Helpers;
use App\HospitalDepartmentUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\Options;
use App\Exports\UnsampledTestExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class TestReceivingController
 * @package Modules\Laboratory\Http\Controllers
 */
class TestUnsamplingRequestController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $patient_id=$request->patient_id;
        $encounter_id=$request->encounter_id;
        $status=$request->status;
        // dd($status);
        $eng_from_date=$request->eng_from_date??date('Y-m-d');
        $eng_to_date=$request->eng_to_date??date('Y-m-d');
        $query=DB::table('tbl_unsampled_test as tut')
                                ->select(
                                    'tut.bill_no',
                                    'tpb.fldbillno',
                                    'tut.testid',
                                    'tut.encounter_id',
                                    'tut.user_id',
                                    'tut.fldmethod',
                                    'tut.fldstatus',
                                    'tut.fldgroupid',
                                    'tut.date',
                                    'tpi.fldpatientval',
                                    'tpi.fldptnamefir',
                                    'tpi.fldmidname',
                                    'tpi.fldptnamelast',
                                    'tpi.fldptbirday',
                                    'tpi.fldptsex',
                                    'tpi.fldptcontact'
                                    )
                                ->join('tblencounter as tent','tut.encounter_id','=','tent.fldencounterval')
                                ->join('tblpatbilling as tpb','tpb.fldid','=','tut.fldgroupid')
                                ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                                ->whereDate('tut.date','>=', "$eng_from_date 00:00:00")->whereDate('tut.date','<=', "$eng_to_date 23:59:59.999");
        if($encounter_id){
            $query = $query->where('tut.encounter_id',$encounter_id);
        }
        if($patient_id){
            $query = $query->where('tpi.fldpatientval',$patient_id);
        }
        if($status){
            $query = $query->where('tut.fldstatus',$status);
        }
        $unsampled_test=$query->groupBy('tut.encounter_id')->get();
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('laboratory::tests.unsampled_request',compact('unsampled_test','date'));
    }

    public function addUnsampledPatlabtest(Request $request){
        $billing_group=array_unique($request->billing_groupid);
        $encounter_id = $request->get('encounter_id');
        // $rejected_checkbox=$request->get('rejected_checkbox');
        try {
            DB::beginTransaction();
            PatLabTest::destroy($request->testids);
            if(Options::get('unsample_lists')=='Yes'){
                foreach ($request->test_value as $key => $test_value) {
                    UnsampledTest::create([
                        'encounter_id' => $encounter_id,
                        'testid' => $test_value['fldtest'],
                        'fldmethod' => NULL,
                        'fldgroupid' => $test_value['fldbillgrouping'],
                        'fldstatus' => 'Unsampled',
                        'bill_no' => 'NONE',
                        'user_id' =>  Auth::guard('admin_frontend')->user()->flduserid,
                        'date' => date('Y-m-d H:i:s')
                    ]);
                }
                PatBilling::whereIn('fldid', $billing_group)->update(['fldsample' => 'Unsampled']);
            }else{
                foreach ($request->test_value as $key => $test_value) {
                    UnsampledTest::create([
                        'encounter_id' => $encounter_id,
                        'testid' => $test_value['fldtest'],
                        'fldmethod' => NULL,
                        'fldgroupid' => $test_value['fldbillgrouping'],
                        'fldstatus' => 'Removed',
                        'bill_no' => 'NONE',
                        'user_id' =>  Auth::guard('admin_frontend')->user()->flduserid,
                        'date' => date('Y-m-d H:i:s')
                    ]);
                }
                PatBilling::whereIn('fldid', $billing_group)->update(['fldsample' => 'Removed']);
            }
            DB::commit();
            return response()->json(['status'=>true,'message'=>'successfully inserted the data']);
        }catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        //         Helpers::logStack(["Order of bill no. " . $bill->fldbillno, "Lab", ], ['encounter_id' => $bill->fldencounterval, 'bill_no' => $bill->fldbillno]);
    }

    public function pdfUnsampledTest(Request $request){
        $patient_id=$request->patient_id;
        $encounter_id=$request->encounter_id;
        $status=$request->status;
        $eng_from_date=$request->eng_from_date??date('Y-m-d');
        $eng_to_date=$request->eng_to_date??date('Y-m-d');
        $userid = Auth::guard('admin_frontend')->user()->flduserid;
        $query=DB::table('tbl_unsampled_test as tut')
                                ->select(
                                    'tut.bill_no',
                                    'tpb.fldbillno',
                                    'tut.testid',
                                    'tut.encounter_id',
                                    'tut.user_id',
                                    'tut.fldmethod',
                                    'tut.fldstatus',
                                    'tut.fldgroupid',
                                    'tut.date',
                                    'tpi.fldpatientval',
                                    'tpi.fldptnamefir',
                                    'tpi.fldmidname',
                                    'tpi.fldptnamelast',
                                    'tpi.fldptbirday',
                                    'tpi.fldptsex',
                                    'tpi.fldptcontact'
                                    )
                                ->join('tblencounter as tent','tut.encounter_id','=','tent.fldencounterval')
                                ->join('tblpatbilling as tpb','tpb.fldid','=','tut.fldgroupid')
                                ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                                ->whereDate('tut.date','>=', "$eng_from_date 00:00:00")->whereDate('tut.date','<=', "$eng_to_date 23:59:59.999");
        if($encounter_id){
            $query = $query->where('tut.encounter_id',$encounter_id);
        }
        if($patient_id){
            $query = $query->where('tpi.fldpatientval',$patient_id);
        }
        if($status){
            $query = $query->where('tut.fldstatus',$status);
        }
        $unsampled_test=$query->groupBy('tut.encounter_id')->get();
        return view('laboratory::pdf.unsampled_request_pdf',compact('unsampled_test','userid'));
    }

    public function exportExcelUnsampledTest(Request $request){
        $patient_id=$request->patient_id;
        $encounter_id=$request->encounter_id;
        $status=$request->status;
        $eng_from_date=$request->eng_from_date??date('Y-m-d');
        $eng_to_date=$request->eng_to_date??date('Y-m-d');

        $export = new UnsampledTestExport($eng_from_date,$eng_to_date,$status,$patient_id,$encounter_id);
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'unsampled-test-report.xlsx');
    }

    public function testList(Request $request){
        $unsampled_test=DB::table('tbl_unsampled_test as tut')
                                ->select(
                                    'tut.bill_no',
                                    'tut.testid',
                                    'tut.encounter_id'
                                    )
                                ->where('tut.encounter_id',$request->encounterid)->get();
        return view('laboratory::modal.ajax_test_list',compact('unsampled_test'));
    }

    public function changeUnsampledStatus(Request $request){
        // $patlabtest_data = PatLabTest::select('fldencounterval')->whereIn('fldid',$request->testids)->first();
        $patbilling_data = PatBilling::where([
            ['flditemtype', '=', 'Diagnostic Tests'],
            ['fldsample', '=', 'Unsampled'],
            ['fldencounterval', '=', $request->encounter_id],
            ['flditemqty', '>', 'fldretqty'],
        ])->where(function($query) {
            $query->where('fldsave', '1')
                ->orWhere(function($q) {
                    $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
                });
        })->pluck('fldid')->toArray();
        try{
            \DB::beginTransaction();
            UnsampledTest::where('encounter_id', $request->encounter_id)->update([
                'fldstatus' => 'Removed',
                'date' => date('Y-m-d H:i:s')
            ]);

            PatBilling::whereIn('fldid', $patbilling_data)->update([
                'fldsample' => 'Removed',
                'fldtime' => date('Y-m-d H:i:s')
            ]);
            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'successfully Accepted the Information.',
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }
   
}
