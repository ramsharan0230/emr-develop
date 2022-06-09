$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

//essentail
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

$(".remove_zero_to_empty").on("focusin", function () {
    var current_val = $(this).val();
    if (current_val == 0) {
        $(this).val(null);
    }
})

//complaint

$("#insert_complaints").click(function () {
    var flditem = $(".flditem option:selected").val();
    var duration = $(".duration").val();
    var duration_type = $(".duration_type option:selected").val();
    var fldreportquali = $(".fldreportquali option:selected").val();

    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var url = $(this).attr("url");
    var latest_table_row_counter = $('.get_cheif_complent_data_table tr:last').find('.loop_iteration').html();

    if (fldencounterval === "") {
        showAlert('Slect patient first.', 'Error');
    }

    if ($.isNumeric(duration) === true) {
        var formData = {
            fldencounterval: fldencounterval,
            flduserid: flduserid,
            fldcomp: fldcomp,
            flditem: flditem,
            duration: duration,
            duration_type: duration_type,
            fldreportquali: fldreportquali,
            latest_table_row_counter: latest_table_row_counter
        };
        // console.log(formData);

        /*if (flditem == '' || duration == '' || duration_type == '' || fldreportquali == '') {*/
        if (flditem == '') {
            showAlert('Fill all the data', 'Error');
        } else {

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#complaintfldid').val(data.success.id);
                        $(".get_cheif_complent_data_table").last().append(data.success.row);
                    } else {
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        }
    } else {
        alert("Duration only numeric value allowed!!");
    }
});

$(".unauthorised").click(function () {
    permit_user = $(this).attr('permit_user');
    showAlert('Authorization with  ' + permit_user);
});

$(document).on("click", "#submit_detail_complaint", function () {
    var fldid = $("#complaintfldid").val();
    var flddetail = CKEDITOR.instances.editor.getData();
    var url = $(this).attr('url');
    // var current = $(this).attr('')
    formData = {
        fldid: fldid,
        flddetail: flddetail
    }
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        data: formData,
        success: function (data) {
            // console.log(data);
            if (data.success) {
                // alert(fldid);
                showAlert(data.success.message);
                $("#com_" + fldid).find('td:last-child').text(data.success.detail);
                // $("#detail_" + fldid).text(data.success.detail);
                $("#edit_complaint").modal("hide");
            } else {
                showAlert(data.error.message);
            }
        }
    });
});
$(document).on("click", ".clicked_edit_complaint", function (e) {
    $('#edit_complaint').modal('show');
    current_user = $('.current_user').val();
    permit_user = $(this).attr('permit_user');
    if (current_user == permit_user) {

        var id = $(this).attr("clicked_flag_val");

        var old_complaint_detail = $(this).attr("old_complaint_detail");
        $("#complaintfldid").val(id);
        var editor = CKEDITOR.instances.editor;
        CKEDITOR.instances.editor.setData(old_complaint_detail);

    } else {

        showAlert('Authorization with  ' + permit_user);
    }


});


$(".clicked_edit_finding").click(function () {
    var id = $(this).attr("clicked_flag_val");
    $("#findingfldid").val(id);

});


//finding

$("#insert_finding").click(function () {
    var find_fldhead = $("#find_fldhead option:selected").text();
    var find_fldtype = $("#find_fldtype").val();
    var fldrepquali = $("#find_fldrepquali").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        flduserid: flduserid,
        fldcomp: fldcomp,
        fldrepquali: fldrepquali,
        fldtype: find_fldtype,
        fldhead: find_fldhead
    };
    disableButton();
    // console.log(formData);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                location.reload();
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});

$(".delete_finding").click(function () {
    current_user = $('.current_user').val();
    permit_user = $(this).attr('permit_user');
    if (current_user == permit_user) {
        var cur = $(this);
        var url = $(this).attr("url");
        if (confirm("Are you sure?")) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        cur.closest("tr").remove();
                    } else {
                        showAlert("Something went wrong!!", 'error');
                    }
                }
            });
        } else {
            return false;
        }
    } else {
        showAlert('Authorization with ' + permit_user);
    }
});

$(".clicked_flag").click(function () {
    var id = $(this).attr("clicked_flag_val");

    $("#findingfldidabn").val(id);
});

//tabs
$(".save_history").click(function () {
    var history = CKEDITOR.instances.history.getData();
    var url = $(".note_tabs").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var old_id = $(this).attr("old_id");

    var formData = {
        content: history,
        fldinput: "History",
        flduserid: flduserid,
        fldcomp: fldcomp,
        fldencounterval: fldencounterval,
        old_id: old_id
    };

    // console.log(formData);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Information saved!!");
                //location.reload();
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});

$(".save_advice").click(function () {
    var advice = CKEDITOR.instances.advice.getData();
    var url = $(".note_tabs").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var old_id = $(this).attr("old_id");

    var formData = {
        content: advice,
        fldinput: "Notes",
        flduserid: flduserid,
        fldcomp: fldcomp,
        fldencounterval: fldencounterval,
        old_id: old_id
    };

    // console.log(formData);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Information saved!!");
                //location.reload();
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});


