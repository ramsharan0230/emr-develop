<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientExam extends Model
{
    use LogsActivity;
    protected $table = 'tblpatientexam';
    public $timestamps = false; 
    protected static $logUnguarded = true;
    public $fillable = ['fldencounterval'];
    protected $primaryKey = 'fldid';

    public function patientSubExam()
    {
        return $this->hasMany(PatientSubExam::class, 'fldid')->select('fldsubtexam', 'fldreport', 'fldtanswertype', 'fldid', 'fldheadid');
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
