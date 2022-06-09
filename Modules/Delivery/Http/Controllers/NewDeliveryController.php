<?php

namespace Modules\Delivery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class NewDeliveryController extends Controller
{
    public function getDelivery(Request $request)
    {
        $data = \App\Confinement::where('fldid', $request->get('fldid'))->first();
        $date_time = explode(' ', $data->flddeltime);
        $data->flddate = Helpers::dateEngToNepdash($date_time[0])->full_date;
        $data->fldtime = $date_time[1];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $form_data = $this->_get_form_data($request);
        $validation = $this->_validate_data($form_data);
        if ($validation)
            return $validation;

        try {
            $insert_data = $form_data + [
                'fldencounterval' => \Session::get('delivery_encounter_id'),
                'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => date('Y-m-d H:i:s'),
                'fldcomp' => Helpers::getCompName(),
                'fldbabypatno' => NULL,
                'fldsave' => '1',
                'fldreference' => NULL,
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $fldid = \App\Confinement::insertGetId($insert_data);
            $form_data += [
                'fldid' => $fldid,
            ];

            return [
                'status' => TRUE,
                'data' => $form_data,
                'message' => __('messages.success', ['name' => 'Data'])
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    public function update(Request $request)
    {
        $form_data = $this->_get_form_data($request);
        $validation = $this->_validate_data($form_data);
        if ($validation)
            return $validation;

        try {
            $update_data = $form_data + [
                'flduptime' => date('Y-m-d H:i:s'),
                'xyz' => '0',
            ];

            \App\Confinement::where([
                'fldid' => $request->get('fldid')
            ])->update($update_data);

            return [
                'status' => TRUE,
                'data' => $form_data,
                'message' => __('messages.update', ['name' => 'Data'])
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to update data.'
            ];
        }
    }

    private function _get_form_data($request)
    {
        $date = $request->get('flddeldate');
        $date = Helpers::dateNepToEng($date)->full_date . ' ' . $request->get('flddeltime') . ':00';

        return [
            'flddeltime' => $date,
            'flddeltype' => $request->get('flddeltype'),
            'flddelresult' => $request->get('flddelresult'),
            'flddelphysician' => $request->get('flddelphysician'),
            'flddelnurse' => json_encode($request->get('flddelnurse')),
            'fldcomplication' => $request->get('fldcomplication'),
            'fldbloodloss' => $request->get('fldbloodloss'),
            'flddelwt' => $request->get('flddelwt'),
            'fldcomment' => $request->get('fldcomment'),
            'fldplacenta' => $request->get('fldplacenta'),
        ];
    }

    private function _validate_data($form_data)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($form_data, [
            'flddeltime' => 'required',
            'flddeltype' => 'required',
            'flddelresult' => 'required',
            'flddelphysician' => 'required',
            'flddelnurse' => 'required',
            'fldcomplication' => 'required',
            'fldbloodloss' => 'required|numeric',
            'flddelwt' => 'required|numeric',
        ], [
            'flddeltime.required' => 'The DateTime is required.',
            'flddeltype.required' => 'The Delivery type is required.',
            'flddelresult.required' => 'The Delivery result is required.',
            'flddelphysician.required' => 'The Physician is required.',
            'flddelnurse.required' => 'The Nurse is required.',
            'fldcomplication.required' => 'The Complication is required.',
            'fldbloodloss.required' => 'The Blood loss is required.',
            'flddelwt.required' => 'The Weight is required.',
        ]);

        if($validator->fails()) {
            $errors = 'Error while saving information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

        return FALSE;
    }

    public function getSelectOptions()
    {
        return response()->json([
            'delivered_types' => Helpers::getDeliveredTypeList(),
            'complications' => Helpers::getComplicationList(),
        ]);
    }

    private function _get_modal_name($type)
    {
        $modelName = ($type === 'delivery_type') ? "\App\Delivery" : "\App\Delcomplication";
        return $modelName;
    }

    public function addVariable(Request $request)
    {
        $type = $request->get('type');
        $flditem = $request->get('flditem');

        if ($flditem == '' || !in_array($type, ['delivery_type', 'complication'])) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            $data = [
                'flditem' => $flditem,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $modelName = $this->_get_modal_name($type);
            $modelName::insert($data);

            return [
                'status' => TRUE,
                'data' => $data,
                'message' => __('messages.success', ['name' => 'Data'])
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    public function deleteVariable(Request $request)
    {
        $type = $request->get('type');
        $flditem = $request->get('flditem');

        if ($flditem == '' || !in_array($type, ['delivery_type', 'complication'])) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            $data = [
                'flditem' => $flditem,
            ];
            $modelName = $this->_get_modal_name($type);
            $modelName::where($data)->delete();

            return [
                'status' => TRUE,
                'message' => __('messages.delete', ['name' => 'Data'])
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to delete data.'
            ];
        }
    }

    public function saveUpdateChildGender(Request $request)
    {
        $fldid = $request->get('fldid');
        $gender = $request->get('gender');
        $fldpatientval = $request->get('fldpatientval');

        if ($fldpatientval)
            return $this->_update_child_gender(compact('gender', 'fldpatientval'));
        else
            return $this->_save_child_gender(compact('gender', 'fldid'));
    }

    private function _save_child_gender($data)
    {
        $fldid = $data['fldid'];
        $gender = $data['gender'];

        if ($fldid == '' || $gender == '') {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            $encounter_data = \App\Encounter::where('fldencounterval', \Session::get('delivery_encounter_id'))->first();
            $patient_data =  $encounter_data->patientInfo;
            $delivery_data = \App\Confinement::select('flddeltime')->where('fldid', $fldid)->first();
            $date_time = date('Y-m-d H:i:s');
            $computer = Helpers::getCompName();

            $patient_id = \App\AutoId::select('fldvalue')->where('fldtype', 'PatientNo')->first()->fldvalue;
            $encounter_id = \App\AutoId::select('fldvalue')->where('fldtype', 'EncounterID')->first()->fldvalue;
            \App\AutoId::where('fldtype', 'PatientNo')->update([
                'fldvalue' => ($patient_id+1),
            ]);
            \App\AutoId::where('fldtype', 'EncounterID')->update([
                'fldvalue' => ($encounter_id+1),
            ]);

            \App\PatientInfo::insert([
                'fldpatientval' => $patient_id,
                'fldptnamefir' => "I/O {$patient_data->fldptnamefir}",
                'fldptnamelast' => "{$patient_data->fldptnamelast}",
                'fldptsex' => "$gender",
                'fldptaddvill' => "{$patient_data->fldptaddvill}",
                'fldptadddist' => "{$patient_data->fldptadddist}",
                'fldptcontact' => NULL,
                'fldptguardian' => "{$patient_data->fldptnamefir} {$patient_data->fldptnamelast}",
                'fldrelation' => "Mother",
                'fldptbirday' => $delivery_data->flddeltime,
                'fldptadmindate' => $date_time,
                'fldemail' => NULL,
                'fldptcode' => NULL,
                'flddiscount' => NULL,
                'fldadmitfile' => NULL,
                'fldcomment' => NULL,
                'fldencrypt' => "0",
                'fldpassword' => NULL,
                'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => $date_time,
                'fldupuser' => NULL,
                'flduptime' => NULL,
                'xyz' => "0",
            ]);
            \App\Encounter::insert([
                'fldencounterval' => "E$encounter_id",
                'fldpatientval' => $patient_id,
                'fldadmitlocat' => $encounter_data->fldadmitlocat,
                'fldcurrlocat' => NULL,
                'flddoa' => NULL,
                'flddod' => NULL,
                'fldheight' => NULL,
                'fldcashdeposit' => '0',
                'flddisctype' => NULL,
                'fldcashcredit' => '0',
                'flduserid' => NULL,
                'fldadmission' => 'Recorded',
                'fldfollowdate' => NULL,
                'fldreferto' => NULL,
                'fldregdate' => $date_time,
                'fldcharity' => '0',
                'fldbillingmode' => NULL,
                'fldcomp' => $computer,
                'fldvisit' => 'NEW',
                'xyz' => '0',
            ]);
            \App\Confinement::where('fldid', $fldid)->update([
                'fldbabypatno' => $patient_id,
            ]);

            return response()->json([
                'status' => TRUE,
                'action' => 'save',
                'data' => [
                    'fldpatientval' => $patient_id,
                    'gender' => $gender,
                ],
                'message' => __('messages.success', ['name' => 'Data'])
            ]);
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    private function _update_child_gender($data)
    {
        $fldpatientval = $data['fldpatientval'];
        $gender = $data['gender'];

        if ($fldpatientval == '' || $gender == '') {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            \App\PatientInfo::where('fldpatientval', $fldpatientval)->update([
                'fldptsex' => $gender,
                'xyz' => '0',
            ]);

            return [
                'status' => TRUE,
                'action' => 'update',
                'data' => [
                    'gender' => $gender,
                ],
                'message' => __('messages.success', ['name' => 'Discount map'])
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to update data.'
            ];
        }
    }
}
