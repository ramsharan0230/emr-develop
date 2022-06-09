<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssentialexamFinger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblessentialexamfinger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fldencounterval')->nullable()->default(null);
            $table->string('finger_mcp_flexion_left')->nullable()->default(null);
            $table->string('finger_mcp_flexion_right')->nullable()->default(null);
            $table->string('finger_mcp_extension_left')->nullable()->default(null);
            $table->string('finger_mcp_extension_right')->nullable()->default(null);
            $table->string('finger_pip_flexion_left')->nullable()->default(null);
            $table->string('finger_pip_flexion_right')->nullable()->default(null);
            $table->string('finger_pip_extension_left')->nullable()->default(null);
            $table->string('finger_pip_extension_right')->nullable()->default(null);
            $table->string('finger_dip_flexion_left')->nullable()->default(null);
            $table->string('finger_dip_flexion_right')->nullable()->default(null);
            $table->string('finger_dip_extension_left')->nullable()->default(null);
            $table->string('finger_dip_extension_right')->nullable()->default(null);
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
        Schema::dropIfExists('tblessentialexamfinger');
    }
}
