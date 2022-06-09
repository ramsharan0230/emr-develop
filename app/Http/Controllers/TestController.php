<?php

namespace App\Http\Controllers;

use App\Group;
use App\PermissionReference;
use App\SidebarMenu;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function menus(){
		if (Helpers::isSuperAdmin()) {
			return Helpers::allMenus();
		} else {
			return Helpers::permittedMenus();
		}
    }
}
