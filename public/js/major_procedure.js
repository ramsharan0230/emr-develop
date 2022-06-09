$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $("meta[name='_token']").attr("content")
        }
    });
});


// Patient Profile
// ****************************************************

// Encounter ID
$("#majorprocedure_id_submit").on("keyup", function (e) {
    if (e.keyCode === 13) {
        var patient_id = $("#majorprocedure_id_submit").val();
        var url = $("#majorprocedure_req").attr("url");
        if (patient_id === "" || patient_id === 0) {
            showAlert("Enter patient id");
        } else {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {patient_id: patient_id},
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        $("#ajax_response_encounter_list").empty();
                        $("#ajax_response_encounter_list").html(data.success.options);
                        $("#encounter_list").modal("show");
                    } else {
                        showAlert("Something went wrong!!", 'error');
                    }
                }
            });
        }
    }
});



$("#majorprocedure_req").click(function () {
    var patient_id = $("#majorprocedure_id_submit").val();
    var url = $(this).attr("url");
    if (patient_id === "" || patient_id === 0) {
        showAlert("Enter patient id");
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {patient_id: patient_id},
            success: function (data) {
                // console.log(data);
                if ($.isEmptyObject(data.error)) {
                    $("#ajax_response_encounter_list").empty();
                    $("#ajax_response_encounter_list").html(data.success.options);
                    $("#encounter_list").modal("show");
                } else {
                    showAlert("Something went wrong!!", 'error');
                }
            }
        });
    }
});

// Saving Patient Height
$("#majorprocedure_save_height").click(function () {
    var encounter_id = $("#encounter_id").val();
    var fldcomp = $("#fldcomp").val();
    var heightvalue = $("#heightvalue").val();
    var heightrate = $("#heightrate option:selected").text();
    var height = heightvalue;
    if (heightrate === "m") {
        height = heightvalue * 100;
    }
    var url = $(this).attr("url");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {height: height, encounter_id: encounter_id, fldcomp: fldcomp},
        success: function (data) {
            // console.log(data);
            if ($.isEmptyObject(data.error)) {
                $("#bmi").val(data.success.bmi);
                showAlert("Information saved!!");
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});

// Saving Patient Weight
$("#majorprocedure_save_weight").click(function () {
    var encounter_id = $("#encounter_id").val();
    var fldcomp = $("#fldcomp").val();
    var weight = $("#weight_inpatient").val();

    var url = $(this).attr("url");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {weight: weight, encounter_id: encounter_id, fldcomp: fldcomp},
        success: function (data) {
            // console.log(data);
            if ($.isEmptyObject(data.error)) {
                $("#bmi").val(data.success.bmi);
                showAlert("Information saved!!");
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});

// Saving Consult
$(document).on("click", "#submitconsultant_list", function () {
    var user_consult = $("input[name='consultant']:checked").val();
    var encounter_id = $("#encounter_id").val();
    var url = $(this).attr("url");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {user_consult: user_consult, encounter_id: encounter_id},
        success: function (data) {
            console.log(data);
            if ($.isEmptyObject(data.error)) {
                $("#consulting_aar").val(user_consult);
                showAlert("Information saved!!");
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});

// DatePicker
var dateToday = new Date();
$("#datepicker").datepicker({
    maxDate: dateToday,
    changeMonth: true,
    changeYear: true,
    // maxDate: 0,
    dateFormat: "yy-mm-dd",
    yearRange: "-100:+0",
    onSelect: function (selected, evnt) {
        dob = new Date(selected);
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        var encounter_id = $("#encounter_id").val();
        var url = $("#majorprocedure_getAgeUrl").attr("url");

        $("#datepicker").val(age);
        $("#dateselected").val(selected);
        $("#datepicker").datepicker("hide");
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {date: selected, encounter_id: encounter_id},
            success: function (data) {
                console.log(data);
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");
                } else {
                    showAlert("Something went wrong!!", 'error');
                }
            }
        });
    }
});

// Major Procedure // pre operative
//insert Discussion freetext
$('#pre-anaesthesia-getDataOnClick').on('click', function(){
    var encounter_id = $('.gotopatient').attr('encounter_id');

    if(encounter_id != '0'){

    }else{
        alert('Choose Encounter');
        return false;
    }
})

// pre-operative on click
$("#pre-operative-getDataOnClick").click(function () {
    showAllFldReportquali();
    showAllFldReport();
    getPatFinding();
    showClinicalIndicationFldReportquali();
    getExaminationPreOperative();
    var fldinput = "Pre-Operative";
    getMajorExaminationTable(fldinput);
});

$("#operation-getDataOnClick").click(function () {
    showOperationClinicalIndicationFldReportquali();
    showOperationClinicalNoteFldReport();
    getPersonnelData();
    getOtherItemsData();
    getExaminationOperation();
    // getExaminationOperation();
    var fldinput = 'Operation';
    getMajorExaminationTable(fldinput);
});

$("#anaesthesia-getDataOnClick").click(function () {
    showAnaesthesiaClinicalIndicationFldReportquali();
    showAnaesthesiaOperationClinicalNoteFldReport();
    getAnaesthesiaVariables();
    getAnaesthesiaVariablesSelect();
    getExaminationAnaesthesia();
    // getExaminationAnaesthesia();
    var fldinput = "Anaesthesia";
    getMajorExaminationTable(fldinput);
});

$("#post-operative-getDataOnClick").click(function () {
    showPostOpClinicalIndicationFldReportquali();
    showOperationClinicalNoteFldReport();
    getExaminationPostOperative();
    // getExaminationPostOperative();
    var fldinput = "Post-Operative";
    getMajorExaminationTable(fldinput);
});

$("#save-pre-operative-discussion-freetext").click(function () {
    var fldencounterval = $("#encounter_id").val();
    // alert(fldencounterval)
    var flditemid = $('#newProcedure_fldid').val();
    // var flduserid = $("#flduserid_mp").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#pre-operative-discussion-freetext").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreportquali: fldreportquali,
        flditemid: flditemid
    };
    // console.log(formData);
    if (fldencounterval === ""  || fldreportquali === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    showAllFldReportquali();
                } else {
                    showAlert(data.error.message, 'error');
                }
            }
        });
    }
});


