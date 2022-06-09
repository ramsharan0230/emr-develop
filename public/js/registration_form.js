$(function () {
    $('#js-registration-list-filter').hide();
    // $('#js-toggle-filter').text('Show Filter');
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

// var nepaliDateConverter = new NepaliDateFunction();

$("body").on("submit", "form#regsitrationForm,form#oldRegistrationForm", function () {
console.log('here');
    // $(this).submit(function () {
    //     return false;
    // });
    var activeForm = $('div.tab-pane.fade.active.show');
    var paymode = $(activeForm).find("input[name='payment_mode']:checked").val();
    var patienttype = $(activeForm).find(".js-registration-billing-mode").val();
    var healthinsurancetype = $(activeForm).find(".js-registration-insurance-type").val();

    // alert(paymode);
    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }
    if((patienttype.toLowerCase() == 'health insurance' || patienttype.toLowerCase() == 'healthinsurance' || patienttype.toLowerCase() == 'hi') && healthinsurancetype == ''){
        showAlert('Choose Health Insurance Type.','fail');
        return false;
    }
    return true;
});

$('.js-registration-oldpatientid-id').keydown(function (e) {
    var oldPatientId = $(this).val() || '';
    if (e.which == 13 && oldPatientId != '') {
        e.preventDefault();
        $.ajax({
            url: baseUrl + "/registrationform/getOldPatientDetail",
            type: "GET",
            data: {
                oldPatientId: oldPatientId,
            },
            dataType: "json",
            success: function (response) {
                if (response.type == 'registred') {
                    $('#old-patient-tab').trigger('click');
                }
                setTimeout(() => {
                    var activeForm = $('div.tab-pane.fade.active.show');
                    var data = response.patientInfo;
                    $('form#oldRegistrationForm')[0].reset();
                    setTimeout(() => {
                        $('form#oldRegistrationForm .payment_mode').val('Cash');
                    }, 500);
                    $(activeForm).find('#select2-js-registration-billing-mode-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-billing-mode-old-container').text('--Select--');
                    $(activeForm).find('#select2-js-registration-last-name-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-last-name-old-container').text('--Select--');
                    $(activeForm).find('#select2-js-registration-ethnic-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-ethnic-old-container').text('--Select--');

                    plotPatientData(data);
                }, 500);
            }
        });
    }
});

$('.js-registration-booking-id').keydown(function (e) {
    var appointmentNo = $(this).val() || '';
    if (e.which == 13 && appointmentNo != '') {
        e.preventDefault();
        $.ajax({
            url: baseUrl + "/registrationform/getOldPatientDetail",
            type: "GET",
            data: {
                appointmentNo: appointmentNo,
            },
            dataType: "json",
            success: function (response) {
                if (response.type == 'registred') {
                    $('#old-patient-tab').trigger('click');
                }
                setTimeout(() => {
                    var activeForm = $('div.tab-pane.fade.active.show');
                    var data = response.patientInfo;

                    $('form#oldRegistrationForm')[0].reset();
                    $('form#oldRegistrationForm select').val('');
                    $(activeForm).find('#select2-js-registration-billing-mode-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-billing-mode-old-container').text('--Select--');
                    $(activeForm).find('#select2-js-registration-last-name-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-last-name-old-container').text('--Select--');
                    $(activeForm).find('#select2-js-registration-ethnic-old-container').attr('title', '--Select--');
                    $(activeForm).find('#select2-js-registration-ethnic-old-container').text('--Select--');

                    plotPatientData(data);
                }, 500);
            }
        });
    }
});


$(".unauthorised").click(function () {
    permit_user = $(this).attr('permit_user');
    showAlert('Authorization with  ' + permit_user);
});

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("hover");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}

var patientOldDepartments = [];

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(input).closest('div').prev('div').find('img.img-info').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

var provinceSelector = 'js-registration-province';
var districtSelector = 'js-registration-district';
var municipalityVdcSelector = 'js-registration-municipality';
var selectOption = $('<option>', {val: '', text: '--Select--'});

var districts = null;
var municipalities = null;

function getProvinces(id, provinceId) {
    var activeForm = $('div.tab-pane.fade.active.show');
    $(activeForm).find('.' + provinceSelector).empty().append(selectOption.clone());
    $(activeForm).find('.' + districtSelector).empty().append(selectOption.clone());
    $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone());

    if (id == 'Other') {
        $(activeForm).find('.' + provinceSelector).removeAttr('required');
        $(activeForm).find('.' + provinceSelector).closest('div.form-group').find('span.text-danger').text('');
        $(activeForm).find('.' + districtSelector).removeAttr('required');
        $(activeForm).find('.' + districtSelector).closest('div.form-group').find('span.text-danger').text('');
        $(activeForm).find('.' + municipalityVdcSelector).removeAttr('required');
        $(activeForm).find('.' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('');
        return false;
    } else {
        $(activeForm).find('.' + provinceSelector).attr('required', true);
        $(activeForm).find('.' + provinceSelector).closest('div.form-group').find('span.text-danger').text('*');
        $(activeForm).find('.' + districtSelector).attr('required', true);
        $(activeForm).find('.' + districtSelector).closest('div.form-group').find('span.text-danger').text('*');
        $(activeForm).find('.' + municipalityVdcSelector).attr('required', true);
        $(activeForm).find('.' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('*');
    }

    if (id === "" || id === null) {
    } else {
        var elems = $.map(addresses, function (d) {
            if (d.fldprovince == provinceId)
                districts = d.districts;

            return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId)});
        });

        $(activeForm).find('.' + provinceSelector).empty().append(selectOption.clone()).append(elems);
        $(activeForm).find('.' + districtSelector).empty().append(selectOption.clone());
        $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone());
    }

    if (id == "NEPAL") {
        var elems = $.map(initdistricts, function (d) {
            return $("<option value='" + d.flddistrict + "' data-fldprovince='" + d.fldprovince + "'>" + d.flddistrict + "</option>")
        });
        $(activeForm).find('.' + districtSelector).empty().append(selectOption.clone()).append(elems);
    }
}

function getDistrict(id, districtId) {
    var activeForm = $('div.tab-pane.fade.active.show');
    if (id === "" || id === null) {
        $(activeForm).find('.' + districtSelector).empty().append(selectOption.clone());
        $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone());
    } else {
        $.map(addresses, function (d) {
            if (d.fldprovince == id) {
                districts = d.districts;
                return false;
            }
        });
        districts = Object.keys(districts).sort().reduce(
            (obj, key) => {
                obj[key] = districts[key];
                return obj;
            },
            {}
        );
        var elems = $.map(districts, function (d) {
            return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId)});
        });

        $(activeForm).find('.' + districtSelector).empty().append(selectOption.clone()).append(elems);
        $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone());
    }
}

