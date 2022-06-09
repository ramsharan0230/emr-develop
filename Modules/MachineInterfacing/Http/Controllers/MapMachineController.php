<?php

namespace Modules\MachineInterfacing\Http\Controllers;

use App\MachineMap;
use App\SubExamQuali;
use App\SubTestQuali;
use App\Test;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class MapMachineController
 * @package Modules\MachineInterfacing\Http\Controllers
 */
class MapMachineController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listMap()
    {
        $data['machine_map'] = MachineMap::paginate(25);
        return view('machineinterfacing::list', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $test = Test::select('fldtestid')->get();
        $data['tests'] = $test;
        return view('machineinterfacing::add', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required',
            'test' => 'required|unique:machine_map,test',
        ]);

        try {
            $insertData = [
                'code' => $request->code,
//                'fldtype' => $request->fldtype,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            if ($request->sub_test != ""){
                $insertData['test'] = $request->sub_test;
            }else{
                $insertData['test'] = $request->test;
            }
            $insertData['machinename'] = $request->machine_name;
            MachineMap::create($insertData);
            return redirect()->route('machine.interfacing.list')->with('success', 'Insert Successful.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('machine.interfacing.add')->with('error', __('messages.error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data['test_edit'] = MachineMap::where('id', $id)->first();
        $data['tests'] = Test::select('fldtestid')->get();
        $data['subtests'] = SubTestQuali::select('fldtestid')->get();
        return view('machineinterfacing::edit', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required',
            'test' => 'required',
        ]);

        try {
            $updateData = [
                'code' => $request->code,
                'test' => $request->test
            ];
            $updateData['machinename'] = $request->machine_name;
            MachineMap::where('id', $request->_id)->update($updateData);
            return redirect()->route('machine.interfacing.list')->with('success', 'Update Successful.');
        } catch (\Exception $e) {
            return redirect()->route('machine.interfacing.edit', $request->_id)->with('error', __('messages.error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            MachineMap::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Map deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('machine.interfacing.list')->with('error', __('messages.error'));
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getSubTest(Request $request)
    {
//        return $request->all();
        $subTests = SubTestQuali::select('fldsubtest')->where('fldtestid', 'LIKE', $request->test)->groupBy('fldsubtest')->get();
        $html = '';
        if ($subTests) {
            foreach($subTests as $subTest){
                $html .= '<option value="' . $subTest->fldsubtest . '">' . $subTest->fldsubtest . '</option>';
            }
        } else {
            $html = '<option value=""></option>';
        }

        return $html;
    }
}
