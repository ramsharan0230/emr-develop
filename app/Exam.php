<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Exam extends Model
{
    use LogsActivity;
    protected $table = 'tblexam';

    protected $guarded = ['fldexamid'];
    protected $keyType = 'string'; //Added by Anish because it was returning only integer becuase fldexamid is primary

    public $timestamps = false;

    protected $primaryKey = 'fldexamid';
    protected static $logUnguarded = true;

    //this will gives all finding list options
    public static function getAllExam()
    {
        return Exam::get();
    }

    public function testLimit()
    {
        return $this->hasOne(Examlimit::class, 'fldexamid', 'fldexamid');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {

    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
