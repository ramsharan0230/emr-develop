<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Departmentbed extends Model
{
    use LogsActivity;
    protected $table = 'tbldepartmentbed';
    public $timestamps = false;
    protected $primaryKey = 'fldbed';
    protected $keyType = 'string';
    protected $fillable = ['fldbed','flddept','is_oxygen','fldfloor','fldbedgroup','fldbedtype','hospital_department_id'];
    protected static $logUnguarded = true;

    public function floor()
    {
        return $this->belongsTo('App\Bedfloor', 'name', 'fldfloor');
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'flddept', 'flddept');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }
    //     });
    // }
}
