<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatLabTest extends Model
{
    use LogsActivity;
    protected $table = 'tblpatlabtest';
    protected $primaryKey = 'fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $guarded = [];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope(new \App\Scopes\TblpatlabtestDeletedScope);
    // }

    public function patientEncounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function patbill()
    {
        return $this->hasOne(PatBilling::class, 'fldid', 'fldgroupid');
    }

    public function patTestResults()
    {
        return $this->hasMany(Test::class, 'fldtestid', 'fldtestid');
    }

    public function subTest()
    {
        return $this->hasMany(PatLabSubTest::class, 'fldtestid', 'fldid');
    }

    public function testLimit()
    {
        return $this->hasMany(TestLimit::class, 'fldtestid', 'fldtestid')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldptsex', '=', 'Male')
                    ->orWhere('fldptsex', '=', 'Both Sex');
            })
            ->where(function ($query) {
                return $query
                    ->orWhere('fldagegroup', '=', 'Adolescent')
                    ->orWhere('fldagegroup', '=', 'All Age');
            });
    }

    public function testLimitAll()
    {
        return $this->hasMany(TestLimit::class, 'fldtestid', 'fldtestid');
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'fldtestid', 'fldtestid');
    }

    public function bill()
    {
        return $this->belongsTo(PatBilling::class, 'fldgroupid', 'fldid');
    }

    public function refrename()
    {
        return $this->belongsTo(CogentUsers::class, 'fldrefername', 'username');
    }

    public function macaccess()
    {
        return $this->hasOne(MacAccess::class, 'fldcomp', 'fldcomp_sample');
    }

    public function testgroup()
    {
        return $this->hasOne(TestGroup::class, 'fldtestid', 'fldtestid');
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

    public function tracking()
    {
        return $this->hasMany(SampleTracking::class, 'sample_id', 'fldsampleid');
    }

}
