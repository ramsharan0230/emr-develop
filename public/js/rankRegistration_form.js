function getPatientDetailByPatientId() {
    var patientId = $('#patient_no').val() || '';
    if (patientId != '') {
        var registration_type = $('[name="registration_type"]:checked').val() || 'other';
        $.ajax({
            method: "GET",
            url: baseUrl + '/rank/patient-details/' + patientId,
            data: {registration_type: registration_type},
            dataType: "json",
        }).done(function (data) {
            // console.log(data)
            if (data) {
                var dob = data.fldptbirday ? data.fldptbirday : '';
                var nepaliDateConverter = new NepaliDateConverter();
                if (dob != '') {
                    var detail = getAgeDetail(dob);
                    $('#registration-age').val(detail.age);
                    $('#registration-month').val(detail.month);
                    $('#registration-day').val(detail.day);

                    dob = dob.split(' ')[0].split('-');
                    dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    dob = nepaliDateConverter.ad2bs(dob);
                }
                $('#registration-title').val(data.fldtitle ? data.fldtitle : '');
                $('#registration-first-name').val(data.fldptnamefir);
                $('#registration-middle-name').val(data.fldmidname ? data.fldmidname : '');
                $('#registration-guardian').val(data.fldptguardian ? data.fldptguardian : '');
                $('#registration-tole').val(data.fldptaddvill ? data.fldptaddvill : '');
                $('#registration-email').val(data.fldemail ? data.fldemail : '');
                $('#registration-pan-number').val(data.fldpannumber ? data.fldpannumber : '');
                $('#registration-claim-code').val(data.fldclaimcode ? data.fldclaimcode : '');
                $('#registration-national-id').val(data.fldnationalid ? data.fldnationalid : '');
                $('#registration-nhsi-no').val(data.fldnhsiid ? data.fldnhsiid : '');
                $('#registration-contact-number').val(data.fldptcontact ? data.fldptcontact : '');
                $('#registration-wardno').val(data.fldward ? data.fldward : (data.fldwardno ? data.fldwardno :'' ));

                $('#registration-citizenship-no').val((data.fldcitizen ? data.fldcitizen : (data.fldcitizenshipno ? data.fldcitizenshipno :'')));
                $('#registration-dob').val(dob);
                $('#opd_no').val(data.fldopdno).prop('readOnly',true);
                // $('#registration_service').val('');
                var rank ='';
                var unit ='';
                if(data.fldrank!=null &&  data.fldunit!=null){
                     rank +="<option value="+data.fldrank+">"+data.fldrank+"</option>";
                     unit +="<option value="+data.fldunit+">"+data.fldunit+"</option>";
                    $('#registration-rank').empty().append(rank);
                    $('#registration-unit').empty().append(unit);
                }else {
                    unit +="<option value=''>Not availlabl</option>";
                    rank +="<option value=''>Not availlable</option>";
                    $('#registration-rank').empty().append(rank);
                    $('#registration-unit').empty().append(unit);
                }



                $('#registration-discount').val(data.flddiscount);
                if (data.latest_image)
                    $('#profile').attr('src', data.latest_image.fldpic);
                else
                    $('#profile').attr('src', baseUrl + '/assets/images/dummy-img.jpg');

                if (data.latest_encounter && data.latest_encounter.fldregdate && data.latest_encounter.fldregdate != null) {
                    var fldregdate = data.latest_encounter.fldregdate.split(' ')[0].split('-');
                    fldregdate = fldregdate[1] + '/' + fldregdate[2] + '/' + fldregdate[0];
                    fldregdate = nepaliDateConverter.ad2bs(fldregdate);
                    $('#registration-last-visit').val(fldregdate);
                }
                if (data.latest_encounter && data.latest_encounter.fldfollowdate && data.latest_encounter.fldfollowdate != null) {
                    var fldfollowdate = data.latest_encounter.fldfollowdate.split(' ')[0].split('-');
                    fldfollowdate = fldfollowdate[1] + '/' + fldfollowdate[2] + '/' + fldfollowdate[0];
                    fldfollowdate = nepaliDateConverter.ad2bs(fldfollowdate);
                    $('#registration-followup-date').val(fldfollowdate);
                }


                $('#registration-gender option').attr('selected', false);
                $('#registration-gender option[value="' + data.fldptsex + '"]').attr('selected', true);
                $('#registration-relation option').attr('selected', false);
                $('#registration-relation option[value="' + data.fldrelation + '"]').attr('selected', true);
                $('#registration-ethnic-group option').attr('selected', false);
                $('#registration-ethnic-group option[value="' + data.fldethnicgroup + '"]').attr('selected', true);
                $('#registration-blood-group option').attr('selected', false);
                $('#registration-blood-group option[value="' + data.fldbloodgroup + '"]').attr('selected', true);

                //date time
                var datime = (data.fldtime) ? data.fldtime.split(' ')[0].split('-') : '';
                datime = datime[1] + '/' + datime[2] + '/' + datime[0];
                datime = nepaliDateConverter.ad2bs(datime);
                $('#registration-date-time').val(datime);


                var selectedLastName = $('#registration-last-name option[value="' + data.fldptnamelast + '"]');
                if (data.fldptnamelast && selectedLastName.length > 0) {
                    $('#registration-last-name option').attr('selected', false);
                    $(selectedLastName).attr('selected', true);
                    $('.select2-selection__rendered').attr('title', data.fldptnamelast);
                    $('.select2-selection__rendered').text(data.fldptnamelast);
                }
                // address
                var defaultCountry = 'Nepal';
                var fldprovince = data.municipality && data.municipality.fldprovince ? data.municipality.fldprovince : null;
                $('#registration-country option[value="' + defaultCountry + '"]').attr('selected', true);
                getProvinces(defaultCountry, fldprovince);

                setTimeout(function () {
                    if(data.municipality && data.municipality.fldprovince && data.municipality.flddistrict){
                        getDistrict(data.municipality.fldprovince, data.municipality.flddistrict);
                        setTimeout(function () {
                            getMunicipality(data.municipality.flddistrict, data.municipality.fldpality);
                        }, 500);
                    }

                }, 500);
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



var provinceSelector = 'registration-province';
var districtSelector = 'registration-district';
var municipalityVdcSelector = 'registration-municipality';
var selectOption = $('<option>', {val: 0, text: '--Select--'});

function getProvinces(id, provinceId) {
    if (id === "" || id === null) {
        $('#' + provinceSelector).empty().append(selectOption.clone());
        $('#' + districtSelector).empty().append(selectOption.clone());
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
    } else {
        var elems = $.map(addresses, function(d) {
            if (d.fldprovince == provinceId)
                districts = d.districts;

            return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId) });
        });

        $('#' + provinceSelector).empty().append(selectOption.clone()).append(elems);
        $('#' + districtSelector).empty().append(selectOption.clone());
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
    }
}

function getDistrict(id, districtId) {
    if (id === "" || id === null) {
        $('#' + districtSelector).empty().append(selectOption.clone());
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
    } else {
        $.map(addresses, function(d) {
            if (d.fldprovince == id) {
                districts = d.districts;
                return false;
            }
        });

        var elems = $.map(districts, function(d) {
            return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId) });
        });

        $('#' + districtSelector).empty().append(selectOption.clone()).append(elems);
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
    }
}

