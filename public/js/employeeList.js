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
    if(id !=null || id===0 || id==='0'){
        var patientId = id;
    }else {
         patientId = $('#patient_no').val() || '';
    }

    if (patientId != '' || patientId===0 ) {
        $.ajax({
            method: "GET",
            url: baseUrl + '/employee/getDetails/' + patientId,
        }).done(function (data) {
            if (data) {
                $('#save').prop("disabled", true);
                $('#update').prop("disabled", false);
                $('#delete').prop("disabled", false);
                $('#edit_discount').prop("disabled", false);
                $('#clear').prop("disabled", false);
                var dob = data.fldptbirday ? data.fldptbirday : '';
                var nepaliDateConverter = new NepaliDateConverter();
                if (dob != '') {
                    // var detail = getAgeDetail(dob);
                    // $('#registration-age').val(detail.age);
                    // $('#registration-month').val(detail.month);
                    // $('#registration-day').val(detail.day);

                    dob = dob.split(' ')[0].split('-');
                    dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    dob = nepaliDateConverter.ad2bs(dob);
                }

                if (data.fldjoindate != '' && data.fldjoindate != null ) {
                   var  join_date = data.fldjoindate ? data.fldjoindate.split(' ')[0].split('-') :'';
                    join_date = join_date[1] + '/' + join_date[2] + '/' + join_date[0];
                    join_date = nepaliDateConverter.ad2bs(join_date);
                    $('#join_date').val(join_date);
                }

                if (data.fldenddate != '' &&  data.fldenddate != null) {
                    var  end_date = data.fldenddate ? data.fldenddate.split(' ')[0].split('-') :'';
                    end_date = end_date[1] + '/' + end_date[2] + '/' + end_date[0];
                    end_date = nepaliDateConverter.ad2bs(end_date);
                    $('#end_date').val(end_date);
                }

                $('#patient_no').val(data.fldptcode);
                $('#opdNo').val(data.fldopdno);
                $('#first_name').val(data.fldptnamefir);
                $('#middle_name').val(data.fldmidname ? data.fldmidname : '');
                $('#last_name').val(data.fldptnamelast ? data.fldptnamelast : '');
                $('#email').val(data.fldemail ? data.fldemail : '');
                $('#patient_dob').val(dob);
                $('#patient_status').val(data.fldpost);
                // $('#gender').val(data.fldptsex ? data.fldptsex : '');
                $('#contact').val(data.fldptcontact ? data.fldptcontact : '');
                $('#address').val(data.fldptaddvill ? data.fldptaddvill : '');
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

                if(data.fldpost != '' && data.fldpost != null )
                {
                    var ptstatus  = "<option value=" + data.fldpost + ">" + data.fldpost + "</option>";
                    $('#patient_status').empty().append(ptstatus);
                }
                if(data.patient_info!='' && data.patient_info!=null )
                {
                    $('#patientNo').val(data.patient_info.fldpatientval ? data.patient_info.fldpatientval : '' )
                }

                if(data.patient_info!='' && data.patient_info!=null &&  data.patient_info.flddiscount!=null )
                {
                    $('#discount option').attr('selected', false);
                    $('#discount option[value="' + data.patient_info.flddiscount + '"]').attr('selected', true);
                }

                $('#patientType option').attr('selected', false);
                $('#patientType option[value="' + data.fldpatienttype + '"]').attr('selected', true);

                $('#gender option').attr('selected', false);
                $('#gender option[value="' + data.fldptsex + '"]').attr('selected', true);

                $('#status option').attr('selected', false);
                $('#status option[value="' + data.fldstatus + '"]').attr('selected', true);

                $('#service option').attr('selected', false);
                $('#service option[value="' + data.flddept + '"]').attr('selected', true);

            }
            if(data.error){
                showAlert(data.error,'error');
            }
        });
    }
}

