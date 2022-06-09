<?php

namespace App;

use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Bedgroup extends Model
{
    use LogsActivity;
    protected $table = 'tblbedgroup';
    protected $guarded = [];
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';

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
