<?php

namespace Modules\Dispensar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class DispensingListController extends Controller
{
    public function index()
    {
        $cat = ['Patient Ward','Emergency','Consultation'];
        $data = [
            'dispensingDepartments' => Helpers::getDispensingDepartments(),
            'departments' => Helpers::getDepartmentByCategory('Patient Ward'),
            // 'departments' => \App\Department::select('fldid','flddept')
            //                 ->whereIn('fldcateg',$cat)
            //                 ->where('fldstatus',1)
            //                 ->get(),
            'billingsets' => Helpers::getBillingModes(),
            'userComputers' => Helpers::getDepartmentAndComp(),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
        return view('dispensar::dispensingList', $data);
    }

    public function getDepartments(Request $request)
    {
    	/*
			select flddept from tbldepartment where fldcateg like 'Patient Ward'
			-- inpatient => 'Patient Ward'
			-- outpatient => 'Consultation'
    	*/
        $departmentPresets = [
            'InPatient'=> 'Patient Ward',
            'OutPatient'=> 'Consultation',
            'ER'=> 'Emergency',
        ];
        $department = $request->get('dispensingDepartment');
        $department = (isset($departmentPresets[$department])) ? $departmentPresets[$department] : 'Consultation';
        return  response()->json(Helpers::getDepartmentByCategory($department));
    }

    public function getPatients(Request $request)
    {
        // dd($request->all());
    	/*
			select fldencounterval from tblpatdosing where flditemtype='Medicines' and fldlevel='Requested' and fldencounterval like '%' and fldcurval='Continue' and fldencounterval in(select fldencounterval from tblencounter where fldcurrlocat like 'OPD10') GROUP BY fldencounterval
    	*/

        $currentlocation = $request->get('currentlocation');
        $fldorder = $request->get('fldorder');
        $fldlevel = $request->get('fldlevel');
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');

        $fldbillingmode = $request->get('fldbillingmode');
        $fldcompid = $request->get('fldcompid');

        if($request->dispensingDepartment == 'InPatient'){
            $department = 'Patient Ward';
        }else if($request->dispensingDepartment == 'OutPatient'){
            $department = 'Consultation';
        }else if($request->dispensingDepartment == 'ER'){
            $department = 'Emergency';
        }else{
            $department = '';  
        }


        $patients = \App\PatDosing::select('fldencounterval')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldcurrlocat,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                // 'encounter.currentDepartment:fldcateg',
            ])
            ->where([
                ['flditemtype', 'Medicines'],
                ['fldlevel', $fldlevel],
                ['fldcurval', 'Continue'],
                ['fldorder', $fldorder],
                ["fldstarttime", ">=", "$fromdate 00:00:00"],
                ["fldstarttime", "<=", "$todate 23:59:59.999"],
                
            ]);
        if ($fldcompid)
            $patients->where('fldcomp', $fldcompid);

        if($department){
            $patients->whereHas('encounter.currentDepartment', function($departmentQuery) use ($department) {
                $departmentQuery->where('fldcateg', $department);
            });
        }
        if(empty($currentlocation)){
            $patients = $patients->whereHas('encounter', function($query) use ($fldbillingmode) {
              
                if (!empty($fldbillingmode))
                    $query->where('fldbillingmode', $fldbillingmode);

            })->groupBy('fldencounterval')
            ->get();
        }else{
            $patients = $patients->whereHas('encounter', function($query) use ($currentlocation, $fldbillingmode) {
                $query->where('fldcurrlocat', $currentlocation);
                if (!empty($fldbillingmode))
                    $query->where('fldbillingmode', $fldbillingmode);
                    
            })->groupBy('fldencounterval')
            ->get();
        }

        // echo $patients; exit;
       

        return response()->json($patients);

    }

    public function getPatientMedicines(Request $request)
    {
        /*
			select fldpatientval from tblencounter where fldencounterval='E14473GH'
			select fldptsex from tblpatientinfo where fldpatientval='14440GH'
			select fldpatientval from tblencounter where fldencounterval='E14473GH'
			select fldptaddvill from tblpatientinfo where fldpatientval='14440GH'
			select fldpatientval from tblencounter where fldencounterval='E14473GH'
			select fldptadddist from tblpatientinfo where fldpatientval='14440GH'
			select fldcurrlocat from tblencounter where fldencounterval='E14473GH'
			select fldid,fldtime_order,fldroute,flditem,flddose,fldfreq,flddays,fldqtydisp,fldlabel,fldcomp_order,fldencounterval from tblpatdosing where fldencounterval='E14473GH' and fldlevel='Requested' and flditemtype='Medicines' and fldcurval='Continue'
    	*/

        $encounterId = $request->get('encounterId');
        $patientInfo = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank')
            ->where('fldencounterval', $encounterId)
            ->first();

        $medicines = \App\PatDosing::select('fldid', 'fldtime_order', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'fldcomp_order', 'fldencounterval', 'flduserid_order')
            ->where([
                'fldencounterval' => $encounterId,
                'fldlevel' => $request->fldlevel,
                'flditemtype' => 'Medicines',
                'fldcurval' => 'Continue',
            ])->get();

        return  response()->json(compact('patientInfo', 'medicines'));
    }

    public function dispense(Request $request)
    {
        // dd($request->all());
        // UPDATE `tblpatdosing` SET `fldlevel` = 'Dispensed', `xyz` = '0' WHERE `fldid` = 86
        $fldid = $request->get('fldid');
        $encounterId = $request->get('fldencounterval');

        $patientInfo = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank')
            ->where('fldencounterval', $encounterId)
            ->first();

        $medicine = \App\PatDosing::where([
                'fldid' => $fldid,
            ])->first();
        $medicine->fldlevel = 'Dispensed';
        $medicine->save();
        return view('dispensar::dispenseReport', compact('patientInfo', 'medicine'));
    }

    public function changeQuantity(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $type = $request->get('type');
            $quantity = $request->get('quantity');
            $column = '';
            if ($type == 'Dose')
                $column = 'flddose';
            elseif ($type == 'Frequency')
                $column = 'fldfreq';
            elseif ($type == 'Day')
                $column = 'flddays';

            if ($column) {
                \App\PatDosing::where([
                    'fldid' => $fldid,
                ])->update([
                    $column => $quantity
                ]);
                return response()->json([
                    'status'  => TRUE,
                    'message' => __('messages.update', ['name' => 'Data']),
                ]);
            }
        } catch (Exception $e) {
        }
        return response()->json([
            'status'  => FALSE,
            'message' => 'Failed to update data.',
        ]);
    }

    public function exportMedicines(Request $request){
        // dd($request->all());
        try{
            $encounterId = $request->get('encounter');
            $patientInfo = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'fldrank', 'fldregdate')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptsex,fldrank,fldptbirday,fldptcontact')
                ->where('fldencounterval', $encounterId)
                ->first();

            $medicines = \App\PatDosing::select('fldid', 'fldtime_order', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'fldcomp_order', 'fldencounterval', 'flduserid_order')
                ->where([
                    'fldencounterval' => $encounterId,
                    'fldlevel' => 'Requested',
                    'flditemtype' => 'Medicines',
                    'fldcurval' => 'Continue',
                ])->get();
            $data['patientInfo'] = $patientInfo;
            $data['medicines'] = $medicines;

            return view('dispensar::pdf.dispensed-medicine-list', $data);
        }catch(\Exception $e){
            dd($e);
        }
    }
}
