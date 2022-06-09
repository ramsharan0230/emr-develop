var category = '';
var data = '';
var encounter = $("#encounter_no").val();
var patient = $("#fldpatientval").val();

$('#lines_save').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }
    category = 'lines_and_tube';
    data = {
        peripheral_insertion_date: $('#peripheral_insertion_date').val(),
        artherial_insertion_date: $('#artherial_insertion_date').val(),
        central_insertion_date: $('#central_insertion_date').val(),
        ettube_insertion_date: $('#ettube_insertion_date').val(),
        tracheostomy_insertion_date: $('#tracheostomy_insertion_date').val(),
        foley_insertion_date: $('#foley_insertion_date').val(),
        peripheral_incertion_date: $('#peripheral_incertion_date').val(),
        artherial_incertion_date: $('#artherial_incertion_date').val(),
        central_incertion_date: $('#central_incertion_date').val(),
        ettube_incertion_date: $('#ettube_incertion_date').val(),
        tracheostomy_incertion_date: $('#tracheostomy_incertion_date').val(),
        ng_og: $('#ng_og').val(),
        peripheral_changed_date: $('#peripheral_changed_date').val(),
        artherial_changed_date: $('#artherial_changed_date').val(),
        central_changed_date: $('#central_changed_date').val(),
        ettube_changed_date: $('#ettube_changed_date').val(),
        tracheostomy_changed_date: $('#tracheostomy_changed_date').val(),
        chest_tube: $('#chest_tube').val(),
        peripheral_removed_date: $('#peripheral_removed_date').val(),
        artherial_removed_date: $('#artherial_removed_date').val(),
        central_removed_date: $('#central_removed_date').val(),
        ettube_removed_date: $('#ettube_removed_date').val(),
        tracheostomy_removed_date: $('#tracheostomy_removed_date').val(),
        line_others: $('#line_others').val(),
        peripheral_duration: $('#peripheral_duration').val(),
        artherial_duration: $('#artherial_duration').val(),
        central_duration: $('#central_duration').val(),
        ettube_duration: $('#ettube_duration').val(),
        tracheostomy_duration: $('#tracheostomy_duration').val(),
        lines_duration: $('#lines_duration').val(),
    };

    saveIcuGeneralData(category, data);
    $('form#lines_form')[0].reset();
    // $('#lines_form').rese
});

//insertion for blood products
$('#blood_product_save').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }

    category = 'blood_product';
    data = {
        whole_blood_transfusion: $('#whole_blood_transfusion').val(),
        prp_transfusion: $('#prp_transfusion').val(),
        ffp_transfusion: $('#ffp_transfusion').val(),
        rbc_transfusion: $('#rbc_transfusion').val(),
        platelet_transfusion: $('#platelet_transfusion').val(),
        others_transfusion: $('#others_transfusion').val(),
        whole_blood_collection: $('#whole_blood_collection').val(),
        prp_collection: $('#prp_collection').val(),
        ffp_collection: $('#ffp_collection').val(),
        rbc_collection: $('#bc_collection').val(),
        platelet_collection: $('#platelet_collection').val(),
        others_collection: $('#others_collection').val(),
        creatimine_clearence: $('#creatimine_clearence').val(),
        isolation: $('#isolation').val(),
        isolation_type: $('#isolation_type').val(),
        predicted_body_weight: $('#predicted_body_weight').val(),
        code_status_full_code : $('#full_code').val(),
        code_status_full_dnr : $('#dnr').val(),
        code_status_other : $('#code_status_other').val(),
    };


    saveIcuGeneralData(category, data);
    $('form#blood_form')[0].reset();
});

//inserting vitals
$('#vital_save').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }

    $.ajax({
        url: baseUrl + "/icu-general/store-vital",
        method: "post",
        dataType: 'json',
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
                temp : $('#temp').val(),
                pulse : $('#pulse').val(),
                respiratory : $('#respiratory').val(),
                rhythm : $('#rhythm').val(),
                sbp : $('#sbp').val(),
                dbp : $('#dbp').val(),
                map:$('#map').val(),
                cvp : $('#cvp').val(),
                icp : $('#icp').val(),
                spo : $('#spo').val(),
                air_entry :  $('#air_entry').val(),
                tracheal_suctioning : $('#tracheal_suctioning').val(),
                iv_site_check :  $('#iv_site_check').val(),
                position : $('#position').val(),
                rass :  $('#rass').val(),
                encounter_id: encounter,
                temp_val:$('#temp_val').val(),


        },
        success: function (data) {
            showAlert(data);
            $('form#vital_form')[0].reset();
        },
        error: function (data) {
            showAlert('Something Went Wrong','error');
        },
    });
});

