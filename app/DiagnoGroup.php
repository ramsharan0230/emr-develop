<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class DiagnoGroup extends Model
{
    use LogsActivity;
    protected $table = 'tbldiagnogroup';
    protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
