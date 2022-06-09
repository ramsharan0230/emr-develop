<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssentialexamJoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblessentialexamjoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable()->default(null);
            $table->string('joints_cervical_flexion_left')->nullable()->default(null);
            $table->string('joints_cervical_extension_left')->nullable()->default(null);
            $table->string('joints_cervical_rotation_left')->nullable()->default(null);
            $table->string('joints_cervical_lateral_flexion_left')->nullable()->default(null);
            $table->string('joints_cervical_flexion_right')->nullable()->default(null);
            $table->string('joints_cervical_extension_right')->nullable()->default(null);
            $table->string('joints_cervical_rotation_right')->nullable()->default(null);
            $table->string('joints_cervical_lateral_flexion_right')->nullable()->default(null);
            $table->string('joints_shoulder_flexion_left')->nullable()->default(null);
            $table->string('joints_shoulder_extension_left')->nullable()->default(null);
            $table->string('joints_shoulder_abduction_left')->nullable()->default(null);
            $table->string('joints_shoulder_adduction_left')->nullable()->default(null);
            $table->string('joints_shoulder_internal_rotation_left')->nullable()->default(null);
            $table->string('joints_shoulder_external_rotation_left')->nullable()->default(null);
            $table->string('joints_shoulder_flexion_right')->nullable()->default(null);
            $table->string('joints_shoulder_extension_right')->nullable()->default(null);
            $table->string('joints_shoulder_abduction_right')->nullable()->default(null);
            $table->string('joints_shoulder_adduction_right')->nullable()->default(null);
            $table->string('joints_shoulder_internal_rotation_right')->nullable()->default(null);
            $table->string('joints_shoulder_external_rotation_right')->nullable()->default(null);
            $table->string('joints_elbow_flexion_left')->nullable()->default(null);
            $table->string('joints_elbow_extension_left')->nullable()->default(null);
            $table->string('joints_elbow_supination_left')->nullable()->default(null);
            $table->string('joints_elbow_pronation_left')->nullable()->default(null);
            $table->string('joints_elbow_flexion_right')->nullable()->default(null);
            $table->string('joints_elbow_extension_right')->nullable()->default(null);
            $table->string('joints_elbow_supination_right')->nullable()->default(null);
            $table->string('joints_elbow_pronation_right')->nullable()->default(null);
            $table->string('joints_waist_flexion_left')->nullable()->default(null);
            $table->string('joints_waist_extension_left')->nullable()->default(null);
            $table->string('joints_waist_ulnas_deviation_left')->nullable()->default(null);
            $table->string('joints_waist_radial_deviation_left')->nullable()->default(null);
            $table->string('joints_waist_flexion_right')->nullable()->default(null);
            $table->string('joints_waist_extension_right')->nullable()->default(null);
            $table->string('joints_waist_ulnas_deviation_right')->nullable()->default(null);
            $table->string('joints_waist_radial_deviation_right')->nullable()->default(null);
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
        Schema::dropIfExists('tblessentialexamjoints');
    }
}
