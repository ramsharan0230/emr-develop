<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPriceEditableToTblservicecost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblservicecost', function (Blueprint $table) {
            $table->boolean('price_editable')->nullable()->comment('check if price editable or not');
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
            $table->dropColumn('price_editable');
        });
    }
}
