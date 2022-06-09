<?php

namespace Modules\ConsultActivity\Http\Controllers;

use App\Encounter;
use App\PatientInfo;
use App\Districts;
use App\Department;
use App\BillingSet;
use App\Consult;
use App\DeptConsult;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayUserPostingForm()
    {
        $data['department'] = Helpers::getDepartmentByCategory('Consultation');
        $data['mode'] = BillingSet::all();
        $data['result'] = DeptConsult::select('fldid','fldmethod','fldselect','flddate','fldbillingmode','flddept','flduserid','fldquota','fldreason')->where('fldcomp','LIKE',Helpers::getCompName())->get();
        $html = view('consultactivity::menu-dynamic-views.user-posting', $data)->render();
        return $html;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addUserPostingForm(Request $request)
    {

       $data['fldcomp'] = $request->comp;
       $data['fldselect'] = $request->dateType;
       if($request->dateType == 'Specific'){
        $date = $request->date;
       }else{
        $date = NULL;
       }
       $data['fldmethod'] = $request->method;
       if($request->method == 'ConsultOnly'){
            $data['flddept'] = NULL;
            $data['fldbillingmode'] = NULL;
       }elseif($request->method == 'Consultation+Mode'){
            $data['flddept'] = NULL;
            $data['fldbillingmode'] = $request->mode;
       }else{
            $data['flddept'] = $request->department;
            $data['fldbillingmode'] = $request->mode;
       }

       $data['flduserid'] = $request->username;
       $data['fldreason'] = $request->comment;
       $data['fldquota'] = $request->allocation;
       $data['flddate'] = $date;
       $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
       DeptConsult::insert($data);

       $html = '';
       $result = DeptConsult::select('fldid','fldmethod','fldselect','flddate','fldbillingmode','flddept','flduserid','fldquota','fldreason')->where('fldcomp','LIKE',$data['fldcomp'])->where('fldselect','LIKE',$data['fldselect'])->where('fldmethod','LIKE',$data['fldmethod'])->where('fldbillingmode','LIKE',$data['fldbillingmode'])->get();
       if(isset($result) and count($result)){
        foreach($result as $data){
            $html .='<tr>';
            $html .='<td><input type="checkbox" value="'.$data->fldid.'" class="user_fldid"></td>';
            $html .='<td>'.$data->fldmethod.'</td>';
            $html .='<td>'.$data->fldselect.'</td>';
            $html .='<td>'.$data->flddate.'</td>';
            $html .='<td>'.$data->fldbillingmode.'</td>';
            $html .='<td>'.$data->flddept.'</td>';
            $html .='<td>'.$data->flduserid.'</td>';
            $html .='<td>'.$data->fldquota.'</td>';
            $html .='<td>'.$data->fldreason.'</td>';
            $html .='</tr>';
        }
       }
       echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function updateUserPostingForm(Request $request)
    {
        $data['fldid'] = $request->fldid;
       // echo $data['fldid']; exit;
       $data['fldcomp'] = $request->comp;
       $data['fldselect'] = $request->dateType;
       if($request->dateType == 'Specific'){
        $date = $request->date;
       }else{
        $date = NULL;
       }
       $data['fldmethod'] = $request->method;
       if($request->method == 'ConsultOnly'){
            $data['flddept'] = NULL;
            $data['fldbillingmode'] = NULL;
       }elseif($request->method == 'Consultation+Mode'){
            $data['flddept'] = NULL;
            $data['fldbillingmode'] = $request->mode;
       }else{
            $data['flddept'] = $request->department;
            $data['fldbillingmode'] = $request->mode;
       }

       $data['flduserid'] = $request->username;
       $data['fldreason'] = $request->comment;
       $data['fldquota'] = $request->allocation;
       $data['flddate'] = $date;

       // dd($data);
      DeptConsult::where([['fldid', $request->fldid]])->update($data);

       $html = '';
       $result = DeptConsult::select('fldid','fldmethod','fldselect','flddate','fldbillingmode','flddept','flduserid','fldquota','fldreason')->where('fldcomp','LIKE',$data['fldcomp'])->where('fldselect','LIKE',$data['fldselect'])->where('fldmethod','LIKE',$data['fldmethod'])->where('fldbillingmode','LIKE',$data['fldbillingmode'])->get();
       if(isset($result) and count($result)){
        foreach($result as $data){
            $html .='<tr>';
            $html .='<td><input type="checkbox" value="'.$data->fldid.'" class="user_fldid"></td>';
            $html .='<td>'.$data->fldmethod.'</td>';
            $html .='<td>'.$data->fldselect.'</td>';
            $html .='<td>'.$data->flddate.'</td>';
            $html .='<td>'.$data->fldbillingmode.'</td>';
            $html .='<td>'.$data->flddept.'</td>';
            $html .='<td>'.$data->flduserid.'</td>';
            $html .='<td>'.$data->fldquota.'</td>';
            $html .='<td>'.$data->fldreason.'</td>';
            $html .='</tr>';
        }
       }
       echo $html; exit;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listData(Request $request)
    {
       $type = $request->type;
       $mode = $request->mode;
       $comp = $request->depart;
       $html = '';
       if($type == 'mode'){

            $result = DeptConsult::select('fldid','fldmethod','fldselect','flddate','fldbillingmode','flddept','flduserid','fldquota','fldreason')->where('fldbillingmode','LIKE',$mode)->get();
       }else{
            $result = DeptConsult::select('fldid','fldmethod','fldselect','flddate','fldbillingmode','flddept','flduserid','fldquota','fldreason')->where('fldcomp','LIKE',$comp)->get();
       }

       if(isset($result) and count($result) > 0){
        foreach($result as $data){
            $html .='<tr>';
            $html .='<td><input type="checkbox" value="'.$data->fldid.'" class="user_fldid"></td>';
            $html .='<td>'.$data->fldmethod.'</td>';
            $html .='<td>'.$data->fldselect.'</td>';
            $html .='<td>'.$data->flddate.'</td>';
            $html .='<td>'.$data->fldbillingmode.'</td>';
            $html .='<td>'.$data->flddept.'</td>';
            $html .='<td>'.$data->flduserid.'</td>';
            $html .='<td>'.$data->fldquota.'</td>';
            $html .='<td>'.$data->fldreason.'</td>';
            $html .='</tr>';
        }
       }
       echo $html; exit;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exporttoPdf(Request $request)
    {

        $result = DeptConsult::all();
        $data['result'] = $result;
        // dd($data);
        return view('consultactivity::pdf.report', $data)/*->setPaper('a4')->stream('consultation_plan_report.pdf')*/;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function showAll(Request $request)
    {
       $html = '';
       $result = DeptConsult::all();
       $data['result'] = $result;
       if(isset($result) and count($result) > 0){
        foreach($result as $data){
            $html .='<tr>';
            $html .='<td><input type="checkbox" value="'.$data->fldid.'" class="user_fldid"></td>';
            $html .='<td>'.$data->fldmethod.'</td>';
            $html .='<td>'.$data->fldselect.'</td>';
            $html .='<td>'.$data->flddate.'</td>';
            $html .='<td>'.$data->fldbillingmode.'</td>';
            $html .='<td>'.$data->flddept.'</td>';
            $html .='<td>'.$data->flduserid.'</td>';
            $html .='<td>'.$data->fldquota.'</td>';
            $html .='<td>'.$data->fldreason.'</td>';
            $html .='</tr>';
        }
       }
       echo $html; exit;
    }
}
