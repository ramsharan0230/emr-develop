
function calculateDays(date, dateElem) {
    var dor = $('#js-inpatient-dor-input').val() || '';
    if (dor !== '') {
        dor = new Date(dor);
        var dayCount = Math.round((date-dor)/(1000*60*60*24)) + " Days";

        $(dateElem).next('label').text(dayCount);
    }
}

$(document).ready(function() {
    $.each($('.f-input-date'), function(i, e) {
        calculateDays(new Date(), $(e));
    });
});

$('.f-input-date').change(function() {
    var date = $(this).val() || '';
    date = (date !== '') ? new Date(date) : new Date();

    calculateDays(date, $(this));
});

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

/**
* Start Functions for OnExam
*/

function get_examinations_options() {
    $('#js-examination-option').closest('div').find('span.select2').css('width', '100%');
    $.ajax({
        url: baseUrl + '/inpatient/onexamination/getExaminationsOptions',
        type: "GET",
        success: function (data) {
            var optionData = '';
            optionData += '<option value="">-- Select --</option>';
            $.each(data.data, function(i, option) {
                optionData += '<option value="' + option.fldexamid + '" data-type="' + option.fldtype + '" data-opt="' + option.fldoption + '">' + option.fldexamid + '</option>';
            });
            $('#js-examination-option').empty().html(optionData);
        }
    });
}

function get_examinations_data(date) {
    $.ajax({
        url: baseUrl + '/inpatient/onexamination/getExaminations',
        type: "GET",
        data: {date: date},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data.examinations, function(i, val) {
                var observationVal = (val.fldoption == 'No Selection' || val.fldoption == 'Clinical Scale') ? val.fldrepquanti : val.fldrepquali;
                var abnormalVal = get_abnoraml_btn(val.fldabnormal);
                trData += '<tr data-fldid="' + val.fldid + '">';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td>' + val.fldhead + '</td>';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + observationVal + '</td>';
                trData += '<td class="js-td-delete" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                trData += '<td>' + val.fldtime + '</td>';
                trData += '<td>' + val.flduserid + '</td>';
                trData += '<td>' + val.fldcomp + '</td></tr>';
            });
            $('.js-examinations').empty().html(trData);
        }
    });
}

function get_weight_data() {
    $.ajax({
        url: baseUrl + '/inpatient/onexamination/getWeights',
        type: "GET",
        success: function (data) {
            var trData = '';
            $.each(data.weights, function(i, val) {
                trData += '<tr>';
                trData += '<td>' + val.fldtime + '</td>';
                trData += '<td>' + val.fldrepquanti + '</td></tr>';
            });
            $('.js-weights').empty().html(trData);
        }
    });
}

// Get examination and wegiht data
$('div.accordion-nav ul li a[data-target="#onexam"]').click(function() {
    get_examinations_options();
    get_examinations_data();
    get_weight_data();

    if($('#js-inpatient-status-input').val().toLowerCase() == 'discharge')
    	$('#js-weight-add').attr('disabled', true);
});

// load patient history initially
$('div.accordion-nav ul li a[data-target="#present"]').click(function() {
    $("#patientHistory").change();
});

// Delete examination data
$(document).on('click', '.js-td-delete', function() {
    var currentElem = $(this);
    $.ajax({
        url: baseUrl + '/inpatient/onexamination/deleteExamination',
        type: "POST",
        data: {fldid: $(this).data('fldid')},
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $(currentElem).closest('tr').remove();
                $.each($('.js-examinations tr td:first-child'), function(i, e) {
                    $(e).text(i+1);
                });
            }
            showAlert(response.message);
        }
    });
});

$(document).on('click', '#js-examination-add', function() {
    var examinationid = $('#js-examination-option').val();
    var examtype = $('#js-examination-option option:selected').data('type');
    var examOption = $('#js-examination-option option:selected').data('opt');
    var qualitative = '';
    var quantative = '0';

    if (examOption === 'Clinical Scale') {
        var quantative = 0;
        qualitative = "{";
        $.each($('.js-modal-scale-text'), function(i, e) {
            var valueee = $(e).val();
            quantative += Number(valueee);

            qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
        });
        qualitative = qualitative.substring(0, qualitative.length - 2);
        qualitative += "}";
    } else if (examOption === 'Left and Right') {
        qualitative = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
    } else if (examOption == 'No Selection') {
        qualitative = $('#js-input-no-selection').val();
        quantative = qualitative;
    } else {
        qualitative = $('#js-input-element').val();
    }

    var data = {
        examtype : examtype,
        examinationid : examinationid,
        qualitative : qualitative,
        quantative : quantative,
        abnormalVal : ($('#js-abnormal').length !== undefined && $('#js-abnormal').prop('checked')) ? '1' : '0'
    };
    $.ajax({
        url: baseUrl + '/inpatient/onexamination/saveExamination',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var observationVal = (examOption == 'No Selection' || examOption == 'Clinical Scale') ? val.quantative : val.qualitative;
                var abnormalVal = get_abnoraml_btn(val.abnormal);

                var trData = '<tr data-fldid="' + val.fldid + '">';
                trData += '<td>' + ($('.js-examinations tr').length+1) + '</td>';
                trData += '<td>' + val.examination + '</td>';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + observationVal + '</td>';
                trData += '<td class="js-td-delete" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                trData += '<td>' + val.time + '</td>';
                trData += '<td>' + val.userid + '</td>';
                trData += '<td>' + val.computer + '</td></tr>';

                $('.js-examinations').append(trData);
            }
            $('span.select2-selection__rendered').attr('title', '');
            $('span.select2-selection__rendered').text('');
            $('#js-input-element').val('');
            $('#js-fldrepquanti').val('0');
            showAlert(response.message);

            $('#js-modal').modal('hide');
        }
    });
});

// get modal body content and open pop-up
$('#js-examination-option').change(function() {
    var elem = $('#js-examination-option option:selected');
    var type = $(elem).data('opt');
    var examid = $(elem).attr('value');

    $.ajax({
        url: baseUrl + '/inpatient/onexamination/getModalContent',
        type: "GET",
        data: {type: type, examid: examid},
        dataType: "json",
        success: function (response) {
            if (response.hasOwnProperty('modal') && response.modal) {
                $('#js-modal').html(response.view_data);
                $('#js-modal').modal('show');

                $('#js-modal').on('change', '.js-modal-select', function() {
                    $(this)
                        .closest('td')
                        .next('td')
                        .find('input.js-modal-scale-text')
                        .val($(this).find('option:selected').data('val'));
                });
            }
        }
    });
});

// pop up save button action
$(document).on('click', '#js-save-modal', function() {
    var examinationType = $('#js-examination-type-value').val();
    var valueData = '';
    if (examinationType == 'Clinical Scale') {
        var fldrepquanti = 0;
        valueData = "{";
        $.each($('.js-modal-scale-text'), function(i, e) {
            var valueee = $(e).val();
            fldrepquanti += Number(valueee);

            valueData += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
        });

        $('#js-fldrepquanti').val(fldrepquanti);
        valueData = valueData.substring(0, valueData.length - 2);
        valueData += "}";
    } else if (examinationType == 'No Selection') {
        valueData = $('#js-input-no-selection').val();
    } else if (examinationType == 'Left and Right') {
        valueData = "{\"Left\": \"" + $('#js-left-tbody').val() + "\", \"Right\": \"" + $('#js-right-tbody').val() + "\"}";
    }

    $('#js-input-element').val(valueData);
    $('#js-modal').modal('hide');
});

// list examination by date
$('#js-date-onexam').change(function() {
    get_examinations_data($(this).val());
});

// toggle show all examinations
$('#js-list-onexam').click(function() {
    var toggleData = $('#js-list-onexam').attr('datatype');
    get_examinations_data(toggleData);

    var newVal = (toggleData === 'all') ? 'today' : 'all';
    $('#js-list-onexam').attr('datatype', newVal);
});

// Add weight
$('#js-weight-add').click(function() {
    var weight = $('#js-weight-text').val();
	var date = $('#js-date-onexam').val() || '';
	if (weight !== '0' && weight !== '' ) {
	    $.ajax({
	        url: baseUrl + '/inpatient/onexamination/saveWeight',
	        type: "POST",
	        data: {weight: weight, date: date},
	        dataType: "json",
	        success: function (response) {
	            if (response.status) {
	                $('.js-weights').append('<tr><td>' + response.data.fldtime + '</td><td>' + response.data.fldrepquanti + '</td></tr>');
	            }
	            $('#js-weight-text').val('');
	            showAlert(response.message);
	        }
	    });
	} else
		alert('Weight must be greater thn 0.');
});

