<?php

namespace Modules\Billing\Http\Controllers;

use App\AutoId;
use App\Banks;
use App\BillingSet;
use App\BillRemark;
use App\CogentUsers;
use App\Encounter;
use App\Entry;
use App\Fiscalyear;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientExam;
use App\PatientInfo;
use App\PatLabTest;
use App\ServiceCost;
use App\ServiceGroup;
use App\TempPatbillDetail;
use App\User;
use App\UserShare;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

/**
 * Class DischargeController
 * @package Modules\Billing\Http\Controllers
 */
class DischargesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $departments = Helpers::getDepartments();
        $patient_id = NULL;
        // $patients = PatBillDetail::select('fldencounterval','fldbillno','fldprevdeposit','flditemamt','fldreceivedamt','fldbilltype')->with([
        //     'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
        //     'patientInfo.credential:fldpatientval,fldusername,fldpassword',
        //     'consultant:fldencounterval,fldorduserid',
        //     'consultant.userRefer:flduserid,firstname,middlename,lastname'
        // ]);
        $patients = PatBillDetail::select('fldencounterval', 'fldbillno', 'fldprevdeposit', 'flditemamt', 'fldreceivedamt', 'fldbilltype');



        $patients = $patients->paginate(10);
        //dd($patients);

        return view('billing::dischargelist', compact('departments', 'patients', 'patient_id'));
    }

    // public function dischargeCsv(Request $request)
    // {
    //     $export = new \App\Exports\RegistrationExport(
    //         $request->name,
    //         $request->from_date,
    //         $request->to_date,
    //         $request->department
    //     );
    //     ob_end_clean();
    //     ob_start();
    //     return \Excel::download($export, 'RegistrationExport.xlsx');
    // }

    public function dischargePdf(Request $request)
    {
        $department = $request->get('department');
        $billno = $request->get('billno');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $patients = PatBilling::select('*');


        if ($billno) {
            $billno = $billno;
            $patients = $patients->where('fldbillno', $billno);
        }

        $patients = $patients->get();

        return view('billing::pdf.discharge-pdf', compact('billno', 'from_date', 'to_date', 'department', 'patients'));
    }

    public function accountlist(Request $request)
    {
        $data['bill_patientDetail'] = $patientbillDetail = array();
        $data['bill_patient'] = '';

        if ($request->patient_id) {
            $patient_encounter = Encounter::where('fldpatientval',$request->patient_id)->pluck('fldencounterval');

            $patientBill = PatBillDetail::whereIn('fldencounterval',$patient_encounter)->groupBy('fldbillno')->get();
            if($patientBill){
                foreach($patientBill as $k => $bill){
                    $patientbillDetail[$k]['bill'] = $bill;

                    $patientbillDetail[$k]['billdetail'] = PatBilling::where('fldbillno',$bill->fldbillno)->get();

                }
            }
            //dd($patientbillDetail);
            $data['bill_patient'] = $patientbillDetail;


        }


        return view('billing::account-list', $data);
    }
}
