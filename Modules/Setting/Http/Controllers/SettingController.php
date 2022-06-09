<?php

namespace Modules\Setting\Http\Controllers;

use App\BillingSet;
use App\CheckRedirectLastEncounter;
use App\Option;
use App\Patsubs;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Year;
use App\Patientcreditcolor;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Session;
use Log;

/**
 * Class SettingController
 * @package Modules\Setting\Http\Controllers
 */
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::index');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportSetting()
    {
        return view('setting::report');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deviceSetting()
    {
        return view('setting::device-new');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formSetting()
    {
        $data['tab_nav'] = 'form-setting';
        return view('setting::form-setting', $data);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function labSetting()
    {
        return view('setting::lab');
    }

    public function patientCreditColor()
    {
        $credit_color = Patientcreditcolor::first();
        return view('setting::patient-credit-color', compact('credit_color'));
    }

    public function PatientCreditColorUpdate(Request $request)
    {

        $greenday = $request->green_day + 1;
        $yellowday = $request->yellow_day + 1;

        $request->validate([
            'green_day'              => 'required|numeric',
            'yellow_day'        => 'required|numeric|min:' . $greenday,
            'red_day'              => 'required|numeric|min:' . $yellowday,
        ]);

        $patient = Patientcreditcolor::first();
        if (!is_null($patient)) {
            $patient->update([
                'green_day' => $request->green_day,
                'yellow_day' => $request->yellow_day,
                'red_day' => $request->red_day
            ]);
        } else {
            Patientcreditcolor::create([
                'green_day' => $request->green_day,
                'yellow_day' => $request->yellow_day,
                'red_day' => $request->red_day
            ]);
        }
        return redirect()->back();
        // Session::flash('success_message', 'Success');
    }

    public function registerSetting()
    {
        return view('setting::registersetting');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function patientReportSetting()
    {
        return view('setting::patient');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingSave(Request $request)
    {
        try {
            $settingsKey = $request->settingTitle;
            $settingsValue = $request->settingValue;
            $previousData = Options::get($settingsKey);
            Options::update($settingsKey, $settingsValue);
            Helpers::logStack(["Setting updated", "Event"], ['current_data' => Options::get($settingsKey), 'previous_data' => $previousData]);
            return response()->json(['message' => 'Setting Saved', 'status' => 'Done']);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in setting update', "Error"]);
            return response()->json(['message' => 'Something went wrong', 'status' => 'Error']);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function systemSetting()
    {
        if (can('hospital-info-setup-view')) {
            return view('setting::system');
        }
        return can('hospital-info-setup-view', false);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function systemSettingStore(Request $request)
    {
        $request->validate([
            'system_name' => 'required',
            'hospital_code' => 'required',
            'system_feedback_email' => 'required',
            'licensed_by' => 'required',
        ]);
        try {
            // site config data
            $siteconfig = [
                'system_name' => $request->get('system_name'),
                'system_email' => $request->get('system_email'),
                // 'system_slogan' => $request->get('system_slogan'),
                'system_address' => $request->get('system_address'),
                'hospital_code' => $request->get('hospital_code'),
            ];
            $previousData = [
                'system_patient_rank' => Options::get('system_patient_rank'),
                'siteconfig' => Options::get('siteconfig'),
                'system_feedback_email' => Options::get('system_feedback_email'),
                'system_telephone_no' => Options::get('system_telephone_no'),
                'system_mobile' => Options::get('system_mobile'),
                'hospital_code' => Options::get('hospital_code'),
                'licensed_by' => Options::get('licensed_by'),
                'system_2fa' => Options::get('system_2fa'),
                'hospital_pan' => Options::get('hospital_pan'),
                'hospital_vat' => Options::get('hospital_vat'),
                'pharmacy_pan' => Options::get('pharmacy_pan'),
                'pharmacy_vat' => Options::get('pharmacy_vat'),
                'sync_ird' => Options::get('sync_ird'),
                'brand_image' => Options::get('brand_image'),
                'dda_number' => Options::get('dda_number'),
                'hospital_login_color' => Options::get('hospital_login_color'),
                'hospital_default_color' => Options::get('hospital_default_color')
            ];

            Options::update('system_patient_rank', $request->get('system_patient_rank'));
            Options::update('siteconfig', $siteconfig);
            Options::update('system_feedback_email', $request->get('system_feedback_email'));
            Options::update('system_telephone_no', $request->get('system_telephone_no'));
            Options::update('system_mobile', $request->get('system_mobile'));
            Options::update('hospital_code', $request->get('hospital_code'));
            Options::update('licensed_by', $request->get('licensed_by'));
            Options::update('system_2fa', $request->get('system_2fa'));
            Options::update('hospital_pan', $request->get('hospital_pan'));
            Options::update('hospital_vat', $request->get('hospital_vat'));
            Options::update('pharmacy_pan', $request->get('pharmacy_pan'));
            Options::update('pharmacy_vat', $request->get('pharmacy_vat'));
            Options::update('sync_ird', $request->get('sync_ird'));
            Options::update('dda_number', $request->get('dda_number'));
            Options::update('hospital_login_color', $request->get('hospital_login_color'));
            Options::update('hospital_default_color', $request->get('hospital_default_color'));

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                $path = public_path() . "/uploads/config/";

                $image->move($path, $brand_image);
                Options::update('brand_image', $brand_image);
            }
            $currentData = [
                'system_patient_rank' => Options::get('system_patient_rank'),
                'siteconfig' => Options::get('siteconfig'),
                'system_feedback_email' => Options::get('system_feedback_email'),
                'system_telephone_no' => Options::get('system_telephone_no'),
                'system_mobile' => Options::get('system_mobile'),
                'hospital_code' => Options::get('hospital_code'),
                'licensed_by' => Options::get('licensed_by'),
                'system_2fa' => Options::get('system_2fa'),
                'hospital_pan' => Options::get('hospital_pan'),
                'hospital_vat' => Options::get('hospital_vat'),
                'sync_ird' => Options::get('sync_ird'),
                'brand_image' => Options::get('brand_image'),
                'dda_number' => Options::get('dda_number'),
                'hospital_login_color' => Options::get('hospital_login_color'),
                'hospital_default_color' => Options::get('hospital_default_color')
            ];
            Helpers::logStack(["System setting updated", "Event"], ['current_data' => $currentData, 'previous_data' => $previousData]);
            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.system');
        } catch (Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in system setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.system');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deviceSettingStore(Request $request)
    {
        $request->validate([
            'pac_server_name' => 'required',
            'pac_server_host' => 'required',
            'pac_server_aetitle' => 'required',
            'pac_server_query' => 'required',
            'pac_server_port' => 'required',
        ]);

        try {
            $previousData = [
                'pac_server_name' => Options::get('pac_server_name'),
                'pac_server_host' => Options::get('pac_server_host'),
                'pac_server_aetitle' => Options::get('pac_server_aetitle'),
                'pac_server_cget' => Options::get('pac_server_cget'),
                'pac_server_modality' => Options::get('pac_server_modality'),
                'pac_server_query' => Options::get('pac_server_query'),
                'pac_server_port' => Options::get('pac_server_port'),
                'dicom_command' => Options::get('dicom_command'),
                'dicom_apppath' => Options::get('dicom_apppath')
            ];

            Options::update('pac_server_name', $request->get('pac_server_name'));
            Options::update('pac_server_host', $request->get('pac_server_host'));
            Options::update('pac_server_aetitle', $request->get('pac_server_aetitle'));
            Options::update('pac_server_cget', $request->get('pac_server_cget'));
            Options::update('pac_server_modality', $request->get('pac_server_modality'));
            Options::update('pac_server_query', $request->get('pac_server_query'));
            Options::update('pac_server_port', $request->get('pac_server_port'));
            Options::update('dicom_command', $request->get('dicom_command'));
            Options::update('dicom_apppath', $request->get('dicom_apppath'));

            $currentData = [
                'pac_server_name' => Options::get('pac_server_name'),
                'pac_server_host' => Options::get('pac_server_host'),
                'pac_server_aetitle' => Options::get('pac_server_aetitle'),
                'pac_server_cget' => Options::get('pac_server_cget'),
                'pac_server_modality' => Options::get('pac_server_modality'),
                'pac_server_query' => Options::get('pac_server_query'),
                'pac_server_port' => Options::get('pac_server_port'),
                'dicom_command' => Options::get('dicom_command'),
                'dicom_apppath' => Options::get('dicom_apppath')
            ];
            Helpers::logStack(["Device setting updated", "Event"], ['current_data' => $currentData, 'previous_data' => $previousData]);
            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.device');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in device setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.device');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pacsDetail(Request $request)
    {
        $pacsname = $request->pacsName;
        if (Options::get('pac_server_name') == $pacsname) {
            $data['pac_server_host'] = Options::get('pac_server_host');
            $data['pac_server_aetitle'] = Options::get('pac_server_aetitle');
            $data['pac_server_cget'] = Options::get('pac_server_cget');
            $data['pac_server_modality'] = Options::get('pac_server_modality');
            $data['pac_server_query'] = Options::get('pac_server_query');
            $data['pac_server_port'] = Options::get('pac_server_port');
            $data['dicom_command'] = Options::get('dicom_command');
            $data['dicom_apppath'] = Options::get('dicom_apppath');

            return $data;
        } else {
            echo "No Data Available";
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function labPdfTemplateSave(Request $request)
    {
        $request->validate([
            '_template_active' => 'required',
        ]);

        try {
            $previousData = [
                '_template_active' => Options::get('_template_active'),
                '_template_A' => Options::get('_template_A'),
                '_template_B' => Options::get('_template_B'),
                '_template_C' => Options::get('_template_C'),
                '_template_D' => Options::get('_template_D')
            ];

            Options::update('_template_active', $request->get('_template_active'));
            Options::update('_template_A', $request->get('_template_A'));
            Options::update('_template_B', $request->get('_template_B'));
            Options::update('_template_C', $request->get('_template_C'));
            Options::update('_template_D', $request->get('_template_D'));
            $currentData = [
                '_template_active' => Options::get('_template_active'),
                '_template_A' => Options::get('_template_A'),
                '_template_B' => Options::get('_template_B'),
                '_template_C' => Options::get('_template_C'),
                '_template_D' => Options::get('_template_D')
            ];
            Helpers::logStack(["Lab PDF template setting updated", "Event"], ['current_data' => $currentData, 'previous_data' => $previousData]);

            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('lab-setting');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in lab PDF template setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('lab-setting');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function formSettingStore(Request $request)
    {
        $request->validate([
            'free_text' => 'required',
        ]);
        try {
            $previousData = Options::get('free_text');
            Options::update('free_text', $request->get('free_text'));
            Helpers::logStack(["Form setting updated", "Event"], ['current_data' => Options::get('free_text'), 'previous_data' => $previousData]);
            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.form');
        } catch (Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in form setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.form');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveOpdReport(Request $request)
    {
        $requestData = $request->except('_token');
        Options::update('opd_pdf_options', serialize($requestData));

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('report-setting');
    }

    function billingmode()
    {
        $data['billingmode'] = BillingSet::where('status', 1)->orWhere('status', 0)->get();
        return view('setting::billingmode', $data);
    }

    function addbillingmode(Request $request)
    {
        $data['fldsetname'] = $request->mode;
        $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
        BillingSet::insertGetId($data);
        Cache::forget('billing-set');
        $billingmode = BillingSet::where('fldsetname', $request->mode)->first();
        // $html = '<tr><td>' . $billingmode->fldsetname . '</td><td><a href="javascript:;" class="delete-billing-mode text-danger" url="' . route('deletebillingmode', $billingmode->fldsetname) . '" billingid="' . $billingmode->fldsetname . '"><i class="fa fa-trash"></i></a></td></tr>';
        $html = '<tr><td>' . $billingmode->fldsetname . '</td><td>' . ($billingmode->status === 1 ? 'Active' : 'Inactive') . '</td><td><a href="javascript:;" class="change-status-billing-mode btn ' . ($billingmode->status == 1 ? "btn-warning" : "btn-success") . '" onclick="changeBillingModeStatus(' . "'$billingmode->fldsetname'" . ')">Inactive</a></td></tr>';

        return response()->json([
            'success' => [
                'html' => $html,
            ]
        ]);
    }

    public function statusChangeBillingmode(Request $request)
    {
        try {
            $billingData = BillingSet::where('fldsetname', 'like', $request->id)->first();
            if ($billingData->status === 1) {
                $updateData['status'] = 0;
            } else {
                $updateData['status'] = 1;
            }
            BillingSet::where('fldsetname', 'like', $request->id)->update($updateData);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*function deletebillingmode($id)
    {
        $billingmode = BillingSet::where('fldsetname', $id)->delete();


        return response()->json([
            'success' => 'true'
        ]);
    }*/

    function deleteyear(Request $request)
    {
        $id = $request->fldid;
        $year = Year::where('fldname', $id)->delete();

        $data = Year::get();
        $html = '';
        if ($data) {
            foreach ($data as $dept) {
                $html .= '<tr>
                <td class="bedn" dept="' . $dept->id . '">' . $dept->fldname . '</td>
                <td>' . $dept->fldfirst . '</td>
                <td>' . $dept->fldlast . '</td>
                <td><a href="javascript:;" class="delete-year" url="' . route('deleteyear') . '"fldid="' . $dept->fldname . '" billingid="' . $dept->id . '"><i class="fa fa-trash"></i></a></td>

            </tr>';
            }
        }


        return response()->json([
            'success' => [
                'html' => $html,
            ]
        ]);
    }


    function prefixsetting()
    {
        $data['prefix'] = Patsubs::where('fldid', 1)->first();
        return view('setting::prefixsetting', $data);
    }


    function updateprefix(Request $request)
    {
        try {
            $data = array(
                'fldpatno' => $request->fldpatno,
                'fldpatlen' => $request->fldpatlen,
                'fldencid' => $request->fldencid,
                'fldenclen' => $request->fldenclen,
                'fldbooking' => $request->fldbooking,
                'fldbooklen' => $request->fldbooklen,
                'fldhospcode' => $request->fldhospcode,

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $prefix = Patsubs::where('fldid', 1)->first();
            if ($prefix) {
                Patsubs::where('fldid', 1)->update($data);
                Helpers::logStack(["Prefix updated", "Event"], ['current_data' => $data, 'previous_data' => $prefix]);
            } else {
                Patsubs::insertGetId($data);
                Helpers::logStack(["Prefix created", "Event"], ['current_data' => $data]);
            }

            return response()->json([
                'success' => 'true'
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in prefix create/update', "Error"]);
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registrationSetting()
    {
        return view('setting::registration');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registrationSettingStore(Request $request)
    {
        $request->validate([
            'patient_credential_setting' => 'required',
        ]);
        try {
            $previousData = Options::get('patient_credential_setting');
            Options::update('patient_credential_setting', $request->get('patient_credential_setting'));
            Helpers::logStack(["Registration setting updated", "Event"], ['current_data' => Options::get('patient_credential_setting'), 'previous_data' => $previousData]);

            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.registration');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in registration setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.registration');
        }
    }

    public function smsSettingStore(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'token' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $previousData = [
                Options::get('url'),
                Options::get('token'),
                Options::get('username'),
                Options::get('password'),
                Options::get('text_messgae'),
                Options::get('bulk_sms'),
                Options::get('lab_report_text_message'),
                Options::get('radio_report_text_message'),
                Options::get('opd_report_text_message'),
                Options::get('discharge_text_message'),
                Options::get('low_deposit_text_message')
            ];

            Options::update('url', $request->get('url'));
            Options::update('token', $request->get('token'));
            Options::update('username', $request->get('username'));
            Options::update('password', $request->get('password'));
            Options::update('text_messgae', $request->get('text_messgae'));
            Options::update('bulk_sms', $request->get('bulk_sms'));
            Options::update('lab_report_text_message', $request->get('lab_report_text_message'));
            Options::update('radio_report_text_message', $request->get('radio_report_text_message'));
            Options::update('opd_report_text_message', $request->get('opd_report_text_message'));
            Options::update('discharge_text_message', $request->get('discharge_text_message'));
            Options::update('low_deposit_text_message', $request->get('low_deposit_text_message'));

            $responseData = [
                Options::get('url'),
                Options::get('token'),
                Options::get('username'),
                Options::get('password'),
                Options::get('text_messgae'),
                Options::get('bulk_sms'),
                Options::get('lab_report_text_message'),
                Options::get('radio_report_text_message'),
                Options::get('opd_report_text_message'),
                Options::get('discharge_text_message'),
                Options::get('low_deposit_text_message')
            ];
            Helpers::logStack(["SMS setting updated", "Event"], ['current_data' => $responseData, 'previous_data' => $previousData]);

            Session::flash('success_message', 'SMS setting updated successfully.');
            return redirect()->route('setting.device');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in SMS setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.device');
        }
    }

    public function medicineSetting(Request $request)
    {
        return view('setting::medicine');
    }

    public function medicineSettingStore(Request $request)
    {
        $request->validate([
            'expire_color_code' => 'required',
            'near_expire_color_code' => 'required',
            'near_expire_duration' => 'required',
            'non_expire_color_code' => 'required',
        ]);
        try {
            $previousData = [
                Options::get('expire_color_code'),
                Options::get('near_expire_color_code'),
                Options::get('near_expire_duration'),
                Options::get('non_expire_color_code'),
            ];

            Options::update('expire_color_code', $request->get('expire_color_code'));
            Options::update('near_expire_color_code', $request->get('near_expire_color_code'));
            Options::update('near_expire_duration', $request->get('near_expire_duration'));
            Options::update('non_expire_color_code', $request->get('non_expire_color_code'));

            $responseData = [
                Options::get('expire_color_code'),
                Options::get('near_expire_color_code'),
                Options::get('near_expire_duration'),
                Options::get('non_expire_color_code'),
            ];
            Helpers::logStack(["Medicine setting updated", "Event"], ['current_data' => $responseData, 'previous_data' => $previousData]);

            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('setting.purchaseOrder');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in medicine setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.purchaseOrder');
        }
    }

    public function purchaseentrySettingStore(Request $request)
    {
        $request->validate([
            'report_format' => 'required',
        ]);
        try {
            $previousData = [
                Options::get('report_format'),
            ];

            Options::update('report_format', $request->get('report_format'));

            $responseData = [
                Options::get('report_format'),
            ];
            Helpers::logStack(["Purchase Entry setting updated", "Event"], ['current_data' => $responseData, 'previous_data' => $previousData]);

            Session::flash('success_message', 'Purchase Entry updated successfully.');
            return redirect()->route('setting.purchaseOrder');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in purchase entry setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('setting.purchaseOrder');
        }
    }

    public function redirectLastEncounterStore(Request $request)
    {
        $loginUserId = Auth::guard("admin_frontend")->id();
        $data = array(
            'user_id' => $loginUserId,
            'fld_redirect_encounter' => $request->redirect_to_last_encounter,
        );
        $redirectData = CheckRedirectLastEncounter::where('user_id', $loginUserId)->first();
        if ($redirectData) {
            $redirectData->update($data);
        } else {
            CheckRedirectLastEncounter::insertGetId($data);
        }
        return response()->json([
            'success' => [
                'message' => __('messages.update', ['name' => 'Surgical Brand']),
            ]
        ]);
    }

    public function dispensingSetting(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'dispensing_freq_dose' => 'required',
                'dispensing_medicine_stock' => 'required',
                'dispensing_expiry_limit' => 'nullable|numeric',
            ]);
            try {
                $previousData = [
                    Options::get('dispensing_freq_dose'),
                    Options::get('dispensing_medicine_stock'),
                    Options::get('direct_purchase_entry'),
                    Options::get('dispensing_expiry_limit'),
                    Options::get('medicine_by_category'),
                ];

                Options::update('dispensing_freq_dose', $request->get('dispensing_freq_dose'));
                Options::update('dispensing_medicine_stock', $request->get('dispensing_medicine_stock'));
                Options::update('direct_purchase_entry', $request->get('direct_purchase_entry'));
                Options::update('dispensing_expiry_limit', $request->get('dispensing_expiry_limit'));
                Options::update('medicine_by_category', $request->get('medicine_by_category'));

                $responseData = [
                    Options::get('dispensing_freq_dose'),
                    Options::get('dispensing_medicine_stock'),
                    Options::get('direct_purchase_entry'),
                    Options::get('dispensing_expiry_limit'),
                    Options::get('medicine_by_category'),
                ];
                Helpers::logStack(["Dispensing setting updated", "Event"], ['current_data' => $responseData, 'previous_data' => $previousData]);

                Session::flash('success_message', 'Dispensing setting updated successfully.');
                return redirect()->route('setting.dispensing');
            } catch (\Exception $e) {
                Helpers::logStack([$e->getMessage() . ' in dispensing setting update', "Error"]);
                Session::flash('error_message', $e->getMessage());
                return redirect()->route('setting.dispensing');
            }
        }

        return view('setting::dispensing');
    }

    public function purchaseOrderSetting(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'stock_lead_time' => 'required',
                'safety_stock' => 'required',
                'stock_available_color_code' => 'required',
                'stock_near_empty_color_code' => 'required'
            ]);
            try {
                $previousData = [
                    Options::get('stock_lead_time'),
                    Options::get('safety_stock'),
                    Options::get('stock_available_color_code'),
                    Options::get('stock_near_empty_color_code'),
                ];

                Options::update('stock_lead_time', $request->get('stock_lead_time'));
                Options::update('safety_stock', $request->get('safety_stock'));
                Options::update('stock_available_color_code', $request->get('stock_available_color_code'));
                Options::update('stock_near_empty_color_code', $request->get('stock_near_empty_color_code'));

                $responseData = [
                    Options::get('stock_lead_time'),
                    Options::get('safety_stock'),
                    Options::get('stock_available_color_code'),
                    Options::get('stock_near_empty_color_code'),
                ];
                Helpers::logStack(["Purchase order setting updated", "Event"], ['current_data' => $responseData, 'previous_data' => $previousData]);

                Session::flash('success_message', 'Purchase order setting updated successfully.');
                return redirect()->route('setting.purchaseOrder');
            } catch (\Exception $e) {
                Helpers::logStack([$e->getMessage() . ' in purchase order setting update', "Error"]);
                Session::flash('error_message', $e->getMessage());
                return redirect()->route('setting.purchaseOrder');
            }
        }

        return view('setting::purchase-order');
    }

    public function labPrintingTypeSetting(Request $request)
    {
        // dd($request->all());
        //left_signature left_center_signature center_signature right_center_signature right_signature
        try {
            $lab_type = $request->get('lab_type');
            //header and footer option
            $header = $request->get('header');
            $footer = $request->get('footer');

            //select type options
            $left_signature = $request->get('left_signature');
            $left_center_signature = $request->get('left_center_signature');
            $center_signature = $request->get('center_signature');
            $right_center_signature = $request->get('right_center_signature');
            $right_signature = $request->get('right_signature');

            //textarea comment box
            $left_signature_textarea = $request->get('left_signature_textarea');
            $left_center_signature_textarea = $request->get('left_center_signature_textarea');
            $center_signature_textarea = $request->get('center_signature_textarea');
            $right_center_signature_textarea = $request->get('right_center_signature_textarea');
            $right_signature_textarea = $request->get('right_signature_textarea');

            if ($lab_type == 'lab_patient_type') {
                //header and footer option
                Options::update('lab_patient_header', $header);
                Options::update('lab_patient_footer', $footer);

                //select type options
                Options::update('left_signature', $left_signature);
                Options::update('left_center_signature', $left_center_signature);
                Options::update('center_signature', $center_signature);
                Options::update('right_center_signature', $right_center_signature);
                Options::update('right_signature', $right_signature);

                //select type options
                Options::update('left_signature_textarea', $left_signature_textarea);
                Options::update('left_center_signature_textarea', $left_center_signature_textarea);
                Options::update('center_signature_textarea', $center_signature_textarea);
                Options::update('right_center_signature_textarea', $right_center_signature_textarea);
                Options::update('right_signature_textarea', $right_signature_textarea);

                //image upload
                if ($request->hasFile('left_signature_image')) {
                    $image = $request->file('left_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('left_signature_image', $brand_image);
                }

                if ($request->hasFile('left_center_signature_image')) {
                    $image = $request->file('left_center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('left_center_signature_image', $brand_image);
                }

                if ($request->hasFile('center_signature_image')) {
                    $image = $request->file('center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('center_signature_image', $brand_image);
                }

                if ($request->hasFile('right_center_signature_image')) {
                    $image = $request->file('right_center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('right_center_signature_image', $brand_image);
                }

                if ($request->hasFile('right_signature_image')) {
                    $image = $request->file('right_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('right_signature_image', $brand_image);
                }
            } elseif ($lab_type == 'lab_patient_pcr_type') {
                Options::update('lab_patient_pcr_header', $header);
                Options::update('lab_patient_pcr_footer', $footer);
                //select type options
                Options::update('pcr_left_signature', $left_signature);
                Options::update('pcr_left_center_signature', $left_center_signature);
                Options::update('pcr_center_signature', $center_signature);
                Options::update('pcr_right_center_signature', $right_center_signature);
                Options::update('pcr_right_signature', $right_signature);

                //select type options
                Options::update('pcr_left_signature_textarea', $left_signature_textarea);
                Options::update('pcr_left_center_signature_textarea', $left_center_signature_textarea);
                Options::update('pcr_center_signature_textarea', $center_signature_textarea);
                Options::update('pcr_right_center_signature_textarea', $right_center_signature_textarea);
                Options::update('pcr_right_signature_textarea', $right_signature_textarea);
                //image upload
                if ($request->hasFile('left_signature_image')) {
                    $image = $request->file('left_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
                    $path = public_path() . "/uploads/config/";
                    $image->move($path, $brand_image);

                    Options::update('pcr_left_signature_image', $brand_image);
                }

                if ($request->hasFile('left_center_signature_image')) {
                    $image = $request->file('left_center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('pcr_left_center_signature_image', $brand_image);
                }

                if ($request->hasFile('center_signature_image')) {
                    $image = $request->file('center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('pcr_center_signature_image', $brand_image);
                }

                if ($request->hasFile('right_center_signature_image')) {
                    $image = $request->file('right_center_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('pcr_right_center_signature_image', $brand_image);
                }

                if ($request->hasFile('right_signature_image')) {
                    $image = $request->file('right_signature_image');
                    $brand_image = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

                    $path = public_path() . "/uploads/config/";

                    $image->move($path, $brand_image);
                    Options::update('pcr_right_signature_image', $brand_image);
                }
            }

            Helpers::logStack(["Lab Printing setting updated", "Lab Printing Setting"]);
            Session::flash('success_message', 'Lap Printing Setting updated successfully.');
            return redirect()->route('lab-setting');
        } catch (Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in lab printing setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('lab-setting');
        }
    }
}
