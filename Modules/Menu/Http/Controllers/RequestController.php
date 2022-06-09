<?php

namespace Modules\Menu\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Consult;
use App\Department;
use App\Discount;
use App\Encounter;
use App\Exam;
use App\GroupProc;
use App\Monitor;
use App\Nepalicalendar;
use App\PatBilling;
use App\PatGeneral;
use App\PatientDate;
use App\PatientInfo;
use App\Radio;
use App\ServiceCost;
use App\Test;
use App\User;
use App\Utils\Helpers;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

// use App\PatBilling;

class RequestController extends Controller
{
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayMajorProcedureForm(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['patdata'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', '!=', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        $data['billing'] = BillingSet::select('fldsetname')->get();
        $data['completed_patdata'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        $billingmode = $enpatient->fldbillingmode;
        $data['procedures'] = ServiceCost::select('flditemname')
            ->where('flditemtype', 'Procedures')
            ->where('fldstatus', 'Active')
            ->where('fldtarget', 'LIKE', 'Major')
            ->where(function ($query) use ($billingmode) {
                return $query
                    ->orWhere('fldgroup', '=', $billingmode)
                    ->orWhere('fldgroup', '=', '%');
            })
            ->get();

        // dd($data['procedures']);
        $html = view('menu::menu-dynamic-views.major-procedure-form', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayExtraProcedureForm(Request $request)
    {

        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['patdata'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', '!=', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        $data['completed_patdata'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        $pstatus = array('Done', 'Cleared');
        // echo $encounter_id; exit;
        $data['pending_data'] = PatBilling::select('fldid', 'fldtime', 'flditemname', 'fldrefer', 'fldtarget')->where([
            ['fldencounterval', $encounter_id],
            ['flditemtype', 'Procedures'],
            ['fldtarget', 'Extra'],
            ['fldsave', '1']
        ])->whereIn('fldstatus', $pstatus)->get();
        // dd($data['pending_data']);

        $servicegroup = array($enpatient->fldbillingmode, '%');
        $serviceCostdata = ServiceCost::select('flditemname')->whereIn('fldgroup', $servicegroup)->where('fldstatus', 'Active')->get();
        // dd($serviceCostdata)
        $data['requestGroup_data'] = GroupProc::select('fldgroupname')->distinct()->whereIn('fldgroupname', $serviceCostdata)->get();
        // echo $data['requestGroup_data']; exit;
        $data['punched_data'] = PatBilling::select('fldid', 'fldstatus', 'flditemname', 'fldreason', 'fldtarget', 'fldordtime')
            ->where([
            ['fldencounterval', $encounter_id],
            ['flditemtype', 'Procedures'],
            ['fldtarget', 'Extra'],
            ['fldsave', '0']
        ])->get();

        $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
        $data['patgeneraldata'] = PatGeneral::select('fldid','flditem')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldinput', 'Extra Procedures')
            ->distinct()
            ->get();

        $html = view('menu::menu-dynamic-views.extra-procedure-form', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function getProcedureByBilling(Request $request)
    {
        $html = '';
        if ($request->get('term')) {
            $billing = $request->get('term');

            $fldgroups = array($billing, '%');

            $data = ServiceCost::select('flditemname')->where('flditemtype', 'Procedures')->where('fldstatus', 'Active')->where('fldtarget', 'LIKE', 'Major')->whereIn('fldgroup', $fldgroups)->get();

            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {
                    $html .= '<option value="' . $d->flditemname . '">' . $d->flditemname . '</option>';
                }
            } else {
                $html = '';
            }

        } else {
            $html = '';
        }

        echo $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function addProcedure(Request $request)
    {
        $html = '';
        // echo $request->procedure;
        $existingdata = PatGeneral::where('flditem', $request->procedure)->where('fldencounterval', $request->encounterId)->where('fldstatus', 'Waiting')->where('fldreportquali', '!=', 'Done')->get();
        // echo $existingdata; exit;
        try {
            if (isset($existingdata) and count($existingdata) > 0) {
                // echo "hersdfsdf"; exit;
                return response()->json([
                    'status' => true,
                    'html' => 'available'
                ]);
                // $html .='available';
            } else {

                $mytime = Carbon::now();
                $data['fldencounterval'] = $request->encounterId;
                $data['fldinput'] = 'Procedures';
                $data['flditem'] = $request->procedure;
                $data['fldreportquali'] = $request->status;
                $data['fldstatus'] = 'Waiting';
                $data['flddetail'] = NULL;
                $data['fldnewdate'] = $request->date;
                $data['fldbillingmode'] = $request->billing;
                $data['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $data['flduserid'] = NULL;
                $data['fldtime'] = $mytime->toDateTimeString();
                $data['fldcomp'] = Helpers::getCompName();
                $data['fldsave'] = 0;
                $data['flduptime'] = NULL;
                $data['xyz'] = 0;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatGeneral::insert($data);

                $patdata = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
                    ['fldencounterval', $request->encounterId],
                    ['fldinput', 'Procedures'],
                    ['fldreportquali', '!=', 'Done'],
                    ['fldstatus', 'Waiting']
                ])->get();
                if (isset($patdata) and count($patdata) > 0) {
                    foreach ($patdata as $k => $data) {
                        $sn = $k + 1;
                        $html .= '<tr>';
                        $html .= '<td><input type="checkbox" name="procedureId" class="procedureId" value="' . $data->fldid . '"></td>';
                        $html .= '<td>' . $sn . '</td>';
                        $html .= '<td>' . $data->fldnewdate . '</td>';
                        $html .= '<td>' . $data->flditem . '</td>';
                        $html .= '<td>' . $data->fldreportquali . '</td>';
                        $html .= '<td><a href="javascript:;" onclick="deletePro(' . $data->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html = '';
                }
                return response()->json([
                    'status' => true,
                    'html' => $html
                ]);
            }

            // return $html;
        } catch (\Exception $e) {
            dd($e);
        }


    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function addExtraProcedure(Request $request)
    {
        $html = '';

        try {

            $procedures = $request->procedures;
            // echo $procedures; exit;
            $procData = explode(',', $procedures);
            $encounter = Encounter::where('fldencounterval', $request->encounterId)->first();
            $mytime = Carbon::now();
            $discount = Discount::select('fldpercent', 'fldamount')->where('fldmode', $encounter->flddisctype)->first();
            if (isset($discount)) {
                $discPer = $discount->fldpercent;
                $discAmount = $discount->fldamount;
            } else {
                $discPer = 0;
                $discAmount = 0;
            }

            if (isset($procData) and count($procData) > 0) {
                foreach ($procData as $value) {

                    $serviceData = ServiceCost::where('flditemname', $value)->first();

                    if (isset($serviceData)) {
                        $data['fldencounterval'] = $request->encounterId;
                        $data['fldbillingmode'] = $encounter->fldbillingmode;
                        $data['flditemtype'] = 'Procedures';
                        $data['flditemno'] = $serviceData->fldid;
                        $data['flditemname'] = $value;
                        $data['flditemrate'] = $serviceData->flditemcost;
                        $data['flditemqty'] = 1;
                        $data['fldtaxper'] = 0;
                        $data['flddiscper'] = $discPer;
                        $data['fldtaxamt'] = 0;
                        $data['flddiscamt'] = $discAmount;
                        $data['fldditemamt'] = $serviceData->flditemcost;
                        $data['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                        $data['fldordtime'] = $mytime->toDateTimeString();
                        $data['fldordcomp'] = Helpers::getCompName();
                        $data['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                        $data['fldtime'] = $mytime->toDateTimeString();
                        $data['fldcomp'] = Helpers::getCompName();
                        $data['fldsave'] = 0;
                        $data['fldbillno'] = NULL;
                        $data['fldparent'] = 0;
                        $data['fldprint'] = 0;
                        $data['fldstatus'] = 'Punched';
                        $data['fldalert'] = 1;
                        $data['fldtarget'] = 'Extra';
                        $data['fldpayto'] = NULL;
                        $data['fldrefer'] = $request->referto;
                        $data['fldreason'] = NULL;
                        $data['fldretbill'] = NULL;
                        $data['fldretqty'] = 0;
                        $data['fldsample'] = 'Waiting';
                        $data['xyz'] = 0;
                        PatBilling::insert($data);
                    }
                }
                $punchedData = PatBilling::select('fldid', 'fldstatus', 'flditemname', 'fldreason', 'fldtarget', 'fldordtime')->where([
                    ['fldencounterval', $request->encounterId],
                    ['flditemtype', 'Procedures'],
                    ['fldtarget', 'Extra'],
                    ['fldsave', '0']
                ])->get();
                // dd($punchedData);
                if (isset($punchedData) and count($punchedData) > 0) {
                    foreach ($punchedData as $k => $pvalue) {
                        $sn = $k + 1;
                        $html .= '<tr>';
                        $html .= '<td><input type="checkbox" checked="checked" name="punched_procedure" class="punched_procedure" value="' . $pvalue->fldid . '" style="display:none;"></td>';
                        $html .= '<td>' . $pvalue->fldordtime . '</td>';
                        $html .= '<td>' . $pvalue->flditemname . '</td>';
                        $html .= '<td>' . $pvalue->fldstatus . '</td>';
                        $html .= '<td><a href="javascript:void(0)" onclick="deletePunchedProcedure(' . $pvalue->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html = '';
                }
                return response()->json([
                    'status' => true,
                    'html' => $html
                ]);

            } else {
                return response()->json([
                    'status' => true,
                    'html' => 'Error'
                ]);
            }

        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function editProcedure(Request $request)
    {

        $html = '';
        $mytime = Carbon::now();
        $data['fldencounterval'] = $request->encounterId;
        $data['fldinput'] = 'Procedures';
        $data['flditem'] = $request->procedure;
        $data['fldreportquali'] = $request->status;
        $data['fldstatus'] = 'Waiting';
        $data['flddetail'] = NULL;
        $data['fldnewdate'] = $request->date;
        $data['fldbillingmode'] = $request->billing;
        $data['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
        $data['flduserid'] = NULL;
        $data['flduptime'] = $mytime->toDateTimeString();
        $data['fldcomp'] = 'comp01';
        $data['fldsave'] = 0;
        $data['flduptime'] = NULL;
        $data['xyz'] = 0;
        $patgeneraldata = PatGeneral::where([['fldid', $request->proID]])->update($data);

        $patdata = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', '!=', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        if (isset($patdata) and count($patdata) > 0) {
            foreach ($patdata as $k => $data) {
                $sn = $k + 1;
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" name="procedureId" class="procedureId" value="' . $data->fldid . '"></td>';
                $html .= '<td>' . $sn . '</td>';
                $html .= '<td>' . $data->fldnewdate . '</td>';
                $html .= '<td>' . $data->flditem . '</td>';
                $html .= '<td>' . $data->fldreportquali . '</td>';
                $html .= '<td><a href="javascript:;" onclick="deletePro(' . $data->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                $html .= '</tr>';
            }
        } else {
            $html = '';
        }

        echo $html;

    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function deleteProcedure(Request $request)
    {

        $html = '';

        $patgeneraldata = PatGeneral::where('fldid', $request->proID)->where('fldstatus', 'Waiting')->delete();

        $patdata = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
            ['fldencounterval', $request->encounterId],
            ['fldinput', 'Procedures'],
            ['fldreportquali', '!=', 'Done'],
            ['fldstatus', 'Waiting']
        ])->get();
        if (isset($patdata) and count($patdata) > 0) {
            foreach ($patdata as $k => $data) {
                $sn = $k + 1;
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" name="procedureId" class="procedureId" value="' . $data->fldid . '"></td>';
                $html .= '<td>' . $sn . '</td>';
                $html .= '<td>' . $data->fldnewdate . '</td>';
                $html .= '<td>' . $data->flditem . '</td>';
                $html .= '<td>' . $data->fldreportquali . '</td>';
                $html .= '<td><a href="javascript:;" onclick="deletePro(' . $data->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                $html .= '</tr>';
            }
        } else {
            $html = '';
        }

        echo $html;

    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function deleteExtraProcedure(Request $request)
    {

        $html = '';

        $patgeneraldata = PatBilling::where('fldid', $request->procId)->delete();

        $punchedData = PatBilling::select('fldid', 'fldstatus', 'flditemname', 'fldreason', 'fldtarget', 'fldordtime')->where([
            ['fldencounterval', $request->encounterId],
            ['flditemtype', 'Procedures'],
            ['fldtarget', 'Extra'],
            ['fldsave', '0']
        ])->get();

        if (isset($punchedData) and count($punchedData) > 0) {

            foreach ($punchedData as $k => $pvalue) {
                $sn = $k + 1;
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" name="punched_procedure" class="punched_procedure" value="' . $pvalue->fldid . '" style="display:none;" checked="checked"></td>';
                $html .= '<td>' . $pvalue->fldordtime . '</td>';
                $html .= '<td>' . $pvalue->flditemname . '</td>';
                $html .= '<td>' . $pvalue->fldstatus . '</td>';
                $html .= '<td><a href="javascript:void(0)" onclick="deletePunchedProcedure(' . $pvalue->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                $html .= '</tr>';
            }
        } else {
            $html = '';
        }
        echo $html;
        exit;
    }

    public function englishtonepali(Request $request)
    {

        $value = $request->date;
        $cal = new Nepalicalendar();
        list($y, $m, $d) = explode("-", $value);
        $date = $cal->eng_to_nep($y, $m, $d);


        $nepalidate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];
        echo $nepalidate;
        exit;
    }

    public function nepalitoenglish(Request $request)
    {

        $value = $request->date;
        $cal = new Nepalicalendar();
        list($y, $m, $d) = explode("-", $value);
        $date = $cal->nep_to_eng($y, $m, $d);


        $englishdate = $date['year'] . '-' . $date['month'] . '-' . $date['date'];
        echo $englishdate;
        exit;
    }


    #Monitoring

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayMonitoringForm(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['monitordata'] = Monitor::select('fldid', 'fldcategory', 'flditem', 'fldevery', 'fldunit')->where([
            ['fldencounterval', $request->encounterId],
            ['fldstatus', 'Continue']
        ])->get();

        $html = view('menu::menu-dynamic-views.monitoring-form', $data)->render();
        return $html;
    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function getMonitoringParticulars(Request $request)
    {
        $html = '';
        if ($request->get('term')) {
            $term = $request->get('term');
            if ($term == 'Test') {
                $column = 'fldtestid';
                $data = Test::select('fldtestid')->where('fldtype', 'Like', '%')->get();
            } elseif ($term == 'Exam') {
                $column = 'fldexamid';
                $data = Exam::select('fldexamid')->where('fldtype', 'Like', '%')->get();
            } elseif ($term == 'Radio') {
                $column = 'fldexamid';
                $data = Radio::select('fldexamid')->where('fldtype', 'Like', '%')->get();
            } else {
                $column = '';
                $data = array();
            }


            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {

                    $html .= '<option value="' . $d->$column . '">' . $d->$column . '</option>';
                }
            } else {
                $html = '';
            }

        } else {
            $html = '';
        }

        echo $html;
    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function addMonitor(Request $request)
    {

        $html = '';
        $mytime = Carbon::now();
        $data['fldencounterval'] = $request->encounterId;
        $data['fldcategory'] = $request->category;
        $data['flditem'] = $request->item;
        $data['fldevery'] = $request->freq;
        $data['fldunit'] = $request->unit;
        $data['fldtype'] = 'Registered';
        $data['fldstatus'] = 'Continue';
        $data['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
        $data['fldtime'] = $mytime->toDateTimeString();
        $data['fldcomp'] = 'comp01';
        $data['fldsave'] = 0;
        $data['xyz'] = 0;
        $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
        Monitor::insert($data);

        $monitordata = Monitor::select('fldid', 'fldcategory', 'flditem', 'fldevery', 'fldunit')->where([
            ['fldencounterval', $request->encounterId],
            ['fldstatus', 'Continue']
        ])->get();
        if (isset($monitordata) and count($monitordata) > 0) {
            foreach ($monitordata as $k => $data) {
                $sn = $k + 1;
                $html .= '<tr>';

                $html .= '<td>' . $sn . '</td>';
                $html .= '<td>' . $data->fldcategory . '</td>';
                $html .= '<td>' . $data->flditem . '</td>';
                $html .= '<td>' . $data->fldevery . '</td>';
                $html .= '<td>' . $data->fldunit . '</td>';
                $html .= '<td><a href="javascript:;" onclick="deleteMonitor(' . $data->fldid . ')" class="text-danger"><i class="ri-delete-bin-5-fill"></i></a></td>';
                $html .= '</tr>';
            }
        } else {
            $html = '';
        }

        echo $html;

    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function deleteMonitor(Request $request)
    {

        $html = '';
        $data['fldstatus'] = 'Discontinue';
        $data['xyz'] = 0;
        Monitor::where('fldid', $request->monitorID)->update($data);

        $monitordata = Monitor::select('fldid', 'fldcategory', 'flditem', 'fldevery', 'fldunit')->where([
            ['fldencounterval', $request->encounterId],
            ['fldstatus', 'Continue']
        ])->get();
        if (isset($monitordata) and count($monitordata) > 0) {
            foreach ($monitordata as $k => $data) {
                $sn = $k + 1;
                $html .= '<tr>';

                $html .= '<td>' . $sn . '</td>';
                $html .= '<td>' . $data->fldcategory . '</td>';
                $html .= '<td>' . $data->flditem . '</td>';
                $html .= '<td>' . $data->fldevery . '</td>';
                $html .= '<td>' . $data->fldunit . '</td>';
                $html .= '<td><a href="javascript:;" onclick="deleteMonitor(' . $data->fldid . ')" class="text-danger"><i class="ri-delete-bin-5-fill"></i></a></td>';
                $html .= '</tr>';
            }
        } else {
            $html = '';
        }

        echo $html;

    }

    #End Monitoring

    #Start Outcome
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayRefertoForm(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['monitordata'] = Monitor::select('fldid', 'fldcategory', 'flditem', 'fldevery', 'fldunit')->where([
            ['fldencounterval', $request->encounterId],
            ['fldstatus', 'Continue']
        ])->get();

        $html = view('menu::menu-dynamic-views.monitoring-form', $data)->render();
        return $html;
    }


    #Enf Referto

    /*
     * Consultation
     */
    public function displayConsultantForm(Request $request)
    {
        $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldadmission', 'fldcurrlocat', 'fldbillingmode', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $data['department'] = Department::select('flddept')
            ->where('fldcateg', 'Consultation')
            ->get();

        $data['consult_list'] = Consult::select('fldid', 'fldconsulttime', 'fldencounterval', 'fldconsultname', 'flduserid', 'fldcomment')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldstatus', 'Planned')
            ->get();

        $data['consult_list_complete'] = Consult::select('fldid', 'fldconsulttime', 'fldencounterval', 'fldconsultname', 'fldorduserid')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldstatus', 'Done')
            ->where('fldsave', 1)
            ->with('user')
            ->get();

        $data['consultation'] = CogentUsers::where('fldopconsult', 1)->get();

        $html = view('menu::menu-dynamic-views.consultation-form', $data)->render();
        return $html;
    }

    public function addConsultation(Request $request)
    {
        try {
            $insertData['fldencounterval'] = $request->encounter;
            $insertData['fldconsultname'] = $request->consultationDeartment;
            $insertData['fldconsulttime'] = $request->consultation_date;
            $insertData['fldcomment'] = $request->consultation_comment;
            $insertData['fldstatus'] = 'Planned';
            $insertData['flduserid'] = NULL;
            $insertData['fldbillingmode'] = $request->billing_mode;
            $insertData['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
            $insertData['fldtime'] = date("Y-m-d H:i:s");
            //            $insertData['fldcomp'] = "comp01";
            $insertData['fldsave'] = 0;
            $insertData['is_refer'] = 1;
            $insertData['xyz'] = 0;
            $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            Consult::insert($insertData);

            $insertDataPatientDate['fldencounterval'] = $request->encounter;
            $insertDataPatientDate['fldhead'] = 'Registered';
            $insertDataPatientDate['fldcomment'] = NULL;
            $insertDataPatientDate['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
            $insertDataPatientDate['flduptime'] = NULL;
            $insertDataPatientDate['fldtime'] = date("Y-m-d H:i:s");
            //            $insertDataPatientDate['fldcomp'] = "comp01";
            $insertDataPatientDate['fldsave'] = 1;
            $insertDataPatientDate['xyz'] = 0;
            $insertDataPatientDate['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatientDate::insert($insertDataPatientDate);


            $encounterUpdateData['fldadmission'] = 'Registered';
            $encounterUpdateData['xyz'] = 0;
            $encounterUpdateData['fldcurrlocat'] = $request->consultationDeartment;
            Encounter::where([['fldencounterval', $request->encounter]])->update($encounterUpdateData);

            $data['consult_list'] = Consult::select('fldid', 'fldconsulttime', 'fldencounterval', 'fldconsultname', 'flduserid', 'fldcomment')
                ->where('fldencounterval', $request->encounter)
                ->where('fldstatus', 'Planned')
                ->get();

            $data['currentLocation'] = $request->consultationDeartment;
            $data['html'] = view('menu::menu-dynamic-views.consulting-list-requested', $data)->render();
            return $data;

        } catch (\GearmanException $e) {
            return $e;
        }
    }

    public function deleteConsultation(Request $request)
    {
        Consult::where('fldid', $request->fldid)->delete();

        $data['consult_list'] = Consult::select('fldid', 'fldconsulttime', 'fldencounterval', 'fldconsultname', 'flduserid', 'fldcomment')
            ->where('fldencounterval', $request->encounter_id)
            ->where('fldstatus', 'Planned')
            ->get();

        $html = view('menu::menu-dynamic-views.consulting-list-requested', $data)->render();
        return $html;
    }


    public function saveExtraProcedure(Request $request)
    {
        // echo $request->procedures; exit;
        $html = '';

        try {
            $punchedProc = explode(',', $request->procedures);
            if (isset($punchedProc) and count($punchedProc) > 0) {
                $mytime = Carbon::now();
                foreach ($punchedProc as $pdata) {
                    $patbillingdata = PatBilling::where('fldid', $pdata)->first();
                    $enpatient = Encounter::where('fldencounterval', $request->encounterId)->first();
                    $serviceData = ServiceCost::where('flditemname', $patbillingdata->flditemname)->first();
                    $groupproc = GroupProc::where('fldgroupname', $patbillingdata->flditemname)->get();

                    $patgeneraldata = PatGeneral::where('flditem', $serviceData->fldbillitem)->first();

                    if (!isset($patgeneraldata) or empty($patgeneraldata)) {
                        if (isset($groupproc) and count($groupproc) > 0) {
                            foreach ($groupproc as $gp) {
                                $pgdata['fldencounterval'] = $request->encounterId;
                                $pgdata['fldinput'] = 'Extra Procedures';
                                $pgdata['flditem'] = $groupproc->fldprocname;
                                $pgdata['fldreportquali'] = 'Planned';
                                $pgdata['fldstatus'] = 'Cleared';
                                $pgdata['flddetail'] = NULL;
                                $pgdata['fldnewdate'] = $mytime->toDateTimeString();
                                $pgdata['fldbillingmode'] = $enpatient->fldbillingmode;
                                $pgdata['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                                $pgdata['flduserid'] = NULL;
                                $pgdata['fldtime'] = $mytime->toDateTimeString();
                                $pgdata['fldcomp'] = Helpers::getCompName();
                                $pgdata['fldsave'] = 0;
                                $pgdata['flduptime'] = NULL;
                                $pgdata['xyz'] = 0;
                                $pgdata['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                                PatGeneral::insert($pgdata);
                            }
                        }

                    }


                    $updatedata['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                    $updatedata['fldtime'] = $mytime->toDateTimeString();
                    $updatedata['fldcomp'] = Helpers::getCompName();
                    $updatedata['fldsave'] = 1;
                    $updatedata['fldstatus'] = 'Done';
                    $updatedata['fldsample'] = 'Sampled';
                    $updatedata['xyz'] = 0;

                    PatBilling::where([['fldid', $pdata]])->update($updatedata);
                }
            }
            $punchedData = PatBilling::select('fldid', 'fldstatus', 'flditemname', 'fldreason', 'fldtarget', 'fldordtime')->where([
                ['fldencounterval', $request->encounterId],
                ['flditemtype', 'Procedures'],
                ['fldtarget', 'Extra'],
                ['fldsave', '0']
            ])->get();

            if (isset($punchedData) and count($punchedData) > 0) {

                foreach ($punchedData as $k => $pvalue) {
                    $sn = $k + 1;
                    $html .= '<tr>';
                    $html .= '<td><input type="checkbox" name="punched_procedure" class="punched_procedure" value="' . $pvalue->fldid . '" style="display:none;" checked="checked"></td>';
                    $html .= '<td>' . $pvalue->fldordtime . '</td>';
                    $html .= '<td>' . $pvalue->flditemname . '</td>';
                    $html .= '<td>' . $pvalue->fldstatus . '</td>';
                    $html .= '<td><a href="javascript:void(0)" onclick="deletePunchedProcedure(' . $pvalue->fldid . ')"><i class="fas fa-trash-alt"></i></a></td>';
                    $html .= '</tr>';
                }
            } else {
                $html = '';
            }
            $pstatus = array('Done', 'Cleared');
            $pendingData = PatBilling::select('fldid', 'fldtime', 'flditemname', 'fldrefer', 'fldtarget')->where([
                ['fldencounterval', $request->encounterId],
                ['flditemtype', 'Procedures'],
                ['fldtarget', 'Extra'],
                ['fldsave', '1']
            ])->whereIn('fldstatus', $pstatus)->get();
            $phtml = '';
            if (isset($pendingData) and count($pendingData) > 0) {
                foreach ($pendingData as $k => $pd) {
                    $sn = $k + 1;
                    $phtml .= '<tr>';
                    $phtml .= '<td>' . $sn . '</td>';
                    $phtml .= '<td>' . $pd->fldtime . '</td>';
                    $phtml .= '<td>' . $pd->flditemname . '</td>';
                    $phtml .= '<td>' . $pd->fldrefer . '</td>';
                    $phtml .= '</tr>';
                }
            }
            $shtml = '';
            $sdata = PatGeneral::select('flditem')->where('fldencounterval', $request->encounterId)->where('fldinput', 'Extra Procedures')->distinct()->get();
            if (isset($sdata) and count($sdata) > 0) {
                foreach ($sdata as $sd) {
                    $shtml .= '<option value="' . $sd->flditem . '">' . $sd->flditem . '</option>';
                }
            }
            $data['shtml'] = $shtml;
            $data['html'] = $html;
            $data['phtml'] = $phtml;
            return $data;
        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function listPlannedData(Request $request)
    {
        $searchtext = $request->searchdata;
        $html = '';
        try {
            $compstatus = array('Planned', 'Done');
            if ($searchtext != '') {
                $result = PatGeneral::select('fldid', 'flditem', 'fldreportquali', 'fldnewdate', 'fldstatus', 'flddetail')
                    ->where('fldencounterval', $request->encounterId)
                    ->where('flditem', 'LIKE', $searchtext)
                    ->where('fldinput', 'Extra Procedures')
                    ->whereIn('fldreportquali', $compstatus)
                    ->get();
            } else {
                $result = PatGeneral::select('fldid', 'flditem', 'fldreportquali', 'fldnewdate', 'fldstatus', 'flddetail')
                    ->where('fldencounterval', $request->encounterId)
                    ->where('flditem', 'LIKE', '%')
                    ->where('fldinput', 'Extra Procedures')
                    ->whereIn('fldreportquali', $compstatus)
                    ->get();
            }
            if (isset($result) and count($result) > 0) {
                foreach ($result as $k => $data) {
                    $sn = $k + 1;
                    $html .= '<tr>';
                    $html .= '<td>' . $sn . '</td>';
                    $html .= '<td>' . $data->flditem . '</td>';
                    $html .= '<td>' . $data->fldreportquali . '</td>';
                    $html .= '<td>' . $data->fldnewdate . '</td>';
                    $html .= '<td>' . $data->fldstatus . '</td>';
                    $html .= '<td>' . $data->flddetail . '</td>';
                    $html .= '</tr>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            //            dd($e);
        }

    }


    /**
     * @return array|string
     * @throws \Throwable
     */
    public function populateData(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $result = PatGeneral::where('fldid', $request->proID)->where('fldencounterval', $encounter_id)->first();
        // dd($result);
        $data['flditem'] = $result->flditem;
        $data['fldstatus'] = $result->fldreportquali;
        if ($request->flduptime != '') {
            $data['fldtime'] = $result->flduptime;
        } else {
            $data['fldtime'] = $result->fldtime;
        }
        // dd($data);
        return $data;
    }
}
