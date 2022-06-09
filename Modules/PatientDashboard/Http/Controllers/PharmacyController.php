<?php

namespace Modules\PatientDashboard\Http\Controllers;

use App\Encounter;
use App\PatDosing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $patientVal = \Auth::guard('patient_admin')->user()->fldpatientval;
        $data['encounters'] = Encounter::select('fldencounterval')
            ->where('fldpatientval', $patientVal)
            ->whereHas('PatDosing', function ($query) {
                $query->where('fldsave', 1);
            })
            ->pluck('fldencounterval');
//            ->whereHas('PatDosing', function ($query) {
//                $query->where('fldsave', 1);
//            })
//            ->paginate(10);
        $data['selectedEncounter'] = $request->encounter??($data['encounters'][0]??[]);
        $data['patDosings'] = [];
        if ($data['selectedEncounter']){
            $data['patDosings'] = PatDosing::where('fldencounterval',$data['selectedEncounter'])->paginate(50);
        }

        return view('patientdashboard::pharmacy.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('patientdashboard::create');
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
        return view('patientdashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('patientdashboard::edit');
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
