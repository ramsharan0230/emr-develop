<?php

namespace Modules\Laboratory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TestMethodController extends Controller
{
    public function index(Request $request)
    {
        $form_errors = [];
        if ($request->isMethod('post')) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'fldtestid' => 'required',
                'fldmethod' => 'required',
            ]);

            if ($validator->passes()) {
                $fldmethod = explode(',', $request->get('fldmethod'));
                $fldtestid = $request->get('fldtestid');

                $insert_data = array_map(function($method) use ($fldtestid) {
                    return [
                        'fldmethod' => $method,
                        'fldtestid' => $fldtestid,
                        'fldcateg' => 'Test',
                    ];
                }, $fldmethod);
                \App\TestMethod::where('fldtestid', $fldtestid)->delete();
                \App\TestMethod::insert($insert_data);

                return redirect()->route('laboratory.testmethod')->with('success_message', 'Test method added successfully.');
            } else {
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $form_errors[$key] = $value[0];
            }
        }

        return view('laboratory::testmethod', [
            'tests' => \App\Test::select('fldtestid')->with('methods:fldtestid,fldmethod')->get(),
            'form_errors' => $form_errors,
        ]);
    }
}
