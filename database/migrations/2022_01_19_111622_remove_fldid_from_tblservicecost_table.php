<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFldidFromTblservicecostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblservicecost', function (Blueprint $table) {
            $table->dropColumn('fldid');
            $table->dropPrimary('flditemname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblservicecost', function (Blueprint $table) {
            $table->bigInteger('fldid');
            $table->primary('flditemname');
        });
    }
}