//inserting outputs
$('#output_save').on('click', function () {
    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }

    $.ajax({
        url: baseUrl + "/icu-general/store-outputs",
        method: "post",
        dataType: 'json',
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
            urine : $('#urine').val(),
            urine_total : $('#urine_total').val(),
            gastic_tube : $('#gastic_tube').val(),
            chest_tube : $('#otput_chest_tube').val(),
            rectal_tube : $('#rectal_tube').val(),
            dialysis : $('#dialysis').val(),
            vomits:$('#vomits').val(),
            naso_gastric : $('#naso_gastric').val(),
            maelena : $('#maelena').val(),
            output_others : $('#output_others').val(),
            drain :  $('#drain').val(),
            drain_value : $('#drain_value').val(),
            encounter_id: encounter,
        },
        success: function (data) {
            showAlert(data);
            $('form#output_form')[0].reset();

        },
        error: function (data) {
            showAlert('Something Went Wrong','error');
        },
    });
});

$(".output").keyup(function () {
    var urine = parseInt($("#urine").val()) ? parseInt($("#urine").val()) : 0;
    var evd = parseInt($("#gastic_tube").val()) ? parseInt($("#gastic_tube").val()) : 0;
    var chest = parseInt($("#otput_chest_tube").val()) ? parseInt($("#otput_chest_tube").val()) : 0;
    var rectal_tube = parseInt($("#rectal_tube").val()) ? parseInt($("#rectal_tube").val()) : 0;
    var dialysis = parseInt($("#dialysis").val()) ? parseInt($("#dialysis").val()) : 0;
    var vomits = parseInt($("#vomits").val()) ? parseInt($("#vomits").val()) : 0;
    var naso_gastric = parseInt($("#naso_gastric").val()) ? parseInt($("#naso_gastric").val()) : 0;
    var maelena = parseInt($("#maelena").val()) ? parseInt($("#maelena").val()) : 0;
    var output_others = parseInt($("#output_others").val()) ? parseInt($("#output_others").val()) : 0;
    var total = urine + evd + chest + rectal_tube + dialysis + vomits + naso_gastric + maelena + output_others;
    total = total ? total : 0;
    $("#urine_total").val(total);
});

$(".input-blood-pressure").keyup(function () {
    var syst_bp = Number($("#sbp").val());
    var dyst_bp = Number($("#dbp").val());

    var map_value = (syst_bp - dyst_bp) / 3 + dyst_bp;
    $("#map").val(map_value);
});


$('#save_gcs').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }
    $.ajax({
        url: baseUrl + "/icu-general/store-gcs",
        method: "post",
        dataType: 'json',
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
            e : $('#gcs_e').val(),
            v : $('#gcs_v').val(),
            m : $('#gcs_m').val(),
            encounter_id: encounter,
            left_side_size : $('#left_side_size').val(),
            left_side_reaction:$('#left_side_reaction').val(),
            total_gcs : $('#total_gcs').val(),
        },
        success: function (data) {
            showAlert(data);
            $('form#gcs_form')[0].reset();
        },
        error: function (data) {
            showAlert('Something Went Wrong','error');
        },
    });
})

