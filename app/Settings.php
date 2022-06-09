<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Settings extends Model
{
    use LogsActivity;
    protected $table = 'tblsettings';

    protected $guarded = [];
    protected $primaryKey = 'fldindex';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $keyType = 'string';
}