$(document).on('click', 'tbody.js-examinations tr td:nth-child(3)', function() {
    $('#js-onexam-status-save-modal').attr('location', 'exam');
    $('#js-onexam-fldid-input').val($(this).closest('tr').data('fldid'));
    var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
    $('#js-onexam-status-select option').attr('selected', false);
    $('#js-onexam-status-select option[value="' + status + '"]').attr('selected', true);
    $('#js-onexam-status-modal').modal('show');
});

$('#js-onexam-status-save-modal').click(function() {
    var fldid = $('#js-onexam-fldid-input').val() || '';
    var fldabnormal = $('#js-onexam-status-select').val() || '';
    var location = $(this).attr('location') || 'onexam';

    if (fldabnormal !== '' || fldid !== '') {
        $.ajax({
            url: baseUrl + '/inpatient/onexamination/changeOnExamStatus',
            type: "POST",
            data: {fldid: fldid, fldabnormal: fldabnormal},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var btnElem = 'tbody.js-examinations tr[data-fldid="' + fldid + '"] td:nth-child(3) div.btn';
                    if (location === 'triage')
                        var btnElem = 'tbody#js-triage-examinations-list tr[data-fldid="' + fldid + '"] td:nth-child(4) div.btn';

                    var addClass = (fldabnormal === '0') ? 'btn-success' : 'btn-danger';
                    var removeClass = (fldabnormal === '0') ? 'btn-danger' : 'btn-success';
                    $(btnElem).removeClass(removeClass);
                    $(btnElem).addClass(addClass);

                    $('#js-onexam-status-modal').modal('hide');
                }
                showAlert(response.message);
            }
        });
    } else
        alert('Invalid data selected for update.');
});


$(document).on('click', '.js-examinations tr td:nth-child(4)', function() {
    updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
});

/**
* End Functions for OnExam
*/



/**
* Start Function for In/Out
*/



// open modal for diet planning
$('#js-plan-btn').click(function() {
    $('#js-input-date-planned').val(new Date().toJSON().slice(0,10));
    $('#js-diet-planning-modal').modal('show');
});

// open modal for intake
$('#js-in-btn').click(function() {
    $('#js-intake-modal').modal('show');
});


// On in-out page load
$('div.accordion-nav ul li a[data-target="#inout"]').click(function() {
    get_out_fluid_select_data();
    get_in_out_list_data();

    get_diet_planning_type();
});

// data for select option in out fluid
function get_out_fluid_select_data() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/getOutFluid',
        type: "GET",
        success: function (data) {
            var optionData = '';
            $.each(data, function(i, option) {
                optionData += '<option value="' + option.fldfluid + '">' + option.fldfluid + '</option>';
            });
            $('#js-out-fluid-option').empty().html(optionData);
        }
    });
}

// list data for in and out fluid
function get_in_out_list_data(date) {
    $.ajax({
        url: baseUrl + '/inpatient/inout/getInOutListData',
        type: "GET",
        data: {date: date},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data.output, function(i, val) {
                trData += '<tr data-fldid="' + val.fldid + '">';
                trData += '<td>' + val.flditem + '</td>';
                trData += '<td>' + val.fldreportquanti + '</td>';
                trData += '<td>' + val.time + '</td></tr>';
            });
            $('.js-fluids-out-tbody').empty().html(trData);

            var trData2 = '';
            $.each(data.input, function(i, val) {
                trData2 += '<tr data-fldid="' + val.fldid + '">';
                trData2 += '<td>' + val.flditem + '</td>';
                trData2 += '<td>' + val.fldreportquanti + '</td>';
                trData2 += '<td>' + val.fldfluid + '</td>';
                trData2 += '<td>' + val.fldenergy + '</td>';
                trData2 += '<td>' + val.time + '</td></tr>';
            });
            $('.js-fluids-intake-tbody').empty().html(trData2);
            calc_total_ml();
            calc_total_energy_fluid();
        }
    });
}

function calc_total_energy_fluid() {
    var totalML = 0;
    var totalKCL = 0;
    $.each($('.js-fluids-intake-tbody tr'), function(i, e) {
        totalML += Number($(e).find('td:nth-child(3)').text().trim());
        totalKCL += Number($(e).find('td:nth-child(4)').text().trim());
    });

    $('#js-inout-total-fluid').val(totalML);
    $('#js-inout-total-energy').val(totalKCL);
}

// calculate total ml for out fluid
function calc_total_ml() {
    var totalML = 0;
    $.each($('.js-fluids-out-tbody tr td:nth-child(2)'), function(i, e) {
        totalML += Number($(e).text().trim());
    });

    $('#js-total-ml').val(totalML);
}

$(document).on('click', '.js-fluids-out-tbody tr', function () {
    selected_td('.js-fluids-out-tbody tr', this);
});

$(document).on('click', '.js-fluids-out-tbody tr td:nth-child(2)', function() {
    $('#js-output-change-volumn-input').val($(this).text().trim());
    currentElem = this;
    $('#js-output-change-volumn-modal').modal('show');
});

$('#js-output-change-volumn-btn-modal').click(function() {
    var volumn = $('#js-output-change-volumn-input').val();
    $.ajax({
        url: baseUrl + '/inpatient/inout/updateVolumn',
        type: "POST",
        data: {fldid: $('.js-fluids-out-tbody tr[is_selected="yes"]').data('fldid'), volumn: volumn},
        dataType: "json",
        success: function (response) {
            if (response.status)
                $(currentElem).text(volumn);

            $('#js-output-change-volumn-modal').modal('hide');
            showAlert(response.message);
        }
    });
});


// list out fluid by date
$('#js-date-inout').change(function() {
    get_in_out_list_data($(this).val());
    $('.pull_right_flex_box').attr('fetchdate', $(this).val());
});

// Open modal for out fluid
$('#js-out-btn').click(function() {
    $('#outFluid').modal('show');
});

$('#js-intake-modal,#outFluid').on('shown.bs.modal', function (e) {
    get_out_fluid_select_data();
    get_in_out_list_data();
    get_diet_planning_type();
});

// show all toggle for out fluid
$('#js-list-fluids').click(function() {
    var toggleData = $('#js-list-onexam').attr('datatype');
    get_in_out_list_data(toggleData);
    $('.pull_right_flex_box').attr('fetchdate', toggleData);

    var newVal = (toggleData === 'all') ? 'today' : 'all';
    $('#js-list-onexam').attr('datatype', newVal);
});

$('.js-inout-intake-radio').change(function() {
    var date = $('.pull_right_flex_box').attr('fetchdate');
    if ($('.js-inout-intake-radio:checked').val() == 'food')
        get_in_out_list_data(date);
    else {
        $.ajax({
            url: baseUrl + '/inpatient/inout/getMedicineList',
            type: "GET",
            data: {date: date},
            dataType: "json",
            success: function (data) {
                var trData2 = '';
                $.each(data, function(i, val) {
                    trData2 += '<tr data-fldid="' + val.fldid + '">';
                    trData2 += '<td>' + val.flditem + '</td>';
                    trData2 += '<td>' + val.fldvalue + '</td>';
                    trData2 += '<td>0</td>';
                    trData2 += '<td>0</td>';
                    trData2 += '<td>' + val.time + '</td></tr>';
                });
                $('.js-fluids-intake-tbody').empty().html(trData2);
                calc_total_energy_fluid();
            }
        });
    }
});

// save data for out fluid
$('#js-out-fluid-save').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/saveOutFluid',
        type: "POST",
        data: {
            encounter_id: $('#encounter_id').val(),
            item: $('#js-out-fluid-option').val(),
            quantative: $('#js-quantative').val()
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var trData = '<tr data-fldid="' + val.fldid + '">';
                trData += '<td>' + val.flditem + '</td>';
                trData += '<td>' + val.fldreportquanti + '</td>';
                trData += '<td>' + val.time + '</td></tr>';
                $('.js-fluids-out-tbody').append(trData);

                $('#outFluid').modal('hide');
                calc_total_ml();
            }
            showAlert(response.message);
        }
    });
});

/*
 * Diet planning
 */

// get select option for diet planning type
function get_diet_planning_type() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/getTypeData',
        type: "GET",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldfoodtype + '">' + option.fldfoodtype + '</option>';
            });
            $('#js-input-type-planned').empty().append(optionData);
            $('#js-intake-type-select').empty().append(optionData);
        }
    });
}

// list data for diet planning
function get_diet_planning_planned_data(date, status) {
    $.ajax({
        url: baseUrl + '/inpatient/inout/getDiets',
        type: "GET",
        data: {date: date, status: status},
        dataType: "json",
        success: function (data) {
            var trData = '';
            var totalFluid = 0;
            var totalEnergy = 0;
            $.each(data, function(i, val) {
                totalFluid += val.fldfluid;
                totalEnergy += val.fldenergy;

                trData += '<tr data-id="' + val.fldid + '">';
                trData += '<td>' + val.type + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td>';
                trData += '<td flddosetime="' + val.flddosetime + '">' + val.time + '</td>';
                trData += '<td class="js-diet-plan-delete" data-status="' + status + '" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                trData += '<td>' + val.status + '</td></tr>';
            });

            var classElem = (status === 'Planned') ? '#js-diet-planning-planned-tbody' : '.js-diet-planning-continued-tbody';
            if (status == 'Continue') {
                $('#js-saved-daily-diet-plan-energy-input').val(totalEnergy);
                $('#js-saved-daily-diet-plan-fluid-input').val(totalFluid);
            }
            $(classElem).empty().html(trData);
        }
    });
}

