<?php

namespace Modules\Setting\Http\Controllers;

use App\HospitalBranch;
use App\HospitalDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class MunicipalityController
 * @package Modules\Setting\Http\Controllers
 */
class HospitalDepartmentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function departmentSetting()
    {
        $data['departments'] = $this->generateDepartmentData();
        return view('setting::hospitalDepartment.hospital-department-setting',$data);
    }

    public function add()
    {
        $data['parent_departments'] = HospitalDepartment::where('status',"active")->get();
        $data['branches'] = HospitalBranch::where('status',"active")->get();
        return view('setting::hospitalDepartment.hospital-department-add',$data);
    }

    public function store(Request $request)
    {
        $rules = array(
            'department_name' => 'required',
            'status' => 'required',
            'company_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('hospital.department.add')->withErrors($validator)->withInput();
        }

        $data = [
                    'name' => $request->department_name,        
                    'branch_id' => $request->branch_id,        
                    'status' => $request->status,   
                    'fldcomp' => $request->company_id  
                ];  
        if($request->has('parent_department')){
            $data['parent_department_id'] = $request->parent_department;
        }
        HospitalDepartment::create($data);
        return redirect()->route('hospital.department');

    }

    public function edit($id)
    {
        $data['department_id'] = encrypt($id);
        $data['branches'] = HospitalBranch::where('status',"active")->get();
        $data['parent_departments'] = HospitalDepartment::where([['status',"active"],['id','!=',$id]])->get();
        $data['department_data'] = HospitalDepartment::where('id',$id)->first();
        return view('setting::hospitalDepartment.hospital-department-edit',$data);
    }

    public function update(Request $request,$id)
    {
        $rules = array(
            'department_name' => 'required',
            'status' => 'required',
            'company_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        $department_id = decrypt($id);

        if ($validator->fails()) {
            return redirect()->route('hospital.department.edit',$department_id)->withErrors($validator)->withInput();
        }

        $data = [
                    'name' => $request->department_name,        
                    'branch_id' => $request->branch_id,        
                    'status' => $request->status,     
                    'fldcomp' => $request->company_id   
                ];  
        if($request->has('parent_department')){
            $data['parent_department_id'] = $request->parent_department;
        }
        HospitalDepartment::where('id',$department_id)->update($data);
        return redirect()->route('hospital.department');

    }

    public function delete(Request $request)
    {
        try {
            HospitalDepartment::where('id', $request->id)->delete();
            $html = $this->generateDepartmentData();
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => [
                    'status' => false,
                    'html' => $html
                ]
            ]);
        }
    }

    public function generateDepartmentData()
    {
        $departments = HospitalDepartment::with('parentDepartment','branchData')->get();
        $html = '';
        if ($departments) {
            foreach ($departments as $key => $department) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$department->name</td>";
                if(isset($department->branchData)){
                    $html .= "<td>".$department->branchData->name."</td>";
                }else{
                    $html .= "<td></td>";
                }
                if($department->parentDepartment != null){
                    $html .= "<td>". $department->parentDepartment->name ."</td>";
                }else{
                    $html .= "<td></td>";
                }
                $html .= "<td>$department->status</td>";
                $html .= "<td>
                            <a href='javascript:;' onclick='department.editDepartment(".$department->id.")'><i class='fas fa-pen-square text-primary'></i></a>
                            <a href='javascript:;' onclick='department.deleteDepartment(".$department->id.")'><i class='fas fa-trash text-danger'></i></a>
                         </td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
