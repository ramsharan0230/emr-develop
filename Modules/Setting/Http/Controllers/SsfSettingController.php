<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Options;

class SsfSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::ssf.index');
    }

    public function settingSave(Request $request)
    {
        $request->validate([
            'ssf_url' => 'required',
            'ssf_username' => 'required',
            'ssf_password' => 'required',
            'ssf_remote_user' => 'required',
        ]);

        try {
            $settingsKey = 'ssf_settings';
            $settingsValue = [
                'ssf_url' => $request->ssf_url,
                'ssf_username' => $request->ssf_username,
                'ssf_password' => $request->ssf_password,
                'ssf_remote_user' => $request->ssf_remote_user,
            ];
            Options::update($settingsKey, $settingsValue);

            session()->flash('success_message', 'Setting Saved');
        } catch (\Exception $e) {
            session()->flash('error_message', 'Something went wrong');
        }

        return redirect()->back();
    }

}
