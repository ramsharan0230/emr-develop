<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Transfer extends Model
{
    use LogsActivity;
    protected $table = 'tbltransfer';

    protected $guarded = [];
    protected static $logUnguarded = true;

    public $timestamps = false;

    protected $primaryKey = 'fldid';
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

    public function fromBranch()
    {
        return $this->hasOne(HospitalBranch::class, 'id', 'from_branch');
    }

    public function fromDepartment()
    {
        return $this->hasOne(HospitalDepartment::class, "fldcomp", "fldfromcomp");
    }

    public function toDepartment()
    {
        return $this->hasOne(HospitalDepartment::class, "fldcomp", "fldtocomp");
    }

    public function Entry() {
        return $this->belongsTo(Entry::class, 'fldstockno', 'fldstockno');
    }

    public function batch() {
        return $this->belongsTo(Entry::class, 'fldoldstockno', 'fldstockno');
    }

    public function brand()
    {
        return $this->belongsTo(MedicineBrand::class,'fldstockid','fldbrandid');
    }

    public function extraBrand()
    {
        return $this->belongsTo(ExtraBrand::class,'fldstockid','fldbrandid');
    }

    public function surgicalBrand()
    {
        return $this->belongsTo(SurgBrand::class,'fldstockid','fldbrandid');
    }

    public function loopOldStockno(){
        return  $this->belongsTo(self::class,'fldstockno','fldoldstockno')->with('loopOldStockno');
    }

}
