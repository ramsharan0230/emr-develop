function getPatientDetailBySearch(url, search_type, patientId) {
    if (patientId != '') {
        // var search_type = $('[name="search_type"]:checked').val();
        $.ajax({
            method: "GET",
            url: url,
            data: {search_type: search_type},
        }).done(function (data) {
            if (data) {
                $('#patientListBody').empty().append(data);
            }
        });
    }
}

function getPatientDetailByPatientId(id) {

    if (id != null || id === 0 || id === '0') {
        var patientId = id;
    } else {
        patientId = $('#patta_no').val() || '';
    }

    if (patientId != '' || patientId === 0) {
        $.ajax({
            method: "GET",
            url: baseUrl + '/behalf/getDetails/' + patientId,
        }).done(function (data) {

            if (data) {
                var family_detail = data.family_detail;
                data = data.data;
                $('#save').prop("disabled", true);
                $('#update').prop("disabled", false);
                $('#delete').prop("disabled", false);
                $('#edit_discount').prop("disabled", false);
                $('#clear').prop("disabled", false);
                var dob = data.fldptbirday ? data.fldptbirday : '';
                var nepaliDateConverter = new NepaliDateConverter();
                if (dob != '') {
                    var detail = getAgeDetail(dob);
                    if (!isNaN(detail.age) && typeof detail.age != undefined) {
                        $('#age').val(detail.age);
                    }
                    dob = dob.split(' ')[0].split('-');
                    dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    dob = nepaliDateConverter.ad2bs(dob);
                }

                if (data.fldjoindate != '' && data.fldjoindate != null) {
                    var join_date = data.fldjoindate ? data.fldjoindate.split(' ')[0].split('-') : '';
                    join_date = join_date[1] + '/' + join_date[2] + '/' + join_date[0];
                    join_date = nepaliDateConverter.ad2bs(join_date);
                    $('#join_date').val(join_date);
                }


                $('#patta_no').val(data.fldptcode);
                $('#opdNo').val(data.fldopdno);
                $('#first_name').val(data.fldptnamefir);
                $('#middle_name').val(data.fldmidname ? data.fldmidname : '');
                $('#last_name').val(data.fldptnamelast ? data.fldptnamelast : '');
                $('#email').val(data.fldemail ? data.fldemail : '');
                $('#patient_dob').val(dob);
                $('#patient_status').val(data.fldpost);
                $('#house_no').val(data.fldhouseno ? data.fldhouseno : '');
                $('#tel_no').val(data.fldptcontact ? data.fldptcontact : '');
                $('#ward').val(data.fldward ? data.fldward : '');
                // $('#district').val(data.fldptadddist ? data.fldptadddist : '');
                $('#citizen').val(data.fldcitizen ? data.fldcitizen : '');
                $('#status').val(data.fldstatus ? data.fldstatus : '');
                $('#remarks').val(data.fldremark ? data.fldremark : '');
                $('#opd_no').val(data.fldopdno).prop('readOnly', true);
                // $('#registration_service').val('');
                var district = '';
                if (data.fldptadddist != null && data.fldptadddist != null) {
                    district += "<option value=" + data.fldptadddist + ">" + data.fldptadddist + "</option>";
                    $('#district').empty().append(district);
                } else {
                    district += "<option value=''>Not availlabl</option>";
                    $('#rank').empty().append(district);

                }
                var rank = '';
                var unit = '';
                if (data.fldrank != null && data.fldunit != null) {
                    rank += "<option value=" + data.fldrank + ">" + data.fldrank + "</option>";
                    unit += "<option value=" + data.fldunit + ">" + data.fldunit + "</option>";
                    $('#rank').empty().append(rank);
                    $('#unit').empty().append(unit);
                } else {
                    unit += "<option value=''>Not availlabl</option>";
                    rank += "<option value=''>Not availlable</option>";
                    $('#rank').empty().append(rank);
                    $('#unit').empty().append(unit);
                }

                if (data.fldpost != '' && data.fldpost != null) {
                    var ptstatus = "<option value=" + data.fldpost + ">" + data.fldpost + "</option>";
                    $('#patient_status').empty().append(ptstatus);
                }

                if (data.fldzone != '' && data.fldzone != null) {
                    var zone = "<option value=" + data.fldzone + ">" + data.fldzone + "</option>";
                    $('#zone').empty().append(zone);
                }

                if (data.fldptaddvill != '' && data.fldptaddvill != null) {
                    var village = "<option value=" + data.fldptaddvill + ">" + data.fldptaddvill + "</option>";
                    $('#municipal').empty().append(village);
                }

                if (data.patient_info != '' && data.patient_info != null) {
                    $('#patientNo').val(data.patient_info.fldpatientval ? data.patient_info.fldpatientval : '')
                }

                if (data.patient_info != '' && data.patient_info != null && data.patient_info.flddiscount != null) {
                    $('#discount option').attr('selected', false);
                    $('#discount option[value="' + data.patient_info.flddiscount + '"]').attr('selected', true);
                }

                $('#gender option').attr('selected', false);
                $('#gender option[value="' + data.fldptsex + '"]').attr('selected', true);

                $('#service option').attr('selected', false);
                $('#service option[value="' + data.flddept + '"]').attr('selected', true);

                $('#post_status option').attr('selected', false);
                $('#post_status option[value="' + data.fldpost + '"]').attr('selected', true);

                //plot family details
                // var familyHtml = '';
                if (typeof family_detail != undefined && family_detail != null && family_detail != '') {
                    $('#patientFamily').empty().append(family_detail);
                } else {
                    $('#patientFamily').empty().append('<tr><td align="center" colspan="8"> No data available</td></tr>');
                }
            }
            if (data.error) {
                showAlert(data.error, 'error');
            }
        });
    }
}

