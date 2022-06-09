<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TempDoctorExpenses extends Model
{
    use LogsActivity;
    protected $table = 'temp_doctor_fraction';

    protected $guarded = [];

    protected static $logUnguarded = true;

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function accountLedger()
    {
        return $this->belongsTo('App\AccountLedger', 'AccountNo', 'AccountNo');
    }
}
