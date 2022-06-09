<?php

namespace Modules\Dispensar\Http\Controllers;

use App\Admissionrequest;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\Encounter;
use App\PatBillDetail;
use App\PatBilling;
use App\PatientDate;
use App\PatientInfo;
use App\ServiceCost;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use App\Services\MaternalisedService;


class DepositFormController extends Controller
{
    public function index(Request $request)
    {
        $computer = Helpers::getCompName();
        $encounter_id_session = Session::get('encounter_id_deposit_form');
        $data = [
            'enpatient' => [],
            'previousDeposit' => 0,
            'deposit_for' => ['Admission Deposit', 'OP Deposit', 'RE Deposit', 'Blood bank', 'Gate pass', 'Post-up deposit', 'Deposit Refund', 'Pharmacy Deposit'],
            'departments' => \DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get(),
            'admissions_requests' => Admissionrequest::where('fldstatus', 'unread')->get(),
        ];
        if ($request->has('encounter_id') || $encounter_id_session) {
            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['encounter_id_deposit_form' => $encounter_id]);

            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)
                ->with(['patientInfo'])
                ->with(['consultant'])
                ->with(['patBill' => function ($query) {
                    return $query->select('fldencounterval', 'fldditemamt');
                }])
                ->with(['patBillDetails' => function ($query2) {
                    return $query2->select('fldencounterval', 'fldreceivedamt');
                }])
                ->first();

            $data['admissions'] = Encounter::where('fldencounterval', $encounter_id)->get();


            // yo final bvk le bhaneko
            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
            ->where('fldencounterval', $encounter_id)
            ->where('fldbilltype', 'Credit')
            ->where('fldcomp', $computer)
             ->first();

