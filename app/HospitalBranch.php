<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalBranch extends Model
{
    use LogsActivity;
    protected $table = 'hospital_branches';

    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $fillable = ['name','address','email','contact','status','slogan','branch_code','mobile_no','show_rank','logo','ward','sanction_bed','operational_bed'];
    protected static $logUnguarded = true;

    public function hasManyDepartments() {
        return $this->hasMany(HospitalDepartment::class, 'branch_id');
    }
}
