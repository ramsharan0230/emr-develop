<?php

namespace Modules\Department\Http\Controllers;

use App\Department;
use App\Departmentbed;
use App\BillingSet;
use App\ServiceCost;
use App\AutogroupDoctor;
use App\User;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AutobillingDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

    	$data['department'] = Department::all();
    	$data['billingset'] = BillingSet::get();
    	$data['test_type'] = ServiceCost::select('flditemtype')->distinct()->get();
        return view('department::autobillingdoctor',$data);
    }

    public function getItemname(Request $request){
    	$html ='';
    	$fldgroup = $request->testtype;
    	$result = ServiceCost::where('flditemtype',$fldgroup)->where('fldgroup',$request->mode)->orWhere('fldgroup','%')->get();
    	if(isset($result) and count($result) >0){
    		foreach($result as $data){
    			$html .='<option value="'.$data->flditemname.'">'.$data->flditemname.'</option>';
			}
    	}
    	echo $html; exit;
    }

    public function saveAutobilling(Request $request){
    	try{
    		$data['fldgroup'] = $request->groupname;
	    	$data['flditemtype'] = $request->testtype;
	    	$data['flditemname'] = $request->itemname;
	    	$data['flditemqty'] = $request->qty;
	    	$data['fldbillingmode'] = $request->mode;
	    	$data['fldexitemtype'] = $request->timing;
	    	$data['fldcutoff'] = $request->cutoff;
			$data['fldregtype'] = $request->reg_type;
			$data['doctor_id'] = $request->consultant_id;
			$data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
	    	AutogroupDoctor::insert($data);
	    	$html = '';
		    $html .='<table id="autobilling-doctor-table" data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                           data-show-toggle="true">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
                            <th>Doctor Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody >';
	    	$result = AutogroupDoctor::select('fldid', 'fldbillingmode', 'flditemname', 'flditemqty', 'fldexitemtype', 'fldcutoff', 'fldregtype','doctor_id')->where('fldgroup',$request->groupname)->get();
	    	if(isset($result) and count($result) > 0){
	    		foreach($result as $k=>$data){
	    			$sn = $k+1;
				    $doctor_name = \App\CogentUsers::where('id',$data->doctor_id)->first()->fullname;
	    			$html .='<tr>';
	    			$html .='<td> '.$sn.' </td>';
	    			$html .='<td>'.$data->fldbillingmode.'</td>';
	    			$html .='<td>'.$data->flditemname.'</td>';
	    			$html .='<td>'.$data->flditemqty.'</td>';
	    			$html .='<td>'.$data->fldexitemtype.'</td>';
	    			$html .='<td>'.$data->fldcutoff.'</td>';
	    			$html .='<td>'.$data->fldregtype.'</td>';
	    			$html .='<td>'.$doctor_name.'</td>';
	    			$html .='<td><a href="javascript:void(0);" class="iq-bg-danger" onclick="deleteautobillingitemdoctor('.$data->fldid.')"><i class="ri-delete-bin-5-fill"></i></a>
					<a href="javascript:void(0);" class="iq-bg-warning" onclick="editautobillingitemdoctor('.$data->fldid.')"><i class="ri-edit-bin-5-fill"></i></a></td>';
	    			$html .='</tr>';
	    		}
			    $html .=' </tbody></table>';
	    	}
	    	echo $html; exit;
    	}catch(\Exception $e){
    		dd($e);
    	}

    }

    public function listAllAutobilling(Request $request){
    	$department = $request->department;
    	// echo $department; exit;
    	try{
    		$html = '';
		    $html .='<table id="autobilling-doctor-table" data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                           data-show-toggle="true">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
                            <th>Doctor Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody >';
	    	$result = AutogroupDoctor::select('fldid', 'fldbillingmode', 'flditemname', 'flditemqty', 'fldexitemtype', 'fldcutoff', 'fldregtype','doctor_id')->where('fldgroup',$department)->get();
	    	if(isset($result) and count($result) > 0){
	    		foreach($result as $k=>$data){
				    $doctor_name = \App\CogentUsers::where('id',$data->doctor_id)->first()->fullname;
				    $sn = $k+1;
	    			$html .='<tr>';
	    			$html .='<td>'.$sn.'</td>';
	    			$html .='<td>'.$data->fldbillingmode.'</td>';
	    			$html .='<td>'.$data->flditemname.'</td>';
	    			$html .='<td>'.$data->flditemqty.'</td>';
	    			$html .='<td>'.$data->fldexitemtype.'</td>';
	    			$html .='<td>'.$data->fldcutoff.'</td>';
	    			$html .='<td>'.$data->fldregtype.'</td>';
	    			$html .='<td>'.$doctor_name.'</td>';
	    			$html .='<td><a href="javascript:void(0);" class="iq-bg-danger p-1" onclick="deleteautobillingitemdoctor('.$data->fldid.')"><i class="ri-delete-bin-5-fill"></i></a>&nbsp;&nbsp;
					<a href="javascript:void(0);" class="iq-bg-warning p-1" onclick="editautobillingitemdoctor('.$data->fldid.')"><i class="fa fa-edit"></i></a></td>';
	    			$html .='</tr>';
	    		}
			    $html .=' </tbody></table>';
	    	}
	    	echo $html; exit;
    	}catch(\Exception $e){
    		dd($e);
    	}
    }

    public function deleteAutobilling(Request $request){
    	try{
    		$html = '';
		    $html .='<table id="autobilling-doctor-table" data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                           data-show-toggle="true">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
                            <th>Doctor Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody >';
			AutogroupDoctor::where('fldid', $request->fldid)->delete();
			$result = AutogroupDoctor::select('fldid', 'fldbillingmode', 'flditemname', 'flditemqty', 'fldexitemtype', 'fldcutoff', 'fldregtype','doctor_id')->where('fldgroup',$request->department)->get();
	    	if(isset($result) and count($result) > 0){
	    		foreach($result as $k=>$data){
				    $doctor_name = \App\CogentUsers::where('id',$data->doctor_id)->first()->fullname;
	    			$sn = $k+1;
	    			$html .='<tr>';
	    			$html .='<td>'.$sn.'</td>';
	    			$html .='<td>'.$data->fldbillingmode.'</td>';
	    			$html .='<td>'.$data->flditemname.'</td>';
	    			$html .='<td>'.$data->flditemqty.'</td>';
	    			$html .='<td>'.$data->fldexitemtype.'</td>';
	    			$html .='<td>'.$data->fldcutoff.'</td>';
	    			$html .='<td>'.$data->fldregtype.'</td>';
	    			$html .='<td>'.$doctor_name.'</td>';
	    			$html .='<td><a href="javascript:void(0);" class="iq-bg-danger" onclick="deleteautobillingitemdoctor('.$data->fldid.')"><i class="ri-delete-bin-5-fill"></i></a>&nbsp;&nbsp;
					<a href="javascript:void(0);" class="iq-bg-warning p-1" onclick="editautobillingitemdoctor('.$data->fldid.')"><i class="fa fa-edit"></i></a></td>';
	    			$html .='</tr>';
	    		}
			    $html .=' </tbody></table>';
	    	}
	    	echo $html; exit;
    	}catch(\Exception $e){
    		dd($e);
    	}
    }

    public function editAutobilling(Request $request){
        try{
            $autogroup = AutogroupDoctor::where('fldid',$request->fldid)->first();
            $itemlists ='';
            $result = ServiceCost::where('flditemtype',$autogroup->flditemtype)->where('fldgroup',$autogroup->fldbillingmode)->orWhere('fldgroup','%')->get();
            if(isset($result) and count($result) >0){
                foreach($result as $data){
                    $itemlists .='<option value="'.$data->flditemname.'">'.$data->flditemname.'</option>';
                }
            }

            $doctorLists ='';
            $result = ServiceCost::where('flditemtype',$autogroup->flditemtype)->where('fldgroup',$autogroup->fldbillingmode)->orWhere('fldgroup','%')->get();
            if(isset($result) and count($result) >0){
                foreach($result as $data){
                    $doctorLists .='<option value="'.$data->flditemname.'">'.$data->flditemname.'</option>';
                }
            }
            return response()->json([
                'status' => true,
                'autogroup' => $autogroup,
                'itemlists' => $itemlists
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function updateAutobilling(Request $request){
    	try{
    		$html = '';
		    $html .='<table id="autobilling-doctor-table" data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                           data-show-toggle="true">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
                            <th>Doctor Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody >';
    		$data['fldgroup'] = $request->department;
	    	$data['flditemtype'] = $request->testtype;
	    	$data['flditemname'] = $request->itemname;
	    	$data['flditemqty'] = $request->qty;
	    	$data['fldbillingmode'] = $request->mode;
	    	$data['fldexitemtype'] = $request->timing;
	    	$data['fldcutoff'] = $request->cutoff;
	    	$data['fldregtype'] = $request->reg_type;
	    	AutogroupDoctor::where([['fldid', $request->fldid]])->update($data);
	    	$result = AutogroupDoctor::select('fldid', 'fldbillingmode', 'flditemname', 'flditemqty', 'fldexitemtype', 'fldcutoff', 'fldregtype','doctor_id')->where('fldgroup',$request->department)->get();
	    	if(isset($result) and count($result) > 0){
	    		foreach($result as $k=>$data){
				    $doctor_name = \App\CogentUsers::where('id',$data->doctor_id)->first()->fullname;
	    			$sn = $k+1;
	    			$html .='<tr>';
	    			$html .='<td>'.$sn.'</td>';
                    // $html .='<td><input type="checkbox" class="autobilling" value="'.$data->fldid.'">'.$sn.'</td>';
	    			$html .='<td>'.$data->fldbillingmode.'</td>';
	    			$html .='<td>'.$data->flditemname.'</td>';
	    			$html .='<td>'.$data->flditemqty.'</td>';
	    			$html .='<td>'.$data->fldexitemtype.'</td>';
	    			$html .='<td>'.$data->fldcutoff.'</td>';
	    			$html .='<td>'.$data->fldregtype.'</td>';
	    			$html .='<td>'.$doctor_name.'</td>';
	    			$html .='<td><a href="javascript:void(0);" class="iq-bg-danger" onclick="deleteautobillingitemdoctor('.$data->fldid.')"><i class="ri-delete-bin-5-fill"></i></a>&nbsp;&nbsp;
					<a href="javascript:void(0);" class="iq-bg-warning p-1" onclick="editautobillingitemdoctor('.$data->fldid.')"><i class="fa fa-edit"></i></a></td>';
	    			$html .='</tr>';
	    		}
			    $html .=' </tbody></table>';
	    	}
	    	echo $html; exit;
    	}catch(\Exception $e){
    		dd($e);
    	}
    }


}
