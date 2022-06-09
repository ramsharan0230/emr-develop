<?php

namespace Modules\Setting\Http\Controllers;

use App\HospitalBranch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class MunicipalityController
 * @package Modules\Setting\Http\Controllers
 */
class HospitalBranchController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function branchSetting()
    {
        $data['branches'] = $this->generateBranchData();
        return view('setting::hospitalBranch.hospital-branch-setting', $data);
    }

    public function add()
    {
        $data['parent_branches'] = HospitalBranch::where('status', "active")->get();
        return view('setting::hospitalBranch.hospital-branch-add', $data);
    }

    public function store(Request $request)
    {
        $rules = array(
            'branch_name' => 'required',
            'branch_code' => 'required',
            'branch_address' => 'required',
            'branch_email' => 'required',
            'system_patient_rank' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('hospital.branch.add')->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->branch_name,
            'address' => $request->branch_address,
            'email' => $request->branch_email,
            'contact' => $request->contact,
            'slogan' => $request->branch_slogan,
            'branch_code' => $request->branch_code,
            'mobile_no' => $request->mobile_no,
            'show_rank' => $request->system_patient_rank,
            'status' => $request->status,
        ];
        if ($request->hasFile('logo')) {
            if (!file_exists(public_path('uploads/images/hospitalbranch')))
                mkdir(public_path('uploads/images/hospitalbranch'), 0777, true);
            $image = $request->file('logo');
            $branch_logo = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

            $path = public_path() . "/uploads/images/hospitalbranch/";

            $image->move($path, $branch_logo);
            $data['logo'] = $branch_logo;
        }
        HospitalBranch::create($data);
        return redirect()->route('hospital.branch');

    }

    public function edit($id)
    {
        $data['branch_id'] = encrypt($id);
        $data['branch_data'] = HospitalBranch::where('id', $id)->first();
        return view('setting::hospitalBranch.hospital-branch-edit', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'branch_name' => 'required',
            'branch_code' => 'required',
            'branch_address' => 'required',
            'branch_email' => 'required',
            'system_patient_rank' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        $branch_id = decrypt($id);

        if ($validator->fails()) {
            return redirect()->route('hospital.branch.edit', $branch_id)->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->branch_name,
            'address' => $request->branch_address,
            'email' => $request->branch_email,
            'contact' => $request->contact,
            'slogan' => $request->branch_slogan,
            'branch_code' => $request->branch_code,
            'mobile_no' => $request->mobile_no,
            'show_rank' => $request->system_patient_rank,
            'status' => $request->status,
        ];
        if ($request->hasFile('logo')) {
            if (!file_exists(public_path('uploads/images/hospitalbranch')))
                mkdir(public_path('uploads/images/hospitalbranch'), 0777, true);
            $image = $request->file('logo');
            $branch_logo = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

            $path = public_path() . "/uploads/images/hospitalbranch/";

            $image->move($path, $branch_logo);
            $data['logo'] = $branch_logo;
        }
        HospitalBranch::where('id', $branch_id)->update($data);
        return redirect()->route('hospital.branch');

    }

    public function delete(Request $request)
    {
        try {
            $hospitalBranch = HospitalBranch::where('id', $request->id)->first();
            if (isset($hospitalBranch->logo)) {
                @unlink(public_path('uploads/images/hospitalbranch/' . $hospitalBranch->logo));
            }
            $hospitalBranch->delete();
            $html = $this->generateBranchData();
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

    public function generateBranchData()
    {
        $branches = HospitalBranch::get();
        $html = '';
        if ($branches) {
            foreach ($branches as $key => $branch) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$branch->name</td>";
                $html .= "<td>$branch->address</td>";
                $html .= "<td>$branch->email</td>";
                $html .= "<td>$branch->status</td>";
                $html .= "<td>
                            <a href='javascript:;' onclick='branch.editBranch(" . $branch->id . ")'><i class='fas fa-pen-square text-primary'></i></a>
                            <a href='javascript:;' onclick='branch.deleteBranch(" . $branch->id . ")'><i class='fas fa-trash text-danger'></i></a>
                         </td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
