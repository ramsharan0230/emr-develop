<?php

namespace Modules\Diagnosis\Http\Controllers;

use App\Exam;
use App\Examlimit;
use App\ExamOption;
use App\ExamQuali;
use App\PatLabSubTest;
use App\SubExamQuali;
use App\SubTestQuali;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\DB;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['categorytype'] = 'Exam';

        return view('diagnosis::examinations.clinical-examination', $data);
    }

    public function addExamination(Request $request)
    {

        $request->validate([
            'fldexamid' => 'required|unique:tblexam',
            'fldcategory' => 'required',
        ], [
            'fldexamid.required' => 'Test Name field is required',
            'fldexamid.unique' => 'Test Name already exists, please change it.',
            'fldcategory.required' => 'Category field is required'
        ]);

        try {
            $examination_data = $request->all();
            unset($examination_data['_token']);
            Exam::insert($examination_data);

            Session::flash('success_message', 'Clinical Examination added sucessfully');

            return redirect()->route('examination.examinationlist');
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
//            $error_message = 'Sorry sohing went wrong while adding the clinical Examination.';
            Session::flash('error_message', $error_message);

            return redirect()->route('examination.examinationlist');
        }


    }

    public function editExamination($fldexamid)
    {
        $fldexamid = decrypt($fldexamid);
        $data = [];
        $examination = Exam::where('fldexamid', $fldexamid)->first();

        $data['examination'] = $examination;
        $data['categorytype'] = 'Exam';

        return view('diagnosis::examinations.editclinicalexamination', $data);

    }

    public function updateExamination(Request $request, $fldexamid)
    {
        $fldexamid = decrypt($fldexamid);
        $request->validate([
            'fldexamid' => 'required|unique:tblexam,fldexamid,' . $request->fldexamid . ',fldexamid',
            'fldcategory' => 'required',
        ], [
            'fldexamid.required' => 'Test Name field is required',
            'fldexamid.unique' => 'Test Name already exists, please change it.',
            'fldcategory.required' => 'Category field is required',
        ]);
        try {
            $examination_edit_data = $request->all();
            unset($examination_edit_data['_token']);
            unset($examination_edit_data['_method']);
            unset($examination_edit_data['searchkeyword']);

            Exam::where('fldexamid', $fldexamid)->update($examination_edit_data, ['timestamps' => false]);

            Session::flash('success_message', 'Clinical Examination updated sucessfully');

            return redirect()->route('examination.examinationlist');
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
//            $error_message = 'Sorry something went wrong while adding the clinical Examination.';
            Session::flash('error_message', $error_message);

            return redirect()->route('examination.examinationlist');
        }
    }

    public function deleteExamination($fldexamid)
    {
        try {
            $fldexamid = decrypt($fldexamid);
            $exam = Exam::where('fldexamid', $fldexamid)->first();

            if ($exam) {
                DB::table('tblexam')->where('fldexamid', $fldexamid)->delete();
                Session::flash('success_message', $exam->fldexamid . ' deleted sucessfully');
            }
        } catch (\Exception $e) {
            Session::flash('error_message', $e->getMessage());
        }

        return redirect()->route('examination.examinationlist');
    }

    public function searchExamination(Request $request)
    {
        $response = array();
        $searchkeyword = $request->searchkeyword;

        try {

            $searchresults = Exam::where('fldexamid', 'like', '' . $searchkeyword . '%')->get();

            $html = '';
            if (count($searchresults) > 0) {
                foreach ($searchresults as $k => $searchresult) {
                    $html .= '<tr>';
                    $html .= '<td class="dietary-td" width="80%">' . $searchresult->fldexamid . '</td>';
                    $html .= '<td class="dietary-td" width="15%">';
                    $html .= '<a type="button" href="' . route('examination.editexamination', encrypt($searchresult->fldexamid)) . '"  title="edit' . $searchresult->fldexamid . '"><i class="fa fa-edit"></i></a>&nbsp;';
                    $html .= '<a type="button" title="delete' . $searchresult->fldexamid . '" class="deleteexam" data-href="' . route('examination.deleteexamination', encrypt($searchresult->fldexamid)) . '"><i class="far fa-trash-alt"></i></a>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            }

            $response['html'] = $html;
            $response['message'] = 'success';

        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);

    }

    public function getQualitativeSubTestOpt()
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        $get_related_data = SubExamQuali::where([
            'fldexamid' => $test,
            'fldanswertype' => $mode
        ])->select('fldid', 'fldanswer', 'fldindex')
            ->orderBy('fldindex', 'ASC')
            ->get();
        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function insertQualitativeSubTestOpt(Request $request)
    {
        try {
            $data = [
                'fldheadid' => $request->test_name,
                `fldsubtexam` => '',
                'fldanswertype' => $request->input_mode,
                'fldanswer' => $request->answer,
                'fldscale' => 0,
                'fldscalegroup' => null,
                'fldindex' => null
            ];
            $insert = SubExamQuali::insertGetId($data);
            if ($insert) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Added Test Option.'
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Add Test Option.',
                ]);
            }
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
            $delete = SubExamQuali::where('fldid', $request->id)->delete();
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

    public function getQualitativeTestOpt()
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');
        $get_related_data = ExamOption::where([
            'fldexamid' => $test,
            'fldanswertype' => $mode
        ])->select('fldid', 'fldanswer', 'fldindex')
            ->orderBy('fldindex', 'ASC')
            ->get();
        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function insertQualitativeTestOpt(Request $request)
    {
        try {
            $data = [
                'fldexamid' => $request->test_name,
                'fldanswertype' => $request->input_mode,
                'fldanswer' => $request->answer,
                'fldscale' => 0,
                'fldscalegroup' => null,
                'fldindex' => null
            ];
            $insert = SubExamQuali::insert($data);
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
            $delete = ExamOption::where('fldid', $request->id)->delete();
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
            $data = [
                'fldexamid' => $request->test_name,
                'fldsubexam' => $request->subtest,
                'fldreference' => $request->reference,
                'fldtanswertype' => $request->input_mode,
                'flddetail' => null
            ];
            $insert = ExamQuali::insertGetId($data);
            $inserteddetail = ExamQuali::where('fldid', $insert)->first();
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

            $data = [
                'fldexamid' => $request->test_name,
                'fldsubexam' => $request->sub_test,
                'fldanswertype' => $request->input_mode,
                'fldanswer' => $request->answer,
                'fldscale' => $request->scale,
                'fldscalegroup' => $request->scalegrp,
                'fldindex' => 1,
            ];
            $insert = SubExamQuali::insertGetId($data);
            $inserteddetail = SubExamQuali::where('fldid', $insert)->first();
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

    public function getQualitativeClinicialScale()
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');

        $get_related_data = ExamOption::where([
            'fldexamid' => $test,
            'fldanswertype' => $mode
        ])->select('fldid', 'fldanswer', 'fldscale', 'fldscalegroup', 'fldindex')
            ->orderBy('fldindex', 'ASC')
            ->get();

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getDistinctGroup()
    {
        $test = Input::get('test_name');
        $mode = Input::get('input_mode');

        $group = ExamOption::where([
            'fldexamid' => $test,
            'fldanswertype' => $mode
        ])->select('fldscalegroup')->distinct()->get();

        if ($group != null) {
            return response()->json($group);
        }
    }

    public function insertQualitativeClinicalScale(Request $request)
    {
        try {
            $data = [
                'fldexamid' => $request->test_name,
                'fldanswertype' => $request->input_mode,
                'fldanswer' => $request->parameter,
                'fldscale' => $request->value,
                'fldscalegroup' => $request->scale_group,
                'fldindex' => null
            ];
            $insert = ExamOption::insert($data);
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

    public function insertQuantitativeTestPara(Request $request)
    {
        try {
            $data = [
                'fldexamid' => $request->test_name,
                'fldmethod' => $request->method,
                'fldminimum' => $request->valid_range,
                'fldmaximum' => $request->matric_unit,
                'fldsensitivity' => $request->sensitivity,
                'fldspecificity' => $request->specificity,
                'fldagegroup' => $request->age_group,
                'fldptsex' => $request->gender,
//                'fldconvfactor' => $request->factor,
//                'fldsilow' => $request->lower_mu,
//                'fldsihigh' => $request->upper_mu,
//                'fldsinormal' => $request->normal_mu,
//                'fldsiunit' => $request->unit_mu,
                'fldlow' => $request->lower_si,
                'fldhigh' => $request->upper_si,
                'fldnormal' => $request->normal_si,
                'fldunit' => $request->unit_si,
            ];
            $insert = Examlimit::insert($data);
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
            $data = [
                'fldmethod' => $request->method,
                'fldminimum' => $request->valid_range,
                'fldmaximum' => $request->matric_unit,
                'fldsensitivity' => $request->sensitivity,
                'fldspecificity' => $request->specificity,
                'fldagegroup' => $request->age_group,
                'fldptsex' => $request->gender,
//                'fldconvfactor' => $request->factor,
//                'fldsilow' => $request->lower_mu,
//                'fldsihigh' => $request->upper_mu,
//                'fldsinormal' => $request->normal_mu,
//                'fldsiunit' => $request->unit_mu,
                'fldlow' => $request->lower_si,
                'fldhigh' => $request->upper_si,
                'fldnormal' => $request->normal_si,
                'fldunit' => $request->unit_si,
            ];
            $update = Examlimit::where('fldid', $request->fldid)->update($data);
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
            $delete = Examlimit::where('fldid', $request->fldid)->delete();
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

    public function getQuantitativeTestParaMu()
    {
        $test_name = Input::get('test_name');
        $get_related_data = Examlimit::where('fldexamid', $test_name)
            ->select('fldid', 'fldptsex', 'fldagegroup', 'fldmetnormal', 'fldmetlow', 'fldhigh', 'fldunit', 'fldhod', 'fldsensitivity', 'fldspecificity')
            ->get();

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getQuantitativeTestParaSi()
    {
        $test_name = Input::get('test_name');
        $get_related_data = Examlimit::where('fldexamid', $test_name)
            ->select('fldid', 'fldptsex', 'fldagegroup', 'fldnormal', 'fldlow', 'fldhigh', 'fldunit', 'fldmethod', 'fldsensitivity', 'fldspecificity')
            ->get();

        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function getQuantitativeTestPara()
    {
        $fldid = Input::get('fldid');
        $get_related_data = Examlimit::where('fldid', $fldid)
            ->select('fldid', 'fldmethod', 'fldminimum', 'fldmaximum', 'fldsensitivity', 'fldspecificity', 'fldagegroup', 'fldptsex', 'fldlow', 'fldhigh', 'fldnormal', 'fldunit')
            ->first();
        if ($get_related_data != null) {
            return response()->json($get_related_data);
        }
    }

    public function deleteTestQuali(Request $request)
    {
        try {
            $delete = ExamQuali::where('fldid', $request->fldid)->delete();
            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Delete Parameter.',
                ]);
            }
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
            $delete = SubExamQuali::where('fldid', $request->fldid)->delete();
            if ($delete) {
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Deleted Test Parameter.',
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to Delete Parameter.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function oldTestQuali(Request $request)
    {
        if ($testData = ExamQuali::where('fldexamid', $request->fldexamid)->get()) {
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Deleted Test Parameter.',
                'data' => $testData
            ]);
        } else {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Get Parameter.',
            ]);
        }
    }

    public function oldSubTestQuali(Request $request)
    {
        if ($testData = SubExamQuali::where('fldexamid', $request->fldexamid)->where('fldsubtest', $request->fldsubtest)->get()) {
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Deleted Test Parameter.',
                'data' => $testData
            ]);
        } else {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to Get Parameter.',
            ]);
        }
    }

}
