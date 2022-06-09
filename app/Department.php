<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use LogsActivity;
    protected $table = 'tbldepartment';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function users()
    {
        return $this->belongsToMany('App\CogentUsers', 'department_users',  'department_id','user_id', 'fldid', 'id');
    }

    public function eapp()
    {
        return $this->hasOne('App\EappDept', 'dept_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }
    //     });
    // }
}