function getMunicipality(id, municipalityId) {
    if (id === "" || id === null) {
        $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
    } else {
        $.map(districts, function(d) {
            if (d.flddistrict == id) {
                municipalities = d.municipalities;
                return false;
            }
        });

        var elems = $.map(municipalities, function(d) {
            return $('<option>', {val: d, text: d, selected: (d == municipalityId) });
        });

        $('#' + municipalityVdcSelector).empty().append(selectOption.clone()).append(elems);
    }
}


$('#registration-country').change(function () {
    getProvinces($(this).val(), null);
});

$('#registration-province').change(function () {
    getDistrict($(this).val(), null);
});

$('#registration-district').change(function () {
    getMunicipality($(this).val(), null);
});



$('#registration-refresh').click(function (e) {
    getPatientDetailByPatientId();
});


$('#registration-department,#registration-discount-scheme,#registration-is-follow-up').change(function () {
    var department = $('#registration-department').val() || '';
    // var type = $('#js-registration-is-follow-up').prop('checked') ? 'Followup' : 'General';
    var type = $('#registration-billing-mode option:selected').val();
    var is_follow = $('#registration-is-follow-up').prop('checked') ? 'Followup' : 'General';

    if (department !== '') {
        $.ajax({
            method: "GET",
            data: {department: department, type: type, is_follow: is_follow},
            dataType: "json",
            url: baseUrl + '/rank/getRegistrationCost',
        }).done(function (amount) { // intial Price bhayo
            var discount = $('#registration-discount-scheme option:selected') || '';
            if ($(discount).val() != '') {
                var discountMode = $(discount).data('fldmode');
                var dicountCalculated =0;  // discount bhayo
                var initalPrice = amount; // intial Price bhayo
                if (discountMode == 'Fixed Percent')
                    dicountCalculated = ((amount*$(discount).data('fldpercent'))/100);
                else
                    dicountCalculated = ($(discount).data('fldamount'));
                    amount=(initalPrice-dicountCalculated);
            }

            $('#registration-amount').val(amount);
            $('#finalAmount').html(amount);
            $('#price').html(initalPrice);
            $('#discount').html(dicountCalculated);
        });
    }
});

