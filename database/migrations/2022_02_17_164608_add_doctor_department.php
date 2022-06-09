<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblautogroupdoctor', function (Blueprint $table) {
	        $table->bigIncrements('fldid');
	        $table->text('fldgroup')->nullable();
	        $table->text('flditemname')->nullable();
	        $table->string('flditemtype')->nullable();
	        $table->string('fldexitemtype')->nullable();
	        $table->string('fldbillingmode')->nullable();
	        $table->text('fldregtype')->nullable();
	        $table->bigInteger('hospital_department_id')->nullable();
	        $table->bigInteger('doctor_id')->nullable();
	        $table->time('fldcutoff')->nullable();
	        $table->double('flditemqty')->nullable();
	        $table->boolean('fldenabledept')->default(true);
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
        Schema::dropIfExists('tblautogroupdoctor');
    }
}