$('#save_routine_safety').on('click', function () {
    category='Routine Safety';
    data = {
        trachestomy_care : $('#trachestomy_care').val(),
        back_care :$('#back_care').val(),
        bath :$('#bath').val(),
        oral_hygine :$('#oral_hygine').val(),
        skin_care :$('#skin_care').val(),
        hair_wash_care :$('#hair_wash_care').val(),
        activity :$('#activity').val(),
        fall_standard_day_night :$('#fall_standard_day_night').val(),
        fall_standard_radio :$('input[name="fall_standard_radio"]:checked').val(),
        yellow_card :$('#yellow_card').val(),
        yellow_card_radio :$('input[name="yellow_card_radio"]:checked').val(),
        call_light :$('#call_light').val(),
        call_light_radio :$('input[name="call_light_radio"]:checked').val(),
        bed_love_and_locked :$('#bed_love_and_locked').val(),
        bed_love_and_locked_radio :$('input[name="bed_love_and_locked_radio"]:checked').val(),
        bed_alarm :$('#bed_alarm').val(),
        bed_alarm_radio :$('input[name="bed_alarm_radio"]:checked').val(),
        side_rails_up :$('#side_rails_up').val(),
        side_rails_up_radio :$('input[name="side_rails_up_radio"]:checked').val(),
        perimeal :$('#perimeal').val(),
    };
    saveIcuGeneralData(category,data);
});

//inserting ventilators
$('#ventilator_save').on('click', function () {
    $.ajax({
        url: baseUrl + "/icu-general/store-ventilator",
        method: "post",
        dataType: 'json',
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
            encounter_id: encounter,
            ventilation_therapy:$('#ventilation_therapy').val(),
            ventilator_mode_and_adjustment:$('#ventilator_mode_and_adjustment').val(),
            volume_control:$('#volume_control').val(),
            pressure_control_and_support:$('#pressure_control_and_support').val(),
            positive_end_expirtatory:$('#positive_end_expirtatory').val(),
            peak_inspiratory_airway_pressure:$('#peak_inspiratory_airway_pressure').val(),
            mean_airway_pressure:$('#mean_airway_pressure').val(),
            tidal_volume:$('#tidal_volume').val(),
            respiratory_rate_ventilator:$('#respiratory_rate_ventilator').val(),
            expired_minute_ventilation:$('#expired_minute_ventilation').val(),
            inspiratory_rate_or_flowrate:$('#inspiratory_rate_or_flowrate').val(),
            sensitivity:$('#sensitivity').val(),
            inspiratory_rise_time:$('#inspiratory_rise_time').val(),
            flow_and_waveform:$('#flow_and_waveform').val(),
            fractional_inspiratory_oxygen:$('#fractional_inspiratory_oxygen').val(),
            dr_name:$('#dr_name').val(),
            time_of_setting_changed:$('#time_of_setting_changed').val(),
            humidifier_temp:$('#humidifier_temp').val(),
            endotracheal_tube_cuff:$('#endotracheal_tube_cuff').val(),
            administered_by:$('#administered_by').val(),
            flow_rate:$('#flow_rate').val(),
            oxygen_equipment_circuit_change:$('#oxygen_equipment_circuit_change').val(),
            fractional_oxygen:$('#fractional_oxygen').val(),
        },
        success: function (data) {
            showAlert(data);
        },
        error: function (data) {

        },
    });
});

