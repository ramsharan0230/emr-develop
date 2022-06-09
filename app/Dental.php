<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Dental extends Model
{
    use LogsActivity;
    protected $table = 'tbldental';
    public $timestamps = false;
    protected $fillable = ['fldencounterval'];
  	protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
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
}
