<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

// use Illuminate\Database\Eloquent\Builder;

class PatFindings extends Model
{
    use LogsActivity;
    protected $table = 'tblpatfindings';
    protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
    public $timestamps  = false;
    protected $fillable = ['fldencounterval', 'flddays'];
    protected static $logUnguarded = true;

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

    //Added by anish for HMIS
    public function patientInfo()
    {
        return $this->belongsTo(PatFindings::class, 'fldencounterval', 'fldencounterval')
            ->select('tblpatientinfo.*', 'tblpatfindings.fldencounterval')
            ->join('tblencounter','tblpatfindings.fldencounterval','=','tblencounter.fldencounterval')
            ->join('tblpatientinfo','tblencounter.fldpatientval','=','tblpatientinfo.fldpatientval');

//            ->leftJoin('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval');
    }

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }
}
