<?php

namespace Modules\Setting\Http\Controllers;

use App\CogentUsers;
use App\FormNames;
use App\SignatureForm;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class SignatureController extends Controller
{
    public function addEditFormSignature($formName = null)
    {
        $data['formName'] = $formName;
        if ($formName == null) {
            $data['signatures'] = [];
        } else {
            $data['signature_left'] = SignatureForm::select('user_id')
                ->where('form_name', $formName)
                ->where('position', 'left')
                ->get();
            $data['signature_right'] = SignatureForm::select('user_id')
                ->where('form_name', $formName)
                ->where('position', 'right')
                ->get();
            $data['signature_middle'] = SignatureForm::select('user_id')
                ->where('form_name', $formName)
                ->where('position', 'middle')
                ->get();
            $data['users'] = CogentUsers::where('status', 'active')->where('fldsigna', 1)->get();
        }

        $data['tab_nav'] = 'form-signature';
        $data['form_name_list'] = FormNames::all();
        return view('setting::form-signature', $data);
    }

    public function insertSignature(Request $request)
    {
        try {
            $formId = FormNames::where('form_name', $request->form_name)->first();

            $dataInsertLeft['form_name'] = $dataInsertMiddle['form_name'] = $dataInsertRight['form_name'] = $request->form_name;
            $dataInsertLeft['type'] = $dataInsertMiddle['type'] = $dataInsertRight['type'] = 'primary';
            $dataInsertLeft['form_name_id'] = $dataInsertMiddle['form_name_id'] = $dataInsertRight['form_name_id'] = $formId->id;

            SignatureForm::where('form_name', $request->form_name)->delete();

            if ($request->left_signature) {
                for ($i = 0; $i < count($request->left_signature); $i++) {
                    if ($request->left_signature[$i] != "") {
                        $dataInsertLeft['position'] = 'left';
                        $dataInsertLeft['user_id'] = $request->left_signature[$i];
                        $dataInsertLeft['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        SignatureForm::insert($dataInsertLeft);
                    }
                }
            }

            if ($request->middle_signature) {
                for ($i = 0; $i < count($request->middle_signature); $i++) {
                    if ($request->middle_signature[$i] != "") {
                        $dataInsertMiddle['position'] = 'middle';
                        $dataInsertMiddle['user_id'] = $request->middle_signature[$i];
                        $dataInsertMiddle['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        SignatureForm::insert($dataInsertMiddle);
                    }
                }
            }

            if ($request->right_signature) {
                for ($i = 0; $i < count($request->right_signature); $i++) {
                    if ($request->right_signature[$i] != "") {
                        $dataInsertRight['position'] = 'right';
                        $dataInsertRight['user_id'] = $request->right_signature[$i];
                        $dataInsertRight['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                        SignatureForm::insert($dataInsertRight);
                    }
                }
            }


            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.signature.form', $request->form_name);
        } catch (\GearmanException $e) {
            Session::flash('error_message', __('messages.error'));
            return redirect()->route('setting.signature.form', $request->form_name);
        }
    }

    public function appendSelectSignature(Request $request)
    {
        $data['users'] = CogentUsers::where('status', 'active')->where('fldsigna', 1)->get();

        if ($request->signaturePosition == 'append-left-signature') {
            $data['position'] = "left_signature[]";
            $data['positionTitle'] = "Left";
        } elseif ($request->signaturePosition == 'append-middle-signature') {
            $data['position'] = "middle_signature[]";
            $data['positionTitle'] = "Middle";
        } elseif ($request->signaturePosition == 'append-right-signature') {
            $data['position'] = "right_signature[]";
            $data['positionTitle'] = "Right";
        }
        $html = view('setting::signature.signature-select', $data)->render();
        return $html;
    }
}
