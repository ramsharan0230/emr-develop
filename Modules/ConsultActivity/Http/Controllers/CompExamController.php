<?php

namespace Modules\ConsultActivity\Http\Controllers;

use App\Complaints;
use App\CompExam;
use App\Exam;
use App\Symptoms;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CompExamController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayCompExamForm()
    {
        $data['department'] = Helpers::getDepartmentByCategory('Consultation');

        $html = view('consultactivity::menu-dynamic-views.comp-exam', $data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listAllComplaints(Request $request)
    {
      $comp = $request->comp;
       $html = '';
       $result = Complaints::when($comp != "%", function ($q) use ($comp){
            return $q->where('fldcomp',$comp);
        })->get();
       if(isset($result) and count($result)){
        foreach($result as $k=>$data){
          $sn = $k+1;
            $html .='<tr>';
            $html .='<td>'.$sn.'</td>';
            $html .='<td>'.$data->fldtype.'</td>';
            $html .='<td>'.$data->fldsymptom.'</td>';
            $html .='<td><a href="javascript:void(0);" class="" onclick="deleteCompExam('.$data->fldid.')"><i class=" fa fa-trash"></i></a></td>';
            $html .='</tr>';
        }
       }
       echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayComplaintList(Request $request)
    {
        $data['category'] = Symptoms::select('fldcategory')->distinct()->get();
        $data['complaints'] = Complaints::when($request->comp != "%", function ($q) use ($request){
            return $q->where('fldcomp',$request->comp);
        })->get();
        $html = view('consultactivity::menu-dynamic-views.symptoms-list', $data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listSymptomsByCat(Request $request)
    {
        $html = '';
        $category = $request->category;
        $comp = $request->target;
        $complaints = Complaints::select('fldsymptom')->when($comp != "%", function ($q) use ($comp){
            return $q->where('fldcomp',$comp);
        })->get();

        $existingsym =  array();
        foreach($complaints as $c){
            $existingsym[] = $c->fldsymptom;
        }
        // dd($existingsym);
        $result = Symptoms::select('fldsymptom')->where('fldcategory','LIKE',$category)->whereNotIn('fldsymptom', $existingsym)->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html .='<tr>';
                $html .='<td><input type="checkbox" value="'.$r->fldsymptom.'" class="symptom_value" name="symptom_value"></td>';
                $html .='<td>'.$r->fldsymptom.'</td>';
                $html .='<td><a href="javascript:void(0);" class="" onclick="deleteCompExam('.$r->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addSymptoms(Request $request)
    {
        $html = '';
        $mhtml = '';
        $category = $request->cat;
        $comp = $request->comp;
        $string_symptoms = $request->symptoms;
        $symptoms = explode(',', $string_symptoms);

        if(isset($symptoms) and count($symptoms) > 0){
            foreach($symptoms as $sym){
                $data['fldsymptom'] = $sym;
                $data['fldtype'] = $category;
                $data['fldcomp'] = $comp;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                Complaints::insert($data);
                $html .='<tr>';
                $html .='<td>'.$sym.'</td>';
                $html .='</tr>';

            }
        }

        $result = Complaints::when($comp != "%", function ($q) use ($comp){
            return $q->where('fldcomp',$comp);
        })->get();
           if(isset($result) and count($result)){
            foreach($result as $k=>$data){
              $sn = $k+1;
                $mhtml .='<tr>';
                $mhtml .='<td>'.$sn.'</td>';
                $mhtml .='<td>'.$data->fldtype.'</td>';
                $mhtml .='<td>'.$data->fldsymptom.'</td>';
                $mhtml .='<td><a href="javascript:void(0);" class="" onclick="deleteCompExam('.$data->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $mhtml .='</tr>';
            }
           }
        $data['mhtml'] = $mhtml;
        $data['html'] = $html;
        return $data;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listExaminationByCategory(Request $request)
    {
      $comp = $request->comp;
      $category = $request->category;
       $html = '';
       $result = CompExam::select('fldexamid','fldtype','fldid')->when($comp != "%", function ($q) use ($comp){
            return $q->where('fldcomp',$comp);
        })->where('fldcategory',$category)->get();
       // echo $result; exit;
       // dd($result);
       if(isset($result) and count($result) > 0){
        foreach($result as $k=>$data){

          $sn = $k+1;
            $html .='<tr>';
            $html .='<td>'.$sn.'</td>';
            $html .='<td>'.$data->fldtype.'</td>';
            $html .='<td>'.$data->fldexamid.'</td>';
            $html .='<td><a href="javascript:void(0);" class="" onclick="deleteExam('.$data->fldid.')"><i class=" fa fa-trash"></i></a></td>';
            $html .='</tr>';
        }
       }else{
        $html .='<tr class="empty_exam">';
        $html .='<td  colspan="9" align="center">No Examination Available</td>';
        $html .='</tr>';
       }
       echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayExaminationAddForm(Request $request)
    {

        // $data['category'] = Symptoms::select('fldcategory')->distinct()->get();
        $data['examinations'] = CompExam::select('fldexamid')->when($request->comp != "%", function ($q) use ($request){
            return $q->where('fldcomp',$request->comp);
        })->where('fldcategory',$request->cat)->get();
        $html = view('consultactivity::menu-dynamic-views.examination-add', $data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listExaminationByType(Request $request)
    {
      $type = $request->type;

       $html = '';
       $existingexams = CompExam::select('fldexamid')->when($request->comp != "%", function ($q) use ($request){
            return $q->where('fldcomp',$request->comp);
        })->where('fldcategory',$request->category)->get();
       // dd($existingexams);
       $result = Exam::select('fldexamid')->where('fldtype',$type)->whereNotIn('fldexamid',$existingexams)->get();
       // dd($result);
       // echo $result; exit;
       if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html .='<tr>';
                $html .='<td><input type="checkbox" value="'.$r->fldexamid.'" class="exam_value" name="exam_value"></td>';
                $html .='<td>'.$r->fldexamid.'</td>';
                $html .='</tr>';
            }
        }else{
                $html .='<tr class="empty_exam_type">';
                $html .='<td  colspan="3">No Examination Available</td>';
                $html .='</tr>';
       }
       echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addExamination(Request $request)
    {
        $html = '';
        $mainhtml = '';
        $category = $request->category;
        $comp = $request->comp;
        $count = $request->count;
        $string_exams = $request->exams;
        $exams = explode(',', $string_exams);

        $view = array();
        if(isset($exams) and count($exams) > 0){
            foreach($exams as $l=>$ex){
                $extraData = Exam::select('fldsysconst','fldtype')->where('fldexamid',$ex)->first();
                $data['fldexamid'] = $ex;
                $data['fldcategory'] = $category;
                $data['fldcomp'] = $comp;
                $data['fldsysconst'] = $extraData->fldsysconst;
                $data['fldtype'] = $extraData->fldtype;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                CompExam::insert($data);
                $compdata = CompExam::where('fldexamid',$ex)->where('fldcategory',$category)->first();
                $html .='<tr>';
                $html .='<td>'.$ex.'</td>';
                $html .='</tr>';

                $sn = $count+1;
                $mainhtml .='<tr>';
                $mainhtml .='<td>'.$sn.'</td>';
                $mainhtml .='<td>'.$extraData->fldtype.'</td>';
                $mainhtml .='<td>'.$ex.'</td>';
                $mainhtml .='<td><a href="javascript:void(0);" class="" onclick="deleteExam('.$compdata->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                $mainhtml .='</tr>';

            }
        }
        $view['html'] = $html;
        $view['mainhtml'] = $mainhtml;
        return $view;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteComplaint(Request $request)
    {
        $id = $request->val;
        try{
            Complaints::where('fldid', $id)->delete();
            $html = '';
            $result = Complaints::when($request->comp != "%", function ($q) use ($request){
                return $q->where('fldcomp',$request->comp);
            })->get();
               if(isset($result) and count($result)){
                foreach($result as $k=>$data){
                  $sn = $k+1;
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$data->fldtype.'</td>';
                    $html .='<td>'.$data->fldsymptom.'</td>';
                    $html .='<td><a href="javascript:void(0);" class="" onclick="deleteCompExam('.$data->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                    $html .='</tr>';
                }
               }
           echo $html; exit;
        } catch (\Exception $exception) {
           dd($e);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteExam(Request $request)
    {
        $id = $request->val;
        try{
            CompExam::where('fldid', $id)->delete();
            $html = '';
            $result = CompExam::when($request->comp != "%", function ($q) use ($request){
                return $q->where('fldcomp',$request->comp);
            })->where('fldcategory', $request->cat)->get();
               if(isset($result) and count($result)){
                foreach($result as $k=>$data){
                    $sn = $k+1;
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$data->fldtype.'</td>';
                    $html .='<td>'.$data->fldexamid.'</td>';
                    $html .='<td><a href="javascript:void(0);" class="" onclick="deleteExam('.$data->fldid.')"><i class=" fa fa-trash"></i></a></td>';
                    $html .='</tr>';
                }
               }
           echo $html; exit;
        } catch (\Exception $exception) {
           dd($e);
        }
    }
}
