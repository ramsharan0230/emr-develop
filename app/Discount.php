<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Discount extends Model
{
    use LogsActivity;
    protected $table = 'tbldiscount';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldtype';
    protected $keyType = 'string';

    protected $guarded = [];

    public function discount_ledger_map()
    {
        return $this->hasOne(DiscountLedgerMap::class, 'discount_name', 'fldtype');
    }

    public function cogentUser()
    {
        return $this->belongsTo(CogentUsers::class, 'updated_by', 'id');
    }

    //  protected static function boot()
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
