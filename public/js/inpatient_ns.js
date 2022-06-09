// To list Patient Histroy
$("#patientHistory").on("change", function (e) {
    if ($("#encounter_id").val() == "") {
        alert("Please select encounter id.");
        return false;
    }
    var patentHistory = e.target.value;
    var fldencounterval = $("#fldencounterval").val();
    // ajax
    $.get("present/ajax-related-history?patentHistory=" + patentHistory + "&fldencounterval=" + fldencounterval, function (data) {


        if (data) {
            CKEDITOR.instances.history_detail.setData(data.flddetail);
            $(".history_fldid").val(data.fldid);
        } else {
            CKEDITOR.instances.history_detail.setData("");
            $(".history_fldid").val("");
        }


    });
});

$('#save_history_of_patient').on('click', function (e) {
    e.preventDefault();
    if ($('#encounter_id').val() == "") {
        alert('Please select encounter id.');
        return false;
    }
    var fldencounterval = $("#fldencounterval").val();
    var fldid = $(".history_fldid").val();
    var flditem = $(".history_flditem option:selected").val();
    var flddetail = CKEDITOR.instances.history_detail.getData();
    var flduserid = $(".history_flduserid").val();
    var fldcomp = $(".history_fldcomp").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        fldid: fldid,
        flditem: flditem,
        flddetail: flddetail,
        flduserid: flduserid,
        fldcomp: fldcomp
    };
    if (flditem == '' || flddetail == '') {
        alert('History Detail Is Empty');
        return false;
    } else if (flduserid == '' || fldcomp == '') {
        alert('User And Computer Name Not Found');
        return false;
    } else {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {


                $('.history_fldid').val(data.success.id);

                showAlert("Information saved");

            }
        });
    }
});


$('#searchbygroups').on('click', function () {
    // alert('searchbygroup');
    var groupname = $('#diagnogroup').val();
    // alert(groupname);
    if (groupname.length > 0) {
        $.get("inpatient/getDiagnosisByGroup", {term: groupname}).done(function (data) {
            // Display the returned data in browser
            $("#diagnosiscat").html(data);
        });
    }
});

$('#closesearchgroups').on('click', function () {
    $('#diagnogroup').val('');
    $.get("inpatient/getInitialDiagnosisCategoryAjaxs", {term: ''}).done(function (data) {
        // Display the returned data in browser
        $("#diagnosiscat").html(data);
    });
});

// var table = $('table.datatable').DataTable({
//     "paging": false
// });

$(document).on('click', '.dccat', function () {
    // alert('click +bhayo');
    $('input[name="dccat"]').bind('click', function () {
        $('input[name="dccat"]').not(this).prop("checked", false);
    });
    var diagnocode = $("input[name='dccat']");
    $('.code').val($(this).val());
    if (diagnocode.is(':checked')) {
        diagnocode = $(this).val() + ",";
        diagnocode = diagnocode.slice(0, -1);
        $("input[name='dccat']").attr('checked', false);
        if (diagnocode.length > 0) {
            // alert(diagnocode);
            $.get("inpatient/getDiagnosisByCodes", {term: diagnocode}).done(function (data) {
                // Display the returned data in browser
                $(".sublist").html(data);
            });
        }
    } else {
        $(".sublist").html('');
    }
});

$('.onclose').on('click', function () {
    $('input[name="dccat"]').prop("checked", false);
    $('.code').val('');
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
        // alert(value);
        $('.diagnosissubname').val(value);
    } else {
        $(".diagnosissubname").val('');
    }
});