function ucwords(str) {
    str = str.toLowerCase();
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function getMunicipality(id, municipalityId) {
    var activeForm = $('div.tab-pane.fade.active.show');
    if (id === "" || id === null) {
        $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone());
    } else {
        var dataFldprovince = $(activeForm).find('.' + districtSelector).find('option:selected').data('fldprovince');
        if (dataFldprovince) {
            $(activeForm).find('.' + provinceSelector).val(dataFldprovince);
            $(activeForm).find('#select2-js-new-province-container').attr('title', dataFldprovince);
            $(activeForm).find('#select2-js-new-province-container').text(dataFldprovince);
            $(activeForm).find('#select2-js-old-province-container').attr('title', dataFldprovince);
            $(activeForm).find('#select2-js-old-province-container').text(dataFldprovince);

            $.map(addresses, function (d) {
                if (d.fldprovince.toLowerCase() == dataFldprovince.toLowerCase()) {
                    districts = d.districts;
                    return false;
                }
            });
        }

        if (districts == null) {
            districts = initdistricts;
            var valueId = ucwords(id);
            $(activeForm).find('.js-registration-district option[value="' + valueId + '"]').prop('selected', true);
            $(activeForm).find('.js-registration-district').trigger('change');
            return false;
        }

        $.map(districts, function (d) {
            if (d.flddistrict.toLowerCase() == id.toLowerCase()) {
                municipalities = d.municipalities;
                return false;
            }
        });

        municipalities = municipalities.sort();
        var elems = $.map(municipalities, function (d) {
            return $('<option>', {val: d, text: d, selected: (d == municipalityId)});
        });

        $(activeForm).find('.' + municipalityVdcSelector).empty().append(selectOption.clone()).append(elems);
    }
}

$('.js-registration-country').change(function () {
    var id = $(this).val();
    getProvinces(id, null);
});

$('.js-registration-province').change(function () {
    getDistrict($(this).val(), null);
});

$('.js-registration-district').change(function () {
    getMunicipality($(this).val(), null);
});

function getPatientDetailByPatientId() {
    var patientId = $('#js-registration-patient-no').val() || '';
    var activeForm = $('div.tab-pane.fade.active.show');
    var department = $(activeForm).find('input[type="radio"][name="department_seperate_num"]:checked').val() || '';

    $('form#oldRegistrationForm select').val('');
    $('form#oldRegistrationForm')[0].reset();

    $(activeForm).find('#select2-js-registration-billing-mode-old-container').attr('title', '--Select--');
    $(activeForm).find('#select2-js-registration-billing-mode-old-container').text('--Select--');
    $(activeForm).find('#select2-js-registration-last-name-old-container').attr('title', '--Select--');
    $(activeForm).find('#select2-js-registration-last-name-old-container').text('--Select--');
    $(activeForm).find('#select2-js-registration-ethnic-old-container').attr('title', '--Select--');
    $(activeForm).find('#select2-js-registration-ethnic-old-container').text('--Select--');
    // $('#js-registration-patient-no').val(patientId);
    $(activeForm).find('input[type="radio"][name="department_seperate_num"][value="' + department + '"]').attr('checked', true);

    if (patientId != '') {
        var registration_type = $(activeForm).find('[name="registration_type"]:checked').val() || 'other';
        $.ajax({
            method: "GET",
            url: baseUrl + '/registrationform/getPatientDetailByPatientId',
            data: {registration_type: registration_type, patientId: patientId},
            dataType: "json",
        }).done(function (data) {
            plotPatientData(data);

        });
    }
}

$(document).on('change', '#js-registration-is-follow-up', function () {
    toggleFollowupdateRequired();
});

function toggleFollowupdateRequired() {
    setTimeout(() => {
        var isFollowUp = $('#js-registration-is-follow-up').prop('checked');
        if (isFollowUp) {
            $('#js-registration-followup-date').attr('required', true);

            var today = new Date();
            today = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();
            var today_object = NepaliFunctions.ConvertToDateObject(today,'MM/DD/YYYY');
            var today_english_date_object = NepaliFunctions.AD2BS(today_object);
            $('#js-registration-followup-date').val(NepaliFunctions.ConvertDateFormat(today_english_date_object));
        } else {
            $('#js-registration-followup-date').attr('required', false);
            $('#js-registration-followup-date').val('');
        }
    }, 200);
}

// $('#select-old-patient-discount-scheme option[value="GENERAL"]').prop('selected', true);
// $(activeForm).find('#select2-select-old-patient-discount-scheme-container').attr('title', data.fldbillingmode);
// $(activeForm).find('#select2-select-old-patient-discount-scheme-container').text(data.fldbillingmode);

