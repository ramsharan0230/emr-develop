<?php

namespace Modules\ConvergentPayment\Http\Controllers;

use App\AutoId;
use App\Encounter;
use App\Fiscalyear;
use App\PatBillDetail;
use App\PatBilling;
use App\Payment;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Year;
use Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Session;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ConvergentPaymentController extends Controller
{
    private $_api_context_convergent = [];
    private $_cc_logger;

    /**
     * ConvergentPaymentController constructor.
     */
    public function __construct()
    {
        // $log_path = storage_path() . '/logs/convergent.log';
        $log_path = storage_path() . '/logs/convergent/' . date('Y-m-d') . '.log';

        $this->_cc_logger = new Logger('CONVERGENT LOG :' . date('Y-d-m H:i:s'));
        $this->_cc_logger->pushHandler(new StreamHandler($log_path, Logger::INFO));

        $this->_api_context_convergent['mode'] = \Options::get('convergent_mode');

        if ($this->_api_context_convergent['mode'] === 'test') {

            $this->_api_context_convergent['payment_url'] = \Options::get('convergent_test_server_url');
            $this->_api_context_convergent['pid'] = \Options::get('convergent_test_pid');
            $this->_api_context_convergent['secret_key'] = \Options::get('convergent_test_secret_key');

        } else {

            $this->_api_context_convergent['payment_url'] = \Options::get('convergent_live_server_url');
            $this->_api_context_convergent['pid'] = \Options::get('convergent_live_pid');
            $this->_api_context_convergent['secret_key'] = \Options::get('convergent_live_secret_key');

        }
    }

    public function fonePayInit($encounterid)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($encounterid == null || $encounterid == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $encounterid)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();
            $patbilling = PatBilling::where([['fldencounterval', $encounterid]])->where('fldstatus', 'Punched')->get();

            $billNumber = AutoId::where('fldtype', 'InvoiceNo')->first();

            // $new_bill_number = $billNumber->fldvalue + 1;
            // AutoId::where('fldtype', 'InvoiceNo')->update(['fldvalue' => $new_bill_number]);

            // $dateToday = Carbon::now();
            // $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
            //     ->first();

            // $billNumberGeneratedString = "CAS-$year->fldname-$new_bill_number" . Options::get('hospital_code');

            $total = PatBilling::where('fldencounterval', $encounterid)->where('fldstatus', 'Punched')->sum('fldditemamt');
            $discount = PatBilling::where('fldencounterval', $encounterid)->where('fldstatus', 'Punched')->sum('flddiscamt');
            if ($patbilling) {
                /*insert pat bill details*/
                // $insertDataPatDetail = [
                //     'fldencounterval' => $encounterid,
                //     // 'fldbillno' => $billNumberGeneratedString,
                //     'flditemamt' => $total,
                //     'fldtaxamt' => 0,
                //     'flddiscountamt' => $discount,
                //     'fldreceivedamt' => $total,
                //     'fldbilltype' => 'fonepay',
                //     'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                //     'fldtime' => date("Y-m-d H:i:s"),
                //     'fldbill' => 'Fonepay',
                //     'fldsave' => 0,
                //     'xyz' => 0,
                //     'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                // ];

                // $paymentData = PatBillDetail::create($insertDataPatDetail);
                // session(['generatedBillNumber' => Crypt::encryptString($paymentData->fldid)]);
                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = $total_amount;
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $encounterid;
                $data['form'] = 'Cashier Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);
            }
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }

    public function dispensingfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($request->encounter == null || $request->encounter == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = $total_amount;
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $request->encounter;
                $data['form'] = 'Dispensing Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }

    public function registrationfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        // if ($request->encounter == null || $request->encounter == '')
        //     return redirect()->route('convergent.payments-failure');

        // $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        // if (!$patient || !$patient->patientInfo) {
        //     Session::flash('error_message', 'Patient not found.');
        //     return redirect()->back();
        // }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = Helpers::numberFormat($total_amount,'insert');
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $convgnt_R1 = $request->firstname. "-" . $request->middlename . "-" . $request->lastname;
                $PRN = time() . str_replace(' ', '-', $request->lastname);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = '';
                $data['form'] = 'Registration Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }

    public function depositfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($request->encounter == null || $request->encounter == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = Helpers::numberFormat($total_amount,'insert');
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $request->encounter;
                $data['form'] = 'Deposit Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }


    public function creditClearancePaymentsfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($request->encounter == null || $request->encounter == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = Helpers::numberFormat($total_amount,'insert');
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $request->encounter;
                $data['form'] = 'Credit Clearance Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }

    public function dischargeClearancePaymentsfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($request->encounter == null || $request->encounter == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = Helpers::numberFormat($total_amount,'insert');
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $request->encounter;
                $data['form'] = 'Discharge Clearance Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }

    public function depositClearancePaymentsfonePayInit(Request $request)
    {
        $convergent_config_status = $this->verify_convergent_configs();

        if ($request->encounter == null || $request->encounter == '')
            return redirect()->route('convergent.payments-failure');

        $patient = Encounter::where('fldencounterval', $request->encounter)->with('patientInfo')->first();

        if (!$patient || !$patient->patientInfo) {
            Session::flash('error_message', 'Patient not found.');
            return redirect()->back();
        }

        if (!$convergent_config_status) {
            Session::flash('error_message', 'Payment Failed. Please try again or use other payment options.');
            return redirect()->back();
        }

        try {
            \DB::beginTransaction();

                /*TOTAL COST*/
                $total_amount = '1';# replace 1 with $request->total variable when deployed

                $payment_params = [];
                $payment_params['merchantCode'] = $this->_api_context_convergent['pid'];
                //                $payment_params['MD'] = 'P';
                $payment_params['amount'] = Helpers::numberFormat($total_amount,'insert');
                //                $payment_params['CRN'] = 'NPR';
                //                $payment_params['DT'] = Carbon::parse(config('constants.current_date'))->format('m/d/Y');

                //                $PRN = $billNumberGeneratedString.time();

                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : '';
                $convgnt_R1 = $user_rank . "-" . $patient->patientInfo->fldptnamefir . "-" . $patient->patientInfo->fldmidname . "-" . $patient->patientInfo->fldptnamelast;
                $PRN = time() . str_replace(' ', '-', $patient->patientInfo->fldptnamelast);

                $PRN = str_limit($PRN, 18, '');

                $payment_params['remarks1'] = "CogentHealth";
                $payment_params['remarks2'] = str_limit($convgnt_R1, 25, '');
                //                $payment_params['RU'] = route('convergent.payments-process');
                $payment_params['prn'] = $PRN;
                $payment_params['username'] = 'COGENT_USER';
                $payment_params['password'] = 'Cogent@123';/*$payment_params['payment_url'] = $this->_api_context_convergent['payment_url']*/;

                $secret_key = $this->_api_context_convergent['secret_key'];

                $sha512_data = $payment_params['amount'] . ','
                    . $payment_params['prn'] . ','
                    . $payment_params['merchantCode'] . ','
                    . $payment_params['remarks1'] . ','
                    . $payment_params['remarks2'];

                $payment_params['dataValidation'] = hash_hmac('sha512', $sha512_data, $secret_key);

                $client = new Client();

                $res = $client->request('POST', $this->_api_context_convergent['payment_url'], ['json' =>$payment_params]);
                $data['response_qr'] = $res->getBody()->getContents();
                $data['encounterid'] = $request->encounter;
                $data['form'] = 'Deposit Clearance Form';
                \DB::commit();
                $html = view('convergentpayment::payment-fonepay-bk', $data)->render();
                return response()->json([
                    'success' => TRUE,
                    'html' => $html
                ]);

        } catch (\Exception $e) {
                       dd($e);
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
                'message' => 'Payment failed. Please select other payment options.'
            ]);
            // Session::flash('error_message', 'Payment failed. Please select other payment options.');
            // return redirect()->back();
        }

    }
    public function convergentPackagePaymentResponse()
    {
        $generatedBillNumber = Session::get('generatedBillNumber');
        $_package_ref_no = Session::get($generatedBillNumber . '-cnvgt-package-ref-no');
        // clear the session
        Session::forget('generatedBillNumber');
        Session::forget($generatedBillNumber . '-cnvgt-package-ref-no');

        $merchant_v_response = Input::all();

        /** Logger : PAYMENT RESPONSE */
        $this->_cc_logger->info("----------------------------------------------------------------------------");
        $this->_cc_logger->info("PAYMENT RESPONSE, BOOKING ID : " . Crypt::decryptString($generatedBillNumber));
        $this->_cc_logger->info(json_encode($merchant_v_response));


        $booking_details = PatBillDetail::where('fldid', Crypt::decryptString($generatedBillNumber))->first();

        if (!$booking_details) {
            return redirect()->route('convergent.payments-failure');
        }

        $total_fare = $booking_details->flditemamt;

        // Verification Parameters
        $package_params_verify = [];
        $package_params_verify['PRN'] = $merchant_v_response['PRN'] ?? null;
        $package_params_verify['PID'] = $merchant_v_response['PID'] ?? null;
        $package_params_verify['BID'] = $merchant_v_response['BID'] ?? null;
        $package_params_verify['AMT'] = $total_fare;
        $package_params_verify['RU'] = $this->_api_context_convergent['payment_url'] . '/api/merchantRequest/verificationMerchant';
        $package_params_verify['UID'] = $merchant_v_response['UID'];
        $secret_key = $this->_api_context_convergent['secret_key'];

        $package_params_verify['MD'] = 'P';

        $sha512_data = $package_params_verify['PID'] . ','
            . $package_params_verify['MD'] . ','
            . $package_params_verify['PRN'] . ','
            . $package_params_verify['AMT'] . ','
            . $package_params_verify['CRN'] . ','
            . $package_params_verify['DT'] . ','
            . $package_params_verify['R1'] . ','
            . $package_params_verify['R2'] . ','
            . $package_params_verify['RU'];


        /* $sha512_data = $package_params_verify['PID'] . ',' . $package_params_verify['AMT'] . ',' . $package_params_verify['PRN'] . ',' . $package_params_verify['BID'] . ',' . $package_params_verify['UID'];*/
        $package_params_verify['DV'] = hash_hmac('sha512', $sha512_data, $secret_key);

        /** Verifying Payment with Convergent */
        $payment_verification_status = $this->verifyPaymentsConvergent($package_params_verify);
        if (!$payment_verification_status) {
            Session::flash('error_message', 'Payment failed. Please select other payment options.');
            //            return redirect()->route('query.package.confirmation', [$booking_details->bookingcode]);
            return redirect()->route('convergent.payments-failure');
        }


        /** Convergent Payment Reference */
        $cnvgt_payment_ref = [
            'amount' => $payment_verification_status->amount ?? null,
            'bankCode' => $payment_verification_status->bankCode ?? null,
            'initiator' => $payment_verification_status->initiator ?? null,
            'message' => $payment_verification_status->message ?? null,
            'response_code' => $payment_verification_status->response_code ?? null,
            'statusCode' => $payment_verification_status->statusCode ?? null,
            'success' => $payment_verification_status->success ?? null,
            'txnAmount' => $payment_verification_status->txnAmount ?? null,
            'uniqueId' => $payment_verification_status->uniqueId ?? null
        ];

        $package_create_payload = [
            'encounter_id' => $booking_details->fldencounterval,
            'payment_type' => 'FonePay',
            'amount_paid' => $total_fare,
            'bill_details' => $booking_details,
            'reference_id' => $_package_ref_no,
            'payment_response' => json_encode($cnvgt_payment_ref),
        ];

        /** Generate Billing Number */
        $billNumber = AutoId::where('fldtype', 'InvoiceNo')->first();

        $new_bill_number = $billNumber->fldvalue + 1;
        AutoId::where('fldtype', 'InvoiceNo')->update(['fldvalue' => $new_bill_number]);

        $dateToday = Carbon::now();
        $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
            ->first();

        $billNumberGeneratedString = "CAS-$year->fldname-$new_bill_number" . Options::get('hospital_code');
        $booking_details->update(['fldsave' => 1, 'fldbillno' => $billNumberGeneratedString]);
        $package_record_status = Payment::create($package_create_payload);

        /**insert to fiscal year*/
        //materlised view fiscal table insert
        if(strtolower($request->payment_mode) == 'cash' ){
            $today_date = Carbon::now()->format('Y-m-d');
            $taxableamt = '0.00';
            $customerDetails = Encounter::where('fldencounterval', $booking_details->fldencounterval)->with('patientInfo')->first();
            $today_date = Carbon::now()->format('Y-m-d');
            $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

            $totalfiscal = PatBilling::where('fldencounterval', $booking_details->fldencounterval)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', null)
                    ->where('fldbillno','LIKE',$billNumberGeneratedString)
                    ->sum('fldditemamt');

                $totalammtfiscal = PatBilling::where('fldencounterval', $booking_details->fldencounterval)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', null)
                    ->where('fldbillno','LIKE',$billNumberGeneratedString)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                $discountfiscal = PatBilling::where('fldencounterval', $booking_details->fldencounterval)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', null)
                    ->where('fldbillno','LIKE',$billNumberGeneratedString)
                    ->sum('flddiscamt');
                $taxableamountfiscal = PatBilling::where('fldencounterval', $booking_details->fldencounterval)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })

                    ->where('fldtaxper', '>', 0)
                    ->where('fldtempbillno', null)
                    ->where('fldbillno','LIKE',$billNumberGeneratedString)
                    ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                $taxfiscal = PatBilling::where('fldencounterval', $booking_details->fldencounterval)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', null)
                    ->where('fldbillno','LIKE',$billNumberGeneratedString)
                    ->sum('fldtaxamt');

            $totalfldditemamt = $totalfiscal;
            $totalitemamt = $totalammtfiscal[0]->total;
            if(!empty($taxableamountfiscal)){
                if($taxableamountfiscal[0]->total > 0)
                    $taxableamt = $taxableamountfiscal[0]->total - $discountfiscal;
            }

            $fiscalData = [
                'Fiscal_Year' => $fiscal_year->fldname,
                'Bill_no' => $billNumberGeneratedString,
                'Customer_name' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fullname : '',
                'Customer_pan' => $customerDetails->patientInfo ? $customerDetails->patientInfo->fldpannumber : '',
                'Bill_Date' => date("Y-m-d H:i:s"),
                'Amount' => Helpers::numberFormat($totalitemamt),
                'Discount' => Helpers::numberFormat($discountfiscal),
                'Taxable_Amount' =>  Helpers::numberFormat($taxableamt),
                'Tax_Amount' => Helpers::numberFormat($taxfiscal),
                'Total_Amount' => Helpers::numberFormat($totalfldditemamt),
                'Sync_with_IRD' => 0,
                'IS_Bill_Printed' => 'Printed',
                'Is_Bill_Active' => 'Active',
                'Printed_Time' => date("Y-m-d H:i:s"),
                'Entered_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Printed_By' => Auth::guard('admin_frontend')->user()->flduserid,
                'Is_realtime' => 'N',
                'Payment_Method' => $request->payment_mode,
                'VAT_Refund_Amount' => 0,
            ];

            $fiscalToIrd = Fiscalyear::create($fiscalData);
            /**CALL FISCAL FUNCTION TO SYNC IRD*/
            // if (Options::get('ird_sync_status') === 'active') {
            //     $fiscalObj = new FiscalDataController();
            //     $fiscalObj->syncIndividualIRD($fiscalToIrd->field);
            // }

        }


            // materlised view end

        if (!$package_record_status) {
            Session::flash('error_message', 'Payment failed. Please select other payment options.');
            //            return redirect()->route('query.package.confirmation', [$booking_details->bookingcode]);
            return redirect()->route('convergent.payments-failure');
        }

        return redirect()->route('package.payment.success', $booking_details->booking_code)->with('success_message', 'Payment Successful. Our representative will contact you as soon as the booking is done processing.');

    }

    /**
     * @param null $package_params_verify
     * @return bool|\SimpleXMLElement
     */
    private function verifyPaymentsConvergent($package_params_verify = null)
    {
        if ($package_params_verify == null || count($package_params_verify) == 0)
            return false;

        try {

            $package_params_verify_query = http_build_query($package_params_verify);
            $package_params_verify_query = $package_params_verify['RU'] . '?' . $package_params_verify_query;

            /** Logger : VERIFY REQUEST */
            $this->_cc_logger->info("---------------------------------------------------------------------------");
            $this->_cc_logger->info("VERIFY REQUEST");
            $this->_cc_logger->info($package_params_verify_query);

            $curl_connection = curl_init($package_params_verify_query);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
            $payment_status = curl_exec($curl_connection);
            curl_close($curl_connection);

            /** Logger : VERIFY RESPONSE */
            $this->_cc_logger->info("---------------------------------------------------------------------------");
            $this->_cc_logger->info("VERIFY RESPONSE");
            $this->_cc_logger->info($payment_status);

            // Payment verification
            $response_payment = simplexml_load_string($payment_status);
            if (isset($response_payment->response_code) && $response_payment->response_code == 'successful') { // payment success
                return $response_payment;
            } else { //payment failed
                return false;
            }

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function verify_convergent_configs()
    {
        if (!$this->_api_context_convergent['mode'] || $this->_api_context_convergent['mode'] == null || $this->_api_context_convergent['mode'] == "") {
            return false;
        }

        if (!$this->_api_context_convergent['payment_url'] || $this->_api_context_convergent['payment_url'] == null || $this->_api_context_convergent['payment_url'] == "") {
            return false;
        }

        if (!$this->_api_context_convergent['pid'] || $this->_api_context_convergent['pid'] == null || $this->_api_context_convergent['pid'] == "") {
            return false;
        }

        if (!$this->_api_context_convergent['secret_key'] || $this->_api_context_convergent['secret_key'] == null || $this->_api_context_convergent['secret_key'] == "") {
            return false;
        }

        return true;

    }

    public function convergentPackagePaymentFailure()
    {
        $data = [];
        return redirect()->route("billing.display.form")->with('error_message', "Payment error, please try again later.");
    }


    public function saveConvergentPaymentLog(request $request){
        try{

            if(isset($request->encounterid) and $request->encounterid !=''){
                $patient = \App\Encounter::where('fldencounterval', $request->encounterid)->with('patientInfo')->first();
                $data['fldencounterval'] = $request->encounterid;
                $data['fldpatientval'] = $patient->patientInfo->fldpatientval;
            }


            $data['fldresponse'] = $request->response;
            $data['fldform'] = $request->form;
            $data['compId'] = Helpers::getCompName();
            $data['flduser'] = Helpers::getCurrentUserName();
            $fonepaylog = \App\Fonepaylog::create($data);
            return response([
                'success' => true,
                'fonepaylogId' =>$fonepaylog->id
            ]);
        }catch(\Exception $e){
            dd($e);
            return response([
                'success' => false
            ]);
        }
    }



}
