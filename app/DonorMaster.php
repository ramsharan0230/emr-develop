<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonorMaster extends Model
{
    protected $guarded = ['id'];

    public function consent(){
        return $this->hasOne(Consent::class,'donor_id','donor_no');
    }

    public function bloodbag()
    {
        return $this->hasOne(Bloodbag::class,'donor_id', 'donor_no');
    }
}
