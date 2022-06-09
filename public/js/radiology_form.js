$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

function getPatRadioTest(encounterid, showall = false) {
    if (encounterid != '') {
        $.ajax({
            url: baseUrl + '/radiology/getPatRadioTest',
            type: "GET",
            data: {encounter_id: encounterid, showall: showall},
            dataType: "json",
            success: function (response) {
                var trData = '';
                $.each(response.tests, function (i, e) {
                    trData += '<tr data-fldid="' + e.fldid + '" fldmethod="' + e.fldmethod + '" flvisible="' + e.flvisible + '" fldcomment="' + e.fldcomment + '" fldcondition="' + e.fldcondition + '" fldsampletype="' + e.fldsampletype + '">';
                    trData += '<td>' + (i+1) + '</td>';
                    trData += '<td><input type="checkbox" class="js-labtest-checkbox" value="' + e.fldid + '"></td>';
                    trData += '<td>' + e.fldtestid + '</td>';
                    trData += '<td onclick="changeObservation.showModal(' + e.fldid + ', \'' + e.fldtestid + '\' , \'' + e.flduserid_report + '\')"></td>';
                    trData += '<td class="flvisible" onclick="changeRadioData.showModal(\'flvisible\')">' + e.flvisible + '</td>';
                    trData += '<td class="fldmethod" onclick="changeRadioData.showModal(\'fldmethod\')">' + e.fldmethod + '</td>';
                    trData += '<td class="fldsampletype" onclick="changeRadioData.showModal(\'fldsampletype\')">' + (e.fldsampletype ? e.fldsampletype : '') + '</td>';
                    trData += '<td>' + (e.fldtime_report ? e.fldtime_report : '') + '</td>';
                    trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addComment(' + e.fldid + ', this)">' + (e.fldcomment ? e.fldcomment : '') + '</textarea></td>';
                    trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addCondition(' + e.fldid + ', this)">' + (e.fldcondition ? e.fldcondition : '') + '</textarea></td>';
                    trData += '</tr>';
                });

                var fullname = ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamefir : '') + ' ' + ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamelast : '');
                $('#js-sampling-encounterid-input').val(encounterid);
                $('#js-sampling-fullname-input').val(fullname);
                $('#js-sampling-address-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptadddist : ''));
                $('#js-sampling-agesex-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptsex : ''));
                $('#js-sampling-location-input').val(((response.encounter_data) ? response.encounter_data.fldcurrlocat : ''));

                $('#js-sampling-labtest-tbody').html(trData);
            }
        });
    } else
        alert('Please enter encounterid.');
}

$('#js-sampling-encounter-show-btn').click(function() {
    var encounterid =$('#js-sampling-encounterid-input').val() || '';
    var showall =$('#js-sampling-showall-input').prop('checked') || false;
    getPatRadioTest(encounterid);
});

$('#js-sampling-encounterid-input').keydown(function (e) {
    if (e.which == 13) {
        var encounterid =$('#js-sampling-encounterid-input').val() || '';
        getPatRadioTest(encounterid);
    }
});

$(document).on('click', '#js-sampling-labtest-tbody tr', function () {
    selected_td('#js-sampling-labtest-tbody tr', this);
});

$('#js-select-all-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-labtest-checkbox').attr('checked', true);
    else
        $('.js-labtest-checkbox').attr('checked', false);
});

var changeSamplingData = {
    addComment: function (fldid, currentElem) {
        $.ajax({
            url: baseUrl + '/radiology/addComment',
            type: "POST",
            data: {fldid: fldid, lab_comment: $(currentElem).val()},
            success: function (response) {
                if (response.status === true) {
                    showAlert(response.message);
                }
            }
        });
    },
    addCondition: function (fldid, currentElem) {
        $.ajax({
            url: baseUrl + '/radiology/addCondition',
            type: "POST",
            data: {fldid: fldid, condition: $(currentElem).val()},
            success: function (response) {
                if (response.status === true)
                    showAlert(response.message);
            }
        });
    },
};

