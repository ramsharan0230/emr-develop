<?php

namespace Modules\Api\Http\Controllers;

use App\PatientInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PatientProfileApiController extends ApiController
{
    
    public function patientProfile($patientId)
    {
        
        $patientData = PatientInfo::where('fldpatientval',$patientId)->first();
        if ($patientData) {
            return $this->sendResponse($patientData, "Patient Details.");
        } else {
            return $this->sendError('Not Found', "Patient details not found.");
        }
    }

   
}
