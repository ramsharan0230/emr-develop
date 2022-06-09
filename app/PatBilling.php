<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatBilling extends Model
{
    use LogsActivity;

    protected $table = 'tblpatbilling';
    protected $primaryKey = 'fldid';

    public $timestamps = false;

    protected $guarded = [];
    protected static $logUnguarded = true;

    protected $appends = ['fldgross'];

    public function getFldgrossAttribute()
    {
        return $this->flditemrate * $this->flditemqty;
    }

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function medicine()
    {
        return $this->belongsTo(Entry::class, 'flditemname', 'fldstockid');
    }

    public function discount_account_map()
    {
        return $this->belongsTo(DiscountLedgerMap::class, 'discount_mode', 'discount_name');
    }

    /*    public function macaccess()
        {
            return $this->hasOne(MacAccess::class, 'fldcomp', 'fldordcomp');
        }*/
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

    public function pat_billing_shares()
    {
        return $this->hasMany(PatBillingShare::class, 'pat_billing_id', 'fldid');
    }

    public function serviceCost()
    {
        return $this->belongsTo(ServiceCost::class, 'flditemname', 'flditemname');
    }

    public function service_cost(): ?ServiceCost
    {
        $service_cost = ServiceCost::where([
            'flditemname' => $this->flditemname,
            'flditemtype' => $this->flditemtype,
        ])->first();

        return $service_cost;
    }

    public function accountServiceMap()
    {
        return $this->hasOne(AccountServiceCostMap::class, 'flditemname', 'flditemname');
    }

    public function noDiscount()
    {
        return $this->hasOne(NoDiscount::class, 'flditemname', 'flditemname');
    }

    public function billDetail()
    {
        return $this->hasOne(PatBillDetail::class, 'fldbillno', 'fldbillno');
    }

    public function tempBillDetail()
    {
        return $this->hasOne(TempPatbillDetail::class, 'fldbillno', 'fldtempbillno');
    }

    public function parentDetail()
    {
        return $this->hasOne(PatBilling::class, 'fldid', 'fldparent');
    }

    public function referUserdetail()
    {
        return $this->hasOne(CogentUsers::class, 'username', 'fldrefer');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'flditemname', 'fldstockid');
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'flditemname', 'fldstockid');
    }

    public function extraBrand()
    {
        return $this->belongsTo(ExtraBrand::class, 'flditemname', 'fldbrandid');
    }

    public function brand()
    {
        return $this->belongsTo(MedicineBrand::class,'flditemname','fldbrandid');
    }

    public function surgicalBrand()
    {
        return $this->belongsTo(SurgBrand::class,'flditemname','fldbrandid');
    }

    public function stockReturn()
    {
        return $this->belongsTo(StockReturn::class,'flditemname','fldstockid');
    }

    public function bulkSale()
    {
        return $this->belongsTo(BulkSale::class,'flditemname','fldstockid');
    }

    public function userPay()
    {
        return $this->hasOne(UserShare::class,'flditemname','flditemname');
    }

}
