<?php

namespace Modules\Billing\Http\Controllers;

use App\CustomDiscount;
use App\Discount;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;

class DiscountModeController extends Controller
{
    public function checkDiscountMode($discount, $itemName = null)
    {
        $discountData = Discount::where('fldtype', $discount)->first();

        if ($discountData->fldmode === "FixedPercent") {
            return response()->json([
                'is_fixed' => true,
                "discountPercent" => $discountData->fldpercent,
                "discountArray" => [],
                "discountArrayMain" => $discountData
            ]);
        }

        if ($discountData->fldmode === "CustomValues") {
            return response()->json([
                "is_fixed" => false,
                "discountPercent" => 0,
                "discountArray" => CustomDiscount::select('flditemname', 'fldpercent')->where('fldtype', $discount)->where('flditemname', 'like', $itemName)->first(),
                "discountArrayMain" => $discountData
            ]);
        }

        return response()->json([
            "is_fixed" => false,
            "discountPercent" => 0,
            "discountArray" => [],
            "discountArrayMain" => $discountData
        ]);
    }

    public function calculateDiscount($service, $itemDetails, $request)
    {
        /**calculate discount*/

        $serviceData['flddiscper'] = 0;
        $serviceData['flddiscamt'] = 0;

        if ($service->discount_per != 0) {
            $serviceData['flddiscper'] = $service->discount_per;
            $serviceData['flddiscamt'] = Helpers::numberFormat($service->flditemqty * ($itemDetails->flditemcost * $service->discount_per / 100),'insert');
            $serviceData['fldditemamt'] = Helpers::numberFormat($service->flditemqty * $itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
        } else {
            if ($request->discountMode != null) {

                $discountModeRaw = $this->checkDiscountMode($request->discountMode, $service->flditemname);

                $discountMode = $discountModeRaw->getData();

                if ($discountMode->is_fixed) {
                    $serviceData['flddiscper'] = $discountMode->discountPercent;
                    $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountPercent / 100),'insert');
                    $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                } elseif ($discountMode->is_fixed === false && $discountMode->discountArray) {
                    $serviceData['flddiscper'] = $discountMode->discountArray->fldpercent;
                    $serviceData['flddiscamt'] = Helpers::numberFormat($itemDetails->flditemcost * $discountMode->discountArray->fldpercent / 100,'insert');
                    $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                } else {
                    if ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Diagnostic Tests") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldlab;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldlab / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Radio Diagnostics") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldradio;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldradio / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Procedures") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldproc;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldproc / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Equipment") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldequip;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldequip / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "General Services") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldservice;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldservice / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Others") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldother;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldother / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Medicine") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldmedicine;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldmedicine / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Surgical") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldsurgical;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldsurgical / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Extra Item") {
                        $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldextra;
                        $serviceData['flddiscamt'] = Helpers::numberFormat(($itemDetails->flditemcost * $discountMode->discountArrayMain->fldextra / 100),'insert');
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost - $serviceData['flddiscamt'],'insert');
                    } else {
                        $serviceData['flddiscper'] = 0;
                        $serviceData['flddiscamt'] = 0;
                        $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost,'insert');
                    }
                }
            } else {
                $serviceData['flddiscper'] = 0;
                $serviceData['flddiscamt'] = 0;
                $serviceData['fldditemamt'] = Helpers::numberFormat($itemDetails->flditemcost,'insert');
            }
        }
        return $serviceData;
    }
}
