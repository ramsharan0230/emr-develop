<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessComp extends Model
{
    use LogsActivity;
    protected $table = 'access_comp';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    protected static $logUnguarded = true;

    public function group()
    {
        return $this->belongsToMany('App\Group', 'group_computer_access','computer_access_id', 'group_id');
    }

    public function mac_access()
    {
        return $this->hasMany('App\MacAccessByComp', 'fldcomp', 'name');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
