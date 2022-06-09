<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FoodContent extends Model
{
    use LogsActivity;
    protected $table = "tblfoodcontent";

    protected $guarded = ['fldfoodid'];

   protected $primaryKey = 'fldfoodid';
   protected $keyType = 'string';
    public $timestamps = false;
    protected static $logUnguarded = true;

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
