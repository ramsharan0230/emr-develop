<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserGroup extends Model
{
    use LogsActivity;
    protected $table = 'user_group';

    protected static $logUnguarded = true;

    public function group_detail()
    {
        return $this->belongsTo('App\Group','group_id');
    }
}
