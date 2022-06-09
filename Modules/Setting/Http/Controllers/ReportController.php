<?php

namespace Modules\Setting\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;

class ReportController extends Controller
{
    public function saveData(Request $request)
    {
        try {
            $dataRequest = $request->except('_token');
            foreach ($dataRequest as $key => $value){
                if ($value != ""){
                    Options::update($key, $value);
                }
            }
            Session::flash('success_message', 'Records updated successfully.');
            return redirect()->route('report-setting');
        } catch (\Exception $e) {

        }
    }
}
