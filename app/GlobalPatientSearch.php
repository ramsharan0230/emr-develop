<?php
  
namespace App;
  
use Illuminate\Database\Eloquent\Model;
 
class GlobalPatientSearch extends Model
{
    public $table = "global_patient_search";

    public function encounter()
    {
        return $this->hasMany('App\Encounter', 'fldpatientval', 'fldpatientval');
    }
}