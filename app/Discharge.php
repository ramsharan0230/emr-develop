<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discharge extends Model
{
    protected $table = 'discharges';
    // public $timestamps = false;
    protected $fillable = ['fldencounterval','fldtype','fldvalue'];
  	protected $guarded = ['fldid'];
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;

}
