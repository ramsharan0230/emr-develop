<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPatbillshareAddEmgencyShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pat_billing_shares', function (Blueprint $table) {
            $table->string('emergencyShare')->nullable();
            //test
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pat_billing_shares', function (Blueprint $table) {
            $table->dropColumn('emergencyShare');
        });
    }
}