//Head to assestment insertion
$('#head_assestment_save').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }
    category='head_to_assestement',
    data= {
        //skin care
        skin_intact : $('#skin_intact').val(),
        stage : $('#stage').val(),
        braden_score : $('#braden_score').val(),
        drsg_date : $('#drsg_date').val(),
        therapiutic_surface : $('#therapiutic_surface').val(),
        skin_care_intensity : $('#skin_care_intensity').val(),
        compromised_skin_integrity : $('#compromised_skin_integrity').val(),
        coccyx : $('#coccyx').val(),
        secrum : $('#secrum').val(),
        heel : $('#heel').val(),
        elbow : $('#elbow').val(),
        occipital : $('#occipital').val(),
        vac_pressure : $('#vac_pressure').val(),

        //Gastro testical
        abdomen : $('#abdomen').val(),
        bowel_sound : $('#bowel_sound').val(),
        gastric_drainage : $('#gastric_drainage').val(),
        nutrition : $('#nutrition').val(),
        nutrition_diet : $('#nutrition_diet').val(),
        nutrition_rate : $('#nutrition_rate').val(),
        external_access : $('#external_access').val(),
        external_access_date_inserted : $('#external_access_date_inserted').val(),
        status : $('#status').val(),
        geni_type : $('#geni_type').val(),
        voiding : $('#voiding').val(),
        foli_cather_situ : $('#foli_cather_situ').val(),
        voiding_date_inserted : $('#voiding_date_inserted').val(),
        description_of_urine : $('#description_of_urine').val(),
        incontinent : $('#incontinent').val(),
        urinal : $('#urinal').val(),
        voiding_others : $('#voiding_others').val(),
        voiding_dialysis : $('#voiding_dialysis').val(),

        //Cardiovascular
        monitor_lead : $('#monitor_lead').val(),
        central_line_type : $('#central_line_type').val(),
        cardiovascular_location : $('#cardiovascular_location').val(),
        cardio_drsg_date : $('#cardio_drsg_date').val(),
        tubing_date : $('#tubing_date').val(),
        cardio_insertion_date : $('#cardio_insertion_date').val(),
        site : $('#site').val(),
        capillary_refill: $('#capillary_refill').val() ,
        muccus_membrance : $('#muccus_membrance').val(),
       art_line_zerod : $('#art_line_zerod').val(),
        piv_location : $('#piv_location').val(),
        // central_line_type : $('#central_line_type').val(),
        piv_date_insertion : $('#piv_date_insertion').val(),
        art_line_site : $('#art_line_site').val(),
        dressing_date : $('#dressing_date').val(),
        artline_location : $('#artline_location').val(),
        artline_insertion_date : $('#artline_insertion_date').val(),
        artline_other : $('#artline_other').val(),
        skin : $('#skin').val(),

        // Raspratory
        airway : $('#airway').val(),
        breathing : $('#breathing').val(),
        // quality : $('#quality').val(),
        oxygenation_air_entry : $('#oxygenation_air_entry').val(),
        quality : $('#quality').val(),
        ltx_date_inserted : $('#ltx_date_inserted').val(),
        suction : $('#suction').val(),
        drainage : $('#drainage').val(),
        secretion : $('#secretion').val(),
        chest_tube_other : $('#chest_tube_other').val(),
        chest_expansion : $('#chest_expansion').val(),
        chest_expansion_location : $('#chest_expansion_location').val(),

        //neurologcal safety
        gag : $('#gag').val(),
        cough : $('#cough').val(),
        pain : $('#pain').val(),
        intensitity : $('#intensitity').val(),
        location : $('#location').val(),
        morse_score : $('#morse_score').val(),
        sedation : $('#sedation').val(),
        narcotic_analgesia : $('#narcotic_analgesia').val(),
        rass_secure : $('#rass_secure').val(),
        po : $('#po').val(),
        pca : $('#pca').val(),
        pcea : $('#pcea').val(),
        iv : $('#iv').val(),
        dermal : $('#dermal').val(),
        bed_rails : $('#bed_rails').val(),
        hob : $('#hob').val(),
        cell_bell_in_reach : $('#cell_bell_in_reach').val(),
        monitor_alarm_set : $('#monitor_alarm_set').val(),
        glasses : $('#glasses').val(),
        pt_wearinf_id : $('#pt_wearinf_id').val(),
        physical_restrain : $('#physical_restrain').val(),
        allergy_band : $('#allergy_band').val(),
        physiian_order_for_restain : $('#physiian_order_for_restain').val(),


    },
        saveIcuGeneralData( category,data)
    $('form#assessment_form')[0].reset();
})



function saveIcuGeneralData(category, data) {

    $.ajax({
        url: baseUrl + "/icu-general/store",
        method: "post",
        dataType: 'json',
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
            category: category,
            data: data,
            encounter_id: encounter,
            fldpatientval: patient,
        },
        success: function (data) {
            showAlert(data);
        },
        error: function (data) {

        },
    });
}