/*
    Sampling Start
*/
$(document).on('click', '.js-sampling-patient-tbody tr', function () {
    selected_td('.js-sampling-patient-tbody tr', this);
    $('#js-sampling-labtest-tbody').empty();
    var encounterid = $(this).data('encounterid');

    if (moduleName == 'addition') {
        $.ajax({
            url: baseUrl + '/radiology/getTests',
            type: "GET",
            data: {encounter_id: encounterid},
            dataType: "json",
            success: function (response) {
                // var trData = '';
                // $.each(response.tests, function (i, e) {
                //     trData += '<option value="' + e.fldid + '">' + e.flditemname + '</option>'
                // });
                // $('#js-sampling-test-tbody').html(trData);

                if (response.status) {
                    var fullname = ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamefir : '') + ' ' + ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamelast : '');
                    $('#js-sampling-encounterid-input').val(encounterid);
                    $('#js-sampling-fullname-input').val(fullname);
                    $('#js-sampling-address-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptadddist : ''));
                    $('#js-sampling-agesex-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptsex : ''));
                    $('#js-sampling-location-input').val(((response.encounter_data) ? response.encounter_data.fldcurrlocat : ''));

                    var trData = '';
                    var lenght = $('#js-sampling-labtest-tbody tr').length;
                    $.each(response.tests, function (i, sample) {
                        trData += '<tr><td>' + (++length) + '</td>';
                        trData += '<td><input type="checkbox" class="js-labtest-checkbox" value="' + sample.fldid + '"></td>';
                        trData += '<td>' + sample.flditemname + '</td>';
                        trData += '<td>' + sample.fldtestid + '</td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addComment(' + sample.fldid + ', this)"></textarea></td>';
                        trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addCondition(' + sample.fldid + ', this)"></textarea></td>';
                        trData += '</tr>';
                    });

                    $('#js-sampling-labtest-tbody').append(trData);
                }
                showAlert(response.message);
            }
        });
    } else {
        getPatRadioTest(encounterid)
    }
});
// $(document).on('click', '#js-sampling-test-tbody tr', function () {
//     selected_td('#js-sampling-test-tbody tr', this);
// });

$('#js-sampling-add-btn').click(function () {
    var encounterid = $('#js-sampling-patient-tbody tr[is_selected="yes"]').data('encounterid') || '';
    var fldid = [];
    $.each($('#js-sampling-test-tbody option:selected'), function (i, ele) {
        fldid.push($(ele).val());
    });
    var payto = $('#js-sampling-userid-select').val() || null;
    if (fldid.length > 0 || encounterid != '') {
        $.ajax({
            url: baseUrl + '/radiology/addPatRadioTest',
            type: "POST",
            data: {fldid: fldid, payto: payto, encounterid: encounterid},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var trData = '';
                    var lenght = $('#js-sampling-labtest-tbody tr').length;
                    $.each(response.data, function (i, sample) {
                        trData += '<tr><td>' + (++length) + '</td>';
                        trData += '<td><input type="checkbox" class="js-labtest-checkbox" value="' + sample.fldid + '"></td>';
                        trData += '<td>' + sample.flditemname + '</td>';
                        trData += '<td>' + sample.fldtestid + '</td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addComment(' + sample.fldid + ', this)"></textarea></td>';
                        trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addCondition(' + sample.fldid + ', this)"></textarea></td>';
                        trData += '</tr>';
                    });

                    $('#js-sampling-labtest-tbody').append(trData);
                    // $('#js-sampling-test-tbody tr[is_selected="yes"]').remove();
                    $('#js-sampling-test-tbody option:selected').remove();
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-sampling-delete-btn').click(function () {
    var fldid = [];
    $.each($('#js-sampling-test-tbody option:selected'), function (i, ele) {
        fldid.push($(ele).val());
    });

    if (fldid.length > 0) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/deleteTest',
            type: "POST",
            data: {fldid: fldid},
            dataType: "json",
            success: function (response) {
                if (response.status)
                    $('#js-sampling-test-tbody option:selected').remove();

                showAlert(response.message);
            }
        });
    }
});

