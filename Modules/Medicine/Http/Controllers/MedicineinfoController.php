<?php

namespace Modules\Medicine\Http\Controllers;

use App\BulkSale;
use App\Code;
use App\Demand;
use App\Drug;
use App\Entry;
use App\Label;
use App\MedicineBrand;
use App\Order;
use App\PatBilling;
use App\Purchase;
use App\Transfer;
use App\Utils\Helpers;
use App\Utils\Permission;
use Barryvdh\Debugbar\Twig\Extension\Dump;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class MedicineinfoController extends Controller
{
    public function index(){
         /**
         * restric access method if user have not permission to access
         * @param auth->user()->id, Array of Permission
         * @retunr 403 status nad boolean mixed
         */
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, [ 'medicine-information', 'medicine-information-view' ])  ) ?
            abort(403) : true ;
        $data = [];
        $data['codes'] = \App\Utils\Medicinehelpers::getAllCodes();
        $data['dosageforms'] = \App\Utils\Medicinehelpers::getAllDosageForms();
        $data['tax_codes'] = \App\Utils\Medicinehelpers::getAllTaxGroup();
        return view('medicine::medicineinfo.medicine',$data);
    }

    public function addDrug(Request $request)
    {
        DB::beginTransaction();
        try {
            $html = '';
            $request['flddrug'] = $drugname = $request->fldcodename . ' -' . $request->fldstrength . $request->fldstrunit;
            $request['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $chkDrug = Drug::where('flddrug', $request->flddrug)->first();
            if($chkDrug){
                $rules = [
                    'flddrug' => 'required|unique:tbldrug,flddrug,' . $request->flddrug . ',flddrug',
                    'fldcodename' => 'required',
                    'fldstrength' => 'required',
                    'fldstrunit' => 'required'
                ];
            }else{
                $rules = [
                    'flddrug' => 'required|unique:tbldrug',
                    'fldcodename' => 'required',
                    'fldstrength' => 'required',
                    'fldstrunit' => 'required'
                ];
            }
            $request->validate($rules, [
                'flddrug.unique' => 'drug name already exist',
                'fldcodename.required' => 'Generic Name field is required',
                'fldstrength' => 'Strength field is required',
                'fldstrunit' => 'Unit field is required'
            ]);
            $requestdata = $request->all();
            unset($requestdata['_token']);
            if($chkDrug){
                Drug::where('flddrug', $request->flddrug)->update($requestdata, ['timestamps' => false]);
                Helpers::logStack(["Medicine detail updated", "Event"], ['current_data' => $requestdata, 'previous_data' => $chkDrug]);
            }else{
                Drug::insert($requestdata);
                Helpers::logStack(["Medicine detail created", "Event"], ['current_data' => $requestdata]);
            }
            $html = json_decode(json_encode($this->getMedicineByGeneric($request)->getData()), true)['html'];
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Drug saved successfully',
                'html' => $html,
                'drugname' => $drugname
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in medicine detail create/update', "Error"]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteDrug(Request $request)
    {
        DB::beginTransaction();
        try {
            $html = '';
            $message = '';
            $flddrug = $request->flddrug;
            $drug = Drug::where('flddrug', $flddrug)->first();
            if (!$drug) {
                Helpers::logStack(["Medicine not found in medicine delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => 'An Error has occured!',
                ]);
            }
            if(isset($drug->MedicineBrand)){
                Helpers::logStack(["Medicine cannot be deleted in medicine delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => 'Warning! Cannot delete this drug.',
                ]);
            }
            $message = $drug->flddrug . ' deleted sucessfully';
            foreach($drug->MedicineBrand as $medicine_brand){
                DB::table('tblmedbrand')->where('fldbrandid', $medicine_brand->fldbrandid)->delete();
            }
            DB::table('tbldrug')->where('flddrug', $flddrug)->delete();
            Helpers::logStack(["Medicine deleted", "Event"], ['previous_data' => $drug]);
            $html = json_decode(json_encode($this->getMedicineByGeneric($request)->getData()), true)['html'];
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => $message,
                'html' => $html,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in medicine detail delete', "Error"]);
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured!',
            ]);
        }
    }

    public function addLabels(Request $request)
    {
        DB::beginTransaction();
        try {
            $request['fldlabel'] = $request->flddrug;
            $request['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $chkLabel = Label::where('fldlabel', $request->flddrug)->first();
            if(isset($chkLabel)){
                $rules = [
                    'fldlabel' => 'required|unique:tbllabel,fldlabel,' . $request->flddrug . ',fldlabel'
                ];
            }else{
                $rules = [
                    'fldlabel' => 'required|unique:tbllabel'
                ];
            }
            $request->validate($rules, [
                'fldlabel.unique' => 'Label already exist'
            ]);
            $requestdata = $request->all();
            unset($requestdata['_token']);
            if(isset($chkLabel)){
                Label::where('fldlabel', $request->flddrug)->update($requestdata, ['timestamps' => false]);
                Helpers::logStack(["Drug label updated", "Event"], ['current_data' => $requestdata, 'previous_data' => $chkLabel]);
            }else{
                Label::insert($requestdata);
                Helpers::logStack(["Drug label created", "Event"], ['current_data' => $requestdata]);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Label saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in medicine label create/update', "Error"]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    // public function deleteLabels($flddrug, $fldlabel)
    // {
    //     try {
    //         $fldlabel = decrypt($fldlabel);
    //         $flddrug = decrypt($flddrug);
    //         $label = Label::where('fldlabel', $fldlabel)->first();

    //         if ($label) {

    //             DB::table('tbllabel')->where('fldlabel', $fldlabel)->delete();

    //             Session::flash('success_message', $label->fldlabel . ' label deleted sucessfully');
    //         }
    //     } catch (\Exception $e) {
    //         $error_message = $e->getMessage();
    //         $error_message = 'Sorry something went wrong';

    //         Session::flash('error_message', $error_message);
    //     }

    //     return redirect()->route('medicines.medicineinfo.labels', encrypt($flddrug));
    // }

    public function getBrandDetails(Request $request)
    {
        try{
            $fldbrandid  = $request->fldbrandid;
            $flddrug  = $request->flddrug;
            $drugDetails = Drug::where('flddrug', $flddrug)->with('MedicineBrand.entry','Label')->first();
            $brandDetails = MedicineBrand::where('fldbrandid', $fldbrandid)->with('label')->first();
            $brandHtml = $this->getBrandByMedicine($flddrug);
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'brandDetails' => $brandDetails,
                'drugDetails' => $drugDetails,
                'brandHtml' => $brandHtml
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }

    public function addBrandInfo(Request $request){
        DB::beginTransaction();
        try {
            $fldcodename = $request->fldcodename;
            $request['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $brandHtml = '';
            unset($request['fldcodename']);
            unset($request['_token']);
            $requestdata = $request->all();
            // $fldbrandid = $request->flddrug . '('. $request->fldbrand .')';
            $fldbrandid = $request->fldbrandid;
            $checkifexist = MedicineBrand::where('fldbrandid', $fldbrandid)->first();
            if($checkifexist != null){
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
                $request->validate([
                    // 'fldbrandid' => 'required|unique:tblmedbrand,fldbrandid,' . $fldbrandid . ',fldbrandid',
                    'flddrug' => 'required',
                    'fldbrand' => 'required',
                    'fldpackvol' => 'numeric',
                    'fldmrp' => 'required',
                    'fldcccharge' => 'required|in:fldcccharge_amt,fldcccharge_percent',
                    'fldcccharg_val' => 'required|numeric',
                ], [
                    // 'fldbrandid.unique' => 'Label already exist',
                    'flddrug.required' => 'Drug is required',
                    'fldbrand.required' => 'Brand is required',
                    'fldpackvol.numeric' => 'Pack Volume field must be number',
                    'fldmrp' => 'MRP is required',
                    'fldcccharge.in' => 'CC Charge must be either amount or percentage',
                    'fldcccharg_val' => 'CC charge must must be numeric',
                ]);

                $requestdata['fldbrandid'] = $newfldbrandid = $request->flddrug . '('. $request->fldbrand .')';

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

                $medicineBrand = MedicineBrand::where('fldbrandid', $fldbrandid)->first();
                $medicineBrand->update($requestdata, ['timestamps' => false]);
                Helpers::logStack(["Medicine brand updated", "Event"], ['current_data' => $requestdata, 'previous_data' => $medicineBrand]);
            }else{
                $requestdata['fldbrandid'] = $request->flddrug . '(' . strtoupper($request->fldbrand) . ')';
                $request->validate([
                    // 'fldbrandid' => 'required|unique:tblmedbrand',
                    'flddrug' => 'required',
                    'fldbrand' => 'required',
                    'fldpackvol' => 'numeric',
                    'fldmrp' => 'required',
                    'fldcccharge' => 'required|in:fldcccharge_amt,fldcccharge_percent',
                    'fldcccharg_val' => 'required|numeric',
                ], [
                    'fldbrandid.unique' => 'Label already exist',
                    'flddrug.required' => 'Drug is required',
                    'fldbrand.required' => 'Brand is required',
                    'fldpackvol.numeric' => 'Pack Volume field must be number',
                    'fldmrp' => 'MRP is required',
                    'fldcccharge.in' => 'CC Charge must be either amount or percentage',
                    'fldcccharg_val' => 'CC charge must must be numeric',
                ]);
                MedicineBrand::insert($requestdata);
                Helpers::logStack(["Medicine brand created", "Event"], ['current_data' => $requestdata]);
            }
            $brandHtml .= $this->getBrandByMedicine($request->flddrug);
            $custRequest = new Request([
                'fldcodename'   => $fldcodename
            ]);
            $html = json_decode(json_encode($this->getMedicineByGeneric($custRequest)->getData()), true)['html'];
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Brand saved successfully',
                'brandHtml' => $brandHtml,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in medicine brand create/update', "Error"]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteBrandInfo(Request $request)
    {
        try {
            $message = '';
            $brandHtml = '';
            $fldbrandid = $request->fldbrandid;
            $brand = MedicineBrand::where('fldbrandid', $fldbrandid)->first();
            if (!$brand) {
                Helpers::logStack(["Medicine brand not found in medicine brand delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => "Data not found."
                ]);
            }
            $qtysum = Entry::where('fldstockid', $brand->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
            if($qtysum > 0){
                Helpers::logStack(["Medicine brand cannot be deleted in medicine brand delete", "Error"]);
                return response()->json([
                    'status' => false,
                    'message' => "Warning! You cannot delete this brand."
                ]);
            }
            $message = $brand->fldbrandid . ' brand deleted sucessfully from ' . $brand->flddrug . ' Drug';
            DB::table('tblmedbrand')->where('fldbrandid', $fldbrandid)->delete();
            Helpers::logStack(["Medicine brand deleted", "Event"], ['previous_data' => $brand]);
            $brandHtml .= $this->getBrandByMedicine($brand->flddrug);
            $custRequest = new Request([
                'fldcodename'   => $request->fldcodename
            ]);
            $html = json_decode(json_encode($this->getMedicineByGeneric($custRequest)->getData()), true)['html'];
            return response()->json([
                'status' => true,
                'message' => $message,
                'brandHtml' => $brandHtml,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in medicine brand delete', "Error"]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getBrandByMedicine($flddrug){
        $brandHtml = '';
        $drugDetails = Drug::where('flddrug', $flddrug)->with('MedicineBrand')->first();
        if(isset($drugDetails)){
            $i = 0;
            foreach($drugDetails->MedicineBrand as $key=>$brand){
                $entries = Entry::where('fldstockid', $brand->fldbrandid)->get()->groupBy('fldbatch');
                if(count($entries) > 0){
                    foreach($entries as $entry){
                        ++$i;
                        $qtysum = $entry->sum('fldqty');
                        $status = ($brand->fldactive == "Active") ? "Active" : "Inactive";
                        $brandHtml .= '<tr class="row-links" data-drug="'.$flddrug.'" data-brandid="'.$brand->fldbrandid.'">
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
                                            <a href="#" data-drug="'.$flddrug.'" data-brandid="'.$brand->fldbrandid.'" title="Delete '.$brand->fldbrand.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                        </tr>';
                    }
                }else{
                    ++$i;
                    $brandHtml .= '<tr class="row-links" data-drug="'.$flddrug.'" data-brandid="'.$brand->fldbrandid.'">
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
                                            <a href="#" data-drug="'.$flddrug.'" data-brandid="'.$brand->fldbrandid.'" title="Delete '.$brand->fldbrand.'" class="deletebrand text-danger"><i class="ri-delete-bin-5-fill"></i></a>
                                        </td>
                                    </tr>';
                }
                // <a href="#" data-drug="'.$request->flddrug.'" data-brandid="'.$brand->fldbrandid.'" title="Edit '.$brand->fldbrand.'" class="editbrand text-primary"><i class="fa fa-edit"></i></a>&nbsp;
            }
        }
        return $brandHtml;
    }

    public function getMedicineByGeneric(Request $request){
        try {
            $medicines = Drug::select('flddrug','fldcodename')
                            ->where('fldcodename',$request->fldcodename)
                            ->with('MedicineBrand')
                            ->get();
            $html = '';
            foreach($medicines as $medicine){
                $html .= '<ul class="list-group medicine">
                            <li class="list-group-item listmed-bak med-padding">
                                <div class="row selectmedicine" data-codename="'.$medicine->fldcodename.'" data-drug="'.$medicine->flddrug.'" style="cursor: pointer;">
                                    <div class="col-sm-1">
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-sm-8" style="font-weight: 600;">
                                        '.$medicine->flddrug.'
                                    </div>
                                    <div class="col-sm-2 p-0 text-right">
                                        <a href="#" class="text-primary  editmedicine mr-2" data-codename="'.$request->fldcodename.'" data-drug="'.$medicine->flddrug.'" title="Edit '.$medicine->flddrug.'"> <i class="fa fa-edit"></i></a>&nbsp;
                                        <a href="#" class="text-danger deletemedicine" data-codename="'.$request->fldcodename.'" data-drug="'.$medicine->flddrug.'" title="Delete '.$medicine->flddrug.'"> <i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>';
                foreach($medicine->MedicineBrand as $medicine_brand){
                    $html .= '<ul class="list-group med-brand-ul" style="display: none;">
                                <li class="list-group-item med-padding">
                                    <div class="row medicine-brand editbrand" data-drug="'.$medicine->flddrug.'" data-brandid="'.$medicine_brand->fldbrandid.'"  style="cursor: pointer;">
                                        <div class="col-sm-1 p-0 text-right">
                                        <i class="fa fa-dot-circle-o " aria-hidden="true"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            '.$medicine_brand->fldbrandid.'
                                        </div>
                                        <div class="col-sm-1 p-0 text-right">
                                            <a href="#" class="text-danger deletebrand" data-drug="'.$medicine->flddrug.'" data-brandid="'.$medicine_brand->fldbrandid.'" title="Delete '.$medicine_brand->fldbrandid.'"> <i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </li>
                                </ul>';
                }
                $html .= '</ul>';
            }
            // foreach($medicines as $medicine){
            //     $html .= '<ul class="list-group medicine">
            //                 <li class="list-group-item listmed-bak med-padding">
            //                     <div class="row selectmedicine" data-codename="'.$medicine->fldcodename.'" data-drug="'.$medicine->flddrug.'" style="cursor: pointer;">
            //                         <div class="col-sm-1">
            //                             <i class="fas fa-angle-right"></i>
            //                         </div>
            //                         <div class="col-sm-8" style="font-weight: 600;">
            //                             '.$medicine->flddrug.'
            //                         </div>
            //                         <div class="col-sm-2 p-0 text-right">
            //                             <a href="#" class="text-primary editmedicine" data-codename="'.$request->fldcodename.'" data-drug="'.$medicine->flddrug.'" title="Edit '.$medicine->flddrug.'"> <i class="fa fa-edit"></i></a>&nbsp;
            //                             <a href="#" class="text-danger deletemedicine" data-codename="'.$request->fldcodename.'" data-drug="'.$medicine->flddrug.'" title="Delete '.$medicine->flddrug.'"> <i class="fa fa-trash"></i></a>
            //                         </div>
            //                     </div>
            //                 </li>';
            //     foreach($medicine->MedicineBrand as $medicine_brand){
            //         $html .= '<ul class="list-group med-brand-ul" style="display: none;">
            //                     <li class="list-group-item med-padding">
            //                         <div class="row medicine-brand editbrand" data-drug="'.$medicine->flddrug.'" data-brandid="'.$medicine_brand->fldbrandid.'"  style="cursor: pointer;">
            //                             <div class="col-sm-1 p-0 text-right">
            //                             <i class="fa fa-caret-right" aria-hidden="true"></i>
            //                             </div>
            //                             <div class="col-sm-9">
            //                                 '.$medicine_brand->fldbrandid.'
            //                             </div>
            //                             <div class="col-sm-1 p-0 text-right">
            //                                 <a href="#" class="text-danger deletebrand" data-drug="'.$medicine->flddrug.'" data-brandid="'.$medicine_brand->fldbrandid.'" title="Delete '.$medicine_brand->fldbrandid.'"> <i class="fa fa-trash"></i></a>
            //                             </div>
            //                         </div>
            //                     </li>
            //                     </ul>';
            //     }
            //     $html .= '</ul>';
            // }
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }

    public function getMedicineDetails(Request $request){
        try{
            $flddrug = $request->drugName;
            $drugDetails = Drug::where('flddrug', $flddrug)->with('MedicineBrand.entry','Label')->first();
            $brandHtml = $this->getBrandByMedicine($flddrug);
            return response()->json([
                'status' => true,
                'message' => 'Successfull',
                'drugDetails' => $drugDetails,
                'brandHtml' => $brandHtml
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An Error has occured.'
            ]);
        }
    }
}
