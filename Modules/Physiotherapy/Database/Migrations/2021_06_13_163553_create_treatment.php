<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbltreatmentphysiotherapy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable()->default(null);
            $table->string('ust_mode')->nullable()->default(null);
            $table->string('ust_frequency')->nullable()->default(null);
            $table->string('ust_intensity')->nullable()->default(null);
            $table->string('ust_time')->nullable()->default(null);
            $table->string('ust_site')->nullable()->default(null);
            $table->string('ust_days')->nullable()->default(null);
            $table->string('tens_mode')->nullable()->default(null);
            $table->string('tens_frequency')->nullable()->default(null);
            $table->string('tens_time')->nullable()->default(null);
            $table->string('tens_site')->nullable()->default(null);
            $table->string('tens_days')->nullable()->default(null);
            $table->string('ust_channel')->nullable()->default(null);
            $table->string('ift_mode')->nullable()->default(null);
            $table->string('ift_site')->nullable()->default(null);
            $table->string('ift_program_selection')->nullable()->default(null);
            $table->string('ift_treatment_mode')->nullable()->default(null);
            $table->string('ift_frequency')->nullable()->default(null);
            $table->string('ift_time')->nullable()->default(null);
            $table->string('ift_days')->nullable()->default(null);
            $table->string('traction_mode')->nullable()->default(null);
            $table->string('traction_hold_time')->nullable()->default(null);
            $table->string('traction_rest_time')->nullable()->default(null);
            $table->string('traction_weight')->nullable()->default(null);
            $table->string('traction_types')->nullable()->default(null);
            $table->string('tracttion_time')->nullable()->default(null);
            $table->string('traction_days')->nullable()->default(null);
            $table->string('ems_mode')->nullable()->default(null);
            $table->string('ems_intensity')->nullable()->default(null);
            $table->string('ems_pulse_duration')->nullable()->default(null);
            $table->string('ems_surge_seconds')->nullable()->default(null);
            $table->string('ems_site')->nullable()->default(null);
            $table->string('ems_days')->nullable()->default(null);
            $table->string('irr_time')->nullable()->default(null);
            $table->string('irr_site')->nullable()->default(null);
            $table->string('irr_days')->nullable()->default(null);
            $table->string('swd_application_mode')->nullable()->default(null);
            $table->string('swd_frequency')->nullable()->default(null);
            $table->string('swd_intensity')->nullable()->default(null);
            $table->string('swd_time')->nullable()->default(null);
            $table->string('swd_days')->nullable()->default(null);
            $table->string('md_frequency')->nullable()->default(null);
            $table->string('md_intensity')->nullable()->default(null);
            $table->string('md_time')->nullable()->default(null);
            $table->string('md_site')->nullable()->default(null);
            $table->string('md_days')->nullable()->default(null);
            $table->string('wax_bath_methods')->nullable()->default(null);
            $table->string('wax_bath_time')->nullable()->default(null);
            $table->string('wax_bath_site')->nullable()->default(null);
            $table->string('wax_bath_days')->nullable()->default(null);
            $table->string('moist_head_pack_time')->nullable()->default(null);
            $table->string('moist_head_pack_site')->nullable()->default(null);
            $table->string('moist_head_pack_days')->nullable()->default(null);
            $table->string('cryotherapy_temperature')->nullable()->default(null);
            $table->string('cryotherapy_time')->nullable()->default(null);
            $table->string('cryotherapy_site')->nullable()->default(null);
            $table->string('cryotherapy_days')->nullable()->default(null);
            $table->string('laser_program_selection')->nullable()->default(null);
            $table->string('laser_time')->nullable()->default(null);
            $table->string('laser_site')->nullable()->default(null);
            $table->string('laser_days')->nullable()->default(null);
            $table->string('ecswt_site')->nullable()->default(null);
            $table->string('ecswt_energy_flux_density')->nullable()->default(null);
            $table->string('ecswt_frequency')->nullable()->default(null);
            $table->string('ecswt_session')->nullable()->default(null);
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
        Schema::dropIfExists('tbltreatmentphysiotherapy');
    }
}
