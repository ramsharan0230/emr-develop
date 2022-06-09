<?php

namespace Modules\Registration\Http\Controllers;

use App\CustomDiscount;
use App\Discount;
use App\ServiceCost;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscountDataController extends Controller
{

    public function getDiscountPercent(Request $request)
    {
        return $this->getDiscountPercentCalculate($request->discountName, $request->itemName);
    }

    public function getDiscountPercentCalculate($discountName, $itemName)
    {
        $discountModeRaw = $this->checkDiscountMode($discountName, $itemName);

        $discountMode = $discountModeRaw->getData();

        $itemDetails = ServiceCost::where('flditemname', $itemName)->first();

        if ($discountMode->is_fixed) {
            $serviceData['flddiscper'] = $discountMode->discountPercent;
        } elseif ($discountMode->is_fixed === false && $discountMode->discountArray) {
            $serviceData['flddiscper'] = $discountMode->discountArray->fldpercent;
        } else {
            if ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Diagnostic Tests") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldlab;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Radio Diagnostics") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldradio;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Procedures") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldproc;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Equipment") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldequip;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "General Services") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldservice;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Others") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldother;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Medicine") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldmedicine;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Surgical") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldsurgical;
            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Extra Item") {
                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldextra;
            } else {
                $serviceData['flddiscper'] = 0;
                $serviceData['flddiscamt'] = 0;
            }
        }

        return $serviceData['flddiscper'];
    }

    public function checkDiscountMode($discount, $itemName = null)
    {
        $discountData = Discount::where('fldtype', $discount)->first();
        if (isset($discountData) and $discountData['fldmode'] === "FixedPercent") {
            return response()->json([
                'is_fixed' => true,
                "discountPercent" => $discountData->fldpercent,
                "discountArray" => [],
                "discountArrayMain" => $discountData
            ]);
        }

        $customDis = CustomDiscount::select('flditemname', 'fldpercent')
            ->where('fldtype', $discount)
            ->where('flditemname', $itemName)
            ->first();

        if (isset($discountData) and $discountData['fldmode'] === "CustomValues") {
            return response()->json([
                "is_fixed" => false,
                "discountPercent" => 0,
                "discountArray" => $customDis,
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
}
