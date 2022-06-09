<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PathoSymp extends Model
{
    use LogsActivity;
    protected $table = 'tblpathosymp';

    protected $primaryKey = 'flid';
    protected static $logUnguarded = true;
}
