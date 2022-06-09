<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\ExamGeneral;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use Exception;

class ComplaintController extends Controller
{

    public function insertComplaints(Request $request) {
        try {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput'        => 'Complaints',
                'fldtype'         => 'Qualitative',
                'flditem'         => $request->flditem,
                'fldreportquanti' => 0,
                'flddetail'       => $request->flddetail,
                'flduserid'       => $request->flduserid, //admin
                'fldtime'         => now(), //'2020-02-23 11:13:27.709'
                'fldcomp'         => $request->fldcomp, //comp01
                'fldsave'         => 1, //1
                'flduptime'       => now(), // null ????
                'xyz'             => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = ExamGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id'   => $latest_id
                    ]
                ]);
            }else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Something went wrong.'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');

            return response()->json([
                'error' => [
                    'message' => 'exception error'
                ]
            ]);
        }
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('physiotherapy::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('physiotherapy::create');
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
        return view('physiotherapy::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('physiotherapy::edit');
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
