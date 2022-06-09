<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsumeReturn extends Model
{
    use LogsActivity;
    protected $table = 'consume_returns';

    protected $primaryKey = 'fldid';

    public $timestamps = false;

    protected $guarded= ['fldid'];

    protected static $logUnguarded = true;


    public function Entry() {
        return $this->belongsTo(Entry::class, 'fldstockno', 'fldstockno');
    }
}
