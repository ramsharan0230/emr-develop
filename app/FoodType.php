<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FoodType extends Model
{
    use LogsActivity;
    protected $table = 'tblfoodtype';

    protected $primaryKey = 'fldid';

    protected $guarded = ['fldid'];
    protected static $logUnguarded = true;

    public function FoodContent() {
        return $this->hasMany(FoodContent::class, 'fldfoodtype', 'fldfoodtype');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
