<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bloodbag extends Model
{
    protected $table ='tblbloodbag';
    protected $guarded =['id'];

    public function donor()
    {
        return $this->hasOne(DonorMaster::class,'donor_no','donor_id');
    }

    public function branch(){

        return $this->hasOne(HospitalBranch::class,'id','branch_id');
    }
}
