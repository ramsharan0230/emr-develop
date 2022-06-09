<?php

namespace Modules\Setting\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Session;

class IrdController extends Controller
{
    public function setting()
    {
        return view('setting::ird-setting');
    }

    public function saveIrd(Request $request)
    {
        $rules = array(
            'ird_sync_status' => 'required',
            'ird_mode' => 'required',
            'ird_test_server_url' => 'required',
            'ird_test_username' => 'required',
            'ird_test_password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('ird.setting')->withErrors($validator)->withInput();
        }

        try {
            Options::update('ird_test_server_url', $request->get('ird_test_server_url'));
            Options::update('ird_test_username', $request->get('ird_test_username'));
            Options::update('ird_test_password', $request->get('ird_test_password'));

            Options::update('ird_live_server_url', $request->get('ird_live_server_url'));
            Options::update('ird_live_username', $request->get('ird_live_username'));
            Options::update('ird_live_password', $request->get('ird_live_password'));

            Options::update('ird_sync_status', $request->get('ird_sync_status'));
            Options::update('ird_mode', $request->get('ird_mode'));

            Session::flash('success', 'Records updated successfully.');
            return redirect()->route('ird.setting');
        } catch (\Exception $exception) {
            Session::flash('error', __('messages.error'));
            return redirect()->route('ird.setting');
        }
    }
}
