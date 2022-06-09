<?php

namespace Modules\Setting\Http\Controllers;

use App\Bedfloor;
use App\Bedgroup;
use App\Bedtype;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class BedController
 * @package Modules\Setting\Http\Controllers
 */
class BedController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bedSetting()
    {
        $data['bed_type'] = $this->generateBedType();
        $data['bed_floor'] = $this->generateBedFloor();
        return view('setting::bed-setting', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bedtypeSettingStore(Request $request)
    {
        $validatedData = $request->validate([
            'bedType' => 'required',
        ]);

        try {
            Bedtype::create([
                                'name' => $request->bedType,
                                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                            ]);
            $html = $this->generateBedType();
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
                    'html' => $e,
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBedType(Request $request)
    {
        try {
            Bedtype::where('id', $request->id)->delete();

            $html = $this->generateBedType();
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
                    'html' => $html,
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bedgroupSettingStore(Request $request)
    {
        $validatedData = $request->validate([
            'bedgroup' => 'required',
        ]);

        try {
            Bedgroup::create([
                                'name' => $request->bedgroup,
                                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                            ]);
            $html = $this->generateBedGroup();
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
                    'html' => $html,
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBedGroup(Request $request)
    {
        try {
            Bedgroup::where('id', $request->id)->delete();

            $html = $this->generateBedGroup();
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
                    'html' => $html,
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBedFloor(Request $request)
    {
        try {
            Bedfloor::where('id', $request->id)->delete();

            $html = $this->generateBedFloor();
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
                    'html' => $html,
                ]
            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bedfloorSettingStore(Request $request)
    {
        $validatedData = $request->validate([
            'bedfloor' => 'required',
            'bedfloororder' => 'required|numeric'
        ]);

        try {
            Bedfloor::create([
                                'name' => $request->bedfloor, 
                                'order_by' => $request->bedfloororder,
                                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                            ]);
            $html = $this->generateBedFloor();
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
                    'html' => $html,
                ]
            ]);
        }
    }

    /**
     * @return string
     */
    public function generateBedType()
    {
        $bedType = Bedtype::all();
        $html = '';
        if (!empty($bedType)) {
            foreach ($bedType as $key => $type) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$type->name</td>";
                $html .= "<td><a href='javascript:;' onclick='bed.deleteBedType(".$type->id.")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }

    /**
     * @return string
     */
    public function generateBedGroup()
    {
        $bedType = Bedgroup::all();
        $html = '';
        if ($bedType) {
            foreach ($bedType as $key => $type) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$type->name</td>";
                $html .= "<td><a href='javascript:;' onclick='bed.deleteBedGroup(".$type->id.")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }

    /**
     * @return string
     */
    public function generateBedFloor()
    {
        $bedType = Bedfloor::all();
        $html = '';
        if ($bedType) {
            foreach ($bedType as $key => $type) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$type->name</td>";
                $html .= "<td>$type->order_by</td>";
                $html .= "<td><a href='javascript:;' onclick='bed.deleteBedFloor(".$type->id.")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