$("#save-pre-operative-discussion-textarea").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $("#newProcedure_fldid").val();
    // var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var fldreport = CKEDITOR.instances.pre_operative_discussion_textarea.getData();//$("#pre-operative-discussion-textarea").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreport: fldreport,
        flditemid: flditemid
    };
    // console.log(formData);
    if (fldencounterval === "" /*|| fldcomp === ""*/ || fldreport === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    showAllFldReport();
                } else {
                    showAlert(data.error.message, 'error');
                }
            }
        });
    }
});


// clinical note
$("#save-clinical-indication").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $("#newProcedure_fldid").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication").val();
    var fldchapter = "Pre-Operative Indication";
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreportquali: fldreportquali,
        flditemid: flditemid,
        fldchapter: fldchapter
    };
    console.log(formData);
    if (fldencounterval === "" || fldreportquali === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showClinicalIndicationFldReportquali();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// To list Notes
$(document).on("click", ".rowOnclick-O tr", function () {
    var fldid = $(this).find("input").val();
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Pre-Operative Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldid=' + fldid + '&fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('#clinical-indication').empty();
        $('#current_id_preO').empty();
        $('#chapter_preO').empty();

        CKEDITOR.instances.clinical_note_textarea.setData(data.fldreport);
        $('#clinical-indication').val(data.fldreportquali);
        $('#current_id_preO').val(data.fldid);
        $('#chapter_preO').val(data.fldchapter);
    });
});

$(document).on("click", ".rowOnclick-postO tr", function () {
    var fldid = $(this).find("input").val();
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Post-Operative Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldid=' + fldid + '&fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('#clinical-indication-postOp').empty();
        $('#current_id_postO').empty();
        $('#chapter_postO').empty();

        CKEDITOR.instances.clinical_note_postOp_textarea.setData(data.fldreport);
        $('#clinical-indication-postOp').val(data.fldreportquali);
        $('#current_id_postO').val(data.fldid);
        $('#chapter_postO').val(data.fldchapter);
    });
});

$(document).on("click", ".rowOnclick-Ope tr", function () {
    var fldid = $(this).find("input").val();
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Operation Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldid=' + fldid + '&fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('#clinical-indication-operation').empty();
        $('#current_id_o').empty();
        $('#chapter_o').empty();

        CKEDITOR.instances.clinical_note_operation_textarea.setData(data.fldreport);
        $('#clinical-indication-operation').val(data.fldreportquali);
        $('#current_id_o').val(data.fldid);
        $('#chapter_o').val(data.fldchapter);
    });
});

$(document).on("click", ".rowOnclick-ana tr", function () {
    var fldid = $(this).find("input").val();
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Anaesthesia Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldid=' + fldid + '&fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('option:selected', 'select[name="clinical-indication-ana"]').removeAttr('selected');
        $('#current_id_ana').empty();
        $('#chapter_ana').empty();

        CKEDITOR.instances.clinical_note_ana_textarea.setData(data.fldreport);
        $('select[name="clinical-indication-ana"]').find('option[value="' + data.fldcategory + '"]').attr("selected", true);
        $('#current_id_ana').val(data.fldid);
        $('#chapter_ana').val(data.fldchapter);
        $('#report_quali_ana').val(data.fldreportquali);
    });
});


// clinical note
$(document).on("click", "#save-clinical-note", function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldid = $("#current_id_preO").val();
    var fldchapter = $("#chapter_preO").val();
    // var fldreport  = CKEDITOR.clinical_note_textarea.getData();
    var fldreport = CKEDITOR.instances["clinical_note_textarea"].getData();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication").val();
    var url = $(this).attr('url');

    var formData = {
        fldencounterval: fldencounterval,
        flditemid: flditemid,
        fldreport: fldreport,
        fldid: fldid,
        fldchapter: fldchapter,
        fldcomp: fldcomp,
        // flduserid: flduserid,
        fldreportquali: fldreportquali
    };
    console.log(formData);
    if (fldencounterval === "" || fldid === "" || fldreport === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});

// New Procedure
// update
$("#update-newPorcedure").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var fldid = $('#newProcedure_fldid').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var fldnewdate = $('#newPorcedure_fldnewdate').val();
    var fldreportquali = $("#fldreportquali-newprocedure option:selected").val();
    var referer = $("#newProcedure_refer option:selected").val();
    var payable = $("#newProcedure_payable option:selected").val();

    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "fldid": fldid,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldreportquali": fldreportquali,
        "fldnewdate": fldnewdate,
        "referer": referer,
        "payable": payable,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Inserted Successfully");
                getRelatedDataNewProcedure();
            } else {
                showAlert("error", 'error');
            }
        }
    });
});


$(document).on("click", ".getRelatedDataNewProcedure tr", function () {
    clearTables();
    var fldid = $(this).attr('rel');
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/new-procedure/getSelectedData?fldid=' + fldid + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data

        $('#newProcedure_fldid').val('');
        $('#newProcedure_fldnewdate').val('');
        $('#newProcedure_proced').val('');
        $('#fldreportquali-newprocedure').empty();
        // console.log(data);
        $.each(data, function (index, get_list_detail) {

            $('#newProcedure_fldid').val(get_list_detail.fldid);
            // $('#newPorcedure_fldnewdate').val(get_list_detail.fldnewdate);
            $('#newPorcedure_fldnewdate').datepicker("setDate", get_list_detail.fldnewdate);
            $('#newProcedure_proced').val(get_list_detail.flditem);

            if (get_list_detail.fldreportquali == null)
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold">On Hold</option><option value="Done">Done</option>');

            if (get_list_detail.fldreportquali == 'Planned')
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" selected=selected class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold">On Hold</option><option value="Done">Done</option>');

            if (get_list_detail.fldreportquali == 'Referred')
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred"  selected="selected" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold">On Hold</option><option value="Done">Done</option>');

            if (get_list_detail.fldreportquali == 'Canceled')
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" selected="selected" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold">On Hold</option><option value="Done">Done</option>');

            if (get_list_detail.fldreportquali == 'On Hold')
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold" selected="selected">On Hold</option><option value="Done">Done</option>');

            if (get_list_detail.fldreportquali == 'Done')
                $('#fldreportquali-newprocedure').append('<option>---select---</option><option value="Planned" class="' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + ' on-' + get_list_detail.fldreportquali + '">Planned</option><option value="Referred" class="' + get_list_detail.fldreportquali + '">Referred</option><option value="Cancelled" class="' + get_list_detail.fldreportquali + '">Cancelled</option><option value="On Hold">On Hold</option><option value="Done" selected="selected">Done</option>');
        });
    });
    getPatSubGeneralComponentsWithID(fldid);
    getPatGeneralDetailsWithID(fldid);
});

