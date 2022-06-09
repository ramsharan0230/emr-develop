<?php

namespace Modules\ServiceData\Http\Controllers;

use App\AccessComp;
use App\ClinicEntry;
use App\UserFormAccess;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\Permission;

class ClinicalAccessController extends Controller
{
    public function index()
    {
      //  if (Permission::checkPermissionFrontendAdmin('view-clinical-access')) {

            $data['usersClinicalAccess'] = UserFormAccess::select('flduserid')
                ->where('fldcategory', 'Clinician')
                ->distinct('flduserid')
                ->with('userName')
                ->get();


            $data['targetscomp'] = AccessComp::where('status', 'active')->get();

            $disable_component         = [
                "Presenting Complaints",
                "Cause of Admission",
                "Patient History",
                "Clinical Findings",
                "Body Weight",
                "Body Height",
                "Input Output",
                "Progress Notes",
                "Clinical Notes",
                "Therapeutic Planning",
                "Symptoms Addition",
                "Provisional Diagnosis",
                "Final Diagnosis",
                "Drug Allergy",
                "Hepatic Status",
                "Pregnancy Status",
                "Laboratory Request",
                "radiology Request",
                "Pharmacy Request",
                "Products Request",
                "Equipments Used",
                "Procedure Plan",
                "Minor Procedure",
                "Consultation Plan",
                "PO Intake Plan",
                "Monitoring Plan",
                "Extra Procedure Plan",
                "Demographics",
                "General Images",
                "DICOM Images",
                "PACS Images",
                "Medicine Dosing",
                "Event Timing",
                "Vaccination Form",
                "Triage examinations",
                "Essential examinations",
                "Structured Examination",
                "Complete Examination",
                "Change Status",
                "Bed Assignment",
                "Procedure Addition",
                "Procedure Components",
                "Procedure Summary",
                "PreOperative Discussion",
                "Preoperative Examination",
                "Preoperative Note",
                "Preoperative Item Used",
                "Procedure Parameters",
                "Procedure Note",
                "Procedure Item Used",
                "Procedure Personnel",
                "Procedure Instruments",
                "Anesthesia Parameters",
                "Anesthesia Note",
                "Anesthesia Item Used",
                "Postoperative Examination",
                "Postoperative Note",
                "Postoperative Item Used",
                "Delivery Addition",
                "Pre Delivery Examination",
                "On Delivery Examination",
                "Post Delivery Examination",
                "Newborn Examination",
                "Delivery Item Used"
            ];
            sort($disable_component);
            $data['disable_component'] = $disable_component;
            return view('servicedata::clinical-access', $data);
        // } else {
        //     Session::flash('display_popup_error_success', true);
        //     Session::flash('error_message', 'You are not authorized for this action.');
        //     return redirect()->route('admin.dashboard');
        // }
    }

    public function userDisabledComponents(Request $request)
    {
        $componetList = ClinicEntry::select('fldid', 'fldaccess', 'fldcomp','flduserid')
            ->where('flduserid', $request->userName)
            ->where('fldstatus', 'Inactive')
            ->get();

        $html    = '';
        $counter = 1;
        if (count($componetList)) {
            foreach ($componetList as $item) {
                $html .= '<tr>';
                $html .= "<td>$counter</td>";
                $html .= "<td>$item->flduserid</td>";
                $html .= "<td>$item->fldaccess</td>";
                $html .= "<td>$item->fldcomp</td>";
                $html .= '<td><a href="javascript:;" onclick="clinicalAccess.deleteComponent(' . $item->fldid . ')"><i class="fa fa-trash text-danger"></i></a></td>';
                $html .= '</tr>';
                $counter++;
            }
        }
        return $html;
    }

    public function userDeleteComponents(Request $request)
    {
        try {
            ClinicEntry::where('fldid', $request->fldid)->delete();

            $componetList = ClinicEntry::select('fldid', 'fldaccess', 'fldcomp', 'flduserid')
                ->where('flduserid', $request->userName)
                ->where('fldstatus', 'Inactive')
                ->get();

            $html    = '';
            $counter = 1;
            if (count($componetList)) {
                foreach ($componetList as $item) {
                    $html .= '<tr>';
                    $html .= "<td>$counter</td>";
                    $html .= "<td>$item->flduserid</td>";
                    $html .= "<td>$item->fldaccess</td>";
                    $html .= "<td>$item->fldcomp</td>";
                    $html .= '<td><a href="javascript:;" onclick="clinicalAccess.deleteComponent(' . $item->fldid . ')"><i class="fa fa-trash text-danger"></i></a></td>';
                    $html .= '</tr>';
                    $counter++;
                }
            }
            return $html;
        } catch (\GearmanException $e) {
        }
    }

    public function userAddComponents(Request $request)
    {
        try {


            $insert['fldcomp']   = $request->target_comp;
            $insert['fldstatus'] = "Inactive";
            $insert['flduserid'] = $request->user_name;
            for ($i = 0, $iMax = count($request->disable_components); $i < $iMax; $i++) {
                $checkaccess = ClinicEntry::/*where('fldcomp', $request->target_comp)
                    ->*/where('flduserid', $request->user_name)
                    ->where('fldaccess', $request->disable_components[$i])->get();
                if ($checkaccess && count($checkaccess) <= 0) {
                    $insert['fldaccess'] = $request->disable_components[$i];
                    $insert['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                    ClinicEntry::insert($insert);
                }
            }

            $componetList = ClinicEntry::select('fldid', 'fldaccess', 'fldcomp', 'flduserid')
                ->where('flduserid', $request->user_name)
                ->where('fldstatus', 'Inactive')
                ->get();

            $html    = '';
            $counter = 1;
            if (count($componetList)) {
                foreach ($componetList as $item) {
                    $html .= '<tr>';
                    $html .= "<td>$counter</td>";
                    $html .= "<td>$item->flduserid</td>";
                    $html .= "<td>$item->fldaccess</td>";
                    $html .= "<td>$item->fldcomp</td>";
                    $html .= '<td><a href="javascript:;" onclick="clinicalAccess.deleteComponent(' . $item->fldid . ')"><i class="fa fa-trash text-danger"></i></a></td>';
                    $html .= '</tr>';
                    $counter++;
                }
            }
            return $html;
        } catch (\GearmanException $e) {
            return $e;
        }
    }
}