function plotPatientData(data) {
    var activeForm = $('div.tab-pane.fade.active.show');
    // $(activeForm).find('.js-registration-billing-mode option').prop('selected', false);

    $('#js-registration-is-follow-up').attr('checked', false);
    toggleFollowupdateRequired();

    if (data) {

        var dob = data.fldptbirday ? data.fldptbirday : '';
        // var nepaliDateConverter = new NepaliDateConverter();
        if (dob != '') {
            var detail = getAgeDetail(dob);
            $(activeForm).find('.js-registration-age').val(detail.age);
            $(activeForm).find('.js-registration-month').val(detail.month);
            $(activeForm).find('.js-registration-day').val(detail.day);

            //     dob = dob.split(' ')[0].split('-');
            //     dob = dob[1] + '/' + dob[2] + '/' + dob[0];
            //     dob = nepaliDateConverter.ad2bs(dob);
        }

        if (data.patientDepartments && data.patientDepartments.length > 0)
            patientOldDepartments = data.patientDepartments;

        $('#js-registration-encounterid-b').text(data.fldencounterval);
        var selectedPatientType = $(activeForm).find('.js-registration-billing-mode option[value="' + data.fldbillingmode + '"]');
        if (data.fldbillingmode && selectedPatientType.length > 0) {
            $(activeForm).find('.js-registration-billing-mode option').prop('selected', false);
            $(selectedPatientType).prop('selected', true);
            // $(activeForm).find('.js-registration-billing-mode').trigger('change');
            $(activeForm).find('#select2-js-registration-billing-mode-old-container').attr('title', data.fldbillingmode);
            $(activeForm).find('#select2-js-registration-billing-mode-old-container').text(data.fldbillingmode);
        }

        $(activeForm).find('.js-registration-last-name').val(data.fldptnamelast ? data.fldptnamelast : '');
        $(activeForm).find('.js-registration-first-name').val(data.fldptnamefir);
        $(activeForm).find('.js-registration-patient-no').val(data.fldpatientval);

        $(activeForm).find('.js-registration-middle-name').val(data.fldmidname ? data.fldmidname : '');
        $(activeForm).find('.js-registration-guardian').val(data.fldptguardian ? data.fldptguardian : '');
        $(activeForm).find('.js-registration-tole').val(data.fldptaddvill ? data.fldptaddvill : '');
        $(activeForm).find('.js-registration-email').val(data.fldemail ? data.fldemail : '');
        $(activeForm).find('.js-registration-pan-number').val(data.fldpannumber ? data.fldpannumber : '');
        $(activeForm).find('.js-registration-claim-code').val(data.fldclaimcode ? data.fldclaimcode : '');
        $(activeForm).find('.js-registration-national-id').val(data.fldnationalid ? data.fldnationalid : '');
        $(activeForm).find('.js-registration-nhsi-no').val(data.fldnhsiid ? data.fldnhsiid : '');
        $(activeForm).find('.js-registration-contact-number').val(data.fldptcontact ? data.fldptcontact : '');
        $(activeForm).find('.js-registration-wardno').val(data.fldwardno ? data.fldwardno : '');
        $(activeForm).find('.js-registration-citizenship-no').val(data.fldcitizenshipno ? data.fldcitizenshipno : '');
        $(activeForm).find('.js-registration-dob').val(dob);
        $(activeForm).find('#old-input-ssf-number').val(data.ssf_number);
        if (data.discount_scheme != "") {
            $(activeForm).find('#select-old-patient-discount-scheme').val(data.discount_scheme).trigger('change');
        }

        if (data.latest_image)
            $(activeForm).find('.profile').attr('src', data.fldpic);
        else
            $(activeForm).find('.profile').attr('src', baseUrl + '/assets/images/dummy-img.jpg');

        if (data && data.fldregdate && data.fldregdate != null) {
            var fldregdate = data.fldregdate.split(' ')[0].split('-');
            fldregdate = fldregdate[1] + '/' + fldregdate[2] + '/' + fldregdate[0];
            var today_object = NepaliFunctions.ConvertToDateObject(fldregdate,'MM/DD/YYYY');
            var today_english_date_object = NepaliFunctions.AD2BS(today_object);
            $('#js-registration-lastvisit-b').text(NepaliFunctions.ConvertDateFormat(today_english_date_object));
            $(activeForm).find('.js-registration-last-visit').val(NepaliFunctions.ConvertDateFormat(today_english_date_object));
        }
        if (data && data.fldfollowdate) {
            var fldfollowdate = data.fldfollowdate.split(' ')[0].split('-');
            fldfollowdate = fldfollowdate[1] + '/' + fldfollowdate[2] + '/' + fldfollowdate[0];
            var today_object = NepaliFunctions.ConvertToDateObject(fldfollowdate,'MM/DD/YYYY');
            var today_english_date_object = NepaliFunctions.AD2BS(today_object);
            $(activeForm).find('.js-registration-followup-date').val(NepaliFunctions.ConvertDateFormat(today_english_date_object));
        }

        $(activeForm).find('.js-registration-title option').prop('selected', false);
        $(activeForm).find('.js-registration-title').val(data.fldtitle);
        $(activeForm).find('.js-registration-title').trigger('change');

        $(activeForm).find('.js-registration-gender option').prop('selected', false);
        $(activeForm).find('.js-registration-gender').val(data.fldptsex);
        $(activeForm).find('.js-registration-gender').trigger('change');
        // $(activeForm).find('.js-registration-gender option[value="' + data.fldptsex + '"]').prop('selected', true);

        $(activeForm).find('.js-registration-relation option').prop('selected', false);
        $(activeForm).find('.js-registration-relation').val(data.fldrelation);
        $(activeForm).find('.js-registration-relation').trigger('change');
        // $(activeForm).find('.js-registration-relation option[value="' + data.fldrelation + '"]').prop('selected', true);

        // $(activeForm).find('.js-registration-ethnic-group option').prop('selected', false);
        // $(activeForm).find('.js-registration-ethnic-group option[value="' + data.fldethnicgroup + '"]').prop('selected', true);
        $(activeForm).find('.js-registration-blood-group option').prop('selected', false);
        $(activeForm).find('.js-registration-blood-group').val(data.fldbloodgroup);
        $(activeForm).find('.js-registration-blood-group').trigger('change');
        // $(activeForm).find('.js-registration-blood-group option[value="' + data.fldbloodgroup + '"]').prop('selected', true);

        $(activeForm).find('.js-registration-marital-status option').prop('selected', false);
        $(activeForm).find('.js-registration-marital-status').val(data.fldmaritalstatus);
        $(activeForm).find('.js-registration-marital-status').trigger('change');
        if(data.remaining_credit_amount !=''){
            $(activeForm).find('#creditPart').show();
            $(activeForm).find('.creditAmount').text(data.remaining_credit_amount)
        }else{
            $(activeForm).find('#creditPart').hide();
        }
        // var selectedLastName = $(activeForm).find('.js-registration-last-name option[value="' + data.fldptnamelast + '"]');
        // $(activeForm).find('.js-registration-last-name option').prop('selected', false);
        // if (data.fldptnamelast && selectedLastName.length > 0) {
        //     $(selectedLastName).prop('selected', true);
        //     $(selectedLastName).trigger('change');
        //     $(activeForm).find('#select2-js-registration-last-name-old-container').attr('title', data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-old-container').text(data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-new-container').attr('title', data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-new-container').text(data.fldptnamelast);
        // } else {
        //     var newOption = new Option(data.fldptnamelast, data.fldptnamelast, true);
        //     $(activeForm).find('.js-registration-last-name').append(newOption);
        //     $(activeForm).find('#select2-js-registration-last-name-old-container').attr('title', data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-old-container').text(data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-new-container').attr('title', data.fldptnamelast);
        //     $(activeForm).find('#select2-js-registration-last-name-new-container').text(data.fldptnamelast);
        // }

        var selectedLastName = $(activeForm).find('.js-registration-ethnic-group option[value="' + data.fldethnicgroup + '"]');
        $(activeForm).find('.js-registration-ethnic-group option').prop('selected', false);
        if (data.fldethnicgroup && selectedLastName.length > 0) {
            $(selectedLastName).prop('selected', true);
            $(activeForm).find('#select2-js-registration-ethnic-old-container').attr('title', data.fldethnicgroup);
            $(activeForm).find('#select2-js-registration-ethnic-old-container').text(data.fldethnicgroup);
        }
        // address
        var defaultCountry = 'NEPAL';
        var fldprovince = data && data.fldprovince ? data.fldprovince : null;
        $(activeForm).find('.js-registration-country option[value="' + defaultCountry + '"]').prop('selected', true);
        getProvinces(defaultCountry, fldprovince);
        setTimeout(function () {
            if (data && data.fldprovince && data.flddistrict) {
                getDistrict(data.fldprovince, data.flddistrict);
                setTimeout(function () {
                    getMunicipality(data.flddistrict, data.fldpality);
                }, 500);
            } else if (data && data.fldptadddist)
                getMunicipality(data.fldptadddist, null);
        }, 500);
    }
}

