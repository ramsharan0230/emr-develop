<?php

namespace Modules\BloodBank\Http\Controllers;

use App\BagMaster;
use App\Bloodbag;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ResultauthorizationController extends Controller
{
    public function index()
    {
        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data ['bag_types'] = BagMaster::select('description', 'id')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        return view('bloodbank::result-authorization', $data);
    }

    public function search(Request $request)
    {

        $from_date = Helpers::dateNepToEng($request->from_date)->full_date;
        $to_date = Helpers::dateNepToEng($request->to_date)->full_date;
        if (!$from_date || !$to_date) {
            return redirect()->route('bloodbank.result-authorization.index')->with('error', 'Please enter From date abd to date');
        }
        try {
            $query = Bloodbag::with('donor')->where('created_at', '>=', $from_date)->where('created_at', '<', $to_date);
            if ($request->branch) {
                $data = $query->where('branch_id', $request->branch)->get();
            }
            dd($data);
//            if ($request->from_bag_no && $request->branch) {
//                $data = Bloodbag::with('donor')->where('id', '>=', $request->from_bag_no)->where('id', '<', $request->to_bag_date)->get();
//            }
//            if ($request->to_bag_date && $request->from_bag_no && $request->branch) {
//
//                $data = Bloodbag::with('donor')->where('id', '>=', $request->from_bag_no)->where('id', '<', $request->to_bag_date)->where('branch_id',$request->branch)->get();
//            }

        } catch (\Exception $exception) {
            dd($exception);
            \Log::info($exception->getMessage());
            return redirect()->route('bloodbank.result-authorization.index')->with('error', 'Something went wrong');
        }
    }

}
