<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFonepaylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fonepaylogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable();
            $table->string('fldpatientval')->nullable();
            $table->text('fldresponse')->nullable();
            $table->string('fldform')->nullable();
            $table->string('compId')->nullable();
            $table->string('fldbillno')->nullable();
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
        Schema::dropIfExists('fonepaylogs');
    }
}
