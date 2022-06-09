<?php

namespace Modules\Setting\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::notification.notification');
    }

    public function settingSave(Request $request)
    {
        try {
            $settingsKey = $request->settingTitle;
            $settingsValue = $request->settingValue;
            Options::update($settingsKey, $settingsValue);

            return response()->json(['message' => 'Setting Saved', 'status' => 'Done']);
        } catch (\GearmanException $e) {
            return response()->json(['message' => 'Something went wrong', 'status' => 'Error']);
        }
    }

}
