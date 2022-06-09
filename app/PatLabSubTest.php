<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatLabSubTest extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblpatlabsubtest';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function quantity_range()
    {
        return $this->belongsTo(TestQuali::class, 'fldsubtest', 'fldsubtest');
    }

    public function subtables()
    {
        return $this->hasMany(PatLabSubTable::class, 'fldsubtestid', 'fldid');
    }

    public function pattest()
    {
        return $this->belongsTo(PatLabTest::class, 'fldtestid', 'fldid');
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
