<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFldcompAndHospitalIdTblessentialexamjoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblessentialexamjoints', function (Blueprint $table) {
            $table->string('fldcomp')->nullable()->default(null)->after('joints_waist_radial_deviation_right');
            $table->bigInteger('hospital_department_id')->nullable()->default(null)->after('fldcomp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblessentialexamjoints', function (Blueprint $table) {
            $table->dropColumn('fldcomp');
            $table->dropColumn('hospital_department_id');
        });
    }
}