$('#registration-discount-scheme').change(function () {
    var discount = $('#registration-discount-scheme option:selected');
    $('#registration-flddiscper').val($(discount).data('fldpercent'));
    $('#registration-flddiscamt').val($(discount).data('fldamount'));
});

$('#registration-department').change(function () {
    var department = $('#registration-department').val() || '';
    $('#registration-consultant').empty().append(selectOption.clone());
    if (department !== '') {
        $.ajax({
            method: "GET",
            data: {department: department},
            dataType: "json",
            url: baseUrl + '/rank/getDepatrmentUser',
        }).done(function (data) {
            var elems = data.map(function (d) {
                return $('<option>', {val: d.firstname, text: d.firstname + '(NMC: ' + d.nmc + ')'});
            });
            $('#registration-consultant').append(elems);
        });
    }
});

function insurance_toggle() {
    var insurance_type = $('#registration-insurance-type').val() || '';
    if (insurance_type == '')
        $('.insurance-toggle').hide();
    else
        $('.insurance-toggle').show();
}

insurance_toggle();

$('#registration-insurance-type').change(function () {
    insurance_toggle();
});

$(document).on('click', '#registration-table-modal tr', function () {
    selected_td('#registration-table-modal tr', this);
});

$(document).on('click', '#registration-add-surname', function (e) {
    var tr_data = '';
    $.each($('#registration-last-name option'), function (i, e) {
        var value = $(e).val();
        var id = $(e).data('id');
        if (value !== '')
            tr_data += '<tr data-flditem="' + id + '"><td>' + value + '</td></tr>';
    });

    $('#registration-table-modal').html(tr_data);
    $('#registration-add-item-modal').modal('show');
});

$(document).on('keyup', '.select2-search__field', function (e) {
    if ($(this).closest('.select2-dropdown').find('.select2-results ul').attr('id') == "select2-js-registration-last-name-results") {
        if (e.keyCode === 13) {
            var data = {
                flditem: $(this).val(),
            };
            $.ajax({
                url: baseUrl + '/rank/addSurname',
                type: "POST",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        var newOption = new Option(val.flditem, val.flditem, true, true);
                        $('#registration-last-name').append(newOption).trigger('change');
                        $('#registration-last-name').val(val.flditem).trigger('change');
                        $("#registration-last-name").select2("close");
                    }
                    showAlert(response.message);
                }
            });
        }
    }
});


$('#registration-add-btn-modal').click(function () {
    var data = {
        flditem: $('#registration-flditem-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/rank/addSurname',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;

                var trData = '<tr data-flditem="' + val.id + '"><td>' + val.flditem + '</td></tr>';
                $('#registration-table-modal').append(trData);
                $('#registration-flditem-input-modal').val('');
            }
            showAlert(response.message);
        }
    });
});

$('#registration-delete-btn-modal').click(function () {
    var data = {
        id: $('#registration-table-modal tr[is_selected="yes"]').data('flditem'),
    };
    $.ajax({
        url: baseUrl + '/rank/deleteSurname',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#registration-table-modal tr[is_selected="yes"]').remove();

            showAlert(response.message);
        }
    });
});

