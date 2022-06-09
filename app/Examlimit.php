<?php

namespace App;

use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Examlimit extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblexamlimit';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {

    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
