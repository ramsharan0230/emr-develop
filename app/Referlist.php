<?php

namespace App;

use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Referlist extends Model
{
     use LogsActivity;
     protected $table = 'tblreferlist';
     protected static $logUnguarded = true;
     protected $primaryKey = 'fldid';

//      protected static function boot()
//     {
//         parent::boot();
//         static::addGlobalScope('hospital_department_id', function (Builder $builder) {
//             $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
//         });
//     }
}
