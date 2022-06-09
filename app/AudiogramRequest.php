<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AudiogramRequest extends Model
{
    use LogsActivity;
    protected $table = 'audiogram_requests';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    protected static $logUnguarded = true;

    public function user()
    {
        return $this->belongsTo('App\CogentUsers', 'requested_by');
    }

    public function examiner()
    {
        return $this->belongsTo('App\CogentUsers', 'examined_by');
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'encouter_id', 'fldencounterval');
    }
}
