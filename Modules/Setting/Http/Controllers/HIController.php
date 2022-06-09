<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Options;

class HIController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function settingSave(Request $request)
    {
        $request->validate([
            'hi_url' => 'required',
            'hi_username' => 'required',
            'hi_password' => 'required',
            'hi_remote_user' => 'required',
            'hi_location' => 'required',
            'hi_practitioner' => 'required'
        ]);
        
        try {
            $settingsKey = 'hi_settings';
            $settingsValue = [
                'hi_url' => $request->hi_url,
                'hi_username' => $request->hi_username,
                'hi_password' => $request->hi_password,
                'hi_remote_user' => $request->hi_remote_user,
                'hi_location' => $request->hi_location,
                'hi_practitioner' => $request->hi_practitioner,
            ];
            Options::update($settingsKey, $settingsValue);

            session()->flash('success_message', 'Setting Saved');
        } catch (\Exception $e) {
            session()->flash('error_message', 'Something went wrong');
        }

        return redirect()->back();

    }

}
