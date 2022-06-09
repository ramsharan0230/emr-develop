<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Exception;

class FluidsController extends Controller
{
	public function getFluids(Request $request)
	{
        $date = $request->get('date');
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $encounter_id = \Session::get('inpatient_encounter_id');
        $fluids = \App\Pathdosing::select('fldid', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstarttime', 'fldstatus')
            ->where([
                "fldencounterval" => $encounter_id,
                "fldsave_order" => "1",
                "fldroute" => "fluid",
                // "flddispmode" => "IPD",
            ]);
        if ($date) {
            $fluids->where([
                ["fldtime", ">=", "$date 00:00:00"],
                ["fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        return response()->json(
            $fluids->get()
        );
	}

	public function getFluidParticulars(Request $request)
	{
        $encounter_id = \Session::get('inpatient_encounter_id');
        $fluids = \App\NurseDosing::select('fldvalue', 'fldunit', 'fldfromtime', 'fldtotime', 'fldid', 'flddoseno')
            ->where([
                "fldencounterval" => $encounter_id,
                "flddoseno" => $request->get('flddoseno'),
            ]);

        return response()->json(
            $fluids->get()
        );
	}

	public function changeFluidStatus(Request $request)
	{
		try{
        	$encounter_id = \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');

            $fldid = \App\Pathdosing::where([
                'fldid' => $request->get('fldid'),
            ])->update([
            	'fldcurval' => $request->get('status'),
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

	public function saveParticulars(Request $request)
	{
		try{
            $doseno = $request->get('doseno');
            $dosevalue = $request->get('dosevalue');

        	$encounter_id = \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $fldid = \App\NurseDosing::insertGetId([
                'fldencounterval' => $encounter_id,
				'flddoseno' => $doseno,
				'fldvalue' => $dosevalue,
				'fldunit' => 'mL/Hour',
				'fldfromtime' => $time,
				'fldtotime' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'xyz' => '0',
            ]);

            return response()->json([
                'status'=> TRUE,
                'data' => [
                    'fldvalue' => $dosevalue,
                    'fldunit' => 'mL/Hour',
                    'fldfromtime' => $time,
                    'fldid' => $fldid,
                ],
                'message' => 'Successfully saved information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save information.',
            ]);
        }
	}

	public function stopParticular(Request $request)
	{
		try{
        	$encounter_id = \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');

            $fldid = \App\NurseDosing::where([
                'fldid' => $request->get('fldid'),
            ])->update([
            	'fldtotime' => $time,
            ]);

            return response()->json([
                'status'=> TRUE,
                'endtime' => $time,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
	}

    public function updateFluidData(Request $request)
    {
        try {
            $date = $request->get('date');
            $time = $request->get('time');
            $flddose = $request->get('flddose');

            $date_time = ($date && $time) ? "$date $time" : '';
            $update_data = array_filter([
                'flddose' => $flddose,
                'fldstarttime' => $date_time,
            ]);
            \App\Pathdosing::where('fldid', $request->get('fldid'))
                ->update($update_data);
            return response()->json([
                'status'=> TRUE,
                'data' => $update_data,
                'message' => __('messages.update', ['name' => 'Fluid']),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update Fluid.',
            ], 200);
        }

    }

    public function getCompatibilityInformation(Request $request)
    {
        return response()->json(
            \DB::table('tblmedbrand AS mb')
                ->select('cfluid.fldchild AS compatibleFluids', 'ifluid.fldchild AS incompatibleFluids', 'cdrug.fldchild AS compatibleDrugs', 'idrug.fldchild AS incompatibleDrugs')
                ->leftJoin('tblcompatfluid AS cfluid', 'cfluid.fldparent', '=', 'mb.fldbrandid')
                ->leftJoin('tblincompatfluid AS ifluid', 'ifluid.fldparent', '=', 'mb.fldbrandid')
                ->leftJoin('tblcompatdrug AS cdrug', 'cdrug.fldparent', '=', 'mb.fldbrandid')
                ->leftJoin('tblincompatdrug AS idrug', 'idrug.fldparent', '=', 'mb.fldbrandid')
                ->where('mb.fldbrandid', $request->get('fluiditem'))
                ->first()
        );
    }

    public function generatePDF(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);

        $date = $request->get('date');
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $medDosing = \App\Pathdosing::select('fldid', 'flditem', 'flddose', 'fldfreq', 'fldroute', 'flddays', 'fldcurval', 'fldstarttime', 'fldstatus')
            ->where([
                'fldencounterval' => $encounter_id,
            ])->orderBy('fldtime');

        if ($date) {
            $medDosing->where([
                ["fldtime", ">=", "$date 00:00:00"],
                ["fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }
        $medDosing = $medDosing->get();

        return \Barryvdh\DomPDF\Facade::loadView('inpatient::layouts.medDosing', compact('patientinfo', 'medDosing'))
            ->stream('fluid_report.pdf');
    }

}
