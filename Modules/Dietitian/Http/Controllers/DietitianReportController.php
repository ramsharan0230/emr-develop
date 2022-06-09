<?php

namespace Modules\Dietitian\Http\Controllers;

use App\BillingSet;
use App\Departmentbed;
use App\Department;
use App\Encounter;
use App\PatFindings;
use App\PatientInfo;
use App\PatPlanning;
use App\StructExam;
use App\ExamGeneral;
use App\ExtraDosing;
use App\FoodType;
use Carbon\Carbon;
use Cache;
use App\Utils\Helpers;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class DietitianReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function report()
    {
        // echo "here"; exit;
        $data['encounters'] = [];
        $data['departments'] = Department::select('fldcateg')->distinct('fldcateg')->get();
        // dd($data);
        return view('dietitian::report', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportReport(Request $request)
    {
        // dd($request->all());
        try {
            $type = $request->get('report_type');
            $from = $request->get('from_date');
            $to = $request->get('to_date');
            switch ($type) {
                case "kitchen_report":
                    return $this->kitchenReport($from, $to);
                    break;
                case "extra_diet":
                    return $this->extraDietReport($from, $to);
                    break;
                case "special_diet":
                    return $this->specialDietReport($from, $to);
                    break;
                case "diet_sheet":
                    return $this->dietSheetReport($from, $to, $request->ward);
                    break;
                case "child_ward":
                    return $this->childSheetReport($from, $to);
                    break;
                default:
                    echo "Invalid Report";
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function kitchenReport($from, $to)
    {
        // echo $from.'-'.$to; exit;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['billing_mode'] = $billingmode = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });
        $data['dietgroup'] = ExtraDosing::select('fldcategory')->get();
        // dd($data);
        return view('dietitian::pdf.kitchen-report', $data);
    }

    public function extraDietReport($from, $to)
    {
        echo "extra diet report";
    }

    public function specialDietReport($from, $to)
    {
        $types = \App\FoodType::select('fldfoodtype')
            ->distinct()
            ->get();
        // $finalarray = array();
        // if(isset($type) and count($type) > 0){
        //   foreach($type as $t){
        //     $result = ExtraDosing::select('fldencounterval')->where('fldcategory',$t)->distinct('fldencounterval')->get();
        //     foreach($result as $r){
        //       $encounterdetail = Encounter::select('fldadmitlocat', 'fldadmitlocat')->where('fldencounterval',$r->fldencounterval)->get();
        //       $finalarray['department'] = $t;
        //       $finalarray['bedlocation'] = $encounterdetail->fldadmitlocat.'/'.$encounterdetail->fldadmitlocat;
        //       $finalarray['total'] = count($result);
        //     }
        //   }
        // }
        $data['diet_type'] = $types;
        $data['from'] = $from;
        $data['to'] = $to;
        return view('dietitian::pdf.special-diet-report', $data);
    }

    public function dietSheetReport($from, $to, $ward)
    {
        $enpatient = Encounter::whereBetween('flddoa', [$from, $to])->where('fldcurrlocat',$ward)->with('patientInfo')->get();

        $patientdata = array();
        if (isset($enpatient) and count($enpatient) > 0) {
            foreach ($enpatient as $ep) {
                $patientdataSingle['fldencounterval'] = $ep->fldencounterval;
                $patientdataSingle['patientnumber'] = $ep->fldpatientval;
                $patientdataSingle['fullname'] = $ep->patientInfo ? $ep->patientInfo->fldfullname : '';
                $patientdataSingle['rank'] = $ep->fldrank;
                $patientdataSingle['doa'] = $ep->flddoa;
                $patientdataSingle['bed_number'] = $ep->fldcurrlocat;
                array_push($patientdata, $patientdataSingle);
            }
        }
        // dd($patientdata);
        $data['patientinfo'] = $patientdata;
        // $result = DB::table('')
        $data['diets'] = FoodType::select('fldfoodtype')
            ->where('fldfoodtype', '!=', 'Extra Diet')
            ->distinct()
            ->get();
        $data['extraitems'] = \App\FoodContent::select('fldfoodid', 'fldfluid', 'fldenergy')
            ->where([
                'fldfoodtype' => 'Extra Diet',
                'fldfoodcode' => 'Active',
            ])->get();

        $data['from'] = $from;
        $data['to'] = $to;
        $data['ward'] = $ward;

        return view('dietitian::pdf.diet-sheet-report', $data);
    }

    
    public function childSheetReport($from, $to)
    {
        $enpatient = Encounter::whereBetween('flddoa', [$from, $to])->with('patientInfo')->get();

        $patientdata = array();
        if (isset($enpatient) and count($enpatient) > 0) {
            foreach ($enpatient as $ep) {
              $dateOfBirth = $ep->patientInfo ? $ep->patientInfo->fldptbirday : '';
              $years = Carbon::parse($dateOfBirth)->age;
              // echo $years; exit;
              if($years <= 15){
                  $patientdataSingle['fldencounterval'] = $ep->fldencounterval;
                  $patientdataSingle['patientnumber'] = $ep->fldpatientval;
                  $patientdataSingle['fullname'] = $ep->patientInfo ? $ep->patientInfo->fldfullname : '';
                  $patientdataSingle['rank'] = $ep->fldrank;
                  $patientdataSingle['doa'] = $ep->flddoa;
                  $patientdataSingle['bed_number'] = $ep->fldcurrlocat;
                  array_push($patientdata, $patientdataSingle);
              }
                  
            }
        }
        // dd($patientdata);
        $data['patientinfo'] = $patientdata;
        // $result = DB::table('')
        $data['dietsc'] = array('0-6 months','6-12 months','1-3 years','4-6 years','7-9 years','10-15 years');
        // $data['dietsc'] = array(
        //     'fldfoodtype'=>'0-6 months',
        //     'fldfoodtype'=>'6-12 months',
        //     'fldfoodtype'=>'1-3 years',
        //     'fldfoodtype'=>'4-6 years',
        //     'fldfoodtype'=>'7-9 years',
        //     'fldfoodtype'=>'10-15 years',
        //   );
        $data['extraitems'] = \App\FoodContent::select('fldfoodid', 'fldfluid', 'fldenergy')
            ->where([
                'fldfoodtype' => 'Extra Diet',
                'fldfoodcode' => 'Active',
            ])->get();

        $data['from'] = $from;
        $data['to'] = $to;
        $data['ward'] = 'Child Ward';

        return view('dietitian::pdf.diet-sheet-report', $data);
    }

}
