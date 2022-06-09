<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consent extends Model
{
  protected $table='tblconsents';
  protected $guarded = ['id'];

  public function donor()
  {
      return $this->belongsTo(DonorMaster::class,'donor_id','donor_no');
  }

}
