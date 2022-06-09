<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserFormAccess extends Model
{
    use LogsActivity;
    protected $table = 'tbluserformaccess';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function userName()
    {
        return $this->hasOne('App\User', 'flduserid', 'flduserid');
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
