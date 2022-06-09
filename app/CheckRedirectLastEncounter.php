<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CheckRedirectLastEncounter extends Model
{
    use LogsActivity;
    protected $table = 'check_redirect_to_last_encounter';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $fillable = ['user_id','fld_redirect_encounter'];
    protected static $logUnguarded = true;

}
