<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblpcrformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblpcrform', function (Blueprint $table) {
            $table->string('fldtole')->nullable()->change();
            $table->string('fldinfectiontype')->nullable()->change();
            $table->string('fldlabreceivedate')->nullable()->change();
            $table->string('fldsamplecollecteddate')->nullable()->change();
            $table->string('fldlabtestdate')->nullable()->change();
            $table->string('fldlabtesttime')->nullable()->change();
            $table->string('fldlabresult')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbltpbills', function (Blueprint $table) {
            $table->dropColumn('fldtole');
            $table->dropColumn('fldinfectiontype');
            $table->dropColumn('fldlabreceivedate');
            $table->dropColumn('fldsamplecollecteddate');
            $table->dropColumn('fldlabtestdate');
            $table->dropColumn('fldlabtesttime');
            $table->dropColumn('fldlabresult');
        });
    }
}