$('#js-registration-patient-no').keydown(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        getPatientDetailByPatientId();
    }
});
$('#js-registration-refresh').click(function (e) {
    getPatientDetailByPatientId();
});

$(document).on('change', '.js-registration-consultant', function () {
    $(this).closest('.c-row').find('.js-registration-consultantid').val($(this).find('option:selected').data('consultantid'));
});

function getAgeDetail(dob) {
    var d1 = new Date();
    var d2 = new Date(dob);
    diff = new Date(d1.getFullYear() - d2.getFullYear(), d1.getMonth() - d2.getMonth(), d1.getDate() - d2.getDate(),d1.getHours() - d2.getHours());

    return {
        age: diff.getYear(),
        month: diff.getMonth(),
        day: diff.getDate(),
        hours: diff.getHours()
    }
}

// $(document).on('blur','.js-registration-dob',function(e){
//     var dob = $(this).val().split('-');
//     if(dob[1] != undefined && dob[2] != undefined && dob[0] != undefined) {
//         var activeForm = $('div.tab-pane.fade.active.show');
//         dob = dob[1] + '/' + dob[2] + '/' + dob[0];
//         dob = (new NepaliDateConverter()).bs2ad(dob);
//         var detail = getAgeDetail(dob);

//         $(activeForm).find('.js-registration-age').val(detail.age);
//         $(activeForm).find('.js-registration-month').val(detail.month);
//         $(activeForm).find('.js-registration-day').val(detail.day);
//     }
// });

$('.js-registration-dob').datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    maxDate:0,
    yearRange: "-100:+0",
    onSelect: function (dob) {
        var activeForm = $('div.tab-pane.fade.active.show');
        var detail = getAgeDetail(dob);
        var eng_date = NepaliFunctions.ConvertToDateObject(this.value,'YYYY-MM-DD');
        var english_date = NepaliFunctions.AD2BS(eng_date);

        $('#nep_from_date').val(NepaliFunctions.ConvertDateFormat(english_date));
        $('#eng_from_date').val(this.value);
        $(activeForm).find('.js-registration-age').val(detail.age);
        $(activeForm).find('.js-registration-month').val(detail.month);
        $(activeForm).find('.js-registration-day').val(detail.day);
        $(activeForm).find('.js-registration-hours').val(0);
        $('#date_hour').val(0);
    }
});

$('.js-registration-age,.js-registration-month,.js-registration-day').keydown(function (e) {
    if (!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8 || e.keyCode == 9))
        return false;
});

$('.js-registration-age,.js-registration-month,.js-registration-day,.js-registration-hours').keyup(function (e) {
    var activeForm = $('div.tab-pane.fade.active.show');
    // this.value = this.value.replace(/[^0-9]/g,'');
    // var age = this.value;
    var age = $(activeForm).find('.js-registration-age').val().replace(/[^0-9]/g, '');
    var month = $(activeForm).find('.js-registration-month').val().replace(/[^0-9]/g, '');
    var day = $(activeForm).find('.js-registration-day').val().replace(/[^0-9]/g, '');
    var hours = $(activeForm).find('.js-registration-hours').val().replace(/[^0-9]/g, '');

    // var totalDays = (Number(age)*365) + (Number(month)*30) + Number(day)+1;
    // var priorDate = new Date().setDate((new Date()).getDate()-totalDays);
    // priorDate = new Date(priorDate);

    // var dd = priorDate.getDate();
    // var mm = priorDate.getMonth()+1;
    // var yyyy = priorDate.getFullYear()-parseInt(age);

    var today = new Date();
    var currentYear = today.getFullYear();
    var yyyy = currentYear - age;
    mm = month;
    dd = day;
    hh = hours;

    if (dd < 10)
        dd = '0' + dd;
    if (mm < 10)
        mm = '0' + mm;
    var newdate = new Date(yyyy, today.getMonth(), today.getDate(), today.getHours(), 0, 0, 0);
    newdate.setMonth(newdate.getMonth() - mm);
    newdate.setDate(newdate.getDate()-dd);
    newdate.setHours(newdate.getHours() - hh);
    $('#date_hour').val(newdate.getHours());


    console.log('newdate',newdate);
    // var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
    // $(activeForm).find('.js-registration-dob').val(dob);
    $(activeForm).find('.js-registration-dob').datepicker('setDate', newdate);
    var nep_date = NepaliFunctions.ConvertToDateObject($('#js-registration-dob-new').val(),'YYYY-MM-DD');
    console.log(nep_date);
    var nepali_date = NepaliFunctions.AD2BS(nep_date);
    $('#nep_from_date').val(NepaliFunctions.ConvertDateFormat(nepali_date));


    $('#eng_from_date').val($('#js-registration-dob-new').val());
    $('#nep_date').val(NepaliFunctions.ConvertDateFormat(nepali_date));
});

