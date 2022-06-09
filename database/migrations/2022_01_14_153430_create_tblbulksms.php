<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblbulksms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblbulksms', function (Blueprint $table) {
            $table->bigIncrements('fldid');
            $table->string('fldtype')->nullable();
            $table->string('fldsubtype')->nullable();
            $table->string('fldmessage')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblbulksms');
    }
}
