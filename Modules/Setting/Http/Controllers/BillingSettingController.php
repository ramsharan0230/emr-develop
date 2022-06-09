<?php

namespace Modules\Setting\Http\Controllers;

use App\Utils\Options;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Log;
use Session;

class BillingSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('setting::billing');
    }

    public function typeSetting(Request $request)
    {
        try {
            $bill_type = $request->get('bill_type');
            Options::update($bill_type, $request->get('header'));

            if($bill_type == 'Service-Billing-Header')
            Options::update('Service-Billing-Total',$request->get('total'));
            elseif($bill_type == 'Pharmacy-Billing-Header')
            Options::update('Pharmacy-Billing-Total',$request->get('total'));
            elseif($bill_type == 'Deposit-Billing-Header')
            Options::update('Deposit-Billing-Total',$request->get('total'));
            elseif($bill_type == 'Discharge-Billing-Header')
            Options::update('Discharge-Billing-Total',$request->get('total'));
            elseif($bill_type == 'Return-Billing-Header')
            Options::update('Return-Billing-Total',$request->get('total'));

            Helpers::logStack(["Billing setting updated", "Billing Setting"]);
            Session::flash('success_message', 'Billing Setting updated successfully.');
            return redirect()->route('billing.setting');
        }
        catch (Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in billing setting update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('billing.setting');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSetting(Request $request)
    {
        try {
            Options::update('discharge_clearance_bill_format', $request->get('discharge_clearance_bill_format'));
            return redirect()->back()->with('success_message', __('messages.data_success', ['name' => 'Discharge clearance ']));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    public function saveEmergencyShareSetting(Request $request)
    {
        try {
            Options::update('emergency_drshare_hospital', $request->get('emergency_drshare_hospital'));
            return redirect()->back()->with('success_message', __('messages.data_success', ['name' => 'Emergency Share enable ']));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveBillingToggle(Request $request)
    {
        try {
            Options::update('display_billing_toggle', $request->get('display_billing_toggle'));
            return redirect()->back()->with('success_message', __('messages.data_success', ['name' => 'Billing toggle ']));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function discountBilling(Request $request)
    {
        try {
            Options::update('discount_percent_cashier_form', $request->get('discount_percent_cashier_form'));
            return redirect()->back()->with('success_message', __('messages.data_success', ['name' => 'Discount ']));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }


}
