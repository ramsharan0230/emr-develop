<?php

namespace Modules\PatientHistory\Http\Controllers;

use App\PatientInfo;
use Illuminate\Routing\Controller;

class PatientDetailsController extends Controller
{
    public static function patientDetails($patientId)
    {
        return PatientInfo::where('fldpatientval', $patientId)->first();
    }
}