$("#insert_majorProcedure_freetext").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var fldreportquali = $('#newPorcedure_fldreportquali').val();
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "flditemid": flditemid,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldreportquali": fldreportquali,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Inserted Successfully");
                getPatSubGeneralComponents();
            } else {
                showAlert("error", 'error');
            }
        }
    });
});


$("#insert_variables").click(function () {

    var flditem = $('#procedure_flditem').val();
    var url = $(this).attr('url');

    var formData = {
        "flditem": flditem,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert(data.success.message);
                getProcedureVariables();
                $('#newPorcedure_fldreportqualiSelect').append(data.success.appendVariable);
            } else {
                showAlert(data.error.message, 'error');
            }
        }
    });
});


$("#delete_variables").click(function () {
    var url = $(this).attr('url');
    var id = Array.from(document.querySelectorAll("input[type=checkbox][name=procedure_variable_list]:checked")).map(e => e.value);

    var formData = {
        "id": id,
    }
    $('#newProcedure_variables').modal('hide');
    $("#confirm_delete_procedure_variables").html("You want to delete this variables?");
    $("#confirm_delete_procedure_variables").dialog({
        resizable: false,
        modal: true,
        title: "Are you sure?",
        height: 210,
        width: 360,
        buttons: {
            "Yes": function () {
                $(this).dialog('close');
                $('#newProcedure_variables').modal('show');
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            // getProcedureVariables();
                            $("input[name='procedure_variable_list']:checked + label").remove();
                            getVeriableForSelect();
                        } else {
                            showAlert(data.error.message, 'error');
                        }
                    }
                });
            },
            "No": function () {
                $(this).dialog('close');
                $('#newProcedure_variables').modal('show');
                return false;
            }
        }
    });
});


$("#insert_majorProcedure_select").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var fldreportquali = $('#newPorcedure_fldreportqualiSelect').val();
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "flditemid": flditemid,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldreportquali": fldreportquali,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Inserted Successfully");
                getPatSubGeneralComponents();
            } else {
                showAlert("error", 'error');
            }
        }
    });
});

$("#insertFlddetailNewProcedure").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var fldid = $('#newProcedure_fldid').val();
    var flddetail = CKEDITOR.instances.newprocedure_detail.getData()//$('.newProcedure_flddetail').val();
    var url = $(this).attr('url');
    var formData = {
        "fldencounterval": fldencounterval,
        "fldid": fldid,
        "flddetail": flddetail,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert(data.success.message);
                getPatGeneralDetails();
            } else {
                showAlert(data.error.message, 'error');
            }
        }
    });
});


function getInitialProcedureCategoryAjaxs() {
    $.get("majorprocedure/new-procedure/getInitialProcedureCategoryAjaxs", function (data) {
        // Display the returned data in browser
        $("#procedureExcel").empty().append(data);
    });
}
$(document).on('click', '.diagnosissub', function () {
    // alert('click sub bhayo');

    $('input[name="diagnosissub"]').bind('click', function () {
        $('input[name="diagnosissub"]').not(this).prop("checked", false);
    });
    var diagnosub = $("input[name='diagnosissub']");

    if (diagnosub.is(':checked')) {
        var value = $(this).val();

        $('#diagnosissubname').val(value);
    } else {
        $("#diagnosissubname").val('');
    }
});

$(document).on("click", '.dccat', function () {
    // showAlert('click bhayo');
    $('input[name="dccat"]').bind("click", function () {
        $('input[name="dccat"]').not(this).prop("checked", false);
    });
    var diagnocode = $("input[name='dccat']");
    $('#code').val($(this).val());
    if (diagnocode.is(':checked')) {
        diagnocode = $(this).val() + ",";
        diagnocode = diagnocode.slice(0, -1);
        $("input[name='dccat']").attr('checked', false);
        if (diagnocode.length > 0) {
            // showAlert(diagnocode);
            // $.get("majorprocedure/new-procedure/getProcedureByCodes", {term: diagnocode}).done(function (data) {
            //     // Display the returned data in browser
            //     $("#sublist").html(data);
            // });
            $.get("getDiagnosisByCode", {term: diagnocode}, {dataType: 'json'}).done(function (data) {
                // Display the returned data in browser
                $("#sublist").html(data);
            });
        }
    } else {
        $("#sublist").html('');
    }
});

$('.onclose').on("click", function () {
    $('input[name="dccat"]').prop("checked", false);
    $('#code').empty();
    $("#displayProcedure").empty();
    $("#sublist").empty();
});

$(document).on("click", '.procedureOnText', function () {
    // showAlert('click sub bhayo');
    $('input[name="procedureOnText"]').bind("click", function () {
        $('input[name="procedureOnText"]').not(this).prop("checked", false);
    });
    var diagnosub = $("input[name='procedureOnText']");
    if (diagnosub.is(':checked')) {
        var value = $(this).val();
        $('#displayProcedure').val(value);
    } else {
        $("#displayProcedure").val('');
    }
});

$("#insertExcelProcedure").click(function () {
    alert('asdf')
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldreportquali = $('#displayProcedure').val();
    var flduserid = $('#flduserid').val();
    var fldcomp = $('#fldcomp').val();
    var url = $(this).attr("url");
    var formData = {
        "fldencounterval": fldencounterval,
        "flditemid": flditemid,
        "flduserid": flduserid,
        "fldcomp": fldcomp,
        "fldreportquali": fldreportquali,
    }
    if (flditemid === "" || fldreportquali === "") {
        showAlert('Requirement not match (Please Re-Try)', 'error');
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                console.log(data);
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    getPatSubGeneralComponents();
                    $('#newProcedureExcel').modal('toggle');
                } else {
                    showAlert("Something went wrong!!", 'error');
                }
            }
        });
    }
});

// Operation
// clinical note
$("#save-clinical-indication-operation").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication-operation").val();
    var fldchapter = 'Operation Indication';
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreportquali: fldreportquali,
        flditemid: flditemid,
        fldchapter: fldchapter
    };
    console.log(formData);
    if (fldencounterval === "" || fldreportquali === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showOperationClinicalIndicationFldReportquali();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// To list Notes
$(document).on("click", ".rowOnclick tr", function () {
    var fldid = $(this).find("input").val();
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Operation Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldid=' + fldid + '&fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('#clinical-note').text(null);
        $('#clinical-indication').empty();
        $('#current_id_preO').empty();
        $('#chapter_preO').empty();
        $.each(data, function (index, get_list_detail) {
            $('#clinical-note').text(get_list_detail.fldreport);
            $('#clinical-indication').val(get_list_detail.fldreportquali);
            $('#current_id_preO').val(get_list_detail.fldid);
            $('#chapter_preO').val(get_list_detail.fldchapter);
        });
    });
});

