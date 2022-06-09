<?php

namespace Modules\Reports\Http\Controllers;

use App\ExtraBrand;
use App\MedicineBrand;
use App\SurgBrand;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\HospitalDepartmentUsers;
use App\Utils\Helpers;
use Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class ReorderLevelReportController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->get('type')){
            $data['inventories'] = $inventories = $this->_get_all_data($request);
            $data['type'] = $request->type;
        }
        $user = Auth::guard('admin_frontend')->user();
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        // if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
        //     $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        // } else {
        //     $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        // }
        return view('reports::reorderreport.reorder-level', $data);
    }

    public function paginate($items, $options = [], $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    private function _get_all_data($request)
    {
        $type = $request->get('type', 'medicines');
        $brand = $request->get('brand', 'brand');
        $search = $request->get('search');
        $dept = ($request->get('alldept')) ? '' : $request->get('department');

        $fldbrandids = \App\Entry::where([
            ["fldcomp", "like", "%$dept%"],
            ["fldqty", ">", "0"],
        ])->pluck("fldstockid")->toArray();

        if ($type == 'surgicals') {
            $inventories = SurgBrand::select('fldbrandid', 'fldbrand')->whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid');
        } elseif ($type == 'extra-items') {
            $inventories = ExtraBrand::select('fldbrandid', 'fldbrand')->whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid');
        } else {
            $inventories = MedicineBrand::select('fldbrandid', 'fldbrand')->whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid');
        }

        if ($search) {
            if ($brand == 'brand')
                $inventories = $inventories->where('fldbrand', 'like', "%$search%");
            else
                $inventories = $inventories->where('fldbrandid', 'like', "%$search%");
        }
        $options = ['path' => route('reorder-level.display.report',['type' => $type,'brand' => $brand,'search' => $search, 'alldept' => $dept])];
        return $this->paginate($inventories->get(),$options);
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'medicines');
        $data['category'] = "Medicines";
        if ($type == 'surgicals')
            $data['category'] = "Surgicals";
        elseif ($type == 'extra-items')
            $data['category'] = "Extra items";
        $data['inventories'] = $this->_get_all_data($request);

        return view('store::inventorydb.exportpdf', $data);
    }

    public function inventory(Request $request)
    {
        $type = $request->get('type', 'medicines');
        $data['category'] = "Medicines";
        if ($type == 'surgicals')
            $data['category'] = "Surgicals";
        elseif ($type == 'extra-items')
            $data['category'] = "Extra items";
        $dept = ($request->get('alldept')) ? '' : $request->get('department');
        $all_data = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcomp', 'fldsav')
            ->where([
                ['fldcategory', $data['category']],
                ['fldcomp', 'like', "%$dept%"],
            ])->orderBy('fldstockid');;

        $search = $request->get('search');
        if ($search)
            $all_data = $all_data->where('fldstockid', 'like', "%$search%");
        $all_data = $all_data->get();
        $inventories = [];
        foreach ($all_data as $a_data)
            $data['inventories'][$a_data->fldcomp][] = $a_data;

        return view('store::inventorydb.inventorypdf', $data);
    }
}