$('#js-sampling-test-update-btn').click(function () {
    var testids = [];
    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
        testids.push($(ele).val());
    });

    if (testids.length > 0) {
        var data = {
            testids: testids,
            fldsampletype: $('#js-sampling-specimen-input').val(),
            fldsampleid: $('#js-sampling-sampleid-input').val(),
            fldcomment: $('#js-sampling-comment-textarea').val(),
            fldtime_start: $('#js-sampling-date-input').val(),
            fldrefername: $('#js-sampling-referal-input').val(),
        };

        $.ajax({
            url: baseUrl + '/radiology/updateTest',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
                        var trElem = $(ele).closest('tr');
                        $(trElem).find('td:nth-child(5)').text(data.fldsampletype);
                        $(trElem).find('td:nth-child(6)').text(data.fldsampleid);
                        $(trElem).find('td:nth-child(7)').text(data.fldtime_start);
                        $(trElem).find('td:nth-child(8)').text(data.fldrefername);

                        $('#js-sampling-specimen-input').val('');
                        $('#js-sampling-sampleid-input').val('');
                        $('#js-sampling-comment-textarea').val('');
                        $('#js-sampling-date-input').val('');
                        $('#js-sampling-referal-input').val('');

                        $(ele).attr('checked', false);
                    });
                }
                showAlert(response.message);
            }
        });
    } else
        alert('Please select atleast one test.');
});

/*
    Sampling End
*/
var changeObservation = {
    showModal: function (fldid, fldtestid, flduserid_report) {
        var currentUser = $('#js-sampling-current-userid').val();
        if (flduserid_report == 'null' || currentUser == flduserid_report) {
            $.ajax({
                url: baseUrl + '/radiology/getModalContent',
                type: "GET",
                data: {fldid: fldid, fldtestid: fldtestid},
                dataType: "json",
                success: function (response) {
                    $('#radiology-observation-modal').html(response.view_data);
                    $('#radiology-observation-modal').modal('show');
                    $('#radiology-observation-encounter-input-modal').val($('#js-sampling-encounterid-input').val());
                }
            });
        } else
            showAlert('Authentication fail', 'fail');
    },
    saveData: function(currentElem) {

    }
}

var changeRadioData = {
    showModal: function (type) {
        var selectedData = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');

        if (selectedData.length > 0) {
            var value = $(selectedData).attr(type);
            $('#js-sampling-modal-column-input').val(type);

            if (type == 'fldmethod' || type == 'flvisible') {
                $('.toggleHide option').attr('selected', false);
                $('.toggleHide option[value="' + value + '"]').attr('selected', true);
            } else if (type == 'fldcomment' || type == 'fldcondition' || type == 'fldsampletype')
                $('.toggleHide').val(value);

            $('.toggleHide').hide();
            $('.' + type).show();

            $('#js-sampling-modal').modal('show');
        } else
            alert('Please select data to update.');
    },
    saveData: function(currentElem) {
        $('.full-width').each(function(e){
            CKEDITOR.instances[this.id].updateElement();
        });

        var modalElement = $(currentElem).closest('.modal');
        var selectedData = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');

        $.ajax({
            url: baseUrl + '/radiology/updateRadioObservation',
            type: "POST",
            data: $('#js-examination-modal-form').serialize(),
            dataType: "json",
            success: function (response) {
                $('#radiology-observation-modal').modal('hide');
                showAlert(response.message);
            }
        });
    }
}



/*
    Printing Start
*/

function getPrintingEncounterData() {
    var type = $('input[name="type"][type="radio"]:checked').val();
    if (type == 'sample')
        $('#js-printing-hform-sample').val($('#js-printing-encounter-input').val())
    else
        $('#js-printing-hform-encounter').val($('#js-printing-encounter-input').val())
    $('#js-printing-hform-category').val($('#js-printing-category-select').val());

    $('#js-printing-hform').submit();
}

