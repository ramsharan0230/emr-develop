<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssentialexamInterpretation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblessentialexaminterpretaion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('interpretation')->nullable()->default(null);
            $table->string('fldcomp')->nullable()->default(null);
            $table->bigInteger('hospital_department_id')->nullable()->default(null);
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
        Schema::dropIfExists('tblessentialexaminterpretaion');
    }
}