// Delete diet plan data
$(document).on('click', '.js-diet-plan-delete', function() {
    var currentElem = $(this);
    $.ajax({
        url: baseUrl + '/inpatient/inout/deleteDiet',
        type: "POST",
        data: {fldid: $(this).data('fldid'), status: $(this).data('status')},
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $(currentElem).closest('tr').remove();
            }
            showAlert(response.message);
        }
    });
});

// Firsttab : On refresh click
$('#js-new-daily-plan-btn').click(function() {
    var date = $('#js-input-date-planned').val() || '';
    if (date == '')
        alert('Please select date ');
    else
        get_diet_planning_planned_data($('#js-input-date-planned').val(), 'Planned');
});

function getPlannedOptions(typeSelectorId, planedSelectorId) {
    $.ajax({
        url: baseUrl + '/inpatient/inout/getTypeItems',
        type: "GET",
        data: {
            type: $(typeSelectorId).val(),
        },
        dataType: "json",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldfoodid + '" data-fldfluid="' + option.fldfluid + '" data-fldenergy="' + option.fldenergy + '">' + option.fldfoodid + '</option>';
            });
            $(planedSelectorId).empty().append(optionData);
        }
    });
}

// get item on type change
$('#js-input-type-planned').change(function() {
    getPlannedOptions('#js-input-type-planned', '#js-input-item-planned');
});

$('#js-intake-type-select').change(function() {
    getPlannedOptions('#js-intake-type-select', '#js-intake-item-select');
});

$('#js-intake-update-button').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/addDailyDietPlan',
        type: "POST",
        data: {
            type: $('#js-intake-type-select').val(),
            item: $('#js-intake-item-select').val(),
            dose: $('#js-intake-intake-input').val(),
            status: 'Completed',
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var trData = '<tr>';
                trData += '<td>' + val.flddosetime + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td></tr>';
                $('#js-intake-table-tbody').append(trData);
            }
            showAlert(response.message);
        }
    });
});

$(document).on('change', '#js-input-item-planned', function() {
    $('#js-input-fluid-planned').val($('#js-input-item-planned option:selected').data('fldfluid'));
    $('#js-input-energy-planned').val($('#js-input-item-planned option:selected').data('fldenergy'));
});

// add diet_planning [status: planned]
$('#js-add-diet-planning-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/addDailyDietPlan',
        type: "POST",
        data: {
            date:$('#js-input-date-planned').val(),
            time: $('#js-input-time-planned').val(),
            type: $('#js-input-type-planned').val(),
            item: $('#js-input-item-planned').val(),
            dose: $('#js-input-dose-planned').val(),
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var trData = '<tr data-id="' + val.fldid + '">';
                trData += '<td>' + val.type + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td>';
                trData += '<td flddosetime="' + val.flddosetime + '">' + val.time + '</td>';
                trData += '<td class="js-diet-plan-delete" data-status="Planned" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                trData += '<td>' + val.status + '</td></tr>';
                $('#js-diet-planning-planned-tbody').append(trData);
            }
            showAlert(response.message);
        }
    });
});

// save diet_planning [status: continued]
$('#js-save-diet-planning-btn').click(function() {
    var fldids = $.map($('#js-diet-planning-planned-tbody tr'), function(e) {
        return $(e).data('id');
    });

    $.ajax({
        url: baseUrl + '/inpatient/inout/saveDailyDietPlan',
        type: "POST",
        data: {fldids: fldids},
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-diet-planning-planned-tbody').empty();

                showAlert(response.message);
        }
    });
});

var currentElem = '';
$(document).on('click', '#js-diet-planning-planned-tbody tr', function () {
    selected_td('#js-diet-planning-planned-tbody tr', this);
});

$(document).on('click', '#js-diet-planning-planned-tbody tr td:nth-child(4)', function() {
    $('#js-diet-change-date-modal').modal('show');
    var dateTIme = $(this).attr('flddosetime').split(' ');
    $('#js-diet-change-date-input').val(dateTIme[0]);
    $('#js-diet-change-time-input').val(dateTIme[1]);

    currentElem = this;
});

$('#js-diet-change-date-btn').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    var date = $('#js-diet-change-date-input').val();
    var time = $('#js-diet-change-time-input').val();

    if (fldid != '') {
        var value = date + ' ' + time;
        $.ajax({
            url: baseUrl + '/inpatient/inout/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'flddosetime',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $(currentElem).text(time);
                    $(currentElem).attr('flddosetime', value);
                }
                showAlert(response.message);
                $('#js-diet-change-date-modal').modal('hide');
            }
        });
    }
});

$(document).on('click', '#js-diet-planning-planned-tbody td:nth-child(3)', function() {
    $('#js-inout-change-dose-modal').modal('show');
    $('#js-inout-change-dose-input').val($(this).text().trim());

    currentElem = this;
});

$('#js-inout-change-dose-btn-modal').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    if (fldid != '') {
        var value = $('#js-inout-change-dose-input').val();
        $.ajax({
            url: baseUrl + '/inpatient/inout/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'flddose',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status)
                    $(currentElem).text(value);

                    showAlert(response.message);
                $('#js-inout-change-dose-modal').modal('hide');
            }
        });
    }
});

$(document).on('click', '#js-diet-planning-planned-tbody td:nth-child(6)', function() {
    $('#js-inout-status-modal').modal('show');
    $('#js-inout-status-select option').attr('selected', false);
    $('#js-inout-status-select option[value="' + $(this).text().trim() + '"]').attr('selected', false);

    currentElem = this;
});

$('#js-inout-status-save-modal').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    if (fldid != '') {
        var value = $('#js-inout-status-select').val();
        $.ajax({
            url: baseUrl + '/inpatient/inout/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'fldstatus',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status)
                    $(currentElem).text(value);

                    showAlert(response.message);
                $('#js-inout-status-modal').modal('hide');
            }
        });
    }
});


// Second tab : On refresh click
$('#js-saved-daily-plan-btn').click(function() {
    get_diet_planning_planned_data($('#js-input-date-saved').val(), 'Continue');
});
$('a[href="#dietsaved"]').click(function() {
    get_diet_planning_planned_data($('#js-input-date-saved').val(), 'Continue');
});

/*
 * InTake
 */

// list data for diet planning
$('input[name="type"]').change(function() {
    var data = {status: 'Continue'};

    if ($('input[name="type"]:checked').val() == 'packages')
        data.flddosecode = 'set_null';

    $.ajax({
        url: baseUrl + '/inpatient/inout/getDiets',
        type: "GET",
        data: data,
        dataType: "json",
        success: function (data) {
            var options = '';
            $.each(data, function(i, val) {
                options += '<option value="' + val.fldid + '" data-datetime="' + val.flddosetime + '" data-particulars="' + val.particulars + '" data-dose="' + val.dose + '">' + val.time + ' | ' + val.particulars + '</option>';
            });
            $('#js-intake-list-select').empty().html(options);
        }
    });
});

$('#js-intake-continue-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/setComplete',
        type: "POST",
        data: {fldids: $('#js-intake-list-select').val()},
        dataType: "json",
        success: function (data) {
            if (data.status) {
                var trData = '';
                $.each($('#js-intake-list-select option:selected'), function(i, elem) {
                    var vall = $(this).val();

                    trData += '<tr data-fldid="' + vall + '">';
                    trData += '<td>' + $(elem).data('datetime') + '</td>';
                    trData += '<td>' + $(elem).data('particulars') + '</td>';
                    trData += '<td>' + $(elem).data('dose') + '</td>';
                    trData += '</tr>';

                    $(this).remove();
                });
                $('#js-intake-table-tbody').append(trData);
            }
            showAlert(data.message);
        }
    });

    $('#js-intake-table-tbody').empty().html(trData);
});

$('#js-intake-save-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/inout/saveIntake',
        type: "POST",
        data: {fldids: $('#js-intake-list-select').val()},
        dataType: "json",
        success: function (data) {
            if (data.status) {
                $('#js-intake-table-tbody').empty();
            }
            showAlert(data.message);
        }
    });
});

$(document).on('click', '.js-fluids-intake-tbody tr td:nth-child(2)', function() {
    $('#js-inout-fldid-input').val($(this).closest('tr').data('fldid'));
    $('#js-inout-change-dose-input').val($(this).text().trim());

    $('#js-inout-change-dose-modal').modal('show');
});

