<?php

namespace Modules\Ssf\Http\Controllers;

use App\Utils\Helpers;
use CogentHealth\Ssf\Claim\Claim;
use CogentHealth\Ssf\Eligibility\Eligibility;
use CogentHealth\Ssf\Ssf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use PDF;

class SsfController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('ssf::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('ssf::create');
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
        return view('ssf::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('ssf::edit');
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

    public function getPatientDeatailById($patientId = null)
    {
        $ssf_patient_detail = [];
        if ($patientId != null) {
            $data = Ssf::getPatientDetailById($patientId);
            $result = json_decode($data->getContent(), true);
            if ($result['data']['total'] > 0) {
                $patient = $result['data']['entry'][0]['resource'];

                $ssf_patient_detail['id'] = $patient['id'];
                $ssf_patient_detail['type'] = $patient['resourceType'];
                $ssf_patient_detail['nepali_dob'] = ($patient['birthDate'] != "")? Helpers::dateEngToNep(str_replace('-', '/', $patient['birthDate']))->full_date :'';
                $ssf_patient_detail['english_dob'] = $patient['birthDate'];
                $ssf_patient_detail['gender'] = ucfirst($patient['gender']);
                $ssf_patient_detail['firstname'] = $patient['name'][0]['given'][0];
                $ssf_patient_detail['lastname'] = $patient['name'][0]['family'];
            }
        }
        return response()->json([
            'data' => $ssf_patient_detail,
            'success' => true,
            'message' => 'Patient detail fetched'
        ]);
    }

    public function checkEligibilityByPatientId($patientId)
    {
        $eligibility = new Eligibility($patientId);
        $valid = $eligibility->getEligibilityStatus();
        $finance = $eligibility->getFinance();


        return response()->json([
            'data' => [
                'valid' => $valid,
                'finance' => $finance
            ],
            'success' => true,
            'message' => 'Eligibility data fetched.'
        ]);
    }

    public function claimSubmission()
    {
        $data = [
            'patient_id' => '38AA1CF5-59A6-4B77-BE2F-83E226E96547',
            'billable_period_start' => '2021-03-27T15:24:14+05:45',
            'billable_period_end' => '2021-03-27T16:04:22+05:45',
            'created_at' => '2021-03-29T12:53:39+05:45'
            // 'item_code' => 'MED01'
        ];
        $claim = new Claim($data);

        $result = Ssf::claimSubmission($claim);
        $result = json_decode($result->getContent(), true);
        dd($result);
    }

    public function test()
    {
        $pdf = app()->make('dompdf.wrapper');
        $pdf = PDF::loadView('ssf::test');
        return $pdf->stream();
        // return view('ssf::test');
    }
}
