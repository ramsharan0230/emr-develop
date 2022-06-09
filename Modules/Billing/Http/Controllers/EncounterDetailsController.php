<?php

namespace Modules\Billing\Http\Controllers;

use App\CogentUsers;
use App\Departmentbed;
use App\Encounter;
use App\PatBillDetail;
use App\PatBilling;
use App\PatientExam;
use App\PatientInfo;
use App\PatLabTest;
use App\User;
use App\Utils\Helpers;
use Carbon\Carbon;
use DB;
use Illuminate\Routing\Controller;

class EncounterDetailsController extends Controller
{
    public function getEncounterData($encounter_id, $addGroupList)
    {
        session(['billing_encounter_id' => $encounter_id]);

        /*create last encounter id*/
        Helpers::moduleEncounterQueue('billing_encounter_id', $encounter_id);
        $computer = Helpers::getCompName();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)
            ->with('patientInfo')
            ->with('consultant:fldencounterval,flduserid')
            ->first();
        if (isset($enpatient->fldbillingmode)) {
            $data['addGroup'] = $addGroupList->where('billingmode', $enpatient->fldbillingmode)->all();
        }
        $data['referralDoctorSelected'] = $enpatient && $enpatient->consultant ? $enpatient->consultant->flduserid : NULL;

        if (!$enpatient) {
            \Session::forget('billing_encounter_id');
            return redirect()->back()->with('error', 'Encounter not found.');
        }
        $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

        $patient_id = $enpatient->fldpatientval;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $data['patient_id'] = $patient_id;
        $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
        $data['refer_by'] = CogentUsers::where('fldreferral', 1)->where('status', 'active')->get();

        if ($patient) {
            $end = Carbon::parse($patient->fldptbirday ? $patient->fldptbirday : null);
            $now = Carbon::now();
            $length = $end->diffInDays($now);
            if ($length < 1) {
                $data['years'] = 'Hours';
                $data['hours'] = $end->diffInHours($now) ?? null;
            }

            if ($length > 0 && $length <= 30)
                $data['years'] = 'Days';
            if ($length > 30 && $length <= 365)
                $data['years'] = 'Months';
            if ($length > 365)
                $data['years'] = 'Years';
        }

        $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->orderBy('fldid', 'desc')->first();
        // dd($body_weight);
        $data['body_height'] = $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

        if (isset($body_height)) {
            if ($body_height->fldrepquali <= 100) {
                $data['heightrate'] = 'cm';
                $data['height'] = $body_height->fldrepquali;
            } else {
                $data['heightrate'] = 'm';
                $data['height'] = $body_height->fldrepquali / 100;
            }
        } else {
            $data['heightrate'] = 'cm';
            $data['height'] = '';
        }


        $data['bmi'] = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = ($hei * $hei);
            if ($divide_bmi > 0) {

                $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }
      //  DB::enableQueryLog();
        $data['billings'] = PatBilling::where([
            ['flditemtype', '=', 'Diagnostic Tests'],
            ['fldsample', '=', 'Waiting'],
            ['fldencounterval', '=', $encounter_id],
            //['fldsave', '=', '1'],
            // ['fldtarget', '=', $compname],
           // ['flditemqty', '>', 'fldretqty'],
        ])
        ->where(function ($query) {
            $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
        })
        ->where(function ($query) {
            $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
            ->orWhere('fldtempbillno', '=', NULL)
            ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
            ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
          

        })
      
        ->get();

      
        $data['labtests'] = PatLabTest::select('fldid', 'fldchk', 'fldtestid', 'fldmethod', 'fldtime_sample', 'fldsampleid', 'fldsampletype', 'fldbillno', 'fldcondition', 'fldtest_type', 'fldrefername', 'fldcomment', 'fldencounterval', 'fldtime_start')
            ->with('test:fldtestid,fldvial')
            ->where([
                'fldencounterval' => $encounter_id,
                // 'fldcomp_sample' => $compname,
            ])->where(function ($query) {
                $query->where('fldstatus', 'Ordered');
                $query->orWhere('fldstatus', 'Sampled');
            })->get();


        $dataList['serviceData'] = PatBilling::where('fldencounterval', $encounter_id)
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldtempbillno', '=', NULL)
            ->where('fldditemamt', '>=', 0)
            ->where('fldstatus', 'Punched')
            ->with('serviceCost')
            ->with('noDiscount')
            ->orderBy('fldid', 'DESC')->get();
        $dataList['serviceTpData'] = PatBilling::where('fldencounterval', $encounter_id)
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where(function ($query) {
                $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
            })
            ->where('fldditemamt', '>=', 0)
            ->where('fldstatus', 'Punched')
            ->with('serviceCost')
            ->with('noDiscount')
            ->orderBy('fldid', 'DESC')->get();
          
        $data['referralDoctorSelected'] = $dataList['serviceData'] && $dataList['serviceData']->isNotEmpty() && $dataList['serviceData'][0]->fldrefer ? $dataList['serviceData'][0]->fldrefer : $data['referralDoctorSelected'];

        $dataList['subtotal'] = $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
            ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
            ->where('fldstatus', 'Punched')
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldtempbillno', '=', NULL)
            // ->where(function ($query) {
            //     $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
            //     ->orWhere('fldtempbillno', '=', NULL)
            //     ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
            //     ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
            // })
          
            ->where('fldditemamt', '>=', 0)
            ->first()->subtotal;
        $dataList['total'] = $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Punched')
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldtempbillno', '=', NULL)
            // ->where(function ($query) {
            //     $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
            //     ->orWhere('fldtempbillno', '=', NULL)
            //     ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
            //     ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
            // })
            ->where('fldditemamt', '>=', 0)
            ->sum('fldditemamt');
        $dataList['discount'] = $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Punched')
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldtempbillno', '=', NULL)
            // ->where(function ($query) {
            //     $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
            //     ->orWhere('fldtempbillno', '=', NULL)
            //     ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
            //     ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
            // })
            ->where('fldditemamt', '>=', 0)
            ->sum('flddiscamt');
        $dataList['tax'] = $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Punched')
            ->where('fldcomp', $computer)
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldtempbillno', '=', NULL)
            // ->where(function ($query) {
            //     $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
            //     ->orWhere('fldtempbillno', '=', NULL)
            //     ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
            //     ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
            // })
            ->where('fldditemamt', '>=', 0)
            ->sum('fldtaxamt');

        $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

        $data['discountMode'] = $dataList['discountMode'] = $dataList['serviceData'][0]->discount_mode ?? '';

        $data['totalAmountReceivedByEncounter'] = PatBillDetail::where('fldsave', 1)
            ->where('fldencounterval', $encounter_id)
            ->sum('fldchargedamt');

        $encounterIdsForTpBill = Encounter::where('fldpatientval', $patient_id)->pluck('fldencounterval');
        $data['totalTPAmountReceived'] = Helpers::getTpAmount($encounter_id);
        $data['totalDepositAmountReceived'] =  Helpers::totalDepositAmountReceived($encounter_id);
       
        $data['remaining_deposit'] = $data['totalDepositAmountReceived']-$data['totalTPAmountReceived'];
         // dd($data);
        // $data['remaining_credit'] = PatBillDetail::select('fldcurdeposit')
        //             ->whereIn('fldencounterval', $encounterIdsForTpBill)
        //             ->where('fldbilltype', '=', 'Credit')
        //             ->where('fldcomp','=',Helpers::getCompName())
        //             ->orderBy('fldid', 'DESC')
        //             ->first();
           // dd($data);
         //  dd(DB::getQueryLog());
        return [
            'dataArray' => $data,
            'dataList' => $dataList
        ];
    }
}