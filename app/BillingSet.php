<?php

namespace App;

// use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class BillingSet extends Model
{
    use LogsActivity;
    protected $table = 'tblbillingset';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldsetname';
    protected $keyType = 'string';

    public $timestamps = false;
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

    /*public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
            ->where('status', '=', 1);
    }*/

}