var ckbox = $("input[name='alpha']");
var chkId = '';
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

        var patientid = $('#patient_id_submit').val();

        if (chkId.length > 0) {
            $.get("inpatient/searchDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
                // Display the returned data in browser
                $("#allergicdrugss").html(data);
            });
        } else {
            $.get("inpatient/getAllDrugs", {term: chkId, patient_id: patientid}).done(function (data) {
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

$("#searchdrugs").keyup(function () {
    var searchtext = $(this).val();
    // if()
    var patientid = $('#patient_id_submit').val();
    // var resultDropdown = $(this).siblings("#allergicdrugss");
    // var resultDropdown = $("#allergicdrugss").html(data);;
    // $('#allergicdrugss').hide();
    if (searchtext.length > 0) {
        $.get("inpatient/searchDrugs", {term: searchtext, patient_id: patientid}).done(function (data) {
            // Display the returned data in browser
            $("#allergicdrugss").html(data)
        });
    } else {
        $.get("inpatient/getAllDrugs", {term: searchtext, patient_id: patientid}).done(function (data) {
            // Display the returned data in browser
            $("#allergicdrugss").html(data)
        });
    }
});


// change form action obstetric


//insert complant
$("#inpatient_insert_complaints").click(function () {
    var flditem = $(".select-present option:selected").val();
    var duration = $(".duration").val();
    var duration_type = $(".duration_type option:selected").val();
    var fldreportquali = $(".fldreportquali option:selected").val();
    var fldencounterval = $("#fldencounterval").val();
    var flduserid = $("#flduserid").val();
    var fldcomp = $("#fldcomp").val();
    var url = $(this).attr("url");
    var formData = {
        fldencounterval: fldencounterval,
        flduserid: flduserid,
        fldcomp: fldcomp,
        flditem: flditem,
        duration: duration,
        duration_type: duration_type,
        fldreportquali: fldreportquali
    };
    console.log(formData);
    if($.isNumeric(duration) === true){

    if (flditem == '' || duration == '' || duration_type == '' || fldreportquali == '') {
        alert('Fill all the data');
    } else {

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    location.reload();
                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });
    }
}else{
    alert('Duration only numeric value allowed!!');
}
});

$(".delete_complaints").click(function () {
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
                        showAlert("Something went wrong!!");
                    }
                }
            });

        }
    } else {
        showAlert('Authorization with  ' + permit_user);
    }


});

$(document).ready(function () {
    $("#update_fldtime").datetimepicker({
        dateFormat: "yy-mm-dd",
    });
});

// Inset Notes
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
                getTodayNoteList(fldencounterval);
                $("#notes_field").val(null);
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

// Note
$(document).on("change", "#note_list_select", function () {
    $("#update_fldtime").val(null);
    // $("#notes_field").val(null);
    $(".note__fldreportquali").val(null);
});

// To list Notes
$(document).on("click", ".notes__table_list tr", function () {
    var value = $(this).find("input").val();
    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/notes/ajax-related-list?value=' + value + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        $.each(data, function (index, get_list_detail) {
            $('.note__fldreportquali').val('');
            $('#notes_field').val('');
            $('.notes_fldtime').val('');
            $('.note__field_id').val('');

            $('#notes_field').val(get_list_detail.flddetail);
            $('.note__fldreportquali').val(get_list_detail.fldreportquali);
            $('.notes_fldtime').val(get_list_detail.fldtime);
            $('.note__field_id').val(get_list_detail.fldid);
        });
    });
});

// To list Notes By Date
function onchangeDate(e) {
    var list_date = e.target.value;
    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/notes/ajax-date-list?list_date=' + list_date + '&fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.notes__table_list').empty();
        $.each(data, function (index, get_list_detail) {
            $('.notes__table_list').append('<tr><input type="hidden" name="my_input" value="' + get_list_detail.fldid + '" /><td>' + get_list_detail.flduptime + '</td><td>' + get_list_detail.flditem + '</td></tr>');
        });
    });
};

// Refere Notes
$("#js-list-notes").click(function () {
    var toggleData = $('#js-list-notes').attr('datatype') || 'all';

    var newVal = (toggleData === 'all') ? 'today' : 'all';
    $('#js-list-notes').attr('datatype', newVal);

    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/notes/ajax-list-all?fldencounterval=' + fldencounterval + '&date=' + toggleData, function (data) {
        $('.notes__table_list').empty();
        $.each(data, function (index, get_list_detail) {
            $('.notes__table_list').append('<tr><input type="hidden" name="my_input" value="' + get_list_detail.fldid + '" /><td>' + get_list_detail.flduptime + '</td><td>' + get_list_detail.flditem + '</td></tr>');
        });
    });
});

