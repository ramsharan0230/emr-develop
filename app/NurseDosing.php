<?php

namespace App;
use App\Pathdosing;
// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NurseDosing extends Model
{
    use LogsActivity;
   protected $table = 'tblnurdosing';
   protected $guarded = ['fldid'];
   protected $primaryKey = 'fldid';
   public $timestamps =false;
   protected static $logUnguarded = true;

   public function getName()
   {
       return $this->hasOne(Pathdosing::class,'fldid','flddoseno');
   }

   public function examgeneral()
   {
       return $this->belongsTo(Notes::class,'fldencounterval','fldencounterval')->where('fldinput','Extra')->where('fldtype','Qualitative');
   }

   // protected static function boot()
   //  {
   //      parent::boot();
   //      static::addGlobalScope('hospital_department_id', function (Builder $builder) {
   //         if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
   //            //do nothing
   //         }else{
   //          $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
   //         }
   //      });
   //  }
}
