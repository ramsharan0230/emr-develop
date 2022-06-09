<?php

namespace Modules\Radiology\Http\Controllers;

use App\BillingSet;
use App\Complaints;
use App\Consult;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\PatFindings;
use App\PatBilling;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\ServiceCost;
use App\RadioGroup;
use App\Code;
use App\DiagnoGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // echo "here"; exit;
        $fldgroupnames = ServiceCost::select('flditemname')->where('fldgroup', 'General')->orWhere('fldgroup','%')->where('fldstatus','Active');
        $rtests = RadioGroup::select('fldgroupname')->whereIn('fldgroupname', $fldgroupnames)->distinct()->get();
        $html = '';
        foreach($rtests as $test){
            $html .='<li><input type="checkbox" name="rgroup" value="'.$test->fldgroupname.'" class="send_alert">&nbsp;'.$test->fldgroupname.'</li>';
        }
       echo $html;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('radiology::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('radiology::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('radiology::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