$('#js-inout-change-dose-btn').click(function() {
    var flddose = $('#js-inout-change-dose-input').val() || '';
    var fldid = $('#js-inout-fldid-input').val();

    if (flddose !== '') {
        $.ajax({
            url: baseUrl + '/inpatient/inout/updateDoseRate',
            type: "POST",
            data: {
                fldid: fldid,
                flddose : $('#js-inout-change-dose-input').val(),
                type : $('.js-inout-intake-radio:checked').val(),
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $('.js-fluids-intake-tbody tr[data-fldid="' + fldid + '"] td:nth-child(2)').text(response.flddose);
                }
                showAlert(response.message);
                $('#js-inout-change-dose-modal').modal('hide');
            }
        });
    } else
        alert('Dose cannot be empty.');
});

$('#js-diet-planning-export-btn-modal').click(function() {
    var date = $('#js-input-date-planned').val() || '';
    var url = baseUrl + '/inpatient/inout/getDietsPdf?status=Planned';
    if (date != '')
        url += '&date=' + date;

    window.open(url, '_blank');
});

$(document).on('click', '.notes__table_list tr', function () {
    selected_td('.notes__table_list tr', this);
});

/**
* End Functions for InOut
*/


/**
* Start Functions for Labs
*/
$('div.accordion-nav ul li a[data-target="#labs"]').click(function() {
    get_lab_option_data();
});

$('.js-lab-option-radio').change(function() {
    get_lab_option_data()
});

function get_lab_option_data() {
    $.ajax({
        url: baseUrl + '/inpatient/labs/getQuantiQualiData',
        type: "GET",
        success: function (response) {
            var elementData = '';
            $.each(response, function(i, option) {
                elementData += '<option value="' + option.fldtestid + '">' + option.fldtestid + '</option>';
            });
            $('#js-labs-select-options').empty().html(elementData);
        }
    });
}

function get_lab_table_data(dataType) {
    $.ajax({
        url: baseUrl + '/inpatient/labs/getTestsData',
        type: "GET",
        data: {dataType: dataType},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                var abnormalVal = get_abnoraml_btn(val.fldabnormal);
                var observationVal = (val.fldreportquali) ? val.fldreportquali : '';
                trData += '<tr data-id="' + val.fldid + '">';
                trData += '<td>' + val.fldsampletype + '</td>';
                trData += '<td>' + dataType + '</td>';
                trData += '<td>' + val.fldstatus + '</td>';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + observationVal + '</td>';
                trData += '<td>' + val.fldtime_sample + '</td>';
                trData += '<td>' + val.fldtime_report + '</td></tr>';
            });

            $('#js-labs-tests-tbody').empty().html(trData);
        }
    });
}

$(document).on('change', '#js-labs-select-options', function() {
    get_lab_table_data($(this).val());
})

/**
* End Functions for Labs
*/


/**
* Start Functions for Prog
*/

function get_time(date) {
    toggleProgDisable(true);
    $.ajax({
        url: baseUrl + '/inpatient/prog/getTime',
        type: "GET",
        data: {date: date},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                trData += '<tr data-fldid="' + val.fldid + '"><td class="td_number">' + (i+1) + '</td><td class="td_input js-prog-td-data">' + val.time + '</td></tr>';
            });

            $('#js-prog-time-tbody').empty().html(trData);
        }
    });
}

function get_exam_select_data() {
    $.ajax({
        url: baseUrl + '/inpatient/prog/getExaminationSelectData',
        type: "GET",
        success: function (data) {
            var options = '';
            $.each(data, function(i, val) {
                var data_option = 'text';
                if (val.fldoption == 'Single Selection')
                    data_option = 'select';
                else if (val.fldtype == 'Quantitative')
                    data_option = 'number';

                options += '<option value="' + val.fldexamid + '" data-option="' + data_option + '">' + val.fldexamid + '</option>';
            });

            $('#js-prog-exam-select').empty().html(options);
        }
    });
}

$('div.accordion-nav ul li a[data-target="#prog"]').click(function() {
    get_time('');
    get_exam_select_data();
    getPatientColor('#js-prog-main-color');
});

$('#js-prog-date-input').change(function() {
    get_time($(this).val());
});

$('#js-prog-add-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/prog/addTime',
        type: "POST",
        success: function (response) {
            if (response.message) {
                var trData = '<tr data-fldid="' + response.data.fldid + '"><td class="td_number">' + (($('#js-prog-time-tbody tr').length) +1) + '</td><td class="td_input js-prog-td-data">' + response.data.time + '</td></tr>';
                $('#js-prog-time-tbody').append(trData);
            }
            showAlert(response.message)
        }
    });
});

function get_time_data(fldid) {
    $.ajax({
        url: baseUrl + '/inpatient/prog/getTimeData',
        type: "GET",
        data: {
            fldid: fldid,
        },
        dataType: "json",
        success: function (response) {
            CKEDITOR.instances['js-prog-problem-input'].setData(response.fldsubjective);
            CKEDITOR.instances['js-prog-treatment-input'].setData(response.fldobjective);
            CKEDITOR.instances['js-prog-in-output-input'].setData(response.fldassess);
            CKEDITOR.instances['js-prog-planning-text-input'].setData(response.fldassess);
            $('#js-prog-planning-drop-input').val(response.fldproblem);

            var trData = '';
            $.each(response.exams, function(i, exam) {
                var abnormalVal = get_abnoraml_btn(exam.fldabnormal);
                var observationVal = (exam.fldrepquanti != '0') ? exam.fldrepquanti : exam.fldrepquali;

                trData += '<tr data-fldid="' + exam.fldid + '">';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td>' + exam.fldhead + '</td>';
                trData += '<td>' + abnormalVal + '</td>';
                trData += '<td>' + observationVal + '</td>';
                trData += '<td class="js-td-delete" data-fldid="' + exam.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                trData += '<td>' + exam.fldtime + '</td>';
                trData += '<td>' + exam.flduserid + '</td>';
                trData += '</tr>';
            });
            $('#js-prog-exam-tbody').empty().html(trData);
        }
    });
}

$(document).on('click', '.js-prog-td-data', function() {
    toggleProgDisable(false);

    $('.js-prog-td-data').css('background-color', '#ffffff');
    $(this).css('background-color', '#c8dfff');

    $.each($('#js-prog-time-tbody tr'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).closest('tr').attr('is_selected', 'yes');

     get_time_data($(this).closest('tr').data('fldid'));
});

function save_ckbox_data(textData, type) {
    $.ajax({
        url: baseUrl + '/inpatient/prog/addTextData',
        type: "POST",
        data: {
            fldid: $('#js-prog-time-tbody tr[is_selected="yes"]').data('fldid'),
            text: textData,
            type: type
        },
        dataType: "json",
        success: function (response) {
            showAlert(response.message);
        }
    });
}

$('#js-prog-problem-save-btn').click(function() {
    save_ckbox_data(CKEDITOR.instances['js-prog-problem-input'].getData(), 'Problems');
});

$('#js-prog-treatment-save-btn').click(function() {
    save_ckbox_data(CKEDITOR.instances['js-prog-treatment-input'].getData(), 'Treatment');
});

$('#js-prog-in-output-save-btn').click(function() {
    save_ckbox_data(CKEDITOR.instances['js-prog-in-output-input'].getData(), 'Input/Output');
});

$('#js-prog-planning-text-save-btn').click(function() {
    save_ckbox_data(CKEDITOR.instances['js-prog-planning-text-input'].getData(), 'PlanningText');
});

$('#js-prog-planning-drop-save-btn').click(function() {
    save_ckbox_data($('#js-prog-planning-drop-input').val(), 'PlanningDrop');
});

$('#js-prog-onexamination-save-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/prog/saveOnExamData',
        type: "POST",
        data: {
            fldid: $('#js-prog-time-tbody tr[is_selected="yes"]').data('fldid'),
            fld1: $('#js-prog-field1').val(),
            fld2: $('#js-prog-field2').val(),
            fld3: $('#js-prog-field3').val(),
        },
        dataType: "json",
        success: function (response) {
            showAlert(response.message)
        }
    });
});


