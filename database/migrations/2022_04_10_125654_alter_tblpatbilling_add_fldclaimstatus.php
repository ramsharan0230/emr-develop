<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblpatbillingAddFldclaimstatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblpatbilling', function (Blueprint $table) {
            $table->string('fldclaimstatus')->default(false);
            $table->string('fldclaimref')->nullable();
            $table->timestamp('fldclaimtime')->nullable();
            $table->string('fldclaimuser')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblpatbilling', function (Blueprint $table) {
            $table->dropColumn('fldclaimstatus');
            $table->dropColumn('fldclaimref');
            $table->dropColumn('fldclaimtime');
            $table->dropColumn('fldclaimuser');
        });
    }
}
