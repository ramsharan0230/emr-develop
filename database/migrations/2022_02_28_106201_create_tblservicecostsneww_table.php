<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblservicecostsnewwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblservicecostsnew', function (Blueprint $table) {
            $table->bigIncrements('fldid');
            $table->string('flditemname')->nullable();
            $table->string('fldbillitem')->nullable();
            $table->string('flditemtype')->nullable();
            $table->double('flditemcost')->default(0);
            $table->string('fldcode')->nullable();
            $table->string('fldgroup')->nullable();
            $table->string('fldreport')->nullable();
            $table->string('fldstatus')->nullable();
            $table->string('fldtarget')->nullable();
            $table->string('flduserid')->nullable();
            $table->dateTime('fldtime', $precision = 0);
            $table->string('fldcomp')->nullable();
            $table->tinyInteger('xyz')->default(0);
            $table->string('hospital_department_id')->nullable();
            $table->mediumText('category')->nullable();
            $table->tinyInteger('rate')->default(0);
            $table->tinyInteger('discount')->default(0);
            $table->double('hospital_share')->nullable();
            $table->double('other_share')->nullable();
            $table->string('account_ledger')->nullable();
            $table->string('hi_code')->nullable();
            $table->longText('flddescription')->nullable();
            $table->unsignedBigInteger('account_ledger_id');
            $table->unsignedBigInteger('fldbillitem_id');
            $table->unsignedBigInteger('fldbillsection_id');
            $table->unsignedBigInteger('fldbillingset_id');
            $table->integer('created_by')->length(11);
            $table->integer('updated_by')->length(11)->nullable();
            $table->foreign('account_ledger_id')->references('AccountId')->on('account_ledger');
            $table->foreign('fldbillitem_id')->references('fldid')->on('tblbillitem');
            $table->foreign('fldbillsection_id')->references('fldid')->on('tblbillsection');
            $table->foreign('fldbillingset_id')->references('fldid')->on('tblbillingset');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('tblservicecostsnew');
    }
}