$('#js-prog-exam-select').change(function() {
    var optionType = $('#js-prog-exam-select option:selected').data('option');
    if (optionType == 'text') {
        $('#js-prog-exam-input-quantity').attr('disabled', true);
        $('#js-prog-exam-input').attr('disabled', false);
        $('#js-prog-exam-input').focus();

        $('#js-prog-exam-span').html('<input type="text" id="js-prog-exam-input" class="form-control">');
    } else if (optionType == 'number') {
        $('#js-prog-exam-span').html('<input type="text" id="js-prog-exam-input" disabled="disabled" class="form-control">');
        $('#js-prog-exam-input-quantity').attr('disabled', false);
        $('#js-prog-exam-input-quantity').focus();
    } else if (optionType == 'select') {
        $('#js-prog-exam-input-quantity').attr('disabled', true);
        $('#js-prog-exam-input').attr('disabled', false);
        $('#js-prog-exam-input').focus();

        $.ajax({
            url: baseUrl + '/inpatient/prog/getExaminationSelectOptions',
            type: "GET",
            data: {examid: $('#js-prog-exam-select option:selected').val()},
            dataType: "json",
            success: function (data) {
                var options = '<select id="js-prog-exam-input" class="form-control">';
                options += '<option value="">--Select--</option>';
                $.each(data, function(i, val) {
                    options += '<option value="' + val.fldanswer + '">' + val.fldanswer + '</option>';
                });
                options += '</select>';

                $('#js-prog-exam-span').html(options);
            }
        });
    }
});

$('#js-prog-exam-add-btn').click(function() {

    var examinationid = $('#js-prog-exam-select option:selected').val() || '';
    var text = $('#js-prog-exam-input').val() || '';
    var quantity = $('#js-prog-exam-input-quantity').val() || '';
    var fldid = $('#js-prog-time-tbody tr[is_selected="yes"]').data('fldid') || '';

    if (examinationid != '' && fldid != '') {
        $.ajax({
            url: baseUrl + '/inpatient/prog/saveExaminationData',
            type: "POST",
            data: {
                examinationid: examinationid,
                text: text,
                quantity: quantity,
                fldid: fldid,
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var exam = response.data;
                    var abnormalVal = get_abnoraml_btn(exam.fldabnormal);
                    var observationVal = (exam.fldrepquanti != '0') ? exam.fldrepquali : exam.fldrepquali;
                    observationVal = (observationVal != 'null') ? observationVal : '';

                    var trData = '<tr data-fldid="' + exam.fldid + '">';
                    trData += '<td>' + ($('#js-prog-exam-tbody tr').length+1) + '</td>';
                    trData += '<td>' + (exam.fldhead ? exam.fldhead : '') + '</td>';
                    trData += '<td>' + abnormalVal + '</td>';
                    trData += '<td>' + observationVal + '</td>';
                    trData += '<td class="js-td-delete" data-fldid="' + exam.fldid + '"><i class="ri-delete-bin-5-fill"></i></td>';
                    trData += '<td>' + exam.fldtime + '</td>';
                    trData += '<td>' + exam.flduserid + '</td>';
                    trData += '</tr>';

                    $('#js-prog-exam-tbody').append(trData);
                }
                showAlert(response.message);
            }
        });
    } else {
        var error = '';
        if (fldid != '')
            error += 'Please select time to save examination<br>';
        if (examinationid != '')
            error += 'The Examination is required.<br>';
        alert(error);
    }
});

// Essential Examination functions

function toggleProgDisable(isDisable) {
    if (isDisable) {
		CKEDITOR.instances['js-prog-problem-input'].setData('');
		CKEDITOR.instances['js-prog-treatment-input'].setData('');
		CKEDITOR.instances['js-prog-in-output-input'].setData('');
		CKEDITOR.instances['js-prog-planning-text-input'].setData('');
        $('#js-prog-planning-drop-input').val('');

        CKEDITOR.instances['js-prog-problem-input'].setReadOnly();
        $('#js-prog-problem-save-btn').attr('disabled', true);
        $('#js-prog-exam-add-btn').attr('disabled', true);
        CKEDITOR.instances['js-prog-treatment-input'].setReadOnly();
        $('#js-prog-treatment-save-btn').attr('disabled', true);
        CKEDITOR.instances['js-prog-in-output-input'].setReadOnly();
        $('#js-prog-in-output-save-btn').attr('disabled', true);
        CKEDITOR.instances['js-prog-planning-text-input'].setReadOnly();
        $('#js-prog-planning-text-save-btn').attr('disabled', true);
        $('#js-prog-planning-drop-input').attr('disabled', true);
        $('#js-prog-planning-drop-save-btn').attr('disabled', true);
    } else {
        CKEDITOR.instances['js-prog-problem-input'].setReadOnly(false);
        $('#js-prog-problem-save-btn').attr('disabled', false);
        $('#js-prog-exam-add-btn').attr('disabled', false);
        CKEDITOR.instances['js-prog-treatment-input'].setReadOnly(false);
        $('#js-prog-treatment-save-btn').attr('disabled', false);
        CKEDITOR.instances['js-prog-in-output-input'].setReadOnly(false);
        $('#js-prog-in-output-save-btn').attr('disabled', false);
        CKEDITOR.instances['js-prog-planning-text-input'].setReadOnly(false);
        $('#js-prog-planning-text-save-btn').attr('disabled', false);
        $('#js-prog-planning-drop-input').attr('disabled', false);
        $('#js-prog-planning-drop-save-btn').attr('disabled', false);
    }
}

$(document).on('click', '#js-prog-exam-tbody tr td:nth-child(4)', function() {
    updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
});

$('#js-prog-planning-textfile-input').change(function() {
    var data = new FormData();
    if ($(this)[0].files !== undefined && $(this)[0].files.length > 0) {
        var image = $(this)[0].files[0];
        data.append('fldtext', image);
    }

    $.ajax({
        url: baseUrl + '/inpatient/prog/readtextfile',
        type: "POST",
        enctype: 'multipart/form-data',
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (response) {
            var optionData = '<select id="js-prog-planning-drop-input" class="select-01 form-input"><option>-- Select --<option>';
            $.each(response, function(i, val) {
                optionData += '<option>' + val + '<option>';
            });
            optionData += "</select>"

            $('#js-prog-planning-input-parent').html(optionData);
        }
    });
});

/**
* End Functions for Prog
*/


/**
* Start Functions for Fluids
*/

function get_fluids_data(date) {
    $.ajax({
        url: baseUrl + '/inpatient/fluids/getFluids',
        type: "GET",
        data: {date: date},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                trData += '<tr class="js-fluids-medicine-tr" data-fldid="' + val.fldid + '">';
                trData += '<td class="js-fluids-medicine-tr-tds">' + (i+1) + '</td>';
                trData += '<td class="js-fluids-medicine-tr-tds">' + val.fldstarttime + '</td>';
                trData += '<td class="js-fluids-item-name js-fluids-medicine-tr-tds">' + val.flditem + '</td>';
                trData += '<td class="js-fluids-medicine-tr-tds">' + val.flddose + '</td>';
                trData += '<td class="js-fluids-medicine-tr-tds">' + val.fldfreq + '</td>';
                trData += '<td class="js-fluids-medicine-tr-tds">' + val.flddays + '</td>';
                trData += '<td class="js-fluids-medicine-status-td">' + val.fldcurval + '</td>';
                trData += '<td class="js-fluids-medicine-particular-td"><i class="fas fa-play"></i></td></tr>';
            });
            $('#js-fluids-medicines-tbody').empty().html(trData);
            $('#js-fluids-particulars-tbody').html('');
        }
    });
}

// Get list of fluids
$('div.accordion-nav ul li a[data-target="#fluids"]').click(function() {
    get_fluids_data($('#js-fluids-date').val());
});

// get list of fluids on show all toggle
$('#js-fluids-showall-btn').click(function() {
    var toggleData = $('#js-fluids-showall-btn').attr('datatype');
    get_fluids_data(toggleData);
    $('#js-fluids-info-top-btn').attr('fetchdate', toggleData);

    var newVal = (toggleData === 'all') ? 'today' : 'all';
    $('#js-fluids-showall-btn').attr('datatype', newVal);
});

//  get list of fluids by date
$('#js-fluids-date').change(function() {
    get_fluids_data($(this).val());
    $('#js-fluids-info-top-btn').attr('fetchdate', $(this).val());
});

// Show popup modal to add particular in fluids data
$(document).on('click', '.js-fluids-medicine-particular-td', function() {
    if ($(this).closest('tr').find('.js-fluids-medicine-status-td').text().trim() == 'Continue') {
        $('#js-fluid-play-fldid-input').val($(this).closest('tr').data('fldid'));
        $('#js-fluids-play-modal').find('h6.modal-title').text($(this).closest('tr').find('.js-fluids-medicine-item-td').text());
        $('#js-fluids-play-modal').modal('show');
    }
});

