<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class SidebarMenu extends Model
{
    use LogsActivity;
    protected $table = 'sidebarmenu';

    protected static $logUnguarded = true;

    public function permissionRefrences()
    {
        return $this->hasMany(PermissionReference::class, 'short_desc', 'submenu');
    }

    public function sub_menus(){
        return $this->hasMany(SidebarMenu::class,'mainmenu','mainmenu');
    }
}
