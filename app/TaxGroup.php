<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TaxGroup extends Model
{
    use LogsActivity;
    protected $table = 'tbltaxgroup';

    protected $guarded = ['fldid'];

    protected $primaryKey = 'fldid';

    public $timestamps = false;
    protected static $logUnguarded = true;
}
