<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Claim extends Model
{
    use LogsActivity;
    protected $guarded = ['id'];
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';
    public function insurancetype()
    {
        return $this->belongsTo(Insurancetype::class, 'insurance_type_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
