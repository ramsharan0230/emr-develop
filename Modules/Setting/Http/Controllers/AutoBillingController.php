<?php

namespace Modules\Setting\Http\Controllers;

use App\Settings;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class AutoBillingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['department'] = '';
        $data['departmentData'] = '';
        if ($request->department) {
            $data['departmentData'] = Settings::where('fldcomp', 'like', $request->department)->get();
            $data['department'] = $request->department;
        }
        $data['hospital_departments'] = Session::get('user_hospital_departments');
        return view('setting::auto-billing', $data);
    }

    public function insertUpdate(Request $request)
    {

        try {
            $oldData = Settings::where('fldcomp', 'like', $request->department)->where('fldcategory', 'like', $request->fldcategory)->first();
            if ($oldData) {
                $oldData->where([
                                    ['fldcomp', 'like', $request->department]
                                ])->where('fldcategory', 'like', $request->fldcategory)->update(['fldcomp' => $request->department, 'fldvalue' => $request->billingType]);
            } else {
                $data['fldindex'] = $request->department . ':' . $request->fldcategory;
                $data['fldcomp'] = $request->department;
                $data['fldcategory'] = $request->fldcategory;
                $data['fldvalue'] = $request->billingType;
                $data['fldtime'] = now();
                $data['flduserid'] = \Auth::guard('admin_frontend')->user()->flduserid;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                Settings::insert($data);
            }
            return response()->json([
                'success' => [
                    'status' => true,
                ]
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'success' => [
                    'status' => false,
                ]
            ]);
        }

    }
}
