<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpbillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbltpbills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fldid');
            $table->string('fldencounterval');
            $table->string('fldbillingmode');
            $table->string('flditemtype');
            $table->string('flditemno')->nullable('true');
            $table->string('flditemname');
            $table->integer('flditemoldqty');
            $table->double('flditemrate', 15, 8);
            $table->double('fldtaxper', 15, 8);
            $table->double('flddiscper', 15, 8);
            $table->double('fldtaxamt', 15, 8);
            $table->double('flddiscamt', 15, 8);
            $table->double('fldditemamt', 15, 8);
            // $table->integer('fldorduserid')->nullable(true);
            // $table->datetime('fldordtime')->nullable(true);
            // $table->string('fldordcomp')->nullable(true);
            $table->integer('flduserid')->nullable(true);
            // $table->datetime('fldtime')->nullable(true);
            $table->string('fldcomp')->nullable(true);
            // $table->integer('fldsave')->nullable(true);
            $table->string('fldbillno')->nullable(true);
            // $table->integer('fldparent')->nullable(true);
            // $table->boolean('fldprint')->default(0);
            $table->string('fldstatus')->nullable(true);
            // $table->boolean('fldalert')->default(0);
            // $table->string('fldtarget')->nullable(true);
            // $table->string('fldpayto')->nullable(true);
            // $table->string('fldrefer')->nullable(true);
            // $table->string('fldreason')->nullable(true);
            // $table->string('fldretbill')->nullable(true);
            $table->integer('fldnewqty')->nullable(true);
            // $table->string('fldsample')->nullable(true);
            // $table->string('fldopip')->nullable(true);
            $table->integer('hospital_department_id')->nullable(true);
            // $table->string('fldpayment_status')->nullable(true);
            $table->string('fldtempbillno')->nullable(true);
            $table->string('discount_mode')->nullable(true);
            // $table->boolean('account_sync')->default(0);
            $table->string('claim_code')->nullable(true);
            $table->string('package_name')->nullable(true);
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
        Schema::dropIfExists('tbltpbills');
    }
}
