<?php

namespace Modules\BloodBank\Http\Controllers;

use App\BagMaster;
use App\Bloodbag;
use App\Consent;
use App\DonorMaster;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BloodBagGenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $errors = [];
        if ($request->isMethod('post')) {


            try {
                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    "branch" => ['required'],
                    "blood_group" => ['required'],
                    "rh_type" => ['required'],
                    "mobile" => ['nullable'],
                    "bag_date" => ['required'],
                    "bag_no" => ['required'],
                    "consent_date" => ['required'],
                    "consent_no" => ['required'],
                    "donor" => ['required'],
                    "donation_type" => ['required'],
                    "bag_type" => ['required'],
                    "tube_id" => ['required'],
                    "collect_date" => ['required'],
                    "start_time" => ['required'],
                    "end_time" => ['required'],
                ]);
                if ($validator->fails()) {
                    \Log::info($validator->getMessageBag()->messages());
                    $errors = [];
                    foreach ($validator->getMessageBag()->messages() as $key => $value)
                        $errors[$key] = $value[0];

                } else {

                    Bloodbag::create([
                        "branch_id" => $request->get('branch'),
                        "blood_group" => $request->get('blood_group') ,
                        "rh_type" =>  $request->get('rh_type'),
                        "mobile" => $request->get('mobile') ,
                        "bag_date" =>  $request->get('bag_date') ? \App\Utils\Helpers::dateNepToEng($request->get('bag_date'))->full_date :'',
                        "bag_no" =>  $request->get('bag_no'),
                        "consent_date" =>  $request->get('consent_date') ? \App\Utils\Helpers::dateNepToEng($request->get('consent_date'))->full_date :'',
                        "consent_id" => $request->get('consent_no') ,
                        "donor_id" => $request->get('donor') ,
                        "donation_type" => $request->get('donation_type') ,
                        "bag_id" => $request->get('bag_type') ,
                        "tube_id" => $request->get('tube_id') ,
                        "collect_date" =>  $request->get('collect_date') ? \App\Utils\Helpers::dateNepToEng($request->get('collect_date'))->full_date :'',
                        "start_time" => $request->get('start_time'),
                        "end_time" =>  $request->get('end_time'),
                    ]);
                    return redirect()->route('bloodbank.blood-bag.index')->with('success', 'Saved successfully');
                }

            }catch (\Exception $exception){
                \Log::info($exception->getMessage());
                return redirect()->route('bloodbank.blood-bag.index')->with('error', 'something went wrong');
            }

        }

        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data ['bag_types'] = BagMaster::select('description', 'id')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $data['form_errors'] = $errors;
        return view('bloodbank::blood-bag-generation', $data);
    }


    public function searchPatient(Request $request)
    {
//        $search_type = $request->search_type;
        $search_value = $request->search_value;
        if (!$search_value) {
            return \response()->json(['error' => 'Please enter consent no to search']);
        }

        if ($search_value) {
            $consent = Consent::with('donor')->where('id', $search_value)->where('is_accepted',1)->first();
            if($consent){
                return \response()->json($consent);
            }else{
                return \response()->json(['error' =>'Consent is Rejected']);
            }

        }


    }
}
