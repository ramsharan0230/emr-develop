<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Famous Media.
 *
 * @property int $pat_billing_id
 * @property string $type
 * @property int $status
 */

class PatBillingShare extends Model
{
    use LogsActivity;
    protected $table = "pat_billing_shares";
    protected $fillable = ['pat_billing_id','type','status','user_id','share','ot_group_sub_category_id','sync','tax_amt','fldupdatedby'];
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';

    public function pat_billing()
    {
        return $this->belongsTo(PatBilling::class, 'pat_billing_id', 'fldid');
    }

    public function user()
    {
        return $this->belongsTo(CogentUsers::class, 'user_id', 'id');
    }
}
