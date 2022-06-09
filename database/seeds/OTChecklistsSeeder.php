<?php

use Illuminate\Database\Seeder;

class OTChecklistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $lastOrderNumber = 0;
        $permissionModule = new \App\PermissionModule();
        $permissionModule->name = ucfirst("otchecklists");
        $permissionModule->status = 1;
        $permissionModule->order_by = (int)$lastOrderNumber + 1;
        $permissionModule->save();

        $name = strtolower(str_replace(' ', '-', 'signin-otchecklists'));
        $listPermissionReference = new \App\PermissionReference();
        $listPermissionReference->code = $name;
        $listPermissionReference->short_desc = 'Signin OTChecklists';
        $listPermissionReference->description = 'Signin OTChecklists';
        $listPermissionReference->permission_modules_id = $permissionModule->id;
        $listPermissionReference->save();

        $name = strtolower(str_replace(' ', '-', 'timeout-otchecklists'));
        $listPermissionReference = new \App\PermissionReference();
        $listPermissionReference->code = $name;
        $listPermissionReference->short_desc = 'Timeout OTChecklists';
        $listPermissionReference->description = 'Timeout OTChecklists';
        $listPermissionReference->permission_modules_id = $permissionModule->id;
        $listPermissionReference->save();

        $name = strtolower(str_replace(' ', '-', 'signout-otchecklists'));
        $listPermissionReference = new \App\PermissionReference();
        $listPermissionReference->code = $name;
        $listPermissionReference->short_desc = 'Signout OTChecklists';
        $listPermissionReference->description = 'Signout OTChecklists';
        $listPermissionReference->permission_modules_id = $permissionModule->id;
        $listPermissionReference->save();

        Schema::enableForeignKeyConstraints();
    }
}
