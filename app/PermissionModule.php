<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PermissionModule extends Model
{
    use LogsActivity;
    protected $table = 'permission_modules';

    protected $guarded = [ 'id' ];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function permission_references()
    {
        return $this->hasMany('App\PermissionReference','permission_modules_id');
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
