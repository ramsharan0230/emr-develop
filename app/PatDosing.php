<?php

namespace App;

use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatDosing extends Model
{
    use LogsActivity;
    protected $table = 'tblpatdosing';
    protected $guarded = ['fldid'];
    protected $primaryKey='fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;

    protected $appends = ['fldtotal', 'flddiscamt', 'fldtaxamt', 'fldformatstarttime'];
    public function getFldtotalAttribute()
    {
        if ($this->medicineBySetting) {
            $total = $this->medicineBySetting->fldsellpr*$this->fldqtydisp;
            $discount = ($this->flddiscper/100)*$total;
            $tax = ($this->fldtaxper/100)*$total;

            return $total + $tax - $discount;
        }

        return 0;
    }
    public function getFlddiscamtAttribute()
     {
     $discount = 0;
     if ($this->medicineBySetting) {
     $total = $this->medicineBySetting->fldsellpr*$this->fldqtydisp;
     $discount = ($this->flddiscper/100)*$total;
     }
     
     return $discount;
     }
     
     public function getFldtaxamtAttribute()
     {
     $tax = 0;
     if ($this->medicineBySetting) {
     $total = $this->medicineBySetting->fldsellpr*$this->fldqtydisp;
     $tax = ($this->fldtaxper/100)*$total;
     }
     
     return $tax;
     }
    public function getFldformatstarttimeAttribute()
    {
        return explode(' ', $this->fldstarttime)[0];
    }

    public function nursedosing()
    {
        return $this->hasMany(NurseDosing::class,'flddoseno','fldid');
    }

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function medicine()
    {
        return $this->belongsTo(Entry::class, 'flditem', 'fldstockid');
    }

    public function medicineBySetting()
    {
        $dispensing_medicine_stock = \App\Utils\Options::get('dispensing_medicine_stock');
        $expiry = date('Y-m-d H:i:s');
        $orderString = "tblentry.fldstockno DESC";
        
        if ($dispensing_medicine_stock == 'FIFO')
            $orderString = "tblentry.fldstatus ASC";
        elseif ($dispensing_medicine_stock == 'LIFO')
            $orderString = "tblentry.fldstatus DESC";
        elseif ($dispensing_medicine_stock == 'Expiry') {
            $days = \App\Utils\Options::get('dispensing_expiry_limit');
            if ($days)
                $expiry = date('Y-m-d H:i:s', strtotime("+{$days} days", strtotime($expiry)));
            $orderString = "tblentry.fldexpiry ASC";
        }

        return $this->hasOne(Entry::class, 'fldstockno', 'fldstockno')->where([
            ['tblentry.fldexpiry', '>=', $expiry],
            ['tblentry.fldqty', '>', '0'],
            ['tblentry.fldstatus', '<>', '0'],
            ['tblentry.fldsav', '=', '1'],
            ['tblentry.fldcomp', Helpers::getCompName()],
        ])->orderByRaw($orderString);
    }

    #medicinebystock rate added
    public function medicineByStockRate()
    {
        $dispensing_medicine_stock = \App\Utils\Options::get('dispensing_medicine_stock');
        $expiry = date('Y-m-d H:i:s');
        $orderString = "tblentry.fldstockno DESC";
        
        if ($dispensing_medicine_stock == 'FIFO')
            $orderString = "tblentry.fldstatus ASC";
        elseif ($dispensing_medicine_stock == 'LIFO')
            $orderString = "tblentry.fldstatus DESC";
        elseif ($dispensing_medicine_stock == 'Expiry') {
            $days = \App\Utils\Options::get('dispensing_expiry_limit');
            if ($days)
                $expiry = date('Y-m-d H:i:s', strtotime("+{$days} days", strtotime($expiry)));
            $orderString = "tblentry.fldexpiry ASC";
        }

        return $this->hasOne(StockRate::class, 'flddrug', 'flditem');
        // ->orderByRaw($orderString);
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
