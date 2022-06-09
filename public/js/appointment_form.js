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
        url: baseUrl + "/radiology/appointment/getPatientTest",
        type: "GET",
        data: $('#js-xray-form').serialize(),
        success: function (response) {

            var trData = '';
            $.each(response.tests, function (i, e) {
                var patient_info = (e.encounter && e.encounter.patient_info) ? e.encounter.patient_info : null;
                var color = e.fldsample == 'CheckIn' ? 'style="color: #b3b9bf;"' : '';
                var consultant = (e.encounter && e.encounter.consultant && e.encounter.consultant.user) ? e.encounter.consultant.user.fldfullname : '';
                consultant += (e.encounter && e.encounter.consultant) ? '<br>' + e.encounter.consultant.fldconsultname : '';

                var fldfullname = '';
                if (patient_info && patient_info.fldrankfullname) {
                    fldfullname = patient_info.fldrankfullname;
                }

                var dataAttr = 'data-fldid="' + e.fldid + '"';
                dataAttr += ' tblpatradiotestid="' + e.tblpatradiotestid + '"';
                dataAttr += ' data-encounterid="' + e.fldencounterval + '"';
                dataAttr += ' fldsample="' + e.fldsample + '"';
                dataAttr += ' data-patientid="' + (e.encounter ? e.encounter.fldpatientval : '') + '"';
                dataAttr += ' data-fldfullname="' + fldfullname + '"';
                dataAttr += ' data-fldcategory="' + (e.fldcategory ? e.fldcategory : '') + '"';
                dataAttr += ' flduseridreport="' + (e.flduserid_report ? e.flduserid_report : '') + '"';
                dataAttr += ' flddate="' + (e.fldnewdate ? AD2BS(e.fldnewdate.split(' ')[0]) : '') + '"';
                dataAttr += ' fldroomno="' + (e.fldroomno ? e.fldroomno : '') + '"';

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
                trData += '<td>' + (e.fldnewdate ? AD2BS(e.fldnewdate.split(' ')[0]) : '') + '</td>';
                trData += '<td>' + (e.fldroomno ? e.fldroomno : '') + '</td>';
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

$(document).on('click', '#js-sampling-labtest-tbody tr', function() {
    selected_td('#js-sampling-labtest-tbody tr', this);
});
$(document).on('click', '#js-sampling-labtest-tbody tr td:nth-child(9)', function() {
    var trElem = $(this).closest('tr');
    $('#js-appointment-name-modal').text($(trElem).data('fldfullname').trim());
    $('#js-appointment-encid-modal').text($(trElem).find('td:nth-child(2)').text().trim());
    $('#js-appointment-test-modal').text($(trElem).find('td:nth-child(5)').text().trim());
    var date = $(this).closest('tr').attr('flddate') || '';

    $('#js-appointment-date-input-modal').val(date);
    $('#js-appointment-modal').modal('show');
});
$(document).on('click', '#js-sampling-labtest-tbody tr td:nth-child(10)', function() {
    var trElem = $(this).closest('tr');
    $('#js-inside-name-modal').text($(trElem).data('fldfullname').trim());
    $('#js-inside-encid-modal').text($(trElem).find('td:nth-child(2)').text().trim());
    $('#js-inside-test-modal').text($(trElem).find('td:nth-child(5)').text().trim());
    var roomno = $(this).closest('tr').attr('fldroomno') || '';

    $('#js-inside-room-input-modal').val(roomno);
    $('#js-inside-modal').modal('show');
});


$('#js-appointment-save-modal').click(function() {
    var date = $('#js-appointment-date-input-modal').val() || '';
    var trElem = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
    if (date !== '') {
        $.ajax({
            url: baseUrl + "/radiology/appointment/schedule",
            type: "POST",
            data: {
                date: date,
                fldid: $(trElem).data('tblpatradiotestid'),
                fldbillingid: $(trElem).data('fldid'),
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $('#js-appointment-modal').modal('hide');
                    // $(trElem).find('td:nth-child(9)').text(date);
                    // $(trElem).attr('flddate', date);
                    $(trElem).remove();
                    showAlert('Data Updated');
                } else
                    showAlert('Failed to update data', 'fail');
            }
        });
    } else
        showAlert('date is required.', 'fail');
});

$('#js-inside-save-modal').click(function() {
    var roomno = $('#js-inside-room-input-modal').val() || '';
    var trElem = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
    if (roomno !== '') {
        $.ajax({
            url: baseUrl + "/radiology/appointment/inside",
            type: "POST",
            data: {
                roomno: roomno,
                fldid: $(trElem).data('tblpatradiotestid'),
                fldbillingid: $(trElem).data('fldid'),
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $('#js-inside-modal').modal('hide');
                    $(trElem).remove();
                    showAlert('Data Updated');
                } else
                    showAlert('Failed to update data', 'fail');
            }
        });
    } else
        showAlert('roomno is required.', 'fail');
});

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

