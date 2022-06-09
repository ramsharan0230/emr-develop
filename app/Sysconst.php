<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Sysconst extends Model
{
    use LogsActivity;
    protected $table = 'tblsysconst';
    protected $primaryKey = 'fldsysconst';
    protected static $logUnguarded = true;
    protected $keyType = 'string';
}
