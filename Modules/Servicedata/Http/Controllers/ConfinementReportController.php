<?php

namespace Modules\Servicedata\Http\Controllers;

use App\Confinement;
use App\Delcomplication;
use App\Delivery;
use App\Districts;
use App\Encounter;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConfinementReportController extends Controller
{
    public function displayReport()
    {
//            Helpers::jobRecord('fmPatAdmit', 'Visit Report');

            $data['addresses'] = $this->_getAllAddress();
            $data['districts'] = \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get();
            $data['department'] = Helpers::getDepartmentByCategory('Consultation');
            $data['comp'] = Helpers::getCompName();
            $data['delivery'] = Delivery::select('flditem')->get();
            $data['complications'] = Delcomplication::select('flditem')->get();

            return view('servicedata::confinement-report', $data);
    }

    public function searchData(Request $request)
    {
        $to_date = Carbon::parse($request->to_date)->setTime(23, 59, 59);
        $from_date = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $html = $this->searchDataFilter($request->all());
        if($request->has('typePdf')){
            $data = [];
            $data['html'] = $html;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['certificate'] = "CONFINEMENT REPORT";
            return view('servicedata::pdf.confinement', $data);
        }else{
            return $html;
        }
    }

    public function searchDataName(Request $request)
    {
        $patientInfo = PatientInfo::select('fldpatientval')
            ->whereRaw('lower(fldptnamefir) like \'' . $request->firstname . '\'')
            ->whereRaw('lower(fldptnamelast) like \'' . $request->lastname . '\'')
            ->pluck('fldpatientval');

        $encounterIds = Encounter::select('fldencounterval')
            ->whereIn('fldpatientval', $patientInfo)
            ->pluck('fldencounterval');

        $resultArray = Confinement::select('fldid', 'fldencounterval', 'flddeltime', 'flddeltype', 'flddelresult', 'fldbloodloss', 'flddelwt', 'fldbabypatno', 'flddelphysician', 'flddelnurse', 'fldcomplication')
            ->whereIn('fldencounterval', $encounterIds)
            ->with(['encounter', 'encounter.patientInfo'])
            ->paginate(25);

        $html = '';
        $count = 1;

        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
            $html .= '<td>' . $user_rank . ' ' . $patient->patientInfo->fldptnamefir . ' ' . $patient->patientInfo->fldmidname . ' ' . $patient->patientInfo->fldptnamelast . '</td>';
            $html .= '<td>' . $patient->patientInfo->fldagestyle . '</td>';
            // $html .= '<td>' . Helpers::ageCalculation($patient->patientInfo->fldptbirday) . '</td>';
            $html .= '<td>' . $patient->patientInfo->fldptsex . '</td>';
            $html .= '<td>' . $patient->fldregdate . '</td>';
            $html .= '<td>' . $patient->fldadmission . '</td>';
            $html .= '<td>Consult</td>';
            $html .= '</tr>';
            $count++;
        }
        $html .= '<tr><td colspan="20">' . $resultArray->appends(request()->all())->links() . '</td></tr>';
        return $html;
    }

    public function displaySearchNameForm()
    {
        $data['routeName'] = route('display.consultation.equipment.search.name');
        $data['appendId'] = 'equipment_data';
        return view('consultpatientdata::common.search-name', $data);
    }

    public function export(Request $request)
    {
        $data['view'] = $this->searchDataFilter($request->all());
        return view('servicedata::pdf.confinement', $data)->setPaper('a4', 'landscape')->stream('confinement.pdf');
    }

    public function searchDataFilter($formData)
    {
        $resultData = Confinement::query();
        $resultData->select('fldid', 'fldencounterval', 'flddeltime', 'flddeltype', 'flddelresult', 'fldbloodloss', 'flddelwt', 'fldbabypatno', 'flddelphysician', 'flddelnurse', 'fldcomplication');
        //        $complication = $formData->complication;
        $delevery = $formData['delevery'];
        $district = $formData['district'];
        $province = $formData['province'];
        $to_date = Carbon::parse($formData['to_date'])->setTime(23, 59, 59);
        $from_date = Carbon::parse($formData['from_date'])->setTime(00, 00, 00);
        $complication = $formData['complication'];
        $result = $formData['result'];
        $age_from = $formData['age_from'] * 365;
        $age_to = $formData['age_to'] * 365;
        $weight_from = $formData['weight_from'];
        $weight_to = $formData['weight_to'];

        if (isset($from_date)) {
            $resultData->where('flddeltime', '>=', $from_date);
        }

        if (isset($to_date)) {
            $resultData->where('flddeltime', '<=', $to_date);
        }

        if (isset($weight_from)) {
            $resultData->where('flddelwt', '>=', $weight_from);
        }

        if (isset($weight_to)) {
            $resultData->where('flddelwt', '<=', $weight_to);
        }

        if (isset($result)) {
            $resultData->where("flddelresult", 'LIKE', $result);
        }

        if (isset($delevery)) {
            $resultData->where("flddeltype", 'LIKE', $delevery);
        }

        if (isset($complication)) {
            $resultData->where("fldcomplication", 'LIKE', $complication);
        }

        if (isset($district)) {
            $patienVal = PatientInfo::select('fldpatientval')
                ->where('fldptadddist', $district)
                ->pluck('fldpatientval');

            if (count($patienVal)) {
                $encounterVal = Encounter::select('fldencounterval')
                    ->whereIn('fldpatientval', $patienVal)
                    ->pluck('fldencounterval');

                if (count($encounterVal)) {
                    $resultData->whereIn("fldencounterval", $encounterVal);
                }
            }
        }

        if (isset($province)) {
            $patienVal = PatientInfo::select('fldpatientval')
                ->where('fldprovince', $province)
                ->pluck('fldpatientval');

            if (count($patienVal)) {
                $encounterVal = Encounter::select('fldencounterval')
                    ->whereIn('fldpatientval', $patienVal)
                    ->pluck('fldencounterval');

                if (count($encounterVal)) {
                    $resultData->whereIn("fldencounterval", $encounterVal);
                }
            }
        }


        if ($age_from != 0 && $age_to != 0) {
            $resultDataPatientValAge = \DB::table('tblpatientinfo')->select('tblencounter.fldencounterval')
                ->join('tblencounter', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->join('tblconfinement', 'tblconfinement.fldencounterval', '=', 'tblencounter.fldencounterval');

            if ($age_from != null && $age_to != null) {
                $resultDataPatientValAge
                    ->where('tblpatientinfo.fldptbirday', 'LIKE', '%')
                    ->whereRaw('DATEDIFF(tblconfinement.flddeltime, tblpatientinfo.fldptbirday) >= ' . $age_from)
                    ->whereRaw('DATEDIFF(tblconfinement.flddeltime, tblpatientinfo.fldptbirday) < ' . $age_to);
            }

            $searchByAge = [0];
            if (count($searchByAge)) {
                $searchByAge = $resultDataPatientValAge->pluck('tblencounter.fldencounterval');
            }
            $resultData->whereIn('fldencounterval', $searchByAge);
        }

        $resultArray = $resultData->with(['encounter', 'encounter.patientInfo'])->get();

        $html = '';
        $count = 1;
        //        return $resultArray[1];
        foreach ($resultArray as $patient) {
            $html .= '<tr>';
            $html .= '<td>' . $count . '</td>';
            $html .= '<td>' . $patient->fldencounterval . '</td>';
            $fldregdate = ($patient->encounter) ? $patient->encounter->fldregdate : "";
            $html .= '<td>' . $fldregdate . '</td>';
            if($patient->encounter){
                if($patient->encounter->patientInfo){
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient->encounter) && isset($patient->encounter->fldrank)) ? $patient->encounter->fldrank : '';
                    $html .= '<td>' . $user_rank . ' ' . $patient->encounter->patientInfo->fldptnamefir . ' ' . $patient->encounter->patientInfo->fldmidname . ' ' . $patient->encounter->patientInfo->fldptnamelast . '</td>';
                    $html .= '<td>' . $patient->encounter->patientInfo->fldptadddist . ', ' . $patient->encounter->patientInfo->fldptaddvill . '</td>';
                    $html .= '<td>' . $patient->encounter->patientInfo->fldagestyle . '</td>';
                    // $html .= '<td>' . Helpers::ageCalculation($patient->encounter->patientInfo->fldptbirday) . '</td>';
                }else{
                    $html .= '<td></td><td></td><td></td>';
                }
            }else{
                $html .= '<td></td><td></td><td></td>';
            }
            if($patient->encounter){
                $html .= '<td>' . $patient->encounter->fldpatientval . '</td>';
            }else{
                $html .= '<td></td>';
            }
            if($patient->encounter){
                if($patient->encounter->patientInfo){
                    $html .= '<td>' . $patient->encounter->patientInfo->fldptguardian . '</td>';
                }else{
                    $html .= '<td></td>';
                }
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td>' . $patient->flddeltime . '</td>';
            $html .= '<td>' . $patient->flddeltype . '</td>';
            $html .= '<td>' . $patient->flddelresult . '</td>';
            $html .= '<td>' . $patient->fldbloodloss . '</td>';
            $html .= '<td>' . $patient->flddelwt . '</td>';
            $html .= '<td>' . $patient->fldbabypatno . '</td>';
            if($patient->encounter){
                if($patient->encounter->patientInfo){
                    $html .= '<td>' . $patient->encounter->patientInfo->fldptsex . '</td>';
                }else{
                    $html .= '<td></td>';
                }
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td>' . $patient->flddelphysician . '</td>';
            $html .= '<td>' . $patient->flddelnurse . '</td>';
            $html .= '<td>' . $patient->fldcomplication . '</td>';
            $html .= '</tr>';
            $count++;
        }
        return $html;
    }

    private function _getAllAddress($encode = TRUE)
    {
        $all_data = \App\Municipal::all();
        $addresses = [];
        foreach ($all_data as $data) {
            $fldprovince = $data->fldprovince;
            $flddistrict = $data->flddistrict;
            $fldpality = $data->fldpality;
            if (!isset($addresses[$fldprovince])) {
                $addresses[$fldprovince] = [
                    'fldprovince' => $fldprovince,
                    'districts' => [],
                ];
            }

            if (!isset($addresses[$fldprovince]['districts'][$flddistrict])) {
                $addresses[$fldprovince]['districts'][$flddistrict] = [
                    'flddistrict' => $flddistrict,
                    'municipalities' => [],
                ];
            }

            $addresses[$fldprovince]['districts'][$flddistrict]['municipalities'][] = $fldpality;
        }

        if ($encode)
            return json_encode($addresses);

        return $addresses;
    }
}