$(document).on('change', '.js-registration-department,.js-registration-discount-scheme,#js-registration-is-follow-up', function () {
    var activeForm = $(this).closest('.tab-pane');
    var discount = $(activeForm).find('.js-registration-discount-scheme option:selected');
    $(activeForm).find('.js-registration-flddiscper').val($(discount).data('fldpercent'));
    $(activeForm).find('.js-registration-flddiscamt').val($(discount).data('fldamount'));

    var department = [];
    var consultant = [];
    $.each($(activeForm).find('.js-registration-department'), function (i, elem) {
        var value = $(elem).val() || '';
        if (value != '')
            department.push(value);
    });
    $.each($(activeForm).find('.js-registration-consultantid'), function (i, elem) {
        var value = $(elem).val() || '';
        consultant.push(value);
    });

    var type = $(activeForm).find('.js-registration-billing-mode option:selected').val();
    var is_follow = $(activeForm).find('#js-registration-is-follow-up').prop('checked') ? 'Followup' : 'General';
    var patientID = $(activeForm).find('#js-registration-patient-no').val();
    var fldregtype = $(activeForm).attr('id');
    if (fldregtype == 'new-patient')
        fldregtype = 'New Registration';
    else
        fldregtype = ((is_follow == 'Followup')) ? 'Follow Up' : 'Other Registration';

    $(activeForm).find('.js-registration-regtype-hidden').val(fldregtype);
    if (department.length > 0) {
        $.ajax({
            method: "GET",
            data: {
                department: department,
                type: type,
                is_follow: is_follow,
                patientid: patientID,
                fldregtype: fldregtype,
                consultant:consultant
            },
            dataType: "json",
            url: baseUrl + '/registrationform/getRegistrationCost',
        }).done(function (response) {
            if(response.status == false){
                $(activeForm).find('.js-registration-billing-tbody').html('');
                $(activeForm).find('.js-registration-amount').val('');
                return;
            }
            var discount = $(activeForm).find('.js-registration-discount-scheme option:selected') || '';
            var discountMode = ($(discount).data('fldmode') || '').toLowerCase();
            var trData = "";
            var grandTotal = 0;

            if (response.followup == '1') {
                // need for department disable
                // $(activeForm).find('select[name="department[]"] option').removeAttr('disabled', false);

                $('#js-registration-is-follow-up').attr('checked', true);
                toggleFollowupdateRequired();
            } else {
                $('#js-registration-is-follow-up').attr('checked', false);
                toggleFollowupdateRequired();
            }

            $.each(response.costData, function (i, cost) {
                var dicountCalculated = 0;
                var subTotal = cost.flditemcost * cost.flditemqty;

                if (discountMode == 'fixed percent' || discountMode == 'fixedpercent' || discountMode == 'percent') {
                    if ($(discount).data('fldpercent') >= 0) {
                        dicountCalculated = (($(discount).data('fldpercent') / 100) * subTotal);
                    }
                } else {
                    // getDiscountData($(discount).val(), cost.flditemname).then((val) => {
                    //     dicountCalculated = subTotal*(val.fldpercent)/100;
                    //     console.log(val.)
                    // });

                    $.ajax({
                        method: "GET",
                        async: false,
                        data: {discountName: $(discount).val(), itemName: cost.flditemname},
                        dataType: "json",
                        url: baseUrl + '/registrationform/get-discount-percent',
                        success: function (discountData) {
                            dicountCalculated = subTotal * (discountData) / 100;
                        }
                    });
                }

                trData += "<tr>";
                trData += "<td>" + (i + 1) + "</td>";
                trData += "<td>" + cost.flditemname + "</td>";
                trData += "<td>" + numberFormat(subTotal) + "</td>";
                trData += "<td>" + numberFormat(dicountCalculated) + "</td>";
                trData += "<td>" + numberFormat((subTotal - dicountCalculated)) + "</td>";
                trData += "</tr>";

                grandTotal += (subTotal - dicountCalculated);
            });
            $(activeForm).find('.js-registration-billing-tbody').html(trData);
            $(activeForm).find('.js-registration-amount').val(numberFormat(grandTotal));
        });
    }
});
$(document).on('change', '.js-registration-consultant', function () {
    var activeForm = $(this).closest('.tab-pane');
    var discount = $(activeForm).find('.js-registration-discount-scheme option:selected');
    $(activeForm).find('.js-registration-flddiscper').val($(discount).data('fldpercent'));
    $(activeForm).find('.js-registration-flddiscamt').val($(discount).data('fldamount'));

    var department = [];
    var consultant = [];

    $.each($(activeForm).find('.js-registration-department'), function (i, elem) {
        var value = $(elem).val() || '';
        if (value != '')
            department.push(value);
    });

    $.each($(activeForm).find('.js-registration-consultantid'), function (i, elem) {
        var value = $(elem).val() || '';
        consultant.push(value);
    });

    var type = $(activeForm).find('.js-registration-billing-mode option:selected').val();
    var is_follow = $(activeForm).find('#js-registration-is-follow-up').prop('checked') ? 'Followup' : 'General';
    var patientID = $(activeForm).find('#js-registration-patient-no').val();
    var fldregtype = $(activeForm).attr('id');
    if (fldregtype == 'new-patient')
        fldregtype = 'New Registration';
    else
        fldregtype = ((is_follow == 'Followup')) ? 'Follow Up' : 'Other Registration';

    $(activeForm).find('.js-registration-regtype-hidden').val(fldregtype);
    if (department.length > 0) {
        $.ajax({
            method: "GET",
            data: {
                department: department,
                type: type,
                is_follow: is_follow,
                patientid: patientID,
                fldregtype: fldregtype,
                consultant:consultant
            },
            dataType: "json",
            url: baseUrl + '/registrationform/getRegistrationCost',
        }).done(function (response) {
            if(response.status == false){
                $(activeForm).find('.js-registration-billing-tbody').html('');
                $(activeForm).find('.js-registration-amount').val('');
                return;
            }
            var discount = $(activeForm).find('.js-registration-discount-scheme option:selected') || '';
            var discountMode = ($(discount).data('fldmode') || '').toLowerCase();
            var trData = "";
            var grandTotal = 0;

            if (response.followup == '1') {
                // need for department disable
                // $(activeForm).find('select[name="department[]"] option').removeAttr('disabled', false);

                $('#js-registration-is-follow-up').attr('checked', true);
                toggleFollowupdateRequired();
            } else {
                $('#js-registration-is-follow-up').attr('checked', false);
                toggleFollowupdateRequired();
            }

            $.each(response.costData, function (i, cost) {
                var dicountCalculated = 0;
                var subTotal = cost.flditemcost * cost.flditemqty;

                if (discountMode == 'fixed percent' || discountMode == 'fixedpercent' || discountMode == 'percent') {
                    if ($(discount).data('fldpercent') >= 0) {
                        dicountCalculated = (($(discount).data('fldpercent') / 100) * subTotal);
                    }
                } else {
                    // getDiscountData($(discount).val(), cost.flditemname).then((val) => {
                    //     dicountCalculated = subTotal*(val.fldpercent)/100;
                    //     console.log(val.)
                    // });

                    $.ajax({
                        method: "GET",
                        async: false,
                        data: {discountName: $(discount).val(), itemName: cost.flditemname},
                        dataType: "json",
                        url: baseUrl + '/registrationform/get-discount-percent',
                        success: function (discountData) {
                            dicountCalculated = subTotal * (discountData) / 100;
                        }
                    });
                }

                trData += "<tr>";
                trData += "<td>" + (i + 1) + "</td>";
                trData += "<td>" + cost.flditemname + "</td>";
                trData += "<td>" + numberFormat(subTotal) + "</td>";
                trData += "<td>" + numberFormat(dicountCalculated) + "</td>";
                trData += "<td>" + numberFormat((subTotal - dicountCalculated)) + "</td>";
                trData += "</tr>";

                grandTotal += (subTotal - dicountCalculated);
            });
            $(activeForm).find('.js-registration-billing-tbody').html(trData);
            $(activeForm).find('.js-registration-amount').val(numberFormat(grandTotal));
        });
    }
});




