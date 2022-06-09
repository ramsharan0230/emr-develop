<?php

namespace Modules\Setting\Http\Controllers;

use App\AccountLedger;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class AccountSettingController extends Controller
{
    public function index()
    {
        $data['ledgers'] = AccountLedger::whereDoesntHave('account_user')->get();
        return view('setting::account-map', $data);
    }

    public function add(Request $request)
    {
        try {
            Options::update('ledger_tax_doctor_fraction', $request->get('ledger_tax_doctor_fraction'));
            Options::update('default_discount', $request->get('default_discount'));
            Options::update('default_cash_in_hand', $request->get('default_cash_in_hand'));
            Session::flash('success', 'Records updated successfully.');
            return redirect()->route('account.setting');
        }catch (\Exception $exception){
            Session::flash('error', __('messages.error'));
            return redirect()->route('account.setting');
        }
    }
}
