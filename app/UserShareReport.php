<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserShareReport extends Model
{
    use LogsActivity;
    protected $table = 'pat_billing_shares_report';
    protected static $logUnguarded = true;

    protected $appends = ['hospital_payment', 'doctor_payment', 'share_amount', 'amount_after_share_tax'];
    // hospital_payment doctor_payment share_amount amount_after_share_tax

    public function user()
    {
        return $this->belongsTo(CogentUsers::class, 'user_id', 'id');
    }

    public function getHospitalPaymentAttribute()
    {
        return ($this->hospital_share / 100) * $this->fldditemamt ?? 0;
    }

    public function getDoctorPaymentAttribute()
    {
        return ($this->other_share / 100) * $this->fldditemamt ?? 0;
    }

    public function getShareAmountAttribute()
    {
        return (($this->share / 100) * ($this->other_share / 100) * $this->fldditemamt) ?? 0;
    }

    public function getAmountAfterShareTaxAttribute()
    {
        return (($this->share / 100) * ($this->other_share / 100) * $this->fldditemamt) - (($this->flditemtax / 100) * ($this->share / 100) * ($this->other_share / 100) * $this->fldditemamt) ?? 0;
    }
}
