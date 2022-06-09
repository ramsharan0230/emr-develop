<?php

namespace Modules\Store\Http\Controllers;

use App\ExtraBrand;
use App\MedicineBrand;
use App\SurgBrand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\HospitalBranch;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use Auth;

use Barryvdh\DomPDF\Facade as PDF;

class InventoryDbController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        // if ($request->get('type')) {
        //     $data['inventories'] = $this->_get_all_data($request);
        // }else{
        //     $data['inventories'] = $this->_get_all_intial_data();
        // }

        $data['inventories'] = $this->getAllInvData($request);
        // dd($data);
        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }

        // dd($data);
        return view('store::inventorydb.index', $data);
    }

    private function _get_all_data($request)
    {
        // echo $request->get('department'); exit;
        $type = $request->get('type', 'medicines');
        $brand = $request->get('brand', 'brand');
        $search = $request->get('search');
        $dept = ($request->get('alldept')) ? '' : $request->get('department');

        $fldbrandids = \App\Entry::where([
            ["fldcomp", "like", "$dept"],
            ["fldqty", ">", "0"],
            ['fldstatus','!=','0'],
            ['fldsav','=','1']
        ])->pluck("fldstockid","fldbatch")->toArray();

        if ($type == 'surgicals') {
            $inventories = SurgBrand::whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid', 'asc');
        } elseif ($type == 'extra-items') {
            $inventories = ExtraBrand::whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid', 'asc');
        } else {
            $inventories = MedicineBrand::whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid', 'asc');
        }

        if ($search) {
            if ($brand == 'brand')
                $inventories = $inventories->where('fldbrand', 'like', "%$search%");
            else
                $inventories = $inventories->where('fldbrandid', 'like', "%$search%");
        }
        return $inventories->whereHas('entry', function ($query) use ($dept) {
            $query->where("fldcomp", "like", "$dept")
            ->where('fldqty', '>', 0);
        })->with('entry.Purchase')->get();
    }

    public function _get_all_intial_data(){
        $dept = '%';
        $fldbrandids = \App\Entry::where([
            ["fldcomp", "like", "$dept"],
            ["fldqty", ">", "0"],
            ['fldstatus','!=','0'],
            ['fldsav','1']
        ])->pluck("fldstockid","fldbatch")->toArray();
        // dd($fldbrandids);
        $inventories = MedicineBrand::whereIn('fldbrandid', $fldbrandids)->orderBy('fldbrandid');

        return $inventories->whereHas('entry', function ($query) use ($dept) {
            $query->where("fldcomp", "like", "$dept")
                ->where('fldqty', '>', 0);
        })->with('entry.Purchase')->get();
    }



    public function getAllInvData($request){
        $dept = '%';
        $type = $request->get('type');
        $dept = ($request->get('alldept')) ? '' : $request->get('department');

        if(isset($type) and $type == 'surgicals'){
            $extrasql = " join tblsurgbrand as b on b.fldbrandid = e.fldstockid";
        }else if(isset($type) and $type == 'extra-items'){
            $extrasql = " join tblextrabrand as b on b.fldbrandid = e.fldstockid";
        }else{
            $extrasql = " join tblmedbrand as b on b.fldbrandid = e.fldstockid";
        }

        if($dept =='' or $dept =='%'){

            $deptsql = "";

        }else{
            $deptsql = " and e.fldcomp = '".$dept."'";
        }
        $sql = "select e.fldcategory, e.fldstockid, e.fldqty, e.fldbatch, e.fldexpiry,e.fldcomp,p.flsuppcost, p.fldsuppname, e.fldsellpr, b.fldbrand from tblentry as e inner join tblpurchase as p on p.fldstockno = e.fldstockno".$extrasql." where e.fldsav = '1' and e.fldqty > 0 and p.fldbatch = e.fldbatch ".$deptsql."";
        // echo $sql; exit;

        $result = \DB::select(
                $sql
            );
        return $result;
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'medicines');
        $data['category'] = "Medicines";
        if ($type == 'surgicals')
            $data['category'] = "Surgicals";
        elseif ($type == 'extra-items')
            $data['category'] = "Extra items";
        $data['inventories'] = $this->getAllInvData($request);

        // return view('store::inventorydb.exportpdf', $data)
        //     ->stream('export.pdf');
        $data['certificate'] = "INVENTORY DB REPORT";
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
        // $dept = ($request->get('alldept')) ? '' : \App\Utils\Helpers::getCompName();
        $dept = ($request->get('alldept')) ? '' : $request->get('department');
        $all_data = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcomp', 'fldsav')
            ->where([
                ['fldcategory', $data['category']],
                ['fldcomp', 'like', "%$dept%"],
                ['fldsav','1']
            ])->orderBy('fldstockid');;

        $search = $request->get('search');
        if ($search)
            $all_data = $all_data->where('fldstockid', 'like', "%$search%");
        $all_data = $all_data->get();
        $inventories = [];
        foreach ($all_data as $a_data)
            $data['inventories'][$a_data->fldcomp][] = $a_data;

        // return view('store::inventorydb.inventorypdf', $data)
        //     ->stream('inventory.pdf');
        $data['certificate'] = "INVENTORY DB REPORT";
        return view('store::inventorydb.inventorypdf', $data);
    }
}
