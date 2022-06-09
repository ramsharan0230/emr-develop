<?php

namespace Modules\ConsultGroup\Http\Controllers;

use App\Procname;
use App\GroupProc;
use App\ServiceCost;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProcGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayForm()
    {
        $data['procs'] = Procname::select('fldprocname')->get();
        $data['groups'] = ServiceCost::select('flditemname')->where('flditemtype', 'Procedures')->where('fldtarget','Extra')->orWhere('fldtarget','%')->where('fldstatus','Active')->get();
        $data['groupprocs'] = GroupProc::orderBy('fldgroupname', 'ASC')->get();
        
        // dd($data['groupprocs']);
        $html = view('consultgroup::dynamic-views.proc-group', $data)->render();
        return $html; 

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addProcGroup(Request $request)
    {
        $data['fldgroupname'] = $request->group;
        $data['fldprocname'] = $request->proc;
        GroupProc::insert($data);
        $html ='';
        $result = GroupProc::orderBy('fldgroupname', 'ASC')->where('fldgroupname',$data['fldgroupname'])->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
               
                $html .='<td>'.$r->fldgroupname.'</td>';
                $html .='<td>'.$r->fldprocname.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deleteprogroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteProcGroups(Request $request)
    {
        $fldids = $request->fldids;
        $fldarray = explode(',', $fldids);
        if(isset($fldarray) and count($fldarray) > 0){
            foreach($fldarray as $id){
                GroupProc::where('fldid', $id)->delete();
            }
        }

        $html ='';
        $result = GroupProc::orderBy('fldgroupname', 'ASC')->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
                
                $html .='<td>'.$r->fldgroupname.'</td>';
                $html .='<td>'.$r->fldprocname.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deleteprogroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteProcGroup(Request $request)
    {
        GroupProc::where('fldid', $request->id)->delete();

        $html ='';
        if($request->group !=''){
            $result = GroupProc::orderBy('fldgroupname', 'ASC')->where('fldgroupname',$request->group)->get();
        }else{
            $result = GroupProc::orderBy('fldgroupname', 'ASC')->get();
        }
        
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
                
                $html .='<td>'.$r->fldgroupname.'</td>';
                $html .='<td>'.$r->fldprocname.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deleteprogroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listByGroupName(Request $request)
    {
        $group = $request->group;


        $html ='';
        $result = GroupProc::orderBy('fldgroupname', 'ASC')->where('fldgroupname',$group)->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
                
                $html .='<td>'.$r->fldgroupname.'</td>';
                $html .='<td>'.$r->fldprocname.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deleteprogroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportGroupToPdf(Request $request)
    {
        // dd($request);
        $group = $request->group;
        $html ='';
        if($group !=''){

            $result = GroupProc::orderBy('fldgroupname', 'ASC')->where('fldgroupname',$group)->get();
        }else{

            $result = GroupProc::orderBy('fldgroupname', 'ASC')->get();
        }
        $data['result'] = $result;
        return view('consultgroup::pdf.proc-group', $data)/*->setPaper('a4')->stream('procedure_group_report.pdf')*/;
    }

}
