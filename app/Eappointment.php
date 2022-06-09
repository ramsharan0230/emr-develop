<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Eappointment extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'eappointment';

    protected $guarded = [];

    protected static $logUnguarded = true;

    protected $primaryKey = 'id';

 
}
