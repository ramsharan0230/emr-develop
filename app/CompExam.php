<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class CompExam extends Model
{
    use LogsActivity;
	public $timestamps = false;
    protected $table = 'tblcompexam';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function examLimit()
    {
        return $this->hasOne(Examlimit::class, 'fldexamid', 'fldexamid');
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
