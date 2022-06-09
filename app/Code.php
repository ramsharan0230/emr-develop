<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Code extends Model
{
    use LogsActivity;
    protected $table = 'tblcode';

    protected $primary = 'fldcodename';

    public $timestamps = false;

    protected $guarded = [''];
    protected static $logUnguarded = true;
    protected $keyType = 'string';

    public static function getAllCode()
    {
        return Code::get();
    }

    public function Drug() {
        return $this->hasMany(Drug::class, 'fldcodename', 'fldcodename');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
