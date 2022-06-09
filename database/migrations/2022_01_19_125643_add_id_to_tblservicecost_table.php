<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdToTblservicecostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblservicecost', function (Blueprint $table) {
            $table->bigIncrements('fldid')->first();
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
            $table->dropPrimary('fldid');
            $table->dropColumn('fldid');
        });
    }
}
