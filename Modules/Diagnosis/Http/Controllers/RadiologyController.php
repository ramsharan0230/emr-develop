<?php

namespace Modules\Diagnosis\Http\Controllers;

use App\Radio;
use App\RadioOption;
use App\TestOption;
use App\Utils\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class RadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (Permission::checkPermissionFrontendAdmin('radiology')) {
            $data = [];
            $data['categorytype'] = 'Radio';
            return view('diagnosis::radiology.radio-diagnostics', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function addRadio(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'fldexamid' => 'required|unique:tblradio',
                'fldcategory' => 'required',
            ], [
                'fldexamid.required' => 'Test Name field is required',
                'fldexamid.unique' => 'Test Name already exists, please change it.',
                'fldcategory.required' => 'Category field is required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'data' => [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                ]);
            }

            $radio_data = $request->all();

            unset($radio_data['_token']);
            Radio::insert($radio_data);
            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Radio diagnostic added sucessfully',
                ]
            ]);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'data' => [
                    'status' => false,
                    'errors' => []
                ]
            ]);
        }
    }

    public function editRadio(Request $request)
    {
        try{
            $radioData = Radio::where('fldexamid', $request->examid)->first();
            return response()->json([
                'data' => [
                    'status' => true,
                    'radioData' => $radioData,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function updateRadio(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'fldexamid' => 'required|unique:tblradio,fldexamid,' . $request->fldexamid . ',fldexamid',
                'fldcategory' => 'required',
            ], [
                'fldexamid.required' => 'Radio Name field is required',
                'fldexamid.unique' => 'Radio Name already exists, please change it.',
                'fldcategory.required' => 'Category field is required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'data' => [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                ]);
            }

            $radio_data = $request->all();

            unset($radio_data['_token']);
            $radioData = Radio::where('fldexamid', $request->fldexamid)->first();
            if($radioData){
                Radio::where('fldexamid', $request->fldexamid)->update($radio_data, ['timestamps' => false]);

                return response()->json([
                    'data' => [
                        'status' => true,
                        'message' => 'Radio diagnostic updated sucessfully',
                    ]
                ]);
            }else{
                return response()->json([
                    'data' => [
                        'status' => false,
                        'errors' => [],
                        'message' => "Invalid radio diagnostic test!"
                    ]
                ]);
            }


        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            return response()->json([
                'data' => [
                    'status' => false,
                    'errors' => [],
                    'message' => "Something went wrong!"
                ]
            ]);
        }
    }

    public function deleteRadio(Request $request)
    {
        try {
            $radio = Radio::where('fldexamid', $request->testName)->first();

            if ($radio) {
                DB::table('tblradio')->where('fldexamid', $request->testName)->delete();
                return response()->json([
                    'data' => [
                        'status' => true,
                        'msg' => "Radio diagnostic deleted successfully!",
                    ]
                ]);
            }

            return response()->json([
                'data' => [
                    'status' => false,
                    'msg' => "Test name not found!"
                ]
            ]);

        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false,
                    'msg' => "Something went wrong!"
                ]
            ]);
        }

        return redirect()->route('diagnostictest.list');
    }

    public function searchRadiology(Request $request) {
        $response = array();
        $searchkeyword = $request->searchkeyword;

        try {

            $searchresults = Radio::where('fldexamid', 'like', ''.$searchkeyword . '%')->get();

            $html = '';
            if(count($searchresults) > 0) {
                foreach($searchresults as $k=>$searchresult) {
                    $html .= '<tr>';
                    $html .= '<td class="dietary-td" width="80%">'. $searchresult->fldexamid  .'</td>';
                    $html .= '<td class="dietary-td" width="15%">';
                    $html .= '<a type="button" href="'. route('radiodiagnostic.edit', encrypt($searchresult->fldexamid)) .'"  title="edit' . $searchresult->fldexamid  .'"><i class="fa fa-edit"></i></a>&nbsp;';
                    $html .= '<a type="button" title="delete'. $searchresult->fldexamid  .'" class="deleteradiodiagnostictest" data-href="'. route('radiodiagnostic.delete', encrypt($searchresult->fldexamid)) .'"><i class="far fa-trash-alt"></i></a>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function insertTextAddition(Request $request)
    {
        try {
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $chkRadioOptionData = RadioOption::where([['fldexamid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
                if(isset($chkRadioOptionData)){
                    RadioOption::where([['fldexamid',$request->test_name],['fldanswertype',$request->input_mode]])->update([
                        'fldanswer' => $request->answer
                    ]);
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Text Updated.',
                    ]);
                }else{
                    $data = [
                        'fldexamid' => $request->test_name,
                        'fldanswertype' => $request->input_mode,
                        'fldanswer' => $request->answer
                    ];
                    RadioOption::insert($data);
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Text Addition.',
                    ]);
                }
            }else{
                $chkTestOptionData = TestOption::where([['fldtestid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
                if(isset($chkTestOptionData)){
                    TestOption::where([['fldtestid',$request->test_name],['fldanswertype',$request->input_mode]])->update([
                        'fldanswer' => $request->answer
                    ]);
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Text Updated.',
                    ]);
                }else{
                    $data = [
                        'fldtestid' => $request->test_name,
                        'fldanswertype' => $request->input_mode,
                        'fldanswer' => $request->answer
                    ];
                    TestOption::insert($data);
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Text Addition.',
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getTextAddition(Request $request){
        try{
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $result = RadioOption::where([['fldexamid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
            }else{
                $result = TestOption::where([['fldtestid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
            }

            return response()->json([
                'status' => TRUE,
                'message' => 'Success',
                'result' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function deleteTextAddition(Request $request){
        try{
            if($request->has('diagoType') && $request->diagoType == "radiology"){
                $chkRadioOptionData = RadioOption::where([['fldexamid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
                if(isset($chkRadioOptionData)){
                    RadioOption::where([['fldexamid',$request->test_name],['fldanswertype',$request->input_mode]])->delete();
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Deleted.',
                    ]);
                }else{
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Unable to delete.',
                    ]);
                }
            }else{
                $chkTestOptionData = TestOption::where([['fldtestid',$request->test_name],['fldanswertype',$request->input_mode]])->first();
                if(isset($chkTestOptionData)){
                    TestOption::where([['fldtestid',$request->test_name],['fldanswertype',$request->input_mode]])->delete();
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Successfully Deleted.',
                    ]);
                }else{
                    return response()->json([
                        'status' => TRUE,
                        'message' => 'Unable to delete.',
                    ]);
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function radiologyUpdateTestName(Request $request)
    {
        try {
            $checkifexist = Radio::where('fldexamid', $request->test_name)->first();
            if ($checkifexist == null) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Could not find exam name.',
                ]);
            }

            $radioComment = \DB::table('tblradiocomment')->where('fldexamid', $request->test_name)->get();
            $radioLimit = \DB::table('tblradiolimit')->where('fldexamid', $request->test_name)->get();
            $radioQuali = \DB::table('tblradioquali')->where('fldexamid', $request->test_name)->get();
            $subRadioQuali = \DB::table('tblsubradioquali')->where('fldexamid', $request->test_name)->get();
            $radioOption = \DB::table('tblradiooption')->where('fldexamid', $request->test_name)->get();
            $radio = \DB::table('tblradio')->where('fldexamid', $request->test_name)->get();
            $groupRadio = \DB::table('tblgroupradio')->where('fldtestid', $request->test_name)->get();

            foreach ($radioComment as $comment) {
                \DB::table('tblradiocomment')->where(['fldexamid' => $comment->fldexamid, 'fldid' => $comment->fldid])->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($radioLimit as $limit) {
                \DB::table('tblradiolimit')->where(['fldexamid' => $limit->fldexamid, 'fldid' => $limit->fldid])->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($radioQuali as $quali) {
                \DB::table('tblradioquali')->where(['fldexamid' => $quali->fldexamid, 'fldid' => $quali->fldid])->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($subRadioQuali as $subQuali) {
                \DB::table('tblsubradioquali')->where(['fldexamid' => $subQuali->fldexamid, 'fldid' => $subQuali->fldid])->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($radioOption as $option) {
                \DB::table('tblradiooption')->where(['fldexamid' => $option->fldexamid, 'fldid' => $option->fldid])->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($radio as $tst) {
                \DB::table('tblradio')->where('fldexamid', $tst->fldexamid)->update(['fldexamid' => $request->test_name_new]);
            }
            foreach ($groupRadio as $gtest) {
                \DB::table('tblgroupradio')->where(['fldtestid' => $gtest->fldtestid, 'tblid' => $gtest->fldid])->update(['fldtestid' => $request->test_name_new]);
            }

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Saved.',
                'test_name' => $request->test_name_new
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Add.',
            ]);
        }
    }

}