$("#insert__notes").click(function () {
    // alert('done');
    var fldencounterval = $('#fldencounterval').val();
    var flditem = $('.note__field_item option:selected').val();
    var fldreportquali = $('.note__fldreportquali').val();
    var flddetail = $('#notes_field').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');
    var formData = {
        "fldencounterval": fldencounterval,
        "flditem": flditem,
        "fldreportquali": fldreportquali,
        "flddetail": flddetail,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert('Inserted Successfully');
                getTodayNoteList(fldencounterval);
                $("#notes_field").val(null);
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

// Update Notes
$("#update__notes").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var flditem = $('.note__field_item option:selected').val();
    var fldid = $('.note__field_id').val();
    var flduptime = $('#update_fldtime').val();
    var fldreportquali = $('.note__fldreportquali').val();
    var flddetail = $('#notes_field').val();
    var flduserid = $('#flduserid').val();
    var fldtime = $('.notes_fldtime').val();
    var fldcomp = $('#fldcomp').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "flditem": flditem,
        "fldid": fldid,
        "fldreportquali": fldreportquali,
        "flddetail": flddetail,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldtime": fldtime,
        "flduptime": flduptime,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert('Inserted Successfully');
                // getTodayNoteList(fldencounterval);
                $("#notes_field").val(null);
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

function getTodayNoteList(fldencounterval) {
    $.get('icu/notes/ajax-list-all?fldencounterval=' + fldencounterval + '&date=today', function (data) {
        $('.notes__table_list').empty();
        $.each(data, function (index, get_list_detail) {
            $('.notes__table_list').append('<tr><input type="hidden" name="my_input" value="' + get_list_detail.fldid + '" /><td>' + get_list_detail.flduptime + '</td><td>' + get_list_detail.flditem + '</td></tr>');
        });
    });
}



var ckbox = $("input[name='alpha']");
var chkId = '';




$(document).on('click', '.diagnosissub', function () {
    // alert('click sub bhayo');
    $('input[name="diagnosissub"]').bind('click', function () {
        $('input[name="diagnosissub"]').not(this).prop("checked", false);
    });
    var diagnosub = $("input[name='diagnosissub']");
    if (diagnosub.is(':checked')) {
        var value = $(this).val();
        // alert(value);
        $('.diagnosissubname').val(value);
    } else {
        $(".diagnosissubname").val('');
    }
});


$(document).ready(function () {
    var date = new Date();
    var date_string = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
    // var date_string = date.getFullYear() + '-' + ( '0' + date.getMonth() + 1).slice(-2) + '-' + ( '0' + date.getDate()).slice(-2);
    var nepaliDate = AD2BS(date_string);
    nepaliDate ? $("#from").val(nepaliDate) : null;
    nepaliDate ? $("#to").val(nepaliDate) : null;
});



$(".datepicker").nepaliDatePicker({});
$(".prevent").click(function (e) {
    e.preventDefault();
});
CKEDITOR.replace("notes_message");
CKEDITOR.replace("nurse_initial",
    { height: '400px' }
    );
// CKEDITOR.replace('notes_message', {
//     removePlugins: ["ImageUpload", "elementspath"],
// });


/**
 * This is for calculating total GCS.
 */
$(".gcs_class").change(function () {
    var e = $("#gcs_e").val() == undefined ? 0 : Number($("#gcs_e").val());
    var m = $("#gcs_m").val() == undefined ? 0 : Number($("#gcs_m").val());
    var v = $("#gcs_v").val() === "T" ? "T" : Number($("#gcs_v").val());
    // console.log(v);
    var total = e + m;
    // e + v + m > 0 ?
    if ($("#gcs_v").val() === "T") {
        total = total + 1 + "T";
        // $('#verbal_t').val(1);
    } else {
        $("#verbal_t").val("T");
        total = e + m + v;
    }

    if ($("#gcs_v").val() === "none") {
        $("#total_gcs").empty("");
        total = 0;
        total = e + m + 1;
        total = isNaN(total) ? "" : total;
    }
    // console.log(total)
    $("#total_gcs").val(total);
});




$(document).on("click", ".btnDiagnosisName", function () {
    $("#sub_diagnosis_table").find("tr").removeClass("diagnosisSelected");
    $(this).parent().parent().addClass("diagnosisSelected");
});

$(".input-blood-pressure").keyup(function () {
    var syst_bp = Number($("#sbp").val());
    var dyst_bp = Number($("#dbp").val());

    var map_value = (syst_bp - dyst_bp) / 3 + dyst_bp;
    $("#map").val(map_value);
});

/**
 * Script for calculating BMI using Weight and Height
 * formula is  weight (kg) / [height (m)]2
 */

$(".for_bmi").keyup(function () {
    var height = parseInt($("#height").val()); //converting height to meter for calculations
    var weight = parseInt($("#weight").val());
    var sm = Math.pow(parseFloat(height / 100), 2);
    var bmi = parseFloat(weight / sm).toFixed(2);
    //bmi.toFixed(1);
    if (bmi == NaN || bmi == "" || bmi == null) {
        $("#bmi").val(0);
    } else {
        $("#bmi").val(bmi);
    }
});

$("#exampleFormControlSelect1").change(function () {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    CKEDITOR.instances[instance].setData("");
    $("#note_status_message").empty();
});



/**
 * function for displaying default value on load for GCS
 */
$(document).ready(function () {
    var test = [];
    $.each($(".gcs_class option:selected"), function () {
        test.push($(this).val());
    });
    var total = 0;
    for (var i = 0; i < test.length; i++) {
        total += test[i] << 0;
    }
    total = total ? total : null;
    $("#total_gcs").val(total);
});


/**
 * Function for displaying details of medicine
 */
$(".fluid_button").click(function (e) {
    $("#fluid_title").text($(this).data("medicine"));
    var fluid_html = "";
    fluid_html += "<tr>";
    fluid_html += "<td>" + $(this).data("start_time") + "<t/d>";
    fluid_html += "<td>" + $(this).data("medicine") + "</td>";
    fluid_html += "<td>" + $(this).data("dose") + "</td>";
    fluid_html += "<td>" + $(this).data("frequency") + "</td>";
    fluid_html += "<td>" + $(this).data("days") + "</td>";
    fluid_html += "<td>" + $(this).data("status") + "</td>";
    // fluid_html+= '<td><button type="button" id="fluid_play_btn" title="Start Fluid"><i class="fas fa-play" ></i></button> &nbsp;';
    fluid_html += "<input class='data-id' type='hidden' data-val='" + $(this).data("id") + "'></tr>";
    $("#fluid_table_body").empty().html(fluid_html);
});


/**
 * For displaying total for  output
 */
$(".output").keyup(function () {
    var urine = parseInt($("#urine").val()) ? parseInt($("#urine").val()) : 0;
    var evd = parseInt($("#evd").val()) ? parseInt($("#evd").val()) : 0;
    var drain = parseInt($("#drain").val()) ? parseInt($("#drain").val()) : 0;
    var extra = parseInt($("#extra").val()) ? parseInt($("#extra").val()) : 0;
    var total = urine + evd + drain + extra;
    total = total ? total : 0;
    $("#output_total").val(total);
});

$(document).ready(function () {
    $('[data-tooltip="tooltip"]').tooltip();

    $("#report_date").datepicker({
        //changeYear: true,
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        autoclose: true,
    });
});
// $(document).ready(function() {
//     $('body').tooltip({
//         selector: "[data-tooltip=tooltip]",
//         container: "body"
//     });
// });

$("#mode").change(function () {
    if ($(this).val() === "other") {
        $("#modeOther").modal("toggle");
    }
});

$("#saveModeOther").click(function () {
    var other = $("#modeOtherInpt").val();
    if (typeof other === undefined || other === null || other === "") {
        showAlert("Please enter other reason,Cannot be empty", "error");
        return false;
    }
    $("#modeRemarks").val(other);
    $("#modeOther").modal("toggle");
});


//Bollus data

$('.bollus-add-btn').click(function() {
    var trTemplateData = $('#js-multi-bollus-tr-template').html();
    var activeForm = $('div.tab-pane.fade.active.show');
    //
    $(activeForm).find('.js-multi-bollus-tbody').append(trTemplateData);
    // $.each($(activeForm).find('.js-multi-consultation-tbody tr select'), function(i, elem) {
    //     if (!$(elem).hasClass('select2-hidden-accessible'))
    //         $(elem).select2();
    // });
});
$('.multi-intravenous-btn').click(function() {
    var trTemplateData = $('#js-multi-intravenous-tr-template').html();
    var activeForm = $('div.tab-pane.fade.active.show');
    //
    $(activeForm).find('.multi-intravenous').append(trTemplateData);
    // $.each($(activeForm).find('.js-multi-consultation-tbody tr select'), function(i, elem) {
    //     if (!$(elem).hasClass('select2-hidden-accessible'))
    //         $(elem).select2();
    // });
});

$(document).on('click', '.js-multi-bollus-remove-btn', function() {
    var trCount = $(this).closest('.js-multi-bollus-tbody').find('tr').length;
    if (trCount > 1) {
        $(this).closest('tr').remove();
    }
});

$(document).on('click', '.js-multi-intravenous-remove-btn', function() {
    var trCount = $(this).closest('.multi-intravenous').find('tr').length;
    if (trCount > 1) {
        $(this).closest('tr').remove();
    }
});

$('#bollus_save').on('click', function () {

    if(encounter ==='' || !encounter){
        showAlert('Please enter encounter','error');
        return false;
    }
    var test =[];
    $.each($('.bollus-tr'), function (i, ele) {
        if ($(ele).css('display') !== 'none') {
            var medicine = $(ele).find('.medicine').val();
            var answer = $(ele).find('.answer').val();
            var intravenous = $(ele).find('.answer').val();
            var intravenous_val = $(ele).find('.answer').val();

            test.push({
                medicine:medicine,
                answer: answer,
                intravenous: intravenous,
                intravenous_val: intravenous_val,
            });
        }
    });
// console.log(test);
// return false;






    category = 'bollus';

    // bollus_array = [];
    // var bollus = $("select[name='bollus_medicine[]']").map(function () {return $(this).val();}).get();
    // var bollus_med_val =  $("input[name='bollus_val[]']").map(function(){return $(this).val();}).get();
    // var intravenous =$("input[name='intravenous[]']").map(function () {return $(this).val();}).get();
    // var  intravenous_val= $("input[name='intravenous_val[]']").map(function () {return $(this).val();}).get();
    // $.each(bollus, function (i, med) {
    //
    //     var medicine = med;
    //
    //     bollus_array.push({
    //
    //         bollus_medicine: medicine,
    //
    //     });
    // });
    // $.each(bollus_med_val, function (i, val) {
    //
    //     var bollus_med_val = val;
    //
    //     bollus_array.push({
    //
    //         bollus_val: bollus_med_val,
    //
    //     });
    // });
    // $.each(intravenous, function (i, val) {
    //
    //     var intravenous = val;
    //
    //     bollus_array.push({
    //
    //         intravenous: intravenous,
    //
    //     });
    // });
    // $.each(intravenous_val, function (i, val) {
    //     var intravenous_val = val;
    //     bollus_array.push({
    //
    //         intravenous_val: intravenous_val,
    //
    //     });
    // });
    // console.log(bollus_array);
        $.ajax({
            url: baseUrl + "/icu-general/store-bollus",
            method: "post",
            dataType: "json",
            // crossDomain: true,
            // dataType: 'jsonp',
            headers: {
                'Access-Control-Allow-Origin': '*',
            },
            data: {
                category: category,
                bollus:test,
                encounter_id: encounter,
                fldpatientval: patient,
            },
            success: function (data) {
                showAlert(data);
                $('form#bollus_form')[0].reset();
            },
            error: function (data) {
                showAlert('Something Went Wrong','error');
            },
        });

    // saveIcuGeneralData(category,data)

    });

$('#save_intake').on('click', function () {
    $.ajax({
        url: baseUrl + "/icu-general/store-intake",
        method: "post",
        dataType: "json",
        // crossDomain: true,
        // dataType: 'jsonp',
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
        data: {
            encounter_id: encounter,
            iv:$('#iv').val(),
            tube_feeding:$('#tube_feeding').val(),
            oral:$('#oral').val(),
            type_of_diet:$('#type_of_diet').val(),
            whole_blood_cell:$('#whole_blood_cell').val(),
            fresh_frozen_plasma:$('#fresh_frozen_plasma').val(),
            platelets_reach_plasma:$('#platelets_reach_plasma').val(),
            albumin:$('#albumin').val(),
            tpn:$('#tpn').val(),
            intake_total:$('#intake_total').val(),
        },
        success: function (data) {
            showAlert(data);
        },
        error: function (data) {

        },
    });
});
$("#isolation_type").change(function () {
    if($('#isolation_type').is(':checked')){
        $("#isolation_val").show();
    }else {
        $("#isolation_val").hide();
    }
});
// $('#isolation_type').click(function () {
//     if($(this).val() =='yes'){
//         $('#isolation_val').show();
//     }
//     else{
//         $('#isolation_val').hide();
//     }
// })

