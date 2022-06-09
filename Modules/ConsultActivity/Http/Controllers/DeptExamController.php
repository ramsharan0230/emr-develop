<?php

namespace Modules\ConsultActivity\Http\Controllers;

use App\Encounter;
use App\PatientInfo;
use App\Procname;
use App\Districts;
use App\Department;
use App\BillingSet;
use App\Consult;
use App\DepartmentExam;
use App\DepartmentExamOption;
use App\Exam;
use App\DeptConsult;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DeptExamController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayDeptExamForm()
    {
        $data['department'] = Procname::all();

        $html = view('consultactivity::menu-dynamic-views.dept-exam', $data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayProcAddForm()
    {

        $data['procname'] = Procname::select('fldprocname')->get();
        $html = view('consultactivity::menu-dynamic-views.proc-add',$data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listSysConst(Request $request)
    {
        $type = $request->type;
        $html = '';
        $result = Exam::select('fldsysconst')->where('fldtype',$type)->distinct()->get();
        if(isset($result) && count($result) > 0){
            foreach($result as $data){
                $html .='<option value="'.$data->fldsysconst.'">'.$data->fldsysconst.'</option>';
            }
        }
        echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addProc(Request $request)
    {
        $variable = $request->variable;
        $ahtml = '';
        $optionhtml = '';
        $data['fldprocname'] = $variable;
        Procname::insert($data);
        $result = Procname::select('fldprocname')->get();
        if(isset($result) and count($result) >  0){
            foreach($result as $value){
                $ahtml .='<tr>';
                $ahtml .='<td><input type="checkbox" value="'.$value->fldprocname.'" class="procname" name="procname">'. $value->fldprocname.'</td>';

                $ahtml .="</tr>";

                $optionhtml .= '<option value="'.$value->fldprocname.'">'.$value->fldprocname.'</option>';
            }
        }
        $view['ahtml'] = $ahtml;
        $view['optionhtml'] = $optionhtml;
       return $view;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteProc(Request $request)
    {
        $html = '';
        $mainhtml = '';

        try{
            $variable = $request->procs;

            $variables = explode(',', $variable);

            if(isset($variables) and count($variables) > 0){
                foreach ($variables as $key => $value) {
                    Procname::where('fldprocname',$value)->delete();

                 }
            }
            $result = Procname::select('fldprocname')->get();

            if(isset($result) and count($result) > 0){
                foreach($result as $pro){
                    $html .='<tr>';

                    $html .='<td><input type="checkbox" name="procname" class="procname" value="'. $pro->fldprocname.'">'.$pro->fldprocname.'</td>';

                    $html .='</tr>';

                    $mainhtml .='<option value"'.$pro->fldprocname.'">'.$pro->fldprocname.'</option>';
                }
            }else{
                $html .='<tr>';
                $html .='<td>No data Available</td>';
                $html .='</tr>';

                $mainhtml .='';
            }
            $data['html'] = $html;
            $data['mainhtml'] = $mainhtml;
            return $data;
        }catch (\Exception $e) {
            dd($e);
            /*return response()->json(['status' => 'error', 'data' => []]);*/
            return [];
        }

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addDeptExam(Request $request)
    {
        // INSERT INTO `tbldeptexam` ( `fldexamid`, `fldsysconst`, `fldtype`, `fldtanswertype`, `flddept` ) VALUES ( 'testint examination', 'APGAR_SCALE', 'Qualitative', 'Dichotomous', 'Pre Delivery' )
        $data['flddept'] = $request->dept;
        $data['fldtype'] = $request->data_type;
        $data['fldsysconst'] = $request->syscon;
        $data['fldtanswertype'] = $request->option;
        $data['fldexamid'] = $request->label;
        // dd($data); 
        $html = '';


        DepartmentExam::insert($data);
        $result = DepartmentExam::where('flddept', $data['flddept'])->get();
        // dd($result);
        if(isset($result) and count($result) >  0){
            foreach($result as $value){
                $html .='<tr>';
                $html .='<td><input type="checkbox" value="'.$value->fldid.'" class="procname_variable" name="procname_variable"></td>';
                $html .='<td>'.$value->fldtype.'</td>';
                $html .='<td>'.$value->fldexamid.'</td>';
                $html .='<td>'.$value->fldsysconst.'</td>';
                $html .='<td>'.$value->fldtanswertype.'</td>';
                $html .='<td><a href="javascript:void(0);" class="" onclick="deleteDeptExam('.$value->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $html .="</tr>";

            }
        }
        echo $html; exit;
    }

/**
     * Display a listing of the resource.
     * @return Response
     */
    public function editDeptExam(Request $request)
    {
        // INSERT INTO `tbldeptexam` ( `fldexamid`, `fldsysconst`, `fldtype`, `fldtanswertype`, `flddept` ) VALUES ( 'testint examination', 'APGAR_SCALE', 'Qualitative', 'Dichotomous', 'Pre Delivery' )
        $data['flddept'] = $request->dept;
        $data['fldtype'] = $request->data_type;
        $data['fldsysconst'] = $request->syscon;
        $data['fldtanswertype'] = $request->option;
        $data['fldexamid'] = $request->label;
        $html = '';

        // dd($request->all());
        DepartmentExam::where('fldid', $request->fldid)->update($data);
        $result = DepartmentExam::where('flddept',$data['flddept'])->get();
        if(isset($result) and count($result) >  0){
            foreach($result as $value){
                $html .='<tr>';
                $html .='<td><input type="checkbox" value="'.$value->fldid.'" class="procname_variable" name="procname_variable"></td>';
                $html .='<td>'.$value->fldtype.'</td>';
                $html .='<td>'.$value->fldexamid.'</td>';
                $html .='<td>'.$value->fldsysconst.'</td>';
                $html .='<td>'.$value->fldtanswertype.'</td>';
                $html .='<td><a href="javascript:void(0);" class="" onclick="deleteDeptExam('.$value->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $html .="</tr>";

            }
        }
        echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listDeptExam(Request $request)
    {
        $html = '';
        $result = DepartmentExam::where('flddept', $request->dept)->get();
        if(isset($result) and count($result) >  0){
            foreach($result as $value){
                $html .='<tr>';
                $html .='<td><input type="checkbox" value="'.$value->fldid.'" class="procname_variable" name="procname_variable"></td>';
                $html .='<td>'.$value->fldtype.'</td>';
                $html .='<td>'.$value->fldexamid.'</td>';
                $html .='<td>'.$value->fldsysconst.'</td>';
                $html .='<td>'.$value->fldtanswertype.'</td>';
                $html .='<td><a href="javascript:void(0);" class="" onclick="deleteDeptExam('.$value->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $html .="</tr>";

            }
        }
        echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportDeptExamToPdf(Request $request)
    {
        $result = DepartmentExam::where('flddept', $request->get('data'))->get();
        $data['result'] = $result;
        $data['department'] = $request->dept;
        return view('consultactivity::pdf.dept-exam', $data)/*->setPaper('a4')->stream('department_exam_report.pdf')*/;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayAddOption(Request $request)
    {
        $data['department'] = $request->dept;
        $data['exam_label'] = $request->label;
        $data['option'] = $request->option;
        $data['result'] = DepartmentExamOption::select('fldid','fldanswer')->where('flddept',$request->dept)->where('fldexamid',$request->label)->where('fldtanswertype',$request->option)->get();
        $html = view('consultactivity::menu-dynamic-views.dept-add-option', $data)->render();
        return $html;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addOptions(Request $request)
    {
        // INSERT INTO `tbldeptexamoption` ( `fldexamid`, `flddept`, `fldanswer`, `fldtanswertype`, `fldindex` ) VALUES ( 'sdfsdf', 'Pre Delivery', 'sdfsdfdsfd', 'Text Table', 1 )
        $html ='';
        $data['flddept'] = $request->sub_test;
        $data['fldexamid'] = $request->test;
        $data['fldanswer'] = $request->option;
        $data['fldtanswertype'] = $request->option_type;
        $data['fldindex'] = 1;
        DepartmentExamOption::insert($data);

        $result = DepartmentExamOption::select('fldid','fldanswer')->where('flddept',$request->sub_test)->where('fldexamid',$request->test)->where('fldtanswertype',$request->option_type)->get();
        // dd($result);
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html .='<tr>';
                $html .='<td><input type="checkbox" class="dept_exam_option" name="dept_exam_option" value="'.$r->fldid.'">'.$r->fldanswer.'</td>';
                $html .='</tr>';
            }
        }

        echo $html; exit;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteOption(Request $request)
    {
        $ids = $request->fldid;
        // dd($ids);
        // echo $ids; exit;
        $idarray = explode(',', $ids);
        foreach($idarray as $id){
             DepartmentExamOption::where('fldid', $id)->delete();
        }

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteDeptExam(Request $request)
    {
        $id = $request->val;
        try{
            DepartmentExam::where('fldid', $id)->delete();
            $html = '';

            $result = DepartmentExam::where('flddept',$request->dept)->get();
            if(isset($result) and count($result) >  0){
                foreach($result as $value){
                    $html .='<tr>';
                    $html .='<td><input type="checkbox" value="'.$value->fldid.'" class="procname_variable" name="procname_variable"></td>';
                    $html .='<td>'.$value->fldtype.'</td>';
                    $html .='<td>'.$value->fldexamid.'</td>';
                    $html .='<td>'.$value->fldsysconst.'</td>';
                    $html .='<td>'.$value->fldtanswertype.'</td>';
                    $html .='<td><a href="javascript:void(0);" class="" onclick="deleteDeptExam('.$value->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                    $html .="</tr>";

                }
            }
            echo $html; exit;
        } catch (\Exception $exception) {

        }
    }

    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function extractDeptExam(Request $request)
    {
        $html = '';
        $result = DepartmentExam::where('fldid', $request->id)->first();
        // echo $result->fldsysconst; exit;
        $constant = Exam::select('fldsysconst')->where('fldtype',$result->fldtype)->distinct()->get();
        
        if(isset($constant) && count($constant) > 0){

            foreach($constant as $con){
                if($con->fldsysconst == $result->fldsysconst){
                    $sel =  "selected='selected'";
                }else{
                    $sel = '';
                }
                $html .='<option value="'.$con->fldsysconst.'" '.$sel.'>'.$con->fldsysconst.'</option>';
            }
        }
        $data['result'] = $result;
        $data['html'] = $html;
        // return json_encode($result);
        return $data;
    }
}
