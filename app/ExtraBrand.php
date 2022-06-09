<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExtraBrand extends Model
{
    use LogsActivity;
    protected $table = "tblextrabrand";

    protected $primaryKey = 'fldbrandid';
    protected $keyType = 'string';
    public $timestamps = false;
    protected static $logUnguarded = true;
    public function entry()
    {
        return $this->hasMany(Entry::class, 'fldstockid', 'fldbrandid');
    }
    public function qtysum()
    {
        return \App\Entry::where('fldstockid', $this->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
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
