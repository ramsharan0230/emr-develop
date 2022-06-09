<?php

namespace Modules\AdminDashboard\Http\Controllers;

use App\Confinement;
use App\Consult;
use App\Delivery;
use App\Eappointment;
use App\PatBilling;
use App\Encounter;
use App\Year;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardVueController extends AdminDashboardController
{
    protected $fiscalYear;

    public function __construct()
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $data = [];
        $this->fiscalYear = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
    }

    public function fiscalYear()
    {
        return response()->json([
            'data' => ['fiscal_year' => $this->fiscalYear->fldname],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function patientCount()
    {
        $fiscal_year = $this->fiscalYear;
        
        $outpatient = Encounter::where(function ($query) {
            $query->where('fldadmission', 'Registered')->orWhereNull('fldadmission');
        })
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->whereNotIn('fldencounterval', function ($query) {
                $query->select('fldencounterval')
                    ->from('tblconsult')
                    ->where('fldconsultname', 'Emergency');
            })
            ->count();

        $inpatient = Encounter::where('fldadmission', '!=', 'Registered')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->whereNotIn('fldencounterval', function ($query) {
                $query->select('fldencounterval')
                    ->from('tblconsult')
                    ->where('fldconsultname', 'Emergency');
            })
            ->count();

        $emergency = Encounter::where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->whereIn('fldencounterval', function ($query) {
                $query->select('fldencounterval')
                    ->from('tblconsult')
                    ->where('fldconsultname', 'Emergency');
            })
            ->count();

        return response()->json([
            'data' => [
                'outPatient' => $outpatient,
                'inPatient' => $inpatient,
                'emergency' => $emergency,
                'total' => $outpatient + $inpatient + $emergency
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function newOldPatientCount()
    {
        $fiscal_year = $this->fiscalYear;

        $newpatient = Encounter::where('fldvisit', 'LIKE', 'NEW')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();
        $oldpatient = Encounter::whereIn('fldvisit', ['OLD', 'FOLLOWUP'])
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();
        $followup = Encounter::where('fldvisit', 'LIKE', 'FOLLOWUP')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();

        return response()->json([
            'data' => [
                'newpatient' => $newpatient,
                'oldpatient' => $oldpatient,
                'followup' => $followup
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function onlineWalking()
    {
        $fiscal_year = $this->fiscalYear;

        $onlinepatient = Eappointment::where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)->count();
        $allpatient = Encounter::where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)->count();

        $walkin = $allpatient - $onlinepatient;

        return response()->json([
            'data' => [
                'online' => $onlinepatient,
                'walking' => $walkin
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function otCount()
    {
        $fiscal_year = $this->fiscalYear;

        $Major = PatBilling::select('tblpatbilling.fldencounterval')
            ->whereHas('serviceCost', function ($q) {
                $q->where('tblservicecost.fldreport', 'Major');
            })
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->distinct('tblpatbilling.fldencounterval')
            ->count('tblpatbilling.fldencounterval');

        $Minor = PatBilling::select('tblpatbilling.fldencounterval')
            ->whereHas('serviceCost', function ($q) {
                $q->where('tblservicecost.fldreport', 'Minor');
            })
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->distinct('tblpatbilling.fldencounterval')
            ->count('tblpatbilling.fldencounterval');


        $Intermediate = PatBilling::select('tblpatbilling.fldencounterval')
            ->whereHas('serviceCost', function ($q) {
                $q->where('tblservicecost.fldreport', 'Intermediate');
            })
            ->where('tblpatbilling.fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('tblpatbilling.fldordtime', '<=', $fiscal_year->fldlast)
            ->distinct('tblpatbilling.fldencounterval')
            ->count('tblpatbilling.fldencounterval');

        return response()->json([
            'data' => [
                'major' => $Major,
                'minor' => $Minor,
                'intermediate' => $Intermediate,
                // 'total' => $Major + $Minor + $Intermediate
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function deliveryCount()
    {
        $fiscal_year = $this->fiscalYear;
        $data['total'] = 0;
        $getdeliveryType = Delivery::groupBy('flditem')->pluck('flditem');
        if ($getdeliveryType) {
            foreach ($getdeliveryType as $type) {
                $data['deliveries'][$type] = Confinement::where('flddeltype', $type)
                    ->where('flddeltime', '>=', $fiscal_year->fldfirst)
                    ->where('flddeltime', '<=', $fiscal_year->fldlast)
                    ->count();
                $data['total'] += $data['deliveries'][$type];
            }
        }

        return $data;
    }

    public function pharmacyCount()
    {
        $fiscal_year = $this->fiscalYear;

        $group = ['Medicines', 'Surgicals', 'Extra Items'];
        $op = PatBilling::where('fldopip', 'OP')
            ->whereIn('flditemtype', $group)
            ->where('fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('fldordtime', '<=', $fiscal_year->fldlast)
            ->where('fldsave', '1')
            ->distinct('fldencounterval')
            ->count('fldencounterval');

        $ip = PatBilling::where('fldopip', 'IP')
            ->whereIn('flditemtype', $group)
            ->where('fldordtime', '>=', $fiscal_year->fldfirst)
            ->where('fldordtime', '<=', $fiscal_year->fldlast)
            ->where('fldsave', '1')
            ->distinct('fldencounterval')
            ->count('fldencounterval');

        return response()->json([
            'data' => [
                'ip' => $ip,
                'op' => $op,
                'total' => $ip + $op
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function currentInpatient()
    {
        $fiscal_year = $this->fiscalYear;

        $Admitted = Encounter::where('fldadmission', 'LIKE', 'Admitted')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();

        $Discharged = Encounter::where('fldadmission', 'LIKE', 'Discharged')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();

        return response()->json([
            'data' => [
                'current' => $Admitted,
                'discharged' => $Discharged,
                'total' => $Admitted + $Discharged
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function deathCount()
    {
        $fiscal_year = $this->fiscalYear;

        $Death = Encounter::where('fldadmission', 'LIKE', 'Death')
            ->where('fldregdate', '>=', $fiscal_year->fldfirst)
            ->where('fldregdate', '<=', $fiscal_year->fldlast)
            ->count();

        return response()->json([
            'data' => [
                'death' => $Death,
            ],
            'success' => 'true',
            'message' => 'success'
        ], 200);
    }

    public function labDetails()
    {
        $fiscal_year = $this->fiscalYear;
        $data = $this->laboratoryStatusCount($fiscal_year);

        return [$data['Ordered'] ?? 0, $data['Sampled'] ?? 0, $data['Reported'] ?? 0, $data['Verified'] ?? 0];
    }

    public function radioDetails()
    {
        $fiscal_year = $this->fiscalYear;
        $data = $this->radiologyStatusCount($fiscal_year);

        return [$data['Waiting'] ?? 0, $data['Sampled'] ?? 0, $data['Reported'] ?? 0, $data['Verified'] ?? 0, $data['Ordered'] ?? 0];
    }

    public function ageWiseDetails()
    {
        $fiscal_year = $this->fiscalYear;

        return $this->ageWiseHospitalServices($fiscal_year);
    }

    public function revenueDetails(Request $request)
    {
        return $this->RevenuePatient($request);
    }

    public function radiologyReports()
    {
        return $this->CategoryRadioStatusCount();
    }

    public function labReports()
    {
        return $this->CategorylaboratoryStatusCount();
    }
}
