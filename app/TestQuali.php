<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TestQuali extends Model
{
    use LogsActivity;
    protected $table = "tbltestquali";
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function subtests()
    {
        return $this->hasMany(SubTestQuali::class, 'fldsubtest', 'fldsubtest');
    }

    public function templates()
    {
        return $this->belongsToMany('App\EmailTemplate', 'template_test_quali', 'test_quali_id','template_id', 'fldid', 'id');
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
