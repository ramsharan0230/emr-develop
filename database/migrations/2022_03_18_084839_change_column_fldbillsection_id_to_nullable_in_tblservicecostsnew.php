<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnFldbillsectionIdToNullableInTblservicecostsnew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblservicecostsnew', function (Blueprint $table) {
            $table->unsignedBigInteger('fldbillsection_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('tblservicecostsnew', function (Blueprint $table) {
            $table->unsignedBigInteger('fldbillsection_id')->nullable(false)->change();
        });
        Schema::enableForeignKeyConstraints();
    }
}