// Add particulars with modal popup
$('#js-fluid-play-save-modal').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/fluids/saveParticulars',
        type: "POST",
        data: {
            doseno: $('#js-fluid-play-fldid-input').val(),
            dosevalue: $('#js-fluids-dosevalue-input').val()
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var fluidname = $('#js-fluids-medicines-tbody tr[is_selected="yes"]').find('.js-fluids-item-name').text().trim();
                var trData = '<tr class="js-fluids-particular-tr" data-fldid="' + response.data.fldid + '">';
                trData += '<td>' + (($('#js-fluids-particulars-tbody tr').length)+1) + '</td>';
                trData += '<td>' + fluidname + '</td>';
                trData += '<td>' + response.data.fldvalue + '</td>';
                trData += '<td>' + response.data.fldunit + '</td>';
                trData += '<td>' + response.data.fldfromtime + '</td>';
                trData += '<td>&nbsp;</td>';
                trData += '<td class="js-fluids-particular-stop-td"><i class="fas fa-stop"></i></td></tr>';

                $('#js-fluids-particulars-tbody').append(trData);
            }
            showAlert(response.message);
            $('#js-fluids-play-modal').modal('hide');
        }
    });
});

// Get particulars list of fluids
$(document).on('click', '.js-fluids-medicine-tr-tds', function() {
    $('#js-fluids-medicines-tbody tr').css('background-color', '#ffffff');
    $(this).closest('tr').css('background-color', '#c8dfff');

    $.each($('#js-fluids-medicines-tbody tr'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).closest('tr').attr('is_selected', 'yes');

    var fluidname = $(this).closest('tr').find('.js-fluids-item-name').text().trim();
    $.ajax({
        url: baseUrl + '/inpatient/fluids/getFluidParticulars',
        type: "GET",
        data: {flddoseno: $(this).closest('tr').data('fldid')},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                var stopHtml = val.fldtotime ? '<td>&nbsp;</td></tr>' : '<td class="js-fluids-particular-stop-td"><i class="fas fa-stop"></i></td></tr>';
                trData += '<tr class="js-fluids-particular-tr" data-fldid="' + val.fldid + '">';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td>' + fluidname + '</td>';
                trData += '<td>' + val.fldvalue + '</td>';
                trData += '<td>' + val.fldunit + '</td>';
                trData += '<td>' + val.fldfromtime + '</td>';
                trData += '<td>' + (val.fldtotime ? val.fldtotime : '') + '</td>';
                trData += stopHtml;
            });
            $('#js-fluids-particulars-tbody').empty().html(trData);
        }
    });
});

// Show modal to change status of fluids
$(document).on('click', '.js-fluids-medicine-status-td', function() {
    $('#js-fluid-status-select option').attr('selected', false);
    $('#js-fluid-status-select option[value="' + $(this).text().trim() + '"]').attr('selected', true);
    $('#js-fluids-status-text-display').text($(this).closest('tr').find('td.js-fluids-item-name').text().trim());
    $('#js-fluid-fldid-input').val($(this).closest('tr').data('fldid'));
    $('#js-fluids-status-modal').modal('show');
});

// change status with modal popup
$('#js-fluid-status-save-modal').click(function() {
	var fldid = $('#js-fluid-fldid-input').val();
	var status = $('#js-fluid-status-select').val();
    $.ajax({
        url: baseUrl + '/inpatient/fluids/changeFluidStatus',
        type: "POST",
        data: {
            fldid: fldid,
            status: status
        },
        dataType: "json",
        success: function (response) {
        	if (response.status) {
        		$('#js-fluids-medicines-tbody tr[data-fldid="' + fldid + '"] td.js-fluids-medicine-status-td').text(status);
            	$('#js-fluids-status-modal').modal('hide');
        	}
            showAlert(response.message);
        }
    });
});

// stop particular
$(document).on('click', '.js-fluids-particular-stop-td', function() {
    var trElem = $(this).closest('tr');
    var fldid = $(trElem).data('fldid');
    var em = $(trElem).find('td:nth-child(6)');
    $.ajax({
        url: baseUrl + '/inpatient/fluids/stopParticular',
        type: "POST",
        data: {fldid: fldid},
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $(em).text(response.endtime);
                $(trElem).find('td:nth-child(7)').html('').removeClass('js-fluids-particular-stop-td');
            }
            showAlert(response.message);
            $('#js-fluids-play-modal').modal('hide');
        }
    });
});

$(document).on('click', '#js-fluids-medicines-tbody td:nth-child(2)', function() {
    $('#js-fluids-change-date-modal').modal('show');
    var dateTIme = $(this).text().split(' ');
    $('#js-fluids-change-date-input').val(dateTIme[0]);
    $('#js-fluids-change-time-input').val(dateTIme[1]);
});
$(document).on('click', '#js-fluids-medicines-tbody td:nth-child(4)', function() {
    $('#js-fluids-change-dose-modal').modal('show');
    $('#js-fluids-change-dose-input').val($(this).text());
});

$('#js-fluid-change-date-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/fluids/updateFluidData',
        type: "POST",
        data: {
            fldid: $('#js-fluids-medicines-tbody tr[is_selected="yes"]').data('fldid'),
            date : $('#js-fluids-change-date-input').val(),
            time : $('#js-fluids-change-time-input').val()
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $('#js-fluids-medicines-tbody td:nth-child(2)').text(response.data.fldstarttime);
            }
            showAlert(response.message);
            $('#js-fluids-change-date-modal').modal('hide');
        }
    });
});

$('#js-fluid-change-dose-btn').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/fluids/updateFluidData',
        type: "POST",
        data: {
            fldid: $('#js-fluids-medicines-tbody tr[is_selected="yes"]').data('fldid'),
            flddose : $('#js-fluids-change-dose-input').val(),
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $('#js-fluids-medicines-tbody td:nth-child(4)').text(response.data.flddose);
            }
            showAlert(response.message);
            $('#js-fluids-change-dose-modal').modal('hide');
        }
    });
});


$('#js-fluids-bluecompact-top-btn').click(function() {
    var fluiditem = $('#js-fluids-medicines-tbody tr td.js-fluids-item-name').text().trim() || '';
    if (fluiditem !== '') {
        $.ajax({
            url: baseUrl + '/inpatient/fluids/getCompatibilityInformation',
            type: "GET",
            data: {fluiditem: fluiditem},
            dataType: "json",
            success: function (response) {
                $('#js-fluids-bluecompact-title').text(fluiditem);
                $('#js-compatible-fluids-div').text(response.compatibleFluids || '');
                $('#js-incompatible-fluids-div').text(response.incompatibleFluids || '');
                $('#js-compatible-drugs-div').text(response.compatibleDrugs || '');
                $('#js-incompatible-drugs-div').text(response.incompatibleDrugs || '');

                $('#js-fluids-bluecompact-modal').modal('show');
            }
        });
    } else
        alert('Select fluid first.');
});

$(document).on('click', '#js-fluids-info-top-btn', function() {
    var url = baseUrl + '/inpatient/fluids/generatePDF?date=' + $('#js-fluids-info-top-btn').attr('fetchdate');
    window.location.href = url;
});


/**
* End Functions for Fluids
*/



/**
* End Functions for Routine
*/

$(document).on('click', '#js-routine-tbody tr', function() {
    $('#js-routine-tbody tr').css('background-color', '#ffffff');
    $(this).css('background-color', '#c8dfff');

    $.each($('#js-routine-tbody tr'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).attr('is_selected', 'yes');
});

$('.js-routine-labels-btn').click(function() {
    var fldid = $('#js-routine-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateLabelPDF?fldid=' + fldid;
        window.location.href = url;
    }
});

$('.js-routine-druginfo-btn').click(function() {
    var fldid = $('#js-routine-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateDrugInfoPDF?fldid=' + fldid;
        window.location.href = url;
    }
});

$('.js-routine-review-btn').click(function() {
    var fldid = $('#js-routine-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateReviewPDF?fldid=' + fldid;
        window.location.href = url;
    }
});


/**
* End Functions for Routine
*/



/**
* Start Functions for Stat/PRN
*/

$(document).on('click', '#js-statprn-tbody tr', function() {
    $('#js-statprn-tbody tr').css('background-color', '#ffffff');
    $(this).css('background-color', '#c8dfff');

    $.each($('#js-statprn-tbody tr'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).attr('is_selected', 'yes');
});

$('.js-statprn-labels-btn').click(function() {
    var fldid = $('#js-statprn-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateLabelPDF?fldid=' + fldid;
        window.location.href = url;
    }
});

$('.js-statprn-druginfo-btn').click(function() {
    var fldid = $('#js-statprn-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateDrugInfoPDF?fldid=' + fldid;
        window.location.href = url;
    }
});

$('.js-statprn-review-btn').click(function() {
    var fldid = $('#js-statprn-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var url = baseUrl + '/inpatient/stat/generateReviewPDF?fldid=' + fldid;
        window.location.href = url;
    }
});

/**
* End Functions for Stat/PRN
*/



/**
* Start Functions for Plan
*/

function get_plan_data(date) {
    $.ajax({
        url: baseUrl + '/inpatient/plan/getPlans',
        type: "GET",
        data: {date: date},
        dataType: "json",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                trData += get_tr_elem_plan(val, false);
            });
            $('#js-plan-tbody').empty().html(trData);

            $('#js-plan-problem-statement-input').val('');
		    CKEDITOR.instances['js-plan-subjective-parameter-textarea'].setData('');
		    CKEDITOR.instances['js-plan-objective-parameter-textarea'].setData('');
		    CKEDITOR.instances['js-plan-assessment-textarea'].setData('');
		    CKEDITOR.instances['js-plan-planning-textarea'].setData('');
        }
    });
}

