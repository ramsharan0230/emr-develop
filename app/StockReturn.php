<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StockReturn extends Model
{
    use LogsActivity;
    protected $table = 'tblstockreturn';

    protected $primaryKey = 'fldid';

    public $timestamps = false;

    protected $guarded= ['fldid'];

    protected static $logUnguarded = true;


    public function Entry() {
        return $this->belongsTo(Entry::class, 'fldstockno', 'fldstockno');
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
