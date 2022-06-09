<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\BulkSale;
use App\Demand;
use App\Entry;
use App\Order;
use App\PatBilling;
use App\Purchase;
use App\SurgBrand;
use App\SurgicalName;
use App\Surgical;
use App\SutureType;
use App\Transfer;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SurgicalController extends Controller
{
    public function surgical()
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'surgical-information', 'surgical-information-view'  ])  ) ?
            abort(403) : true ;

        $data['surgical_names'] = SurgicalName::orderBy('fldsurgname','asc')->get();
        $data['get_suture_variables'] = SurgicalName::select('fldid', 'fldsurgname as col')
                                                    ->where('fldsurgcateg', 'suture')
                                                    ->orderBy('fldid', 'DESC')
                                                    ->get();
        $data['get_related_suture_types'] = SutureType::select('fldid', 'fldsuturetype as type', 'fldsuturecode as code')
                                                        ->orderBy('fldsuturetype', 'DESC')
                                                        ->get();
        $data['tax_codes'] = \App\Utils\Medicinehelpers::getAllTaxGroup();
        return view('pharmacist::surgical', $data);
    }

    public function getSurgicalNameinfo(Request $request){
        try{
            $surgicalNameData = SurgicalName::where('fldid',$request->surgId)->with('surgicals.surgicalbrands')->first();
            $surgicalCategory = $surgicalNameData->fldsurgcateg;
            $html = $this->getSurgicalNameLists($request->surgId);
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'html' => $html,
                'surgicalCategory' => $surgicalCategory,
                'surgicalNameData' => $surgicalNameData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }

    public function getSurgicalNameLists($fldid){
        $surgicalNameData = SurgicalName::where('fldid',$fldid)->with('surgicals.surgicalbrands')->first();
        $html = '';
        foreach($surgicalNameData->surgicals as $surgical){
            $html .= '<ul class="list-group surgical">
                            <li class="list-group-item listmed-bak med-padding">
                                <div class="row selectsurgical" data-surgid="'.$surgical->fldsurgid.'" data-surgcateg="'.$surgical->fldsurgcateg.'" style="cursor: pointer;">
                                    <div class="col-sm-1">
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-sm-8" style="font-weight: 600;">
                                        '.$surgical->fldsurgid.'
                                    </div>
                                    <div class="col-sm-2 p-0 text-right">
                                        <a href="#" class="text-primary editsurgical" data-surgid="'.$surgical->fldsurgid.'" data-surgcateg="'.$surgical->fldsurgcateg.'" title="Edit '.$surgical->fldsurgid.'"> <i class="fa fa-edit"></i></a>&nbsp;
                                        <a href="#" class="text-danger deletesurgical" data-surgid="'.$surgical->fldsurgid.'" data-surgcateg="'.$surgical->fldsurgcateg.'" title="Delete '.$surgical->fldsurgid.'"> <i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>';
            foreach($surgical->surgicalbrands as $brand){
                $html .= '<ul class="list-group surg-brand-ul" style="display: none;">
                                <li class="list-group-item med-padding">
                                    <div class="row surg-brand editbrand" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'"  style="cursor: pointer;">
                                        <div class="col-sm-1 p-0 text-right">
                                        <i class="fa fa-dot-circle-o " aria-hidden="true"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            '.$brand->fldbrandid.'
                                        </div>
                                        <div class="col-sm-1 p-0 text-right">
                                            <a href="#" class="text-danger deletebrand" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'"> <i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function insertSurgicalVariable(Request $request)
    {
        try{
            $checkifexist = SurgicalName::where([
                'fldsurgcateg' => $request->fldsurgcateg,
                'fldsurgname' => $request->fldsurgname
            ])->first();
            if($checkifexist){
                Helpers::logStack(["Surgical variable already exist in surgical variable create", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Variable Already Exists.',
                ]);
            }
            $data = [
              'fldsurgcateg' => $request->fldsurgcateg,
              'fldsurgname' => $request->fldsurgname,
              'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $insert = SurgicalName::insertGetId($data);
            Helpers::logStack(["Surgical variable created", "Event"], ['current_data' => $data]);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Added Surgical Variable.',
                'insertId' => $insert
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical varible create', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteSurgicalVariable(Request $request)
    {
        try{
            $data = [
              'fldid' => $request->fldid,
            ];
            $checkifexist = SurgicalName::where($data)->first();
            if(!$checkifexist){
                Helpers::logStack(["Surgical varible not found in surgical varible delete", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }
            SurgicalName::where($data)->delete();
            Helpers::logStack(["Surgical varible deleted", "Event"], ['previous_data' => $checkifexist]);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Deleted Surgical Variable.',
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical varible delete', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function getSurgicalVariables()
    // {
    //     $fldsurgcateg = Input::get('fldsurgcateg');
    //     $get_related_variables = SurgicalName::select('fldid', 'fldsurgname as col')
    //     ->where('fldsurgcateg', $fldsurgcateg)
    //     ->orderBy('fldid', 'DESC')
    //     ->get();

    //     return response()->json($get_related_variables);
    // }

    public function insertSurgicalType(Request $request)
    {
        try{
            $data = [
                'fldsuturetype' => $request->fldsuturetype,
                'fldsuturecode' => $request->fldsuturecode,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $checkifexist = SutureType::where($data)->first();
            if($checkifexist){
                Helpers::logStack(["Surgical type already exist in surgical varible create", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Suture Codes Already Exists.',
                ]);
            }
            SutureType::insert($data);
            Helpers::logStack(["Surgical type created", "Event"], ['current_data' => $data]);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Added Suture Codes.',
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical type create', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteSurgicalType(Request $request)
    {
        try{
            $data = [
              'fldid' => $request->fldid,
            ];
            $checkifexist = SutureType::where($data)->first();
            if(!$checkifexist){
                Helpers::logStack(["Surgical type not found in surgical type delete", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }
            SutureType::where($data)->delete();
            Helpers::logStack(["Surgical type deleted", "Event"], ['previous_data' => $checkifexist]);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Deleted Suture Codes.',
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical type delete', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getSergicalTypes()
    {
        $get_related_suture_types = SutureType::select('fldid', 'fldsuturetype as type', 'fldsuturecode as code')
        ->orderBy('fldsuturetype', 'DESC')
        ->get();

        return response()->json($get_related_suture_types);
    }

    public function insertSurgical(Request $request)
    {
        try{
            $checkifSurgicalNameExist = SurgicalName::where('fldsurgname', $request->fldsurgname)->first();
            if(!$checkifSurgicalNameExist){
                Helpers::logStack(["Surgical name not found in surgical create", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Invalid Surgical Name Given.',
                ]);
            }

            if($request->fldsurgcateg == 'suture'){
                $fldsurgid = $request->fldsurgname . ' -'. $request->fldsurgsize . '('. $request->fldsurgtype .'-'. $request->fldsurgcode .')';
            }else{
                $fldsurgid = $request->fldsurgname . ' -'. $request->fldsurgsize . '('. $request->fldsurgtype .')';
            }

            $data = [
                'fldsurgid'     => $fldsurgid,
                'fldsurgname'   => $request->fldsurgname,
                'fldsurgcateg'  => $request->fldsurgcateg,
                'fldsurgsize'   => $request->fldsurgsize,
                'fldsurgtype'   => $request->fldsurgtype,
                'fldsurgcode'   => $request->fldsurgcode,
                'fldsurgdetail' => $request->fldsurgdetail,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $checkifexist = Surgical::where('fldsurgid', $fldsurgid)->first();
            if($checkifexist){
                Helpers::logStack(["Surgical already exist in surgical create", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Surgical Already Exists.',
                ]);
            }

            Surgical::insert($data);
            Helpers::logStack(["Surgical created", "Event"], ['current_data' => $data]);
            $html = $this->getSurgicalNameLists($request->fldid);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Added Surgical.',
                'html' => $html,
                'fldsurgid' => $fldsurgid
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical create', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function updateSurgical(Request $request)
    // {
    //     try{
    //         $data = [
    //             'fldsurgid'     => $request->fldsurgid,
    //             'fldsurgcateg'  => $request->fldsurgcateg,
    //             'fldsurgsize'   => $request->fldsurgsize,
    //             'fldsurgtype'   => $request->fldsurgtype,
    //             'fldsurgcode'   => $request->fldsurgcode,
    //             'fldsurgdetail' => $request->fldsurgdetail,
    //         ];

    //         $checkifexist = Surgical::where('fldsurgid', $request->fldsurgid)->first();
    //         if($checkifexist == null){
    //             return response()->json([
    //                 'status'=>  FALSE,
    //                 'message' => 'Match Did Not Found.',
    //             ]);
    //         }

    //         $update = Surgical::where('fldsurgid', $request->fldsurgid)->update($data);
    //         if($update){
    //             return response()->json([
    //               'status'=> TRUE,
    //               'message' => 'Successfully Updated Surgical.',
    //             ]);
    //         }else{
    //             return response()->json([
    //                 'status'=> FALSE,
    //                 'message' => 'Failed to Update Surgical.',
    //             ]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status'=> FALSE,
    //             'message' => __('messages.error'),
    //         ]);
    //     }
    // }

    public function deleteSurgical(Request $request)
    {

        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'surgical-information', 'surgical-information-delete'  ])  ) ?
            abort(403) : true ;
        try{
            $checkifexist = Surgical::where('fldsurgid', $request->fldsurgid)->first();
            if(!$checkifexist){
                Helpers::logStack(["Surgical not found in surgical delete", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }

            $checifSurgBrandExist = SurgBrand::where('fldsurgid', $request->fldsurgid)->first();
            if($checifSurgBrandExist){
                Helpers::logStack(["Surgical cannot be deleted in surgical delete", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Deletion Not Allowed.',
                ]);
            }

            Surgical::where('fldsurgid', $request->fldsurgid)->delete();
            Helpers::logStack(["Surgical deleted", "Event"], ['previous_data' => $checkifexist]);
            $html = $this->getSurgicalNameLists($request->fldid);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Deleted Surgical.',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical delete', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getSingleSurgicalData(Request $request)
    {
        try{
            $fldsurgid = $request->fldsurgid;
            $surgicalData = Surgical::select('fldsurgid', 'fldsurgname', 'fldsurgcateg', 'fldsurgsize', 'fldsurgtype', 'fldsurgcode', 'fldsurgdetail')
                                    ->where('fldsurgid', $fldsurgid)
                                    ->with('surgicalbrands')
                                    ->first();
            $brandHtml = $this->getBrandBySurgical($request);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfull.',
                'surgicalData' => $surgicalData,
                'brandHtml' => $brandHtml
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getBrandBySurgical($request){
        $brandHtml = '';
        $surgicalData = Surgical::where('fldsurgid', $request->fldsurgid)
                                ->with('surgicalbrands.entry')
                                ->first();
        if(isset($surgicalData)){
            $i = 0;
            foreach($surgicalData->surgicalbrands as $key=>$brand){
                $entries = Entry::where('fldstockid', $brand->fldbrandid)->get()->groupBy('fldbatch');
                if(count($entries) > 0){
                    foreach($entries as $entry){
                        ++$i;
                        $qtysum = $entry->sum('fldqty');
                        $status = ($brand->fldactive == "Active") ? "Active" : "Inactive";
                        $brandHtml .= '<tr class="row-links" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'">
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
                                            <a href="#" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                        </tr>';
                    }
                }else{
                    ++$i;
                    $brandHtml .= '<tr class="row-links" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'">
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
                                            <a href="#" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Delete '.$brand->fldbrandid.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                        </tr>';
                }
                // <a href="#" data-surgid="'.$brand->fldsurgid.'" data-brandid="'.$brand->fldbrandid.'" data-brand="'.$brand->fldbrand.'" title="Edit '.$brand->fldbrandid.'" class="editbrand text-primary"><i class="fa fa-edit"></i></a>&nbsp;
            }
        }
        return $brandHtml;
    }

    public function getSurgicalBrandData(Request $request){
        try{
            $surgicalData = Surgical::select('fldsurgid', 'fldsurgname', 'fldsurgcateg', 'fldsurgsize', 'fldsurgtype', 'fldsurgcode', 'fldsurgdetail')
                                    ->where('fldsurgid', $request->fldsurgid)
                                    ->with('surgicalbrands')
                                    ->first();
            $brandDetails = SurgBrand::where('fldbrandid', $request->fldbrandid)->first();
            $brandHtml = $this->getBrandBySurgical($request);
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'brandDetails' => $brandDetails,
                'surgicalData' => $surgicalData,
                'brandHtml' => $brandHtml
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }

    public function insertSurgicalBrand(Request $request)
    {
        /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'surgical-information', 'surgical-information-create'  ])  ) ?
            abort(403) : true ;
        DB::beginTransaction();
        try{
            $checkifSurgicalIdExist = Surgical::where('fldsurgid', $request->fldsurgid)->first();
            if(!$checkifSurgicalIdExist){
                DB::rollBack();
                Helpers::logStack(["Surgical brand already exist in surgical brand create", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Invalid Selected Surgical.',
                ]);
            }

            $newfldbrandid = $request->fldsurgid . '('. $request->fldbrand .')';
            $fldbrandid = $request->fldbrandid;

            $data = [
                'fldbrandid'        => $newfldbrandid,
                'fldsurgid'         => $request->fldsurgid,
                'fldbrand'          => $request->fldbrand,
                'fldmanufacturer'   => $request->fldmanufacturer,
                'flddetail'         => $request->flddetail,
                'fldstandard'       => $request->fldstandard,
                'fldmaxqty'         => $request->fldmaxqty,
                'fldminqty'         => $request->fldminqty,
                'fldleadtime'       => $request->fldleadtime,
                'fldactive'         => $request->fldactive,
                'fldtaxable'        => $request->fldtaxable,
                'fldtaxcode'        => $request->fldtaxcode,
                'fldmrp'            => $request->fldmrp,
                'fldcccharge'       => $request->fldcccharge,
                'fldcccharg_val'    => $request->fldcccharg_val,
                'flddiscountable_item'        => $request->flddiscountable_item,
                'fldinsurance'        => $request->fldinsurance,
                'fldrefundable'        => $request->fldrefundable,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $checkifexist = SurgBrand::where('fldbrandid', $fldbrandid)->first();
            if($checkifexist){
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

                }
                SurgBrand::where('fldbrandid', $fldbrandid)->update($data);
                $html = $this->getSurgicalNameLists($request->fldid);
                $brandHtml = $this->getBrandBySurgical($request);
                DB::commit();
                return response()->json([
                    'status'=> TRUE,
                    'message' => __('messages.update', ['name' => 'Surgical Brand']),
                    'html' => $html,
                    'brandHtml' => $brandHtml
                ]);
            }

            SurgBrand::insert($data);
            Helpers::logStack(["Surgical brand created", "Event"], ['current_data' => $data]);
            $html = $this->getSurgicalNameLists($request->fldid);
            $brandHtml = $this->getBrandBySurgical($request);
            DB::commit();
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Added Surgical Brand.',
                'html' => $html,
                'brandHtml' => $brandHtml
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in surgical brand create', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteSurgicalBrand(Request $request)
    {
        try{
            $checkifexist = SurgBrand::where('fldbrandid', $request->fldbrandid)->first();
            if(!$checkifexist){
                Helpers::logStack(["Surgical brand not found in surgical brand delete", "Error"]);
                return response()->json([
                    'status'=>  FALSE,
                    'message' => 'Data Not Found.',
                ]);
            }

            $qtysum = Entry::where('fldstockid', $request->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
            if($qtysum > 0){
                Helpers::logStack(["Surgical brand cannot be deleted in surgical brand delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => "Warning! You cannot delete this brand."
                ]);
            }
            SurgBrand::where('fldbrandid', $request->fldbrandid)->delete();
            Helpers::logStack(["Surgical brand deleted", "Event"], ['previous_data' => $checkifexist]);
            $html = $this->getSurgicalNameLists($request->fldid);
            $brandHtml = $this->getBrandBySurgical($request);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully Deleted Surgical Brand.',
                'brandHtml' => $brandHtml,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in surgical brand delete', "Error"]);
            return response()->json([
                'status'=> FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
