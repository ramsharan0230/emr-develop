<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patientcreditcolor extends Model
{
    //
    protected $table = 'tbl_patient_credit_color';
    protected $primaryKey = 'id';
    protected $fillable = ['green_day','yellow_day','red_day'];
}
