<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SurgBrand extends Model
{
    use LogsActivity;
	protected $table = 'tblsurgbrand';
    protected $primary = 'fldbrandid';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $keyType = 'string';

    public function Surgical() {
        return $this->belongsTo(Surgical::class, 'fldsurgid', 'fldsurgid');
    }

    public function entry()
    {
        return $this->hasMany(Entry::class, 'fldstockid', 'fldbrandid');
    }
    
    public function qtysum()
    {
        return \App\Entry::where('fldstockid', $this->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
    }
}
