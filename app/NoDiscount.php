<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NoDiscount extends Model
{
    use LogsActivity;
    protected $table = 'tblnodiscount';
    protected $guarded = [];
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'flditemname';
    protected $keyType = 'string';

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
