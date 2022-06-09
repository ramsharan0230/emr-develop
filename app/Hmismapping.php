<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hmismapping extends Model
{
    use LogsActivity;
    protected $table = 'hmis_mapping';
    protected $guarded=['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
}
