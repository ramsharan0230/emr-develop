<?php

use Illuminate\Database\Seeder;

class HospitalAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $newPermissions = [
            "user", "groups"
        ];

        foreach ($newPermissions as $mod) {
            $lastOrderNumber = 0;
            $permissionModule = new \App\PermissionModule();
            $permissionModule->name = ucfirst($mod);
            $permissionModule->status = 1;
            $permissionModule->order_by = (int)$lastOrderNumber + 1;
            $permissionModule->save();

            /*list permission*/
            $name = strtolower(str_replace(' ', '-', 'list-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'List ' . ucfirst($mod);
            $listPermissionReference->description = 'List ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

            $name = strtolower(str_replace(' ', '-', 'view-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'View ' . ucfirst($mod);
            $listPermissionReference->description = 'View ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

            $name = strtolower(str_replace(' ', '-', 'add-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'Add ' . ucfirst($mod);
            $listPermissionReference->description = 'Add ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

            $name = strtolower(str_replace(' ', '-', 'update-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'Update ' . ucfirst($mod);
            $listPermissionReference->description = 'Update ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();


            $name = strtolower(str_replace(' ', '-', 'delete-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'Delete ' . ucfirst($mod);
            $listPermissionReference->description = 'Delete ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

        }

        Schema::enableForeignKeyConstraints();
    }
}
