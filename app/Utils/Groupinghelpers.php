<?php


namespace App\Utils;


use App\BillingSet;
use App\RadioGroup;
use App\TestGroup;

class Groupinghelpers
{

    public static function  getBillingSet() {
        $billingset = BillingSet::all();

        return $billingset;
    }

    public static function getAlltestsfromgroup($fldgroupname, $type = NULL) {

        $tests = array();

        if($type == 'radio') {
            $tests = RadioGroup::where('fldgroupname', $fldgroupname)->orderBy('fldtestid', 'ASC')->get();
        } else if($type == 'lab') {
            $tests = TestGroup::where('fldgroupname', $fldgroupname)->orderBy('fldtestid', 'ASC')->get();
        }


        return $tests;
    }
}
