<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\Essentialexamankle;
use App\Essentialexamfinger;
use App\Essentialexamhip;
use App\Essentialexaminterpretation;
use App\Essentialexamjoints;
use App\Essentialexamknee;
use App\Essentialexamtoejoints;
use App\Manualmuscletesting;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class EssentialExaminationController extends Controller
{

    public function insertEssentialdata(Request $request) {

        try {
            $encounterval = $request->fldencounterval;

            $jointsdata = array(
                'fldencounterval' => $encounterval,
                'joints_cervical_flexion_left' => $request->joints_cervical_flexion_left,
                'joints_cervical_extension_left' => $request->joints_cervical_extension_left,
                'joints_cervical_rotation_left' => $request->joints_cervical_rotation_left,
                'joints_cervical_lateral_flexion_left' => $request->joints_cervical_lateral_flexion_left,
                'joints_cervical_flexion_right' => $request->joints_cervical_flexion_right,
                'joints_cervical_extension_right' => $request->joints_cervical_extension_right,
                'joints_cervical_rotation_right' => $request->joints_cervical_rotation_right,
                'joints_cervical_lateral_flexion_right' => $request->joints_cervical_lateral_flexion_right,
                'joints_shoulder_flexion_left' => $request->joints_shoulder_flexion_left,
                'joints_shoulder_extension_left' => $request->joints_shoulder_extension_left,
                'joints_shoulder_abduction_left' => $request->joints_shoulder_abduction_left,
                'joints_shoulder_adduction_left' => $request->joints_shoulder_adduction_left,
                'joints_shoulder_internal_rotation_left' => $request->joints_shoulder_internal_rotation_left,
                'joints_shoulder_external_rotation_left' => $request->joints_shoulder_external_rotation_left,
                'joints_shoulder_flexion_right' => $request->joints_shoulder_flexion_right,
                'joints_shoulder_extension_right' => $request->joints_shoulder_extension_right,
                'joints_shoulder_abduction_right' => $request->joints_shoulder_abduction_right,
                'joints_shoulder_adduction_right' => $request->joints_shoulder_adduction_right,
                'joints_shoulder_internal_rotation_right' => $request->joints_shoulder_internal_rotation_right,
                'joints_shoulder_external_rotation_right' => $request->joints_shoulder_external_rotation_right,
                'joints_elbow_flexion_left' => $request->joints_elbow_flexion_left,
                'joints_elbow_extension_left' => $request->joints_elbow_extension_left,
                'joints_elbow_supination_left' => $request->joints_elbow_supination_left,
                'joints_elbow_pronation_left' => $request->joints_elbow_pronation_left,
                'joints_elbow_flexion_right' => $request->joints_elbow_flexion_right,
                'joints_elbow_extension_right' => $request->joints_elbow_extension_right,
                'joints_elbow_supination_right' => $request->joints_elbow_supination_right,
                'joints_elbow_pronation_right' => $request->joints_elbow_pronation_right,
                'joints_waist_flexion_left' => $request->joints_waist_flexion_left,
                'joints_waist_extension_left' => $request->joints_waist_extension_left,
                'joints_waist_ulnas_deviation_left' => $request->joints_waist_ulnas_deviation_left,
                'joints_waist_radial_deviation_left' => $request->joints_waist_radial_deviation_left,
                'joints_waist_flexion_right' => $request->joints_waist_flexion_right,
                'joints_waist_extension_right' => $request->joints_waist_extension_right,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $fingerdata  = array(
                'fldencounterval' => $encounterval,
                'finger_mcp_flexion_left' => $request->finger_mcp_flexion_left,
                'finger_mcp_flexion_right' => $request->finger_mcp_flexion_right,
                'finger_mcp_extension_left' => $request->finger_mcp_extension_left,
                'finger_mcp_extension_right' => $request->finger_mcp_extension_right,
                'finger_pip_flexion_left' => $request->finger_pip_flexion_left,
                'finger_pip_flexion_right' => $request->finger_pip_flexion_right,
                'finger_pip_extension_left' => $request->finger_pip_extension_left,
                'finger_pip_extension_right' => $request->finger_pip_extension_right,
                'finger_dip_flexion_left' => $request->finger_dip_flexion_left,
                'finger_dip_flexion_right' => $request->finger_dip_flexion_right,
                'finger_dip_extension_left' => $request->finger_dip_extension_left,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $hipdata  = array(
                'fldencounterval' => $encounterval,
                'hip_flexion_left'=> $request->hip_flexion_left,
                'hip_extension_left'=> $request->hip_extension_left,
                'hip_abduction_left'=> $request->hip_abduction_left,
                'hip_adduction_left'=> $request->hip_adduction_left,
                'hip_medial_rotation_left'=> $request->hip_medial_rotation_left,
                'hip_external_rotation_left'=> $request->hip_external_rotation_left,
                'hip_flexion_right'=> $request->hip_flexion_right,
                'hip_extension_right'=> $request->hip_extension_right,
                'hip_abduction_right'=> $request->hip_abduction_right,
                'hip_adduction_right'=> $request->hip_adduction_right,
                'hip_medial_rotation_right'=> $request->hip_medial_rotation_right,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $kneedata  = array(
                'fldencounterval' => $encounterval,
                'knee_flexion_left'=> $request->knee_flexion_left,
                'knee_extension_left'=> $request->knee_extension_left,
                'knee_flexion_right'=> $request->knee_flexion_right,
                'knee_extension_right'=> $request->knee_extension_right,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $ankledata = array(
                'fldencounterval' => $encounterval,
                'ankle_dorsi_flexion_left'=> $request->ankle_dorsi_flexion_left,
                'ankle_plantar_left'=> $request->ankle_plantar_left,
                'ankle_inversion_left'=> $request->ankle_inversion_left,
                'ankle_eversion_left'=> $request->ankle_eversion_left,
                'ankle_dorsi_flexion_right'=> $request->ankle_dorsi_flexion_right,
                'ankle_plantar_right'=> $request->ankle_plantar_right,
                'ankle_inversion_right'=> $request->ankle_inversion_right,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $toejointsdata = array(
                'fldencounterval' => $encounterval,
                'toe_joints_mtp_flexion_left'=> $request->toe_joints_mtp_flexion_left,
                'toe_joints_mtp_extension_left'=> $request->toe_joints_mtp_extension_left,
                'toe_joints_pip_extension_left'=> $request->toe_joints_pip_extension_left,
                'toe_joints_dip_flexion_left'=> $request->toe_joints_dip_flexion_left,
                'toe_joints_dip_extension_left'=> $request->toe_joints_dip_extension_left,
                'toe_joints_mtp_flexion_right'=> $request->toe_joints_mtp_flexion_right,
                'toe_joints_mtp_extension_right'=> $request->toe_joints_mtp_extension_right,
                'toe_joints_pip_extension_right'=> $request->toe_joints_pip_extension_right,
                'toe_joints_dip_flexion_right'=> $request->toe_joints_dip_flexion_right,
                'toe_joints_dip_extension_right'=> $request->toe_joints_dip_extension_right,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $interpretation = array(
                'fldencounterval' => $encounterval,
                'interpretation' => $request->interpretation,
                'fldcomp' => $request->fldcomp,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );



           Essentialexamjoints::updateOrCreate(['fldencounterval' => $encounterval], $jointsdata);

           Essentialexamfinger::updateOrCreate(['fldencounterval' => $encounterval], $fingerdata);

           Essentialexamhip::updateOrCreate(['fldencounterval' => $encounterval], $hipdata);

           Essentialexamknee::updateOrCreate(['fldencounterval' => $encounterval], $kneedata);

           Essentialexamankle::updateOrCreate(['fldencounterval' => $encounterval], $ankledata);

           Essentialexamtoejoints::updateOrCreate(['fldencounterval' => $encounterval], $toejointsdata);

           Essentialexaminterpretation::updateOrCreate(['fldencounterval' => $encounterval], $interpretation);

           $muscledataname = $request->muscledataname;

           $muscledataleft = $request->muscledataleft;

           $muscledataright = $request->muscledataright;

           $muscledatagrading = $request->muscledatagrading;

           if(is_array($muscledataname) and count($muscledataname) > 0) {
               $length = count($muscledataname);
               for($i=0; $i < $length; $i++) {
                   $manualdata = array(
                        'fldencounterval' => $encounterval,
                        'manual' => $muscledataname[$i],
                        'left' => $muscledataleft[$i],
                        'right' => $muscledataright[$i],
                        'grading' => $muscledatagrading[$i],
                       'fldcomp' => $request->fldcomp,
                       'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                   );

                   Manualmuscletesting::create($manualdata);
               }
           }

            Session::flash('display_popup_error_success', true);
            Session::flash('success_message', 'Complaint update Successfully.');
            return response()->json([
                'success' => 'successfully inserted'
            ]);

        } catch (\Exception $e) {
            dd($e);
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
