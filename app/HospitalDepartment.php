<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalDepartment extends Model
{
    use LogsActivity;
    protected $table = 'hospital_departments';

    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $fillable = ['name','email','contact','status','branch_id','parent_department_id','fldcomp'];
    protected static $logUnguarded = true;

    public function parentDepartment() {
        return $this->belongsTo(HospitalDepartment::class, 'parent_department_id');
    }

    public function hospitalDepartmentUser() {
        return $this->hasMany(HospitalDepartmentUsers::class, 'hospital_department_id');
    }

    public function childDepartments() {
        return $this->hasMany(HospitalDepartment::class, 'parent_department_id');
    }

    public function branchData() {
        return $this->belongsTo(HospitalBranch::class, 'branch_id');
    }
}