$(document).on('click', '#patientListBody tr', function () {
    selected_td('#patientListBody tr', this);
});

$('#patta_no').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        getPatientDetailByPatientId();
    }
});

$('#code').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#sur_name').val('');
        $('#name').val('');
        $('#surname_radio').prop('checked', false);
        $('#name_radio').prop('checked', false);
        $('#code_radio').prop('checked', true);
        var patientId = $(this).val();
        var search_type = 'code';
        var url = baseUrl + '/behalf/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});
$('#name').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#sur_name').val('');
        $('#code').val('');
        $('#surname_radio').prop('checked', false);
        $('#name_radio').prop('checked', true);
        $('#code_radio').prop('checked', false);
        var patientId = $(this).val();
        var search_type = 'name';
        var url = baseUrl + '/behalf/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});
$('#sur_name').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#name').val('');
        $('#code').val('');
        $('#surname_radio').prop('checked', true);
        $('#name_radio').prop('checked', false);
        $('#code_radio').prop('checked', false);
        var patientId = $(this).val();
        var search_type = 'sur_name';
        var url = baseUrl + '/behalf/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});

$(document).on('click', '.patientTr', function () {
    var code = $(this).data('code');
    if (code != '' || code != null) {
        $('#save').prop("disabled", true);
        getPatientDetailByPatientId(code)
    } else {
        showAlert('Something went wrong', 'error');
    }
});

$('#new').click(function () {
    $('#save').prop("disabled", false);
    clear();
});

function clear() {
    $('#patta_no').val('');
    $('#first_name').val('');
    $('#middle_name').val('');
    $('#last_name').val('');
    $('#age').val('');
    $('#patient_dob').val('');
    $('#gender').val('');
    $('#contact').val('');
    $('#address').val('');
    $('#district').val('');
    $('#citizen').val('');
    $('#status').val('');
    $('#ward').val('');
    $('#join_date').val('');
    $('#tel_no').val('');
    $('#marks').val('');
    $('#remarks').val('');
    $('#patient_status').val('');
    $('#unit').val('');
    $('#rank').val('');
}

$(document).on('submit', '#stafform', function () {
    var dob = $('#patient_dob').val();
    var joinDate = $('#join_date').val();
    if (dob != '' && dob != null) {
        dob = BS2AD(dob);
        $('#patient_dob').val(dob);
    }

    if (joinDate != '' && joinDate != null) {
        joinDate = BS2AD(joinDate);
        $('#join_date').val(joinDate);
    }

});

function getAgeDetail(dob) {
    var d1 = new Date();
    var d2 = new Date(dob);
    diff = new Date(d1.getFullYear() - d2.getFullYear(), d1.getMonth() - d2.getMonth(), d1.getDate() - d2.getDate());

    return {
        age: diff.getYear(),
        month: diff.getMonth(),
        day: diff.getDate()
    }
}

$('#update').click(function () {
    var type = $('input[name="update_status"]:checked').val();
    var patta_no = $('#patta_no').val();

    if (typeof patta_no === undefined || patta_no === null || patta_no === '') {

        showAlert('Problem with Patta No.', 'error');
        return false;
    }

    if (typeof type != undefined && type != null && type != '') {

        $.ajax({
            method: "POST",
            url: baseUrl + '/behalf/updateStatus/' + patta_no,
            data: {type: type},
        }).done(function (data) {
            if (type === 'upadan') {
                $("#patientFamily").removeClass("bg-purple");
                $("#patientFamily").removeClass("bg-danger");
                $("#patientFamily").addClass("bg-warning");
            }
            if (type === 'barkashi') {
                $("#patientFamily").removeClass("bg-warning");
                $("#patientFamily").removeClass("bg-danger");
                $("#patientFamily").addClass("bg-purple");
            }

            if (type === 'block') {
                $("#patientFamily").removeClass("bg-warning");
                $("#patientFamily").removeClass("bg-purple");
                $("#patientFamily").addClass("bg-danger");
            }
            if (data.error) {
                showAlert(data.error, 'error');
            }
            showAlert('Updated Successfully');
        });

    } else {

        showAlert('Please select type', 'error');
        return false;
    }


});

//update patient number

$('#updatePattaBtn').click(function () {

    var patta_no = $('#patta_no').val();
    var newPattaNo = $('#updatePattaInput').val();
    if (typeof patta_no === undefined || patta_no === null || patta_no === '') {

        showAlert('Please check Patta no.', 'error');
        return false;
    }
    if (typeof newPattaNo != undefined && newPattaNo != null && newPattaNo != '') {

        $.ajax({
            method: "POST",
            url: baseUrl + '/behalf/updatePatta/' + patta_no,
            data: {newpatta_no: newPattaNo},
        }).done(function (data) {
            console.log(data)
            return false;
            if (data.error) {
                showAlert(data.error, 'error');
            }
            showAlert('Updated Successfully');
        });

    } else {
        showAlert('Please enter new Patta No', 'error');
    }


});


