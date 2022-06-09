<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\BulkSale;
use App\Demand;
use App\Entry;
use App\Extra;
use App\ExtraBrand;
use App\Order;
use App\PatBilling;
use App\Purchase;
use App\Transfer;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Session;

class PharmacistController extends Controller
{

    public function index()
    {
    }

    public function surgical()
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'surgical-information', 'surgical-information-view'  ])  ) ?
            abort(403) : true ;
        if (Permission::checkPermissionFrontendAdmin('surgical-information')) {
            return view('pharmacist::surgical');
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function extraItem()
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'extra-items-information', 'extra-items-information-view'  ])  ) ?
            abort(403, 'Unauthorized action.') : true ;
        if (Permission::checkPermissionFrontendAdmin('extra-items-information')) {
            $data['extras'] = Extra::distinct('fldextraid')->orderBy('fldextraid','asc')->get();
            $data['tax_codes'] = \App\Utils\Medicinehelpers::getAllTaxGroup();
            return view('pharmacist::extra-items', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function getItemNameinfo(Request $request){
        try{
            $extras = Extra::where('fldextraid',$request->fldextraid)->first();
            $data = $this->getExtraBrandLists($request->fldextraid);
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'data' => $data,
                'extras' => $extras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }

    public function getExtraBrandLists($fldextraid){
        $extraBrands = ExtraBrand::where('fldextraid',$fldextraid)->get();
        $html = '';
        $brandHtml = '';
        if(isset($extraBrands)){
            $i = 0;
            foreach($extraBrands as $brand){
                $html .= '<ul class="list-group extra-item-brand-ul">
                                <li class="list-group-item med-padding">
                                    <div class="row extra-item-brand editbrand" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" style="cursor: pointer;">
                                        <div class="col-sm-1 p-0 text-right">
                                        <i class="fa fa-caret-right" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            '.$brand->fldbrandid.'
                                        </div>
                                        <div class="col-sm-1 p-0 text-right">
                                            <a href="#" class="text-danger deletebrand" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'"> <i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>';

                $entries = Entry::where('fldstockid', $brand->fldbrandid)->get()->groupBy('fldbatch');
                if(count($entries) > 0){
                    foreach($entries as $entry){
                        ++$i;
                        $qtysum = $entry->sum('fldqty');
                        $status = ($brand->fldactive == "Active") ? "Active" : "Inactive";
                        $brandHtml .= '<tr class="row-links" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'">
                                        <td>'.$i.'</td>
                                        <td>'.$brand->fldbrand.'</td>
                                        <td>'.$entry[0]->fldbatch.'</td>
                                        <td>'.$entry[0]->fldexpiry.'</td>
                                        <td>'.$entry[0]->fldsellpr.'</td>
                                        <td>'.$qtysum.'</td>
                                        <td>'.$brand->fldtaxable.'</td>
                                        <td>'.$brand->fldtaxcode.'</td>
                                        <td>'.$status.'</td>
                                        <td>
                                            <a href="#" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                        </tr>';
                    }
                }else{
                    ++$i;
                    $brandHtml .= '<tr class="row-links" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'">
                                        <td>'.$i.'</td>
                                        <td>'.$brand->fldbrand.'</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>'.$brand->fldtaxable.'</td>
                                        <td>'.$brand->fldtaxcode.'</td>
                                        <td>'.$brand->fldactive.'</td>
                                        <td>
                                            <a href="#" data-extraid="'.$brand->fldextraid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                        </tr>';
                }
            }
        }

        $data['html'] = $html;
        $data['brandHtml'] = $brandHtml;
        return $data;
    }

    // item name variable
    public function insertVariable(Request $request)
    {
        try {
            $checkifexist = Extra::where('fldextraid', $request->item_name)->first();
            if ($checkifexist) {
                Helpers::logStack(["Extra item already exists in variable create", "Error"]);
                return response()->json([
                    'status' =>  FALSE,
                    'message' => 'Variable Already Exists.',
                ]);
            }
            $data = [
                'fldextraid' => $request->item_name,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $insert = Extra::insertGetId($data);
            Helpers::logStack(["Pharmacy item name variable created", "Event"], ['current_data' => $data]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Item Name Variable.',
                'insertId' => $insert
            ]);
        } catch (Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in variable create', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    // item name variable
    public function deleteVariable(Request $request)
    {
        try {
            $checkifexist = Extra::where('fldid', $request->fldid)->first();
            if (!$checkifexist) {
                Helpers::logStack(["Extra item not found in variable delete", "Error"]);
                return response()->json([
                    'status' =>  FALSE,
                    'message' => 'Variable Does Not Exist.',
                ]);
            }
            if(count($checkifexist->extraBrand) > 0){
                Helpers::logStack(["Extra item cannot be deleted in variable delete", "Error"]);
                return response()->json([
                    'status' =>  FALSE,
                    'message' => 'Cannot delete this variable.',
                ]);
            }
            Extra::where('fldid', $request->fldid)->delete();
            Helpers::logStack(["Extra item deleted", "Event"], ['previous_data' => $checkifexist]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Deleted Item Name Variable.',
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in variable delete', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getVariables()
    {
        $get_all_variables = Extra::select('fldid', 'fldextraid as col')->orderBy('fldextraid')->get();
        return response()->json($get_all_variables);
    }

    public function getBrandDetails(Request $request)
    {
        try {
            $brandid = $request->fldbrandid;
            $brandDetail = ExtraBrand::select('fldbrandid', 'fldextraid', 'fldbrand', 'fldpackvol', 'fldvolunit', 'fldmanufacturer', 'flddepart', 'flddetail', 'fldstandard', 'fldmaxqty', 'fldminqty', 'fldleadtime', 'fldactive', 'fldtaxable', 'fldtaxcode', 'fldmrp', 'fldcccharge', 'fldcccharg_val', 'fldrefundable', 'fldinsurance', 'flddiscountable_item', 'fldcccharge')
                ->where('fldbrandid', $brandid)
                ->first();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfull.',
                'brandDetail' => $brandDetail
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function insertExtraItem(Request $request)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, ['extra-items-information', 'extra-items-information-create'  ])  ) ?
            abort(403) : true ;
        DB::beginTransaction();
        try {
            $newfldbrandid = $request->fldextraid . "(" . $request->fldbrand . ")";
            $fldbrandid = $request->fldbrandid;
            $checkifexist = ExtraBrand::where('fldbrandid', $fldbrandid)->first();
            if ($checkifexist != null) {
                if(trim($request->fldbrand) != trim($checkifexist->fldbrand)){
                    $checkiftransfered = Transfer::where('fldstockid',$checkifexist->fldbrandid)->get();
                    if(count($checkiftransfered) > 0){
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "You cannot edit this Itemname."
                        ]);
                    }
                    $checkifconsumed = BulkSale::where('fldstockid',$checkifexist->fldbrandid)->get();
                    if(count($checkifconsumed) > 0){
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "You cannot edit this Itemname."
                        ]);
                    }
                    $checkifdispensed = PatBilling::where('flditemname',$checkifexist->fldbrandid)->get();
                    if(count($checkifdispensed) > 0){
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "You cannot edit this Itemname."
                        ]);
                    }
                }
                $data = [
                    'fldbrandid' => $newfldbrandid,
                    'fldextraid' => $request->fldextraid,
                    'fldbrand' => $request->fldbrand,
                    'fldpackvol' => $request->fldpackvol,
                    'fldvolunit' => $request->fldvolunit,
                    'fldmanufacturer' => $request->fldmanufacturer,
                    'flddepart' => $request->flddepart,
                    'flddetail' => $request->flddetail,
                    'fldstandard' => $request->fldstandard,
                    'fldminqty' => $request->fldminqty,
                    'fldmaxqty' => $request->fldmaxqty,
                    'fldleadtime' => $request->fldleadtime,
                    'fldactive' => $request->fldactive,
                    'fldtaxable' => $request->fldtaxable,
                    'fldtaxcode' => $request->fldtaxcode,
                    'fldmrp' => $request->fldmrp,
                    'fldcccharge' => $request->fldcccharge,
                    'fldcccharg_val' => $request->fldcccharg_val,
                    'flddiscountable_item' => $request->flddiscountable_item,
                    'fldinsurance' => $request->fldinsurance,
                    'fldrefundable' => $request->fldrefundable,
                ];
                // Rename demand item name
                $demandEntries = Demand::where('fldstockid',$checkifexist->fldbrandid)->get();
                foreach($demandEntries as $demandEntry){
                    $demandEntry->update([
                        'fldstockid' => $newfldbrandid
                    ]);
                }

                // Rename order table item name
                $orderEntries = Order::where('flditemname',$checkifexist->fldbrandid)->get();
                foreach($orderEntries as $orderEntry){
                    $orderEntry->update([
                        'flditemname' => $newfldbrandid
                    ]);
                }

                // Rename purchase table item name
                $purchaseEntries = Purchase::where('fldstockid',$checkifexist->fldbrandid)->get();
                foreach($purchaseEntries as $purchaseEntry){
                    $purchaseEntry->update([
                        'fldstockid' => $newfldbrandid
                    ]);
                }

                // Rename entry table item name
                $entries = Entry::where('fldstockid',$checkifexist->fldbrandid)->get();
                foreach($entries as $entry){
                    $entry->update([
                        'fldstockid' => $newfldbrandid
                    ]);
                }

                $update = ExtraBrand::where('fldbrandid', $fldbrandid)->update($data);
                if ($update) {
                    DB::commit();
                    return response()->json([
                        'status' => TRUE,
                        'message' => __('messages.update', ['name' => 'Item Brand']),
                        'data' => $this->getExtraBrandLists($request->fldextraid)
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Nothing to Update.',
                    ]);
                }
                DB::rollBack();
                return response()->json([
                    'status' =>  FALSE,
                    'message' => 'Brand Already Exist.',
                ]);
            }
            $data = [
                'fldbrandid' => $newfldbrandid,
                'fldextraid' => $request->fldextraid,
                'fldbrand' => $request->fldbrand,
                'fldpackvol' => $request->fldpackvol,
                'fldvolunit' => $request->fldvolunit,
                'fldmanufacturer' => $request->fldmanufacturer,
                'flddepart' => $request->flddepart,
                'flddetail' => $request->flddetail,
                'fldstandard' => $request->fldstandard,
                'fldminqty' => $request->fldminqty,
                'fldmaxqty' => $request->fldmaxqty,
                'fldleadtime' => $request->fldleadtime,
                'fldactive' => $request->fldactive,
                'fldtaxable' => $request->fldtaxable,
                'fldtaxcode' => $request->fldtaxcode,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldmrp' => $request->fldmrp,
                'fldcccharge' => $request->fldcccharge,
                'fldcccharg_val' => $request->fldcccharg_val,
                'flddiscountable_item' => $request->flddiscountable_item,
                'fldinsurance' => $request->fldinsurance,
                'fldrefundable' => $request->fldrefundable,
            ];
            ExtraBrand::insert($data);
            Helpers::logStack(["Pharmacy extra item created", "Event"], ['current_data' => $data]);
            DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Added Item Brand.',
                'data' => $this->getExtraBrandLists($request->fldextraid)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in extra item create', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteExtraItem(Request $request)
    {
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'extra-items-information', 'extra-items-information-delete'  ])  ) ?
            abort(403) : true ;
        try {
            $checkifexist = ExtraBrand::where('fldbrandid', $request->fldbrandid)->first();
            if (!$checkifexist) {
                Helpers::logStack(["Extra brand item not found in extra item delete", "Error"]);
                return response()->json([
                    'status' =>  FALSE,
                    'message' => 'Match Not Found.',
                ]);
            }

            $qtysum = Entry::where('fldstockid', $request->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
            if($qtysum > 0){
                Helpers::logStack(["Extra brand cannot be deleted in extra item delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => "Warning! You cannot delete this brand."
                ]);
            }
            ExtraBrand::where('fldbrandid', $request->fldbrandid)->delete();
            Helpers::logStack(["Extra item deleted", "Event"], ['previous_data' => $checkifexist]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully Deleted Item Brand.',
                'data' => $this->getExtraBrandLists($request->fldextraid)
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in extra item delete', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
