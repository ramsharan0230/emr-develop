<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FoodList extends Model
{
    use LogsActivity;
    protected $table = 'tblfoodlist';

    protected $primaryKey = 'fldid';

    protected $guarded = ['fldid'];
    protected static $logUnguarded = true;

    public function FoodContent() {
        return $this->hasMany(FoodContent::class, 'fldfood', 'fldfood');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
