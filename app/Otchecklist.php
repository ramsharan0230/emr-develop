<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Otchecklist extends Model
{
    use LogsActivity;
   protected static $logUnguarded = true;
   protected $table = 'otchecklists';
   protected $guarded = [];

   public function signinuser()
    {
        return $this->belongsTo(CogentUsers::class, 'fldsigninuser', 'username');
    }

    public function timeoutuser()
    {
        return $this->belongsTo(CogentUsers::class, 'fldtimeoutuser', 'username');
    }

    public function signoutuser()
    {
        return $this->belongsTo(CogentUsers::class, 'fldsignoutuser', 'username');
    }
}