$(document).on('click', '.js-multi-consultation-remove-btn', function () {
    var trCount = $(this).closest('.js-multi-consultation-tbody').find('.c-row').length;
    if (trCount > 1) {
        $(this).closest('.c-row').remove();
        $('.js-registration-discount-scheme').trigger('change');
    }

});

$(document).on('change', '.js-registration-department', function () {

    var department = $(this).val() || '';
    var currentDepartmentTd = $(this).closest('td');
    var _this = $(this);
    $(currentDepartmentTd).next('td').find('.js-registration-consultant').empty().append(selectOption.clone());
    if (department !== '') {
        $.ajax({
            method: "GET",
            data: {department: department},
            dataType: "json",
            url: baseUrl + '/registrationform/getDepatrmentUser',
        }).done(function (data) {
            var elems = '';
            elems += "<option value=''>Select</option>";
            $.each(data, function (i, d) {
                var fldfullname = d.fldfullname.trim();
                elems += '<option value="' + d.flduserid + '" data-consultantid="' + d.id + '">' + fldfullname + '(NMC: ' + d.nmc + ')</option>';
            });
            console.log('asdsada');

            $(currentDepartmentTd).next('td').find('.js-registration-consultant').append(elems);
            _this.closest('div.c-row').find('.js-registration-consultant').empty().append(elems);
            $(document).find('.js-registration-consultant').val("");
        });
    }
});
// $('#js-registration-consultant').change(function() {
//     $('#js-registration-nmc-number').val($('#js-registration-consultant option:selected').attr('nmc'));
// });

function insurance_toggle() {
    var insurance_type = $('#js-registration-insurance-type').val() || '';
    if (insurance_type == '')
        $('.insurance-toggle').hide();
    else
        $('.insurance-toggle').show();
}

insurance_toggle();
$('#js-registration-insurance-type').change(function () {
    insurance_toggle();
});


$(document).on('click', '#js-registration-table-modal tr', function () {
    selected_td('#js-registration-table-modal tr', this);
});
$('.js-registration-add-surname').click(function () {
    var activeForm = $(this).closest('.tab-pane');
    var tr_data = '';
    $.each($(activeForm).find('.js-registration-last-name option'), function (i, e) {
        var value = $(e).val();
        var id = $(e).data('id');
        if (value !== '')
            tr_data += '<tr data-flditem="' + id + '"><td>' + value + '</td></tr>';
    });

    $('#js-registration-table-modal').html(tr_data);
    $('#js-registration-add-item-modal').modal('show');
});


$(document).on('keyup', '.select2-search__field', function (e) {
    var id = $(this).closest('.select2-dropdown').find('.select2-results ul').attr('id');
    var flditem = $(this).val() || '';
    if ((id == "select2-js-registration-last-name-new-results" || id == "select2-js-registration-last-name-old-results") && e.keyCode === 13 && flditem != '') {
        var data = {
            flditem: flditem,
        };
        $.ajax({
            url: baseUrl + '/registrationform/addSurname',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var activeForm = $('div.tab-pane.fade.active.show');
                    var val = response.data;
                    var newOption = new Option(val.flditem, val.flditem, true, true);
                    $(activeForm).find('.js-registration-last-name').append(newOption).trigger('change');
                    $(activeForm).find('.js-registration-last-name').val(val.flditem).trigger('change');
                    $(activeForm).find('.js-registration-last-name').select2("close");
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-registration-add-btn-modal').click(function () {
    var data = {
        flditem: $('#js-registration-flditem-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/registrationform/addSurname',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;

                var trData = '<tr data-flditem="' + val.id + '"><td>' + val.flditem + '</td></tr>';
                $('#js-registration-table-modal').append(trData);
                $('#js-registration-flditem-input-modal').val('');
            }
            showAlert(response.message);
        }
    });
});

$('#js-registration-delete-btn-modal').click(function () {
    var data = {
        id: $('#js-registration-table-modal tr[is_selected="yes"]').data('flditem'),
    };
    $.ajax({
        url: baseUrl + '/registrationform/deleteSurname',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-registration-table-modal tr[is_selected="yes"]').remove();

            showAlert(response.message);
        }
    });
});

function refresh_registration_options() {
    $.ajax({
        url: baseUrl + '/registrationform/getSurname',
        type: "GET",
        success: function (response) {
            var activeForm = $('div.tab-pane.fade.active.show');
            var surnames = '<option value="">-- Select --</option>';
            $.each(response, function (i, e) {
                surnames += '<option value="' + e.flditem + '" data-id="' + e.fldid + '">' + e.flditem + '</option>'
            });
            $(activeForm).find('.js-registration-last-name').html(surnames);
        }
    });
}

$('#js-registration-add-item-modal').on('hidden.bs.modal', function () {
    $('#js-registration-flditem-input-modal').val('');
    $('#js-registration-type-input-modal').val('');
    $('#js-registration-table-modal').html('');

    refresh_registration_options();
});

$('#js-toggle-filter').click(function () {
    if (document.getElementById('js-registration-list-filter').style.display == 'none') {
        $('#js-registration-list-filter').show();
        // $('#js-toggle-filter').text('H');
    } else {
        $('#js-registration-list-filter').hide();
        // $('#js-toggle-filter').text('Show Filter');
    }
});

$('.js-registration-list-edit').click(function () {
    $('#patient_no').val($(this).closest('tr').data('fldpatientval'));
    searchPatient();

    $('#patient-profile-modal').modal('show');
});

$('.js-registration-list-edit-consultant').click(function () {
    $('#edit-consult-patient').val($(this).closest('tr').data('encounter'));
    getConsultants()
    $('#edit-consult-modal').modal('show');
});

