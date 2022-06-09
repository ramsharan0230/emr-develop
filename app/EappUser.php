<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EappUser extends Model
{
    use LogsActivity;
    public $timestamps = true;
}
