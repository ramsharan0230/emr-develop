<?php

namespace Modules\ItemMaster\Http\Controllers;

use App\BillItem;
use Carbon\Carbon;
use App\BillingSet;
use App\BillSection;
use App\ServiceCost;
use App\AccountLedger;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\ItemMaster\Http\Requests\ItemMasterRequest;
use stdClass;

class ItemMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data['category'] = $request->flditemtype ?? "";
        $data['target'] = $request->fldtarget ?? "";
        $data['department'] = $request->fldreport ?? "";
        $data['accountLedger'] = $request->account_ledger ?? "";
        $data['tax'] = $request->fldcode ?? "";
        $data['categories'] = [
            'Diagnostic Tests' => 'Laboratory',
            'Equipment' => 'Equipment',
            'General Services' => 'General Services',
            'Procedures' => 'Procedures',
            'Radio Diagnostics' => 'Radiology',
            'Other Items' => 'Other Items',
        ];
        $data['targets'] = ServiceCost::select('fldtarget')->whereNotNull('fldtarget')->where('fldtarget', '!=', "")->groupBy('fldtarget')->pluck('fldtarget')->toArray();
        $sections = Cache::rememberForever('billing-section', function () {
            return BillSection::select('fldid', 'fldsection', 'fldcateg')->get();
        });
        $data['sections'] = $sections;
        $data['accountLedgers'] =  AccountLedger::select('AccountNo', 'AccountName')->where('fldstatus', 1)->groupBy('AccountNo')->get();
        $data['itemGroup'] = ServiceCost::whereNotNull('fldbillitem')
            ->where('fldbillitem', '!=', "")
            ->when($request->flditemtype != "", function ($query) use ($request) {
                $query->where('flditemtype', $request->flditemtype);
            })
            ->when($request->fldtarget != "", function ($query) use ($request) {
                $query->where('fldtarget', $request->fldtarget);
            })
            ->when($request->fldreport != "", function ($query) use ($request) {
                $query->where('fldreport', $request->fldreport);
            })
            ->when($request->account_ledger != "", function ($query) use ($request) {
                $query->where('account_ledger', $request->account_ledger);
            })
            ->when($request->fldcode != "", function ($query) use ($request) {
                $query->where('fldcode', $request->fldcode);
            })
            ->orderBy('fldbillitem')
            ->get()
            ->groupBy('fldbillitem');

        return view('itemmaster::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['category'] = [
            'Diagnostic Tests' => 'Laboratory',
            'Equipment' => 'Equipment',
            'General Services' => 'General Services',
            'Procedures' => 'Procedures',
            'Radio Diagnostics' => 'Radiology',
            'Other Items' => 'Other Items',
        ];
        $data['items'] = json_encode(BillItem::select('fldbillitem', 'flditemcateg', 'fldbillitemcode')->get());
        $sections = Cache::rememberForever('billing-section', function () {
            return BillSection::select('fldid', 'fldsection', 'fldcateg')->get();
        });
        $data['sections'] = $sections;
        $billingset = Cache::rememberForever('billing-set', function () {
            return BillingSet::get();
        });
        $data['billingset'] = json_encode($billingset);
        $data['equipmentTargets'] = json_encode(['Day', 'Hour', 'Minute']);
        $data['procedureTargets'] = json_encode(['Major', 'Minor', 'Intermediate']);
        $data['otherTargets'] = json_encode(Session::get('user_hospital_departments'));
        $data['categories'] = config('usershare.categories');
        $data['accountLedgers'] =  AccountLedger::select('AccountNo', 'AccountName')->where('fldstatus', 1)->groupBy('AccountNo')->get();
        return view('itemmaster::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ItemMasterRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->saveItems($request);
            DB::commit();
            Session::flash('success_message', 'Items Inserted Successfully.');
            return redirect(route('itemmaster.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) create', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect(route('itemmaster.create'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($encrypt_fldbillitem)
    {
        $fldbillitem = base64_decode($encrypt_fldbillitem);
        $data['items'] = ServiceCost::where('fldbillitem', $fldbillitem)->get();
        if (!$data['items']) {
            return;
        }
        $data['categories'] = $data['items']->first()->category ?? [];
        return view('itemmaster::show', $data)->render();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($encrypt_fldbillitem)
    {
        $fldbillitem = base64_decode($encrypt_fldbillitem);
        $data['items'] = ServiceCost::where('fldbillitem', $fldbillitem)->get();
        if (!$data['items']) {
            Session::flash('error_message', "Item not found.");
            return redirect(route('itemmaster.index'));
        }
        if ($data['items']->first()->flditemtype == 'Procedures') {
            $data['targerLabelName'] = 'Type';
            $data['hospital_departments'] = [];
            $targets = ['Major', 'Minor', 'Intermediate'];
            foreach ($targets as $target) {
                $object = new stdClass();
                $object->fldcomp = $target;
                $object->name = $target;
                array_push($data['hospital_departments'], $object);
            }
        } elseif ($data['items']->first()->flditemtype == 'Equipment' || $data['items']->first()->flditemtype == 'General Services') {
            $data['targerLabelName'] = 'Rate For';
            $data['hospital_departments'] = [];
            $targets = ['Day', 'Hour', 'Minute'];
            foreach ($targets as $target) {
                $object = new stdClass();
                $object->fldcomp = $target;
                $object->name = $target;
                array_push($data['hospital_departments'], $object);
            }
        } else {
            $data['targerLabelName'] = 'Target';
            $data['hospital_departments'] = Session::get('user_hospital_departments');
        }
        $data['category'] = [
            'Diagnostic Tests' => 'Laboratory',
            'Equipment' => 'Equipment',
            'General Services' => 'General Services',
            'Procedures' => 'Procedures',
            'Radio Diagnostics' => 'Radiology',
            'Other Items' => 'Other Items',
        ];
        $billingset = Cache::rememberForever('billing-set', function () {
            return BillingSet::get();
        });
        $data['billingset'] = json_encode($billingset);
        $data['categories'] = config('usershare.categories');
        $data['sections'] = BillSection::select('fldsection')->get();
        $data['accountLedgers'] =  AccountLedger::select('AccountNo', 'AccountName')->where('fldstatus', 1)->groupBy('AccountNo')->get();
        return view('itemmaster::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ItemMasterRequest $request, $encrypt_fldbillitem)
    {
        try {
            DB::beginTransaction();
            $fldbillitem = base64_decode($encrypt_fldbillitem);
            $this->saveItems($request, $fldbillitem);
            DB::commit();
            Session::flash('success_message', 'Items Updated Successfully.');
            return redirect(route('itemmaster.index'));
        } catch (\Exception $e) {
            DB::rollback();
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) update', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect(route('itemmaster.edit', $encrypt_fldbillitem));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $ServiceCost = ServiceCost::find($request->id);
            if ($ServiceCost) {
                $ServiceCost->delete();
                Helpers::logStack(["Service cost deleted", "Event"], ['previous_data' => $ServiceCost]);
                return response()->json([
                    'status' => TRUE,
                    'message' => "Item deleted successfully.",
                ]);
            } else {
                Helpers::logStack(["Item not found in item master(service cost) delete", "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => "Item not found."
                ]);
            }
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) delete', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $fldbillitem = base64_decode($request->encrypt_fldbillitem);
            if (!$fldbillitem) {
                Helpers::logStack(["Item is required in item master(service cost) status change", "Error"]);
                Session::flash('error_message', 'Please provide item.');
                return redirect(route('itemmaster.index'));
            }

            $ServiceCost = ServiceCost::where('fldbillitem', $fldbillitem)->get();
            if ($ServiceCost->count() < 1) {
                Helpers::logStack(["Item not found in item master(service cost) status change", "Error"]);
                Session::flash('error_message', 'Item not found.');
                return redirect(route('itemmaster.index'));
            }

            $status = $ServiceCost->first()->fldstatus;
            if ($status == 'Active') {
                $status = 'Inactive';
            } else {
                $status = 'Active';
            }
            ServiceCost::where('fldbillitem', $fldbillitem)->update(['fldstatus' => $status]);
            Helpers::logStack(["Service cost status change", "Event"], ['current_data' => $status, 'previous_data' => $ServiceCost]);
            Session::flash('success_message', 'Status Changed Successfully.');
            return redirect(route('itemmaster.index'));
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) status change', "Error"]);
            Session::flash('error_message', 'Items Inserted Successfully.');
            return redirect(route('itemmaster.index'));
        }
    }

    public function createItem(Request $request)
    {
        $request->validate([
            'fldbillitem' => 'required',
            'flditemcateg' => 'required',
            'hospital_department_id' => 'required'
        ]);
        try {
            if (BillItem::where(['fldbillitem' => $request->fldbillitem, 'flditemcateg' => $request->flditemcateg])->first()) {
                Helpers::logStack(['Item already exist in item master item create', "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => "Item already exist."
                ]);
            }
            BillItem::create($request->all());
            $item = BillItem::select('fldbillitem', 'flditemcateg', 'fldbillitemcode')->where(['fldbillitem' => $request->fldbillitem, 'flditemcateg' => $request->flditemcateg])->first();
            Helpers::logStack(["Service cost created", "Event"], ['current_data' => $item]);
            return response()->json([
                'status' => TRUE,
                'data' => $item,
                'message' => "Item created successfully.",
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master item create', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createDepartment(Request $request)
    {
        $request->validate([
            'fldsection' => 'required',
            'fldcateg' => 'required',
            'hospital_department_id' => 'required'
        ]);
        try {
            if (BillSection::where(['fldsection' => $request->fldsection, 'fldcateg' => $request->fldcateg])->first()) {
                Helpers::logStack(['Department already exist in item master item department create', "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => "Department already exist."
                ]);
            }
            $department = BillSection::create($request->all());
            Helpers::logStack(["Department created", "Event"], ['current_data' => $department]);
            Cache::forget('billing-section');
            return response()->json([
                'status' => TRUE,
                'data' => $department,
                'message' => "Department create successfully.",
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master department create', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function check(Request $request)
    {
        try {
            $items = ServiceCost::where('fldbillitem', $request->item)->get();
            if ($items && $items->count() > 0) {
                return response()->json([
                    'status' => TRUE,
                    'data' => $items,
                    'message' => "Item deleted successfully.",
                ]);
            } else {
                return response()->json([
                    'status' => FALSE,
                    'message' => "Item not exist."
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    protected function saveItems($request, $fldbillitem = null)
    {
        $data = [];
        $time = date('Y-m-d H:i:s');
        $userid = \App\Utils\Helpers::getCurrentUserName();
        $computer = \App\Utils\Helpers::getCompName();
        $hospital_department_id = Helpers::getUserSelectedHospitalDepartmentIdSession();
        foreach ($request->items as $item) {
            if ($item['flditemname'] && $item['flditemcost'] && $item['flditemcost'] > 0) {
                $data = [
                    'flditemname' => $item['flditemname'],
                    'fldbillitem' => $fldbillitem ?? $request->fldbillitem,
                    'flditemcost' => $item['flditemcost'],
                    'fldcode' => $request->fldcode,
                    'fldgroup' => $item['fldgroup'],
                    'fldreport' => $request->fldreport,
                    'fldstatus' => $item['fldstatus'] ?? $request->fldstatus,
                    'fldtarget' => $request->fldtarget,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'flditemtype' => $request->flditemtype,
                    'hospital_department_id' => $hospital_department_id,
                    'category' => $request->category,
                    'rate' => $request->rate ?? 0,
                    'discount' => $request->discount ?? 0,
                    'hospital_share' => $request->hospital_share,
                    'other_share' => $request->other_share,
                    'account_ledger' => $request->accountledger,
                    'flddescription' => $request->flddescription,
                ];
                $ServiceCost = ServiceCost::where(['flditemname' =>  $item['flditemname'], 'fldbillitem' => $fldbillitem ?? $request->fldbillitem])->first();
                if ($ServiceCost) {
                    $data['category'] = json_encode($request->category);
                    $data['updated_by'] = $userid;
                    $data['updated_at'] = Carbon::now()->toDateTimeString();
                    ServiceCost::where(['flditemname' =>  $item['flditemname'], 'fldbillitem' => $fldbillitem ?? $request->fldbillitem])->update($data);
                    Helpers::logStack(["Service cost updated", "Event"], ['current_data' => $data, 'previous_data' => $ServiceCost]);
                } else {
                    $data['flduserid'] = $userid;
                    $data['created_at'] = Carbon::now()->toDateTimeString();
                    ServiceCost::create($data);
                    Helpers::logStack(["Service cost created", "Event"], ['current_data' => $data]);
                }
            }
        }
    }
}
