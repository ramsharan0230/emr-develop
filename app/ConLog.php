<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ConLog extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblconlog';

    protected $guarded = [];
    protected static $logUnguarded = true;
}
