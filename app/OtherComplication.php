<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OtherComplication extends Model
{
    use LogsActivity;
    protected $table = 'hmis_other_complication';
    protected $guarded =['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
}
