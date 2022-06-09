<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Dispenseremark extends Model
{
    use LogsActivity;
    protected $table = 'tbldispenseremarks';
    public $timestamps = false;
    protected $guarded = [];
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }
}
