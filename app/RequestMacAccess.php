<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RequestMacAccess extends Model
{
    use LogsActivity;
    protected $table = 'request_mac_access';

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;
}
