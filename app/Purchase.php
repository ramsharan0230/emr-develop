<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Purchase extends Model
{
    use LogsActivity;
    protected $table = 'tblpurchase';
    protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';

    public $timestamps = false;
    protected static $logUnguarded = true;

    public function Entry() {
        return $this->belongsTo(Entry::class, 'fldstockno', 'fldstockno');
    }

    public function EntryByStockName() {
        return $this->belongsTo(Entry::class, 'fldstockid', 'fldstockid');
    }

    public function medbrand()
    {
        return $this->belongsTo(MedicineBrand::class, 'fldstockid', 'fldbrandid');
    }

    public function surgbrand()
    {
        return $this->belongsTo(SurgBrand::class, 'fldstockid', 'fldbrandid');
    }

    public function extrabrand()
    {
        return $this->belongsTo(ExtraBrand::class, 'fldstockid', 'fldbrandid');
    }

	public function purchaseBill()
	{
		return $this->hasOne(PurchaseBill::class, 'fldreference', 'fldreference');
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