// clinical note
$("#save-clinical-note-operation").click(function () {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldid = $("#current_id_o").val();
    var fldchapter = $("#chapter_o").val();
    var fldreport = CKEDITOR.instances.clinical_note_operation_textarea.getData();//$("#clinical-note").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication-operation").val();
    var url = $(this).attr('url');

    var formData = {
        fldencounterval: fldencounterval,
        flditemid: flditemid,
        fldreport: fldreport,
        fldid: fldid,
        fldchapter: fldchapter,
        fldcomp: fldcomp,
        // flduserid: flduserid,
        fldreportquali: fldreportquali
    };
    console.log(formData);
    if (fldencounterval === "" || flduserid === "" || fldcomp === "" || fldreport === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showOperationClinicalNoteFldReport();
                    showOperationClinicalIndicationFldReportquali();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// Post-Operative
// clinical note
$("#save-clinical-indication-postOp").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    // var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication-postOp").val();
    var fldchapter = 'Post-Operative Indication';
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreportquali: fldreportquali,
        flditemid: flditemid,
        fldchapter: fldchapter
    };
    console.log(formData);
    if (fldencounterval === "" || fldreportquali === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showPostOpClinicalIndicationFldReportquali();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// clinical note
$("#save-clinical-note-postOp").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldreportquali = $("#clinical-indication-postOp").val();
    var fldcomp = $("#fldcomp").val();
    var fldid = $("#current_id_postO").val();
    var fldreport = CKEDITOR.instances.clinical_note_postOp_textarea.getData();//$("#clinical-note-postOp").val();
    var fldchapter = 'Post-Operative Indication';
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        fldid: fldid,
        fldcomp: fldcomp,
        fldreport: fldreport,
        flditemid: flditemid,
        fldchapter: fldchapter,
        fldreportquali: fldreportquali
    };
    console.log(formData);
    if (fldencounterval === "" || fldcomp === "" || fldreport === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showOperationClinicalNoteFldReport();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// Anaesthesia
$("#insert_anasethesia_variables").click(function () {
    var flditem = $('#anaesthesia_flditem').val();
    var url = $(this).attr('url');

    var formData = {
        "flditem": flditem,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Inserted Successfully");
                getAnaesthesiaVariables();
                getAnaesthesiaVariablesSelect();
            } else {
                showAlert("error", 'error');
            }
        }
    });
});

$("#delete_anasethesia_variables").click(function () {
    var id = $('input[name="ana-var"]:checked').val();
    var url = $(this).attr('url');

    var formData = {
        fldid: id,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert(data.success.message);
                getAnaesthesiaVariables();
                getAnaesthesiaVariablesSelect();
            } else {
                showAlert(data.error.message, 'error');
            }
        }
    });
});


// clinical note
$("#save-clinical-indication-ana").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#clinical-indication-ana option:selected").val();
    var fldchapter = 'Anaesthesia Indication';
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreportquali: fldreportquali,
        flditemid: flditemid,
        fldchapter: fldchapter
    };
    console.log(formData);
    if (fldencounterval === "" || fldreportquali === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showAnaesthesiaClinicalIndicationFldReportquali();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// clinical note
$("#save-clinical-note-ana").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    // var flduserid = $("#flduserid").val();
    var fldid = $("#current_id_ana").val();
    var fldcomp = $("#fldcomp").val();
    var fldreportquali = $("#report_quali_ana").val();
    var fldreport = CKEDITOR.instances.clinical_note_ana_textarea.getData();//$("#clinical-note-ana").val();
    var fldchapter = 'Anaesthesia Indication';
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        fldid: fldid,
        fldcomp: fldcomp,
        fldreport: fldreport,
        flditemid: flditemid,
        fldchapter: fldchapter,
        fldreportquali: fldreportquali
    };
    console.log(formData);
    if (fldencounterval === "" || flduserid === "" || fldcomp === "" || fldreport === "" || flditemid === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    showAnaesthesiaOperationClinicalNoteFldReport();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// Personnel
$("#insert-personnel").click(function () {
    var fldencounterval = $("#fldencounterval").val();
    var flditemid = $('#newProcedure_fldid').val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var fldreport = $("#personnel-description").val();
    var fldreportquali = $("#personnel-username option:selected").val();
    var fldchapter = $("#personnel-category option:selected").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        flditemid: flditemid,
        // flduserid: flduserid,
        fldcomp: fldcomp,
        fldreport: fldreport,
        fldreportquali: fldreportquali,
        fldchapter: fldchapter
    };
    if (fldencounterval === "" || flduserid === "" || fldcomp === "" || fldreportquali === "" || flditemid === "" || fldchapter === "" || fldreport === "") {
        showAlert("The field is empty or encounter id not given.", 'error');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    getPersonnelData();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


$("#other-items-select").on('change', function (e) {
    var fldid = e.target.value;
    // ajax
    $.get('majorprocedure/operation/other-items-select?fldid=' + fldid, function (data) {
        // success
        $('#other-items-rate').empty();
        $('#other-items-dis').empty();
        $('#other-items-tax').empty();

        $('.flditemno').empty();
        $('.flddiscper').empty();
        $('.fldorduserid').empty();
        $('.fldordcomp').empty();
        $('.fldordtime').empty();
        $('.fldalert').empty();
        $('.fldtarget').empty();
        $('.fldtaxamt').empty();

        $.each(data, function (index, get_list_detail) {
            $('#other-items-rate').val(get_list_detail.flditemrate);
            $('#other-items-dis').val(get_list_detail.flddiscamt);
            $('#other-items-tax').val(get_list_detail.fldtaxper);

            $('.flditemno').val(get_list_detail.flditemno);
            $('.flddiscper').val(get_list_detail.flddiscper);
            $('.fldorduserid').val(get_list_detail.fldorduserid);
            $('.fldordcomp').val(get_list_detail.fldordcomp);
            $('.fldordtime').val(get_list_detail.fldordtime);
            $('.fldalert').val(get_list_detail.fldalert);
            $('.fldtarget').val(get_list_detail.fldtarget);
            $('.fldtaxamt').val(get_list_detail.fldtaxamt);
        });
    });
});

$("#insert-other-items").click(function () {
    var fldencounterval = $("#fldencounterval").val();

    var flditemno = $(".flditemno").val();
    var flddiscper = $(".flddiscper").val();
    var fldorduserid = $(".fldorduserid").val();
    var fldordcomp = $(".fldordcomp").val();
    var fldordtime = $(".fldordtime").val();
    var fldalert = $(".fldalert").val();
    var fldtarget = $(".fldtarget").val();
    var fldtaxamt = $(".fldtaxamt").val();

    var flditemname = $("#other-items-select option:selected").attr('rel');
    var flditemrate = $("#other-items-rate").val();
    var fldtaxper = $("#other-items-tax").val();
    var flddiscamt = $("#other-items-dis").val();
    var flditemqty = $("#other-items-qty").val();

    var url = $(this).attr("url");

    var formData = {
        fldencounterval: fldencounterval,

        flditemno: flditemno,
        flddiscper: flddiscper,
        fldorduserid: fldorduserid,
        fldordcomp: fldordcomp,
        fldordtime: fldordtime,
        fldalert: fldalert,
        fldtarget: fldtarget,

        flditemname: flditemname,
        flditemrate: flditemrate,
        fldtaxper: fldtaxper,
        flddiscamt: flddiscamt,
        flditemqty: flditemqty,
        fldtaxamt: fldtaxamt
    };
    if (fldencounterval === "" || flditemname === "" || flditemrate === "" || fldtaxper === "" || flddiscamt === "" || flditemqty == '0') {
        showAlert('Please fill all the required fields');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Inserted Successfully");
                    getOtherItemsData();
                } else {
                    showAlert("error", 'error');
                }
            }
        });
    }
});


