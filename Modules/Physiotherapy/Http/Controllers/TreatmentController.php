<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\Treatmentphysiotherapy;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;;

class TreatmentController extends Controller
{

    public function insertTreatment(Request $request) {

        try {
            $encounterval = $request->fldencounterval;

            $data = array(
                'fldencounterval' =>  $encounterval,
                'ust_mode' => $request->ust_mode,
                'ust_frequency' => $request->ust_frequency,
                'ust_intensity' => $request->ust_intensity,
                'ust_time' => $request->ust_time,
                'ust_site' => $request->ust_site,
                'ust_days' => $request->ust_days,
                'tens_mode' => $request->tens_mode,
                'tens_frequency' => $request->tens_frequency,
                'tens_time' => $request->tens_time,
                'tens_site' => $request->tens_site,
                'tens_days' => $request->tens_days,
                'ust_channel' => $request->ust_channel,
                'ift_mode' => $request->ift_mode,
                'ift_site' => $request->ift_site,
                'ift_program_selection' => $request->ift_program_selection,
                'ift_treatment_mode' => $request->ift_treatment_mode,
                'ift_frequency' => $request->ift_frequency,
                'ift_time' => $request->ift_time,
                'ift_days' => $request->ift_days,
                'traction_mode' => $request->traction_mode,
                'traction_hold_time' => $request->traction_hold_time,
                'traction_rest_time' => $request->traction_rest_time,
                'traction_weight' => $request->traction_weight,
                'traction_types' => $request->traction_types,
                'tracttion_time' => $request->tracttion_time,
                'traction_days' => $request->traction_days,
                'ems_mode' => $request->ems_mode,
                'ems_intensity' => $request->ems_intensity,
                'ems_pulse_duration' => $request->ems_pulse_duration,
                'ems_surge_seconds' => $request->ems_surge_seconds,
                'ems_site' => $request->ems_site,
                'ems_days' => $request->ems_days,
                'irr_time' => $request->irr_time,
                'irr_site' => $request->irr_site,
                'irr_days' => $request->irr_days,
                'swd_application_mode' => $request->swd_application_mode,
                'swd_frequency' => $request->swd_frequency,
                'swd_time' => $request->swd_time,
                'swd_days' => $request->swd_days,
                'md_frequency' => $request->md_frequency,
                'md_intensity' => $request->md_intensity,
                'md_time' => $request->md_time,
                'md_site' => $request->md_site,
                'md_days' => $request->md_days,
                'wax_bath_methods' => $request->wax_bath_methods,
                'wax_bath_time' => $request->wax_bath_time,
                'wax_bath_site' => $request->wax_bath_site,
                'wax_bath_days' => $request->wax_bath_days,
                'moist_head_pack_time' => $request->moist_head_pack_time,
                'moist_head_pack_site' => $request->moist_head_pack_site,
                'moist_head_pack_days' => $request->moist_head_pack_days,
                'cryotherapy_temperature' => $request->cryotherapy_temperature,
                'cryotherapy_time' => $request->cryotherapy_time,
                'cryotherapy_site' => $request->cryotherapy_site,
                'cryotherapy_days' => $request->cryotherapy_days,
                'laser_program_selection' => $request->laser_program_selection,
                'cryotherapy_days' => $request->cryotherapy_days,
                'laser_time' => $request->laser_time,
                'laser_site' => $request->laser_site,
                'laser_days' => $request->laser_days,
                'ecswt_site' => $request->ecswt_site,
                'ecswt_energy_flux_density' => $request->ecswt_energy_flux_density,
                'ecswt_frequency' => $request->ecswt_frequency,
                'ecswt_session' => $request->ecswt_session,
                'ecswt_session' => $request->ecswt_session,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            Treatmentphysiotherapy::updateOrCreate(['fldencounterval' => $encounterval], $data);

            Session::flash('display_popup_error_success', true);
            Session::flash('success_message', 'Complaint update Successfully.');
            return response()->json([
                'success' => 'successfully inserted'
            ]);

        } catch (\Exception $e) {
//            dd($e);
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');

            return response()->json([
                'error' => [
                    'message' => 'exception error'
                ]
            ]);
        }

    }
}
