<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Test extends Model
{
    use LogsActivity;
    protected $table = 'tbltest';

    protected $primary = 'fldtestid';

    protected $guarded = [''];
    protected $keyType = 'string';
    public $timestamps = false;
    protected static $logUnguarded = true;

    public function testoptions()
    {
        return $this->hasMany(TestOption::class, 'fldtestid', 'fldtestid');
    }
    public function methods()
    {
        return $this->hasMany(TestMethod::class, 'fldtestid', 'fldtestid');
    }

    public function subtests()
    {
        return $this->hasMany(TestQuali::class, 'fldtestid', 'fldtestid');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {

    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
