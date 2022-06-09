<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Drug extends Model
{
    use LogsActivity;
    protected $table = 'tbldrug';
    protected $primaryKey = 'flddrug';
    protected $keyType = 'string';

    public $timestamps = false;
    protected static $logUnguarded = true;

    public function MedicineBrand() {
        return $this->hasMany(MedicineBrand::class, 'flddrug', 'flddrug');
    }

    public function Code() {
        return $this->belongsTo(Code::class, 'fldcodename');
    }

    public function Label() {
        return $this->hasMany(Label::class, 'flddrug', 'flddrug');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
