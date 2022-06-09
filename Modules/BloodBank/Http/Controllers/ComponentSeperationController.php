<?php

namespace Modules\BloodBank\Http\Controllers;

use App\BagMaster;
use App\Bloodbag;
use App\ComponentDetais;
use App\Componentseperation;
use App\Consent;
use App\DonorMaster;
use App\Test;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ComponentSeperationController extends Controller
{

    public function index(Request $request)
    {
        $errors = [];
        if ($request->isMethod('post')) {


            try {
                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    "bag_no" => ['required'],
                    "bag_type" => ['required'],
                    "component" => ['required'],
                    "volume" => ['required'],
                    "date" => ['required'],
                    "time" => ['required'],
                ]);
                if ($validator->fails()) {
                    \Log::info($validator->getMessageBag()->messages());
                    $errors = [];
                    foreach ($validator->getMessageBag()->messages() as $key => $value)
                        $errors[$key] = $value[0];

                } else {
                    $component = Componentseperation::create([
                        "bag_no" => $request->get('bag_no'),
                        "bag_id" => $request->get('bag_type'),
                    ]);
                    if ($request->component && is_array($request->component)) {
                        foreach ($request->component as $k => $compnt_detail) {
                            $component_details = [
                                'component_id' => $component->id,
                                'component_name' => $compnt_detail,
                                'volume' =>$request->volume[$k],
                                'date' => $request->date[$k] ? \App\Utils\Helpers::dateNepToEng($request->date[$k])->full_date :'',
                                'time' =>$request->time[$k],
                            ];

                            ComponentDetais::create($component_details);
                        }
                    }
                    return redirect()->route('bloodbank.component-separation.index')->with('success', 'Saved successfully');
                }

            } catch (\Exception $exception) {
                \Log::info($exception->getMessage());
                return redirect()->route('bloodbank.component-separation.index')->with('error_message', 'something went wrong');
            }

        }

        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data['tests'] = Test::select('fldtestid')->distinct('fldtestid')->get();
        $data ['bag_types'] = BagMaster::select('description', 'id')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $data['form_errors'] = $errors;

        return view('bloodbank::component-separation', $data);
    }

    public function searchPatient(Request $request)
    {
        $search_value = $request->search_value;
        if (!$search_value) {
            return \response()->json(['error' => 'Please enter search']);
        }
        if ($search_value) {
//            dd(Bloodbag::with('donor','donor.consent')->where('id', $search_value)->first());
            return \response()->json(Bloodbag::with('donor', 'donor.consent')->where('id', $search_value)->first());

        }

    }


}
