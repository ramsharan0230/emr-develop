<?php

namespace Modules\Inpatient\Http\Controllers;

use App\Consult;
use App\Departmentbed;
use App\Encounter;
use App\undoDischargeLog;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Console\Helper\Helper;

class InpatientListController extends Controller
{
    public function index()
    {
        $data['departments'] = DB::table('tbldepartment')
            ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
            ->where('tbldepartment.fldcateg', 'Patient Ward')
            ->orWhere('tbldepartment.fldcateg', 'Emergency')
            ->select('tbldepartment.flddept')
            ->groupBy('tbldepartment.flddept')
            ->get();
        $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;

        $data['encounters'] = Encounter::where('fldadmission', 'Admitted')->orderBy('fldencounterval', 'ASC')
            ->with('patientInfo','consultant.user', 'departmentBed','room')
//            ->whereDate('flddoa', '>=', date('Y-m-d') )->whereDate('flddoa', '<=',date('Y-m-d') )
            ->get();
//                ->take(10)
//        ->get();
//        dd($data);
        //where date aaja ko
        return view('inpatient::inpatient-list.index', $data);
    }

    public function search(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        try {
         //   DB::enableQueryLog(); // Enable query log
            // Pachi requirement change bhayo tala lekheko filter kam lagena so new modify gareko
            $query = Encounter::with('patientInfo', 'consultant.user', 'departmentBed', 'room');
            if ($from_date && $to_date && !($request->discharge) && !($request->both)) {
                $query->where('flddoa', '>=', $from_date." 00:00:00")->where('flddoa', '<=', $to_date. " 23:59:59");

            }

            if ($request->admitted) {
                $query->where('fldadmission', 'Admitted')
                   ->where('flddoa', '>=', $from_date. " 00:00:00")
                   ->where('flddoa', '<=', $to_date. " 23:59:59.999");
            }

            if ($request->discharge && $from_date && $to_date) {
                $query->where('fldadmission', 'Discharged')->where('flddod', '>=', $from_date. " 00:00:00")->where('flddod', '<=', $to_date. " 23:59:59");
            }
            if ($request->both && $from_date && $to_date) {
                $query->where(function ($query) use ($from_date,$to_date){
                    $query->where('fldadmission', 'Admitted')
                        ->where('flddoa', '>=', $from_date. " 00:00:00")
                        ->where('flddoa', '<=', $to_date. " 23:59:59.999");
                })
                ->orWhere(function ($query) use ($from_date,$to_date){
                    $query->where('fldadmission', 'Discharged')
                        ->where('flddod', '>=', $from_date. " 00:00:00")
                        ->where('flddod', '<=', $to_date. " 23:59:59");
                });
            //     $query->where(function ($both) {
            //         $both->orWhere('fldadmission', 'Admitted')
            //             ->orWhere('fldadmission', 'Discharged');
            //     })->where(function ($date) use ($from_date,$to_date) {
            //         $date->where('flddoa', '>=', $from_date. " 00:00:00")->where('flddoa', '<=', $to_date. " 23:59:59")
            //             ->where('flddod', '>=', $from_date. " 00:00:00")->orWhere('flddod', '<=', $to_date. " 23:59:59");
            //     });


            }
            $patients = $query->get();
           // dd(DB::getQueryLog());
//            dd($patients);
            if (($patients && $patients->count() > 0)) {
                $count = 1;
                $html = '';
                $html = '<table id="myTable2" data-show-columns="true"
                            data-search="true"
                            data-show-toggle="true"
                            data-pagination="true"
                            data-resizable="true">
                        <thead>
                        <tr>
                            <th class="text-center">S.N</th>
                            <th class="text-center">IP Date</th>
                            <th class="text-center">Dis Date</th>
                            <th class="text-center">Hosp No</th>
                            <th class="text-center">IP No</th>
                            <th class="text-center">Ward/BNo/Room.</th>
                            <th class="text-center">Fname</th>
                            <th class="text-center">Lname</th>
                            <th class="text-center">Doctor</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Age</th>
                            <th class="text-center">Billno</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Guardian</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>';
                foreach ($patients as $patient) {
                    $html .= '<tr class="list_tr" id="list_tr' . $patient->fldencounterval . '" data-encounter=' . $patient->fldencounterval . ' data-patient=' . $patient->fldpatientval . '>';
//                    $html .= '<td><input type="checkbox" class="list_tr" data-encounter='.$patient->fldencounterval.' data-patient='.$patient->fldpatientval.'></td>';
                    $html .= '<td>' . ( $count++ ). '</td>';
                    $html .= '<td>' . ($patient->flddoa ? Helpers::dateEngToNepdash(Carbon::parse($patient->flddoa)->format('Y-m-d'))->full_date : null ). '</td>';
                    $html .= '<td>' . ($patient->flddod ? Helpers::dateEngToNepdash(Carbon::parse($patient->flddod)->format('Y-m-d'))->full_date : null ). '</td>';
                    $html .= '<td>' . $patient->fldpatientval . '</td>';
                    $html .= '<td>' . $patient->fldencounterval . '</td>';
                    if (isset($patient) && $patient->fldadmission == 'Admitted') {
                    $html .= '<td> <span class="bed_number">' .
                        ((isset($patient->departmentBed) && $patient->departmentBed->flddept) ?  $patient->departmentBed->flddept : '')
//                        (($patient->fldcurrlocat) ? ($patient->fldcurrlocat=='Null' ? '' : $patient->fldcurrlocat ) : '')
                        .
                        ' ' .
                        ((isset($patient->departmentBed) && $patient->departmentBed->fldbed) ? '/' . $patient->departmentBed->fldbed : '') . '</span>' .
                        ((isset($patient->room) && $patient->room->fldroom) ? '/' . $patient->room->fldroom : '') . '</td>';
                    } else {
                        $bed = Helpers::getPatientBed($patient->fldencounterval);
                        $departmentBed = $bed && $bed->fldcomment ? Helpers::getDepartmentFromBED($bed->fldcomment) : null;

                        $html .= '<td> <span class="bed_number">' .
                        (($departmentBed) ?  $departmentBed : '') . '</span>' .
                        (($bed && $bed->fldcomment) ? '/' . $bed->fldcomment : '') . '</td>';
                    }
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fldptnamefir) ? $patient->patientInfo->fldptnamefir : '') . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fldptnamelast) ? $patient->patientInfo->fldptnamelast : '') . '</td>';
                    $html .= '<td>' . ((isset($patient) && $patient->user) ? $patient->user->fldtitlefullname : null) . '</td>';
//                    $html .= '<td>' . ((isset($patient->consultant) && $patient->consultant->flduserid) ? $patient->consultant->flduserid : null) . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fldptsex) ? $patient->patientInfo->fldptsex : '') ?? null . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) ? $patient->patientInfo->age() : '')) . '</td>';
                    $bill = '';
                    if (isset($patient) && $patient->fldadmission == 'Discharged') {
//                        $bills = (isset($patient) && $patient->patBillDetails && $patient->patBillDetails) ? $patient->patBillDetails->where('fldpayitemname', 'Discharge Clearance')->pluck('fldbillno')->toArray() : '';
//                        $bill = implode(',', array_filter($bills, 'strlen'));
                        $bill = Helpers::getDischargeBill($patient->fldencounterval);
                    }
                    $html .= '<td>' . (isset($bill) ? $bill : null) . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fldptcontact) ? $patient->patientInfo->fldptcontact : '') . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fulladdress) ? $patient->patientInfo->fulladdress : '') . '</td>';
                    $html .= '<td>' . ((isset($patient->patientInfo) && $patient->patientInfo->fldptguardian) ? $patient->patientInfo->fldptguardian : '') . '</td>';
                    $html .= '<td>
                                            <div class="dropdown">
                                               <button class="btn btn-primary dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                                if (isset($patient) && $patient->fldadmission == 'Discharged'){
                                                    $html.='<a class="dropdown-item undo_discharge"  data-encounter="'.(isset($patient->fldencounterval) ? $patient->fldencounterval : '').'" data-patient="'.(isset($patient->fldpatientval) ? $patient->fldpatientval : '').'" id="undo_discharge_btn" url="' . route('inpatientlist.undo.discharge') . '" >Undo Discharge</a>';
                                                }else{
                                                    $html .='<a class="dropdown-item bed_exchange" data-encounter="' . (isset($patient->fldencounterval) ? $patient->fldencounterval : '') . '" data-bedid="' . (isset($patient->fldcurrlocat) ? $patient->fldcurrlocat : '') . '">Bed Exchange</a>
                                                <a class="dropdown-item " id="discharge_billing_btn" href="' . route('billing.dischargeClearance', ['encounter_id' => (isset($patient->fldencounterval) ? $patient->fldencounterval : '')]) . '" target="_blank">Discharge Billing</a>
                                                <a class="dropdown-item " id="creadit_btn" href="' . route('billing.display.form', ['encounter_id' => (isset($patient->fldencounterval) ? $patient->fldencounterval : '')]) . '"  target="_blank">Credit Billing</a>
                                                <a class="dropdown-item " id="deposit_billing_btn" href="' . route('depositForm', ['encounter_id' => (isset($patient->fldencounterval) ? $patient->fldencounterval : '')]) . '" target="_blank">Deposit Billing </a>
                                                <a class="dropdown-item " id="pharmacy_sale_btn" href="' . route('dispensingForm', ['encounter_id' => (isset($patient->fldencounterval) ? $patient->fldencounterval : '')]) . '" target="_blank">Pharmacy Sale</a>
                                                <a class="dropdown-item " id="transition_btn" href="' . route('dataview.transitions', ['encounter_id' => (isset($patient->fldencounterval) ? $patient->fldencounterval : '')]) . '" target="_blank">Transitions</a>';

                                                    if (isset($patient) && $patient->fldadmission == 'Discharged'){
                                                        $html.='<a class="dropdown-item undo_discharge"  data-encounter="'.(isset($patient->fldencounterval) ? $patient->fldencounterval : '').'" data-patient="'.(isset($patient->fldpatientval) ? $patient->fldpatientval : '').'" id="undo_discharge_btn" url="' . route('inpatientlist.undo.discharge') . '" >Undo Discharge</a>';
                                                    }
                                                }

                    $html .= '</div></td></td></tr>';

                }
                return \response()->json($html);
            } else {
                $html = '<tr><td colspan="15" align="center">No data available</td></tr>';
                return \response()->json($html);
            }
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function getRelatedBed()
    {
        $flddept = Input::get('flddept');
        $data['get_related_data'] = Departmentbed::where([
            'flddept' => $flddept
            // 'fldencounterval' => null
        ])->select('fldbed', 'fldencounterval', 'flddept')->get();

        $html = view('emergency::dynamic-views.related-bed', $data)->render();

        return response()->json([
            'status' => TRUE,
            'message' => 'Successfully.',
            'html' => $html
        ]);
    }

    // Get Department Location
    public function getDepartmentLocation()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_related_data = Encounter::where('fldencounterval', $fldencounterval)->select('fldcurrlocat')->first();
        return response()->json($get_related_data);
    }

    //Add Undo discharge Log

    public function undoDischarge(Request $request){
       if (!$request->encounter){
           return \response()->json(['error' =>'Something went wrong!']);
       }
        try {

            if($request->encounter){
                DB::beginTransaction();
                $encounter_exist= Encounter::where('fldencounterval',$request->encounter)->first();
                if($encounter_exist){
                   Encounter::where('fldencounterval',$encounter_exist->fldencounterval)->update(['fldadmission' =>'Admitted','flddod'=>null]);
                    $data = [
                        'fldencounterval' => $encounter_exist->fldencounterval,
                        'fldpatientval' => $encounter_exist->fldpatientval ?? '',
                        'flddate' => date('Y-m-d'),
                        'fldtime' => date('H:i:s'),
                        'flduserid' => Helpers::getCurrentUserName(),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                    ];
                    undoDischargeLog::create($data);
                }
                DB::commit();
                return \response()->json(['message' =>'Undo discharge done!']);
            }
        }catch (\Exception $exception){
           return \response()->json(['error' =>'Something went wrong!']);
        }
    }
}