$('#js-printing-show-btn').click(function() {
    getPrintingEncounterData();
});

$('#js-printing-encounter-input').keydown(function(e) {
    if(e.which == 13)
        getPrintingEncounterData();
});

$(document).on('click', '#js-printing-samples-tbody tr', function () {
    selected_td('#js-printing-samples-tbody tr', this);

    var subtestData = $(this).data('subtest');
    var trData = '';
    $.each(subtestData, function(i, data) {
        trData += '<tr>';
        trData += '<td>' + (i+1) + '</td>'
        trData += '<td><input type="checkbox"></td>'
        trData += '<td>' + data.fldsubtest + '</td>'
        trData += '<td>' + get_abnoraml_btn(data.fldabnormal) + '</td>'
        trData += '<td>' + data.fldreport + '</td>'
        trData += '</tr>';
    });

    $('#js-printing-samples-subtest-tbody').html(trData);
});

$('#js-printing-search-patient-btn-modal').click(function() {
    $.ajax({
        url: baseUrl + '/admin/laboratory/printing/searchPatient',
        type: "POST",
        data: $('#js-printing-search-patient-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function(i, data) {
                trData += '<tr>';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td>' + (data.fldpatientval ? data.fldpatientval : '') + '</td>';
                trData += '<td>' + (data.fldptnamefir ? data.fldptnamefir : '') + '</td>';
                trData += '<td>' + (data.fldptnamelast ? data.fldptnamelast : '') + '</td>';
                trData += '<td>' + (data.fldptsex ? data.fldptsex : '') + '</td>';
                trData += '<td>' + (data.fldptaddvill ? data.fldptaddvill : '') + '</td>';
                trData += '<td>' + (data.fldptadddist ? data.fldptadddist : '') + '</td>';
                trData += '<td>' + (data.fldptcontact ? data.fldptcontact : '') + '</td>';
                trData += '<td>' + (data.fldagestyle ? data.fldagestyle : '') + '</td>';
                trData += '<td>' + (data.fldptcode ? data.fldptcode : '') + '</td>';
                trData += '</tr>';
            });

            $('#js-printing-modal-patient-tbody').html(trData);
        }
    });
});

$('#js-printing-patient-search-modal').on('hidden.bs.modal', function () {
    $('#js-printing-search-patient-form')[0].reset()
    $('#js-printing-modal-patient-tbody').html('');
});

/*
    Printing End
*/

$('.js-printing-verify-btn').click(function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to verify test??')) {
        $.ajax({
            url: baseUrl + '/radiology/verifyReport',
            type: "POST",
            data: {fldid: $(this).data('fldid')},
            dataType: "json",
            success: function (response) {
                showAlert(response.message);
                $('#js-printing-samples-tbody tr[is_selected="yes"] td:nth-child(11)').find('input[type="checkbox"]').attr('checked', true);
            }
        });
    }
});


$('#js-printing-add-btn-modal').click(function () {
    $.ajax({
        url: baseUrl + '/radiology/saveReport',
        type: "POST",
        data: $('#js-printing-hform').serialize() + '&fldtitle=' + $('#js-printing-title-modal-input').val(),
        success: function (response) {
            showAlert(response.message);
            $('#js-printing-save-report-modal').modal('hide');
            $('#js-printing-title-modal-input').val('%')
        }
    });
});

$('#genereate-report').click(function(e) {
    var noOfPage = 1;
    // noOfPage = prompt("Please enter no of films to print:", noOfPage);

    if (noOfPage !== null) {
        noOfPage = parseInt(noOfPage);
        noOfPage = isNaN(noOfPage) ? 1 : noOfPage;

        e.preventDefault();
        var urlReport = baseUrl + "/radiology/printReport?" +$('#js-printing-hform').serialize() + "&noOfPage=" + noOfPage;
        window.open(urlReport, '_blank');
    }
    /*$.ajax({
        url: baseUrl + '/radiology/printReport',
        type: "POST",
        data: $('#js-printing-hform').serialize(),
        /!*xhrFields: {
            responseType: 'blob'
        },*!/
        success: function (response, status, xhr) {
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });*/
});

