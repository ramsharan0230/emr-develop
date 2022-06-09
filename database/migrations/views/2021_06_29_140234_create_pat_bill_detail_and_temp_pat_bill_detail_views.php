<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatBillDetailAndTempPatBillDetailViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            CREATE VIEW pat_bill_detail_view
            AS
            SELECT fldencounterval,fldbillno,fldpayitemname,fldprevdeposit,flditemamt,fldtaxamt,fldtaxgroup,flddiscountamt,flddiscountgroup,fldchargedamt,fldreceivedamt,fldcurdeposit,fldbilltype,fldchequeno,fldbankname, flduserid,fldtime,fldcomp,fldsave,fldhostmac,xyz,tblreason,tblofficename,tblexpecteddate,hospital_department_id FROM tblpatbilldetail
            UNION 
            SELECT fldencounterval,fldbillno,fldpayitemname,fldprevdeposit,flditemamt,fldtaxamt,fldtaxgroup,flddiscountamt,flddiscountgroup,fldchargedamt,fldreceivedamt,fldcurdeposit,fldbilltype,fldchequeno,fldbankname, flduserid,fldtime,fldcomp,fldsave,fldhostmac,xyz,tblreason,tblofficename,tblexpecteddate,hospital_department_id FROM tbltemppatbilldetail;
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DROP VIEW IF EXISTS pat_bill_detail_view');
    }
}
