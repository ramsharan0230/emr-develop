<?php

namespace Modules\Radiology\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\RadioTemplate;

class RadiologyTemplateController extends Controller
{
    public function index()
    {
        return view('radiology::radio-template', [
            'templates' => RadioTemplate::all()
        ]);
    }

    public function saveUpdate(Request $request)
    {
        $fldid = $request->fldid;

        $request->validate([
            'testid' => ["required", \Illuminate\Validation\Rule::unique('tblradiotemplate', 'fldtestid')->ignore($fldid, 'fldid')],
            'description' => 'required',
        ]);

        $data = [
            'fldtestid' => $request->testid,
            'flddescription' => $request->description,
        ];

        try {
            if ($fldid)
                RadioTemplate::where('fldid', $fldid)->update($data);
            else
                RadioTemplate::insert($data);
            \Session::flash('success_message', 'Data saved sucessfully');
        } catch (Exception $e) {
            \Session::flash('error_message', $e->getMessage());
        }

        return redirect()->route('radiology.template.index');
    }

    public function delete(Request $request, $id)
    {
        try {
            $radio = RadioTemplate::where('fldid', $id)->first();

            if ($radio) {
                RadioTemplate::where('fldid', $id)->delete();
                \Session::flash('success_message', $radio->fldtestid . ' deleted sucessfully');
            }
        } catch (\Exception $e) {
            \Session::flash('error_message', $e->getMessage());
        }

        return redirect()->route('radiology.template.index');
    }
}
