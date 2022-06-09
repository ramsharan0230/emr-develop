<?php

namespace Modules\PaymentGatewaySetting\Http\Controllers;

use App\Utils\Options;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class PaymentGatewaySettingController extends Controller
{
    // All Payment Gateway List
    public function allPayments()
    {
        $data=array();
        $data['title']="Payment Gateways Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::all_payments',$data);
    }

    //Esewa Settings
    /*public function esewa()
    {
        if ( !Permission::checkPermissionFrontendAdmin( 'esewa-payment-gateway' ) )
            return redirect()->route('access-forbidden');

        $data=array();
        $data['breadcrumbs']='<li><a href="'.route('admin.dashboard').'">Home</a></li><li><a href="'.route('admin.paymentgateway.list').'">Payment Gateways</a></li><li>eSewa Settings</li>';
        $data['title']="eSewa Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::esewa',$data);
    }

    public function esewaStore(Request $request)
    {
        Options::update('esewa_test_server_url', $request->get('esewa_test_server_url'));
        Options::update('esewa_test_transactionverf_url', $request->get('esewa_test_transactionverf_url'));
        Options::update('esewa_test_merchant_code', $request->get('esewa_test_merchant_code'));
        Options::update('esewa_live_server_url', $request->get('esewa_live_server_url'));
        Options::update('esewa_live_transactionverf_url', $request->get('esewa_live_transactionverf_url'));
        Options::update('esewa_live_merchant_code', $request->get('esewa_live_merchant_code'));

        if( $request->has('esewa_mode') )
            Options::update('esewa_mode', $request->get('esewa_mode'));

        if( $request->has('esewa_payment_status') )
            Options::update('esewa_payment_status', $request->get('esewa_payment_status'));

        //handling logo
        if($request->hasFile('esewa_logo'))
        {
            $image = $request->file('esewa_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('esewa_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.esewa');
    }*/

    //Paypal Settings
    /*public function paypal()
    {
        if ( !Permission::checkPermissionFrontendAdmin( 'paypal-payment-gateway' ) )
            return redirect()->route('access-forbidden');

        $data=array();
        $data['breadcrumbs']='<li><a href="'.route('admin.dashboard').'">Home</a></li><li><a href="'.route('admin.paymentgateway.list').'">Payment Gateways</a></li><li>Paypal Settings</li>';
        $data['title']="Paypal Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::paypal',$data);
    }

    public function paypalStore(Request $request)
    {
        if( $request->get('paypal_live_client_id') != null )
            Options::update('paypal_live_client_id', $request->get('paypal_live_client_id'));

        if( $request->get('paypal_live_secret_id') != null )
            Options::update('paypal_live_secret_id', $request->get('paypal_live_secret_id'));

        if( $request->get('paypal_test_client_id') != null )
            Options::update('paypal_test_client_id', $request->get('paypal_test_client_id'));

        if( $request->get('paypal_test_secret_id') != null )
            Options::update('paypal_test_secret_id', $request->get('paypal_test_secret_id'));

        if( $request->get('paypal_mode') != null )
            Options::update('paypal_mode', $request->get('paypal_mode'));

        if( $request->has('paypal_payment_status') )
            Options::update('paypal_payment_status', $request->get('paypal_payment_status'));

        //handling logo
        if($request->hasFile('paypal_logo'))
        {
            $image = $request->file('paypal_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('paypal_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.paypal');
    }*/

    //Convergent Settings
    public function convergent()
    {
        if ( !Permission::checkPermissionFrontendAdmin( 'convergent-payment-gateway' ) )
            return redirect()->route('access-forbidden');

        $data=array();
        $data['title']="Convergent Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::convergent',$data);
    }

    public function convergentStore(Request $request)
    {
        if (!file_exists(public_path('uploads/paymentpartner')))
            mkdir(public_path('uploads/paymentpartner'), 0777, true);

        if( $request->get('convergent_test_server_url') != null )
            Options::update('convergent_test_server_url', $request->get('convergent_test_server_url'));

        if( $request->get('convergent_test_pid') != null )
            Options::update('convergent_test_pid', $request->get('convergent_test_pid'));

        if( $request->get('convergent_test_secret_key') != null )
            Options::update('convergent_test_secret_key', $request->get('convergent_test_secret_key'));

        if( $request->get('convergent_live_server_url') != null )
            Options::update('convergent_live_server_url', $request->get('convergent_live_server_url'));

        if( $request->get('convergent_live_pid') != null )
            Options::update('convergent_live_pid', $request->get('convergent_live_pid'));

        if( $request->get('convergent_live_secret_key') != null )
            Options::update('convergent_live_secret_key', $request->get('convergent_live_secret_key'));

        if( $request->get('convergent_mode') != null )
            Options::update('convergent_mode', $request->get('convergent_mode'));

        if( $request->has('convergent_payment_status') )
            Options::update('convergent_payment_status', $request->get('convergent_payment_status'));

        if( $request->has('generate_qr') )
            Options::update('generate_qr', $request->get('generate_qr'));

        //handling logo
        if($request->hasFile('convergent_logo'))
        {
            $image = $request->file('convergent_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('convergent_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.convergent');
    }

    //IMEPay Settings
    public function imepay()
    {
        if ( !Permission::checkPermissionFrontendAdmin( 'imepay-payment-gateway' ) )
            return redirect()->route('access-forbidden');

        $data=array();
        $data['breadcrumbs']='<li><a href="'.route('admin.dashboard').'">Home</a></li><li><a href="'.route('admin.paymentgateway.list').'">Payment Gateways</a></li><li>IMEPay Settings</li>';
        $data['title']="IMEPay Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::imepay',$data);
    }

    public function imepayStore(Request $request)
    {
        if( $request->get('imepay_test_merchant_code') != null )
            Options::update('imepay_test_merchant_code', $request->get('imepay_test_merchant_code'));

        if( $request->get('imepay_test_username') != null )
            Options::update('imepay_test_username', $request->get('imepay_test_username'));

        if( $request->get('imepay_test_password') != null )
            Options::update('imepay_test_password', $request->get('imepay_test_password'));

        if( $request->get('imepay_test_module') != null )
            Options::update('imepay_test_module', $request->get('imepay_test_module'));

        if( $request->get('imepay_test_token_url') != null )
            Options::update('imepay_test_token_url', $request->get('imepay_test_token_url'));

        if( $request->get('imepay_test_checkout_url') != null )
            Options::update('imepay_test_checkout_url', $request->get('imepay_test_checkout_url'));

        if( $request->get('imepay_test_payment_confirm_url') != null )
            Options::update('imepay_test_payment_confirm_url', $request->get('imepay_test_payment_confirm_url'));

        if( $request->get('imepay_test_payment_recheck_url') != null )
            Options::update('imepay_test_payment_recheck_url', $request->get('imepay_test_payment_recheck_url'));

        if( $request->get('imepay_mode') != null )
            Options::update('imepay_mode', $request->get('imepay_mode'));

        if( $request->get('imepay_live_merchant_code') != null )
            Options::update('imepay_live_merchant_code', $request->get('imepay_live_merchant_code'));

        if( $request->get('imepay_live_username') != null )
            Options::update('imepay_live_username', $request->get('imepay_live_username'));

        if( $request->get('imepay_live_password') != null )
            Options::update('imepay_live_password', $request->get('imepay_live_password'));

        if( $request->get('imepay_live_module') != null )
            Options::update('imepay_live_module', $request->get('imepay_live_module'));

        if( $request->get('imepay_live_token_url') != null )
            Options::update('imepay_live_token_url', $request->get('imepay_live_token_url'));

        if( $request->get('imepay_live_checkout_url') != null )
            Options::update('imepay_live_checkout_url', $request->get('imepay_live_checkout_url'));

        if( $request->get('imepay_live_payment_confirm_url') != null )
            Options::update('imepay_live_payment_confirm_url', $request->get('imepay_live_payment_confirm_url'));

        if( $request->get('imepay_live_payment_recheck_url') != null )
            Options::update('imepay_live_payment_recheck_url', $request->get('imepay_live_payment_recheck_url'));

        if( $request->has('imepay_payment_status') )
            Options::update('imepay_payment_status', $request->get('imepay_payment_status'));

        //handling logo
        if($request->hasFile('imepay_logo'))
        {
            $image = $request->file('imepay_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('imepay_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.imepay');
    }

    // Nabil Credit Card
    /*public function nabilCredit()
    {
        if ( !Permission::checkPermissionFrontendAdmin( 'creditcard-payment-gateway' ) )
            return redirect()->route('access-forbidden');

        $data=array();
        $data['breadcrumbs']='<li><a href="'.route('admin.dashboard').'">Home</a></li><li><a href="'.route('admin.paymentgateway.list').'">Payment Gateways</a></li><li>Credit Card Settings</li>';
        $data['title']="Credit Card Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::nabil_credit_card',$data);
    }

    public function nabilCreditStore(Request $request)
    {

        if( $request->get('nabil_test_server_url') != null )
            Options::update('nabil_test_server_url', $request->get('nabil_test_server_url'));

        if( $request->get('nabil_test_verify_url') != null )
            Options::update('nabil_test_verify_url', $request->get('nabil_test_verify_url'));

        if( $request->get('nabil_test_merchant_code') != null )
            Options::update('nabil_test_merchant_code', $request->get('nabil_test_merchant_code'));

        if( $request->get('nabil_live_server_url') != null )
            Options::update('nabil_live_server_url', $request->get('nabil_live_server_url'));

        if( $request->get('nabil_live_verify_url') != null )
            Options::update('nabil_live_verify_url', $request->get('nabil_live_verify_url'));

        if( $request->get('nabil_live_merchant_code') != null )
            Options::update('nabil_live_merchant_code', $request->get('nabil_live_merchant_code'));

        if( $request->get('nabil_mode') != null )
            Options::update('nabil_mode', $request->get('nabil_mode'));

        if( $request->has('nabil_payment_status') )
            Options::update('nabil_payment_status', $request->get('nabil_payment_status'));

        //handling logo
        if($request->hasFile('nabil_logo'))
        {
            $image = $request->file('nabil_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('nabil_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.nabil-credit');
    }*/

    // nPay System
    /*public function nPay()
    {
        //if ( !Permission::checkPermissionFrontendAdmin( 'creditcard-payment-gateway' ) )
           // return redirect()->route('access-forbidden');

        $data=array();
        $data['breadcrumbs']='<li><a href="'.route('admin.dashboard').'">Home</a></li><li><a href="'.route('admin.paymentgateway.list').'">Payment Gateways</a></li><li>nPay Settings</li>';
        $data['title']="nPay Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::npay',$data);
    }

    public function nPayStore(Request $request)
    {
        if( $request->get('npay_test_merchant_id') != null )
            Options::update('npay_test_merchant_id', $request->get('npay_test_merchant_id'));

        if( $request->get('npay_test_merchant_username') != null )
            Options::update('npay_test_merchant_username', $request->get('npay_test_merchant_username'));

        if( $request->get('npay_test_merchant_password') != null )
            Options::update('npay_test_merchant_password', $request->get('npay_test_merchant_password'));

        if( $request->get('npay_test_signature_password') != null )
            Options::update('npay_test_signature_password', $request->get('npay_test_signature_password'));

        if( $request->get('npay_live_merchant_id') != null )
            Options::update('npay_live_merchant_id', $request->get('npay_live_merchant_id'));

        if( $request->get('npay_live_merchant_username') != null )
            Options::update('npay_live_merchant_username', $request->get('npay_live_merchant_username'));

        if( $request->get('npay_live_merchant_password') != null )
            Options::update('npay_live_merchant_password', $request->get('npay_live_merchant_password'));

        if( $request->has('npay_live_signature_password') != null )
            Options::update('npay_live_signature_password', $request->get('npay_live_signature_password'));

        if( $request->has('npay_mode') )
            Options::update('npay_mode', $request->get('npay_mode'));

        if( $request->has('npay_payment_status') )
            Options::update('npay_payment_status', $request->get('npay_payment_status'));


        //handling logo
        if($request->hasFile('npay_logo'))
        {
            $image = $request->file('npay_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('npay_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.npay');
    }*/

    // bank charge
    /*public function bankCharge(Request $request)
    {
        if ( $request->has('gateway_name') && $request->get('gateway_name') != "" ) {
            $gateway_name = $request->get('gateway_name');

            switch ( $gateway_name ) {

                case 'esewa':
                    if( $request->has('esewa_add_bank_charge') )
                        Options::update('esewa_add_bank_charge', $request->get('esewa_add_bank_charge'));

                    if( $request->has('esewa_bank_charge_type') )
                        Options::update('esewa_bank_charge_type', $request->get('esewa_bank_charge_type'));

                    Options::update('esewa_bank_charge', $request->get('esewa_bank_charge'));
                    break;

                case 'imepay':
                    if( $request->has('imepay_add_bank_charge') )
                        Options::update('imepay_add_bank_charge', $request->get('imepay_add_bank_charge'));

                    if( $request->has('imepay_bank_charge_type') )
                        Options::update('imepay_bank_charge_type', $request->get('imepay_bank_charge_type'));

                    Options::update('imepay_bank_charge', $request->get('imepay_bank_charge'));
                    break;

                case 'paypal':
                    if( $request->has('paypal_add_bank_charge') )
                        Options::update('paypal_add_bank_charge', $request->get('paypal_add_bank_charge'));

                    if( $request->has('paypal_bank_charge_type') )
                        Options::update('paypal_bank_charge_type', $request->get('paypal_bank_charge_type'));

                    Options::update('paypal_bank_charge', $request->get('paypal_bank_charge'));
                    break;

                case 'convergent':
                    if( $request->has('convergent_add_bank_charge') )
                        Options::update('convergent_add_bank_charge', $request->get('convergent_add_bank_charge'));

                    if( $request->has('convergent_bank_charge_type') )
                        Options::update('convergent_bank_charge_type', $request->get('convergent_bank_charge_type'));

                    Options::update('convergent_bank_charge', $request->get('convergent_bank_charge'));
                    break;

                case 'credit_card':
                    if( $request->has('credit_card_add_bank_charge') )
                        Options::update('credit_card_add_bank_charge', $request->get('credit_card_add_bank_charge'));

                    if( $request->has('nabil_bank_charge_type') )
                        Options::update('nabil_bank_charge_type', $request->get('nabil_bank_charge_type'));

                    Options::update('nabil_bank_charge', $request->get('nabil_bank_charge'));
                    break;

                case 'npay':
                    if( $request->has('npay_card_add_bank_charge') )
                        Options::update('npay_card_add_bank_charge', $request->get('npay_card_add_bank_charge'));

                    if( $request->has('npay_bank_charge_type') )
                        Options::update('npay_bank_charge_type', $request->get('npay_bank_charge_type'));

                    Options::update('npay_bank_charge', $request->get('npay_bank_charge'));
                    break;

                default:

            }

        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.list');
    }*/

    // NIBL System
    /*public function nibl() {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.paymentgateway.list') . '">Payment Gateways</a></li><li>NIBL Settings</li>';
        $data['title'] = "NIBL Settings - " . \Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::nibl', $data);
    }

    public function niblStore(Request $request) {

        if ($request->get('nibl_test_transaction_url') != null)
            Options::update('nibl_test_transaction_url', $request->get('nibl_test_transaction_url'));

        if ($request->get('nibl_test_transaction_verify_url') != null)
            Options::update('nibl_test_transaction_verify_url', $request->get('nibl_test_transaction_verify_url'));

        if ($request->get('nibl_test_bank_id') != null)
            Options::update('nibl_test_bank_id', $request->get('nibl_test_bank_id'));

        if ($request->get('nibl_test_payee_id') != null)
            Options::update('nibl_test_payee_id', $request->get('nibl_test_payee_id'));

        if ($request->get('nibl_test_username') != null)
            Options::update('nibl_test_username', $request->get('nibl_test_username'));

        if ($request->get('nibl_test_password') != null)
            Options::update('nibl_test_password', $request->get('nibl_test_password'));

        if ($request->get('nibl_live_transaction_url') != null)
            Options::update('nibl_live_transaction_url', $request->get('nibl_live_transaction_url'));

        if ($request->has('nibl_live_transaction_verify_url') != null)
            Options::update('nibl_live_transaction_verify_url', $request->get('nibl_live_transaction_verify_url'));

        if ($request->has('nibl_live_bank_id') != null)
            Options::update('nibl_live_bank_id', $request->get('nibl_live_bank_id'));

        if ($request->has('nibl_live_payee_id') != null)
            Options::update('nibl_live_payee_id', $request->get('nibl_live_payee_id'));

        if ($request->has('nibl_live_username') != null)
            Options::update('nibl_live_username', $request->get('nibl_live_username'));

        if ($request->has('nibl_live_password') != null)
            Options::update('nibl_live_password', $request->get('nibl_live_password'));

        if ($request->has('nibl_mode'))
            Options::update('nibl_mode', $request->get('nibl_mode'));

        if ($request->has('nibl_payment_status'))
            Options::update('nibl_payment_status', $request->get('nibl_payment_status'));

        //handling logo
        if ($request->hasFile('nibl_logo')) {
            $image = $request->file('nibl_logo');
            $logo = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
            $path = public_path() . "/uploads/paymentpartner/";
            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path . '' . $logo);
            Options::update('nibl_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.nibl');
    }*/

    // Prabhu System
    /*public function prabhu() {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.paymentgateway.list') . '">Payment Gateways</a></li><li>Prabhu Bank Settings</li>';
        $data['title'] = "Prabhu Bank Settings - " . \Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::prabhu', $data);
    }

    public function prabhuStore(Request $request) {

        if ($request->get('prabhu_test_server_url') != null)
            Options::update('prabhu_test_server_url', $request->get('prabhu_test_server_url'));

        if ($request->get('prabhu_test_pid') != null)
            Options::update('prabhu_test_pid', $request->get('prabhu_test_pid'));

        if ($request->get('prabhu_live_server_url') != null)
            Options::update('prabhu_live_server_url', $request->get('prabhu_live_server_url'));

        if ($request->get('prabhu_live_pid') != null)
            Options::update('prabhu_live_pid', $request->get('prabhu_live_pid'));


        if ($request->has('prabhu_mode'))
            Options::update('prabhu_mode', $request->get('prabhu_mode'));

        if ($request->has('prabhu_payment_status'))
            Options::update('prabhu_payment_status', $request->get('prabhu_payment_status'));


        //handling logo
        if ($request->hasFile('prabhu_logo')) {
            $image = $request->file('prabhu_logo');
            $logo = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

            $path = public_path() . "/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path . '' . $logo);
            Options::update('prabhu_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.prabhu');
    }*/

    //HBL Settings
    public function hbl()
    {
        /*if ( !Permission::checkPermissionFrontendAdmin( 'hbl-payment-gateway' ) )
            return redirect()->route('access-forbidden');*/

        $data=array();
        $data['title']="HBL Settings - ".\Options::get('siteconfig')['system_name'];
        return view('paymentgatewaysetting::hbl',$data);
    }

    public function hblStore(Request $request)
    {
        Options::update('hbl_merchant_test_id', $request->get('hbl_merchant_test_id'));
        Options::update('hbl_merchant_test_name', $request->get('hbl_merchant_test_name'));
        Options::update('hbl_test_secret', $request->get('hbl_test_secret'));
        Options::update('hbl_test_server_url', $request->get('hbl_test_server_url'));
        Options::update('hbl_merchant_live_id', $request->get('hbl_merchant_live_id'));
        Options::update('hbl_merchant_live_name', $request->get('hbl_merchant_live_name'));
        Options::update('hbl_live_secret', $request->get('hbl_live_secret'));
        Options::update('hbl_live_server_url', $request->get('hbl_live_server_url'));

        if( $request->has('hbl_mode') )
            Options::update('hbl_mode', $request->get('hbl_mode'));

        if( $request->has('hbl_payment_status') )
            Options::update('hbl_payment_status', $request->get('hbl_payment_status'));

        //handling logo
        if($request->hasFile('hbl_logo'))
        {
            $image = $request->file('hbl_logo');
            $logo  = time() . '-' .rand(111111,999999).'.'.$image->getClientOriginalExtension();

            $path = public_path()."/uploads/paymentpartner/";

            // Resizing and Upload using Intervention
            $interventinoImg = \Image::make($image);
            //$interventinoImg->resize($this->top_ads_width, $this->top_ads_height);
            $interventinoImg->save($path.''.$logo);
            Options::update('hbl_logo', $logo);
        }

        Session::flash('success_message', 'Records updated successfully.');
        return redirect()->route('admin.paymentgateway.hbl');
    }
}
