<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblextrabrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblextrabrand', function (Blueprint $table) {
            $table->text('fldmrp')->nullable();
            $table->enum('fldcccharge', ["fldcccharge_amt", "fldcccharge_percent"])->nullable();
            $table->float('fldcccharg_val', 8, 2)->nullable();
            $table->boolean('flddiscountable_item', 0, 1)->default(0);
            $table->boolean('fldinsurance', 0, 1)->default(0);
            $table->boolean('fldrefundable', 0, 1)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblextrabrand', function (Blueprint $table) {
            //
        });
    }
}
