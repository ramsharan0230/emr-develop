<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUpdateByColumnToTbldiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbldiscount', function (Blueprint $table) {
            $table->string('updated_by')->nullable()->comment('column to track user who update a discount category');
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
            $table->dropColumn('updated_by');
        });
    }
}