$('#js-sampling-export-left').click(function() {
    var url = baseUrl + "/radiology/samplingPatientReport?category=" + $('#js-sampling-category-select').val();
    window.open(url, '_blank');
});

$('#js-sampling-export-right').click(function() {
    var url = baseUrl + "/radiology/getPatRadioTestPdf?encounter_id=" + $('#js-sampling-encounterid-input').val();
    window.open(url, '_blank');
});

$('#js-addition-select-all-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-addition-labtest-checkbox').prop('checked', true);
    else
        $('.js-addition-labtest-checkbox').prop('checked', false);
});


$('#js-sampling-search-submit-btn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: $('#js-printing-search-form').attr('action'),
        type: "POST",
        data: $('#js-printing-search-form').serialize() || $('#js-sampling-search-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, pat) {
                var patientName = (pat.encounter && pat.encounter.patient_info) ? pat.encounter.patient_info.fldfullname : '';
                trData += '<tr data-encounterid="' + pat.fldencounterval + '">';
                trData += '<td>' + pat.fldencounterval + '</td>';
                trData += '<td>' + patientName + '</td>';
                trData += '<td>' + (pat.macaccess ? pat.macaccess.fldcompname : '') + '</td>';
                trData += '<td>' + (pat.flduserid_report ? pat.flduserid_report : '') + '</td>';
                trData += '<td>' + (pat.fldtime_report ? pat.fldtime_report : '') + '</td>';
                trData += '</tr>';
            });

            if ($('#js-printing-patient-tbody').length > 0)
                $('#js-printing-patient-tbody').html(trData);
            else
                $('.js-sampling-patient-tbody').html(trData);
        }
    });
});

$('#js-reporting-pacs-btn').click(function() {
    var encounterId = $('#js-sampling-encounterid-input').val() || $('#js-printing-encounter-input').val() || '';
    if (encounterId != '') {
        $.ajax({
            url: baseUrl + '/radiology/getPacUrl',
            type: "GET",
            data: {
                encounterId: encounterId
            },
            dataType: "json",
            success: function (response) {
                if (response.status)
                    window.open(response.message, '_blank');
                else
                    alert(response.message);
            }
        });
    } else
        showAlert('Enter encounter id to view PACS data.', 'fail');
});

$('#js-reporting-history-btn').click(function() {
    var encounterId = $('#js-sampling-encounterid-input').val() || '';
    if (encounterId != '') {
        var url = baseUrl + '/radiology/radioHistory?encounterId=' + encounterId
        window.open(url, '_blank');
    } else
        showAlert('Enter encounter id to view history.', 'fail');
});

$(document).on('click', '#js-printing-patient-tbody tr', function () {
    selected_td('#js-printing-patient-tbody tr', this);

    $('#js-printing-encounter-input').val($(this).data('encounterid'));
    $('#js-printing-show-btn').click();
});

$('#js-printing-search-submit-btn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + "/radiology/setting",
        type: "POST",
        data: $('#js-printing-search-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, pat) {
                var nameCol = (pat.encounter && pat.encounter.patient_info) ? pat.encounter.patient_info.fldrankfullname : '';
                trData += '<tr data-encounterid="' + pat.fldencounterval + '">';
                trData += '<td>' + pat.fldencounterval + '</td>';
                trData += '<td>' + nameCol + '</td>';
                trData += '<td>' + ((pat.encounter && pat.encounter.consultant) ? pat.encounter.consultant.fldconsultname : '') + '</td>';
                trData += '<td>' + ((pat.flduserid_report) ? pat.flduserid_report : '') + '</td>';
                trData += '<td>' + ((pat.fldtime_report) ? pat.fldtime_report : '') + '</td>';
                trData += '</tr>';
            });

            $('#js-printing-patient-tbody').html(trData);
        }
    });
});