$('.js-registration-nhsi-no').on('focusout', function (){

    var patientid = $('.js-registration-nhsi-no').val();
    var billingmode = $('.js-registration-billing-mode').val();

    if(patientid != ''){

        let input = $(".js-registration-nhsi-no");

    $.ajax({
        url:  baseUrl + '/registrationform/checkeligibility',
        type: "GET",
        data: {
            patientid: patientid,
           },
        success: function(response) {

            if(response.response == 'error'){
                showAlert('NHSI number is incorrect!','error');
                $('.js-registration-nhsi-no').val('');

            }else if(response.patientval != ''){

                var activeForm = $('div.tab-pane.fade.active.show.active');
                if(activeForm[0].id == 'new-patient'){

                    Swal.fire({
                        title: 'Patient Already Registered With Patient ID:' + response.patientval,
                        showDenyButton: true,
                        confirmButtonText: 'Continue',
                        denyButtonText: `Cancel`,
                        allowOutsideClick: false,
                      }).then((result) => {
                        if (result.isConfirmed) {
                        //   Swal.fire('Saved!', '', 'success')
                          $('#old-patient-tab').trigger('click');
                          $('#js-registration-patient-no').val(response.patientval);
                          var activeForm = $('div.tab-pane.fade.active.show');
        
                          $(activeForm).find('.js-registration-billing-mode').val(billingmode);
    
                          var data = response.response;
              
                          var allowedMoney = data["insurance"] ? data["insurance"][0]["benefitBalance"][0]["financial"][0]["allowedMoney"]["value"] : 0 ;
                          var usedMoney = data["insurance"][0]["benefitBalance"][0]["financial"][0]["usedMoney"]["value"];
                          allowedMoney = allowedMoney - usedMoney;
    
                          $('#oldhiamount').text(allowedMoney);
    
                          getPatientDetailByPatientId();
    
                        } else if (result.isDenied) {
                            $(".js-registration-nhsi-no").val('');
                        }
                      })

                }else{

                    input.css({borderColor: 'red'});
                    // $('#flditemcode').val(patientid);

                    
        
                    var data = response.response;
                    var resPatDetail = response.responsePatDetail;
        
                    var allowedMoney = data["insurance"] ? data["insurance"][0]["benefitBalance"][0]["financial"][0]["allowedMoney"]["value"] : 0 ;
                    var expiry = data["insurance"][0]["contract"]["reference"] ;
                    var usedMoney = data["insurance"][0]["benefitBalance"][0]["financial"][0]["usedMoney"]["value"];
                    var fname = resPatDetail["entry"][0]["resource"]["name"][0]["given"][0];
                    var lname = resPatDetail["entry"][0]["resource"]["name"][0]["family"];
        
                    var dob = resPatDetail["entry"][0]["resource"]["birthDate"];
                    var gender = resPatDetail["entry"][0]["resource"]["gender"];
        
                    if(dob){
                        var detail = getAgeDetail(dob);
                        var activeForm = $('div.tab-pane.fade.active.show');
        
                        $(activeForm).find('.js-registration-age').val(detail.age);
                        $(activeForm).find('.js-registration-month').val(detail.month);
                        $(activeForm).find('.js-registration-day').val(detail.day);
                    }
        
                    var gender1 = gender.charAt(0).toUpperCase() + gender.slice(1);
        
                    // var engdob = BS2AD(dob);
        
                    // alert(engdob);
        
                    // var nepalidate = dob.split('-');
                    // nepalidate = nepalidate[1] + '/' + nepalidate[2] + '/' + nepalidate[0];
                    // nepalidate = nepaliDateConverter.ad2bs(nepalidate);
                    allowedMoney = allowedMoney - usedMoney;
                    $('.hi-modal-lg').modal('show');
                    $('#insurance-no').text(patientid);
                    $('#fname').text(fname);
                    $('#lname').text(lname);
                    $('#dob').text(dob);
                    $('#gender').text(gender1);
                    $('#expiry').text(expiry);
                    $('#allmoney').text(allowedMoney);
                    // $('#usedmoney').text(usedMoney);
        
                    if(allowedMoney > 0){
                        input.css({borderColor: 'green'});
                    }else{
                        input.css({borderColor: 'red'});
                    }
        
        
                    // $('.nhsi-patient').val(allowedMoney);
        
                    // console.log(resPatDetail);
        
                    showAlert('Data Retrieved From HI');

                }
                
                

            
            }else{

                input.css({borderColor: 'red'});
            // $('#flditemcode').val(patientid);

            var data = response.response;
            var resPatDetail = response.responsePatDetail;

            var allowedMoney = data["insurance"] ? data["insurance"][0]["benefitBalance"][0]["financial"][0]["allowedMoney"]["value"] : 0 ;
            var expiry = data["insurance"][0]["contract"]["reference"] ;
            var usedMoney = data["insurance"][0]["benefitBalance"][0]["financial"][0]["usedMoney"]["value"];
            var fname = resPatDetail["entry"][0]["resource"]["name"][0]["given"][0];
            var lname = resPatDetail["entry"][0]["resource"]["name"][0]["family"];

            var dob = resPatDetail["entry"][0]["resource"]["birthDate"];
            var gender = resPatDetail["entry"][0]["resource"]["gender"];

            if(dob){
                var detail = getAgeDetail(dob);
                var activeForm = $('div.tab-pane.fade.active.show');

                $(activeForm).find('.js-registration-age').val(detail.age);
                $(activeForm).find('.js-registration-month').val(detail.month);
                $(activeForm).find('.js-registration-day').val(detail.day);
            }

            var gender1 = gender.charAt(0).toUpperCase() + gender.slice(1);

            // var engdob = BS2AD(dob);

            // alert(engdob);

            // var nepalidate = dob.split('-');
            // nepalidate = nepalidate[1] + '/' + nepalidate[2] + '/' + nepalidate[0];
            // nepalidate = nepaliDateConverter.ad2bs(nepalidate);
            allowedMoney = allowedMoney - usedMoney;
            $('.hi-modal-lg').modal('show');
            $('#insurance-no').text(patientid);
            $('#fname').text(fname);
            $('#lname').text(lname);
            $('#dob').text(dob);
            $('#gender').text(gender1);
            $('#expiry').text(expiry);
            $('#allmoney').text(allowedMoney);
            // $('#usedmoney').text(usedMoney);

            if(allowedMoney > 0){
                input.css({borderColor: 'green'});
            }else{
                input.css({borderColor: 'red'});
            }


            // $('.nhsi-patient').val(allowedMoney);

            // console.log(resPatDetail);

            showAlert('Data Retrieved From HI');

            }

        },

        error: function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });

    }else{
        showAlert('Please enter NHSI number for HI Patient!','error');

    }


  });





  $('.verify').on('click', function (){

    var act = $('#new-patient-tab').hasClass("active");

    if(act){
        $('.js-registration-hi-amount').val($('#allmoney').text());
        $('.js-registration-first-name').val($('#fname').text());
        $('.js-registration-last-name').val($('#lname').text());

        $('.js-registration-dob').val($('#dob').text());
        $('.js-registration-gender').val($('#gender').text()).change();

    }else{
        $('.js-registration-old-hi-amount').val($('#allmoney').text());
    }


  });


