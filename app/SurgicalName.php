<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SurgicalName extends Model
{
    use LogsActivity;
    protected $table = 'tblsurgicalname';
    protected $primaryKey = 'fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;
    public function surgicals() {
        return $this->hasMany(Surgical::class, 'fldsurgname', 'fldsurgname');
    }
}