// Phramacy
$("#getAllPhramacy").click(function () {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/pre-operative/phramacy/show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show-all-phramacy').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show-all-phramacy').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$("#getAllPhramacyPostOp").click(function () {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/post-operative/phramacy/show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show-all-phramacyPostOp').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show-all-phramacyPostOp').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$("#getAllPhramacyOperation").click(function () {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/operation/phramacy/show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show-all-phramacyOperation').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show-all-phramacyOperation').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$("#getAllPhramacyAnaesthesia").click(function () {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/anaesthesia/phramacy/show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show-all-phramacyAnaesthesia').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show-all-phramacyAnaesthesia').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});


$(document).on("click", '.pre-operative-examination-list tr', function () {
    selected_td('.pre-operative-examination-list tr', this);
    var ans_type = $(this).attr('data-fldtanswertype');
    var examid = $(this).attr('data-fldexamid');
    var type = $(this).attr('data-fldtype');
    var depttype = "Pre-Operative";
    modalRequestFunction(type, examid, ans_type, depttype);
});


$(document).on("click", ".post-operative-examination-list tr", function () {
    selected_td(".post-operative-examination-list tr", this);
    var ans_type = $(this).attr('data-fldtanswertype');
    var examid = $(this).attr('data-fldexamid');
    var type = $(this).attr('data-fldtype');
    var depttype = "Post-Operative";
    modalRequestFunction(type, examid, ans_type, depttype);
});


$(document).on("click", '.operation-examination-list tr', function () {
    selected_td('.operation-examination-list tr', this);
    var ans_type = $(this).attr('data-fldtanswertype');
    var examid = $(this).attr('data-fldexamid');
    var type = $(this).attr('data-fldtype');
    var depttype = 'Operation';
    modalRequestFunction(type, examid, ans_type, depttype);
});


$(document).on("click", '.anaesthesia-examination-table tr', function () {
    selected_td('.anaesthesia-examination-table tr', this);
    var ans_type = $(this).attr('data-fldtanswertype');
    var examid = $(this).attr('data-fldexamid');
    var type = $(this).attr('data-fldtype');
    var depttype = 'Anaesthesia';
    modalRequestFunction(type, examid, ans_type, depttype);
});


$(document).on("click", '#js-examination-save-modal', function () {
    getExaminationInformation();
});

function getExaminationInformation() {
    alert('here')
    var examinationid = $('#examinationExamidjs').val();
    var examtype = $('#examinationTypejs').val();
    var examOption = $('#examinationAnsTypejs').val();
    var deleveryType = $('#examinationDeleveryType').val();
    var qualitative = '';
    var quantative = '0';

    if (examOption === 'Left and Right') {
        qualitative = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
    } else if (examOption == 'No Selection') {
        qualitative = $('#js-input-no-selection').val();
        quantative = qualitative;
    } else {
        qualitative = $('#js-input-element').val();
    }

    var data = {
        fldinput: deleveryType,
        examtype: examtype,
        examinationid: examinationid,
        qualitative: qualitative,
        quantative: quantative,
        abnormalVal: ($('#js-abnormal').length !== undefined && $('#js-abnormal').prop('checked')) ? '1' : '0'
    };
    $.ajax({
        url: baseUrl + '/majorprocedure/savePatientExaminations',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var observationVal = (examOption == 'No Selection' || examOption == 'Clinical Scale') ? val.quantative : val.qualitative;
                var abnormalVal = (val.abnormal == '0') ? '<div class="btn btn-success btn-sm"></div>' : '<div class="btn btn-danger btn-sm"></div>';

                var trData = '<tr data-fldid="' + val.fldid + '">';
                trData += '<td>' + val.examination + '</td>';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + observationVal + '</td>';
                trData += '<td>' + val.time + '</td>';
                trData += '</tr>';
                if (val.tabName == "Pre-Operative")
                    $('.major-pre-examination-table').append(trData);
                if (val.tabName == "Post-Operative")
                    $('.major-post-examination-table').append(trData);
                if (val.tabName == 'Operation')
                    $('.major-operation-examination-table').append(trData);
                if (val.tabName == 'Anaesthesia')
                    $('.major-anaesthesia-examination-table').append(trData);
            }
            showAlert(response.message);
            $('#js-examination-content-modal').modal('hide');
        }
    });
}

