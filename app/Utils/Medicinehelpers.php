<?php


namespace App\Utils;


use App\Chemical;
use App\Code;
use App\DosageForms;
use App\Drug;
use App\MedCategory;
use App\SensitivityDrug;
use App\TaxGroup;

class Medicinehelpers
{

    public static function getAllCodes($perpage = NULL) {
        if($perpage) {
            $codes = Code::orderBy('fldcodename', 'ASC')->paginate($perpage);
        } else {
            $codes = Code::orderBy('fldcodename', 'ASC')->get();
        }


        return $codes;
    }

    public static function getMedCategory() {
        $medcategory = MedCategory::all();

        return $medcategory;
    }

    public static function getChemicals() {
        $chemicals = Chemical::all();

        return $chemicals;
    }

    public static function getSensitivityDrug() {
        $sensitivity = SensitivityDrug::all();

        return $sensitivity;
    }

    public static function getAllDosageForms() {

        $dosageForms = DosageForms::orderBy('flforms', 'ASC')->get();

        return $dosageForms;
    }

    public static function getAllTaxGroup() {

        $tax_group = TaxGroup::orderBy('fldgroup')->get();

        return $tax_group;
    }

    public static function getAllDistinctCodeFromDrugs() {
        $codefromdrugs = Drug::select('fldcodename')->groupBy('fldcodename')->orderBy('fldcodename', 'ASC')->paginate(100);

        return $codefromdrugs;
    }

    public static function getDrugsFromCode($code = NULL) {

        $drugs = Drug::with('MedicineBrand')->where('fldcodename', $code)->orderBy('flddrug', 'ASC')->get();

        return $drugs;
    }

}
