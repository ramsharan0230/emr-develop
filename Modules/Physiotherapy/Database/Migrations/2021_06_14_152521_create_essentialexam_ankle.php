<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssentialexamAnkle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblessentialexamankle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable()->default(null);
            $table->string('ankle_dorsi_flexion_left')->nullable()->default(null);
            $table->string('ankle_plantar_left')->nullable()->default(null);
            $table->string('ankle_inversion_left')->nullable()->default(null);
            $table->string('ankle_eversion_left')->nullable()->default(null);
            $table->string('ankle_dorsi_flexion_right')->nullable()->default(null);
            $table->string('ankle_plantar_right')->nullable()->default(null);
            $table->string('ankle_inversion_right')->nullable()->default(null);
            $table->string('ankle_eversion_right')->nullable()->default(null);
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
        Schema::dropIfExists('tblessentialexamankle');
    }
}
