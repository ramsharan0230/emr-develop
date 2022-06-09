<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblpatientInsuranceDetailss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpatient_insurance_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldpatientval');
            $table->string('fldencounterval');
            $table->string('fldinsurance_type');
            $table->string('fldpatinsurance_id');
            $table->float('fldallowedamt');
            $table->string('flduser');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('tblpatient_insurance_details');
    }
}
