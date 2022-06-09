<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssentialexamHip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblessentialexamhip', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable()->default(null);
            $table->string('hip_flexion_left')->nullable()->default(null);
            $table->string('hip_extension_left')->nullable()->default(null);
            $table->string('hip_abduction_left')->nullable()->default(null);
            $table->string('hip_adduction_left')->nullable()->default(null);
            $table->string('hip_medial_rotation_left')->nullable()->default(null);
            $table->string('hip_external_rotation_left')->nullable()->default(null);
            $table->string('hip_flexion_right')->nullable()->default(null);
            $table->string('hip_extension_right')->nullable()->default(null);
            $table->string('hip_abduction_right')->nullable()->default(null);
            $table->string('hip_adduction_right')->nullable()->default(null);
            $table->string('hip_medial_rotation_right')->nullable()->default(null);
            $table->string('hip_external_rotation_right')->nullable()->default(null);
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
        Schema::dropIfExists('tblessentialexamhip');
    }
}
