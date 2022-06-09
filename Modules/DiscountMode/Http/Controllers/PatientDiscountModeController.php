<?php

namespace Modules\DiscountMode\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\CustomDiscount;
use App\Discount;
use App\ExtraBrand;
use App\MedicineBrand;
use App\NoDiscount;
use App\Policies\PatienDiscountModePolicy;
use App\ServiceCost;
use App\SurgBrand;
use App\Surgical;
use App\User;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Routing\Controller;
use Nwidart\Modules\Routing\Controller;
use Auth;
use Barryvdh\Debugbar\Twig\Extension\Dump;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Modules\DiscountMode\Events\DiscountEvent;
use Illuminate\Validation\Rule;

/**
 * Class PatientDiscountModeController
 * @package Modules\DiscountMode\Http\Controllers
 */
class PatientDiscountModeController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        // $this->middleware('access-check:patient-discount-category');

    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function displayDiscountModeForm()
    {
       (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
         abort(403) : true ;
        $data['discountData'] = Discount::select('fldtype', 'fldmode', 'fldyear', 'fldamount', 'fldcredit', 'fldpercent', 'fldbillingmode', 'flduserid', 'fldtime','updated_by')->with('cogentUser')->get();
        $data['billingset'] = BillingSet::get();
        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = //$current_user->department->unique('flddept')->pluck('flddept')->toArray();
        $data['noDiscountList'] = ["Diagnostic Tests", "General Services", "Procedures", "Equipment", "Radio Diagnostics", "Other Items", "Medicines", "Surgicals", "Extra Items"];
        $data['existingNoDiscount'] = NoDiscount::select('flditemname')->get();

        return view('discountmode::patient-mode', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insertPatientMode(Request $request)
    {

        //Added by anish for fldtype validation
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        $validator = Validator::make($request->all(), [
                'fldtype' => 'required|unique:tbldiscount,fldtype',
                'fldmode' => "required|in:None,FixedPercent,CustomValues,Flexible,FlexibleWithLimit",
                'flddiscountlimit' => 'required_if:fldmode,===,FlexibleWithLimit',
            ],
            [
                'flddiscountlimit.required_if' => 'The discount limit field is required when Discount mode is Flexible With Limit.!',
                'flddiscountlimit.numeric' => 'The discount limit field should be number with in 1-100.!',
                'flddiscountlimit.between' => 'The discount limit field should be number with in 1-100.!',
                'fldmode.required' => 'Discount mode is required!',
                'fldmode.in' => 'Discount mode should be None, Fixed Percent, Custom Values, Flexible, or Flexible With Limit!',
            ]
        );


        if($validator->fails()){
            return redirect()->route('patient.discount.mode.form')->with('error', $validator->errors()->first())->withInput(Input::all());
        }

        try {

            if($request->fldtype == "FlexibleWithLimit" && !$request->flddiscountlimit)
                return redirect()->route('patient.discount.mode.form')->with('error', 'Something went wrong!');

            $dataInsert = [
                "fldbillingmode" => $request->fldbillingmode,
                "fldtype" => $request->fldtype,
                "fldmode" => $request->fldmode,
                "fldamount" => $request->fldamount,
                "fldpercent" => $request->fldpercent,
                "flddiscountlimit" => $request->flddiscountlimit??null,
                "fldcredit" => $request->fldcredit,
                //            "request_department_pharmacy" => "1OPD",
                "fldlab" => 0,
                "fldradio" => 0,
                "fldproc" => 0,
                "fldequip" => 0,
                "fldservice" => 0,
                "fldother" => 0,
                "fldmedicine" => 0,
                "fldsurgical" => 0,
                "fldextra" => 0,
                "fldregist" => 0,
                "flduserid" => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                "fldtime" => date("Y-m-d H:i:s"),
                "fldcomp" => Helpers::getCompName(),
                "fldyear" => $request->fldyear,
                "fldsave" => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            Discount::insert($dataInsert);
            return redirect()->route('patient.discount.mode.form')->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('patient.discount.mode.form')->with('error', 'Something went wrong!');
        }

    }

    /**
     * @param Request $request
     * @return string
     */
    public function listByDiscountGroup(Request $request)
    {
        // dd($request->all());
        try{
            $data['discountList'] = ServiceCost::select('tblservicecost.flditemname')
                ->where('tblservicecost.flditemtype', $request->discountGroupName)
                ->whereNotExists( function ($query) use ($request) {
                    $query
                    ->select(DB::raw(1))
                    ->from('tblcustdiscount')
                    ->whereRaw('tblservicecost.fldbillitem = tblcustdiscount.flditemname');
                    // ->where('tblcustdiscount.fldtype', '=', $request->discountLabel);

                })
                ->whereNotExists(function($q) use ($request){
                    $q
                    ->select(DB::raw(1))
                    ->from('tblnodiscount')
                    ->whereRaw('tblservicecost.flditemname = tblnodiscount.flditemname');
                    // ->where('tblnodiscount.fldtype', '=', $request->discountLabel);

                })
                ->get();

                // $data['discountList'] = ServiceCost::where('flditemtype', $request->discountGroupName)
                // ->whereDoesntHave('customeDiscounts', function($query) use ($request){
                //     return $query->where('tblcustdiscount.fldtype', $request->discountLabel);
                // })
                // ->get();
        // dd($data);
        $itemlist = view('discountmode::ajax-html-view.list-by-discount-group', $data)->render();

        $data['discountList'] = NoDiscount::select('flditemname')
                            ->where('flditemtype', $request->discountGroupName)
                            // ->where('fldtype', $request->discountLabel)
                            ->get();
        $nodiscountlist = view('discountmode::dynamic-view.no-discount-list', $data)->render();
        return response(['itemlist' => $itemlist, 'nodiscountlist' => $nodiscountlist ]) ;

        }catch(Exception $e)
        {
            return response(['errors' => $e->getMessage(), 'status' => $e->getCode(),  'line' => $e->getLine()],500);
        }

    }

    /**
     * @param Request $request
     * @return array|\Exception|string
     * @throws \Throwable
     */
    public function addByDiscountGroup(Request $request)
    {
        // dd($request->all());
        try {
            $dataInsert = [
                "flditemtype" => $request->discountGroup,
                "flduserid" => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                "fldtime" => date("Y-m-d H:i:s"),
                "fldcomp" => NULL,
                // 'fldtype' => $request->fldtype,
            ];
            if ($request->no_discount) {
                foreach ($request->no_discount as $item) {
                    $dataInsert['flditemname'] = $item;
                    //                NoDiscount::insert($dataInsert);
                    NoDiscount::firstOrCreate(
                        ['flditemname' => $item],
                        $dataInsert
                    );
                }
            }

            $data['discountList'] = NoDiscount::select('flditemname')
                        ->where('flditemtype', $request->discountGroup)
                        // ->where('fldtype', $request->fldtype)
                        ->get();
            $html = view('discountmode::dynamic-view.no-discount-list', $data)->render();
            return $html;
        } catch (\Exception $e) {
            return $e;
        }

    }

       /**
     * @param Request $request
     * @return array|\Exception|string
     * @throws \Throwable
     */
    public function removeByDiscountGroup(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->no_discount_remove) {
                NoDiscount::whereIn('flditemname', $request->no_discount_remove)->delete();
            }


        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        DB::commit();
        $data['discountList'] = NoDiscount::select('flditemname')->get();
        $html = view('discountmode::dynamic-view.no-discount-list', $data)->render();
        return $html;

    }

    /**
     * @param Request $request
     * @return array|\Exception|string
     * @throws \Throwable
     */
    public function deleteNoDiscount(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            NoDiscount::where('flditemname', $request->itemToDelete)->delete();
            $data['discountList'] = NoDiscount::select('flditemname')->get();
            $html = view('discountmode::dynamic-view.no-discount-list', $data)->render();
            return $html;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function editPatientMode(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
        abort(403) : true ;
        $data['discountData'] = Discount::select('fldtype', 'fldmode', 'fldyear', 'fldamount', 'fldcredit', 'fldpercent', 'fldyear', 'fldbillingmode', 'flddiscountlimit')->where('fldtype', $request->fldtype)->first();

        $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
        $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();
        $data['noDiscountList'] = ["Diagnostic Tests", "General Services", "Procedures", "Equipment", "Radio Diagnostics", "Other Items", "Medicines", "Surgicals", "Extra Items"];
        $data['existingNoDiscount'] = NoDiscount::select('flditemname')->get();
        $data['billingset'] = BillingSet::get();

        $html = view('discountmode::dynamic-view.update-discount', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePatientMode(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            DB::beginTransaction();
            $dataInsert = [
                "fldbillingmode" => $request->fldbillingmode,
                "fldtype" => $request->fldtype,
                "fldmode" => $request->fldmode,
                "fldamount" => $request->fldamount,
                "fldpercent" => $request->fldpercent ?? 0,
                "fldcredit" => $request->fldcredit,
                "flddiscountlimit" => $request->flddiscountlimit??null,
                //            "request_department_pharmacy" => "1OPD",
                "fldyear" => $request->fldyear,
            ];
            $discount = Discount::where('fldtype', $request->old_fldtype)->first();
            $discount->update($dataInsert);

            $event = event(new DiscountEvent($discount, Auth::guard('admin_frontend')->user()->id));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('patient.discount.mode.form')->with('error', 'Something went wrong!');
        }
        DB::commit();
        return redirect()->route('patient.discount.mode.form')->with('success', 'Item updated successfully!');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDiscountMode(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            Discount::where('fldtype', $request->fldtype)->delete();
            return redirect()->route('patient.discount.mode.form')->with('success', 'Discount mode deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('patient.discount.mode.form')->with('error', 'Something went wrong!');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function displayDiscountList(Request $request)
    {
        $data['html'] = $this->getDiscountList($request->discountLable);
        $data['specifics'] = Discount::select('fldtype', 'fldmode', 'fldlab', 'fldradio', 'fldproc', 'fldequip', 'fldservice', 'fldother', 'fldmedicine', 'fldsurgical', 'fldextra', 'fldregist')->where('fldtype', $request->discountLable)->first();

        return $data;
    }

    /**
     * @param $disLable
     * @return array|string
     * @throws \Throwable
     */
    public function getDiscountList($disLable)
    {
        $data['discountList'] = CustomDiscount::select('fldid', 'fldtype', 'flditemtype', 'flditemname', 'fldpercent')->where('fldtype', $disLable)->get();

        $html = view('discountmode::dynamic-view.list-custom-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function displayDiscountListByType(Request $request)
    {
        $type = $request->type;

        if ($type == 'medbrand') {
            $list = MedicineBrand::select('fldbrandid as col')->get();
        } elseif ($type == 'surgbrand') {
            $list = SurgBrand::select('fldbrandid as col')->get();
        } elseif ($type == 'extrabrand') {
            $list = ExtraBrand::select('fldbrandid as col')->get();
        } else {
            $list = ServiceCost::select('flditemname as col')->where('flditemtype', $type)->get();
        }

        $html = '';
        $html .= '<option value="">--Select--</option>';
        if ($list) {
            foreach ($list as $value) {
                $html .= '<option value="' . $value->col . '">' . $value->col . '</option>';
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return array|\Exception|string
     * @throws \Throwable
     */
    public function saveCustomDiscount(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            $dataCustomFields = [
                'fldtype' => $request->discountLable,
                'flditemname' => $request->itemName,
                'flditemtype' => $request->category,
                'fldpercent' => $request->customPercentage,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => date("Y-m-d H:i:s"),
                'fldcomp' => null
            ];
            CustomDiscount::insert($dataCustomFields);

            return $this->getDiscountList($request->discountLable);
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * @param Request $request
     * @return array|\Exception|string
     * @throws \Throwable
     */
    public function deleteCustomDiscountByType(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            CustomDiscount::where('fldid', $request->fldid)->delete();
            return $this->getDiscountList($request->fldtype);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param Request $request
     * @return \Exception
     */
    public function saveCustomDiscountSpecific(Request $request)
    {
        (!Permission::checkCanAccessSpecificMethodFromUrl(auth('admin_frontend')->user()->id, 'patient-discount-category')) ?
            abort(403) : true ;
        try {
            $dataCustomFields = [
                'fldlab' => $request->Laboratory,
                'fldradio' => $request->Radiology,
                'fldproc' => $request->Procedures,
                'fldequip' => $request->Equipment,
                'fldservice' => $request->GenServices,
                'fldother' => $request->Others,
                'fldmedicine' => $request->Medical,
                'fldsurgical' => $request->Surgical,
                'fldextra' => $request->ExtraItem,
                'fldregist' => $request->Registration,
            ];
            Discount::where('fldtype', $request->discountLable)->update($dataCustomFields);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
