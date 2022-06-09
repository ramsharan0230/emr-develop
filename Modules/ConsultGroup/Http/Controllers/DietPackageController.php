<?php

namespace Modules\ConsultGroup\Http\Controllers;

use App\FoodType;
use App\DietGroup;
use App\FoodContent;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DietPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayForm()
    {
        // echo "diet Package"; exit;
        $data['components'] = FoodType::select('fldfoodtype')->distinct()->get();
        $data['groups'] = DietGroup::select('fldgroup')->distinct()->get();
        
        // dd($data['groupprocs']);
        $html = view('consultgroup::dynamic-views.diet-package', $data)->render();
        return $html; 

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addDietPackage(Request $request)
    {
        // dd($request->all());
        $data['fldgroup'] = $request->group;
        $data['flditemtype'] = $request->components;
        $data['flditemname'] = $request->item_type;
        $data['flditemqty'] = $request->quantity;
        DietGroup::insert($data);


        $dghtml ='';
        $result = DietGroup::where('fldgroup', $request->group)->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $dghtml.='<tr>';
               
                $dghtml .='<td>'.$r->flditemtype.'</td>';
                $dghtml .='<td>'.$r->flditemname.'</td>';
                $dghtml .='<td>'.$r->flditemqty.'</td>';
                $dghtml .='<td><a href="javascript:void(0)" onclick="deletedietgroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $dghtml .='</tr>';
            }
        }
        echo $dghtml; exit;
    }

/**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayDietItemType(Request $request)
    {
        // dd($request->all());
        
        $result = FoodContent::select('fldfoodid')->where('fldfoodtype', 'LIKE',$request->component)->where('fldfoodcode', 'LIKE', 'Active')->get();
        $html ='';
        
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html .='<option value="'.$r->fldfoodid.'">'.$r->fldfoodid.'</option>';
            }
        }
        echo $html; exit;
    }
    

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteDietGroupPackage(Request $request)
    {
        DietGroup::where('fldid', $request->id)->delete();

        $html ='';
        if($request->group !=''){
            $result = DietGroup::where('fldgroup',$request->group)->get();
        }
        
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
               
                $html .='<td>'.$r->flditemtype.'</td>';
                $html .='<td>'.$r->flditemname.'</td>';
                $html .='<td>'.$r->flditemqty.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deletedietgroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .='</tr>';
            }
        }
        echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listDietByGroup(Request $request)
    {
        $group = $request->group;


        $html ='';
        $result = DietGroup::where('fldgroup',$group)->get();
        if(isset($result) and count($result) > 0){
            foreach($result as $r){
                $html.='<tr>';
               
                $html .='<td>'.$r->flditemtype.'</td>';
                $html .='<td>'.$r->flditemname.'</td>';
                $html .='<td>'.$r->flditemqty.'</td>';
                $html .='<td><a href="javascript:void(0)" onclick="deletedietgroup('.$r->fldid.')"><i class="fa fa-trash text-danger"></i></a></td>';
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
