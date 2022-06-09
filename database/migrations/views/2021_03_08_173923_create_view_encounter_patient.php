<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewEncounterPatient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            CREATE VIEW encounter_patient
            AS
            SELECT
                tblencounter.fldencounterval,
                tblencounter.fldpatientval,
                tblencounter.fldadmitlocat,
                tblencounter.fldcurrlocat,
                tblencounter.flddoa,
                tblencounter.flddod,
                tblencounter.fldheight,
                tblencounter.fldcashdeposit,
                tblencounter.flddisctype,
                tblencounter.fldcashcredit,
                tblencounter.flduserid as encounter_flduserid,
                tblencounter.fldadmission,
                tblencounter.fldfollowup,
                tblencounter.fldfollowdate,
                tblencounter.fldreferto,
                tblencounter.fldregdate,
                tblencounter.fldcharity,
                tblencounter.fldbillingmode,
                tblencounter.fldcomp,
                tblencounter.fldvisit,
                tblencounter.xyz as encounter_xyz,
                tblencounter.fldunit as encounter_fldunit,
                tblencounter.fldrank as encounter_fldrank,
                tblencounter.fldoldptcode,
                tblencounter.fldvisitcount,
                tblencounter.fldpttype,
                tblencounter.fldclass,
                tblencounter.fldclaimcode,
                tblencounter.fldparentcode,
                tblencounter.fldptcode as encounter_fldptcode,
                tblencounter.fldreferfrom,
                tblencounter.fldhospname,
                tblencounter.fldinside,
                tblencounter.fldphminside,
                tblencounter.fldopip,
                tblencounter.fldroom,
                tblencounter.hospital_department_id as encounter_hospital_department_id,
                tblpatientinfo.fldptcode as patient_fldptcode,
                tblpatientinfo.fldptnamefir,
                tblpatientinfo.fldmidname,
                tblpatientinfo.fldptnamelast,
                tblpatientinfo.fldptsex,
                tblpatientinfo.fldunit as patient_fldunit,
                tblpatientinfo.fldrank as patient_fldrank,
                tblpatientinfo.fldptbirday,
                tblpatientinfo.fldptadddist,
                tblpatientinfo.fldptaddvill,
                tblpatientinfo.fldptcontact,
                tblpatientinfo.fldptguardian,
                tblpatientinfo.fldrelation,
                tblpatientinfo.fldptadmindate,
                tblpatientinfo.fldemail,
                tblpatientinfo.flddiscount,
                tblpatientinfo.fldadmitfile,
                tblpatientinfo.fldcomment,
                tblpatientinfo.fldencrypt,
                tblpatientinfo.fldpassword,
                tblpatientinfo.fldcategory,
                tblpatientinfo.flduserid as patient_flduserid,
                tblpatientinfo.fldtime,
                tblpatientinfo.fldupuser,
                tblpatientinfo.flduptime,
                tblpatientinfo.fldopdno,
                tblpatientinfo.xyz as patient_xyz,
                tblpatientinfo.fldbookingid,
                tblpatientinfo.fldnhsiid,
                tblpatientinfo.fldtitle,
                tblpatientinfo.fldethnicgroup,
                tblpatientinfo.fldcountry,
                tblpatientinfo.fldprovince,
                tblpatientinfo.fldmunicipality,
                tblpatientinfo.fldwardno,
                tblpatientinfo.fldnationalid,
                tblpatientinfo.fldpannumber,
                tblpatientinfo.fldbloodgroup,
                tblpatientinfo.hospital_department_id as patient_hospital_department_id
            FROM
                tblencounter
                JOIN tblpatientinfo ON tblencounter.fldpatientval = tblpatientinfo.fldpatientval;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS encounter_patient");
    }
}
