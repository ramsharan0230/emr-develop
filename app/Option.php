<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Option extends Model
{
    use LogsActivity;
    protected $table = 'options';

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected static $logUnguarded = true;
}
