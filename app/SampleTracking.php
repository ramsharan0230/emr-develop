<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SampleTracking extends Model
{
    use LogsActivity;
    protected $table = 'sample_tracking';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected static $logUnguarded = true;
}
