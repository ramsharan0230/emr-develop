<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPcrForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpcrform', function (Blueprint $table) {

	        $table->string('fldtoken');
	        $table->string('fldencounterval');
	        $table->string('fldname');
	        $table->integer('fldage');
	        $table->integer('fldsex')->comment('1=>Male, 2=>Female, 3=>Other');
	        $table->integer('fldcaste')->comment('0=>Dalit, 1=>Janajati, 2=>Madhesi, 3=>Muslim, 4=>Brahmin/Chettri, 5=>Other, 6=>Don\'t know');
	        $table->integer('fldprovince');
	        $table->integer('flddistrict');
	        $table->integer('fldmunicipality');
	        $table->integer('fldward');
	        $table->string('fldtole');
	        $table->string('fldcontact');
	        $table->string('fldsampletoken');
	        $table->integer('fldtravelled')->comment('0=>No, 1=>Yes');
	        $table->integer('fldinfectiontype')->comment('1=>Symptomatic, 2=>Asymptomatic');
	        $table->integer('fldoccupation')->comment('1=>Front Line Health Worker, 2=>Doctor ,3=>Nurse ,4=>Police/Army , 5=>Business/Industry, 6=>Teacher/Student/Education , 7=>Journalist, 8=>Agriculture , 9=>Transport/Delivery, 10=>Other');
	        $table->integer('fldlabresult')->comment('3=> Positive, 4 => Negative');
	        $table->integer('fldsampletype')->comment('1=>Nasopharyngeal, 2=>Oropharyngeal');
	        $table->integer('fldservicetype')->comment('1=>Paid service, 2=>Free of cost service');
	        $table->integer('fldservicefor')->comment('1=>PCR Swab Collection, 2=>Anitgen Test');
	        $table->dateTime('fldregisteredate');
	        $table->date('fldlabreceivedate');
	        $table->date('fldsamplecollecteddate');
	        $table->date('fldlabtestdate');
	        $table->time('fldlabtesttime');
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblpcrform', function (Blueprint $table) {
        });
    }
}
