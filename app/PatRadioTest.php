<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class PatRadioTest extends Model
{
    use LogsActivity;
    protected $table = 'tblpatradiotest';
    public $timestamps  = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function radioData()
    {
        return $this->hasMany(Radio::class,'fldexamid', 'fldtestid');
    }

    public function radio()
    {
        return $this->hasOne(Radio::class,'fldexamid', 'fldtestid');
    }

    public function radioSubTest()
    {
        return $this->hasMany(PatRadioSubTest::class,'fldtestid', 'fldid');
    }

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function reportedBy()
    {
        return $this->hasOne(CogentUsers::class, 'username', 'flduserid_report');
    }

    public function verifiedBy()
    {
        return $this->hasOne(CogentUsers::class, 'username', 'flduserid_verify');
    }

    public function macaccess()
    {
        return $this->hasOne(MacAccess::class, 'fldcomp', 'fldcomp_report');
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