$('li.nav-item a[href="#notes"]').click(function () {
    var fldencounterval = $('#fldencounterval').val();
    getTodayNoteList(fldencounterval);
});

function getTodayNoteList(fldencounterval) {
    $.get('inpatient/notes/ajax-list-all?fldencounterval=' + fldencounterval + '&date=today', function (data) {
        $('.notes__table_list').empty();
        $.each(data, function (index, get_list_detail) {
            $('.notes__table_list').append('<tr><input type="hidden" name="my_input" value="' + get_list_detail.fldid + '" /><td>' + get_list_detail.flduptime + '</td><td>' + get_list_detail.flditem + '</td></tr>');
        });
    });
}

$("#notes__refer_patient").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var fldcomp = $('#fldcomp').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');
    var formData = {
        "fldencounterval": fldencounterval,
        "fldcomp": fldcomp,
    }
    // console.log(url);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "json",
        data: formData,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                showAlert('Refere Successfully');
                $('#refere__to').modal('toggle');
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

$("#routine_show_all").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var value = $('.js-routine-option-radio:checked').val() || '';
    // ajax
    $.get('inpatient/routine/ajax-list-all?fldencounterval=' + fldencounterval + '&value=' + value, function (data) {
        // succecc data
        $('.show_all_routine').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_routine').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" class="update_fldcurval change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$("#list_all_routine").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/routine/ajax-show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show_all_routine').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_routine').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" class="change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$(".show_all_routine").on('click', 'td', function () {
    var fldid = $(this).attr('rel');
    // ajax
    $.get('inpatient/routine/get_status?fldid=' + fldid, function (data) {
        // succecc data
        console.log(data);
        $('.modal_item').empty();
        $('.modal_id').empty();
        $('.modal_items').empty();
        $('.modal_ids').empty();
        $('.modal_days').empty();
        $.each(data, function (index, get_patdosing) {
            $('.modal_item').append(get_patdosing.flditem);
            $('.modal_id').val(get_patdosing.fldid);
            $('.modal_items').append(get_patdosing.flditem);
            $('.modal_ids').val(get_patdosing.fldid);
            $('.modal_days').val(get_patdosing.flddays);
        });
    });
});

$("#show_medicine_routine").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/routine/show-medicine-routine?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.medicine__list').empty();
        $.each(data, function (index, get_patdosing) {
            $('.medicine__list').append('<li rel="' + get_patdosing.fldid + '">' + get_patdosing.flditem + '</li>');
        });
    });
});

$(".medicine__list").on('click', 'li', function () {
    var fldid = $(this).attr('rel');
    // ajax
    $.get('inpatient/routine/show-medicine-details?fldid=' + fldid, function (data) {
        // succecc data
        console.log(data);
        $('.m-name').empty();
        $('.m-route').empty();
        $('.m-dose').empty();
        $.each(data, function (index, get_patdosing) {
            $('.m-name').append(get_patdosing.flditem);
            $('.m-route').append(get_patdosing.fldroute + " " + get_patdosing.flddose + " x 1");
            $('.m-dose').val(get_patdosing.flddose);
        });
    });
});


$('.js-routine-option-radio').on('change', function (e) {
    $("#routine_show_all").click();
});