function getMajorExaminationTable(fldinput) {
    $.ajax({
        url: baseUrl + '/majorprocedure/getPatientExaminations',
        type: "GET",
        data: {fldinput: fldinput},
        dataType: "json",
        success: function (response) {
            var trData = '';

            $.each(response, function (i, val) {
                var abnormalVal = (val.abnormal == '0') ? '<div class="btn btn-success btn-sm"></div>' : '<div class="btn btn-danger btn-sm"></div>';

                var trData = '<tr data-fldid="' + val.examination + '">';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + val.fldrepdate + '</td>';
                trData += '</tr>';
            });

            if (fldinput == "Pre-Operative")
                $('.major-pre-examination-table').html(trData);
            if (fldinput == "Post-Operative")
                $('.major-post-examination-table').html(trData);
            if (fldinput == 'Operation')
                $('.major-operation-examination-table').html(trData);
            if (fldinput == 'Anaesthesia')
                $('.major-anaesthesia-examination-table').html(trData);
        }
    });
}

function modalRequestFunction(type, examid, ans_type, depttype) {
    $.ajax({
        url: baseUrl + '/majorprocedure/getModalContent',
        type: "GET",
        data: {ans_type: ans_type, examid: examid, type: type, depttype: depttype},
        dataType: "json",
        success: function (response) {
            $('#js-examination-content-modal').html(response.view_data);
            $('#js-examination-content-modal').modal('show');
        }
    });
}

// Anaesthesia
// List
function getExaminationAnaesthesia() {
    // var fldencounterval = $("#encounter_id").val();
    var flddept = 'Anaesthesia';

    $.ajax({
        url: baseUrl + '/majorprocedure/getExaminationList',
        type: "GET",
        data: {flddept: flddept},
        dataType: "json",
        success: function (response) {
            var trData = '';

            $.each(response, function (i, val) {
                trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                trData += '<td>' + val.fldexamid + '</td></tr>';
            });
            $('.anaesthesia-examination-list').html(trData);
        }
    });
}

// Operation
// List
function getExaminationOperation() {
    // var fldencounterval = $("#encounter_id").val();
    var flddept = 'Operation';

    $.ajax({
        url: baseUrl + '/majorprocedure/getExaminationList',
        type: "GET",
        data: {flddept: flddept},
        dataType: "json",
        success: function (response) {
            var trData = '';

            $.each(response, function (i, val) {
                trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                trData += '<td>' + val.fldexamid + '</td></tr>';
            });
            $('.operation-examination-list').html(trData);
        }
    });
}

// Post Operative
// List
function getExaminationPostOperative() {
    // var fldencounterval = $("#encounter_id").val();
    var flddept = "Post-Operative";

    $.ajax({
        url: baseUrl + '/majorprocedure/getExaminationList',
        type: "GET",
        data: {flddept: flddept},
        dataType: "json",
        success: function (response) {
            var trData = '';

            $.each(response, function (i, val) {
                trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                trData += '<td>' + val.fldexamid + '</td></tr>';
            });
            $('.post-operative-examination-list').html(trData);
        }
    });
}

// Examination
// Pre Operative
// List
function getExaminationPreOperative() {
    // var fldencounterval = $("#encounter_id").val();
    var flddept = "Pre-Operative";

    $.ajax({
        url: baseUrl + '/majorprocedure/getExaminationList',
        type: "GET",
        data: {flddept: flddept},
        dataType: "json",
        success: function (response) {
            var trData = '';

            $.each(response, function (i, val) {
                trData += '<tr data-fldexamid="' + val.fldexamid + '" data-fldtype="' + val.fldtype + '" data-fldtanswertype="' + val.fldtanswertype + '">';
                trData += '<td>' + val.fldexamid + '</td></tr>';
            });
            $('.pre-operative-examination-list').html(trData);
        }
    });
    // ajax
    // $.get('majorprocedure/pre-operative/getExaminationList?fldencounterval=' + fldencounterval + '&fldinput=' + fldinput, function(data) {
    //     // succecc data
    //     $('.pre-operative-examination-list').empty();
    //     var n = 1;
    //     $.each(data, function(index, get_list_detail) {
    //         $('.pre-operative-examination-list').append('<li rel="'+ get_list_detail.fldid +'">'+get_list_detail.fldhead+'</li>');
    //         n++;
    //     });
    // });
}

// Load immidetly after above query
function getOtherItemsData() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    // ajax
    $.get('majorprocedure/operation/getOtherItemsData?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        $('.show-other-items-table').empty();
        var n = 1;
        $.each(data, function (index, get_list_detail) {
            $('.show-other-items-table').append('<tr><td>' + n + '</td><td>' + get_list_detail.fldid + '</td><td>' + get_list_detail.fldordtime + '</td><td>' + get_list_detail.flditemtype + '</td><td>' + get_list_detail.flditemno + '</td><td>' + get_list_detail.flditemname + '</td><td>' + get_list_detail.flditemrate + '</td><td>' + get_list_detail.flddiscper + '</td><td>' + get_list_detail.fldtaxper + '</td><td>' + get_list_detail.fldditemamt + '</td></tr>');
            n++;
        });
    });
}

// Load immidetly after above query
function getPersonnelData() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    // ajax
    $.get('majorprocedure/personnel/getData?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid, function (data) {
        // succecc data
        $('.show-personnel-table').empty();
        $.each(data, function (index, get_list_detail) {
            $('.show-personnel-table').append('<tr><td>' + get_list_detail.fldtime + '</td><td>' + get_list_detail.fldchapter + '</td><td>' + get_list_detail.fldreportquali + '</td><td>' + get_list_detail.fldreport + '</td></tr>');
        });
    });
}

// Load immidetly after above query
function showAnaesthesiaOperationClinicalNoteFldReport() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Anaesthesia Note';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.list-clinical-note-ana').empty();
        $.each(data, function (index, get_list_detail) {
            $('.list-clinical-note-ana').append('<tr><th>' + get_list_detail.fldtime + '</th><th>' + get_list_detail.fldreport + '</th><th>Note</th></tr>');
        });
    });
}

// Load immidetly after above query
function showAnaesthesiaClinicalIndicationFldReportquali() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Anaesthesia Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-indication?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.show-clinical-indication-ana').empty();
        var n = 1;
        $.each(data, function (index, get_list_detail) {
            $('.show-clinical-indication-ana').append('<tr><input type="hidden" value="' + get_list_detail.fldid + '"><td>' + n + '</td><td>' + get_list_detail.fldtime + '</td><td>' + get_list_detail.fldreportquali + '</td></tr>');
            n++;
        });
    });
}

