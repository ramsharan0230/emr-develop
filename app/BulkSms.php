<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkSms extends Model
{
    protected $table = 'tblbulksms';
    protected $fillable = ['fldtype','fldsubtype','fldmessage','status','from_date','to_date'];
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
