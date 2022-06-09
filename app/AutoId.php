<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AutoId extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblautoid';

    protected $guarded = [];

    protected static $logUnguarded = true;

    protected $primaryKey = 'fldtype';
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
