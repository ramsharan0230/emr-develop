<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BulkSale extends Model
{
    use LogsActivity;
    protected $table = 'tblbulksale';

    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;

    public function stock()
    {
        return $this->hasOne('App\Entry', 'fldstockno', 'fldstockno');
    }

    public function Entry() {
        return $this->belongsTo(Entry::class, 'fldstockno', 'fldstockno');
    }

    public function EntryByStockName() {
        return $this->belongsTo(Entry::class, 'fldstockid', 'fldstockid');
    }

    public function pendingConsumeReturn()
    {
        return $this->hasMany('App\ConsumeReturn', 'fldstockno', 'fldstockno')->where('fldsave',0);
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
