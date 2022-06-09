<?php

namespace Modules\Diagnosis\Http\Controllers;

use App\Pathocategory;
use App\Radiolimit;
use App\RadioOption;
use App\Sampletype;
use App\SubRadioQuali;
use App\SubTestQuali;
use App\Sysconst;
use App\TestLimit;
use App\TestOption;
use App\TestQuali;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('diagnosis::index');
    }

    public function addPathoCategory(Request $request) {
        $response = array();
        try {
            $flclass = $request->flclass;
            $fldcategory = $request->fldcategory;

            $data=[];
            $data['flclass'] = $flclass;
            $data['fldcategory'] = $fldcategory;

            $checkdublicate = Pathocategory::where(['flclass' => $flclass, 'fldcategory' => $fldcategory])->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Category exists already.';
            } else {
                Pathocategory::Insert($data);

                $latestcategory = Pathocategory::where('fldcategory', $fldcategory)->orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Category added successfully.';
                $response['fldid'] = $latestcategory->fldid;
                $response['flclass'] = $latestcategory->flclass;
            }


        } catch(\Exception $e) {

//            $response['message'] = $e->getMessage();
            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function addSysConstant(Request $request) {
        $response = array();
        try {
            $fldsysconst = $request->fldsysconst;
            $fldcategory = $request->fldcategory;

            $data=[];
            $data['fldsysconst'] = $fldsysconst;
            $data['fldcategory'] = $fldcategory;
            $checkdublicate = Sysconst::where(['fldsysconst' => $fldsysconst, 'fldcategory' => $fldcategory])->get();

            if(count($checkdublicate) > 0) {
                $response['message']  = 'Sys Contant exist already';

            } else {
                Sysconst::Insert($data);
                $response['message'] = 'Sys Constant added successfully.';
            }


        } catch(\Exception $e) {

//            $response['message'] = $e->getMessage();
            $response['message'] = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function addSpecimen(Request $request) {
        $response = array();
        try {
            $fldsampletype = $request->fldsampletype;

            $data=[];
            $data['fldsampletype'] = $fldsampletype;

            $checkdublicate = Sampletype::where('fldsampletype', $fldsampletype)->get();

            if(count($checkdublicate) > 0) {
                $response['message'] = 'Sample type Exists already.';
            } else {

                Sampletype::Insert($data);

                $sampletype = Sampletype::orderBy('fldid', 'DESC')->first();
                $response['message'] = 'Specimen added successfully.';
                $response['fldid'] = $sampletype->fldid;
                $response['fldsampletype'] = $sampletype->fldsampletype;
            }


        } catch(\Exception $e) {

//            $response['message'] = $e->getMessage();
            $response = "Sorry something went wrong.";
        }

        return json_encode($response);
    }

    public function deleteCategory($fldid) {
        $response = array();
        try {

            $pathocategory = Pathocategory::find($fldid);

            if($pathocategory) {
                $pathocategory->delete();
            }
            $response['message'] = "success";
            $response['successmessage'] = 'Category deleted successfully.';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

//            $response['errormessage'] = 'something went wrong while deleting category';
//            $response['message'] = 'error';
        }

        return  json_encode($response);
    }

    public function deleteSysconstant($fldsysconst,Request $request) {
        $response = array();
        try {
            if($request->has('fldcategory')){
                $fldcategory = $request->fldcategory;
            }else{
                $fldcategory = "Test";
            }
            $sysconstants = Sysconst::where(['fldsysconst' => $fldsysconst, 'fldcategory' => $fldcategory])->get();

            if($sysconstants) {
                foreach($sysconstants as $sysconstant) {
                    DB::table('tblsysconst')->where(['fldsysconst' => $fldsysconst, 'fldcategory' => $fldcategory])->delete();
                }
            }

            $response['message'] = 'success';
            $response['successmessage'] = 'Sysconstant deleted successfully.';
        } catch(\Exception $e) {

//            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';
//            $response['message'] = 'error';
        }

        return  json_encode($response);
    }

    public function deleteSpecimen($fldid) {
        $response = array();
        try {

            $specimen = Sampletype::find($fldid);

            if($specimen) {
                $specimen->delete();
            }

            $response['successmessage'] = 'Specimen deleted successfully.';
            $response['message'] = 'success';
        } catch(\Exception $e) {

            $response['errormessage'] = $e->getMessage();

            $response['errormessage'] = 'something went wrong while deleting category';
            $response['message'] = 'error';
        }

        return json_encode($response);
    }


//    options related functions

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
            } else {
                \DB::table('tbltestmethod')->insert([
                    'fldmethod' => $request->flclass,
                    'fldcateg' => 'Test'
                ]);
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Variable.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add Variable.',
            ]);
        }
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

    // get Method variables
    public function getMethodVariables()
    {
        $getMethodVariables = \DB::table('tbltestmethod')->select('fldmethod as col')->where('fldcateg', 'Test')->get();
        return response()->json($getMethodVariables);
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
                    'fldunit' => $request->unit,
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
                    'fldsilow' => $request->lower_si,
                    'fldsihigh' => $request->upper_si,
                    'fldsinormal' => $request->normal_si,
                    'fldsiunit' => $request->unit_si,
                    'fldmetlow' => $request->lower_mu,
                    'fldmethigh' => $request->upper_mu,
                    'fldmetnormal' => $request->normal_mu,
                    'fldmetunit' => $request->unit_mu,
                ];
                $insert = TestLimit::insert($data);
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Quantitative Test Parameter.',
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add Quantitative Test Parameter.',
                ]);
            }
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
                    'fldmethod' => $request->fldmethod,
                    'fldminimum' => $request->valid_range,
                    'fldmaximum' => $request->matric_unit,
                    'fldsensitivity' => $request->sensitivity,
                    'fldspecificity' => $request->specificity,
                    'fldagegroup' => $request->age_group,
                    'fldptsex' => $request->gender,
                    'fldconvfactor' => $request->factor,
                    'fldsilow' => $request->lower_si,
                    'fldsihigh' => $request->upper_si,
                    'fldsinormal' => $request->normal_si,
                    'fldsiunit' => $request->unit_si,
                    'fldmetlow' => $request->lower_mu,
                    'fldmethigh' => $request->upper_mu,
                    'fldmetnormal' => $request->normal_mu,
                    'fldmetunit' => $request->unit_mu,
                ];
                $update = TestLimit::where('fldid', $request->fldid)->update($data);
            }

            if ($update) {
                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Quantitative Test Parameter']),
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Update Quantitative Test Parameter.',
                ]);
            }
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
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Delete Quantitative Test Parameter.',
                ]);
            }
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
            $get_related_data = RadioLimit::where('fldexamid', $test_name)
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
        }else{
            echo "data aaena"; exit;
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
                $insert = SubRadioQuali::insert($data);
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldanswertype' => $request->input_mode,
                    'fldanswer' => $request->answer,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => null
                ];
                $insert = SubTestQuali::insert($data);
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Sub Test Option.',
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add Sub Test Option.',
                ]);
            }
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
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Delete.',
                ]);
            }
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
                    'fldradioid' => $request->test_name,
                    'fldsubradio' => $request->subtest,
                    'fldanswertype' => $request->subinput_mode,
                    'fldanswer' => null,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => 1,
                    'hospital_department_id' => null,

                ];
                $insert = SubRadioQuali::insertGetId($data);
                $inserteddetail = SubRadioQuali::where('fldid', $insert)->first();
            }else{
                $data = [
                    'fldtestid' => $request->test_name,
                    'fldsubtest' => $request->subtest,
                    'fldanswertype' => $request->subinput_mode,
                    'fldanswer' => null,
                    'fldscale' => 0,
                    'fldscalegroup' => null,
                    'fldindex' => 1,
                    'hospital_department_id' => null,
                ];
                $insert = SubTestQuali::insertGetId($data);
                $inserteddetail = SubTestQuali::where('fldid', $insert)->first();
            }

            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added  Test Option.',
                    'fldid' => $inserteddetail->fldid,
                    'fldanswer' => $inserteddetail->fldsubtest,
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add  Test Option.',

                ]);
            }
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
                    'message' => 'Successfully Added  Test Option.',
                    'fldid' => $inserteddetail->fldid,
                    'fldanswer' => $inserteddetail->answer,

                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add  Test Option.',
                ]);
            }
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
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add Clinical Scale.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }


//    options related functions end
}