$('#patient_no').keydown(function (e) {
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
        $('#surname_radio').prop('checked',false);
        $('#name_radio').prop('checked',false);
        $('#code_radio').prop('checked',true);
        var patientId = $(this).val();
        var search_type = 'code';
        var url = baseUrl + '/employee/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});
$('#name').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#sur_name').val('');
        $('#code').val('');
        $('#surname_radio').prop('checked',false);
        $('#name_radio').prop('checked',true);
        $('#code_radio').prop('checked',false);
        var patientId = $(this).val();
        var search_type = 'name';
        var url = baseUrl + '/employee/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});
$('#sur_name').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#name').val('');
        $('#code').val('');
        $('#surname_radio').prop('checked',true);
        $('#name_radio').prop('checked',false);
        $('#code_radio').prop('checked',false);
        var patientId = $(this).val();
        var search_type = 'sur_name';
        var url = baseUrl + '/employee/getDetails/' + patientId;
        getPatientDetailBySearch(url, search_type, patientId);
    }
});

$(document).on('click','.patientTr', function () {
    var code = $(this).data('code');
    if(code!='' || code!=null)
    {
        $('#save').prop("disabled", true);
        getPatientDetailByPatientId(code)
    }else {
        showAlert('Something went wrong','error');
    }
});

$(document).on('click', '#patientListBody tr', function () {
    selected_td('#patientListBody tr', this);
});

$('#delete').click( function () {
    var patientId = $('#patient_no').val();

    if(patientId!='' && patientId!=null)
    {
       var  url = baseUrl + '/employee/delete/' + patientId;

        $.ajax({
            method: "delete",
            url: url,
        }).done(function (data) {
            if (data) {
                showAlert(data);
            }
        });

    }else {
        showAlert('please enter patient no','error');
    }

});

$('#clear').click( function () {
   clear();
});
$('#new').click( function () {
    $('#save').prop("disabled", false);
    clear();
});

function clear() {
    $('#patient_no').val('');
    $('#first_name').val('');
    $('#middle_name').val('');
    $('#last_name').val('');
    $('#email').val('');
    $('#patient_dob').val('');
    $('#gender').val('');
    $('#contact').val('');
    $('#address').val('');
    $('#district').val('');
    $('#citizen').val('');
    $('#status').val('');
    $('#opdNo').val('');
    $('#join_date').val('');
    $('#end_date').val('');
    $('#marks').val('');
    $('#remarks').val('');
    $('#patient_status').val('');
    $('#unit').val('');
    $('#rank').val('');
}

$(document).on('submit','#stafform',function(){
    var dob = $('#patient_dob').val();
    var joinDate = $('#join_date').val();
    var endDate = $('#end_date').val();
    if(dob!='' && dob!=null)
    {
        dob = BS2AD(dob);
        $('#patient_dob').val(dob);
    }

    if(joinDate!='' && joinDate!=null)
    {
        joinDate = BS2AD(joinDate);
        $('#join_date').val(joinDate);
    }

    if(endDate!='' && endDate!=null)
    {
        endDate = BS2AD(endDate);
        $('#end_date').val(endDate);
    }
});

$(document).ready(function () {
    $('#save').prop("disabled", true);
    $('#update').prop("disabled", true);
    $('#delete').prop("disabled", true);
    $('#edit_discount').prop("disabled", true);
    $('#clear').prop("disabled", true);

    $('#patient_no').keyup(function () {
        if ($(this).val() != '') {
            // $('#save').prop("disabled", false);
            $('#update').prop("disabled", false);
            $('#delete').prop("disabled", false);
            $('#edit_discount').prop("disabled", false);
            $('#clear').prop("disabled", false);
        } else {
            // $('#save').prop("disabled", true);
            $('#update').prop("disabled", true);
            $('#delete').prop("disabled", true);
            $('#edit_discount').prop("disabled", true);
            $('#clear').prop("disabled", true);
        }
    });
});