function getAnaesthesiaVariables() {
    // ajax
    $.get('majorprocedure/new-procedure/getAnaesthesiaVariables', function (data) {
        // succecc data
        $('.anasethesia-variables').empty();
        $.each(data, function (index, get_list_detail) {
            $('.anasethesia-variables').append('<input type="radio" name="ana-var" class="anaesthesia-ariables-checkbox" value="' + get_list_detail.fldid + '" id="deleteAnasethesiaVariables-' + get_list_detail.fldid + '" style="display:none;"><label style="display:block;" for="deleteAnasethesiaVariables-' + get_list_detail.fldid + '">' + get_list_detail.flditem + '</label>');
        });
    });
}

function getAnaesthesiaVariablesSelect() {
    // ajax
    $.get('majorprocedure/new-procedure/getAnaesthesiaVariables', function (data) {
        // succecc data
        $('#clinical-indication-ana').empty();
        $.each(data, function (index, get_list_detail) {
            $('#clinical-indication-ana').append('<option value="' + get_list_detail.flditem + '">' + get_list_detail.flditem + '</option>');
        });
    });
}

// Load immidetly after above query
function showOperationClinicalNoteFldReport() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Post-Operative Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.list-clinical-note-postOp').empty();
        $.each(data, function (index, get_list_detail) {
            $('.list-clinical-note-postOp').append('<tr><th>' + get_list_detail.fldtime + '</th><th>' + get_list_detail.fldreport + '</th><th>Note</th></tr>');
        });
    });
}

// Load immidetly after above query
function showPostOpClinicalIndicationFldReportquali() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Post-Operative Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-indication?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.show-clinical-indication-postOp').empty();
        var n = 1;
        $.each(data, function (index, get_list_detail) {
            $('.show-clinical-indication-postOp').append('<tr><input type="hidden" value="' + get_list_detail.fldid + '"><td>' + n + '</td><td>' + get_list_detail.fldtime + '</td><td>' + get_list_detail.fldreportquali + '</td></tr>');
            n++;
        });
    });
}

// Load immidetly after above query
function showOperationClinicalNoteFldReport() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Operation Note';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-note?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.list-clinical-note-operation').empty();
        $.each(data, function (index, get_list_detail) {
            $('.list-clinical-note-operation').append('<tr><th>' + get_list_detail.fldtime + '</th><th>' + get_list_detail.fldreport + '</th><th>Note</th></tr>');
        });
    });
}

// Load immidetly after above query
function showOperationClinicalIndicationFldReportquali() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Operation Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-indication?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.show-clinical-indication-operation').empty();
        var n = 1;
        $.each(data, function (index, get_list_detail) {
            $('.show-clinical-indication-operation').append('<tr><input type="hidden" value="' + get_list_detail.fldid + '"><td>' + n + '</td><td>' + get_list_detail.fldtime + '</td><td>' + get_list_detail.fldreportquali + '</td></tr>');
            n++;
        });
    });
}

function getPatGeneralDetails() {
    var fldid = $('#newProcedure_fldid').val();
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/new-procedure/getDetails?fldid=' + fldid + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        // $('.newProcedure_flddetail').empty();
        $.each(data, function (index, get_list_detail) {
            CKEDITOR.instances.newprocedure_detail.setData(get_list_detail.flddetail);
        });
    });
}

function getPatGeneralDetailsWithID(fldid) {
    if (fldid == null) {
        return false;
    }
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/new-procedure/getDetails?fldid=' + fldid + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        // $('.newProcedure_flddetail').empty();
        $.each(data, function (index, get_list_detail) {
            CKEDITOR.instances.newprocedure_detail.setData(get_list_detail.flddetail);
        });
    });
}

function getVeriableForSelect() {
    // ajax
    $.get('majorprocedure/new-procedure/getVariables', function (data) {
        // succecc data
        $('#newPorcedure_fldreportqualiSelect').empty();
        $.each(data, function (index, get_list_detail) {
            $('#newPorcedure_fldreportqualiSelect').append('<option value="' + get_list_detail.flditem + '">' + get_list_detail.flditem + '</option>');
        });
    });
}

// Load immidetly after above query
function getPatSubGeneralComponents() {
    var flditemid = $('#newProcedure_fldid').val();
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/new-procedure/getComponents?flditemid=' + flditemid + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        $('.newprocedure-table-components').empty();
        $.each(data, function (index, get_list_detail) {
            $('.newprocedure-table-components').append('<tr><td>' + get_list_detail.fldreportquali + '</td></tr>');
        });
    });
}

function getPatSubGeneralComponentsWithID(flditemid) {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get('majorprocedure/new-procedure/getComponents?flditemid=' + flditemid + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        $('.newprocedure-table-components').empty();
        $.each(data, function (index, get_list_detail) {
            $('.newprocedure-table-components').append('<tr><td>' + get_list_detail.fldreportquali + '</td></tr>');
        });
    });
}

function getProcedureVariables() {
    // ajax
    // showAlert('here')
    $.get('majorprocedure/new-procedure/getVariables', function (data) {
        // succecc data
        $('.listed-variables').empty();
        $.each(data, function (index, get_list_detail) {
            $('.listed-variables').append('<input type="checkbox" name="procedure_variable_list" class="variables-checkbox" rel="' + get_list_detail.flditem + '" value="' + get_list_detail.fldid + '" id="deleteVariables-' + get_list_detail.fldid + '" style="display:none;"><label class="procedure_label_variable" for="deleteVariables-' + get_list_detail.fldid + '">' + get_list_detail.flditem + '</label><br>');
        });
    });
}

// Load immidetly after above query
function getRelatedDataNewProcedure() {
    var fldencounterval = $("#encounter_id").val();

    // ajax
    $.get('majorprocedure/new-procedure/reload-table?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        $('.getRelatedDataNewProcedure').empty();
        var sn = 1;
        $.each(data, function (index, get_list_detail) {
            $('.getRelatedDataNewProcedure').append('<tr rel="' + get_list_detail.fldid + '"><th>' + sn + '</th><th>' + get_list_detail.fldnewdate + '</th><th>' + get_list_detail.flditem + '</th><th>' + get_list_detail.fldreportquali + '</th><th><input type="checkbox"></th></tr>');
            sn++;
        });
    });
}

function clearTables() {
    $('.major-pre-examination-table').empty();
    $('.show-clinical-indication').empty();
    $('.show-all-phramacy').empty();
    $('.major-operation-examination-table').empty();
    $('.show-clinical-indication-operation').empty();
    $('.show-all-phramacyOperation').empty();
    $('.show-personnel-table').empty();
    $('.show-other-items-table').empty();
    $('.anaesthesia-examination-list').empty();
    $('.major-anaesthesia-examination-table').empty();
    $('.show-clinical-indication-ana').empty();
    $('.show-all-phramacyAnaesthesia').empty();
    $('.major-post-examination-table').empty();
    $('.show-clinical-indication-postOp').empty();
    $('.show-all-phramacyPostOp').empty();
}

