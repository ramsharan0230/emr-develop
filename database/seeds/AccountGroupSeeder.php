<?php

use Illuminate\Database\Seeder;
use App\AccountGroup;
class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_group')->insert(array(
	     array(
	       'GroupName' => 'Assets',
	       'GroupNameNep' => 'Assets',
	       'ReportId' => '1',
	       'GroupTree' => '1',
	       'ParentId' => '0',
	     ),
	     array(
	       'GroupName' => 'Liabilities',
	       'GroupNameNep' => 'Liabilities',
	       'ReportId' => '2',
	       'GroupTree' => '2',
	       'ParentId' => '0',
	     ),
	     array(
	       'GroupName' => 'Income',
	       'GroupNameNep' => 'Income',
	       'ReportId' => '3',
	       'GroupTree' => '3',
	       'ParentId' => '0',
	     ),
	     array(
	       'GroupName' => 'Expenses',
	       'GroupNameNep' => 'Expenses',
	       'ReportId' => '4',
	       'GroupTree' => '4',
	       'ParentId' => '0',
	     ),
	   ));
    }
}
