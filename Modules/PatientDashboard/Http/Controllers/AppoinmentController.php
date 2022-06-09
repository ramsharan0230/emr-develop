<?php

namespace Modules\PatientDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class AppoinmentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('patientdashboard::appoinment');
    }
}