// Load immidetly after above query
function showAllFldReportquali() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $("#newProcedure_fldid").val();
    // ajax
    $.get("majorprocedure/pre-operative/getPreOperativeDiscussion?fldencounterval=" + fldencounterval + "&flditemid=" + flditemid, function (data) {
        // succecc data
        $(".dislpay-pre-operative-fldreportquali").empty();
        $.each(data, function (index, get_list_detail) {
            $(".dislpay-pre-operative-fldreportquali").append("<tr><td>" + get_list_detail.fldreportquali + "</td></tr>");
        });
    });
}

// Load immidetly after above query
function showAllFldReport() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $("#newProcedure_fldid").val();
    // ajax
    $.get("majorprocedure/pre-operative/getPreOperativeDiscussionTxtArea?fldencounterval=" + fldencounterval + "&flditemid=" + flditemid, function (data) {
        // succecc data
        // $('#pre-operative-discussion-textarea').empty();
        // $.each(data, function(index, get_list_detail) {
        // CKEDITOR.instances.pre_operative_discussion_textarea.setData(data.fldreport);
        CKEDITOR.instances["pre_operative_discussion_textarea"].setData(data.fldreport);
        // });
    });
}

function getPatFinding() {
    var fldencounterval = $("#encounter_id").val();
    // ajax
    $.get("majorprocedure/pre-operative/getPatFinding?fldencounterval=" + fldencounterval, function (data) {
        // succecc data
        $(".display-pat-finding").empty();
        $.each(data, function (index, get_list_detail) {
            $(".display-pat-finding").append("<tr><td>" + get_list_detail.fldcode + "</tr></td>");
        });
    });
}

// Load immidetly after above query
function showClinicalIndicationFldReportquali() {
    var fldencounterval = $("#encounter_id").val();
    var flditemid = $('#newProcedure_fldid').val();
    var fldchapter = 'Pre-Operative Indication';
    // ajax
    $.get('majorprocedure/pre-operative/get-clinical-indication?fldencounterval=' + fldencounterval + '&flditemid=' + flditemid + '&fldchapter=' + fldchapter, function (data) {
        // succecc data
        $('.show-clinical-indication').empty();
        var n = 1;
        $.each(data, function (index, get_list_detail) {
            $('.show-clinical-indication').append('<tr><input type="hidden" value="' + get_list_detail.fldid + '"><td>' + n + '</td><td>' + get_list_detail.fldtime + '</td><td>' + get_list_detail.fldreportquali + '</td></tr>');
            n++;
        });
    });
}

$("#save_essential").on("click", function () {

    var pulse_rate =
        $("#pulse_rate").attr("pulse_rate") + ":" + $("#pulse_rate").val();
    var sys_bp = $("#sys_bp").attr("sys_bp") + ":" + $("#sys_bp").val();
    var dia_bp = $("#dia_bp").attr("dia_bp") + ":" + $("#dia_bp").val();
    var respi = $("#respi").attr("respi") + ":" + $("#respi").val();
    var saturation =
        $("#saturation").attr("saturation") + ":" + $("#saturation").val();
    var pulse_rate_rate =
        $("#pulse_rate_rate").attr("pulse_rate_rate") +
        ":" +
        $("#pulse_rate_rate").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();

    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        flduserid: flduserid,
        fldcomp: fldcomp,
        "essential[]": [
            pulse_rate,
            sys_bp,
            dia_bp,
            respi,
            saturation,
            pulse_rate_rate
        ]
    };

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Information saved!!");
                $('#pulse_rate_rate').val(null);
                $('#pulse_rate').val(null);
                $('#sys_bp').val(null);
                $('#respi').val(null);
                $('#saturation').val(null);
                $('#grbs').val(null);
                $('#dia_bp').val(null);

                $('#sys_bp').removeClass('highline');
                $('#sys_bp').removeClass('lowline');
                $('#dia_bp').removeClass('highline');
                $('#dia_bp').removeClass('lowline');
                $('#pulse_rate').removeClass('highline');
                $('#pulse_rate').removeClass('lowline');
                $('#pulse_rate_rate').removeClass('highline');
                $('#pulse_rate_rate').removeClass('lowline');
                $('#respi').removeClass('highline');
                $('#respi').removeClass('lowline');
                $('#saturation').removeClass('highline');
                $('#saturation').removeClass('lowline');
                $('#grbs').removeClass('highline');
                $('#grbs').removeClass('lowline')
                $.get('essential_exam/get_essential_exam?fldencounterval=' + fldencounterval, function (data) {
                    // console.log(data);
                    $.each(data, function (index, getValue) {
                        if (index == 'systolic_bp') {
                            $('#sys_bp').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#sys_bp').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#sys_bp').addClass('lowline');
                            }
                        }

                        if (index == 'diasioli_bp') {
                            $('#dia_bp').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#dia_bp').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#dia_bp').addClass('lowline');
                            }
                        }

                        if (index == 'pulse') {
                            $('#pulse_rate').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#pulse_rate').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#pulse_rate').addClass('lowline');
                            }
                        }

                        if (index == 'temperature') {
                            $('#pulse_rate_rate').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#pulse_rate_rate').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#pulse_rate_rate').addClass('lowline');
                            }
                        }

                        if (index == 'respiratory_rate') {
                            $('#respi').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#respi').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#respi').addClass('lowline');
                            }
                        }

                        if (index == 'o2_saturation') {
                            $('#saturation').val(getValue.fldrepquanti);
                            if (getValue.fldrepquanti >= getValue.fldhigh) {
                                $('#saturation').addClass('highline');
                            }
                            if (getValue.fldrepquanti <= getValue.fldlow) {
                                $('#saturation').addClass('lowline');
                            }
                        }

                        // if(index == 'grbs'){
                        //     $('#grbs').val(getValue.fldrepquanti);
                        //     if(getValue.fldrepquanti >= getValue.fldhigh){
                        //         $('#grbs').addClass('highline');
                        //     }
                        //     if(getValue.fldrepquanti <= getValue.fldlow){
                        //         $('#grbs').addClass('lowline');
                        //     }
                        // }
                    });
                });
                //location.reload();
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});
