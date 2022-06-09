<?php

namespace Modules\BloodBank\Http\Controllers;

use App\Consent;
use App\Consentquestionanswer;
use App\DonorMaster;
use App\QuestionMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ConsentFormController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        if ($request->isMethod('post')) {
            $this->store($request);
        }

        return view('bloodbank::consent-form', [
            'hospitalbranches' => \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get(),
            'questions' => QuestionMaster::whereNull('parent_id')
                ->with([
                    'childs' => function($query) {
                        $query->where('is_active', TRUE);
                    }
                ])->where('is_active', TRUE)
                ->orderBy('order')
                ->get(),
        ]);
    }


    public function searchPatient(Request $request)
    {
        $text = $request->get('text');
        if (!$text) {

            return \response()->json(['error', 'Please enter donor number']);
        }
        return response()->json(
            \App\DonorMaster::where('donor_no', $text)->first()
        );
    }

    public function store(Request $request)
    {

        $errors = [];
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
                "weight" => ['required'],
                "systolic" => ['required'],
                "diastolic" => ['required'],
                "temperature" => ['required'],
                "pulse" => ['required'],
                "answer" => ['required'],
                "question_remarks" => ['required'],
                "form_date" => ['required'],
                "is_accepted" => ['required'],
            ]);

            if ($validator->fails()) {
                \Log::info($validator->getMessageBag()->messages());
                $errors = [];
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $errors[$key] = $value[0];

            } else {

                try {
                    $donor_no = $request->donor_no;
                    if ($donor_no != '' || $donor_no != null) {
                        $donor_id = DonorMaster::where('donor_no', $request->donor_no)->first();
                        if ($donor_id) {
                            $consent = Consent::create([
                                "donor_id" => $donor_id->id,
                                "weight" => $request->weight,
                                "systolic" => $request->systolic,
                                "diastolic" => $request->diastolic,
                                "temperature" => $request->temperature,
                                "pulse" => $request->pulse,
                                "branch_id" => $request->branch,
                                "form_date" => $request->form_date ? \App\Utils\Helpers::dateNepToEng($request->form_date)->full_date :'',
                                "is_accepted" => $request->is_accepted,
                            ]);
                            $consent_answer =[];
                            if($request->answer && is_array($request->answer)) {
                                foreach ($request->answer as $k => $answer) {
                                    $consent_answer = [
                                        'consent_id' => $consent->id,
                                        'question_id' => $k,
                                        'answer' => $answer,
                                        'remarks' => $request->question_remarks[$k-1] ? $request->question_remarks[$k-1] : null,
                                    ];
                                }

                                Consentquestionanswer::create($consent_answer);
                            }

                            return redirect()->route('bloodbank.consent-form.index')->with('success', 'Saved successfully');
                        }
                    } else {
                        $this->saveDonor($request);
                    }
                }catch (\Exception $exception)
                {
                    \Log::info($exception->getMessage());
                    return redirect()->route('bloodbank.consent-form.index')->with('error', 'Something went wrong');
                }

            }

            return view('bloodbank::consent-form', [
                'form_errors' => $errors,
                'hospitalbranches' => \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get(),
                'questions' => QuestionMaster::whereNull('parent_id')->with('childs')->orderBy('order')->get(),
            ]);
        }


    public function saveDonor(Request $request)
    {
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

           $consent= Consent::create([
                "donor_id" => $donorNo,
                "weight" => $request->weight,
                "systolic" => $request->systolic,
                "diastolic" => $request->diastolic,
                "temperature" => $request->temperature,
                "pulse" => $request->pulse,
               "branch_id" => $request->branch,
               "form_date" => $request->form_date ? \App\Utils\Helpers::dateNepToEng($request->form_date)->full_date :'',
               "is_accepted" => $request->is_accepted,
            ]);
            $consent_answer =[];
            if($request->answer && is_array($request->answer)) {
                foreach ($request->answer as $k => $answer) {
                    $consent_answer = [
                        'consent_id' => $consent->id,
                        'question_id' => $k,
                        'answer' => $answer,
                        'remarks' => $request->question_remarks[$k-1] ? $request->question_remarks[$k-1] : null,
                    ];
                }
                Consentquestionanswer::create($consent_answer);
            }

            \DB::commit();

            return redirect()->route('bloodbank.consent-form.index')->with('success', $messsage);
        } catch (\Exception $e) {
            \DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in consent form save donor', "Error"]);
            session()->flash('error_message', __('Error while adding Consent'));
        }

    }

}