$(".save_fluid").click(function () {
    var fluid = CKEDITOR.instances.fluid.getData();
    var url = $(".note_tabs").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var old_id = $(this).attr("old_id");

    var formData = {
        content: fluid,
        fldinput: "fluid",
        flduserid: flduserid,
        fldcomp: fldcomp,
        fldencounterval: fldencounterval,
        old_id: old_id
    };

    // console.log(formData);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert("Information saved!!");
                //location.reload();
            } else {
                showAlert("Something went wrong!!", 'error');
            }
        }
    });
});


$("#sys_bp").on("keyup", function () {
    var high = $(this).attr('high');
    var low = $(this).attr('low');

    var curr = $(this).val();
    if (curr > high)
        $(this).addClass('red-highlight');

    if (curr < low)
        $(this).addClass('green-highlight');


});

$("#dia_bp").on("keyup", function () {
    var high = $(this).attr('high');
    var low = $(this).attr('low');

    var curr = $(this).val();
    if (curr > high)
        $(this).addClass('red-highlight');

    if (curr < low)
        $(this).addClass('green-highlight');

});

$("#respi").on("keyup", function () {

    var high = $(this).attr('high');
    var low = $(this).attr('low');

    var curr = $(this).val();
    if (curr > high)
        $(this).addClass('red-highlight');

    if (curr < low)
        $(this).addClass('green-highlight');
});

$("#saturation").on("keyup", function () {
    var high = $(this).attr('high');
    var low = $(this).attr('low');

    var curr = $(this).val();
    if (curr > high)
        $(this).addClass('red-highlight');

    if (curr < low)
        $(this).addClass('green-highlight');

});

$("#pulse_rate_rate").on("keyup", function () {
    var high = $(this).attr('high');
    var low = $(this).attr('low');

    var curr = $(this).val();
    if (curr > high)
        $(this).addClass('red-highlight');

    if (curr < low)
        $(this).addClass('green-highlight');

});
$(document).ready(function () {
    $(".find_fldhead").on("change", function () {

        var type = $("option:selected", this).attr("typeoption");
        var item = $("option:selected", this).val();
        var fldsysconst = $("option:selected", this).attr("fldsysconst");
        var fldtype = $("option:selected", this).attr("fldtype");
        var url = $("#get_content").val();
        var encounter_id = $("#encounter_id").val();

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {type: type, item: item},
            success: function (data) {
                // console.log(data);
                if ($.isEmptyObject(data.error)) {
                    // console.log(type);
                    if (type == "Clinical Scale") {
                        $("#scale_box").find(".modal_flditem").val(item);
                        $("#scale_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#scale_box").find(".modal_fldtype").val(fldtype);
                        $("#scale_box").find(".modal_fldencounterval").val(encounter_id);

                        $("#ajax_response_scale_list").empty();
                        $("#ajax_response_scale_list").html(data.success.options);
                        $("#scale_box").modal("show");
                    }

                    if (type == "Fixed Components") {

                        $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#text_box").find(".modal_flditem").val(item);
                        $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#text_box").find(".modal_fldtype").val(fldtype);
                        $("#text_box").modal("show");
                    }

                    if (type == "Left and Right") {
                        $("#lnr_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#lnr_box").find(".modal_flditem").val(item);
                        $("#lnr_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#lnr_box").find(".modal_fldtype").val(fldtype);
                        $("#lnr_box").modal("show");
                    }

                    if (type == "No Selection") {
                        $("#number_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#number_box").find(".modal_flditem").val(item);
                        $("#number_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#number_box").find(".modal_fldtype").val(fldtype);
                        $("#number_box").modal("show");
                    }

                    if (type == "Single Selection") {
                        $("#scale_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#scale_box").find(".modal_flditem").val(item);
                        $("#scale_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#scale_box").find(".modal_fldtype").val(fldtype);
                        $("#scale_box").modal("show");
                    }

                    if (type == "Text Addition") {
                        $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#text_box").find(".modal_flditem").val(item);
                        $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#text_box").find(".modal_fldtype").val(fldtype);
                        $("#text_box").modal("show");
                    }

                    if (type == "Text Table") {
                        $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#text_box").find(".modal_flditem").val(item);
                        $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                        $("#text_box").find(".modal_fldtype").val(fldtype);
                        $("#text_box").modal("show");
                    }
                } else {
                    showAlert("Something went wrong!!", 'error');
                }
            }
        });
    });
});


$(document).on("change", ".examanswer", function () {
    countToIdentify = $(this).attr("count");
    var item = $("option:selected", this).val();
    // alert(countToIdentify);
    $(".scaleindex-" + countToIdentify).val(item);
});


/**
 * Start Function for Allergy
 */

var ckbox = $("input[name='alpha']");
var chkId = '';