// To list Routine By Date
function onchangeDateRoutine(e) {
    var list_date = e.target.value;
    var encounter_id = $('#encounter_id').val();
    // ajax
    $.get('inpatient/routine/ajax-date-list?list_date=' + list_date + '&fldencounterval=' + encounter_id, function (data) {
        // succecc data
        $('.show_all_routine').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_routine').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" class="change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
};

// Insert Routine Status
$(".save__fldcurval").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var fldcurval = $('.modal_curval option:selected').val();
    var fldid = $('.modal_id').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "fldcurval": fldcurval,
        "fldid": fldid,
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
                $('#update_fldcurval').modal('toggle');
                $('.change-status-td-' + fldid).empty();
                $.get('inpatient/routine/get-changed-status?fldid=' + fldid, function (data) {
                    $('.change-status-td-' + fldid).append(data.fldcurval);
                });
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

// Inset Routine Days
$(".save__flddays").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var flddays = $('.modal_days').val();
    var fldid = $('.modal_ids').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "flddays": flddays,
        "fldid": fldid,
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
                $('#update_flddays').modal('toggle');
                $('.change-day-td-' + fldid).empty();
                $.get('inpatient/routine/get-changed-day?fldid=' + fldid, function (data) {
                    $('.change-day-td-' + fldid).append(data.flddays);
                });
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

// stat
// To list Stat By Date
function onchangeDateStat(e) {
    var list_date = e.target.value;
    var encounter_id = $('#encounter_id').val();
    // ajax
    $.get('inpatient/stat/ajax-date-list?list_date=' + list_date + '&fldencounterval=' + encounter_id, function (data) {
        // succecc data
        $('.show_all_stat').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_stat').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval" class="change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
};

$("#stat_show_all").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var value = $('.js-stat-option-radio:checked').val();
    // ajax
    $.get('inpatient/stat/ajax-list-all?fldencounterval=' + fldencounterval + '&value=' + value, function (data) {
        // succecc data
        $('.show_all_stat').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_stat').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays_stat" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval_stat" class="update_fldcurval change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});



$('.js-stat-option-radio').on('change', function (e) {
    $("#stat_show_all").click();
});

$("#list_all_stat").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    // ajax
    $.get('inpatient/stat/ajax-show-all?fldencounterval=' + fldencounterval, function (data) {
        // succecc data
        console.log(data);
        $('.show_all_stat').empty();
        var num = 0;
        $.each(data, function (index, get_patdosing) {
            num++;
            $('.show_all_stat').append('<tr data-fldid="' + get_patdosing.fldid + '"><td>' + num + '</td><td>' + get_patdosing.fldstarttime + '</td><td>' + get_patdosing.fldroute + '</td><td>' + get_patdosing.flditem + '</td><td class^=dosing_change>' + get_patdosing.flddose + '</td><td>' + get_patdosing.fldfreq + '</td><td data-toggle="modal" data-target="#update_flddays_stat" style="cursor:pointer;" class="change-day-td-' + get_patdosing.fldid + '" rel="' + get_patdosing.fldid + '">' + get_patdosing.flddays + '</td><td data-toggle="modal" data-target="#update_fldcurval_stat" class="change-status-td-' + get_patdosing.fldid + '" style="cursor:pointer;" rel="' + get_patdosing.fldid + '">' + get_patdosing.fldcurval + '</td><td>' + get_patdosing.fldstatus + '</td></tr>');
        });
    });
});

$(".show_all_stat").on('click', 'td', function () {
    var fldid = $(this).attr('rel');
    // ajax
    $.get('inpatient/stat/get_status?fldid=' + fldid, function (data) {
        // succecc data
        console.log(data);
        $('.modal_item1').empty();
        $('.modal_id1').empty();
        $('.modal_items2').empty();
        $('.modal_ids2').empty();
        $('.modal_days2').empty();
        $.each(data, function (index, get_patdosing) {
            $('.modal_item1').append(get_patdosing.flditem);
            $('.modal_id1').val(get_patdosing.fldid);
            $('.modal_items2').append(get_patdosing.flditem);
            $('.modal_ids2').val(get_patdosing.fldid);
            $('.modal_days2').val(get_patdosing.flddays);
        });
    });
});
// Insert Stat Status
$(".save__fldcurval_stat").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var fldcurval = $('.modal_curval1 option:selected').val();
    var fldid = $('.modal_id1').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "fldcurval": fldcurval,
        "fldid": fldid,
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
                $('#update_fldcurval_stat').modal('toggle');
                $('.change-status-td-' + fldid).empty();
                $.get('inpatient/stat/get-changed-status?fldid=' + fldid, function (data) {
                    $('.change-status-td-' + fldid).append(data.fldcurval);
                });
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

// Inset Stat Days
$(".save__flddays_stat").click(function () {
    var fldencounterval = $('#fldencounterval').val();
    var flddays = $('.modal_days2').val();
    var fldid = $('.modal_ids2').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var url = $(this).attr('url');

    var formData = {
        "fldencounterval": fldencounterval,
        "flddays": flddays,
        "fldid": fldid,
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
                $('#update_flddays_stat').modal('toggle');
                $('.change-day-td-' + fldid).empty();
                $.get('inpatient/stat/get-changed-day?fldid=' + fldid, function (data) {
                    $('.change-day-td-' + fldid).append(data.flddays);
                });
                // location.reload();
            } else {
                showAlert('error');
            }
        }
    });
});

$(".clicked_edit_complaint").click(function () {
    current_user = $('.current_user').val();
    permit_user = $(this).attr('permit_user');
    if (current_user == permit_user) {
        var fldid = $(this).attr('rel');
        $('.complaintfldid').val(fldid);
        var old_complaint_detail = $(this).attr("old_complaint_detail");
        CKEDITOR.instances['editor_present'].setData(old_complaint_detail);
    } else {
        showAlert('Authorization with  ' + permit_user);
        return false;
    }
});

$(".clicked_edit_complaint_duration").click(function () {
    current_user = $('.current_user').val();
    permit_user = $(this).attr('permit_user');
    if (current_user == permit_user) {
        var fldid = $(this).attr('rel');
        $('#insert_complaint_diration_inpatient').attr('rel', fldid);
    } else {
        showAlert('Authorization with  ' + permit_user);
        return false;
    }
});

$(".clicked_edit_complaint_side").click(function () {
    current_user = $('.current_user').val();
    permit_user = $(this).attr('permit_user');
    var reportquali = $(this).attr('rel1');
    if (current_user == permit_user) {
        var fldid = $(this).attr('rel');
        $('#insert_complaint_side_inpatient').attr('rel', fldid);
        $('option:selected', 'select[name="fldreportquali_side"]').removeAttr('selected');
        $('select[name="fldreportquali_side"]').find('option[value="' + reportquali + '"]').attr("selected", true);
    } else {
        showAlert('Authorization with  ' + permit_user);
        return false;
    }
});

$(document).on("click", "#insert_complaint_diration_inpatient", function () {
    var value = $("#get_complaint_duration_inpatient_value").val();
    var type = $("#get_complaint_duration_inpatient_type").val();
    var fldid = $(this).attr('rel');
    var url = $(this).attr('url');
    formData = {
        value: value,
        type: type,
        fldid: fldid
    }

    if (value == 0) {
        alert('0 is not accepted');
        return false;
    }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                location.reload();
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(document).on("click", "#insert_complaint_side_inpatient", function () {
    var side = $("#get_complaint_side").val();
    var fldid = $(this).attr('rel');
    var url = $(this).attr('url');
    formData = {
        side: side,
        fldid: fldid
    }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                location.reload();
            } else {
                showAlert(data.message);
            }
        }
    });
});

$(document).on("click", "#cause_detail_store", function () {
    var fldencounterval = $("#fldencounterval").val();
    var fldcomp = $("#flduserid").val();
    var flduserid = $("#fldcomp").val();
    var fldid = $("#c_o_a_fldid").val();
    var flddetail = $("#details_of_patient").val();
    var url = $(this).attr('url');
    formData = {
        fldencounterval: fldencounterval,
        fldcomp: fldcomp,
        flduserid: flduserid,
        fldid: fldid,
        flddetail: flddetail
    }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                $('#get_detail_after_process').empty();
                $("#c_o_a_fldid").val();
                $.get('present/postCauseDetail?fldencounterval=' + fldencounterval, function (data) {
                    var html = '';
                    html += '<input type="text" name="flddetail" class="form-control" id="details_of_patient" value="' + data.flddetail + '">';
                    $('#get_detail_after_process').append(html);
                    $("#c_o_a_fldid").val(data.fldid);
                });
            } else {
                showAlert(data.message);
            }
        }
    });
});
