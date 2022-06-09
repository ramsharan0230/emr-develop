<?php

namespace Modules\Account\Http\Controllers;

use App\AccountLedger;
use App\BillingSet;
use App\HospitalDepartment;
use App\ServiceCost;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Cache;
use Illuminate\Support\Facades\Session;
use App\Traits\HospitalDepartmentTrait;
use App\Utils\Permission;

class LaboratoryController extends Controller
{
    use HospitalDepartmentTrait;

    private $_itemcategory = 'Diagnostic Tests';

    public function index(Request $request)
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'laboratory', 'laboratory-create'  ])  ) ?
            abort(403, config('unauthorize-message.item-master.laboratory.view')) : true ;
        $data['billitems'] = \App\BillItem::select('fldbillitem')->with('serviceCosts')->where('flditemcateg', $this->_itemcategory)->get();
        $data['sections'] = \App\BillSection::select('fldsection')->where('fldcateg', $this->_itemcategory)->get();
        $all_items = \App\ServiceCost::where('flditemtype', $this->_itemcategory);
        if ($request->get('fldstatus')) {
            $all_items = $all_items->where('fldstatus', $request->get('fldstatus'));
        }
        $data['all_items'] = $all_items->orderBy('fldtime', 'asc')->paginate(100);
        $data['billingset'] = BillingSet::get();
        $data['accountLedger'] = AccountLedger::select('AccountNo','AccountName')->where('fldstatus',1)->groupBy('AccountNo')->get();
        $data['categories'] = config('usershare.categories');
        $data['hospital_departments'] = Session::get('user_hospital_departments');
        return view('account::laboratory', $data);
    }

    public function searchInDatabase(Request $request)
    {
        $all_items = \App\ServiceCost::where('flditemtype', $this->_itemcategory);

        if ($request->get('fldstatus')) {
            $all_items = $all_items->where('fldstatus', $request->get('fldstatus'));
        }

        if ($request->get('input')) {
            $all_items = $all_items->where('flditemname','like', '%'. $request->get('input') . '%');
        }
        $result_items = $all_items->orderBy('fldtime', 'asc')->get();
        $html ='';
        $count =1;
        if($result_items){
            foreach ($result_items as $item){

                $billitemcode = \App\BillItem::where('fldbillitem',$item->fldbillitem)->pluck('fldbillitemcode')->first();

                $html.="<tr fldid='".$item->fldid."' flddocshare='".$item->other_share ."' fldhospitalshare='". $item->hospital_share ."' accountledger='". $item->account_ledger ."' fldcategory='". json_encode($item->category) ."' fldbillitem='". $item->fldbillitem ."' flditemcost='". $item->flditemcost ."' fldtarget='". $item->fldtarget ."' fldgroup='". $item->fldgroup ."' fldreport='". $item->fldreport ."' fldstatus='". $item->fldstatus ."' fldcode='". $item->fldcode."' flditemname='". $item->flditemname ."' fldrate='". $item->rate. "'hi_code='" . $item->hi_code . "'fldbillitemcode='" . $billitemcode ."' flddiscount='". $item->discount ."'>";
                $html.='<td>'.$count++.'</td>';
                $html.='<td>'.$item->flditemname.'</td>';
                $html.='<td>'.$item->flditemcost.'</td>';
                $html.='<td>'.$item->fldtarget.'</td>';
                $html.='<td>'.$item->fldstatus.'</td>';
                $html.='<td>'.$item->fldgroup.'</td>';
                $html.='<td>'.$item->fldreport.'</td>';
                //comment
                // $html.='<td>'.$billitemcode.'</td>';
            }
        }
        return response()->json(['item' =>$html]);
    }

    public function getSelectOptions()
    {
        return response()->json([
            'billitems' => \App\BillItem::select('fldbillitem')->where('flditemcateg', $this->_itemcategory)->get(),
            'sections' => \App\BillSection::select('fldsection')->where('fldcateg', $this->_itemcategory)->get(),
        ]);
    }

    public function saveUpdate(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'laboratory', 'laboratory-add'  ])  ) ?
        abort(403, config('unauthorize-message.item-master.laboratory.add')) : true ;
        // INSERT INTO `tblservicecost` ( `flditemname`, `fldcode`, `fldid`, `fldbillitem`, `flditemtype`, `flditemcost`, `fldtarget`, `fldstatus`, `fldgroup`, `fldreport`, `flduserid`, `fldtime`, `fldcomp` ) VALUES ( 'Asdasd(HealthInsuranceProvider)', '1112', 1524, 'asdasd', 'General Services', 11, 'Unit', 'Active', 'HealthInsuranceProvider', 'asdasd', 'admin', '2020-09-03 13:08:25.735', 'comp01' )
        try {
            $fldid = $request->fldid;
            $data = [
                'flditemname' => $request->get('flditemname') ?? '',
                'fldcode' => $request->get('fldcode') ?? '',
                'fldbillitem' => $request->get('fldbillitem') ?? '',
                'flditemcost' => $request->get('flditemcost') ?? '',
                'fldtarget' => $request->get('fldtarget') ?? '',
                'fldstatus' => $request->get('fldstatus') ?? '',
                'fldgroup' => $request->get('fldgroup') ?? '',
                'fldreport' => $request->get('fldreport') ?? '',
                'category' => json_encode($request->get('category')),
                'rate' => $request->get('rate') ?? 0,
                'discount' => $request->get('discount') ?? 0,
                'other_share' => $request->get('other_share') ?? '',
                'hospital_share' => $request->get('hospital_share') ?? '',
                'account_ledger' => $request->get('accountledger') ?? '',
                'hi_code' => $request->get('hi_code') ?? '',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $message = '';
            if (!empty($fldid)) {
                $service_cost = ServiceCost::where('fldid', $fldid)->first();
                $service_cost = ServiceCost::where([
                   // ['fldid', $fldid],
                   ['flditemname', $request->get('flditemname')],
                    ['flditemtype', $this->_itemcategory]
                ])->first();
                // for update function insert as raw array for category.
                $data['category'] = $request->category;
                $service_cost->update($data);
                $message = 'Updated Successfully';
                Helpers::logStack(["Service cost updated", "Event"], ['current_data' => $data, 'previous_data' => $service_cost]);
            } else {
                $request->validate([
                    'flditemname' => 'required|unique:tblservicecost,flditemname'
                ], [
                    'flditemname.unique' => 'The item name already exists.'
                ]);

                $fldid = \App\ServiceCost::max('fldid');
                $fldid = ($fldid) ? $fldid + 1 : 1;

                $time = date('Y-m-d H:i:s');
                $userid = \App\Utils\Helpers::getCurrentUserName();
                $computer = \App\Utils\Helpers::getCompName();

                \App\ServiceCost::insertGetId([
                        'fldid' => $fldid,
                        'flduserid' => $userid,
                        'fldtime' => $time,
                        'fldcomp' => $computer,
                        'flditemtype' => $this->_itemcategory,
                    ] + $data);

                    $message = 'Created Successfully';

                Helpers::logStack(["Service cost created", "Event"], ['current_data' => [
                    'fldid' => $fldid,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'flditemtype' => $this->_itemcategory,
                ] + $data]);
            }
            return response()->json([
                'status' => TRUE,
                'data' => $data + ['fldid' => $fldid] + ['hospital_department' => $data['fldtarget']],
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) create/update', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }

    public function exportItems()
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'laboratory', 'laboratory-view'  ])  ) ?
        abort(403, config('unauthorize-message.item-master.laboratory.view')) : true ;
        $all_items = \App\ServiceCost::where('flditemtype', $this->_itemcategory)->get();
        return \Barryvdh\DomPDF\Facade::loadView('account::laboratoryPdf', compact('all_items'))
            ->stream('laboratoryPdf.pdf');
    }

}
