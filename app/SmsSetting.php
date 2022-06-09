<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $table = 'tblsmssetting';
    protected $primaryKey = 'id';
    protected $fillable = ['sms_type','sms_name','status','free_follow_up_day','deposit_condition','deposit_mode'
                        ,'deposit_percentage','deposit_amount','events_condition','visit_per_year','test_name','test_status','test_details','sms_details'];
    // protected $fillable = [];
}
