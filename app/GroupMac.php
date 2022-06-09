<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GroupMac extends Model
{
    use LogsActivity;
    protected $table = 'group_mac';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected static $logUnguarded = true;

    public function access_comp()
    {
        return $this->hasOne('App\AccessComp', 'id','group_id');
    }

    public function request()
    {
        return $this->hasOne('App\RequestMacAccess', 'id','requestid');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
