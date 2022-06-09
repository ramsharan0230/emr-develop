<?php

namespace Modules\Account\Http\Controllers;

use App\AccountLedger;
use App\BillingSet;
use App\ServiceCost;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Cache;

class OtherItemController extends Controller
{
    private $_itemcategory = 'Other Items';

    public function index(Request $request)
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'other-items', 'other-items-view'  ])  ) ?
            abort(403, config('unauthorize-message.item-master.other-items.view')) : true ;
        $billitems = \App\BillItem::select('fldbillitem')->with('serviceCosts')->where('flditemcateg', $this->_itemcategory)->get();
        $sections = \App\BillSection::select('fldsection')->where('fldcateg', $this->_itemcategory)->get();
        $all_items = \App\ServiceCost::where('flditemtype', $this->_itemcategory);
        if ($request->get('fldstatus'))
            $all_items = $all_items->where('fldstatus', $request->get('fldstatus'));
        $all_items = $all_items->paginate(2);
        $billingset = BillingSet::get();
        $hospital_departments = \Session::get('user_hospital_departments');
        $categories = config('usershare.categories');
        $data['accountLedger'] =  $accountLedger = AccountLedger::select('AccountNo','AccountName')->where('fldstatus',1)->groupBy('AccountNo')->get();
        return view('account::otherItem', compact('billingset', 'billitems', 'sections', 'all_items', 'hospital_departments', 'categories','accountLedger'));
    }

    public function searchInDatabase(Request $request)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'other-items', 'other-items-view'  ])  ) ?
            abort(403, config('unauthorize-message.item-master.other-items.view')) : true ;

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

                $html.='<tr fldid="'.$item->fldid.'" flddocshare="'.$item->other_share .'" fldhospitalshare="'. $item->hospital_share .'" accountledger="'. $item->account_ledger .'" fldcategory='. json_encode($item->category) .' fldbillitem="'. $item->fldbillitem .'" flditemcost="'. $item->flditemcost .'" fldtarget="'. $item->fldtarget .'" fldgroup="'. $item->fldgroup .'" fldreport="'. $item->fldreport .'" fldstatus="'. $item->fldstatus .'" fldcode="'. $item->fldcode.'" flditemname="'. $item->flditemname .'" fldrate="'. $item->rate . "'fldbillitemcode='" . $billitemcode .'" flddiscount="'. $item->discount .'">';
                $html.='<td>'.$count++.'</td>';
                $html.='<td>'.$item->flditemname.'</td>';
                $html.='<td>'.$item->flditemcost.'</td>';
                $html.='<td>'.$item->fldtarget.'</td>';
                $html.='<td>'.$item->fldstatus.'</td>';
                $html.='<td>'.$item->fldgroup.'</td>';
                $html.='<td>'.$item->fldreport.'</td>';
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

    public function addVariable(Request $request)
    {
        try {
            $type = $request->get('type');
            $flditem = $request->get('flditem');

            if ($flditem == '' || !in_array($type, ['tblbillitem', 'tblbillsection'])) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Invalid data. Please refresh page and try again.'
                ]);
            }

            $modelData = $this->_getModalName($type);

            //auto service code generation--------------------------------

            // $billitemcode = \App\BillItem::select('fldbillitemcode')->where('flditemcateg','=',$request->get('category'))->get();

            $billitemcode = \DB::table('tblbillitem')
            ->where('flditemcateg','=',$request->get('category'))
            ->where('hospital_department_id','=',Helpers::getUserSelectedHospitalDepartmentIdSession())
            ->whereRaw('fldbillitemcode is not null')
            ->orderByRaw('fldbillitemcode asc')
            ->pluck('fldbillitemcode')->last();

            $codepref ='';
            $codeid = '';


            if($request->get('category') == 'Diagnostic Tests'){
                $codepref = 'LB';
            }elseif($request->get('category') == 'Radio Diagnostics'){
                $codepref = 'RD';
            }elseif($request->get('category') == 'General Services'){
                $codepref = 'GS';
            }elseif($request->get('category') == 'Other Items'){
                $codepref = 'ET';
            }elseif($request->get('category') == 'Procedures'){
                $codepref = 'PR';
            }elseif($request->get('category') == 'Equipment'){
                $codepref = 'EQ';
            }


            if(isset($billitemcode)){

               $billitemcode = trim($billitemcode, $codepref);



                $billitemcode = $billitemcode + 1;
                $codeid = $codepref . $billitemcode;

            }else{
                $codeid = $codepref . '1';
            }

            //auto service code generation---------------------------


            $data = [
                $modelData['columnName'] => $flditem,
                $modelData['categoryColumn'] => $request->get('category'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldbillitemcode' => $codeid
            ];
            $modelData['modelName']::insert($data);



            return [
                'status' => TRUE,
                'data' => $flditem,
                'codeid' => $codeid,
                'message' => __('messages.success', ['name' => 'Information'])
            ];
        } catch (\Exception $e) {
            return [
                'status' => FALSE,
                'message' => $e->getMessage()
            ];

        }
    }

    public function deleteVariable(Request $request)
    {
        try {
            $type = $request->get('type');
            $flditem = $request->get('flditem');

            if ($flditem == '' || !in_array($type, ['tblbillitem', 'tblbillsection'])) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Invalid data. Please refresh page and try again.'
                ]);
            }

            $modelData = $this->_getModalName($type);
            $data = [
                $modelData['columnName'] => $flditem,
            ];
            $modelData['modelName']::where($data)->delete();

            return [
                'status' => TRUE,
                'message' => __('messages.success', ['name' => 'Information'])
            ];
        } catch (\Exception $e) {
            return [
                'status' => FALSE,
                'message' => $e->getMessage()
            ];
        }
    }

    public function importVariable(Request $request)
    {

        try {
            $type = $request->get('type');

            if (!in_array($type, ['tblbillitem', 'tblbillsection'])) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Invalid data. Please refresh page and try again.'
                ]);
            }

            $modelData = $this->_getModalName($type);
            $file = $request->file('fldtext')->getPathName();
            $txt_file = file_get_contents($file);
            $rows = array_filter(explode("\r\n", $txt_file));

            $data = [];
            foreach ($rows as $value) {
                $data[] = [
                    $modelData['columnName'] => $value,
                    $modelData['categoryColumn'] => $request->get('category'),
                ];
            }
            $modelData['modelName']::insert($data);

            return [
                'status' => TRUE,
                'data' => $rows,
                'message' => __('messages.success', ['name' => 'Information'])
            ];
        } catch (\Exception $e) {
            return [
                'status' => FALSE,
                'message' => $e->getMessage()
            ];
        }
    }

    private function _getModalName($type)
    {
        $modelName = ($type === 'tblbillitem') ? "\App\BillItem" : "\App\BillSection";
        $columnName = ($type === 'tblbillitem') ? "fldbillitem" : "fldsection";
        $categoryColumn = ($type === 'tblbillitem') ? "flditemcateg" : "fldcateg";
        return compact('modelName', 'columnName', 'categoryColumn');
    }

    public function saveUpdate(Request $request)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'other-items', 'other-items-add', 'other-items-update'  ])  ) ?
            abort(403, config('unauthorize-message.item-master.other-items.update')) : true ;
        // INSERT INTO `tblservicecost` ( `flditemname`, `fldcode`, `fldid`, `fldbillitem`, `flditemtype`, `flditemcost`, `fldtarget`, `fldstatus`, `fldgroup`, `fldreport`, `flduserid`, `fldtime`, `fldcomp` ) VALUES ( '## search(General)', '1111', 1523, '## search', 'Other Items', 0, 'comp07', 'Inactive', 'General', '2020-05-11 22:31:44.761 gb.db.sqlite3: 0x1a70378: select advid,advdate,advleftlink,advrightlink from tbladlinks where advdate>=''2020-05-11 00:00:00'' and advdate<=''2020-05-11 23:59:59.999''', 'admin', '2020-07-30 23:12:13.875', 'comp07' )
        try {
            $fldid = $request->get('fldid');
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
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $message = '';
            if (!empty($fldid)) {
                $service_cost = ServiceCost::where([
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
                'data' => $data + ['fldid' => $fldid],
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in item master(service cost) create/update', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function exportItems()
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status and boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'other-items', 'other-items-view'  ])  ) ?
            abort(403, config('unauthorize-message.item-master.other-items.view')) : true ;
        $all_items = \App\ServiceCost::where('flditemtype', $this->_itemcategory)->get();
        return \Barryvdh\DomPDF\Facade::loadView('account::otherItemPdf', compact('all_items'))
            ->stream('otherItemPdf.pdf');
    }

    public function getbillitemcode(Request $request){

        $billitemcode = \App\BillItem::where('fldbillitem',$request->get('fldbillitem'))->pluck('fldbillitemcode')->first();

        return ['codeid' => $billitemcode];

    }

}
