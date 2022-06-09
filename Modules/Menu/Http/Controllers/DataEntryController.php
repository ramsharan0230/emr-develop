<?php

namespace Modules\Menu\Http\Controllers;

use App\CogentUsers;
use App\Encounter;
use App\JobRecord;
use App\PatGeneral;
use App\PatTiming;
use App\ServiceCost;
use App\Utils\Helpers;
use App\VaccDosing;
use App\Vaccine;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class DataEntryController
 * @package Modules\Menu\Http\Controllers
 */
class DataEntryController extends Controller
{
    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function minorProcedureForm(Request $request)
    {
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('fldpatientval', 'fldadmission', 'fldcurrlocat', 'flduserid', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->with('patientInfo')
            ->get();

        $data['serviceCost'] = ServiceCost::select('flditemname')
            ->where('flditemtype', 'LIKE', 'Procedures')
            ->where('fldstatus', 'LIKE', 'Active')
            ->where('fldtarget', 'LIKE', 'Minor')
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('fldgroup', '=', $request->billingmode)
                    ->orWhere('fldgroup', '=', '%');
            })
            ->get();

        $data['patGeneralWaiting'] = PatGeneral::select('fldid', 'fldtime', 'fldencounterval', 'flditem', 'fldreportquali')
            ->where('fldencounterval', $encounter_id)
            ->where('fldinput', 'Minor Procedures')
            ->where('fldreportquali', 'Done')
            ->where('fldstatus', 'Waiting')
            ->where('fldsave', 0)
            ->get();

        $data['patGeneralCleared'] = PatGeneral::select('fldid', 'fldtime', 'fldencounterval', 'flditem', 'fldreportquali')
            ->where('fldencounterval', $encounter_id)
            ->where('fldinput', 'Minor Procedures')
            ->where('fldreportquali', 'Done')
            ->where('fldstatus', 'Cleared')
            ->where('fldsave', 1)
            ->get();

        $data['payable_to'] = CogentUsers::where('fldpayable', 1)->where('status', 'active')->get();

