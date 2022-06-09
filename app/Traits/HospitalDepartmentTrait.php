<?php

namespace App\Traits;

use App\HospitalDepartment;

trait HospitalDepartmentTrait
{
    public function getFldCompName($departmentId): string
    {
        if ($departmentId) {
            $hospital_department = HospitalDepartment::find($departmentId);
            return $hospital_department->fldcomp ?? "";
        }

        return "";
    }
}
