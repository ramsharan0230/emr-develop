<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserDepartment extends Model
{
    use LogsActivity;
    protected $table ='department_users';
    protected static $logUnguarded = true;
}
