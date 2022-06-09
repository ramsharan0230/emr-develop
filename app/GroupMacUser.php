<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GroupMacUser extends Model
{
    use LogsActivity;
    protected $table = 'group_mac_user';
    protected $guarded = [];
    protected static $logUnguarded = true;

    public function request(){
        return $this->hasOne('App\RequestMacAccess', 'request_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