$('#deletealdrug').on('click', function () {

    $('#select-multiple-aldrug').each(function () {
        // alval = [];
        var finalval = $(this).val().toString();
        // alert(finalval);
        var url = $('.delete_pat_findings').val();
        // alert(url);
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {ids: finalval},
            success: function (data) {
                // console.log(data);
                if ($.isEmptyObject(data.error)) {

                    alert('Delete Drug ?');
                    location.reload();
                } else {
                    showAlert('Something went wrong!!');
                }
            }
        });
    });


});


/**
 * End Function for Allergy
 */

/**
 * Start Function for Diagnosis
 */


var table = $('table.datatable').DataTable({
    "paging": false

});

$(document).on('click', 'input[name="dccat"]', function () {
    // alert('click bhayo');

    $('input[name="dccat"]').bind('click', function () {
        $('input[name="dccat"]').not(this).prop("checked", false);
    });
    var diagnocode = $("input[name='dccat']");
    $('#code').val($(this).val());
    if (diagnocode.is(':checked')) {

        diagnocode = $(this).val() + ",";
        diagnocode = diagnocode.slice(0, -1);

        $("input[name='dccat']").attr('checked', false);

        if (diagnocode.length > 0) {
            // alert(diagnocode);
            $.get("getDiagnosisByCode", {term: diagnocode}, {dataType: 'json'}).done(function (data) {
                // Display the returned data in browser
                $("#sublist").html(data);
            });

            // $.ajax({
            //           url: '{{route("getDiagnosisByCode")}}',
            //           type: "POST",
            //           dataType: "json",
            //           data: {term:diagnocode},
            //           success: function(data) {
            //               $("#sublist").html(data);
            //           }
            //       });
        }
    } else {
        $("#sublist").html('');
    }
});

$('.onclose').on('click', function () {

    $('input[name="dccat"]').prop("checked", false);
    $('#code').val('');
    $("#diagnosissubname").val('');
    $("#sublist").val('');
});


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

$('#searchbygroup').on('click', function () {
    // alert('searchbygroup');
    var groupname = $('#diagnogroup').val();
    // alert(groupname);
    if (groupname.length > 0) {
        $.get("getDiagnosisByGroup", {term: groupname}).done(function (data) {
            // Display the returned data in browser
            $("#diagnosiscat").empty().html(data);
        });
    }
});
$('#closesearchgroup').on('click', function () {
    $('#diagnogroup').val('');
    $.get("getInitialDiagnosisCategoryAjax", {term: ''}).done(function (data) {
        // Display the returned data in browser
        $("#diagnosiscat").html(data);
    });

});

// $('#deletealdiagno').on('click', function () {

//     $('#select-multiple-diagno').each(function () {
//         // alval = [];
//         var finalval = $(this).val().toString();
//         // alert(finalval);
//         var url = $('.delete_pat_findings').val();
//         // alert(finalval);
//         $.ajax({
//             url: url,
//             type: "POST",
//             dataType: "json",
//             data: {ids: finalval},
//             success: function (data) {
//                 // console.log(data);
//                 if ($.isEmptyObject(data.error)) {

//                     alert('Delete Diagnosis ?');
//                     location.reload();
//                 } else {
//                     showAlert('Something went wrong!!');
//                 }
//             }
//         });
//     });


// });

/**
 * Start Function for Diagnosis
 */


$('.alphabet').on('click', function () {
    $('input[name="alpha"]').bind('click', function () {
        $('input[name="alpha"]').not(this).prop("checked", false);
    });
    if (ckbox.is(':checked')) {

        $('#searchdrugs').val($('.alphabet').val());
        chkId = $(this).val() + ",";
        chkId = chkId.slice(0, -1);


        // alert(chkId);
        $("input[name='alpha']").attr('checked', false);
        $('#searchdrugs').val(chkId);

        var patientid = $('#patientID').val();

        if (chkId.length > 0) {
            $.get("searchDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
                // Display the returned data in browser
                $("#allergicdrugss").html(data);
            });
        } else {
            $.get("getAllDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
                // Display the returned data in browser
                $("#allergicdrugss").html(data);
            });
        }
    } else {
        $('#searchdrugs').val('');
        $.get("getAllDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
            // Display the returned data in browser
            $("#allergicdrugss").html(data);
        });
    }
});
$('.adonclose').on('click', function () {
    $('input[name="alpha"]').prop("checked", false);
    $('#searchdrugs').val('');
    var chkId = '';
    var patientid = $('#patientID').val();
    $.get("getAllDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
        // Display the returned data in browser
        $("#allergicdrugss").html(data);
    });
});


$("#searchdrugs").keyup(function () {
    var searchtext = $(this).val();
    // if()
    var patientid = $('#patientID').val();
    var resultDropdown = $(this).siblings("#allergicdrugss");
    // $('#allergicdrugss').hide();
    if (searchtext.length > 0) {
        $.get("searchDrugs", {term: searchtext, patient_id: patientid}).done(function (data) {
            // Display the returned data in browser
            $('#allergicdrugss').html(data);
        });
    } else {
        $.get("getAllDrugs", {term: searchtext, patient_id: patientid}).done(function (data) {
            // Display the returned data in browser
            $('#allergicdrugss').html(data);
        });
    }
});
