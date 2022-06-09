<?php

namespace Modules\Reports\Http\Controllers;

use App\BillingSet;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ReorderLevelReportBkpController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year.'-'.$datevalue->month.'-'.$datevalue->date;
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] =HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }
        return view('reports::reorderreport.reorder-level',$data);
    }

    public function loadData(Request $request){
        try{
            $patbillingdata = PatBilling::select('flditemname')
                                        ->when($request->category != "%", function ($q) use ($request){
                                            return $q->where('flditemtype',$request->category);
                                        })
                                        ->when($request->category == "%", function ($q) use ($request){
                                            return $q->where('flditemtype',['Medicines','Surgicals','Extra Items']);
                                        })
                                        ->when($request->billingmode != "%", function ($q) use ($request){
                                            return $q->where('fldbillingmode',$request->billingmode);
                                        })
                                        ->distinct('flditemname')
                                        ->orderBy('flditemname','asc')
                                        ->get();
            $html = "";
            foreach($patbillingdata as $patbilling){
                $html .= '<tr>
                            <td class="item-td" data-itemname="'.$patbilling->flditemname.'"><i class="fas fa-angle-right mr-2"></i>'.$patbilling->flditemname.'</td>
                        </tr>';
            }
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }

    public function getRefreshData(Request $request){
        try{
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $itemRadio = $request->itemRadio;
            $category = $request->category;
            $billingmode = $request->billingmode;
            $comp = $request->comp;
            $selectedItem = $request->selectedItem;
            $html = [];
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }
    }
}