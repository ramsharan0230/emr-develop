<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUnsampledTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_unsampled_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_no');
            $table->string('testid');
            $table->string('patient_id');
            $table->string('encounter_id');
            $table->string('user_id')->nullable();
            $table->string('fldmethod')->nullable();
            $table->integer('fldgroupid');
            $table->string('fldstatus');
            $table->timestamp('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_unsampled_test');
    }
}
