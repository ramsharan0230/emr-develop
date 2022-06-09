<?php

namespace Modules\DepartmentWiseRequestReport\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Department;
use App\Exports\DeptWiseRequestReportExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;

class DepartmentWiseRequestReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(!$request->has('patientType') || ($request->has('patientType') && $request->patientType == "outpatient")){
            $data['patientType'] = $patientType = "outpatient";
        }else{
            $data['patientType'] = $patientType = "inpatient";
        }
        $user = CogentUsers::where('id', Auth::guard("admin_frontend")->id())
            ->with(['department' => function ($query) use ($patientType){
                $query->when($patientType != null && $patientType == "outpatient", function ($q){
                            return $q->where('fldcateg', 'Consultation');
                        })
                        ->when($patientType != null && $patientType == "inpatient", function ($q){
                            return $q->whereIn('fldcateg', ['Patient Ward','Emergency']);
                        });
            }])
            ->first();

        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            if($patientType == "outpatient"){
                $data['departments'] = Department::where('fldcateg', 'Consultation')->with('users')->get();
            }else{
                $data['departments'] = Department::whereIn('fldcateg', ['Patient Ward','Emergency'])->with('users')->get();
            }
        } else {
            $data['departments'] = $user->department;
        }

        if ($request->has('request_date')) {
            $data['request_date'] = $request_date = $request->request_date;
        } else {
            $data['request_date'] = $request_date = Carbon::now()->format('Y-m-d');
        }

        if ($request->has('billing')) {
            $data['billing'] = $billing = $request->billing;
        } else {
            $data['billing'] = $billing = null;
        }

        if ($request->has('filter')) {
            $data['filter'] = true;
        } else {
            $data['filter'] = false;
        }

        if ($request->has('dept')) {
            if($request->dept == "%"){
                if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
                    if($patientType == "outpatient"){
                        $data['dept'] = Department::where('fldcateg', 'Consultation')->with('users')->get();
                    }else{
                        $data['dept'] = Department::whereIn('fldcateg', ['Patient Ward','Emergency'])->with('users')->get();
                    }
                } else {
                    $data['dept'] = $user->department;
                }
            }else{
                $data['dept'] = Department::where('flddept', $request->dept)->with('users')->get();
            }
            $data['request_dept'] = $request_dept = $request->dept;
        } else {
            $data['dept'] = $data['departments'][0];
            $data['request_dept'] = $request_dept = null;
        }

        $data['billing_mode'] = BillingSet::all();

        $data['departmentWiseData'] = Department::leftJoin('department_users','tbldepartment.fldid','=','department_users.department_id')
                            ->leftJoin('users','users.id','=','department_users.user_id')
                            ->leftJoin('tblpatbilling','tblpatbilling.fldorduserid','=','users.flduserid')
                            ->leftJoin('tblencounter','tblpatbilling.fldencounterval','=','tblencounter.fldencounterval')
                            ->leftJoin('tblpatientinfo','tblpatientinfo.fldpatientval','=','tblencounter.fldpatientval')
                            ->when($request_dept != null && $request_dept != "%", function ($query) use ($request_dept){
                                return $query->where('tbldepartment.flddept', $request_dept);
                            })
                            ->where('tbldepartment.fldcateg', 'Consultation')
                            ->whereIn('tblpatbilling.flditemtype',["Diagnostic Tests","Radio Diagnostics"])
                            ->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldordtime,'%Y-%m-%d'))"), "=", $request_date)
                            ->when($billing != null, function ($q) use ($billing){
                                return $q->where('tblencounter.fldbillingmode', $billing);
                            })
                            ->get(['tbldepartment.flddept as flddept','users.flduserid','tblpatbilling.fldencounterval as encounter','tblpatbilling.fldorduserid as fldorduserid',
                                'tblpatientinfo.fldptsex as patientGender','tblpatbilling.flditemtype as flditemtype',
                                'tblencounter.fldcurrlocat as fldcurrlocat'])
                            ->groupBy(['fldcurrlocat','flduserid','flditemtype','patientGender','encounter'])->toArray();

        $data['departmentWisePharmacyData'] = Department::leftJoin('department_users','tbldepartment.fldid','=','department_users.department_id')
                            ->leftJoin('users','users.id','=','department_users.user_id')
                            ->leftJoin('tblpatdosing','tblpatdosing.flduserid_order','=','users.flduserid')
                            ->leftJoin('tblencounter','tblpatdosing.fldencounterval','=','tblencounter.fldencounterval')
                            ->leftJoin('tblpatientinfo','tblpatientinfo.fldpatientval','=','tblencounter.fldpatientval')
                            ->when($request_dept != null && $request_dept != "%", function ($query2) use ($request_dept){
                                return $query2->where('tbldepartment.flddept', $request_dept);
                            })
                            ->where('tbldepartment.fldcateg', 'Consultation')
                            ->where(DB::raw("(STR_TO_DATE(tblpatdosing.fldtime_order,'%Y-%m-%d'))"), "=", $request_date)
                            ->when($billing != null, function ($q) use ($billing){
                                return $q->where('tblencounter.fldbillingmode', $billing);
                            })
                            ->get(['tbldepartment.flddept as flddept','users.flduserid','tblpatdosing.fldencounterval as encounter','tblpatdosing.flduserid_order as fldorderuserid',
                                'tblpatientinfo.fldptsex as patientGender','tblpatdosing.flditemtype as flditemtype',
                                'tblpatdosing.flditem as flditem','tblencounter.fldcurrlocat as fldcurrlocat','tblpatdosing.fldtime_order as fldtime_order'])
                            ->groupBy(['fldcurrlocat','flduserid','patientGender','encounter'])->toArray();

        $data['departmentWiseConsultData'] = Department::leftJoin('department_users','tbldepartment.fldid','=','department_users.department_id')
                            ->leftJoin('users','users.id','=','department_users.user_id')
                            ->leftJoin('tblconsult','tblconsult.fldorduserid','=','users.flduserid')
                            ->leftJoin('tblencounter','tblconsult.fldencounterval','=','tblencounter.fldencounterval')
                            ->leftJoin('tblpatientinfo','tblpatientinfo.fldpatientval','=','tblencounter.fldpatientval')
                            ->when($request_dept != null && $request_dept != "%", function ($query) use ($request_dept){
                                return $query->where('tbldepartment.flddept', $request_dept);
                            })
                            ->where('tbldepartment.fldcateg', 'Consultation')
                            ->where(DB::raw("(STR_TO_DATE(tblconsult.fldconsulttime,'%Y-%m-%d'))"), "=", $request_date)
                            ->when($billing != null, function ($q) use ($billing){
                                return $q->where('tblencounter.fldbillingmode', $billing);
                            })
                            ->get(['tbldepartment.flddept as flddept','users.flduserid','tblconsult.fldencounterval as encounter','tblconsult.fldorduserid as fldorduserid',
                                'tblconsult.fldstatus as fldstatus','tblpatientinfo.fldptsex as patientGender','tblencounter.fldcurrlocat as fldcurrlocat'])
                            ->groupBy(['fldcurrlocat','flduserid','fldstatus','patientGender','encounter'])->toArray();

        if(!$request->has('filter') || ($request->has('filter') && $request->filter == "filter")){
            return view('departmentwiserequestreport::department-wise-request-report', $data);
        }elseif($request->has('filter') && $request->filter == "pdf"){
            return view('departmentwiserequestreport::department-wise-request-pdf', $data);
        }
    }

    public function getDepartmentLists(Request $request){
        if($request->patientType == "outpatient"){
            $patientType = "outpatient";
        }else{
            $patientType = "inpatient";
        }
        $user = CogentUsers::where('id', Auth::guard("admin_frontend")->id())
            ->with(['department' => function ($query) use ($patientType){
                $query->when($patientType != null && $patientType == "outpatient", function ($q){
                            return $q->where('fldcateg', 'Consultation');
                        })
                        ->when($patientType != null && $patientType == "inpatient", function ($q){
                            return $q->whereIn('fldcateg', ['Patient Ward','Emergency']);
                        });
            }])
            ->first();

        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            if($patientType == "outpatient"){
                $departments = Department::where('fldcateg', 'Consultation')->with('users')->get();
            }else{
                $departments = Department::whereIn('fldcateg', ['Patient Ward','Emergency'])->with('users')->get();
            }
        } else {
            $departments = $user->department;
        }

        $options = '<option value="%">%</option>';
        foreach($departments as $department){
            $options .= '<option value="'.$department->flddept.'">'.$department->flddept.'</option>';
        }

        return response()->json([
            'success' => [
                'status' => true,
                'options' => $options,
            ]
        ]);
    }

    public function exportDeptReport(Request $request){
        if($request->has('billing')){
            $billing = $request->billing;
        }else{
            $billing = null;
        }
        $export = new DeptWiseRequestReportExport($request->patientType,$request->request_date,$billing,$request->filter,$request->dept);
        ob_end_clean();
        ob_start();
        return Excel::download($export, ucfirst($request->patientType).'-'.$request->request_date.'-DeptRequestReport.xlsx');
    }
}
