<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmssettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblsmssetting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sms_type')->nullable();
            $table->string('sms_name')->nullable();
            $table->string('status')->nullable();
            $table->string('free_follow_up_day')->nullable();
            $table->string('deposit_condition')->nullable();
            $table->string('deposit_mode')->nullable();
            $table->string('deposit_amount')->nullable();
            $table->string('deposit_percentage')->nullable();
            $table->string('events_condition')->nullable();
            $table->string('visit_per_year')->nullable();
            $table->string('test_name')->nullable();
            $table->string('test_status')->nullable();
            $table->string('sms_details')->nullable();
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
        Schema::dropIfExists('tblsmssetting');
    }
}
