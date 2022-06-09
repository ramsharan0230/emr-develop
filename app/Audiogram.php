<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Audiogram extends Model
{
    use LogsActivity;
    protected $table = 'audiograms';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
}