// Get list of fluids
$('div.accordion-nav ul li a[data-target="#plan"]').click(function() {
    get_plan_data($('#js-plan-date').val());

    $('#js-plan-problem-statement-input').val('');
    $('#js-plan-subjective-parameter-textarea').val('');
    $('#js-plan-objective-parameter-textarea').val('');
    $('#js-plan-assessment-textarea').val('');
    $('#js-plan-planning-textarea').val('');
});

//  get list of plan by date
$('#js-plan-date').change(function() {
    get_plan_data($(this).val());
});

$(document).on('click', '.js-plan-tbody-tr-data', function() {
    $('.js-plan-tbody-tr-data').css('background-color', '#ffffff');
    $(this).closest('tr').css('background-color', '#c8dfff');

    $.each($('.js-plan-tbody-tr-data'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).attr('is_selected', 'yes');

    $('#js-plan-problem-statement-input').val($(this).find('td:first-child').data('fldproblem'));

    CKEDITOR.instances['js-plan-subjective-parameter-textarea'].setData($(this).find('td:first-child').data('fldsubjective'));
    CKEDITOR.instances['js-plan-objective-parameter-textarea'].setData($(this).find('td:first-child').data('fldobjective'));
    CKEDITOR.instances['js-plan-assessment-textarea'].setData($(this).find('td:first-child').data('fldassess'));
    CKEDITOR.instances['js-plan-planning-textarea'].setData($(this).find('td:first-child').data('fldplan'));
});

function get_tr_elem_plan(data, tr_data_only) {
    var tr_datas = ' data-fldproblem="' + data.fldproblem + '"';
    tr_datas += ' data-fldid="' + data.fldid + '"';
    tr_datas += ' data-fldsubjective="' + data.fldsubjective + '"';
    tr_datas += ' data-fldobjective="' + data.fldobjective + '"';
    tr_datas += ' data-fldassess="' + data.fldassess + '"';
    tr_datas += ' data-fldplan="' + data.fldplan + '"';

    if (tr_data_only)
        return tr_datas;

    return '<tr class="js-plan-tbody-tr-data"><td ' + tr_datas + '>' + data.fldproblem + '</td></tr>'
}

function add_update_plan(post_data) {
    if (post_data.hasOwnProperty('fldid') && post_data.fldid === '')
        alert('Invalid data selected for update')
    else {
        $.ajax({
            url: baseUrl + '/inpatient/plan/saveUpdatePlan',
            type: "POST",
            data: post_data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    if (post_data.hasOwnProperty('fldid')) {
                        var selected_elem = $('#js-plan-tbody tr[is_selected="yes"]');

                        $(selected_elem).removeAttr('data-fldproblem');
                        $(selected_elem).removeAttr('data-fldid');
                        $(selected_elem).removeAttr('data-fldsubjective');
                        $(selected_elem).removeAttr('data-fldobjective');
                        $(selected_elem).removeAttr('data-fldassess');
                        $(selected_elem).removeAttr('data-fldplan');

                        var tr_datas = get_tr_elem_plan(response.data, true);
                        $('#js-plan-tbody tr[is_selected="yes"]').html('<td ' + tr_datas + '>' + response.data.fldproblem + '</td>');
                    }
                    else
                        $('#js-plan-tbody').append(get_tr_elem_plan(response.data, false));
                }
                showAlert(response.message);
            }
        });
    }
}

$('#js-plan-add-btn').click(function() {
    add_update_plan({
        fldproblem: $('#js-plan-problem-statement-input').val(),
        fldsubjective: CKEDITOR.instances['js-plan-subjective-parameter-textarea'].getData(),
        fldobjective: CKEDITOR.instances['js-plan-objective-parameter-textarea'].getData(),
        fldassess: CKEDITOR.instances['js-plan-assessment-textarea'].getData(),
        fldplan: CKEDITOR.instances['js-plan-planning-textarea'].getData()
    });
});
$('#js-plan-edit-btn').click(function() {
    add_update_plan({
        fldid: $('#js-plan-tbody tr[is_selected="yes"] td:first-child').data('fldid'),
        fldproblem: $('#js-plan-problem-statement-input').val(),
        fldsubjective: CKEDITOR.instances['js-plan-subjective-parameter-textarea'].getData(),
        fldobjective: CKEDITOR.instances['js-plan-objective-parameter-textarea'].getData(),
        fldassess: CKEDITOR.instances['js-plan-assessment-textarea'].getData(),
        fldplan: CKEDITOR.instances['js-plan-planning-textarea'].getData()
    });
});


/**
* End Functions for Plan
*/

/**
* Start Functions for General
*/
function get_symptom_data() {
    $.ajax({
        url: baseUrl + '/inpatient/general/getPatientSymptoms',
        type: "GET",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                var cssclass = 'js-general-symptom-tr-tds';
                var csscolor = '000';
                var flddetail = val.flddetail || '';

                if (val.fldreportquanti == 0) {
                    cssclass += ' js-general-symptom-lock-tds';
                    csscolor = '70d470';
                }
                trData += '<tr class="js-general-symptom-tr" data-fldid="' + val.fldid + '" flddetail="' + flddetail + '">';
                trData += '<td class="js-general-symptom-tr-tds">' + (i+1) + '</td>';
                trData += '<td class="js-general-symptom-tr-tds">' + val.fldtime + '</td>';
                trData += '<td class="js-general-symptom-tr-tds">' + val.flditem + '</td>';
                trData += '<td class="js-general-symptom-status-td">' + val.fldreportquali + '</td>';
                trData += '<td class="js-general-symptom-tr-tds">' + val.fldreportquanti + '</td>';
                trData += '<td class="' + cssclass + '" style="color: #' + csscolor + ';"><i class="fas fa-lock"></i></td>';
                trData += '<td class="js-general-symptom-tr-tds" style="color: #6682d0;"><i class="fas fa-info-circle"></i></td></tr>';
            });
            $('#js-general-symptoms-tbody').empty().html(trData);
        }
    });
}

function get_symptom_list_data() {
    $.ajax({
        url: baseUrl + '/inpatient/general/getSymptomsList',
        type: "GET",
        success: function (data) {
            var trData = '';
            $.each(data, function(i, val) {
                trData += '<tr><td style="background-color: #fff;"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="' + val.fldsymptom + '" value="' + val.fldsymptom + '"><label class="custom-control-label" for="' + val.fldsymptom + '">' + val.fldsymptom + '</label></div></td></tr>';
            });
            $('#js-general-symptoms-list-tbody').empty().html(trData);
        }
    });
}

function get_status(fldhead, id) {
    $.ajax({
        url: baseUrl + '/inpatient/general/getStatus',
        type: "GET",
        data: {fldhead: fldhead},
        success: function (data) {
            $(id + ' option[value="' + data.fldrepquali + '"]').attr('selected', true);
        }
    });
}

// Get list of fluids
$('div.accordion-nav ul li a[data-target="#general"]').click(function() {
    get_symptom_data();
    get_symptom_list_data();

    get_status('Hepatic Status', '#js-general-heptatic-status-select');
    get_status('Pregnancy/Breast feeding', '#js-general-pregnancy-status-select');

    var gender = $('#js-inpatient-gender-input').text().trim().toLowerCase();
    if (gender == 'male')
    	$('#js-general-pregnancy-status-select').attr('disabled', true);
});

$(document).on('click', '.js-general-symptom-tr-tds', function() {
    $('#js-general-symptoms-tbody tr').css('background-color', '#ffffff');
    $(this).closest('tr').css('background-color', '#c8dfff');

    $.each($('#js-general-symptoms-tbody tr'), function(i,e) {
        $(e).attr('is_selected', 'no');
    });
    $(this).closest('tr').attr('is_selected', 'yes');
});

// Show modal to add symptom
$('#js-general-add-symptom-btn').click(function() {
    $(":checkbox").prop('checked', false).parent().removeClass('active');
    $('#js-general-symptoms-modal').modal('show');
});