function refresh_registration_options() {
    $.ajax({
        url: baseUrl + '/rank/getSurname',
        type: "GET",
        success: function (response) {
            var surnames = '<option value="">-- Select --</option>';
            $.each(response, function (i, e) {
                surnames += '<option value="' + e.flditem + '" data-id="' + e.fldid + '">' + e.flditem + '</option>'
            });
            $('#registration-last-name').html(surnames);
        }
    });
}

$('#registration-add-item-modal').on('hidden.bs.modal', function () {
    $('#registration-flditem-input-modal').val('');
    $('#registration-type-input-modal').val('');
    $('#registration-table-modal').html('');

    refresh_registration_options();
});

$('#toggle-filter').click(function () {
    if (document.getElementById('registration-list-filter').style.display == 'none') {
        $('#registration-list-filter').show();
        $('#toggle-filter').text('Hide Filter');
    } else {
        $('#registration-list-filter').hide();
        $('#toggle-filter').text('Show Filter');
    }
});

$('.registration-list-edit').click(function () {
    $('#patient_no').val($(this).closest('tr').data('fldpatientval'));
    searchPatient();

    $('#patient-profile-modal').modal('show');
});

$('.registration-list-view').click(function () {
    var patientId = $(this).closest('tr').data('fldpatientval') || '';
    if (patientId != '') {
        $.ajax({
            method: "GET",
            url: baseUrl + 'rank/patient-details/' + patientId,
        }).done(function (data) {
            var dob = data.fldptbirday ? data.fldptbirday : '';
            var nepaliDateConverter = new NepaliDateConverter();
            if (dob != '') {
                var detail = getAgeDetail(dob);
                $('#js_view_years').text(detail.age);
                $('#js_view_month').text(detail.month);

                dob = dob.split(' ')[0].split('-');
                dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                dob = nepaliDateConverter.ad2bs(dob);
            }

            $('#js_view_dob').text(dob);
            $('#js_view_patient_no').text(patientId);
            $('#js_view_profile_encounter').text(data.latest_encounter.fldencounterval);
            $('#js_view_name').text(data.fldptnamefir);
            $('#js_view_surname').text(data.fldptnamelast);
            $('#js_view_guardian').text(data.fldptguardian);
            $('#js_view_address').text(data.fldptaddvill);
            $('#js_view_gender').text(data.fldptsex);
            $('#js_view_contact').text(data.fldptcontact);
            $('#js_view_comment').text(data.fldcomment);
            $('#js_view_district').text(data.fldptadddist);
            $('#js_view_email').text(data.fldemail);
            $('#js_view_relation').text(data.fldrelation);
            $('#js_view_code_pan').text();
        });
    }

    $('#patient-profile-view-modal').modal('show');
});

var currentdepartment = 'Consultation';
function getDepartments(department) {
    if (department != currentdepartment) {
        currentdepartment = department;
        $.ajax({
            url: baseUrl + '/rank/getDepartments',
            type: "GET",
            data: {department: department},
            dataType: "json",
            success: function (response) {
                var optionData = '<option value="">-- Select --</option>';
                $.each(response, function (i, option) {
                    optionData += '<option value="' + option.flddept + '">' + option.flddept + '</option>';
                });
                $('#registration-department').empty().html(optionData);
            }
        });
    }
}

// OPD no disabled

$('#familyRadio').click(function(){
    $('#opd_no').val('')
    $('#opd_no').prop('readOnly',true)
});

$('.radioType').click( function (){
    $('#opd_no').val('')
    $('#opd_no').prop('readOnly',false)
});

$(document).ready(function () {
    //suru ma country wise load gareko
    var defaultCountry = 'Nepal';
    $('#registration-country option[value="' + defaultCountry + '"]').attr('selected', true);
    getProvinces($('#js-registration-country'), null);

    // $('#saveBtn').prop("disabled", true);
    // $('#SaveandBillBtn').prop("disabled", true);
    // $('#patient_no').keyup(function () {
    //     if ($(this).val() != '') {
    //         $('#saveBtn').prop("disabled", false);
    //         $('#SaveandBillBtn').prop("disabled", false);
    //     } else {
    //         $('#saveBtn').prop("disabled", true);
    //         $('#SaveandBillBtn').prop("disabled", true);
    //     }
    // });
});



