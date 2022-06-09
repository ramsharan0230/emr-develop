<?php

namespace Modules\Diagnosis\Http\Controllers;

use App\Pathocategory;
use App\Sampletype;
use App\Sysconst;
use App\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['categorytype'] = 'Test';
        return view('diagnosis::laboratory.diagnostic-test', $data);
    }

    public function addTest(Request $request) {

        $request->validate([
            'fldtestid' => 'required|unique:tbltest',
            'fldcategory' => 'required',
            'fldsysconst' => 'required',
            'fldspecimen' => 'required'
        ],[
            'fldtestid.required' => 'Test Name field is required',
            'fldtestid.unique' => 'Test Name already exists, please change it.',
            'fldcategory.required' => 'Category field is required',
            'fldsysconst.required' => 'Sys Constant field is required',
            'fldspecimen.required' => 'Specimen field is required'
        ]);

        try {
            $test_data = $request->all();

            unset($test_data['_token']);
            Test::insert($test_data);

            Session::flash('success_message', 'Diagnostic test added sucessfully');

            return redirect()->route('laborotarylist');
        } catch(\Exception $e) {
            $error_message = $e->getMessage();
//            $error_message = 'Sorry something went wrong while adding the clinical Examination.';
            Session::flash('error_message', $error_message);

            return redirect()->route('diagnostictest.list');
        }



    }

    public function editTest($fldtestid) {
        $fldtestid = decrypt($fldtestid);
        $data = [];
        $test = Test::where('fldtestid', $fldtestid)->first();

        $data['test'] = $test;
        $data['categorytype'] = 'Test';

        return view('diagnosis::laboratory.editdiagnostictest', $data);

    }

    public function updateTest(Request $request, $fldtestid) {
        $fldtestid = decrypt($fldtestid);
        $request->validate([
            'fldtestid' => 'required|unique:tbltest,fldtestid,'.$request->fldtestid.',fldtestid',
            'fldcategory' => 'required',
        ],[
            'fldtestid.required' => 'Test Name field is required',
            'fldtestid.unique' => 'Test Name already exists, please change it.',
            'fldcategory.required' => 'Category field is required',
        ]);

        try {
            $test_edit_data = $request->all();
            unset($test_edit_data['_token']);
            unset($test_edit_data['_method']);
//            dd($test_edit_data);
            Test::where('fldtestid', $fldtestid)->update($test_edit_data,['timestamps' => false]);

            Session::flash('success_message', 'Diagnostic Test updated sucessfully');

            return redirect()->route('diagnostictest.list');
        } catch(\Exception $e) {
            $error_message = $e->getMessage();
//            $error_message = 'Sorry something went wrong while adding the clinical Examination.';
            Session::flash('error_message', $error_message);

            return redirect()->route('diagnostictest.list');
        }
    }

    public function deleteTest($fldtestid) {
        try {
            $fldtestid = decrypt($fldtestid);
            $test = Test::where('fldtestid', $fldtestid)->first();

            if($test) {
                DB::table('tbltest')->where('fldtestid', $fldtestid)->delete();
                Session::flash('success_message', $test->fldtestid.' deleted sucessfully');
            }
        } catch(\Exception $e) {
            Session::flash('error_message', $e->getMessage());
        }

        return redirect()->route('diagnostictest.list');
    }

}
