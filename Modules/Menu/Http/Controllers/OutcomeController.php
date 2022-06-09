<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Department;
use App\Encounter;
use App\PatientInfo;
use App\Consult;
use App\ServiceCost;
use App\PatGeneral;
use App\Exam;
use App\Radio;
use App\Referlist;
use App\Monitor;
use Carbon\Carbon;
use App\Nepalicalendar;
use App\Utils\Helpers;
use Illuminate\Support\Facades\Auth;

class OutcomeController extends Controller
{
    
    #Start Outcome To
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayRefertoForm(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        
        // dd($data);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['referlist'] = Referlist::select('fldlocation','fldcode')->get();

        $html = view('menu::menu-dynamic-views.referto-form', $data)->render();
        return $html;
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function addReferto(Request $request)
    {
       // echo "here"; exit;
       try {
            $location = $request->location; 
            $encounterId = $request->encounterId;
            $code = Referlist::select('fldcode')->where('fldlocation',$location)->first();
            // echo $code; exit;
            $data['fldreferto'] = (isset($code) and $code !='') ? $code->fldcode : NULL;
            $data['xyz'] = 0;
            Encounter::where([['fldencounterval',$encounterId]])->update($data);
            return redirect()->route('patient');
            
        } catch (\Exception $e) {
            // dd($e);
            session()->flash('error_message', __('Error While Adding Refer To Location'));

            return redirect()->route('patient');
        }
        
    }
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displayFollowupForm(Request $request)
    {
        // echo "here"; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($data['enpatient'])
        if(isset($enpatient->fldfollowdate) and !is_null($enpatient->fldfollowdate)){
            $datetime = explode(' ', $enpatient->fldfollowdate);
      
            $data['date'] = $datetime[0];
            $data['time'] = $datetime[1];
            // dd($data);
            $now = time(); // or your date as well
            $your_date = strtotime($data['date']);
            $datediff = $now - $your_date;

            $data['days'] = abs(round($datediff / (60 * 60 * 24)));
        }
        
    
        
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        

        $html = view('menu::menu-dynamic-views.followup-form', $data)->render();
        return $html;
    }
   

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function addFollowup(Request $request)
    {
       // echo "here"; exit;
       try {
            $location = $request->location; 
            $encounterId = $request->encounterId;
            $code = Referlist::select('fldcode')->where('fldlocation',$location)->first();

            $data['fldreferto'] = (isset($code) and $code !='') ? $code->fldcode : NULL;
            $data['xyz'] = 0;
            // dd($data);
            Encounter::where('fldencounterval',$encounterId)->update($data);
            return redirect()->back();
            
        } catch (\Exception $e) {
            // dd($e);
            session()->flash('error_message', __('Error While Adding Refer To Location'));

            return redirect()->back();
        }
        
    }

    /**
     * @return array|string
     * @throws \Throwable
     */
    public function updateFollowupdate(Request $request)
    {
            try{
                $encounterId = $request->encounterId;
                $encounterdata = Encounter::where('fldencounterval',$encounterId)->first();
                $datetime = $request->date;
               
                $data['fldfollowdate'] = $datetime;
                $data['xyz'] = 0;
                Encounter::where([['fldencounterval',$encounterId]])->update($data);
                
                $cdata['fldencounterval'] = $encounterId;
                $cdata['fldconsultname'] = $encounterdata->fldcurrlocat;
                $cdata['fldconsulttime'] = $datetime;
                $cdata['fldcomment'] = '';
                $cdata['fldstatus'] = 'Planned';
                $cdata['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $cdata['fldbillingmode'] = $encounterdata->fldbillingmode;
                $cdata['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $cdata['fldtime'] = date('Y-m-d H:i:s');
                $cdata['fldcomp'] = Helpers::getCompName()??'';
                $cdata['fldsave'] = 1;
                $cdata['xyz'] = 0;
                $cdata['fldcategory'] = NULL;
                $cdata['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                $cdata['is_refer'] = 0;
                Consult::insert($cdata);
            }catch(\Exxception $e){
                dd($e);
            }
            
    }
     #Enf Outcome
}