        $html = view('menu::menu-dynamic-views.minor-procedure-form', $data)->render();
        return $html;
    }

    public function listAddWaiting(Request $request)
    {
        try {
            $request->validate([
                'minor_procedure' => 'required',
            ]);

            $insertData['fldencounterval'] = $request->encounter;
            $insertData['fldinput'] = 'Minor Procedures';
            $insertData['flditem'] = $request->minor_procedure;
            $insertData['fldreportquali'] = 'Done';
            $insertData['fldstatus'] = 'Waiting';
            $insertData['flddetail'] = $request->minor_Procedure_Comment;
            $insertData['fldnewdate'] = date("Y-m-d H:i:s");
            $insertData['fldbillingmode'] = $request->billing;
            $insertData['fldorduserid'] = $request->payable_to_add;
            $insertData['flduserid'] = NULL;
            $insertData['fldtime'] = date("Y-m-d H:i:s");
            $insertData['fldcomp'] = Helpers::getCompName();
            $insertData['fldsave'] = 0;
            $insertData['flduptime'] = NULL;
            $insertData['xyz'] = 0;
            $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatGeneral::insert($insertData);

            $data['patGeneralWaitingCleared'] = PatGeneral::select('fldid', 'fldtime', 'fldencounterval', 'flditem', 'fldreportquali')
                ->where('fldencounterval', $request->encounter)
                ->where('fldinput', 'Minor Procedures')
                ->where('fldreportquali', 'Done')
                ->where('fldstatus', 'Waiting')
                ->where('fldsave', 0)
                ->get();

            $html = view('menu::menu-dynamic-views.minor-procedure-waiting-list', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function listSaveCleared(Request $request)
    {
        try {
            if ($request->minor_procedure != "") {
                $insertData['fldencounterval'] = $request->encounter;
                $insertData['fldinput'] = 'Minor Procedures';
                $insertData['flditem'] = $request->minor_procedure;
                $insertData['fldreportquali'] = 'Done';
                $insertData['fldstatus'] = 'Waiting';
                $insertData['flddetail'] = $request->minor_Procedure_Comment;
                $insertData['fldnewdate'] = date("Y-m-d H:i:s");
                $insertData['fldbillingmode'] = $request->billing;
                $insertData['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $insertData['flduserid'] = NULL;
                $insertData['fldtime'] = date("Y-m-d H:i:s");
                $insertData['fldcomp'] = Helpers::getCompName();
                $insertData['fldsave'] = 0;
                $insertData['flduptime'] = NULL;
                $insertData['xyz'] = 0;

                PatGeneral::insert($insertData);
            } else {
                $updateData['fldstatus'] = 'Cleared';
                $updateData['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $updateData['fldcomp'] = Helpers::getCompName();
                $updateData['fldsave'] = 1;
                $updateData['flduptime'] = date("Y-m-d H:i:s");
                $updateData['xyz'] = 0;

                PatGeneral::where('fldencounterval', $request->encounter)->update($updateData);
            }

            $data['patGeneralWaitingCleared'] = PatGeneral::select('fldid', 'fldtime', 'fldencounterval', 'flditem', 'fldreportquali')
                ->where('fldencounterval', $request->encounter)
                ->where('fldinput', 'Minor Procedures')
                ->where('fldreportquali', 'Done')
                ->where('fldstatus', 'Cleared')
                ->where('fldsave', 1)
                ->get();

            $html = view('menu::menu-dynamic-views.minor-procedure-waiting-list', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    /*2020-06-07 13:10:57.954 gb.db.mysql: 0x560819c668f0: DELETE FROM `tblpatgeneral` WHERE fldencounterval='E1001' and fldinput='Minor Procedures' and fldsave='0' and fldcomp='comp01' and fldreportquali='Done'
    2020-06-07 13:10:58.095 gb.db.mysql: 0x560819c668f0: select fldid,fldtime,fldencounterval,flditem,fldreportquali,fldid from tblpatgeneral where fldencounterval='E1001' and fldinput='Minor Procedures' and fldsave='0' and fldreportquali='Done' and fldstatus='Waiting'*/

    public function deleteMinorProcedure(Request $request)
    {
        PatGeneral::where('fldid', $request->fldid)->delete();
        $data['patGeneralWaitingCleared'] = PatGeneral::select('fldid', 'fldtime', 'fldencounterval', 'flditem', 'fldreportquali')
            ->where('fldencounterval', $request->encounter_id)
            ->where('fldinput', 'Minor Procedures')
            ->where('fldreportquali', 'Done')
            ->where('fldstatus', 'Waiting')
            ->where('fldsave', 0)
            ->get();

        $html = view('menu::menu-dynamic-views.minor-procedure-waiting-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function vaccinationForm(Request $request)
    {
        try {
            $encounter_id = $data['encounterId'] = $request->encounterId;

            $VaccinationFormEntry['fldindex'] = date('YmdHis') . ':' . $request->encounterId . ':63000666';
            $VaccinationFormEntry['fldfrmname'] = 'FmVaccine';
            $VaccinationFormEntry['fldfrmlabel'] = 'Vaccination Form';
            $VaccinationFormEntry['flduser'] = Auth::guard('admin_frontend')->user()->flduserid;
            $VaccinationFormEntry['fldcomp'] = Helpers::getCompName();
            $VaccinationFormEntry['fldentrytime'] = date("Y-m-d H:i:s");
            $VaccinationFormEntry['fldexittime'] = NULL;
            $VaccinationFormEntry['fldpresent'] = '1';
            $VaccinationFormEntry['fldhostuser'] = get_current_user();
            $VaccinationFormEntry['fldhostip'] = NULL;
            $VaccinationFormEntry['fldhostname'] = gethostname();

            $MAC = exec('getmac');
            $MAC = strtok($MAC, ' ');

            $VaccinationFormEntry['fldhostmac'] = $MAC;
            JobRecord::insert($VaccinationFormEntry);

            $data['encounter'] = Encounter::select('fldpatientval')
                ->where('fldencounterval', $encounter_id)
                ->get();

            $data['vaccDosing'] = VaccDosing::select('fldid', 'fldtime', 'flditem', 'fldtype', 'fldvalue', 'fldunit', 'fldencounterval')
                ->where('fldencounterval', $encounter_id)
                ->get();

            $data['vaccineList'] = Vaccine::select('flditem')
                ->get();


            $html = view('menu::menu-dynamic-views.vaccination-form', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }

    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function variableForm()
    {
        try {
            $data['vaccineList'] = Vaccine::select('flditem', 'fldid')
                ->get();

            $html = view('menu::menu-dynamic-views.vaccination-variables', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function variableAdd(Request $request)
    {
        try {
            if (Vaccine::where('flditem', 'LIKE', $request->vaccination_variable)->exists()) {
                return "Data Exists";
            } else {
                Vaccine::insert(['flditem' => $request->vaccination_variable]);
                $data['vaccineList'] = Vaccine::select('flditem', 'fldid')
                    ->get();

                $selectOptions = '<option value=""></option>';
                if (count($data['vaccineList'])) {
                    foreach ($data['vaccineList'] as $list) {
                        $selectOptions .= '<option value="' . $list->flditem . '">' . $list->flditem . '</option>';
                    }
                }

                $html['selectData'] = $selectOptions;
                $html['tableData'] = view('menu::menu-dynamic-views.vaccination-variable-list', $data)->render();
                return $html;
            }
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function variableDelete(Request $request)
    {
        try {
            Vaccine::where('fldid', $request->fldid)->delete();
            $data['vaccineList'] = Vaccine::select('flditem', 'fldid')
                ->get();

            $selectOptions = '<option value=""></option>';
            if (count($data['vaccineList'])) {
                foreach ($data['vaccineList'] as $list) {
                    $selectOptions .= '<option value="' . $list->flditem . '">' . $list->flditem . '</option>';
                }
            }

            $html['selectData'] = $selectOptions;
            $html['tableData'] = view('menu::menu-dynamic-views.vaccination-variable-list', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function vaccinationAdd(Request $request)
    {
        $validatedData = $request->validate([
            'name_vaccination' => 'required',
            'vaccination_schedule' => 'required',
            'vaccination_dose' => 'required',
        ]);

        $vaccinationAddData['fldencounterval'] = $request->encounter;
        $vaccinationAddData['flditem'] = $request->name_vaccination;
        $vaccinationAddData['fldtype'] = $request->vaccination_schedule;
        $vaccinationAddData['fldvalue'] = $request->vaccination_dose;
        $vaccinationAddData['fldunit'] = $request->vaccination_unit;
        $vaccinationAddData['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
        $vaccinationAddData['fldtime'] = date("Y-m-d H:i:s");
        $vaccinationAddData['fldcomp'] = Helpers::getCompName();
        $vaccinationAddData['fldsave'] = 1;
        $vaccinationAddData['xyz'] = 0;
        $vaccinationAddData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

        VaccDosing::insert($vaccinationAddData);

        $data['vaccDosing'] = VaccDosing::select('fldid', 'fldtime', 'flditem', 'fldtype', 'fldvalue', 'fldunit', 'fldencounterval')
            ->where('fldencounterval', $request->encounter)
            ->get();

        $html = view('menu::menu-dynamic-views.vaccination-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function vaccinationEdit(Request $request)
    {
        $fldid = $request->val;
        $vaccinationAddData['fldencounterval'] = $request->encounter;
        $vaccinationAddData['flditem'] = $request->name_vaccination;
        $vaccinationAddData['fldtype'] = $request->vaccination_schedule;
        $vaccinationAddData['fldvalue'] = $request->vaccination_dose;
        $vaccinationAddData['fldunit'] = $request->vaccination_unit;
        $vaccinationAddData['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
        $vaccinationAddData['fldtime'] = date("Y-m-d H:i:s");
        $vaccinationAddData['fldcomp'] = Helpers::getCompName();
        $vaccinationAddData['fldsave'] = 1;
        $vaccinationAddData['xyz'] = 0;

        VaccDosing::where([['fldid', $fldid], ['hospital_department_id', Helpers::getUserSelectedHospitalDepartmentIdSession()]])->update($vaccinationAddData);

        $data['vaccDosing'] = VaccDosing::select('fldid', 'fldtime', 'flditem', 'fldtype', 'fldvalue', 'fldunit', 'fldencounterval')
            ->where('fldencounterval', $request->encounter)
            ->get();

        $html = view('menu::menu-dynamic-views.vaccination-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function essenexamForm(Request $request)
    {
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('flduserid', 'fldpatientval', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->with('patientInfo')
            ->get();


        $html = view('menu::menu-dynamic-views.essenexam-form', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function equipmentsForm(Request $request)
    {
        $encounter_id = $data['encounterId'] = $request->encounterId;

        $data['encounter'] = Encounter::select('flduserid', 'fldpatientval', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->with('patientInfo')
            ->get();

        //        $data['lowDeposit'] = Settings::select('fldvalue')->where('fldindex', 'comp01:LowDeposit/Procedure')->get();
        //        $data['FixPayableUser'] = Settings::select('fldvalue')->where('fldindex', 'comp01:FixPayableUser/Procedure')->get();

        //        select flditemname as col from tblservicecost where flditemtype='Equipment' and (fldgroup like '' or fldgroup='%') and fldstatus='Active'
        $data['serviceCost'] = ServiceCost::select('flditemname')
            ->where('flditemtype', 'Equipment')
            ->where('fldstatus', 'Active')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldgroup', '=', '')
                    ->orWhere('fldgroup', '=', '%');
            })
            ->get();

        //        select fldid,flditem,fldfirsttime from tblpattiming where fldencounterval='1' and fldtype='Equipment' and fldfirstsave='1' and fldsecondsave='0'
        $data['equipmentWaiting'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldfirstsave', 'fldsecondsave')
            ->where('fldencounterval', $encounter_id)
            ->where('fldtype', 'Equipment')
            ->where('fldfirstsave', 1)
            ->where('fldsecondsave', 0)
            ->get();

        //        select fldid,flditem,fldfirsttime,fldsecondtime,fldid from tblpattiming where fldencounterval='1' and fldtype='Equipment'
        $data['equipmentCleared'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime')
            ->where('fldencounterval', $encounter_id)
            ->where('fldtype', 'Equipment')
            ->where('fldsecondsave', 1)
            ->get();

        $data['payable_to'] = CogentUsers::where('fldpayable', 1)->where('status', 'active')->get();

        $html = view('menu::menu-dynamic-views.equipment-form', $data)->render();
        return $html;
    }

    public function addEquipment(Request $request)
    {
        $data['equipmentWaiting'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldfirstsave', 'fldsecondsave')
            ->where('fldencounterval', $request->encounter)
            ->where('fldtype', 'Equipment')
            ->where('fldfirstsave', 1)
            ->where('fldsecondsave', 0)
            ->get();

        $data['serviceCost'] = ServiceCost::select('flditemname')
            ->where('flditemname', $request->equipmentEquipment)
            ->get();

        $html = view('menu::menu-dynamic-views.equipment-form-add', $data)->render();
        return $html;
    }

    public function insertStartEquipment(Request $request)
    {
        $request->validate([
            'fldname' => 'required',
        ]);
        try {
            $insertData['fldencounterval'] = $request->encounter;
            $insertData['fldtype'] = 'Equipment';
            $insertData['flditem'] = $request->fldname;
            $insertData['fldfirstreport'] = NULL;
            $insertData['fldfirstuserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $insertData['fldfirsttime'] = date("Y-m-d H:i:s");
            $insertData['fldfirstcomp'] = Helpers::getCompName();
            $insertData['fldfirstsave'] = 1;
            $insertData['fldsecondreport'] = NULL;
            $insertData['fldseconduserid'] = NULL;
            $insertData['fldsecondtime'] = NULL;
            $insertData['fldsecondcomp'] = NULL;
            $insertData['fldsecondsave'] = 0;
            $insertData['fldcomment'] = NULL;
            $insertData['xyz'] = 0;
            $insertData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatTiming::insert($insertData);
            $data['equipmentWaiting'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldfirstsave', 'fldsecondsave')
                ->where('fldencounterval', $request->encounter)
                ->where('fldtype', 'Equipment')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            $data['serviceCost'] = [];

            $html = view('menu::menu-dynamic-views.equipment-form-add', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function stopEquipment(Request $request)
    {
        $request->validate([
            'fldid' => 'required',
        ]);
        try {
            $updateData['fldsecondreport'] = NULL;
            $updateData['fldseconduserid'] = $request->payable_to_add;
            $updateData['fldsecondtime'] = date("Y-m-d H:i:s");
            $updateData['fldsecondcomp'] = Helpers::getCompName();
            $updateData['fldsecondsave'] = 1;
            $updateData['xyz'] = 0;

            PatTiming::where([['fldid', $request->fldid]])->update($updateData);
            $data['equipmentWaiting'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldfirstsave', 'fldsecondsave')
                ->where('fldencounterval', $request->encounter)
                ->where('fldtype', 'Equipment')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            $data['serviceCost'] = [];

            $html = view('menu::menu-dynamic-views.equipment-form-add', $data)->render();
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function stopComplete(Request $request)
    {
        $data['equipmentCleared'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime')
            ->where('fldencounterval', $request->encounter)
            ->where('fldtype', 'Equipment')
            ->where('fldsecondsave', 1)
            ->get();
        $html = view('menu::menu-dynamic-views.equipment-form-stop', $data)->render();
        return $html;
    }


}
