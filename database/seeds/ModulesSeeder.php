<?php

use Illuminate\Database\Seeder;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $modules = [
            //Setting
            ['module' => 'Settings', 'name' => 'System Settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Payment gateways', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Registration settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Departments setups', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Lab settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Form settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Device settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Prefix setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Form signature', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Interface mapping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Bed setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Advertisement', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Ethnic setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Insurance setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Municipality setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Hospital Branch', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Hospital Department', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'IP Autobilling', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Medicine settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Dispensing settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Settings', 'name' => 'Permission settings', 'status' => '1', 'order_by' => '1'],

            //USER
            ['module' => 'User', 'name' => 'Users', 'status' => '1', 'order_by' => '1'],
            ['module' => 'User', 'name' => 'Group', 'status' => '1', 'order_by' => '1'],
            ['module' => 'User', 'name' => 'Duty Roster', 'status' => '1', 'order_by' => '1'],
            ['module' => 'User', 'name' => 'Clinical Access', 'status' => '1', 'order_by' => '1'],

            //HMIS
            ['module' => 'HMIS', 'name' => 'Redirects to HMIS app', 'status' => '1', 'order_by' => '1'],
            ['module' => 'HMIS', 'name' => 'Generate report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'HMIS', 'name' => 'Mapping setting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'HMIS', 'name' => 'Mapping report', 'status' => '1', 'order_by' => '1'],

            //Account setting
            ['module' => 'Account settings', 'name' => 'Fiscal year setups', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Register settings', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Billing Mode', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Patient Discount Category', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Autobilling', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Tax Group', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Cashier Package', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Account settings', 'name' => 'Bank List', 'status' => '1', 'order_by' => '1'],

            //Item master
            ['module' => 'Item Master', 'name' => 'Laboratory', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'Radiology', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'Procedures', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'Equipment', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'General Service', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'Other items', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Item Master', 'name' => 'Inventory items', 'status' => '1', 'order_by' => '1'],

//Queue Management
            ['module' => 'Queue Management', 'name' => 'Queue Management', 'status' => '1', 'order_by' => '1'],
//OPD
            ['module' => 'OPD', 'name' => 'General OPD form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OPD', 'name' => 'Eye form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OPD', 'name' => 'Dental form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OPD', 'name' => 'ENT form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OPD', 'name' => 'Neuro oPD Form', 'status' => '1', 'order_by' => '1'],

            //Emergency

            ['module' => 'Emergency', 'name' => 'emergency', 'status' => '1', 'order_by' => '1'],
            //Inpatient
            ['module' => 'Inpatient', 'name' => 'Inpatient', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inpatient', 'name' => 'ICU', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inpatient', 'name' => 'Delivery form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inpatient', 'name' => 'Bed occupancy', 'status' => '1', 'order_by' => '1'],

//Discharge
            ['module' => 'Discharge', 'name' => 'Discharge', 'status' => '1', 'order_by' => '1'],
            //OT Management
            ['module' => 'OT Management', 'name' => 'OT', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OT Management', 'name' => 'OT Plan', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OT Management', 'name' => 'Pain Management', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OT Management', 'name' => 'OT Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'OT Management', 'name' => 'OT Plan Report', 'status' => '1', 'order_by' => '1'],
//Haemodialysis

            ['module' => 'Haemodialysis', 'name' => 'Haemodialysis', 'status' => '1', 'order_by' => '1'],

            //Registration

            ['module' => 'Registration', 'name' => 'Registration', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Registration', 'name' => 'Registration List', 'status' => '1', 'order_by' => '1'],
//Billing
            ['module' => 'Billing', 'name' => 'Cashier Form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Return form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Extra Receipt', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Deposit form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Discharge Clearance', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Fiscal year bill', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing', 'name' => 'Bill Status', 'status' => '1', 'order_by' => '1'],

            //Pharmacy
            ['module' => 'Pharmacy', 'name' => 'Dispensing form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy', 'name' => 'Return form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy', 'name' => 'Dispensing list', 'status' => '1', 'order_by' => '1'],
//Laboratory
            ['module' => 'Laboratory', 'name' => 'Test Addition', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'Test Sampling', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'Test Reporting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'Test Verification', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'Test Printing', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'TAT Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Laboratory', 'name' => 'Sample Tracking ', 'status' => '1', 'order_by' => '1'],
//Radiology

            ['module' => 'Radiology', 'name' => 'Radio Reporting', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Radiology', 'name' => 'Radio Verification', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Radiology', 'name' => 'Radio Printing', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Radiology', 'name' => 'Radio Appointment', 'status' => '1', 'order_by' => '1'],

//Dietitian
            ['module' => 'Dietitian', 'name' => 'Dietitian Planning', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Dietitian', 'name' => 'Dietitian Report', 'status' => '1', 'order_by' => '1'],

    //Nutrition Master
            ['module' => 'Nutrition Master', 'name' => 'Nutrition Information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Nutrition Master', 'name' => 'Food Mixture', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Nutrition Master', 'name' => 'Requirements', 'status' => '1', 'order_by' => '1'],
            //Diagnostic Master
            ['module' => 'Diagnostic Master', 'name' => 'Laboratory', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Master', 'name' => 'Laboratory Grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Master', 'name' => 'Radiology', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Master', 'name' => 'Radiology Grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Master', 'name' => 'Radio Template', 'status' => '1', 'order_by' => '1'],

            //Clinical Data Master
            ['module' => 'Clinical Data Master', 'name' => 'Symptoms', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Syndromes', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Examination', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Examination Grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Diet Package', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Procedure Grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Selected Grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Departmental Examination', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Computer Examination', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Triage Parameters', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Body Fluid', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Clinical Data Master', 'name' => 'Ethnic Group', 'status' => '1', 'order_by' => '1'],

            //Patient Reports
            ['module' => 'Patient Reports', 'name' => 'Visit Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Patient Reports', 'name' => 'Inpatient Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Patient Reports', 'name' => 'Transition Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Patient Reports', 'name' => 'Examination Report', 'status' => '1', 'order_by' => '1'],

            //Service Reports
            ['module' => 'Service Reports', 'name' => 'Consultation', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Service Reports', 'name' => 'Procedure', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Service Reports', 'name' => 'Equipment', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Service Reports', 'name' => 'Confinements', 'status' => '1', 'order_by' => '1'],

            //Diagnostic Reports
            ['module' => 'Diagnostic Reports', 'name' => 'Laboratory', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Reports', 'name' => 'Radiology', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Reports', 'name' => 'Lab Category wise report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Reports', 'name' => 'TAT Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Reports', 'name' => 'Sample Tracking', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Diagnostic Reports', 'name' => 'Sensitivity', 'status' => '1', 'order_by' => '1'],

            //Inventory Reports

            ['module' => 'Inventory Reports', 'name' => 'Expiry Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inventory Reports', 'name' => 'Under Stock', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inventory Reports', 'name' => 'Inventory Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inventory Reports', 'name' => 'Inventory db report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Inventory Reports', 'name' => 'Item Ledger Report', 'status' => '1', 'order_by' => '1'],

//Patient Profile
            ['module' => 'Patient Profile', 'name' => 'Patient Profile', 'status' => '1', 'order_by' => '1'],

           //Pharmacy Master
            ['module' => 'Pharmacy Master', 'name' => 'Generic information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Medicine information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Surgical information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Extra items information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Labeling', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Medicine grouping', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Pharmacy item activation', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Pharmacy Master', 'name' => 'Out of order', 'status' => '1', 'order_by' => '1'],

//Store/Inventory

            ['module' => 'Store/Inventory', 'name' => 'Supplier information', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Purchase entry', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Stock Transfer', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Stock Return', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Stock consume', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Storage code', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Purchase order', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Demand form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Dispensing Form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Dispensing list', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Store/Inventory', 'name' => 'Deposit form', 'status' => '1', 'order_by' => '1'],

//Billing Reports
            ['module' => 'Billing Reports', 'name' => 'Collection', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing Reports', 'name' => 'Billing Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing Reports', 'name' => 'User Collection', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing Reports', 'name' => 'User Share', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing Reports', 'name' => 'Deposit report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Billing Reports', 'name' => 'Department', 'status' => '1', 'order_by' => '1'],
            //Templates
            // ['module' => 'Templates', 'name' => '', 'status' => '1', 'order_by' => '1'],

            //Reports
            ['module' => 'Reports', 'name' => 'Demand form', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Purchase Order', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Purchase entry', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Demand form report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Purchase order', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Purchase entry', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Stock return', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Stock transfer', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Stock consume', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Item report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Medical report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Entry waiting report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Behalf', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Patient Register', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Pdf report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Employee List', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Registration', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Collection', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Department', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Billing report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Transfer report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Service status', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Fraction report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Group report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Generic report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Service report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Verify', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Inventory report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Voucher Report', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Materialized view', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Materialized view 2', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Materialized view 3', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'User Collection', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Reports', 'name' => 'Bill design', 'status' => '1', 'order_by' => '1'],

//            ['module' => 'Reports', 'name' => 'Under Stock', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Order VS Receive Report', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Demand vs Order VS Receive
//                                                                  Report', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Remarks Reports', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Expiry Reports', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Near Expiry Reports', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Purchase Return Credit Note', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account SubGroup', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Subhead', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Transaction', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Ledger', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Statement', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Day Book', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Profit Loss', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Account Trail Balance', 'status' => '1', 'order_by' => '1'],
//            ['module' => 'Reports', 'name' => 'Balance sheet', 'status' => '1', 'order_by' => '1'],
            //E- appointment
            ['module' => 'E- appointment', 'name' => '', 'status' => '1', 'order_by' => '1'],
            ['module' => 'User share report', 'name' => '', 'status' => '1', 'order_by' => '1'],
            //Core Accounting
            ['module' => 'Core Accounting', 'name' => 'Account Sub Group', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Account Transaction', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Account Ledger', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Account Statement', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Day Book', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Profit Loss', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Trial Balance', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Core Accounting', 'name' => 'Balance Sheet', 'status' => '1', 'order_by' => '1'],

        //    ['module' => '', 'name' => '', 'status' => '1', 'order_by' => '1'],

            ['module' => 'eappointment list', 'name' => 'eappointment list', 'status' => '1', 'order_by' => '1'],
            ['module' => 'eappointment log', 'name' => 'eappointment log', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Emergency', 'name' => 'Emergency', 'status' => '1', 'order_by' => '1'],
            ['module' => 'Radio Template', 'name' => 'Radio Template', 'status' => '1', 'order_by' => '1'],

        ];

        foreach ($modules as $module)
        {
            $permissionModule =\App\PermissionModule::create($module);

            $reference = ['permission_modules_id' => $permissionModule->id,
                'code'=>str_slug($permissionModule->name ?? null,'-'),
                'short_desc' =>ucfirst($permissionModule->name ?? null).' '.$permissionModule->module,
                'description' => ucfirst($permissionModule->name ?? null).' '.$permissionModule->module ];

            \App\PermissionReference::create($reference);
        }
    }
}
