<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Fiscalyear extends Model
{
    use LogsActivity;
    protected $table = 'tblfiscal';
    protected $guarded = ['field'];
    protected $primaryKey = 'field';
    public $timestamps = false;
    protected static $logUnguarded = true;
}
