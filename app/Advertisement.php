<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Advertisement extends Model
{
    use LogsActivity;
    protected $table = 'tbladlink';
    protected $fillable = ['title','description','image'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }

}
