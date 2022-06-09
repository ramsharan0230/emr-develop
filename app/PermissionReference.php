<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PermissionReference extends Model
{
    use LogsActivity;
    protected $table = 'permission_references';

    protected $guarded = [ 'id' ];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function permission_groups()
    {
        return $this->hasMany('App\PermissionGroup','permission_reference_id');
    }

    public function group()
    {
        return $this->belongsToMany('App\Group', 'permission_groups', 'permission_reference_id', 'group_id');
    }

    /**
     * linking relation to SideBarMenu models submenu attribute from PermissionRefrence model short_desc attr.
     * @param void
     *
     */
    public function permissionRefrenceSideBarMenu()
    {
        return $this->belongsTo(SidebarMenu::class, 'short_desc', 'submenu');
    }

    public function permissionModule()
    {
        return $this->belongsTo(PermissionModule::class, 'permission_module_id', 'id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }
    //     });
    // }
}
