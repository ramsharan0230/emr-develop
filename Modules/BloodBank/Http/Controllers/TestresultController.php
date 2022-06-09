<?php

namespace Modules\BloodBank\Http\Controllers;

use App\BagMaster;
use App\Bloodbag;
use App\DonorMaster;
use App\Testresult;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class TestresultController extends Controller
{
    public function index(Request $request)
    {

        $errors = [];
        if ($request->isMethod('post')) {


            try {
                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    "branch" => ['required'],
                    "bag_date" => ['required'],
                    "bag_no" => ['required'],
                    "donor" => ['required'],
                    "donor_detail" => ['required'],
                    "test-status" => ['required'],
                ]);
                if ($validator->fails()) {
                    \Log::info($validator->getMessageBag()->messages());
                    $errors = [];
                    foreach ($validator->getMessageBag()->messages() as $key => $value)
                        $errors[$key] = $value[0];

                } else {

                    Testresult::create([
                        "branch_id" => $request->get('branch'),
                        "donor_id" => $request->get('donor') ,
                        "bag_no" =>  $request->get('bag_no'),
                        "bag_date" => $request->get('bag_date') ? \App\Utils\Helpers::dateNepToEng($request->get('bag_date'))->full_date :'',
                        "donor_info" =>  $request->get('donor_detail'),
                        "status" =>  $request->get('test-status'),
                    ]);
                    return redirect()->route('bloodbank.test-result.index')->with('success', 'Saved successfully');
                }

            }catch (\Exception $exception){
                \Log::info($exception->getMessage());
                return redirect()->route('bloodbank.test-result.index')->with('error', 'something went wrong');
            }

        }

        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data ['bag_types'] = BagMaster::select('description', 'id')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        return view('bloodbank::test-result', $data);
    }

    public function search(Request $request)
    {

        $search_val = $request->search_value;
        $search_type = $request->search_type;
        if (!$search_val) {
            return \response()->json(['error', 'Please enter donor or bag no']);
        }

        if ($search_type == 'donor') {
            return \response()->json(['donor' => DonorMaster::with('bloodbag')->select('branch_id','donor_no', 'fullname')->where('donor_no', $search_val)->first()]);
        }

        if ($search_type == 'bag') {
            return \response()->json(['bag'=>Bloodbag::with('donor')->select('donor_id', 'branch_id','bag_id','bag_date')->where('id', $search_val)->first()]);
        }
    }

}
