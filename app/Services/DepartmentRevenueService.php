<?php

namespace App\Services;

use App\Department;
use App\Departmentbed;
use App\DepartmentRevenue;
use App\Utils\Helpers;
class DepartmentRevenueService
{
    public static function inserRevenueOrReturn($patDetails, $formType = 'INCOME')
    {
        $flddepartment = null;
        if($patDetails->location){
            $chkbed = Departmentbed::where('fldbed',$patDetails->location)->first();
            if($chkbed){
                $flddepartment = $chkbed->flddept;
            }else{
                $chkdepart = Department::where('flddept',$patDetails->location)->first();
                if($chkdepart){
                    $flddepartment = $chkdepart->flddept;
                }
            }
        }
        $insertData = [
            "pat_details_id" => $patDetails->fldid,
            "fldencounterval" => $patDetails->fldencounterval,
            "fldbillno" => $patDetails->fldbillno,
            "flditemamt" => Helpers::numberFormat($patDetails->flditemamt,'insert'),
            "fldtaxamt" => Helpers::numberFormat($patDetails->fldtaxamt,'insert'),
            "flddiscountamt" => Helpers::numberFormat($patDetails->flddiscountamt,'insert'),
            "flddiscountgroup" => $patDetails->flddiscountgroup,
            "fldreceivedamt" => Helpers::numberFormat($patDetails->fldreceivedamt,'insert'),
            "fldchargedamt" => Helpers::numberFormat($patDetails->fldchargedamt,'insert'),
            "form_type" => $formType,
            "hospital_department_id" => $patDetails->hospital_department_id,
            "location" => $patDetails->location,
            'bill_type' => $patDetails->bill_type,
            "xyz" => 0,
            'flddepartment' => $flddepartment
        ];
        try {
            DepartmentRevenue::create($insertData);
        } catch (\Exception $exception) {

        }
    }
}
