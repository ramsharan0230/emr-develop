<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Haemodialysis extends Model
{
  use LogsActivity;
    protected $table = 'tblhaemodialysis';
    // public $timestamps = false;
    protected $fillable = ['fldencounterval','fldtype','flditem','fldvalue'];
  	protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
}