$('#js-general-symptoms-save-modal').click(function() {
    var symptoms = [];
    $.each($('input.custom-control-input[type="checkbox"]:checked'), function(i, e) {
        symptoms.push($(e).val());
    });

    if (symptoms.length !== 0) {
	    $.ajax({
	        url: baseUrl + '/inpatient/general/saveSymptoms',
	        type: "POST",
	        data: { symptoms: symptoms},
	        dataType: "json",
	        success: function (response) {
	            if (response.status) {
	                var length = $('#js-general-symptoms-tbody tr').length;
	                var trData = '';
	                $.each(response.data.symptoms, function(i, val) {
	                    trData += '<tr class="js-general-symptom-tr" data-fldid="' + val.fldid + '">';
	                    trData += '<td class="js-general-symptom-tr-tds">' + (++length) + '</td>';
	                    trData += '<td class="js-general-symptom-tr-tds">' + response.data.date + '</td>';
	                    trData += '<td class="js-general-symptom-tr-tds">' + val.flditem + '</td>';
	                    trData += '<td class="js-general-symptom-status-td"></td>';
	                    trData += '<td class="js-general-symptom-tr-tds">0</td>';
	                    trData += '<td class="js-general-symptom-tr-tds js-general-symptom-lock-tds" style="color: #70d470;"><i class="fas fa-lock"></i></td>';
	                    trData += '<td class="js-general-symptom-tr-tds" style="color: #6682d0;"><i class="fas fa-info-circle"></i></td></tr>';
	                });
	                $('#js-general-symptoms-tbody').append(trData);
	                $('#js-general-symptoms-modal').modal('hide');
	            }
	            showAlert(response.message);
	        }
	    });
    } else
    	alert('Please select atleast one symptom.');
});

// Show modal to change status of general
$(document).on('click', '.js-general-symptom-status-td', function() {
    $('#js-general-fldid-input').val($(this).closest('tr').data('fldid'));
    $('#js-general-status-modal').modal('show');
});

$('#js-general-status-save-modal').click(function() {
    var fldid = $('#js-general-fldid-input').val();
    var fldreportquali = $('#js-general-status-select').val();
    $.ajax({
        url: baseUrl + '/inpatient/general/changeSymptomStatus',
        type: "POST",
        data: {
            fldid: fldid,
            fldreportquali: fldreportquali
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $('tr[data-fldid="' + fldid+ '"] td:nth-child(4)').text(fldreportquali);
            }
            showAlert(response.message);
            $('#js-general-status-modal').modal('hide');
        }
    });
});

$(document).on('click', '.js-general-symptom-lock-tds', function() {
    if (confirm("Are you sure you want to resolve this symptom?")) {
        var currentElem = $(this);
        $.ajax({
            url: baseUrl + '/inpatient/general/resolveSymptom',
            type: "POST",
            data: {
                fldid: $(this).closest('tr').data('fldid'),
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $(currentElem).css('color', '#000');
                    $(currentElem).removeClass('js-general-symptom-lock-tds');
                    $(currentElem).closest('tr').find('td:nth-child(5)').text(response.hours);
                }
                showAlert(response.message);
            }
        });
    }
});

$('.js-general-status-save-bth').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/general/saveStatus',
        type: "POST",
        data: {
            status: $(this).closest('div').prev('div').find('select').val(),
            fldhead: $(this).data('fldhead'),
        },
        dataType: "json",
        success: function (response) {
            showAlert(response.message);
        }
    });
});

$('#js-general-symptom-delete-btn').click(function() {
    var fldid = $('#js-general-symptoms-tbody tr[is_selected="yes"]').data('fldid');
    if (fldid !== undefined) {
        $.ajax({
            url: baseUrl + '/inpatient/general/deleteSymptoms',
            type: "POST",
            data: {
                fldid: $('#js-general-symptoms-tbody tr[is_selected="yes"]').data('fldid')
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $('#js-general-symptoms-tbody tr[is_selected="yes"]').remove();
                }
                showAlert(response.message);
            }
        });
    } else
        alert('Please select Symptom to delete.');
});

$('#js-general-freewriting-symptoms-add-btn').click(function() {
    $('#js-general-freewriting-symptoms-modal').modal('show');
    $('#js-general-freewriting-symptoms-input').val('');
});

$('#js-general-freewriting-symptoms-save-btn').click(function() {
    var symptoms = $('#js-general-freewriting-symptoms-input').val()

    if (symptoms !== undefined && symptoms !== '') {
        symptoms = [symptoms];
        $.ajax({
            url: baseUrl + '/inpatient/general/saveSymptoms',
            type: "POST",
            data: { symptoms: symptoms },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var length = $('#js-general-symptoms-tbody tr').length;
                    var trData = '';
                    $.each(response.data.symptoms, function(i, val) {
                        trData += '<tr class="js-general-symptom-tr" data-fldid="' + val.fldid + '">';
                        trData += '<td class="js-general-symptom-tr-tds">' + (++length) + '</td>';
                        trData += '<td class="js-general-symptom-tr-tds">' + response.data.date + '</td>';
                        trData += '<td class="js-general-symptom-tr-tds">' + val.flditem + '</td>';
                        trData += '<td class="js-general-symptom-status-td"></td>';
                        trData += '<td class="js-general-symptom-tr-tds">0</td>';
                        trData += '<td class="js-general-symptom-tr-tds js-general-symptom-lock-tds" style="color: #70d470;"><i class="fas fa-lock"></i></td>';
                        trData += '<td class="js-general-symptom-tr-tds" style="color: #6682d0;"><i class="fas fa-info-circle"></i></td></tr>';
                    });
                    $('#js-general-symptoms-tbody').append(trData);
                    $('#js-general-freewriting-symptoms-input').val('');
                }
                showAlert(response.message);
                $('#js-general-freewriting-symptoms-modal').modal('hide');
            }
        });
    } else
        alert('Symptom cannot be empty.');
});

$(document).on('click', '.js-general-symptom-tr td:nth-child(7)', function() {
    var flddetail = $('.js-general-symptom-tr[is_selected="yes"]').attr('flddetail') || '';

    $('#js-general-flddetail-textarea').val(flddetail);
    $('#js-general-flddetail-modal').modal('show');
});
$('#js-general-flddetail-save-btn').click(function() {
    var flddetail = $('#js-general-flddetail-textarea').val();

    if (flddetail !== undefined && flddetail !== '') {
        $.ajax({
            url: baseUrl + '/inpatient/general/updateSymptoms',
            type: "POST",
            data: {
                fldid: $('.js-general-symptom-tr[is_selected="yes"]').data('fldid'),
                flddetail: flddetail,
            },
            dataType: "json",
            success: function (response) {
                $('#js-general-flddetail-modal').modal('hide');
                $('.js-general-symptom-tr[is_selected="yes"]').attr('flddetail', flddetail);
                showAlert(response.message);
            }
        });
    } else
        alert('Symptom report cannot be empty.')
});

$('#js-general-modal-search-input').keyup(function() {
	var searchText = $(this).val();
	$.each($('#js-general-symptoms-list-tbody tr td:contains(' + searchText + ')'), function(i, e) {
		$(e).show();
	});
	$.each($('#js-general-symptoms-list-tbody tr td:not(:contains(' + searchText + '))'), function(i, e) {
		$(e).hide();
	});
});
$.extend($.expr[":"], {
	"contains": function(elem, i, match, array) {
		return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

/**
* End Functions for General
*/

/**
 * Menu js
*/

function get_checkdata_elem(all_data) {
    var checkdata = '';
    $.each(all_data, function(i, data) {
        checkdata += '<div class="custom-control custom-checkbox">';
        checkdata += '<input type="checkbox" name="item_id[]" value="' + data.col + '" class="custom-control-input" id="' + data.col + '" value="' + data.col + '">';
        checkdata += '<label class="custom-control-label" for="' + data.col + '">' + data.col + '</label>';
        checkdata += '</div>';
    });

    return checkdata;
}

$('#menu-certificate-discharge-paper').click(function() {
    $.ajax({
        url: baseUrl + '/inpatient/certificateMenu/getDischargePaperOptions',
        method: 'GET',
        success: function(response) {
            $('#js-certificate-discharge-paper-exam-div').html(get_checkdata_elem(response.exams));
            $('#js-certificate-discharge-paper-test-div').html(get_checkdata_elem(response.labs));
            $('#js-certificate-discharge-paper-radio-div').html(get_checkdata_elem(response.radios));
        }
    });
    $('#js-certificate-discharge-paper-modal').modal('show');
});

$('#js-certificate-discharge-paper-modal').on('hidden.bs.modal', function () {
    $('#js-certificate-discharge-paper-exam-div').html('');
    $('#js-certificate-discharge-paper-test-div').html('');
    $('#js-certificate-discharge-paper-radio-div').html('');
});

$('.js-discharge-paper-select-all').change(function() {
    if ($(this).prop('checked'))
        $(this).closest('div').prev('div').find('input[type="checkbox"]').attr('checked', true);
    else
        $(this).closest('div').prev('div').find('input[type="checkbox"]').attr('checked', false);
});


$(document).on('click', '#js-triage-examinations-list tr td:nth-child(5)', function() {
    updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
});
