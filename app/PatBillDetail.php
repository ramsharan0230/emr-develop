<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatBillDetail extends Model
{
    use LogsActivity;

    protected $table = 'tblpatbilldetail';

    public $timestamps = false;

    protected $primaryKey = 'fldid';

    protected $guarded = [];

    protected static $logUnguarded = true;

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'fldpatientval', 'fldpatientval');
    }

    public function patBill()
    {
        return $this->hasMany(PatBilling::class, 'fldbillno', 'fldbillno');
    }

    public function encounter()
    {
        return $this->hasOne('App\Encounter', 'fldencounterval', 'fldencounterval');
    }

}
