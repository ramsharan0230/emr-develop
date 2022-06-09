<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceCostNew extends Model
{
    use LogsActivity;
    protected $table = 'tblservicecostsnew';
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
        'account_ledger_id',
        'fldbillitem_id',
        'fldbillsection_id',
        'fldbillingset_id',
        'created_by',
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

    public function createdUser()
    {
        return $this->belongsTo(CogentUsers::class, 'created_by');
    }

    public function updatedUser()
    {
        return $this->belongsTo(CogentUsers::class, 'updated_by');
    }
}
