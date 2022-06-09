<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewEntryAccountLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW entry_account_ledger
            AS
            SELECT
                account_service_cost_map.flditemname,
                account_group.GroupName,
                account_group.GroupId,
                account_ledger.AccountNo,
                account_ledger.AccountName
            FROM
                account_ledger
                JOIN account_group ON account_ledger.GroupId = account_group.GroupId
                JOIN account_service_cost_map ON account_service_cost_map.sub_group_id = account_group.GroupId;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS entry_account_ledger");
    }
}
