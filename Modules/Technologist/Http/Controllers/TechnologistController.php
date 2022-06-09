<?php

namespace Modules\Technologist\Http\Controllers;

use App\Pathocategory;
use App\Radiolimit;
use App\RadioOption;
use App\RadioQuali;
use App\SubRadioQuali;
use App\SubTestQuali;
use App\Test;
use App\TestLimit;
use App\TestOption;
use App\TestQuali;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Exception;

class TechnologistController extends Controller
{
    public function getTechnologist()
    {
       // if (Permission::checkPermissionFrontendAdmin('view-diagnostic-tests')) {
            $data['get_test'] = Test::select('fldtestid as col')->whereRaw('LOWER(`fldtestid`) LIKE ? ', '%')->get();
            $data['get_variable_categories'] = \DB::table('tblpathocategory')->where('fldcategory', 'LIKE', '%Test%')->select('fldid', 'flclass')->get();
            $data['get_variable_specimen'] = \DB::table('tblsampletype')->where('fldsampletype', 'LIKE', '%')->select('fldid', 'fldsampletype as col')->get();
            $data['get_variable_constant'] = \DB::table('tblsysconst')->where('fldcategory', 'Test')->select('fldsysconst as col')->get();
            return view('technologist::index', $data);
        // } else {
        //     \Session::flash('display_popup_error_success', true);
        //     \Session::flash('error_message', 'You are not authorized for this action.');
        //     return redirect()->route('admin.dashboard');
        // }
    }

    // insert Category variables
    public function insertVariableCategory(Request $request)
    {
        try {
            $checkifexist = \DB::table('tblpathocategory')->where('flclass', $request->flclass)->first();
            if ($checkifexist != null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Category Already Exists Search The List.',
                ]);
            }

