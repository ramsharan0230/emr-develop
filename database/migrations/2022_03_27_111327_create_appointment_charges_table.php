<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('eapp_service_name')->nullable();
            $table->integer('eapp_service_id')->nullable();
            $table->integer('tblservicecost_id')->nullable();
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
        Schema::dropIfExists('appointment_charges');
    }
}