            $data['expenses'] = PatBilling::select('fldid', 'fldtime', 'flditemtype', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldditemamt', 'fldbillno')
                ->where('fldencounterval', $encounter_id)
                ->whereNotNull('flditemtype')
                ->where('fldcomp', $computer)
                ->where('fldsave', 1)
                ->get();

            $data['invoices'] = PatBillDetail::select('fldid', 'fldtime', 'payment_mode','fldbillno', 'flditemamt', 'fldtaxamt', 'flddiscountamt', 'fldreceivedamt', 'fldcurdeposit', 'fldbilltype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp', $computer)
                ->get();

            $data['previousDeposit'] = $previousDeposit ?  $previousDeposit->fldcurdeposit : 0;
            $data['admissions_requests'] = Admissionrequest::where('fldstatus', 'unread')->get();

            // TP Bill Ko data

            if (isset($enpatient) && $enpatient->patientInfo) {
                $date = $enpatient->flddoa;
                $doa =  $enpatient->flddoa;
                $datework = \Carbon\Carbon::createFromDate($date);
                $now = \Carbon\Carbon::now();
                $noofdays = $datework->diffInDays($now);
            } else {
                $doa = 0;
                $noofdays = 0;
            }


            $data['bedcharge'] = 0;
            $data['bedname'] = '';

            $getBedDetails = Departmentbed::where('fldencounterval', $encounter_id)->first();
            if(isset($getBedDetails)){
                $getBeddeparment = Department::where('flddept', $getBedDetails->flddept)->first();
                if (isset($getBeddeparment)) {
                    if (isset($getBeddeparment->fldhead) && $getBeddeparment->fldhead != "0") {
                        $servicecost = ServiceCost::where([
                            'flditemtype' => "General Services",
                            'flditemname' => $getBeddeparment->fldhead
                        ])->first();
                        $data['bedname'] = $getBeddeparment->fldhead;
                    }
                }
            }

            $getCategory = PatBilling::select('flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', 'like', '%TP%')
                // ->where('fldcomp','=','comp01')
                ->where('fldcomp','=',Helpers::getCompName())
                ->whereIn('fldstatus', ['Waiting', 'Punched'])
                ->groupBy('flditemtype')->get();

            if ($getCategory) {

                foreach ($getCategory as $k => $billing) {
                    $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;

                    //yahaaa

                    $haveautobill = PatBilling::where('flditemname',$data['bedname'])
                        ->where('fldencounterval',$enpatient->fldencounterval)
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp01')
                        ->first();
                    //dd($haveautobill);
                    if($haveautobill){
                        $this->updateautobillingbedcharge($haveautobill->fldid,$noofdays);
                    }


                    $patbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                        ->where('fldencounterval', $encounter_id)
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldtempbillno', 'like','%TP%')
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp01')
                        ->whereIn('fldstatus', ['Waiting', 'Punched'])
                        ->get();

                    $data['eachpatbilling'][$k]['details'] = $patbillData;
                    $data['eachpatbilling'][$k]['total'] =  $patbillData->sum('fldditemamt') + $patbillData->sum('flddiscamt');
                    $data['patbillingdetail'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp01')
                        ->first();
                }

                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where('fldstatus', 'Waiting')
                    ->where('fldcomp','=',Helpers::getCompName())
                    // ->where('fldcomp','=','comp01')
                    ->first()->subtotal;
                $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TP%')
                    // ->where('fldtempbillno', 'like', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldditemamt');
                $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TP%')
                    // ->where('fldtempbillno', '!=', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('flddiscamt');
                $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TP%')
                    // ->where('fldtempbillno', '!=', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldtaxamt');
                $ref_doctor_pat  = PatBilling::with('referUserdetail')
                    ->where('fldencounterval',$encounter_id)
                    ->where('fldrefer','!=', NULL)
                    ->first();
                if($ref_doctor_pat){
                    $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldfullname :'' );
                }else{
                    $ref_doctor_consult  = Consult::with('user')
                        ->where('fldencounterval',$encounter_id)
                        ->first();
                    if($ref_doctor_consult){
                        $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldfullname :'' );
                    }
                }


                $Deposit = \App\PatBillDetail::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', 'like', '%DEP%')
                    ->where('fldcomp','=',Helpers::getCompName())
                    // ->where('fldcomp','=','comp01')
                    ->sum('fldreceivedamt');




                $data['remaining_deposit'] =  $rd = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $request->encounter_id)
                    ->where('fldbilltype', '=', 'Credit')
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->orderBy('fldid', 'DESC')
                    ->first();
                if ($rd)
                    $data['previousDeposit'] =  $rd->fldcurdeposit;
                else
                    $data['previousDeposit'] =  0;
            }


            //Pharmacy ko data like tpphm

            $getCategoryPhm = PatBilling::select('flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', 'like', '%TPPHM%')
                // ->where('fldcomp','=','comp01')
                ->where('fldcomp','=',Helpers::getCompName())
                ->whereIn('fldstatus', ['Waiting', 'Punched'])
                ->groupBy('flditemtype')->get();
            if ($getCategoryPhm) {

                foreach ($getCategoryPhm as $k => $billingphm) {
                    $data['eachpatbillingphm'][$k]['category'] = $billingphm->flditemtype;

                    //yahaaa

                    $haveautobillphm = PatBilling::where('flditemname',$data['bedname'])
                        ->where('fldencounterval',$enpatient->fldencounterval)
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp01')
                        ->first();
                    //dd($haveautobill);
                    if($haveautobillphm){
                        $this->updateautobillingbedcharge($haveautobillphm->fldid,$noofdays);
                    }


                    $patbillDataphm = PatBilling::where('flditemtype', $billingphm->flditemtype)
                        ->where('fldencounterval', $encounter_id)
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldtempbillno', 'like','%TPPHM%')
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp02')
                        ->whereIn('fldstatus', ['Waiting', 'Punched'])
                        ->get();

                    $data['eachpatbillingphm'][$k]['details'] = $patbillDataphm;
                    $data['eachpatbillingphm'][$k]['total'] =  $patbillDataphm->sum('fldditemamt') + $patbillDataphm->sum('flddiscamt');
                    $data['patbillingdetailphm'][$k] = PatbillDetail::where('fldtempbillno', $billingphm->fldtempbillno)
                        ->where('fldcomp','=',Helpers::getCompName())
                        // ->where('fldcomp','=','comp01')
                        ->first();
                }

                $data['subtotalphm'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where('fldstatus', 'Waiting')
                    ->where('fldcomp','=',Helpers::getCompName())
                    // ->where('fldcomp','=','comp01')
                    ->first()->subtotal;
                $data['totalphm'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TPPHM%')
                    // ->where('fldtempbillno', 'like', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldditemamt');
                $data['discountphm'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TPPHM%')
                    // ->where('fldtempbillno', '!=', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('flddiscamt');
                $data['taxphm'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'like', '%TPPHM%')
                    // ->where('fldtempbillno', '!=', NULL)
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldtaxamt');
                $ref_doctor_pat_phm  = PatBilling::with('referUserdetail')
                    ->where('fldencounterval',$encounter_id)
                    ->where('fldrefer','!=', NULL)
                    ->first();
                if($ref_doctor_pat_phm){
                    $data['referable_doctor_phm'] = (($ref_doctor_pat_phm->fldrefer && $ref_doctor_pat_phm->referUserdetail) ? $ref_doctor_pat_phm->referUserdetail->fldfullname :'' );
                }else{
                    $ref_doctor_consult_phm  = Consult::with('user')
                        ->where('fldencounterval',$encounter_id)
                        ->first();
                    if($ref_doctor_consult_phm){
                        $data['referable_doctor_phm'] = (($ref_doctor_consult_phm->flduserid && $ref_doctor_consult_phm->user) ? $ref_doctor_consult_phm->user->fldfullname :'' );
                    }
                }


                $DepositPhm = \App\PatBillDetail::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', 'like', '%DEP%')
                    ->where('fldcomp','=',Helpers::getCompName())
                    // ->where('fldcomp','=','comp01')
                    ->sum('fldreceivedamt');




                $data['remaining_deposit_phm'] =  $rd = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $request->encounter_id)
                    ->where('fldbilltype', '=', 'Credit')
                    // ->where('fldcomp','=','comp01')
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->orderBy('fldid', 'DESC')
                    ->first();
                if ($rd)
                    $data['previousDepositphm'] =  $rd->fldcurdeposit;
                else
                    $data['previousDepositPhm'] =  0;
            }
            $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();


        }
        return view('dispensar::depositForm', $data);
    }

    public function expensesList(Request $request)
    {
        $computer = Helpers::getCompName();
        $expenses = PatBilling::select('fldid', 'fldtime', 'flditemtype', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldditemamt', 'fldbillno')
            ->where('fldencounterval', $request->encounter)
            ->whereNotNull('flditemtype')
            ->where('fldcomp', $computer)
            ->where('fldsave', 1)
            ->get();

        $html = '';
        if ($expenses) {
            foreach ($expenses as $key => $expens) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$expens->fldtime</td>";
                $html .= "<td>$expens->flditemtype</td>";
                $html .= "<td>$expens->flditemname</td>";
                $html .= "<td>".Helpers::numberFormat($expens->flditemrate)."</td>";
                $html .= "<td>$expens->fldtaxper</td>";
                $html .= "<td>$expens->flddiscper</td>";
                $html .= "<td>$expens->flditemqty</td>";
                $html .= "<td>".Helpers::numberFormat($expens->fldditemamt)."</td>";
                $html .= "<td>$expens->fldbillno</td>";
                $html .= "</tr>";
            }
        }

        return response()->json([
            'success' => [
                'status' => true,
                'html' => $html,
            ]
        ]);
    }

    public function getInvoiceList(Request $request)
    {
        $computer = Helpers::getCompName();
        $invoices = PatBillDetail::select('fldid', 'fldtime', 'payment_mode','fldbillno', 'flditemamt', 'fldtaxamt', 'flddiscountamt', 'fldreceivedamt', 'fldcurdeposit', 'fldbilltype')
            ->where('fldencounterval', $request->encounter)
            ->where('fldcomp', $computer)
            ->get();

        $html = '';

        if ($invoices) {
            foreach ($invoices as $key => $invoice) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$invoice->fldtime</td>";
                $html .= "<td>$invoice->fldbillno</td>";
                $html .= "<td>".Helpers::numberFormat($invoice->flditemamt)."</td>";
                $html .= "<td>".Helpers::numberFormat($invoice->fldtaxamt)."</td>";
                $html .= "<td>".Helpers::numberFormat($invoice->flddiscountamt)."</td>";
                $html .= "<td>".Helpers::numberFormat($invoice->fldreceivedamt)."</td>";
                $html .= "<td>".Helpers::numberFormat($invoice->fldcurdeposit)."</td>";
                $html .= "<td>$invoice->fldbilltype</td>";
                $html .= "<td>$invoice->payment_mode</td>";
                $html .= "</tr>";
            }
        }

        return response()->json([
            'success' => [
                'status' => true,
                'html' => $html,
            ]
        ]);
    }

    public function saveComment(Request $request)
    {
        $patientId = Encounter::select('fldpatientval')->where('fldencounterval', $request->encounter)->first();

        try {
            $updateData = [
                'fldcomment' => $request->comment,
                'fldupuser' => \Auth::guard('admin_frontend')->user()->flduserid,
                'flduptime' => now(),
                'xyz' => '0'
            ];
            PatientInfo::where([['fldpatientval', $patientId->fldpatientval]])->update($updateData);
            return response()->json([
                'success' => [
                    'status' => true,
                ]
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in deposite form save comment', "Error"]);
            return response()->json([
                'success' => [
                    'status' => false,
                ]
            ]);
        }
    }

    public function saveDiaryNumber(Request $request)
    {
        $patientId = Encounter::select('fldpatientval')->where('fldencounterval', $request->encounter)->first();

        try {
            $updateData = [
                'fldadmitfile' => $request->diary_number,
                'fldupuser' => \Auth::guard('admin_frontend')->user()->flduserid,
                'flduptime' => now(),
                'xyz' => '0'
            ];
            PatientInfo::where([['fldpatientval', $patientId->fldpatientval]])->update($updateData);
            return response()->json([
                'success' => [
                    'status' => true,
                ]
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in deposite form save diary number', "Error"]);
            return response()->json([
                'success' => [
                    'status' => false,
                ]
            ]);
        }
    }

    public function expensesListPDF(Request $request)
    {
        $computer = Helpers::getCompName();
        $expenses = PatBilling::select('fldencounterval', 'fldid', 'fldtime', 'flditemtype', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldditemamt', 'fldbillno')
            ->where('fldencounterval', $request->encounter)
            ->where('fldsave', 1)
            ->where('fldcomp', $computer)
            ->with('parentDetail')
            ->get();

        $html = '';

        if ($expenses) {
            foreach ($expenses as $key => $expens) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$expens->fldtime</td>";
                $html .= "<td>$expens->fldbillno</td>";
                $html .= "<td>$expens->flditemtype</td>";
                $html .= "<td>$expens->flditemname</td>";
                $html .= "</tr>";
            }
        }
        $data['encounterData'] = Encounter::select('fldpatientval', 'fldrank', 'fldregdate')->where('fldencounterval', $request->encounter)->first();;
        $data['html'] = $html;
        $data['certificate'] = 'Deposit Form Expenses';
        $data['encounterId'] = $request->encounter;
        return view('dispensar::pdf.expenses-deposit', $data);
    }

    public function getInvoiceListPDF(Request $request)
    {
        $computer = Helpers::getCompName();
        $invoices = PatBillDetail::select('fldid', 'fldtime', 'fldbillno', 'flditemamt', 'fldtaxamt', 'flddiscountamt', 'fldreceivedamt', 'fldcurdeposit', 'fldbilltype')
            ->where('fldencounterval', $request->encounter)
            ->where('fldcomp', $computer)
            ->with('patBill')
            ->get();

        $html = '';

        if ($invoices) {
            foreach ($invoices as $key => $invoice) {
                if(substr($invoice->fldbillno, 0, 3) != "DEP"){
                    $html .= "<tr>";
                    $html .= "<td>" . ++$key . "</td>";
                    $html .= "<td>$invoice->fldtime</td>";
                    $html .= "<td>$invoice->fldbillno</td>";
                    $html .= "<td>".Helpers::numberFormat($invoice->flditemamt)."</td>";
                    $patbillParticulars = '';
                    if ($invoice->patBill){
                        $invoiceDetails = $invoice->patBill->pluck('flditemname')->toArray();
                        $patbillParticulars = nl2br(implode("\n", $invoiceDetails));
                    }
                    $html .= "<td>".$patbillParticulars."</td>";
                    $html .= "</tr>";
                }else{
                    $html .= "<tr>";
                    $html .= "<td>" . ++$key . "</td>";
                    $html .= "<td>$invoice->fldtime</td>";
                    $html .= "<td>$invoice->fldbillno</td>";
                    $html .= "<td>".Helpers::numberFormat($invoice->fldreceivedamt)."</td>";
                    $html .= "<td></td>";
                    $html .= "</tr>";
                }
            }
        }

        $data['encounterData'] = Encounter::select('fldpatientval', 'fldrank')->where('fldencounterval', $request->encounter)->first();
        $data['html'] = $html;
        $data['certificate'] = 'Deposit Form Invoices';
        $data['encounterId'] = $request->encounter;
        return view('dispensar::pdf.invoice-deposit', $data);
    }

    public function saveAdmittedConsultant(Request $request)
    {
        $computer = Helpers::getCompName();
        $admitted = $request->admitted;
        $consultant = $request->consultant;
        $encounter = $request->encounter;
        $admitFileNo = '';
        try {
            $admitFileNo = Helpers::getNextAutoId('AdmitFileNo', TRUE);
            $patientId = Encounter::select('fldpatientval')->where('fldencounterval', $request->encounter)->first();
            PatientInfo::where([['fldpatientval', $patientId->fldpatientval]])->update([
                'fldadmitfile' => $admitFileNo,
                'fldptguardian' => $request->get('fldptguardian'),
                'fldrelation' => $request->get('fldrelation'),
            ]);

            if ($admitted != "" || $consultant != "") {
                $dataSave = [];
                if ($admitted != "") {
                    $dataSave['fldadmission'] = $admitted;
                    $dataPatientDate = [
                        'fldencounterval' => $encounter,
                        'fldhead' => $admitted,
                        'fldcomment' => '',
                        'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => now(),
                        'fldcomp' => $computer,
                        'fldsave' => 1,
                        'flduptime' => null,
                        'xyz' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                    $patientDate = PatientDate::create($dataPatientDate);
                    Helpers::logStack(["Patient date created", "Event"], ['current_data' => $patientDate]);

                }
                if ($consultant != "") {
                    $dataSave['flduserid'] = $consultant;
                    $dataPatientDate = [
                        'fldencounterval' => $encounter,
                        'fldhead' => "Consultant",
                        'fldcomment' => $consultant,
                        'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => now(),
                        'fldcomp' => $computer,
                        'fldsave' => 1,
                        'flduptime' => null,
                        'xyz' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                    $patientDate = PatientDate::create($dataPatientDate);
                    Helpers::logStack(["Patient date created", "Event"], ['current_data' => $patientDate]);

                    \App\Consult::where([
                        'fldencounterval' => $encounter,
                    ])->whereNull('flduserid')->update([
                        'flduserid' => $consultant
                    ]);
                }
                $dataSave['xyz'] = 0;
                $dataSave['flddoa'] = now();
                Encounter::where([['fldencounterval', $encounter]])->update($dataSave);
            }
            return response()->json([
                'success' => [
                    'status' => true,
                    'admitFileNo' => $admitFileNo,
                ]
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in deposite form save admitted consult', "Error"]);
            return response()->json([
                'success' => [
                    'status' => false,
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }

    public function saveDeposit(Request $request)
    {
        $encounterId = $request->get('encounterId');
        $chkAdmitted = Encounter::where('fldencounterval', $encounterId)->first();
        if($chkAdmitted){
            if($chkAdmitted->fldadmission != "Admitted"){
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Patient not admitted. Failed to save deposit.',
                ]);
            }
        }
        $deposit_for = $request->get('deposit_for');
        $received_amount = $request->get('received_amount');
        $payment_mode = $request->get('payment_mode');
        $expected_payment_date = $request->get('expected_payment_date');
        $cheque_number = $request->get('cheque_number');
        $other_reason = $request->get('other_reason');
        $bank_name = $request->get('bank_name');
        $office_name = $request->get('office_name');
        $payment_mode = $request->get('payment_mode');
        $datetime = date('Y-m-d H:i:s');
        $userid = Helpers::getCurrentUserName();
        $computer = Helpers::getCompName();
        $department = Helpers::getUserSelectedHospitalDepartmentIdSession();

        $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
            ->where([
                'fldencounterval' => $encounterId,
                'fldbilltype' => 'Credit',
                'fldcomp' => $computer,
            ])->orderBy('fldid', 'DESC')

            ->first();
        $previousDeposit = $previousDeposit ? $previousDeposit->fldcurdeposit : 0;
        $currentDeposit = $previousDeposit + $received_amount;

        \DB::beginTransaction();
        try {


            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
            $dateToday = \Carbon\Carbon::now();
            $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
            $billNumberGeneratedString = "DEP-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');


            $encounterData = Encounter::select('fldcurrlocat', 'flddisctype')->where('fldencounterval', $encounterId)->first();
            $patbilldetail = [
                'fldencounterval' => $encounterId,
                'fldbillno' => $billNumberGeneratedString,
                'fldprevdeposit' => $previousDeposit,
                'flditemamt' => Helpers::numberFormat(0,'insert'),
                'fldpayitemname' => $deposit_for,
                'fldtaxamt' => Helpers::numberFormat(0,'insert'),
                'flddiscountamt' => Helpers::numberFormat(0,'insert'),
                'flddiscountgroup' => $encounterData->flddisctype,
                'fldchargedamt' => Helpers::numberFormat(0,'insert'),
                'fldreceivedamt' => Helpers::numberFormat($received_amount,'insert'),
                'fldcurdeposit' => Helpers::numberFormat($currentDeposit,'insert'),
                'fldbilltype' => 'Credit',
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldbill' => "Invoice",
                'fldsave' => '1',
                'xyz' => '0',
                'hospital_department_id' => $department,
                'tblofficename' => $office_name,
                'tblexpecteddate' => $expected_payment_date,
                'payment_mode' => $payment_mode,
            ];
            if ($payment_mode == "cheque") {
                $patbilldetail['fldchequeno'] = $cheque_number;
                $patbilldetail['fldbankname'] = $bank_name;
            }elseif ($payment_mode == 'credit') {
               // $patbilldetail['fldcurdeposit'] = '-' . $fldchargedamt;
            }

            $patDetailsData = \App\PatBillDetail::create($patbilldetail);
            Helpers::logStack(["Pat bill detail created", "Event"], ['current_data' => $patDetailsData]);

            $patDetailsData['location'] = $encounterData->fldcurrlocat;
            #Fonepayment Log Update with Bill Number
            $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
            \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
            #End Fonepayment Log Update with Bill Number
            \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);

            Encounter::where([['fldencounterval', $encounterId]])->update(['fldcashdeposit' => Helpers::numberFormat($currentDeposit,'insert')]);
            MaternalisedService::insertMaternalisedFiscalDeposit($encounterId,$billNumberGeneratedString,$payment_mode);



            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved deposit.',
                'data' => [
                    'previousDeposit' => Helpers::numberFormat($previousDeposit,'insert'),
                    'billno' => $billNumberGeneratedString,
                    'receivedAmount' => Helpers::numberFormat($received_amount,'insert'),
                ]
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in deposite form save deposite', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save deposit.',
            ]);
        }
    }

    public function printBill(Request $request)
    {
        $depositDetail = \App\PatBillDetail::select('fldbillno', 'fldtime', 'fldbilltype', 'fldprevdeposit', 'fldreceivedamt', 'fldcurdeposit', 'fldencounterval', 'fldpayitemname','payment_mode','flduserid')
            ->where('fldbillno', $request->get('fldbillno'))

            ->first();



        $encounterData = \App\Encounter::select('fldpatientval', 'fldencounterval', 'fldbillingmode')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptadddist,fldptaddvill,fldmunicipality,fldwardno,fldptbirday,fldptcontact',
            ])->where('fldencounterval', $depositDetail->fldencounterval)
            ->first();

        $billno = $request->get('fldbillno');
        $countdata = \App\PatBillCount::where('fldbillno', $billno)->pluck('fldcount')->first();
        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

        if (isset($countdata) and $countdata != '') {
            \App\PatBillCount::where('fldbillno', $billno)->update($updatedata);
            Helpers::logStack(["Pat bill count updated", "Event"], ['current_data' => $updatedata, 'previous_data' => $countdata]);
        } else {
            $insertdata['fldbillno'] = $billno;
            $insertdata['fldcount'] = $count;
            \App\PatBillCount::insert($insertdata);
            Helpers::logStack(["Pat bill count created", "Event"], ['current_data' => $insertdata]);
        }

        return view('dispensar::pdf.bill-print', [
            'depositDetail' => $depositDetail,
            'encounterinfo' => $encounterData,
            'billCount' => $count,

        ]);
    }

    public function DepositePrintBill(Request $request)
    {
        $depositDetail = \App\PatBillDetail::select('fldbillno', 'fldtime', 'fldbilltype', 'fldprevdeposit', 'fldreceivedamt', 'fldcurdeposit', 'fldencounterval', 'fldpayitemname','payment_mode')
        ->where('fldbillno', $request->get('fldbillno'))

        ->first();


        $encounterData = \App\Encounter::select('fldpatientval', 'fldencounterval', 'fldbillingmode')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptadddist,fldptaddvill,fldmunicipality,fldwardno,fldptbirday,fldptcontact',
            ])->where('fldencounterval', $depositDetail->fldencounterval)
            ->first();

        $billno = $request->get('fldbillno');
        $countdata = \App\PatBillCount::where('fldbillno', $billno)->pluck('fldcount')->first();
        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

        if (isset($countdata) and $countdata != '') {
            \App\PatBillCount::where('fldbillno', $billno)->update($updatedata);
            Helpers::logStack(["Pat bill count updated", "Event"], ['current_data' => $updatedata, 'previous_data' => $countdata]);
        } else {
            $insertdata['fldbillno'] = $billno;
            $insertdata['fldcount'] = $count;
            \App\PatBillCount::insert($insertdata);
            Helpers::logStack(["Pat bill count created", "Event"], ['current_data' => $insertdata]);
        }
        $invoiceBill =  view('dispensar::pdf.bill-print', [
                'depositDetail' => $depositDetail,
                'encounterinfo' => $encounterData,
                'billCount' => $count,

        ])->render();
        return response([
            'invoicebill' => $invoiceBill,
            'encounter_id' => $request->encounter_id,
            'billno' => $request->billno,
        ]);

    }

    public function returnDeposit(Request $request)
    {
        $encounterId = Session::get('encounter_id_deposit_form');

        if ($encounterId === '' || $encounterId === null) {
            return redirect()->back()->with('error_message', 'Please enter encounter to return deposit.');
        }

        $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
            ->where('fldencounterval', $encounterId)
            ->orderBy('fldid', 'DESC')
            ->first();
        $previousDeposit = $previousDeposit ? $previousDeposit->fldcurdeposit : 0;
        $returnDeposit = $previousDeposit; //0;
        $newbalance = $previousDeposit - $returnDeposit;

        $datetime = date('Y-m-d H:i:s');
        $userid = Helpers::getCurrentUserName();
        $computer = Helpers::getCompName();
        $department = Helpers::getUserSelectedHospitalDepartmentIdSession();

        \DB::beginTransaction();
        try {
            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
            $dateToday = \Carbon\Carbon::now();
            $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
            $billNumberGeneratedString = "DEPRT-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
            $patbilldata = \App\PatBilling::create([
                'fldencounterval' => $encounterId,
                'fldbillingmode' => NULL,
                'flditemtype' => NULL,
                'flditemname' => "Deposit Return",
                'flditemrate' => Helpers::numberFormat($previousDeposit,'insert'),
                'flditemqty' => '-1',
                'fldreason' => NULL,
                'flddiscper' => Helpers::numberFormat(0,'insert'),
                'flddiscamt' => Helpers::numberFormat(0,'insert'),
                'fldditemamt' => Helpers::numberFormat((-1 * $previousDeposit),'insert'),
                'fldpayto' => NULL,
                'fldorduserid' => $userid,
                'fldordtime' => $datetime,
                'fldordcomp' => $computer,
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldprint' => '0',
                'fldtarget' => $computer,
                'fldsave' => '1',
                'fldsample' => 'Waiting',
                'xyz' => '0',
                'fldbillno' => $billNumberGeneratedString,
                'fldstatus' => 'Cleared',
                'hospital_department_id' => $department,
            ]);
            Helpers::logStack(["Pat billing created", "Event"], ['current_data' => $patbilldata]);

            if ($previousDeposit) {
                $payment_mode = 'Cash';
                $patbilldetail = [
                    'fldencounterval' => $encounterId,
                    'fldbillno' => $billNumberGeneratedString,
                    'fldprevdeposit' => Helpers::numberFormat($previousDeposit,'insert'),
                    'flditemamt' => '-'.Helpers::numberFormat($previousDeposit,'insert'),
                    'fldtaxamt' => Helpers::numberFormat(0,'insert'),
                    'flddiscountamt' => Helpers::numberFormat(0,'insert'),
                    'flddiscountgroup' => NULL,
                    'fldchargedamt' => Helpers::numberFormat(0,'insert'),
                    'fldreceivedamt' => '-'.Helpers::numberFormat($previousDeposit,'insert'),
                    'fldcurdeposit' => Helpers::numberFormat(0,'insert'),
                    'fldbilltype' => $payment_mode,
                    'flduserid' => $userid,
                    'fldtime' => $datetime,
                    'fldcomp' => $computer,
                    'fldbill' => NULL,
                    'fldsave' => '1',
                    'xyz' => '0',
                    'hospital_department_id' => $department,
                ];
                if ($payment_mode == "cheque") {
                    $patbilldetail['fldchequeno'] = $request->cheque_number;
                    $patbilldetail['fldbankname'] = $request->bank_name;
                }

                $patDetailsData = \App\PatBillDetail::create($patbilldetail);
                Helpers::logStack(["Pat bill detail created", "Event"], ['current_data' => $patDetailsData]);
                $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounterId)->first();
                $patDetailsData['location'] = $encounterData->fldcurrlocat;

                \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData, "Expenditure");
                \App\PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);
                Helpers::logStack(["Pat bill count created", "Event"], ['current_data' => $patDetailsData]);

                Encounter::where([['fldencounterval', $encounterId]])->update(['fldcashdeposit' => $newbalance]);
                Helpers::logStack(["Encounter updated", "Event"], ['current_data' => ['fldcashdeposit' => $newbalance], 'previous_data' => $encounterData]);
                MaternalisedService::insertMaternalisedFiscalReturn($encounterId,$billNumberGeneratedString,$payment_mode);

            }

            \DB::commit();
            return redirect()->back()->with('success_message', 'Refund Successful.');
        } catch (\Exception $e) {
            \DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in deposite form return deposit', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to return deposit.',
            ]);
        }
    }

    public function changePatientDepartment(Request $request)
    {
        try {
            $fiscalYear = Helpers::getNepaliFiscalYearRange();
            $startdate = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date . ' 00:00:00';
            $enddate = Helpers::dateNepToEng($fiscalYear['enddate'])->full_date . ' 23:59:59';
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();
            $datetime = date('Y-m-d H:i:s');
            $department = Helpers::getUserSelectedHospitalDepartmentIdSession();

            $formatData = \App\Patsubs::first();
            if (!$formatData)
                $formatData = new \App\Patsubs();

            $department_seperate_num = $request->department_category;
            $today_date = \Carbon\Carbon::now()->format('Y-m-d');
            $current_fiscalyr = \App\Year::select('fldname')->where([
                ['fldfirst', '<=', $today_date],
                ['fldlast', '>=', $today_date],
            ])->first();
            $current_fiscalyr = ($current_fiscalyr) ? $current_fiscalyr->fldname : '';
            if ($department_seperate_num)
                $formatedEncId = ($department_seperate_num == 'OPD') ? "OP" : $department_seperate_num;
            else
                $formatedEncId = $formatData->fldencid;

            if ($department_seperate_num == 'IP')
                $encounterID = Helpers::getNextAutoId('IpEncounterID', TRUE);
            elseif ($department_seperate_num == 'ER')
                $encounterID = Helpers::getNextAutoId('ErEncounterID', TRUE);
            else
                $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);

            $formatedEncId .= $current_fiscalyr . '-' . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);

            $encounter_data = Encounter::where('fldencounterval', $request->encounter_val)->first();
            $fldvisit = \App\Encounter::where('fldpatientval', $encounter_data->fldpatientval)->whereBetween('fldregdate', [$startdate, $enddate])->count() > 0 ? 'OLD' : 'NEW';

            $new_data = $encounter_data->replicate();
            $new_data->fldencounterval = $formatedEncId;
            $new_data->fldadmitlocat = null;
            $new_data->fldcurrlocat = null;
            $new_data->flddoa = null;
            $new_data->fldvisit = $fldvisit;
            $new_data->fldregdate = $datetime;
            $new_data->fldcomp = $computer;
            $new_data->flduserid = $userid;
            $new_data->save();

            $consultData = \App\Consult::create([
                'fldencounterval' => $formatedEncId,
                'fldstatus' => 'Planned',
                'fldbillingmode' => $encounter_data->fldbillingmode,
                'fldorduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldsave' => 1,
                'hospital_department_id' => $department,
            ]);
            Helpers::logStack(["Consult created", "Event"], ['current_data' => $consultData]);


            session()->put('changed_department_encounter_val', $formatedEncId);
            session()->flash('success', 'Department changed successfully.');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in deposite form change patient department', "Error"]);
            session()->flash('error_message', 'Something went wrong. Please try again.');
        }

        return redirect()->back();
    }

    public function reset()
    {
        Session::forget('encounter_id_deposit_form');
        return redirect()->route('depositForm');
    }

    public function updateConsult(Request $request){

        if(!($request->encounter || $request->consultant)){
            return response()->json(['error' =>'Please check encounter and Consultant']);
        }
        try {

            $consult = Consult::select('fldconsultname', 'flduserid')
                ->where('fldencounterval', $request->encounter)
                ->get();

            if(!($consult->isEmpty())){
                \App\Consult::where([
                    'fldencounterval' => $request->encounter,
                ])->update([
                    'flduserid' => $request->consultant
                ]);
                return response()->json(['message' =>'Updated Successfully!']);
            }else{
                $encounter = Encounter::select('flduserid')
                    ->where('fldencounterval', $request->encounter)
                    ->get();
                if($encounter){
                    Encounter::where('fldencounterval', $request->encounter)->update(['flduserid' => $request->consultant]);
                    return response()->json(['message' =>'Updated Successfully!']);
                }
            }
            return response()->json(['error' => 'Something Went Wrong']);

        }catch (\Exception $e){
            Helpers::logStack([$e->getMessage() . ' in deposite form update consult', "Error"]);
            return response()->json(['error' => 'Something Went Wrong']);
        }

    }

}
