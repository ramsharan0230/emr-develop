<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Surgical extends Model
{
    use LogsActivity;
    protected $table = 'tblsurgicals';
    protected $primary = 'fldsurgid';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $keyType = 'string';

    public function surgicalbrands() {
        return $this->hasMany(SurgBrand::class, 'fldsurgid', 'fldsurgid');
    }
}
