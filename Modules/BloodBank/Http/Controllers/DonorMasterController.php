<?php

namespace Modules\BloodBank\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\DonorMaster;

class DonorMasterController extends Controller
{
    public function index(Request $request)
    {
        $errors = [];
        if ($request->isMethod('post')) {

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                "branch" => ['required'],
                "registration_date" => ['required'],
                "title" => ['required'],
                "fullname" => ['required'],
                "blood_group" => ['required'],
                "rh_type" => ['required'],
                "gender" => ['required'],
                "dob" => ['required'],
                "temp_country" => ['required'],
                "temp_state" => ['required'],
                "temp_city" => ['required'],
                "mobile" => ['nullable'],
                "phone" => ['nullable'],
                "email" => ['nullable', 'email'],
                "prem_country" => ['required'],
                "prem_state" => ['required'],
                "prem_city" => ['required'],
                "type" => ['required'],
                "last_donated" => ['required'],
                "remarks" => ['required'],
            ]);

            if ($validator->fails()) {
                \Log::info($validator->getMessageBag()->messages());
                $errors = [];
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $errors[$key] = $value[0];
            } else {
                \DB::beginTransaction();
                try {
                    $donorNo = \App\Utils\Helpers::getNextAutoId('DonorNo', TRUE);
                    $messsage = __('Donor master added successfully with donor number: ' . $donorNo);
                    DonorMaster::create([
                        'donor_no' => $donorNo,
                        "branch_id" => $request->get("branch"),
                        "registration_date" => $request->get("registration_date"),
                        "title" => $request->get("title"),
                        "fullname" => $request->get("fullname"),
                        "blood_group" => $request->get("blood_group"),
                        "rh_type" => $request->get("rh_type"),
                        "gender" => $request->get("gender"),
                        "dob" => $request->get("dob"),
                        "temp_country" => $request->get("temp_country"),
                        "temp_state" => $request->get("temp_state"),
                        "temp_city" => $request->get("temp_city"),
                        "mobile" => $request->get("mobile"),
                        "phone" => $request->get("phone"),
                        "email" => $request->get("email"),
                        "prem_country" => $request->get("prem_country"),
                        "prem_state" => $request->get("prem_state"),
                        "prem_city" => $request->get("prem_city"),
                        "type" => $request->get("type"),
                        "last_donated" => $request->get("last_donated"),
                        "remarks" => $request->get("remarks"),
                    ]);
                    \DB::commit();

                    return redirect()->route('bloodbank.donor-master.index')->with('success', $messsage);
                } catch (\Exception $e) {
                    \DB::rollBack();
                    Helpers::logStack([$e->getMessage() . ' in doner master create', "Error"]);
                    session()->flash('error_message', __('Error while adding Donor master'));
                }
            }
        }

        return view('bloodbank::donormaster', [
            'form_errors' => $errors,
            'hospitalbranches' => \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get(),
        ]);
    }

    public function searchPatient(Request $request)
    {
        $text = $request->get('text');
        return response()->json(
            \App\PatientInfo::select("tblpatientinfo.fldtitle", "tblpatientinfo.fldbloodgroup", "tblpatientinfo.fldptbirday", "tblpatientinfo.fldptsex", "tblpatientinfo.fldptnamefir", "tblpatientinfo.fldptnamelast", "tblpatientinfo.fldptcontact", "tblpatientinfo.fldemail", "tblpatientinfo.fldmidname", "tblpatientinfo.fldcountry", "tblpatientinfo.fldprovince", "tblpatientinfo.fldmunicipality", "tblpatientinfo.fldwardno", "tblpatientinfo.fldmaritalstatus", "tblencounter.fldencounterval")
                ->leftJoin("tblencounter", "tblencounter.fldpatientval", "=", "tblpatientinfo.fldpatientval")
                ->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $text . '%')
                ->orWhere("tblpatientinfo.fldpatientval", "like", "%$text%")
                ->orWhere("tblencounter.fldencounterval", "like", "%$text%")
                ->first()
        );
    }
}
