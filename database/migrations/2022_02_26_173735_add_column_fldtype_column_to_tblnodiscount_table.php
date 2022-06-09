<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFldtypeColumnToTblnodiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblnodiscount', function (Blueprint $table) {
            $table->string('fldtype')->comment('fld type indicate which discount item from tbldiscount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblnodiscount', function (Blueprint $table) {
            // if (Schema::hasColumn('fldtype')) {
                $table->dropColumn('fldtype');
            // }

        });
    }
}
