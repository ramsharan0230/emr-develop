<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceCost extends Model
{
    use LogsActivity;
    protected $table = 'tblservicecost';
    protected $primaryKey = 'fldid';
    protected $fillable = [
        'flditemname',
        'fldbillitem',
        'flditemcost',
        'fldcode',
        'fldgroup',
        'fldreport',
        'fldstatus',
        'fldtarget',
        'fldtime',
        'fldcomp',
        'flditemtype',
        'hospital_department_id',
        'category',
        'rate',
        'discount',
        'hospital_share',
        'other_share',
        'account_ledger',
        'flddescription',
        'flduserid',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'category' => 'array'
    ];

    protected static $logUnguarded = true;

    public function accountServiceMap()
    {
        return $this->hasOne(AccountServiceCostMap::class, 'flditemname');
    }

    /**
     * service cost has many relation to custome discount
     */
    public function customeDiscounts()
    {
        return $this->hasMany(CustomDiscount::class, 'flditemtype', 'flditemtype' );
    }

    public function userPay()
    {
        return $this->hasOne(UserShare::class,'flditemname','flditemname');
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
