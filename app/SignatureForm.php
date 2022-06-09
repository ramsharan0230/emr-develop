<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SignatureForm extends Model
{
    use LogsActivity;
    protected $table = 'signature_form';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';

    public function user_signature()
    {
        return $this->hasOne('App\CogentUsers', 'id', 'user_id');
    }
}
