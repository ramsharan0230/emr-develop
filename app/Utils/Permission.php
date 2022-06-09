<?php
namespace App\Utils;

use App\CogentUsers;
use App\PermissionGroup;
use App\PermissionModule;
use Auth;
use App\PermissionReference;
use App\SidebarMenu;

class Permission
{
    // CHECKING USER PERMISSION TO ACCESS THE MODULE
    public static function checkPermission ( $module_name = null )
    {
        try {

            if ( $module_name == null ) {
                return false;
            }

            if ( !Auth::guard('admin')->check() ) {
                return false;
            }

            // if super admin return true;
            if ( Auth::guard('admin')->user()->user_is_superadmin->count() > 0 ) {
                return true;
            }

//            $module_details = PermissionReference::with('permission_groups')->where('code', $module_name)->first();
            $module_details = PermissionReference::with('permission_groups')->where('code', $module_name)->first();

            if ( !$module_details ) {
                return false;
            }

            $permission_grp_ids = $module_details->permission_groups ? $module_details->permission_groups->pluck('group_id')->toArray() : [];
            $user_grp_ids = Auth::guard('admin')->user()->user_group ? Auth::guard('admin')->user()->user_group->pluck('group_id')->toArray() : [];

            return count(array_intersect($permission_grp_ids, $user_grp_ids)) > 0 ? true : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function checkPermissionFrontendAdmin ( $module_name = null )
    {
        try {
            if ( $module_name == null ) {
                return false;
            }

            if ( !Auth::guard('admin_frontend')->check() ) {
                return false;
            }

            // if super admin return true;
            if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
                return true;
            }

//            $module_details = PermissionReference::with('permission_groups')->where('code', $module_name)->first();
            $module_details = PermissionReference::with('permission_groups')->where('code', $module_name)->first();

            if ( !$module_details ) {
                return false;
            }

            $permission_grp_ids = $module_details->permission_groups ? $module_details->permission_groups->pluck('group_id')->toArray() : [];
            $user_grp_ids = Auth::guard('admin_frontend')->user()->user_group ? Auth::guard('admin_frontend')->user()->user_group->pluck('group_id')->toArray() : [];

            return count(array_intersect($permission_grp_ids, $user_grp_ids)) > 0 ? true : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function checkFrontendAdminModulePermission ( $module_name = null )
    {
        try {
            if ( $module_name == null ) {
                return false;
            }

            // if ( !Auth::guard('admin_frontend')->check() ) {
            //     return false;
            // }

            // if super admin return true;
            // if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
            //     return true;
            // }

            $submenus = SidebarMenu::where('mainmenu',$module_name)->pluck('submenu')->toArray();
            if (count($submenus) == 0) {
                return false;
            }
            $permission_module_ids = PermissionModule::whereIn('name',$submenus)->pluck('id')->toArray();
            if (count($permission_module_ids) == 0) {
                return false;
            }
            $permission_reference_ids = PermissionReference::whereIn('permission_modules_id', $permission_module_ids)->pluck('id')->toArray();
            if (count($permission_reference_ids) == 0) {
                return false;
            }
            $group_ids = PermissionGroup::whereIn('permission_reference_id', $permission_reference_ids)->pluck('group_id')->toArray();
            if (count($group_ids) == 0) {
                return false;
            }
            $user_grp_ids = Auth::guard('admin_frontend')->user()->user_group ? Auth::guard('admin_frontend')->user()->user_group->pluck('group_id')->toArray() : [];

            return count(array_intersect($group_ids, $user_grp_ids)) > 0 ? true : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    public static function checkDashboardModulePermission($modules = []){
        if ( $modules == [] ) {
            return false;
        }

        if ( !Auth::guard('admin_frontend')->check() ) {
            return false;
        }

        // if super admin return true;
        if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
            return true;
        }

        $permitted = false;
        foreach($modules as $module){
            $permitted = Permission::checkFrontendAdminModulePermission($module);
        }

        return $permitted;
    }

    public static function checkCanAccessSpecificMethodFromUrl($user_id, $permission = [])
    {
        if ( !Auth::guard('admin_frontend')->check() ) {
            return false;
        }
         // if super admin return true;
         if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
            return true;
        }
        $permissionExist = CogentUsers::where('id', $user_id )
                    ->whereHas('groups.permission', function($filter) use ($permission){
                        $filter->whereIn('code', $permission);
                    })
                    ->first();
        return !is_null($permissionExist)  ? true : false ;
    }

    public static function checkCanAccessFromSpecificSideBar($sidebar)
    {
        if ( !Auth::guard('admin_frontend')->check() ) {
            return false;
        }
         // if super admin return true;
         if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
            return true;
        }
        $permissionExist = CogentUsers::where('id', Auth::guard('admin_frontend')->user()->id )
                    ->whereHas('groups.permission', function($filter) use ($sidebar){
                        $filter->where('short_desc', $sidebar);
                    })
                    ->first();
        return !is_null($permissionExist)  ? true : false ;
    }
}
