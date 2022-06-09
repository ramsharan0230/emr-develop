<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlddiscountlimitToTbldiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbldiscount', function (Blueprint $table) {
            $table->string('flddiscountlimit')->after('fldamount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbldiscount', function (Blueprint $table) {
            $table->dropColumn('flddiscountlimit');
        });
    }
}
