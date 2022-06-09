<?php

namespace Modules\PatientHistory\Http\Controllers;

use App\Encounter;
use Illuminate\Routing\Controller;

class PatientEncounterController extends Controller
{
    protected $patientFunctions;

    public function __construct(PatientFunctionsController $controller)
    {
        $this->patientFunctions = $controller;
    }

    public function displayEncounterHistory($encounterId)
    {
        $data['encounterData'] = Encounter::where('fldencounterval', $encounterId)->with('patientInfo')->first();

        $data['patientDetails'] = PatientDetailsController::patientDetails($data['encounterData']->fldpatientval);

        /**Encounters*/
        $encounters = [$encounterId];

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

        return view('patienthistory::encounter-history', $data);
    }

    public function showEncounterDetails($encounterId)
    {
        $data['encounterData'] = Encounter::where('fldencounterval', $encounterId)->with('patientInfo')->first();

        $data['patientDetails'] = $data['encounterData']->patientInfo;

        /**Encounters*/
        $encounters = [$encounterId];

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

        return view('patienthistory::encounter-history-popup', $data)->render();
    }
}
