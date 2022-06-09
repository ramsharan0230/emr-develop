<?php

namespace Modules\Template\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class VaribalesSamplesController extends Controller
{
    /**
     * Display a listing of the resource.

     */
    public function index()
    {
        //Birth Parameters
        $MotherEncID ='';
        $MotherName ='';
        $DeliverDate ='';
        $DeliveryTime ='';
        $DeliveryType ='';
        $DeliveryResult ='';
        $DeliveryConsult ='';
        $BloodLoss ='';
        $BabyWeight ='';

        //Procedure parameters
        $status ='';
        $name ='';
        $personal ='';
        $components ='';
        $comment ='';
        $summary ='';
        $Preoperative_indication ='';
        $Preoperative_discussion ='';
        $Preoperative_comment ='';
        $Anaesthesia_technique ='';
        $Anaesthesia_comment ='';
        $postoperative_indicator ='';
        $postoperative_comment='';


        ///General Parameters
        $HospitalName ='';
        $HospitalAddress ='';
        $PrintDepartment ='';
        $CurrentDate ='';
        $CurrentTime ='';
        $CurrentUser ='';
        $signature1 ='';
        $signature2 ='';
        $signature3 ='';

        //pateint Parameters

        $encounterID ='';
        $patientNo ='';
        $patientName ='';
        $EthinicGroup = '';
        $patientCode ='';
        $patientAddress ='';
        $patientDistrict ='';

        //Diagnosis
        $triagexamination ='';
        $presenting_complatints ='';
        $OPDExaminations ='';
        $causeOfAdmission ='';
        $generalCOmplaints ='';
        $historyofIllness ='';
        $pastHistory ='';
        $familyHistory ='';
        $treatmentHistory ='';
        $medicationHistory ='';
        $personalHistory ='';
        $surgericalHistory ='';
        $occupationalHistory ='';
        $socialHistory ='';
        $essentialExamination ='';
        $structuredExamination ='';
        $clinicalFindings ='';
        $provisionalDiagnosis ='';
        $finalDIagnosis ='';
        $patientDiagnosis ='';
        $patientDiagnosisClass ='';
        $initialPlanning ='';
        $dischargeExamination ='';

        //Examination
        $weight = '';
        $hepaticStatus = '';
        $pregnancy_status ='';
        $systlicBp ='';
        $dystolicBP ='';
        $PulseRate ='';
        $respirationRate ='';
        $temperature ='';

        //Consultationparamater
        $lastConsultDept ='';
        $lastConsultDate ='';
        $lastConsultDeTime ='';
        $lastConsultComment ='';
        $lastConsultStatus ='';
        $lastConsultant ='';
        $lastConsultque ='';
        $lastConsultRoom='';
        $lastConsultBillMode='';

        //Encounter Parameters
        $regdDepartment ='';
        $regdDepartmentRoom ='';
        $currnetLocation ='';
        $Height ='';
        $RegBillingMode ='';
        $RegistratonDate ='';
        $patientVisitType ='';
        $AdmissionDate ='';
        $AdmissionTime ='';
        $DischargeDate ='';
        $followUpDate ='';
        $followUpTime ='';
        $AdmissionStatus ='';
        $COnsultant ='';
        $RegdCOnsultantFree ='';



    }


}
