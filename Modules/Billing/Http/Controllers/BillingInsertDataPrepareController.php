<?php

namespace Modules\Billing\Http\Controllers;

use App\ServiceCost;
use App\TaxGroup;
use App\Utils\Helpers;
use Illuminate\Routing\Controller;

class BillingInsertDataPrepareController extends Controller
{
    private static $discountMode;

    public function __construct()
    {
        self::$discountMode = new DiscountModeController();
    }

    public static function preparePatBillData($request, $service){
        $itemtotal = 0;

        $encounterData = \App\Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

        $patientDepartment = "OP";
        if ($encounterData) {
            $department = \App\Departmentbed::select('fldbed', 'flddept')
                ->with('department:flddept,fldcateg')
                ->where('fldbed', $encounterData->fldcurrlocat)
                ->first();
            if ($department && $department->department) {
                if ($department->department->fldcateg == 'Patient Ward' || $department->department->fldcateg == 'Emergency') {
                    $patientDepartment = "IP";
                } else {
                    $patientDepartment = "OP";
                }
            }
        }

        $serviceData = [
            'fldencounterval' => $request->fldencounterval,
            'fldbillingmode' => $request->fldbillingmode,
            'flditemrate' => 0.00,
            'flditemqty' => 1,
            'fldtaxper' => 0,
            'fldtaxamt' => 0.00,
            'fldorduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
            'fldordtime' => date("Y-m-d H:i:s"),
            'fldopip' => $patientDepartment,
            'fldordcomp' => NULL,
            'flduserid' => NULL,
            'fldtime' => NULL,
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 0,
            'fldbillno' => NULL,
            'fldparent' => 0,
            'fldprint' => 0,
            'fldstatus' => 'Punched',
            'fldalert' => 1,
            'fldtarget' => NULL,
            'fldpayto' => NULL,
            'fldrefer' => NULL,
            'fldreason' => NULL,
            'fldretbill' => NULL,
            'fldretqty' => 0,
            'fldsample' => 'Waiting',
            'xyz' => 0,
            'discount_mode' => $request->discountMode,
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        ];

        $itemDetails = ServiceCost::where('flditemname', $service)->first();
        if ($itemDetails) {
            $serviceData['flditemtype'] = $itemDetails->flditemtype;
            $serviceData['flditemno'] = $itemDetails->fldid;
            $serviceData['flditemname'] = $itemDetails->flditemname;
            $serviceData['flditemrate'] = Helpers::numberFormat($itemDetails->flditemcost,'insert');
            $totalAmt = ($itemDetails->flditemcost * 1);
            $returnData['total'] = Helpers::numberFormat(($itemtotal + $totalAmt),'insert');

            /**calculate discount*/
            $serviceData['flddiscper'] = 0;
            $serviceData['flddiscamt'] = 0.00;
            if ($request->discountMode != null) {
                $discountModeRaw = self::$discountMode->checkDiscountMode($request->discountMode, $itemDetails->flditemname);

                $discountMode = $discountModeRaw->getData();

                if ($discountMode->is_fixed) {
                    $serviceData['flddiscper'] = $discountMode->discountPercent;
                    $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountPercent / 100,'insert');
                } elseif ($discountMode->is_fixed === false && $discountMode->discountArray) {
                    $serviceData['flddiscper'] = $discountMode->discountArray->fldpercent;
                    $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArray->fldpercent / 100,'insert');
                } else {
                    if ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Diagnostic Tests") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldlab;
                        $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldlab / 100,'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Radio Diagnostics") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldradio;
                        $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldradio / 100,'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Procedures") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldproc;
                                $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldproc / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Equipment") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldequip;
                                $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldequip / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "General Services") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldservice;
                                $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldservice / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Others") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldother;
                                $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldother / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Medicine") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldmedicine;
                                $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldmedicine / 100,'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Surgical") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldsurgical;
                        $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldsurgical / 100,'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Extra Item") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldextra;
                        $serviceData['flddiscamt'] = Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldextra / 100,'insert');
                    } else {
                        $serviceData['flddiscper'] = 0;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(0,'insert');
                    }
                }
            }
            $serviceData['fldditemamt'] = Helpers::numberFormat($totalAmt - $serviceData['flddiscamt'],'insert');
            if ($itemDetails->fldcode != null) {
                $tax = TaxGroup::where('fldgroup', $itemDetails->fldcode)->first();
                $serviceData['fldtaxper'] = $tax->fldtaxper;
                $taxAmtCalculation = ($serviceData['fldditemamt'] * $tax->fldtaxper / 100);
                $serviceData['fldtaxamt'] = Helpers::numberFormat($taxAmtCalculation,'insert');
            }
            $serviceData['fldtaxamt'] = Helpers::numberFormat($serviceData['fldtaxamt'] * $serviceData['flditemqty'],'insert');
            $serviceData['fldditemamt'] = Helpers::numberFormat($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt'],'insert');

            $serviceData['fldtaxamt'] = Helpers::numberFormat($serviceData['fldtaxamt'] * $serviceData['flditemqty'],'insert');

            $serviceData['fldditemamt'] = Helpers::numberFormat($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt'],'insert');
            return $serviceData;
        }
        return false;
    }
}
