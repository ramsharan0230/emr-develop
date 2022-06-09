<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use LogsActivity;
    protected $table = 'tblorder';
    public $timestamps = false;
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
    protected $guarded =[];

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'fldstockid', 'flditemname');
    }
    public function demand()
    {
        return $this->hasOne(Demand::class,'fldpono','fldreference');
    }

    public function getFldstockAttribute()
    {
    	// elect SUM(fldqty) as col from tblentry where fldstockid='Abana- 1 tab (ABANA)' and fldcomp='comp07'
        return \App\Entry::select('fldqty')->where([
                'fldstockid' => $this->flditemname,
                'fldcomp' => \App\Utils\Helpers::getCompName(),
            ])->sum('fldqty');
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
