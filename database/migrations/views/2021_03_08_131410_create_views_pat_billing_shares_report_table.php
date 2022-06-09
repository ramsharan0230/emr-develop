<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewsPatBillingSharesReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbluserpay', function (Blueprint $table) {
            $table->unsignedInteger('flduserid')->charset(null)->collation(null)->change();
        });

        DB::statement("CREATE VIEW pat_billing_shares_report
        AS select `pbs`.`user_id`, `pbs`.`pat_billing_id`, `pbs`.`type`, `tblpatbilling`.`fldbillno`, `tblpatbilling`.`flditemtype`, `tblpatbilling`.`flditemname`, `tblpatbilling`.`fldorduserid`, `tblpatbilling`.`fldordtime`, `tblpatbilling`.`fldditemamt`,`tblpatbilling`.`flditemqty`, `sc`.`hospital_share`, `up`.`flditemshare` as user_share_percent, `up`.`flditemtax` as user_tax_percent, `pbs`.`tax_amt`, `pbs`.`share` as doctor_share, `pbs`.`is_returned`, `tblpatbilling`.`fldencounterval`
         from `pat_billing_shares` as pbs
         left join `tblpatbilling` on `tblpatbilling`.`fldid` = `pbs`.`pat_billing_id`
         inner join `tbluserpay` as `up` on `pbs`.`user_id` = `up`.`flduserid`
         inner join `tblservicecost` as `sc` on `sc`.`flditemtype` LIKE `tblpatbilling`.`flditemtype`
         and `sc`.`flditemname` LIKE `tblpatbilling`.`flditemname`
         where `pbs`.`share` > 0
         and `pbs`.`status` = 1
         and exists (select * from `users` where `pbs`.`user_id` = `users`.`id`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW pat_billing_shares_report");
    }
}
