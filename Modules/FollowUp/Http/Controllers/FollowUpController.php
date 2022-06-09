<?php

namespace Modules\FollowUp\Http\Controllers;

use App\Department;
use App\Encounter;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['department'] = Department::select('flddept')->where('fldcateg', 'Consultation')->get();
        return view('followup::index', $data);
    }

    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'from_date_eng' => 'required',
            'department' => 'required',
        ]);

        $startTime = Carbon::parse($request->from_date_eng)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->from_date_eng)->setTime(23, 59, 59);

        $data['encounter'] = Encounter::select('fldcurrlocat', 'fldfollowdate', 'fldpatientval', 'flduserid', 'fldencounterval', 'fldrank')
            ->where('fldcurrlocat', $request->department)
            ->where('fldfollowdate', '>=', $startTime)
            ->where('fldfollowdate', '>=', $endTime)
            ->with('patientInfo')
            ->get();

        return response()->json([
            'success' => true,
            'message' => "consultant added successfully.",
            'html' => $this->generateHtml($data['encounter'])
        ]);
    }

    public function searchByEncounter(Request $request)
    {
        $validatedData = $request->validate([
            'encounter_search' => 'required',
        ]);

        $data['encounter'] = Encounter::select('fldcurrlocat', 'fldfollowdate', 'fldpatientval', 'flduserid', 'fldencounterval', 'fldrank')
            ->where('fldencounterval', $request->encounter_search)
            ->with('patientInfo')
            ->get();
        return response()->json([
            'success' => true,
            'message' => "consultant added successfully.",
            'html' => $this->generateHtml($data['encounter'])
        ]);
    }

    public function generateHtml($dataArray)
    {
        $html = '';
        if ($dataArray) {
            foreach ($dataArray as $key => $item) {
                $user_rank = ((Options::get('system_patient_rank') == 1) && isset($item) && isset($item->fldrank)) ? $item->fldrank : '';
                $name = $user_rank . ' ' . $item->patientInfo ? ($item->patientInfo->fldptnamefir . ' ' . $item->patientInfo->fldmidname . ' ' . $item->patientInfo->fldptnamelast) : "";
                $age = $item->patientInfo ? $item->patientInfo->fldagestyle . '/' . $item->patientInfo->fldptsex : "";
                // $age = $item->patientInfo ? \Carbon\Carbon::parse($item->patientInfo->fldptbirday)->age . '/' . $item->patientInfo->fldptsex : "";

                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td><a href='javascript:;' onclick='changeFollowUpDate(\"$item->fldencounterval\")'>$item->fldfollowdate</a></td>";
                $html .= "<td>$item->fldcurrlocat</td>";
                $html .= "<td>$item->fldencounterval</td>";
                $html .= "<td>$name</td>";
                $html .= "<td>$age</td>";
                $html .= "<td>$item->fldptcontact</td>";
                $html .= "<td>$item->flduserid</td>";
                $html .= "<td></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }

    public function updateFollowUpDate(Request $request)
    {
//        UPDATE `tblencounter` SET `fldfollowdate` = '2020-10-07 10:30:00', `xyz` = '0' WHERE `fldencounterval` = '1'

        try {
            $updateData = [
                'fldfollowdate' => date('Y-m-d H:i:s', strtotime("$request->date_eng_follow_up $request->consult_time_edit")),
                'xyz' => 0
            ];
            Encounter::where('fldencounterval', $request->encounter_id_follow_up)->update($updateData);
            $data['encounter'] = Encounter::select('fldcurrlocat', 'fldfollowdate', 'fldpatientval', 'flduserid', 'fldencounterval', 'fldrank')
                ->where('fldencounterval', $request->encounter_id_follow_up)
                ->with('patientInfo')
                ->get();
            return response()->json([
                'success' => true,
                'message' => "Follow up update successfully.",
                'html' => $this->generateHtml($data['encounter'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong."
            ]);
        }
    }
}
