<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Entry extends Model
{
    use LogsActivity;

    protected $table = 'tblentry';

    protected $primaryKey = 'fldstockno';

    public $timestamps = false;

    protected $guarded = [];

    protected static $logUnguarded = true;
    protected $appends = ['fldexpirydateonly'];
    public function getFldexpirydateonlyAttribute()
    {
        return explode(' ', $this->fldexpiry)[0];
    }

    public function Purchase()
    {
        return $this->hasMany(Purchase::class, 'fldstockno', 'fldstockno');
    }

    public function hasPurchase()
    {
        return $this->hasOne(Purchase::class, 'fldstockno', 'fldstockno');
    }

    public function multiplePurchase()
    {
        return $this->hasMany(Purchase::class, 'fldstockid', 'fldstockid');
    }

    public function batches()
    {
        return $this->hasMany(Entry::class, 'fldstockid', 'fldstockid');
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

    public function entryBackup()
    {
        return $this->hasMany('App\Entry', 'fldstockno', 'fldstockno');
    }

    public function patBilling()
    {
        return $this->hasMany('App\PatBilling', 'flditemno', 'fldstockno');
    }

    public function patBillingByName()
    {
        return $this->hasMany('App\PatBilling', 'flditemname', 'fldstockid');
    }

    public function bulkSale()
    {
        return $this->hasMany('App\BulkSale', 'fldstockno', 'fldstockno');
    }

    public function transfer()
    {
        return $this->hasMany('App\Transfer', 'fldstockno', 'fldstockno');
    }

    public function hasTransfer()
    {
        return $this->hasOne('App\Transfer', 'fldstockno', 'fldstockno');
    }

    public function pendingTransfer()
    {
        return $this->hasMany('App\Transfer', 'fldoldstockno', 'fldstockno')->where('fldsav', 0);
    }

    public function adjustment()
    {
        return $this->hasMany('App\Adjustment', 'fldstockno', 'fldstockno');
    }

    public function accountServiceMap()
    {
        return $this->hasOne(AccountServiceCostMap::class, 'flditemname', 'fldstockid');
    }

    public function pendingStockReturn()
    {
        return $this->hasMany('App\StockReturn', 'fldstockno', 'fldstockno')->where('fldsave',0);
    }

    public function stockReturn()
    {
        return $this->hasMany('App\StockReturn', 'fldstockno', 'fldstockno')->where('fldsave',1);
    }

    public function pendingStockConsume()
    {
        return $this->hasMany('App\BulkSale', 'fldstockno', 'fldstockno')->where('fldsave',0);
    }

    public function pendingStockAdjust()
    {
        return $this->hasMany('App\Adjustment', 'fldstockno', 'fldstockno')->where('fldsav',0);
    }

}