            \DB::table('tblpathocategory')->insert([
                'flclass' => $request->flclass,
                'fldcategory' => 'Test'
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Variable.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Variable.',
            ]);
        }
    }

    // get Category variables
    public function getVariableCategories()
    {
        $get_related_variables = \DB::table('tblpathocategory')->where('fldcategory', 'LIKE', '%Test%')->select('order_by','fldid', 'flclass')->orderBy('order_by', 'ASC')->get();
        return response()->json($get_related_variables);
    }

    // delete Category variables
    public function deleteVariableCategory(Request $request)
    {
        try {
            if ($request->fldid != null) {
                \DB::table('tblpathocategory')->where('fldcategory', 'LIKE', '%Test%')->where('fldid', $request->fldid)->delete();
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Variable.',
                ]);
            }
            return response()->json([
                'status' => FALSE,
                'message' => 'Please Select Variable To Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Variable.',
            ]);
        }
    }

    // Specimen-------------------------------------
    // insert Specimen variables
    public function insertVariableSpecimen(Request $request)
    {
        try {
            $checkifexist = \DB::table('tblsampletype')->where('fldsampletype', $request->fldsampletype)->first();
            if ($checkifexist != null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Spiceman Already Exists Search The List.',
                ]);
            }

            \DB::table('tblsampletype')->insert([
                'fldsampletype' => $request->fldsampletype
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Variable.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Variable.',
            ]);
        }
    }

    // get Specimen variables
    public function getVariableSpecimens()
    {
        $get_related_variables = \DB::table('tblsampletype')->where('fldsampletype', 'LIKE', '%')->select('fldid', 'fldsampletype as col')->get();
        return response()->json($get_related_variables);
    }

    // delete Specimen variables
    public function deleteVariableSpecimen(Request $request)
    {
        try {
            if ($request->fldsampletype != null) {
                \DB::table('tblsampletype')->where('fldsampletype', 'LIKE', '%')->where('fldid', $request->fldsampletype)->delete();
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Variable.',
                ]);
            }
            return response()->json([
                'status' => FALSE,
                'message' => 'Please Select Variable To Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Variable.',
            ]);
        }
    }

    // Sys Constant-------------------------------------
    // insert Constant variables
    public function insertVariableConstant(Request $request)
    {
        try {
            $checkifexist = \DB::table('tblsysconst')->where('fldsysconst', $request->fldsysconst)->first();
            if ($checkifexist != null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Spiceman Already Exists Search The List.',
                ]);
            }

            \DB::table('tblsysconst')->insert([
                'fldsysconst' => $request->fldsysconst,
                'fldcategory' => 'Test'
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Variable.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Variable.',
            ]);
        }
    }

    // get Constant variables
    public function getVariableConstants()
    {
        $get_related_variables = \DB::table('tblsysconst')->where('fldcategory', 'Test')->select('fldsysconst as col')->get();
        return response()->json($get_related_variables);
    }

    // delete Constant variables
    public function deleteVariableConstant(Request $request)
    {
        try {
            if ($request->fldsysconst != null) {
                \DB::table('tblsysconst')->where('fldsysconst', $request->fldsysconst)->delete();
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Variable.',
                ]);
            }
            return response()->json([
                'status' => FALSE,
                'message' => 'Please Select Variable To Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Variable.',
            ]);
        }
    }

    public function getRelatedTestData()
    {
        $fldtestid = Input::get('testid');
        $get_related_test_data = Test::where('fldtestid', $fldtestid)->select('fldtestid', 'fldcategory', 'fldsysconst', 'fldspecimen', 'fldcollection', 'flddetail', 'fldtype', 'fldcomment', 'fldoption', 'fldsensitivity', 'fldspecificity', 'fldcritical', 'fldvial','fldorder')->first();
        return response()->json($get_related_test_data);
    }

    public function technologistInsert(Request $request)
    {
        try {
            $checkifexist = Test::where('fldtestid', $request->test_name)->first();
            if ($checkifexist != null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Test Already Exists Search The List.',
                ]);
            }

            $data = [
                'fldtestid' => $request->test_name,
                'fldcategory' => $request->categories,
                'fldsysconst' => $request->constant,
                'fldspecimen' => $request->specimen,
                'fldcollection' => $request->collection,
                'fldvial' => $request->vial,
                'flddetail' => $request->description,
                'fldtype' => $request->datatype,
                'fldcomment' => $request->comment,
                'fldoption' => $request->input_mode,
                'fldsensitivity' => $request->sensitivity,
                'fldspecificity' => $request->specificity,
                'fldcritical' => $request->critical,
                'fldorder' => $request->order
            ];
            Test::insert($data);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Saved.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add.',
            ]);
        }
    }

    public function technologistList()
    {
        $getNewTestList = Test::select('fldtestid as col')->whereRaw('LOWER(`fldtestid`) LIKE ? ', '%')->get();
        return response()->json($getNewTestList);
    }

    public function technologistUpdate(Request $request)
    {

        try {
            $checkifexist = Test::where('fldtestid', $request->test_name)->first();
            if ($checkifexist == null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Test Name Should not be changed.',
                ]);
            }

            $data = [
                'fldtestid' => $request->test_name,
                'fldcategory' => $request->categories,
                'fldsysconst' => $request->constant,
                'fldspecimen' => $request->specimen,
                'fldcollection' => $request->collection,
                'fldvial' => $request->vial,
                'flddetail' => $request->description,
                'fldtype' => $request->datatype,
                'fldcomment' => $request->comment,
                'fldoption' => $request->input_mode,
                'fldsensitivity' => $request->sensitivity,
                'fldspecificity' => $request->specificity,
                'fldcritical' => $request->critical,
                'fldorder' => $request->order
            ];

            Test::where('fldtestid', $request->test_name)->update($data);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Saved.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add.',
            ]);
        }
    }

    public function technologistDelete(Request $request)
    {
        try {
            if ($request->test_name != null) {
                $checkifexist = Test::where('fldtestid', $request->test_name)->first();
                if ($checkifexist == null) {
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Test Name Should not be changed.',
                    ]);
                }

                Test::where('fldtestid', $request->test_name)->delete();
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Please Select Test To Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete.',
            ]);
        }
    }

    public function technologistUpdateTestName(Request $request)
    {
        try {
            $checkifexist = Test::where('fldtestid', $request->test_name)->first();
            if ($checkifexist == null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Could not find test old test name.',
                ]);
            }

            $testComment = \DB::table('tbltestcomment')->where('fldtestid', $request->test_name)->get();
            $testLimit = \DB::table('tbltestlimit')->where('fldtestid', $request->test_name)->get();
            $testQuali = \DB::table('tbltestquali')->where('fldtestid', $request->test_name)->get();
            $subTestQuali = \DB::table('tblsubtestquali')->where('fldtestid', $request->test_name)->get();
            $testOption = \DB::table('tbltestoption')->where('fldtestid', $request->test_name)->get();
            $codeHypo = \DB::table('tblcodehypo')->where('fldparent', $request->test_name)->get();
            $codeHyper = \DB::table('tblcodehyper')->where('fldparent', $request->test_name)->get();
            $syndroHypo = \DB::table('tblsyndrohypo')->where('fldparent', $request->test_name)->get();
            $syndroHyper = \DB::table('tblsyndrohyper')->where('fldparent', $request->test_name)->get();
            $test = \DB::table('tbltest')->where('fldtestid', $request->test_name)->get();
            $groupTest = \DB::table('tblgrouptest')->where('fldtestid', $request->test_name)->get();

            foreach ($testComment as $comment) {
                \DB::table('tbltestcomment')->where(['fldtestid' => $comment->fldtestid, 'fldid' => $comment->fldid])->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($testLimit as $limit) {
                \DB::table('tbltestlimit')->where(['fldtestid' => $limit->fldtestid, 'fldid' => $limit->fldid])->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($testQuali as $quali) {
                \DB::table('tbltestquali')->where(['fldtestid' => $quali->fldtestid, 'fldid' => $quali->fldid])->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($subTestQuali as $subQuali) {
                \DB::table('tblsubtestquali')->where(['fldtestid' => $subQuali->fldtestid, 'fldid' => $subQuali->fldid])->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($testOption as $option) {
                \DB::table('tbltestoption')->where(['fldtestid' => $option->fldtestid, 'fldid' => $option->fldid])->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($codeHypo as $hypo) {
                \DB::table('tblcodehypo')->where(['fldparent' => $hypo->fldparent, 'fldid' => $hypo->fldid])->update(['fldparent' => $request->test_name_new]);
            }
            foreach ($codeHyper as $hyper) {
                \DB::table('tblcodehyper')->where(['fldparent' => $hyper->fldparent, 'fldid' => $hyper->fldid])->update(['fldparent' => $request->test_name_new]);
            }
            foreach ($syndroHypo as $syndro) {
                \DB::table('tblsyndrohypo')->where(['fldparent' => $syndro->fldparent, 'fldid' => $syndro->fldid])->update(['fldparent' => $request->test_name_new]);
            }
            foreach ($syndroHyper as $syndrohpr) {
                \DB::table('tblsyndrohyper')->where(['fldparent' => $syndrohpr->fldparent, 'fldid' => $syndrohpr->fldid])->update(['fldparent' => $request->test_name_new]);
            }
            foreach ($test as $tst) {
                \DB::table('tbltest')->where('fldtestid', $tst->fldtestid)->update(['fldtestid' => $request->test_name_new]);
            }
            foreach ($groupTest as $gtest) {
                \DB::table('tblgrouptest')->where(['fldtestid' => $gtest->fldtestid, 'fldid' => $gtest->fldid])->update(['fldtestid' => $request->test_name_new]);
            }

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Saved.',
                'test_name' => $request->test_name_new
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // insert Method variables
    public function insertVariableMethod(Request $request)
    {
        try {
            $checkifexist = \DB::table('tbltestmethod')->where(['fldmethod' => $request->fldmethod, 'fldcateg' => 'Test'])->first();
            if ($checkifexist != null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Method Already Exists Search The List.',
                ]);
            }

            \DB::table('tbltestmethod')->insert([
                'fldmethod' => $request->flclass,
                'fldcateg' => 'Test'
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Variable.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Variable.',
            ]);
        }
    }

    // get Method variables
    public function getMethodVariables()
    {
        $getMethodVariables = \DB::table('tbltestmethod')->select('fldmethod as col')->where('fldcateg', 'Test')->get();
        return response()->json($getMethodVariables);
    }

    // delete Method variables
    public function deleteVariableMethod(Request $request)
    {
        try {
            if ($request->fldmethod != null) {
                \DB::table('tbltestmethod')->where('fldmethod', $request->fldmethod)->where('fldcateg', 'LIKE', '%Test%')->delete();
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Variable.',
                ]);
            }
            return response()->json([
                'status' => FALSE,
                'message' => 'Please Select Variable To Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Variable.',
            ]);
        }
    }

    //get test option  yesle
    public function getQualitativeTestOpt(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }else{
            $get_related_data = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }
        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }


    public function getQualitativeSubTestOpt(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = SubRadioQuali::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }else{
            $get_related_data = SubTestQuali::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function insertQualitativeTestOpt(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => null
                ];
                $insert = RadioOption::insert($data);
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => null
                ];
                $insert = Testoption::insert($data);
            }
            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Sub Test Option.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Sub Test Option.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function insertFixedComponentLevelOne(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => $request->subtest,
                    'fldreference' => $request->reference,
                    'fldtanswertype' => $request->subinput_mode,
                    'flddetail' => $request->procedure
                ];
                $insert = RadioQuali::insertGetId($data);
                $inserteddetail = RadioQuali::where('fldid', $insert)->first();
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => $request->subtest,
                    'fldreference' => $request->reference,
                    'fldtanswertype' => $request->subinput_mode,
                    'flddetail' => $request->procedure
                ];
                $insert = TestQuali::insertGetId($data);
                $inserteddetail = TestQuali::where('fldid', $insert)->first();
            }

            if ($insert) {
                if($request->has('diagoType') && $request->diagoType == "radiology"){
                    $fldanswer = $inserteddetail->fldsubexam;
                }else{
                    $fldanswer = $inserteddetail->fldsubtest;
                }
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added  Test Option.',
                    'fldid' => $inserteddetail->fldid,
                    'fldanswer' => $fldanswer,
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add  Test Option.',

            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function updateFixedComponentLevelOne(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldsubexam' => $request->subtest,
                    'fldreference' => $request->reference,
                    'fldtanswertype' => $request->subinput_mode,
                    'flddetail' => $request->procedure
                ];
                $update = RadioQuali::where('fldid',$request->fldid)->update($data);
                $updateddetail = RadioQuali::where('fldid', $request->fldid)->first();
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => $request->subtest,
                    'fldreference' => $request->reference,
                    'fldtanswertype' => $request->subinput_mode,
                    'flddetail' => $request->procedure
                ];
                $update = TestQuali::where('fldid',$request->fldid)->update($data);
                $updateddetail = TestQuali::where('fldid', $request->fldid)->first();
            }

            if ($update) {
                if($request->has('diagoType') && $request->diagoType == "radiology"){
                    $fldanswer = $updateddetail->fldsubexam;
                }else{
                    $fldanswer = $updateddetail->fldsubtest;
                }
                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Data']),
                    'fldid' => $updateddetail->fldid,
                    'fldanswer' => $fldanswer,
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Update  Test Option.',

            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function insertFixedComponentLevelTwo(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldsubexam' => $request->sub_test,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => $request->scale,
                    'fldscalegroup' => $request->scalegrp,
                    'fldindex' => 1,
                ];
                $insert = SubRadioQuali::insertGetId($data);
                $inserteddetail = SubRadioQuali::where('fldid', $insert)->first();
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => $request->sub_test,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => $request->scale,
                    'fldscalegroup' => $request->scalegrp,
                    'fldindex' => 1,
                ];
                $insert = SubTestQuali::insertGetId($data);
                $inserteddetail = SubTestQuali::where('fldid', $insert)->first();
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Test Option.',
                    'fldid' => $inserteddetail->fldid,
                    'fldanswer' => $inserteddetail->fldanswer,

                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add  Test Option.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }


    public function insertQualitativeSubTestOpt(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldsubexam' => '',
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => null
                ];
                $insert = SubRadioQuali::insertGetId($data);
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => '',
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => null
                ];
                $insert = SubTestQuali::insertGetId($data);
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Test Option.'

                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Test Option.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function deleteQualitativeTestOpt(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $delete = RadioOption::where('fldid', $request->id)->delete();
            }else{
                $delete = TestOption::where('fldid', $request->id)->delete();
            }

            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function deleteQualitativeSubTestOpt(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $delete = SubRadioQuali::where('fldid', $request->id)->delete();
            }else{
                $delete = SubTestQuali::where('fldid', $request->id)->delete();
            }

            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    //yesle
    public function getQualitativeClinicialScale(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldscale', 'fldscalegroup', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }else{
            $get_related_data = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldscale', 'fldscalegroup', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    //yesle
    public function getDistinctGroup(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $group = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct('fldscalegroup')->get();
        }else{
            $group = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct('fldscalegroup')->get();
        }

        if ($group != null) {
            return response()->json($group);
        }
    }

    public function insertQualitativeClinicalScale(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->parameter,
                    'fldscale' => $request->value,
                    'fldscalegroup' => $request->scale_group,
                    'fldindex' => null
                ];
                $insert = RadioOption::insert($data);
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->parameter,
                    'fldscale' => $request->value,
                    'fldscalegroup' => $request->scale_group,
                    'fldindex' => null
                ];
                $insert = TestOption::insert($data);
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Clinical Scale.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Clinical Scale.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function insertQuantitativeTestPara(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldexamid' => $request->test_name,
                    'fldmethod' => $request->method,
                    'fldminimum' => $request->valid_range,
                    'fldmaximum' => $request->matric_unit,
                    'fldsensitivity' => $request->sensitivity,
                    'fldspecificity' => $request->specificity,
                    'fldagegroup' => $request->age_group,
                    'fldptsex' => $request->gender,
                    'fldlow' => $request->lower,
                    'fldhigh' => $request->upper,
                    'fldnormal' => $request->normal,
                    'fldunit' => $request->unit
                ];
                $insert = Radiolimit::insert($data);
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldmethod' => $request->method,
                    'fldminimum' => $request->valid_range,
                    'fldmaximum' => $request->matric_unit,
                    'fldsensitivity' => $request->sensitivity,
                    'fldspecificity' => $request->specificity,
                    'fldagegroup' => $request->age_group,
                    'fldptsex' => $request->gender,
                    'fldconvfactor' => $request->factor,
                    'fldsilow' => $request->lower_mu,
                    'fldsihigh' => $request->upper_mu,
                    'fldsinormal' => $request->normal_mu,
                    'fldsiunit' => $request->unit_mu,
                    'fldmetlow' => $request->lower_si,
                    'fldmethigh' => $request->upper_si,
                    'fldmetnormal' => $request->normal_si,
                    'fldmetunit' => $request->unit_si,
                ];
                $insert = TestLimit::insert($data);
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Quantitative Test Parameter.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Quantitative Test Parameter.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function updateQuantitativeTestPara(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $data = [
                    'fldmethod' => $request->method,
                    'fldminimum' => $request->valid_range,
                    'fldmaximum' => $request->matric_unit,
                    'fldsensitivity' => $request->sensitivity,
                    'fldspecificity' => $request->specificity,
                    'fldagegroup' => $request->age_group,
                    'fldptsex' => $request->gender,
                    'fldlow' => $request->lower,
                    'fldhigh' => $request->upper,
                    'fldnormal' => $request->normal,
                    'fldunit' => $request->unit,
                ];
                $update = Radiolimit::where('fldid', $request->fldid)->update($data);
            }else{
                $data = [
                    'fldmethod' => $request->method,
                    'fldminimum' => $request->valid_range,
                    'fldmaximum' => $request->matric_unit,
                    'fldsensitivity' => $request->sensitivity,
                    'fldspecificity' => $request->specificity,
                    'fldagegroup' => $request->age_group,
                    'fldptsex' => $request->gender,
                    'fldconvfactor' => $request->factor,
                    'fldsilow' => $request->lower_mu,
                    'fldsihigh' => $request->upper_mu,
                    'fldsinormal' => $request->normal_mu,
                    'fldsiunit' => $request->unit_mu,
                    'fldmetlow' => $request->lower_si,
                    'fldmethigh' => $request->upper_si,
                    'fldmetnormal' => $request->normal_si,
                    'fldmetunit' => $request->unit_si,
                ];
                $update = TestLimit::where('fldid', $request->fldid)->update($data);
            }

            if ($update) {
                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Quantitative Test Parameter']),
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Update Quantitative Test Parameter.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function deleteQuantitativeTestPara(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $delete = Radiolimit::where('fldid', $request->fldid)->delete();
            }else{
                $delete = TestLimit::where('fldid', $request->fldid)->delete();
            }

            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Quantitative Test Parameter.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Quantitative Test Parameter.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getQuantitativeTestParaMu(Request $request)
    {
        $test_name = Input::get('test_name');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = Radiolimit::where('fldexamid', $test_name)
                                ->select('fldid', 'fldptsex', 'fldagegroup', 'fldnormal', 'fldlow', 'fldhigh', 'fldunit', 'fldmethod', 'fldsensitivity', 'fldspecificity')
                                ->get();
        }else{
            $get_related_data = TestLimit::where('fldtestid', $test_name)
                                ->select('fldid', 'fldptsex', 'fldagegroup', 'fldmetnormal', 'fldmetlow', 'fldmethigh', 'fldmetunit', 'fldconvfactor', 'fldmethod', 'fldsensitivity', 'fldspecificity')
                                ->get();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getQuantitativeTestParaSi()
    {
        $test_name = Input::get('test_name');
        $get_related_data = TestLimit::where('fldtestid', $test_name)
            ->select('fldid', 'fldptsex', 'fldagegroup', 'fldsinormal', 'fldsilow', 'fldsihigh', 'fldsiunit', 'fldconvfactor', 'fldmethod', 'fldsensitivity', 'fldspecificity')
            ->get();

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getQuantitativeTestPara(Request $request)
    {
        $fldid = Input::get('fldid');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = Radiolimit::where('fldid', $fldid)
                                ->select('fldid', 'fldmethod', 'fldminimum', 'fldmaximum', 'fldsensitivity', 'fldspecificity', 'fldagegroup', 'fldptsex', 'fldlow', 'fldhigh', 'fldnormal', 'fldunit')
                                ->first();
        }else{
            $get_related_data = TestLimit::where('fldid', $fldid)
                                ->select('fldid', 'fldmethod', 'fldminimum', 'fldmaximum', 'fldsensitivity', 'fldspecificity', 'fldagegroup', 'fldptsex', 'fldconvfactor', 'fldsilow', 'fldsihigh', 'fldsinormal', 'fldsiunit', 'fldmetlow', 'fldmethigh', 'fldmetnormal', 'fldmetunit')
                                ->first();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }


    //pooja
    public function getDistinctGroupsub(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $group = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct()->get();
        }else{
            $group = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct()->get();
        }


        if ($group != null) {
            return response()->json($group);
        }
    }

    public function getDistinctGroupSubTest(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        $subtest = Input::get('fldsubtest');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $group = SubRadioQuali::where([
                'fldexamid' => $test,
                'fldsubexam' => $subtest,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct()->get();
        }else{
            $group = SubTestQuali::where([
                'fldtestid' => $test,
                'fldsubtest' => $subtest,
                'fldanswertype' => $mode
            ])->select('fldscalegroup')->distinct()->get();
        }


        if ($group != null) {
            return response()->json($group);
        }
    }


    public function getQualitativeTestOptsub(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }else{
            $get_related_data = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getQualitativeClinicialScalesub(Request $request)
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            $get_related_data = RadioOption::where([
                'fldexamid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldscale', 'fldscalegroup', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }else{
            $get_related_data = TestOption::where([
                'fldtestid' => $test,
                'fldanswertype' => $mode
            ])->select('fldid', 'fldanswer', 'fldscale', 'fldscalegroup', 'fldindex')
                ->orderBy('fldindex', 'ASC')
                ->get();
        }

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function deleteTestQuali(Request $request)
    {
        try {
            $delete = false;
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $examData = RadioQuali::where('fldid', $request->fldid)->first();
                if($examData){
                    $subRadioQualiDatas = SubRadioQuali::where([
                                                                ['fldexamid',$examData->fldtestid],
                                                                ['fldsubexam',$examData->fldsubtest]
                                                            ])->get();
                    foreach($subRadioQualiDatas as $subRadioQualiData){
                        $subRadioQualiData->delete();
                    }
                    $delete = RadioQuali::where('fldid', $request->fldid)->delete();
                }
            }else{
                $testData = TestQuali::where('fldid', $request->fldid)->first();
                if($testData){
                    $subTestQualiDatas = SubTestQuali::where([
                                                                ['fldtestid',$testData->fldtestid],
                                                                ['fldsubtest',$testData->fldsubtest]
                                                            ])->get();
                    foreach($subTestQualiDatas as $subTestQualiData){
                        $subTestQualiData->delete();
                    }
                    $delete = TestQuali::where('fldid', $request->fldid)->delete();
                }
            }

            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Parameter.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function deleteSubTestQuali(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $delete = SubRadioQuali::where('fldid', $request->fldid)->delete();
            }else{
                $delete = SubTestQuali::where('fldid', $request->fldid)->delete();
            }

            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                ]);
            }

            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Delete Parameter.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function oldTestQuali(Request $request)
    {
        // echo $request->fldtestid; exit;
        // $testData = TestQuali::where('fldtestid', $request->fldtestid)->toSql();
        // echo $testData; exit;
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            if ($testData = RadioQuali::where('fldexamid', $request->fldtestid)->get()) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                    'data' => $testData
                ]);
            }
        }else{
            if ($testData = TestQuali::where('fldtestid', $request->fldtestid)->orderBy('order_by','ASC')->get()) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                    'data' => $testData
                ]);
            }
        }

        return response()->json([
            'status' => FALSE,
            'message' => 'Failed to Get Parameter.',
        ]);
    }

    public function oldSubTestQuali(Request $request)
    {
        if($request->has('diagoType') && $request->diagoType == "radiology"){
            if ($testData = SubRadioQuali::where([
                                                    ['fldexamid', $request->fldtestid],
                                                    ['fldsubexam', $request->fldsubtest],
                                                    ['fldanswertype', $request->fldanswertype]
                                                ])->get()) {
                // dd($testData);
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                    'data' => $testData
                ]);
            }
        }else{
            if ($testData = SubTestQuali::where([
                                                    ['fldtestid', $request->fldtestid],
                                                    ['fldsubtest', $request->fldsubtest],
                                                    ['fldanswertype', $request->fldanswertype]
                                                ])->get()) {
                // dd($testData);
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                    'data' => $testData
                ]);
            }
        }

        return response()->json([
            'status' => FALSE,
            'message' => 'Failed to Get Parameter.',
        ]);
    }

    //category ko order update garne
    public function updateOrder(Request $request)
    {
        try {
            if ($request->positions) {
                foreach ($request->positions as $sortdata) {
                    Pathocategory::where('flclass', $sortdata[2])
                        ->update(['order_by' => $sortdata[1]]);
                }
            }
            return response()->json(['message' => 'Order Saved', 'status' => 'Done']);
        } catch (\GearmanException $e) {
            return response()->json(['message' => 'Something went wrong', 'status' => 'Error']);
        }
    }
    public function updateTestOrder(Request $request)
    {
        try {
            if ($request->positions) {
                foreach ($request->positions as $sortdata) {
                    Test::where('fldtestid', $sortdata[2])
                        ->update(['fldorder' => $sortdata[1]]);
                }
            }
            if($request->category){
                $tests = Test::select('fldtestid','fldorder')->where('fldcategory', 'LIKE', $request->category)->orderBy('fldorder', 'ASC')->get();
                $html ='';
                if($tests){
                    foreach ($tests as $test)
                        $html.='<tr rel="'.$test->fldtestid.'" data-class="'.$test->fldtestid.'"> <td class="fixed-side">'.(isset($test->fldorder) ? ($test->fldorder ? $test->fldorder.'-' :'') :'' ).$test->fldtestid.'</td> </tr>';
                    return response()->json(['message'=>'Saved successfully','html' =>$html]);
                }else{
                    return response()->json(['html'=>'<tr><td>No data available</td></tr>']);
                }
            }
            return response()->json(['message' => 'Order Saved', 'status' => 'Done']);
        } catch (\GearmanException $e) {
            return response()->json(['error' => 'Something went wrong', 'status' => 'Error']);
        }
    }

    public function updateTestQualiOrder(Request $request)
    {
        try {
            if ($request->positions) {
                foreach ($request->positions as $sortdata) {
                    TestQuali::where('fldid', $sortdata[2])
                        ->update(['order_by' => $sortdata[1]]);
                }
            }
            return response()->json(['message' => 'Order Saved', 'status' => 'Done']);
        } catch (\GearmanException $e) {
            return response()->json(['error' => 'Something went wrong', 'status' => 'Error']);
        }
    }
    public  function searchByCategory(Request  $request){
        if(!$request->category){
            return response()->json(['error'=>'Something went wrong']);
        }
        $tests = Test::select('fldtestid','fldorder')->where('fldcategory', 'LIKE', $request->category)->orderBy('fldorder', 'ASC')->get();
        $html ='';
        if($tests){
            foreach ($tests as $test)
                $html.='<tr rel="'.$test->fldtestid.'" data-class="'.$test->fldtestid.'"> <td class="fixed-side">'.(isset($test->fldorder) ? ($test->fldorder ? $test->fldorder.'-' :'') :'' ).$test->fldtestid.'</td> </tr>';
            return response()->json(['html' =>$html]);
        }else{
            return response()->json(['html'=>'<tr><td>No data available</td></tr>']);
        }
    }
}
