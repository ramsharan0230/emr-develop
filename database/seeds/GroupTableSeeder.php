<?php

use Illuminate\Database\Seeder;
use App\Group;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        Group::truncate();
        $admins = ['super-admin', 'default', 'hospital-admin'];
        foreach ($admins as $admin){
            $listPermissionReference = new Group();
            $listPermissionReference->name = $admin;
            $listPermissionReference->status = 'active';
            $listPermissionReference->save();
        }


        $group = \App\UserFormAccess::select('fldcategory')->orderBy('fldcategory', 'ASC')->distinct('fldcategory')->get();
        foreach ($group as $mod) {
            /*list permission*/
            $listPermissionReference = new Group();
            $listPermissionReference->name = $mod->fldcategory;
            $listPermissionReference->status = 'active';
            $listPermissionReference->save();

        }

        Schema::enableForeignKeyConstraints();
    }
}
