<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testresult extends Model
{
   protected $table ='tblbloodbanktestresult';
   protected $guarded=['id'];

   public function donor()
   {
       return $this->belongsTo(DonorMaster::class,'donor_no','donor_id');
   }
   public function consent()
   {
       return $this->belongsTo(Consent::class, 'donor_id','donor_id');
   }

   public  function bloodbag(){
       return $this->hasOne(Bloodbag::class,'donor_id','donor_id');
   }
}
