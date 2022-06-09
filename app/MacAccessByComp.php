<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MacAccessByComp extends Model
{
    use LogsActivity;
    protected $table = 'mac_access';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function comp_access()
    {
        $this->hasMany('App\AccessComp', 'name', 'fldcomp');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
