<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempConsultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_consults', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval');
            $table->integer('pat_billing_id');
            $table->string('flddept');
            $table->integer('flddoctor');
            $table->string('flduserid');
            $table->string('fldcomp');
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
        Schema::dropIfExists('temp_consults');
    }
}
