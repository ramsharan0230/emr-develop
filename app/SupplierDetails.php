<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierDetails extends Model
{
    use LogsActivity;
    protected $table = 'tblsupplierdetails';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';

    public function purchaseBill(){
      return  $this->belongsTo(PurchaseBill::class,'fldbillno','fldbillno');
    }
    /*For fetching unique data of purchase bill data */
    public function purchaseBillReference(){
        return  $this->belongsTo(PurchaseBill::class,'fldpurreference','fldreference');
    }

}
