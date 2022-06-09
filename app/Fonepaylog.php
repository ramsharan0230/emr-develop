<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Fonepaylog extends Model
{
    use LogsActivity;

    protected $table = 'fonepaylogs';
    protected $fillable = ['fldencounterval', 'fldpatientval', 'fldresponse', 'fldform','compId','fldbillno','flduser'];
}
