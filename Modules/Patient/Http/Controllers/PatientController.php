<?php

namespace Modules\Patient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Consult;
use App\Encounter;
use App\PatientInfo;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\HospitalDepartmentUsers;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;
use Illuminate\Database\Eloquent\Builder;
class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {
        $data['patient_id'] = NULL;
        $data['departments'] = Helpers::getDepartmentByCategory('Consultation');
        $data['consultantList'] = Helpers::getConsultantList();
        $number_of_days = Options::get('followup_days');

        $patients = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by','fldfollowdate')
        ->whereNull('fldfollowdate')
        ->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'allConsultant:fldencounterval,fldconsultname,flduserid',
            // 'consultant.userRefer:flduserid,firstname,middlename,lastname'
        ])->whereHas('patientInfo')->orderBy('fldregdate', 'DESC');

        $patients_checked = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by','fldfollowdate')
        ->whereNotNull('fldfollowdate')
        ->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'allConsultant'=>function($query){
                $query->where('fldcomment','!=','Follow Up');
            }
        ])
        ->whereHas('patientInfo')->orderBy('fldfollowdate', 'DESC');

        if ($request->get('consultant')) {
            $patients = $patients->where('flduserid', $request->get('consultant'));
            $patients_checked = $patients_checked->where('flduserid', $request->get('consultant'));
        }
        if ($request->get('from_date')) {
            $from_date = $request->get('from_date');
            $from_date = Helpers::dateNepToEng($from_date)->full_date;
            // $patients = $patients->whereRaw('DATE_ADD(DATE(fldregdate),INTERVAL 7 day) >= "'.$from_date.'"');
            $patients = $patients->where('fldregdate', '>=', $from_date . " 00:00:00");
            $patients_checked = $patients_checked->where('fldfollowdate', '>=', $from_date . " 00:00:00");
        } else {
            $patients = $patients->where('fldregdate', '>=', date('Y-m-d') . " 00:00:00");
            $patients_checked = $patients_checked->where('fldfollowdate', '>=', date('Y-m-d') . " 00:00:00");
        }
        if ($request->get('to_date')) {
            $to_date = $request->get('to_date');
            $to_date = Helpers::dateNepToEng($to_date)->full_date;
            $patients = $patients->whereRaw('DATE_ADD(DATE(fldregdate),INTERVAL '.$number_of_days.' day) >= "'.$to_date.'.23:59:59"');
            // $patients = $patients->where('fldregdate', '<=', $to_date . " 23:59:59");
            $patients_checked = $patients_checked->where('fldfollowdate', '<=', $to_date . " 23:59:59");
        } else {
            $patients = $patients->whereRaw('DATE_ADD(DATE(fldregdate),INTERVAL '.$number_of_days.' day) >= "'.date('Y-m-d').'.23:59:59"');
            $patients_checked = $patients_checked->where('fldfollowdate', '<=', date('Y-m-d') . " 23:59:59");
        }
        if ($request->get('department')) {
            $patients = $patients->where(function($q) use ($request) {
                                $q->where('fldadmitlocat', $request->get('department'))
                                ->orWhere('fldcurrlocat', $request->get('department'));
                        });
            $patients_checked = $patients_checked->where(function($q) use ($request) {
                                $q->where('fldadmitlocat', $request->get('department'))
                                ->orWhere('fldcurrlocat', $request->get('department'));
                        });
        }

        $data['patients'] = $patients->get();
        $data['patients_counts'] = $patients->count();
        $data['patients_checked'] = $patients_checked->get();
        $data['patients_checked_counts'] = $patients_checked->count();
        // dd($data['patients_checked']);
        return view('patient::list',$data);
    }

    public function patientListCsv(Request $request)
    {
        $export = new \App\Exports\PatientListExport(
            $request->consultant,
            $request->from_date,
            $request->to_date,
            $request->department
        );
        ob_end_clean();
        ob_start();
        return \Excel::download($export, 'PatientListExport.xlsx');
    }

    public function patientListPdf(Request $request)
    {
        $department = $request->get('department');
        $consultant = $request->get('consultant');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');
        $number_of_days = Options::get('followup_days');
        
        $patients = Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by','fldfollowdate')
        ->whereNull('fldfollowdate')
        ->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'allConsultant:fldencounterval,fldconsultname,flduserid',
            // 'consultant.userRefer:flduserid,firstname,middlename,lastname'
        ])->whereHas('patientInfo')->orderBy('fldregdate', 'DESC');

        if ($consultant) {
            $patients = $patients->where('flduserid', $consultant);
        }
        if ($from_date) {
            $patients = $patients->where('fldregdate', '>=', $from_date . " 00:00:00");
        }
        if ($to_date) {
            $patients = $patients->whereRaw('DATE_ADD(DATE(fldregdate),INTERVAL '.$number_of_days.' day) >= "'.$to_date.'.23:59:59"');
            // $patients = $patients->where('fldfollowdate', '<=', $to_date . " 23:59:59");
        }
        if ($department) {
            $patients = $patients->where(function($q) use ($department) {
                                $q->where('fldadmitlocat', $department)
                                ->orWhere('fldcurrlocat', $department);
                        });
        }

        $data['patients'] = $patients->get();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['patients_counts'] = $patients->count();
        return view('patient::patientlist-pdf',$data);
    }

    public function patientFollowListCsv(Request $request)
    {
        $export = new \App\Exports\PatientFollowListExport(
            $request->consultant,
            $request->from_date,
            $request->to_date,
            $request->department
        );
        ob_end_clean();
        ob_start();
        return \Excel::download($export, 'PatientFollowListExport.xlsx');
    }

    public function patientFollowListPdf(Request $request)
    {
        $department = $request->get('department');
        $consultant = $request->get('consultant');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');
        
        $patients= Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by','fldfollowdate')
        ->whereNotNull('fldfollowdate')
        // ->whereHas('allConsultant', function ($query) {
        //     $query->where('fldcomment','!=','Follow Up');
        // })
        ->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'allConsultant'=>function($query){
                $query->where('fldcomment','!=','Follow Up');
            }
        ])->whereHas('patientInfo')->orderBy('fldfollowdate', 'DESC');

        if ($consultant) {
            $patients = $patients->where('flduserid', $consultant);
        }
        if ($from_date) {
            $patients = $patients->where('fldfollowdate', '>=', $from_date . " 00:00:00");
        }
        if ($to_date) {
            $patients = $patients->where('fldfollowdate', '<=', $to_date . " 23:59:59");
        }
        if ($department) {
            $patients = $patients->where(function($q) use ($department) {
                                $q->where('fldadmitlocat', $department)
                                ->orWhere('fldcurrlocat', $department);
                        });
        }

        $data['patients'] = $patients->get();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['patients_counts'] = $patients->count();
        return view('patient::patientfollowlist-pdf',$data);
    }

    public function updatePatient(Request $request)
    {
        try{
            $encounterId = $request->get('encounterId');
            Encounter::where('fldencounterval',$encounterId)->update(['fldfollowdate'=>Carbon::now()]);
            return response([
                'status' => TRUE,
                'message' => 'Updated'
            ]);
        } catch(\Exception $e)
        {
            return [
                'status' => FALSE,
                'message' => '!Some thing went wrong.'
                //test
            ];
        }
    }

    public function UpdateConsultantList(Request $request)
    {
        if (!$request->get('edit-consult-patient')) {
            return redirect()->back();
        }
        $encounter = Encounter::select('fldregdate','fldencounterval','fldpatientval')->where('fldencounterval', $request->get('edit-consult-patient'))->first();

        // if ($encounter) {
        //     $diff = Carbon::parse($encounter->fldregdate)->diffInHours(Carbon::now());
        //     if ($diff > 12) {
        //         Session::put(['edit_consult_message' => 'Patient has been registered for more than 12 hrs!']);
        //         return redirect()->back();
        //     }
        // }


        $oldConsultants = Consult::select('fldid','fldbillno')->where('fldencounterval', $request->get('edit-consult-patient'))->get();
        try {
            $departments = $request->get('department') ? array_filter($request->get('department')) : [];
            $consultants = $request->get('consultant') ? array_filter($request->get('consultant')) : [];
            if ($oldConsultants) {
                $billNumberGeneratedString = $oldConsultants[0]->fldbillno;
                // Consult::whereIn('fldid',$oldConsultants->pluck('fldid')->toArray())->delete();
                foreach ($departments as $deptIndex => $department) {
                    $departmentConsultant = isset($consultants[$deptIndex]) ? $consultants[$deptIndex] : NULL;
                    $insertConsultant[] = [
                        'fldencounterval' => $request->get('edit-consult-patient'),
                        'fldconsultname' => $department,
                        'fldconsulttime' => Carbon::now(),
                        'fldcomment' => 'Follow Up',
                        'fldstatus' => 'Planned',
                        'flduserid' => $departmentConsultant,
                        'fldorduserid' => Helpers::getCurrentUserName(),
                        'fldtime' => Carbon::now(),
                        'fldcomp' => Helpers::getCompName(),
                        'fldsave' => '1',
                        'xyz' => '0',
                        'fldcategory' => NULL,
                        'fldbillno' => $billNumberGeneratedString,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }
                \App\Consult::insert($insertConsultant);

                Encounter::where('fldencounterval',$encounter->fldencounterval)->update(['fldfollowdate'=>Carbon::now()]);
                // $doc = CogentUsers::select('id')->where('username',$departmentConsultant)->first();
                // $regbill = PatBilling::where('fldencounterval', $request->get('edit-consult-patient'))
                    // ->where('fldbillno', 'LIKE','%'.$billNumberGeneratedString.'%')->orderBy('fldid','DESC')->first();
                // if ($regbill) {
                    // foreach ($regbill as $bill) {
                        // $patbillshares = [
                            // 'user_id' => $doc->id,
                        // ];
                        // PatBillingShare::where('pat_billing_id',$regbill->fldid)->update($patbillshares);
                   // }
                // }
                // $loging = [
                //     'fldencounterval' =>$encounter->fldencounterval,
                //     'fldpatientval' =>$encounter->fldpatientval,
                //     'flddate' => date('Y:m:d'),
                //     'fldtime' => date('H:i:s'),
                //     'flduserid' => Helpers::getCurrentUserName()

                // ];
                // undoDischargeLog::insert($loging);


                return redirect()->back();
            } else {
                return redirect()->back()->with('edit_consult_message', 'Encounter not found!');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('edit_consult_message', 'Something Went Wrong!');
        }
    }

    public function reset()
    {
        return redirect()->route('patient.list');
    }
}
