$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
$('#to_date').val(AD2BS(date));
$('#from_date').val(AD2BS(date));

function getPatientTest(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + "/radiology/verify/getPatientTest",
        type: "GET",
        data: $('#js-xray-form').serialize(),
        success: function (response) {

            var trData = '';
            $.each(response.tests, function (i, e) {
                var patient_info = (e.encounter && e.encounter.patient_info) ? e.encounter.patient_info : null;
                var consultant = (e.encounter && e.encounter.consultant && e.encounter.consultant.user) ? e.encounter.consultant.user.fldfullname : '';
                consultant += (e.encounter&& e.encounter.consultant) ? '<br>' + e.encounter.consultant.fldconsultname : '';

                var fldfullname = '';
                if (patient_info && patient_info.fldrankfullname) {
                    fldfullname = patient_info.fldrankfullname;
                }
                var dataAttr = 'data-fldid="' + e.fldid + '"';
                dataAttr += ' data-encounterid="' + e.fldencounterval + '"';
                dataAttr += ' fldsample="' + e.fldstatus + '"';
                dataAttr += ' data-patientid="' + (e.encounter ? e.encounter.fldpatientval : '') + '"';
                dataAttr += ' data-fldfullname="' + fldfullname + '"';
                dataAttr += ' data-fldcategory="' + (e.fldcategory ? e.fldcategory : '') + '"';
                dataAttr += ' data-flduseridreport="' + (e.flduserid_report ? e.flduserid_report : '') + '"';

                trData += '<tr ' + dataAttr + '>';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td>' + e.fldencounterval + '</td>';

                trData += '<td>' + fldfullname + '<br>';
                trData += ((patient_info) ? patient_info.fldagestyle : '') + '/' + ((patient_info && patient_info.fldptsex) ? patient_info.fldptsex : '');
                trData += ' ' + ((patient_info && patient_info.fldptcontact) ? patient_info.fldptcontact : '') + '<br>';
                trData += '<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;' + ((patient_info && patient_info.fldptaddvill) ? patient_info.fldptaddvill : '')+ ', ' + ((patient_info && patient_info.fldptadddist) ? patient_info.fldptadddist : '') + '</td>';

                trData += '<td>' + consultant + '</td>';
                trData += '<td>' + (e.fldtestid ? e.fldtestid : '') + '</td>';
                trData += '<td>' + (e.fldcomment ? e.fldcomment : (e.fldreason ? e.fldreason : '')) + '</td>';
                trData += '<td><button class="btn btn-primary js-sampling-history-btn">History</button><button class="btn btn-primary js-sampling-pacs-btn">PACS</button></td>';
                trData += '<td>' + (e.fldreportquali ? e.fldreportquali : '') + '</td>';
                trData += '</tr>';
            });

            $('#js-sampling-labtest-tbody').html(trData);
        }
    });
}

$('#js-sampling-encounter-show-btn').click(function(e) {
    getPatientTest(e);
});
$('#js-sampling-encounterid-input').keydown(function (e) {
    if (e.which == 13)
        getPatientTest(e);
});

$(document).on('click', '#js-sampling-labtest-tbody tr td:last-child', function() {
    var trElem = $(this).closest('tr');
    var fldcategory = $(trElem).data('fldcategory');
    var fldid = $(trElem).data('fldid');
    var flduseridreport = $(trElem).data('flduseridreport');
    var fldtestid = $(trElem).find('td:nth-child(5)').text().trim();
    selected_td('#js-sampling-labtest-tbody tr', trElem);

    $('#js-radiotemplate-name-modal').text($(trElem).data('fldfullname').trim());
    $('#js-radiotemplate-encid-modal').text($(trElem).find('td:nth-child(2)').text().trim());
    $('#js-radiotemplate-test-modal').text(fldtestid);
    if (fldcategory == 'X-RAY') {
        $('#js-radiotemplate-obseravtion-modal').html($(trElem).find('td:nth-child(8)').text().trim());
    } else {
        $.ajax({
            url: baseUrl + "/radiology/verify/getModalContent",
            type: "GET",
            data: {fldid: fldid, fldtestid: fldtestid},
            dataType: "json",
            success: function (response) {
                if (response.type == "Fixed Components") {
                    var table = '<table class="table table-bordered table-striped">';
                    table += '<thead>';
                    table += '<tr>';
                    table += '<th>Test</th>';
                    table += '<th>Abnormal</th>';
                    table += '<th>Observation</th>';
                    table += '</tr>';
                    table += '</thead>';
                    table += '<tbody>';
                    $.each(response.data, function(i, d) {
                        table += '<tr>';
                        table += '<td>' + d.fldsubexam + '</td>';
                        table += '<td>' + get_abnoraml_btn(d.fldabnormal) + '</td>';
                        table += '<td>' + (d.fldreport ? d.fldreport : '') + '</td>';
                        table += '</tr>';
                    })
                    table += '</tbody>';
                    table += '</table>';
                } else
                    table = response.data.fldreportquali;
                $('#js-radiotemplate-obseravtion-modal').html(table);
            }
        });
    }
    $('#js-radiotemplate-modal').modal('show');
});

$('#js-radiotemplate-save-modal').click(function() {
    var selecteTr = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
    $.ajax({
        url: baseUrl + "/radiology/verify/changeStatus",
        type: "POST",
        data: {
            fldid: $(selecteTr).data('fldid'),
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $('#js-radiotemplate-modal').modal('hide');
                showAlert('Changed Status');
            } else
                showAlert('Failed to update comment', 'fail');
        }
    });
});

var changeObservation = {
    showModal: function (fldid, fldtestid, flduserid_report) {
        var currentUser = $('#js-sampling-current-userid').val();
        if (flduserid_report == 'null' || currentUser == flduserid_report) {
        } else
            showAlert('Authentication fail', 'fail');
    }
}

$(document).on('click', '.js-sampling-pacs-btn', function() {
    var encounterId = $(this).closest('tr').data('encounterid') || '';
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

$(document).on('click', '.js-sampling-history-btn', function() {
    var patientid = $(this).closest('tr').data('patientid') || '';
    if (patientid != '') {
        var url = baseUrl + '/outpatient/history-generate/' + patientid + '?opd'
        window.open(url, '_blank');
    } else
        showAlert('Enter patient id to view history.', 'fail');
});
