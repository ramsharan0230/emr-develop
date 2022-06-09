<?php

namespace Modules\PatientHistory\Http\Controllers;

use App\Encounter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class PatientHistoryController extends Controller
{
    protected $patientFunctions;

    public function __construct(PatientFunctionsController $controller)
    {
        $this->patientFunctions = $controller;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = [];
        $patient_id_session = Session::get('history_patient_id') ?? 0;
        $data['encounters'] = $data['transitions'] = $data['symtoms'] = $data['foods'] = $data['exams'] = $data['laboratory'] = $data['radiologies'] = $data['notes'] = $data['medDosing'] = $data['progress'] = $data['nur_activity'] = $data['intakes'] = [];

        if ($request->has('patient_id') || $patient_id_session != 0) {
            if ($request->has('patient_id')) {
                $patient_id = $request->get('patient_id');
            } else {
                $patient_id = $patient_id_session;
            }
            Session::put(['history_patient_id' => $patient_id]);

            /**patient details*/
            $data['patientDetails'] = PatientDetailsController::patientDetails($patient_id);

            /**Encounters*/
            $data['encounters'] = Encounter::where('fldpatientval', $patient_id)->orderBy('fldregdate', 'desc')->get();
            $encounters = $data['encounters']->pluck('fldencounterval');

            /**Transitions*/
            $data['transitions'] = $this->patientFunctions->transition($encounters);

            /**Symptoms*/
            $data['symtoms'] = $this->patientFunctions->symptoms($encounters);

            /**po inputs*/
            $data['foods'] = $this->patientFunctions->foods($encounters);

            /**Examination*/
            $data['exams'] = $this->patientFunctions->exam($encounters);

            /**laboratory*/
            $data['laboratory'] = $this->patientFunctions->laboratory($encounters);

            /**radiology*/
            $data['radiologies'] = $this->patientFunctions->radiology($encounters);

            /**notes*/
            $data['notes'] = $this->patientFunctions->notes($encounters);

            /**med dosing*/
            $data['medDosing'] = $this->patientFunctions->medDosing($encounters);

            /**progress*/
            $data['progress'] = $this->patientFunctions->progress($encounters);

            /**nursing check*/
            $data['nur_activity'] = $this->patientFunctions->nursActivity($encounters);

            /**bladder irrigation*/
            $data['intakes'] = $this->patientFunctions->bladder($encounters);
        }

        return view('patienthistory::index', $data);
    }

}
