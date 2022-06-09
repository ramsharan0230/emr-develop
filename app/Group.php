<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Group extends Model
{
    use LogsActivity;
    protected $table = 'group';

    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function group_users()
    {
        return $this->hasMany('App\UserGroup', 'group_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\CogentUser', 'user_group', 'group_id', 'user_id');
    }

    public function group_computer_access()
    {
        return $this->hasMany('App\GroupComputerAccess',  'group_id');
    }

    public function computer_access()
    {
        return $this->belongsToMany('App\AccessComp', 'group_computer_access','group_id', 'computer_access_id');
    }

    public function permission()
    {
        return $this->belongsToMany('App\PermissionReference', 'permission_groups','group_id', 'permission_reference_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //     });
    // }
}
