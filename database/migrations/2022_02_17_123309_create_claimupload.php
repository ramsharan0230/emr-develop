<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimupload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claimupload', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldbillno');
            $table->string('fldpatbillid');
            $table->string('fldencounterval');
            $table->dateTime('flduploaddate');
            $table->string('flduser');
            $table->string('fldstatus');
            $table->string('fldclaimid');
            $table->string('fldclaimcode');
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
        Schema::dropIfExists('claimupload');
    }
}
