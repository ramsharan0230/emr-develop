<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Options;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function settingSave(Request $request)
    {
        $request->validate([
            'claim_url' => 'required',
            'claim_username' => 'required',
            'claim_password' => 'required',
            'claim_access_code' => 'required'
        ]);
        
        try {
            $settingsKey = 'claim_settings';
            $settingsValue = [
                'claim_url' => $request->claim_url,
                'claim_username' => $request->claim_username,
                'claim_password' => $request->claim_password,
                'claim_access_code' => $request->claim_access_code,
            ];
            Options::update($settingsKey, $settingsValue);

            session()->flash('success_message', 'Setting Saved');
        } catch (\Exception $e) {
            session()->flash('error_message', 'Something went wrong');
        }

        return redirect()->back();

    }
}
