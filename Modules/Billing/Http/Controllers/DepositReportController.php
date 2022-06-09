<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\HospitalDepartmentUsers;
use App\Utils\Helpers;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class DepositReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('billing::department', $data);
    }


}
