<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TempConsult extends Model
{
    use LogsActivity;

    protected $table = 'temp_consults';
    protected $fillable = ['fldencounterval','pat_billing_id','flddept','flddoctor','flduserid','fldcomp'];

}
