<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalDepartmentUsers extends Model
{
    use LogsActivity;
    protected $table = 'hospital_department_users';

    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $fillable = ['department_id','user_id'];
    protected static $logUnguarded = true;

    public function departmentData() {
        return $this->belongsTo(HospitalDepartment::class, 'hospital_department_id');
    }

    public function userData() {
        return $this->belongsTo(CogentUsers::class, 'user_id');
    }
}