function getConsultants() {

    if ($('#edit-consult-patient').val() == "") {
        alert('Please select patient id.');
        return false;
    }
    // alert($('#edit-consult-patient').val());
    $.ajax({
        url: baseUrl + '/registrationform/get-edit-consultation/',
        type: "GET",
        data: {encounterId: $('#edit-consult-patient').val()},
        success: function (data) {
            console.log(data);
            // return false;
            $('.js-multi-consultation-tbody-edit-consult').empty().append(data.select);
            $(document).find('.js-registration-department').select2();
            $(document).find('.js-registration-consultant').select2();

            if(data.error){
                showAlert(data.error,'error');
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}


$('.js-registration-list-view').click(function () {
    var patientId = $(this).closest('tr').data('fldpatientval') || '';
    if (patientId != '') {
        $.ajax({
            method: "GET",
            // url: baseUrl + '/registrationform/getPatientDetailByPatientId/' + patientId, // yeta bata parameter send gareko chha but route ma define chhaina like /{id}
            url: baseUrl + '/registrationform/getPatientDetailByPatientId/',
            data: {
                patientId: patientId, //because controller ma $request->PatientID chha
            },
        }).done(function (data) {
            var dob = data.fldptbirday ? data.fldptbirday : '';
            // var nepaliDateConverter = new NepaliDateConverter();
            if (dob != '') {
                var detail = getAgeDetail(dob);
                $('#js_view_years').text(detail.age);
                $('#js_view_month').text(detail.month);

                // dob = dob.split(' ')[0].split('-');
                // dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                // dob = nepaliDateConverter.ad2bs(dob);
            }

            $('#js_view_dob').text(dob);
            $('#js_view_patient_no').text(patientId);
            $('#js_view_profile_encounter').text((data.latest_encounter && data.latest_encounter.fldencounterval) ? data.latest_encounter.fldencounterval : '');
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

    $('#js-patient-profile-view-modal').modal('show');
});


var currentdepartment = 'Consultation';

function getDepartments(department) {
    var activeForm = $('div.tab-pane.fade.active.show');
    var radioVal = $(activeForm).find("input[name='department_seperate_num']:checked").val();

    if (radioVal != 'ER') {
        $(activeForm).find('.consultant-span').show();
        $(activeForm).find('.js-registration-consultant').prop('required', true);
    }

    if (department != currentdepartment) {
        currentdepartment = department;
        $.ajax({
            url: baseUrl + '/registrationform/getDepartments',
            type: "GET",
            data: {department: department},
            dataType: "json",
            success: function (response) {
                var optionData = '<option value="">-- Select --</option>';
                $.each(response, function (i, option) {
                    optionData += '<option value="' + option.flddept + '">' + option.flddept + '</option>';
                });
                $(activeForm).find('.js-registration-department').empty().html(optionData);
                if(department == 'Patient Ward'){
                    $(activeForm).find('.specs_label').text('Patient Ward');
                }
                if(department == 'Consultation'){
                    $(activeForm).find('.specs_label').text('Specialization');
                }

                if(department == 'Emergency'){
                    $(activeForm).find('.specs_label').text('Emergency Department');
                }

            }
        });
    }

    if (department == 'Patient Ward')
        $(activeForm).find('.js-registration-phone-number-div').show();
    else
        $(activeForm).find('.js-registration-phone-number-div').hide();
}

//Suru ma load gareko

$(document).ready(function () {
    var activeForm = $('div.tab-pane.fade.active.show');
    //suru ma country wise load gareko
    var defaultCountry = 'NEPAL';
    $(activeForm).find('.js-registration-country option[value="' + defaultCountry + '"]').attr('selected', true);
    $(activeForm).find('.js-registration-country option').trigger('change');
    getProvinces(defaultCountry, null);
    // getDistrict($('#js-registration-province').val(), null);
    // getMunicipality($('#js-registration-district'), null);

});

function toggleOtherTitle() {
    var value = $('#js-registration-title').val() || '';
    if (value == 'other')
        $('.js-toggle-other-title').show();
    else
        $('.js-toggle-other-title').hide();
}

toggleOtherTitle();
$('#js-registration-title').change(function () {
    toggleOtherTitle();
});

$('#js-patient-global-search').keyup(function () {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-registration-list tr'), function (i, e) {
        var tdText = $(e).text().trim().toUpperCase();

        if (tdText.search(searchText) >= 0)
            $(e).show();
        else
            $(e).hide();
    });
});

function invalidAlphabets(current) {
    openPersonalInfoAccordian();
    current.setCustomValidity('Must include alphabets only');
}

function invalidContact(current) {
    openContactInfoAccordian();
    current.setCustomValidity('Contact number must be atleast 10 digits');
}

function openPersonalInfoAccordian() {
    $('.personalInfoBtn').each(function (index, item) {
        if (!$(this).hasClass('hover')) {
            $(this).addClass('hover');
        }
    });
    $('.personalInfoPanel').css('display', 'block');
}

function openContactInfoAccordian() {
    $('.contactInfoBtn').each(function (index, item) {
        if (!$(this).hasClass('hover')) {
            $(this).addClass('hover');
        }
    });
    $('.contactInfoPanel').css('display', 'block');
}

$('.js-multi-consultation-add-btn').click(function () {
    var trTemplateData = $('#js-multi-consultation-tr-template').html();
    var activeForm = $('div.tab-pane.fade.active.show');

    $(activeForm).find('.js-multi-consultation-tbody').append(trTemplateData);
    $.each($(activeForm).find('.js-multi-consultation-tbody div select'), function (i, elem) {
        if (!$(elem).hasClass('select2-hidden-accessible'))
            $(elem).select2();
    });
});

function getCommon(arr1, arr2) {
    var common = [];
    for (var i = 0; i < arr1.length; ++i) {
        for (var j = 0; j < arr2.length; ++j) {
            if (arr1[i] == arr2[j])
                common.push(arr1[i]);
        }
    }

    return common;
}

// $('.js-registrationform-submit-btn').on('click', function(e) {
//     var postDepartment = $.map($('div.tab-pane.fade.active.show select[name="department[]"]'), function(ele) {
//         return $(ele).val();
//     });
//     if (getCommon(patientOldDepartments, postDepartment).length > 0) {
//         e.preventDefault();
//         showAlert("Same patient cannot be registered in same department in same day.", "fail");
//     }
// });
