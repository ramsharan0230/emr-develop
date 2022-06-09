<?php

namespace Modules\Inpatient\Http\Controllers;

use App\ExamGeneral;
use App\ExtraDosing;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;
use DB;
use Exception;

class InOutController extends Controller
{

    public function getMedicineList(Request $request)
    {
        $aa = \App\Pathdosing::select('fldid')->whereOr([
            'fldroute' => 'fluid',
            'fldroute' => 'IIV',
            'fldroute' => 'CIV',
        ])->get()->pluck('fldid')->toArray();
        if (!$aa) {
            return response()->json([]);
        }

        $encounter_id = Session::get('inpatient_encounter_id');
        $date         = $request->get('date');
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $data = \App\NurseDosing::select('tblnurdosing.fldid', 'tblnurdosing.fldencounterval', 'tblnurdosing.flddoseno', 'tblnurdosing.fldvalue', 'tblnurdosing.fldtime', 'tblpatdosing.flditem')
            ->leftJoin('tblpatdosing', 'tblpatdosing.fldid', '=', 'tblnurdosing.flddoseno')
            ->where([
                'tblnurdosing.fldencounterval' => $encounter_id,
                'tblnurdosing.fldsave'         => '1',
            ]);
        if ($date) {
            $data->where([
                ["tblnurdosing.fldtime", ">=", "$date 00:00:00"],
                ["tblnurdosing.fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        $data = $data->whereIn('flddoseno', $aa)->get();

        foreach ($data as &$d) {
            $d->time = explode(' ', $d->fldtime)[1];
            $d->time = substr($d->time, 0, -3);
        }

        return response()->json($data);
    }

    public function getInOutListData(Request $request)
    {
        $date = $request->get('date');
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d');
        }

        $encounter_id = Session::get('inpatient_encounter_id');
        $inoutLists   = ExamGeneral::select('tblexamgeneral.fldid', 'tblexamgeneral.fldencounterval', 'tblexamgeneral.fldtype', 'tblexamgeneral.flditem', 'tblexamgeneral.fldreportquanti', 'tblexamgeneral.fldtime', 'tblexamgeneral.fldinput', 'tblfoodcontent.fldfluid', 'tblfoodcontent.fldenergy')
            ->leftJoin('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
            ->where([
                'tblexamgeneral.fldencounterval' => $encounter_id,
                'tblexamgeneral.fldsave'         => '1',
            ]);

        if ($date) {
            $inoutLists->where([
                ["tblexamgeneral.fldtime", ">=", "$date 00:00:00"],
                ["tblexamgeneral.fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        $inoutLists = $inoutLists->Where(function ($query) {
            $query->where('fldinput', 'Output Fluid');
            $query->orWhere('fldinput', 'Input Food/Fluid');
        })->get();

        $ret_data = [];
        foreach ($inoutLists as $fluid) {
            $key = ($fluid->fldinput === 'Output Fluid') ? 'output' : 'input';

            $fluid->time      = explode(' ', $fluid->fldtime)[1];
            $fluid->time      = substr($fluid->time, 0, -3);
            $ret_data[$key][] = $fluid;
        }

        return response()->json($ret_data);
    }

    public function saveOutFluid(Request $request)
    {
        try {
            $encounter_id = $request->enounter_id ?: Session::get('inpatient_encounter_id');
            $time         = date('Y-m-d H:i:s');
            $userid       = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer     = \App\Utils\Helpers::getCompName();
            $item         = implode(', ', $request->get('item'));
            $quantative   = $request->get('quantative');

            $fldid = ExamGeneral::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldinput'        => 'Output Fluid',
                'fldtype'         => 'Quantitative',
                'flditem'         => $item,
                'fldreportquali'  => $quantative,
                'fldreportquanti' => $quantative,
                'flddetail'       => NULL,
                'flduserid'       => $userid,
                'fldtime'         => $time,
                'fldcomp'         => $computer,
                'fldsave'         => '1',
                'flduptime'       => NULL,
                'xyz'             => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            $time = explode(' ', $time)[1];
            $time = substr($time, 0, -3);
            return response()->json([
                'status'  => TRUE,
                'data'    => [
                    'flditem'         => $item,
                    'fldreportquanti' => $quantative,
                    'time'            => $time,
                    'fldid'           => $fldid,
                ],
                'message' => 'Successfully saved out fluid.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to save out fluid.',
            ]);
        }
    }

    /**
     * Out fluid options
     */
    public function getOutFluid()
    {
        return response()->json(
            \App\BodyFluid::select('fldfluid')->get()
        );
    }


    /**
     * Planned Functions
     */

    public function getDiets(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        $date         = $request->get('date');
        $status       = $request->get('status');
        if (!$date)
            $date = date('Y-m-d');

        $plannedDiets = ExtraDosing::select('tblextradosing.fldid', 'tblextradosing.fldcategory AS type', 'tblextradosing.flditem AS particulars', 'tblextradosing.flddose AS dose', 'tblextradosing.flddosetime', 'tblextradosing.fldstatus AS status', 'fc.fldfluid', 'fc.fldenergy')
            ->join('tblfoodcontent AS fc', 'fc.fldfoodid', '=', 'tblextradosing.flditem')
            ->where([
                'tblextradosing.fldencounterval' => $encounter_id,
                'tblextradosing.fldstatus'       => $status,
                'tblextradosing.fldsave'         => ($status == 'Continue'),
            ]);

        if ($date) {
            $plannedDiets->where([
                ["tblextradosing.fldtime", ">=", "$date 00:00:00"],
                ["tblextradosing.fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        $flddosecode = $request->get('flddosecode');
        if ($flddosecode && $flddosecode == 'set_null')
            $plannedDiets->where('tblextradosing.flddosecode', 'like', '%');
        else
            $plannedDiets->whereNull('tblextradosing.flddosecode');

        $plannedDiets = $plannedDiets->get();

        foreach ($plannedDiets as &$diet) {
            $diet->time = explode(' ', $diet->flddosetime)[1];
            $diet->time = substr($diet->time, 0, -3);
        }

        return response()->json($plannedDiets);
    }

    public function getTypeItems(Request $request)
    {
        $typeItems = \App\FoodContent::select('fldfoodid', 'fldfluid', 'fldenergy')
            ->where([
                'fldfoodtype' => $request->get('type'),
                'fldfoodcode' => 'Active',
            ])->get();

        return response()->json($typeItems);
    }

    public function getTypeData(Request $request)
    {
        $types = \App\FoodType::select('fldfoodtype')
            ->distinct()
            ->get();

        return response()->json($types);
    }

    public function saveDailyDietPlan(Request $request)
    {
        try {
            $userid   = \Auth::guard('admin_frontend')->user()->flduserid;
            $time     = date('Y-m-d H:i:s');
            $computer = \App\Utils\Helpers::getCompName();

            ExtraDosing::whereIn('fldid', $request->get('fldids'))
                        ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                        ->update([
                            'fldstatus' => 'Continue',
                            'flduserid' => $userid,
                            'fldtime'   => $time,
                            'fldcomp'   => $computer,
                            'fldsave'   => '1',
                            'xyz'       => '0'
                        ]);
            return response()->json([
                'status'  => TRUE,
                'message' => 'Saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to save ',
            ]);
        }
    }

    public function addDailyDietPlan(Request $request)
    {
        try {
            $encounter_id = Session::get('inpatient_encounter_id');
            $time         = date('Y-m-d H:i:s');
            $userid       = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer     = \App\Utils\Helpers::getCompName();

            $req_time    = $request->get('time', date('H:i:s'));
            $type        = $request->get('type');
            $particulars = $request->get('item');
            $dose        = $request->get('dose');
            $dosetime    = $request->get('date', date('Y-m-d')) . " " . $req_time;
            $status      = $request->get('status', 'Planned');

            $fldid = ExtraDosing::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldcategory'     => $type,
                'flditem'         => $particulars,
                'flddose'         => $dose,
                'fldfreq'         => NULL,
                'fldtype'         => 'Input Food/Fluid',
                'flddosetime'     => $dosetime,
                'flddosecode'     => NULL,
                'fldstatus'       => $status,
                'flduserid'       => $userid,
                'fldtime'         => $time,
                'fldcomp'         => $computer,
                'fldsave'         => '0',
                'xyz'             => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            if ($status == 'Completed') {
                ExamGeneral::insert([
                    'fldencounterval' => $encounter_id,
                    'fldinput'        => 'Input Food/Fluid',
                    'fldtype'         => 'Qualitative',
                    'flditem'         => $particulars,
                    'fldreportquali'  => $type,
                    'fldreportquanti' => $dose,
                    'flddetail'       => NULL,
                    'flduserid'       => $userid,
                    'fldtime'         => $time,
                    'fldcomp'         => $computer,
                    'fldsave'         => '1',
                    'flduptime'       => NULL,
                    'xyz'             => '0',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }

            return response()->json([
                'status'  => TRUE,
                'data'    => [
                    'fldid'       => $fldid,
                    'type'        => $type,
                    'particulars' => $particulars,
                    'dose'        => $dose,
                    'time'        => $req_time,
                    'flddosetime' => $dosetime,
                    'status'      => $status,
                ],
                'message' => 'Successfully saved planned diet.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to save planned diet.',
            ]);
        }
    }

    public function deleteDiet(Request $request)
    {
        try {
            $status = $request->get('status');
            $fldid  = $request->get('fldid');

            if ($status === 'Planned') {
                ExtraDosing::where([
                        'fldid' => $fldid,
                    ])
                    ->delete();
            } else {
                ExtraDosing::where([
                        'fldid' => $fldid,
                    ])->update([
                        'fldstatus' => 'Discontinue',
                    ]);
            }
            return response()->json([
                'status'  => TRUE,
                'message' => 'Successfully deleted planned diet.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => False,
                'message' => 'Failed to delete planned diet.'
            ]);
        }
    }

    public function getDietsPdf(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        $date         = $request->get('date');
        $status       = $request->get('status');
        if (!$date)
            $date = date('Y-m-d');

        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);

        $plannedDiets = ExtraDosing::select('tblextradosing.fldid', 'tblextradosing.fldcategory AS type', 'tblextradosing.flditem AS particulars', 'tblextradosing.flddose AS dose', 'tblextradosing.flddosetime', 'tblextradosing.fldstatus AS status', 'fc.fldfluid', 'fc.fldenergy')
            ->join('tblfoodcontent AS fc', 'fc.fldfoodid', '=', 'tblextradosing.flditem')
            ->where([
                ['tblextradosing.fldencounterval',  $encounter_id],
                ['tblextradosing.fldstatus',  $status],
                ['tblextradosing.fldsave',  ($status == 'Continue')],
                ["tblextradosing.fldtime", ">=", "$date 00:00:00"],
                ["tblextradosing.fldtime", "<=", "$date 23:59:59.999"],
            ]);

        $flddosecode = $request->get('flddosecode');
        if ($flddosecode && $flddosecode == 'set_null')
            $plannedDiets->whereNull('tblextradosing.flddosecode');

        $plannedDiets = $plannedDiets->get();

        foreach ($plannedDiets as &$diet) {
            $diet->time = explode(' ', $diet->flddosetime)[1];
            $diet->time = substr($diet->time, 0, -3);
        }
        return \Barryvdh\DomPDF\Facade::loadView('inpatient::pdf.dietPlan-inout', compact('patientinfo', 'plannedDiets'))
            ->stream('ipd_diet_plan.pdf');
    }


    /**
     * Intake
     */

    public function setComplete(Request $request)
    {
        try {
            $encounter_id = Session::get('inpatient_encounter_id');
            $time         = date('Y-m-d H:i:s');
            $userid       = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer     = \App\Utils\Helpers::getCompName();
            $fldids       = $request->get('fldids');

            ExtraDosing::whereIn('fldid', $fldids)
                        ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                        ->update([
                            'fldstatus' => 'Completed'
                        ]);
            $data = ExtraDosing::whereIn('fldid', $fldids)->get();

            $insert_data = [];
            foreach ($data as $d) {
                $insert_data[] = [
                    'fldencounterval' => $encounter_id,
                    'fldinput'        => 'Input Food/Fluid',
                    'fldtype'         => 'Qualitative',
                    'flditem'         => $d->flditem,
                    'fldreportquali'  => $d->fldcategory,
                    'fldreportquanti' => $d->flddose,
                    'flddetail'       => NULL,
                    'flduserid'       => $userid,
                    'fldtime'         => $time,
                    'fldcomp'         => $computer,
                    'fldsave'         => '1',
                    'flduptime'       => NULL,
                    'xyz'             => '0',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];
            }
            $data = ExamGeneral::insert($insert_data);
            return response()->json([
                'status'  => TRUE,
                'message' => 'Successfully save.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => TRUE,
                'message' => 'Failed to save.',
            ]);
        }
    }

    public function saveIntake(Request $request)
    {
        try {
            ExamGeneral::where([
                'fldencounterval' => '1',
                'fldinput'        => 'Input Food/Fluid',
                'fldtype'         => 'Qualitative',
                'fldsave'         => '0'
            ])->update([
                'fldsave' => '1'
            ]);
            return response()->json([
                'status'  => TRUE,
                'message' => 'Successfully save.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => TRUE,
                'message' => 'Failed to save.',
            ]);
        }
    }

    public function updateVolumn(Request $request)
    {
        try {
            $fldid   = $request->get('fldid');
            $volumn   = $request->get('volumn');

            ExamGeneral::where('fldid', $fldid)
                ->update([
                    'fldreportquali'  => $volumn,
                    'fldreportquanti' => $volumn,
                ]);
            return response()->json([
                'status'  => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to update data.',
            ], 200);
        }
    }

    public function updateDoseRate(Request $request)
    {
        try {
            $flddose = $request->get('flddose');
            $fldid   = $request->get('fldid');
            $table   = ($request->get('type') === 'medicine') ? 'tblnurdosing' : 'tblexamgeneral';
            $column  = ($request->get('type') === 'medicine') ? 'fldvalue' : 'fldreportquanti';

            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $data   = \DB::table($table)->where('fldid', $fldid)->first();

            if ($userid !== $data->flduserid)
                return response()->json([
                    "status"  => FALSE,
                    "message" => "This action is authorized by {$data->flduserid}",
                ]);

            \DB::table($table)->where('fldid', $fldid)
                ->update([
                    $column => $flddose,
                ]);
            return response()->json([
                'status'  => TRUE,
                'flddose' => $flddose,
                'message' => __('messages.update', ['name' => 'Fluid']),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => FALSE,
                'message' => 'Failed to update Fluid.',
            ], 200);
        }
    }

    public function updateExtraDosing(Request $request)
    {
        try{
            $field = $request->get('field');
            ExtraDosing::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                $field => $request->get('value'),
            ]);

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

}
