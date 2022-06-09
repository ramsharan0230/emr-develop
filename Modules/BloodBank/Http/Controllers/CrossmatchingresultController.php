<?php

namespace Modules\BloodBank\Http\Controllers;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CrossmatchingresultController extends Controller
{

    public function index()
    {
        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        return view('bloodbank::cross-matching-result',$data);
    }

}
