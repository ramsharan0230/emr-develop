<?php

use Illuminate\Database\Seeder;
use App\PermissionModule;
use App\PermissionReference;

class PermissionPackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        PermissionModule::truncate();
        PermissionReference::truncate();

        $permissions = \App\UserFormAccess::select('fldformname')->orderBy('fldformname', 'ASC')->distinct('fldformname')->get();
        foreach ($permissions as $mod) {
            $lastOrderNumber = 0;
            $permissionModule = new \App\PermissionModule();
            $permissionModule->name = $mod->fldformname;
            $permissionModule->status = 1;
            $permissionModule->order_by = (int)$lastOrderNumber + 1;
            $permissionModule->save();

            /*list permission*/
            $name = strtolower(str_replace(' ', '-', 'view-' . $mod->fldformname));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'View ' . $mod->fldformname;
            $listPermissionReference->description = 'View ' . $mod->fldformname;
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

        }

        $newPermissions = [
            'emergency', 'eye', 'dental', 'bed occupancy', 'Symptoms', 'Syndromes', 'Body fluid', 'Ethnic Group', 'Test Printing', 'Nutrition Information', 'Food Mixture',
            'Nutrition Requirements', 'Service Equipments', 'Search Patient Profile', 'Medicine Generic Information',
            'Labeling', 'Medicine Grouping', 'Pharmacy Item activation', 'labortary grouping', 'Examination', 'Examination Grouping',
            'Procedure Grouping', 'Selected Grouping', 'Computer Examination',
            'Radio Addition', 'Radio Sampling', 'Radio Reporting', 'Radio Verification', 'Radio Printing', 'Consultation',
            'Dignostic Laboratory report', 'Dignostic Radiology report', 'Expiry Report', 'Under Stock',
            'users', 'groups', 'fiscalyear setting', 'register setting', 'Billing mode', 'Patient Disc Mode',
            'Autobilling', 'Item Master Laboratory',
            'Item Master Laboratory',
            'Item Master Radiology',
            'Item Master Procedures',
            'Item Master Equipments',
            'Item Master Gen Service',
            'Item Master Other Items',
            'Item Master Inventory Items',
            'Queue Management',
            'ICU',
            'Registration form',
            'Registration List',
            'Cash Billing',
            'Dispensing Form',
            'Return Form',
            'Dispensing List',
            'Deposit Form',
            'Tax Group',
            'Bank List',
            'Plan Report',
            'Followup List',
            'Consultant List',
            'Cashier Packages',
            'User Share',
            'Extra Reception',
            'Staff List',
            'Storage Coding',
            'Stock Adjustment',
            'Stock Return',
            'Purchase Entry',
            'Stock Transfer',
            'Stock Consume',
            'Storage Code',
            'Purchase Order',
            'Supplier Information',
            'Out Of stock',
            'elekha',
            'Extra Receipt',
            'Cashier Package',
            'Demand Form',
            'Plan Report Extra procedure',
            'Plan Report Major procedure',
            'Plan Report Radiology Plan',
            'Follow up list',
            'Consulting lists',
            'Stock',
            'OPD Neuro',
            'hmis',
            'registration setting',
            'ent'
            

        ];

        foreach ($newPermissions as $mod) {
            $lastOrderNumber = 0;
            $permissionModule = new \App\PermissionModule();
            $permissionModule->name = ucfirst($mod);
            $permissionModule->status = 1;
            $permissionModule->order_by = (int)$lastOrderNumber + 1;
            $permissionModule->save();

            /*list permission*/
            $name = strtolower(str_replace(' ', '-', 'view-' . $mod));
            $listPermissionReference = new \App\PermissionReference();
            $listPermissionReference->code = $name;
            $listPermissionReference->short_desc = 'View ' . ucfirst($mod);
            $listPermissionReference->description = 'View ' . ucfirst($mod);
            $listPermissionReference->permission_modules_id = $permissionModule->id;
            $listPermissionReference->save();

        }

        Schema::enableForeignKeyConstraints();
    }
}
