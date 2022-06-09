<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\PatBillDetail;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use Auth;
use Carbon\Carbon;
use App\Patientcreditcolor;
use DB;
use Excel;
use App\Exports\PatientCreditExport;

class PatientCreditReportController extends Controller
{
    public function index(Request $request) {
        $department=$request->department;
        $patient_name=$request->patient_name;
        $patient_id=$request->patient_id;
        $encounter_id=$request->encounter_id;
        $patient_number=$request->patient_number;
        $amount_type=$request->amount_type;
        $patient_credit_color=Patientcreditcolor::first();
        // dd($department);

        
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $hospital_department = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id',Auth::guard('admin_frontend')->user()->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $hospital_department = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->take(20)->get();
        }
        $query=DB::table('tblpatbilldetail as tptbd')
                                ->select(
                                    'tptbd.fldid',
                                    'tptbd.fldtime',
                                    'tptbd.fldbillno',
                                    'tpi.fldpatientval',
                                    'tptbd.fldencounterval',
                                    'tpi.fldptnamefir',
                                    'tpi.fldptnamelast',
                                    'tpi.fldptbirday',
                                    'tpi.fldptsex',
                                    'tpi.fldptcontact',
                                    'tptbd.fldcurdeposit'
                                    )
                                ->join('tblencounter as tent','tptbd.fldencounterval','=','tent.fldencounterval')
                                ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                                ->where('tptbd.fldbilltype','Credit');
        if($department){
            $query->where('tptbd.fldcomp', $department);
        }
        if($patient_name){
            $query->whereRaw('concat(tpi.fldptnamefir," ",tpi.fldptnamelast) like ?', "%{$patient_name}%");
        }
        if($patient_id){
            $query->where('tpi.fldpatientval', 'like', '%'.$patient_id.'%');
        }
        if($encounter_id){
            $query->where('tptbd.fldencounterval', 'like', '%'.$encounter_id.'%');
        }
        if($patient_number){
            $query->where('tpi.fldptcontact', $patient_number);
        }
        if($amount_type==0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit <= 0  GROUP BY fldencounterval)');
        }
        if($amount_type==1){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit > 0  GROUP BY fldencounterval)');
        }
        if($amount_type!=0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit <= 0  GROUP BY fldencounterval)');
        }
        $patient_credit_report=$query
        ->where('tptbd.fldcurdeposit', '!=' , 0)
        ->orderBy('tptbd.fldencounterval','desc')
        ->paginate(10);
        return view('reports::patient-credit-report.index',compact(
                                                            'patient_credit_report',
                                                            'hospital_department',
                                                            'department',
                                                            'patient_name',
                                                            'patient_id',
                                                            'encounter_id',
                                                            'patient_number',
                                                            'amount_type',
                                                            'patient_credit_color'
                                                        ));
    }

    public function patientCreditPdfReport(Request $request){
        $department=$request->department;
        $patient_name=$request->patient_name;
        $patient_id=$request->patient_id;
        $encounter_id=$request->encounter_id;
        $patient_number=$request->patient_number;
        $amount_type=$request->amount_type;
        $patient_credit_color=Patientcreditcolor::first();

        $query=DB::table('tblpatbilldetail as tptbd')
                                ->select(
                                    'tptbd.fldid',
                                    'tptbd.fldtime',
                                    'tptbd.fldbillno',
                                    'tpi.fldpatientval',
                                    'tptbd.fldencounterval',
                                    'tpi.fldptnamefir',
                                    'tpi.fldptnamelast',
                                    'tpi.fldptbirday',
                                    'tpi.fldptsex',
                                    'tpi.fldptcontact',
                                    'tptbd.fldcurdeposit'
                                    )
                                ->join('tblencounter as tent','tptbd.fldencounterval','=','tent.fldencounterval')
                                ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                                ->where('tptbd.fldbilltype','Credit');
        if($department){
            $query->where('tptbd.fldcomp', $department);
        }
        if($patient_name){
            $query->whereRaw('concat(tpi.fldptnamefir," ",tpi.fldptnamelast) like ?', "%{$patient_name}%");
        }
        if($patient_id){
            $query->where('tpi.fldpatientval', 'like', '%'.$patient_id.'%');
        }
        if($encounter_id){
            $query->where('tptbd.fldencounterval', 'like', '%'.$encounter_id.'%');
        }
        if($patient_number){
            $query->where('tpi.fldptcontact', $patient_number);
        }
        if($amount_type==0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit < 0  GROUP BY fldencounterval)');
        }
        if($amount_type==1){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit > 0  GROUP BY fldencounterval)');
        }
        if($amount_type!=0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit < 0  GROUP BY fldencounterval)');
        }
        $patient_credit_report=$query
        ->orderBy('tptbd.fldencounterval','desc')
        ->get();
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        return view('reports::patient-credit-report.pdf.patient-credit-pdf-report',compact('patient_credit_report','userid','patient_credit_color','amount_type'));
    }

    public function patientCreditExcelReport(Request $request){
        $department=$request->department ?? '';
        $patient_name=$request->patient_name ?? '';
        $patient_id=$request->patient_id ?? '';
        $encounter_id=$request->encounter_id ?? '';
        $patient_number=$request->patient_number ?? '';
        $amount_type=$request->amount_type ?? '';

        $export = new PatientCreditExport($department,$patient_name,$patient_id,$encounter_id,$patient_number,$amount_type);
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'patient-credit-report.xlsx');
    }

    public function showRemarks($encounter_id){
        $remarks=PatBillDetail::select('fldid','fldencounterval','fldbillno','remarks')->where('fldid',$encounter_id)->first();
        return view('reports::patient-credit-report.modal.remarks_data',compact('remarks'));
    }

    public function insertRemarks(Request $request,$encounter_id){
        PatBillDetail::where('fldid', $encounter_id)
            ->update([
                'remarks' => $request->remarks
            ]);
        return redirect()->back();
    }
}
