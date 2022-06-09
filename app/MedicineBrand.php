<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MedicineBrand extends Model
{
    use LogsActivity;

    protected $table = 'tblmedbrand';

    protected $primaryKey = 'fldbrandid';
    protected $keyType = 'string';
    protected $guarded = [];

    public $timestamps = false;
    protected static $logUnguarded = true;
    public function Drug()
    {
        return $this->belongsTo(Drug::class, 'flddrug', 'flddrug');
    }

    public function entry()
    {
        return $this->hasMany(Entry::class, 'fldstockid', 'fldbrandid');
    }

    public function qtysum()
    {
        return \App\Entry::where('fldstockid', $this->fldbrandid)->where('fldqty', '>', '0')->sum('fldqty');
    }

    public function label()
    {
        return $this->belongsTo(Label::class, 'flddrug', 'flddrug');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
