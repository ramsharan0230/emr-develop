<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceGroup extends Model
{
    use LogsActivity;
    protected $table = 'tblservicegroup';
    protected $primaryKey = 'fldid';
	protected $guarded = ['fldid']; //modified by anish
    public $timestamps = false;
    protected static $logUnguarded = true;

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }

    /**
     * refrecing relation to service Cost from tblflditemname column
     */
    public function serviceCost()
    {
        return $this->belongsTo(ServiceCost::class, 'flditemname', 'flditemname');
    }
}

