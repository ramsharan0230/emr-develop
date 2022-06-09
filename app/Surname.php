<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Surname extends Model
{
    use LogsActivity;
    protected $table = 'tblsurname';

    protected $guarded = ['fldid'];

    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;

    public function getFlditemAttribute($value)
    {
        return strtoupper($value);
    }
}
