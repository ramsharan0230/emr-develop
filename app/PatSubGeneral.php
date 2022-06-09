<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatSubGeneral extends Model
{
    use LogsActivity;
    protected $table = 'tblpatsubgeneral';
    protected $primaryKey = 'fldid';
    public $timestamps = false;
    protected $guarded = [];//['fldencounterval', 'flditemid', 'fldchapter', 'fldreportquali', 'fldreportquanti', 'fldreport', 'flduserid', 'fldtime', 'fldcomp', 'fldsave', 'flduptime'];    
    protected static $logUnguarded = true;

    public function pathGeneral()
    {
        return $this->hasOne(PatGeneral::class, 'fldid', 'flditemid');
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
