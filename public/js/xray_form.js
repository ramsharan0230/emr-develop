$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

CKEDITOR.replace('js-radiotemplate-textarea-modal', {
    height: '300px',
});

var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
$('#to_date').val(AD2BS(date));
$('#from_date').val(AD2BS(date));

function getPatientTest(e) {
    var colorWaiting = 'green';
    var colorAppointment = 'yellow';
    var colorCheckin = 'blue';
    e.preventDefault();
    $.ajax({
        url: baseUrl + "/xray/getPatientTest",
        type: "GET",
        data: $('#js-xray-form').serialize(),
        success: function (response) {

            var trData = '';
            $.each(response.tests, function (i, e) {
                var trColor = '';
                if (e.fldsample == 'Waiting' || e.fldsample == 'Sampled')
                    trColor = 'green';
                else if (e.fldsample == 'Appointment')
                    trColor = 'red';
                else if (e.fldsample == 'CheckIn')
                    trColor = 'blue';
                else if (e.fldsample == 'Reported')
                    trColor = 'yellow';
                else if (e.fldsample == 'Verified')
                    trColor = 'orange';

                var patient_info = (e.encounter && e.encounter.patient_info) ? e.encounter.patient_info : null;
                var color = (e.fldsample == 'CheckIn' || e.fldsample == 'Reported' || e.fldsample == 'Verified') ? 'style="color: #b3b9bf;"' : '';
                var consultant = (e.encounter && e.encounter.consultant && e.encounter.consultant.user) ? e.encounter.consultant.user.fldfullname : '';
                consultant += (e.encounter && e.encounter.consultant) ? '<br>' + e.encounter.consultant.fldconsultname : '';
                var payable = '';
                $.each(e.pat_billing_shares, function (i, e) {
                    payable += e.user.fldfullname+'<br>';
                });



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

                trData += '<tr ' + dataAttr + '>';
                trData += '<td>' + (i+1) + '</td>';
                trData += '<td><button class="btn" style="background: ' + trColor + ';">&nbsp;</button></td>';
                trData += '<td>' + e.fldencounterval + '</td>';

                trData += '<td>' + fldfullname + '<br>';
                trData += ((patient_info) ? patient_info.fldagestyle : '') + '/' + ((patient_info && patient_info.fldptsex) ? patient_info.fldptsex : '');
                trData += ' ' + ((patient_info && patient_info.fldptcontact) ? patient_info.fldptcontact : '') + '<br>';
                trData += '<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;' + ((patient_info && patient_info.fldptaddvill) ? patient_info.fldptaddvill : '')+ ', ' + ((patient_info && patient_info.fldptadddist) ? patient_info.fldptadddist : '') + '</td>';

                trData += '<td>' + consultant + '</td>';
                trData += '<td>' + payable + '</td>';
                trData += '<td>' + (e.fldtestid ? e.fldtestid : '') + '</td>';
                trData += '<td>' + (e.fldcomment ? e.fldcomment : (e.fldreason ? e.fldreason : '')) + '</td>';
                trData += '<td><button class="btn btn-primary js-sampling-history-btn">History</button>';
                trData += '<button class="btn btn-primary js-sampling-pacs-btn">PACS</button>';
                trData += '<a href="javascript:void(0)" onclick="radiology.displayModal(\'' + e.fldencounterval + '\')" class="btn btn-primary">Laboratory</a>';
                trData += '<a href="javascript:void(0)" onclick="pharmacy.displayModal(\'' + e.fldencounterval + '\')" class="btn btn-primary">Pharmacy</a></td>';
                trData += '<td>' + (e.fldreportquali ? e.fldreportquali : '') + '</td>';
                trData += '<td><i class="fa fa-arrow-circle-right" aria-hidden="true" ' + color + '></i></td>';
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

$('#find-test-name').on('keyup', function() {
    if(this.value){
        var val = this.value;
        if(val){
            $('#js-radiotemplate-tbody-modal tr').hide().filter(function() {
                return $(this).data('fldtestid').toLowerCase().includes(val);
            })
                .show();
        } else{
            $('#js-radiotemplate-tbody-modal tr').show();
        }

    }else{
        $('#js-radiotemplate-tbody-modal tr').show();
    }

})
    .change();

$(document).on('click', '#js-sampling-labtest-tbody tr td:last-child', function() {
    var currentTr = $(this).closest('tr');
    var status = $(currentTr).attr('fldsample');
    var fldcategory = $(currentTr).data('fldcategory');
    selected_td('#js-sampling-labtest-tbody tr', currentTr);

    if (status == 'Waiting' || status == 'Appointment') {
        // if ((fldcategory == 'X-RAY')) {
            $.ajax({
                url: baseUrl + "/xray/changeStatus",
                type: "GET",
                data: {
                    fldid: $(currentTr).data('fldid'),
                    tblpatradiotestid: $(currentTr).attr('tblpatradiotestid'),
                },
                dataType: "json",
                success: function (response) {
                    // $(currentTr).remove();
                    $(currentTr).attr('fldsample', 'CheckIn');
                    $(currentTr).attr('tblpatradiotestid', response.data.tblpatradiotestid);
                    updatecolor('CheckIn');
                    $(currentTr).attr('flduseridreport', response.data.flduserid_report);
                    $(currentTr).find('td:last-child').css('color', '#b3b9bf');
                    $("#js-sampling-encounter-show-btn").trigger("click");
                    showAlert('Status changed.');
                }
            });
        // } else {
        //     $('#js-appointment-name-modal').text($(currentTr).data('fldfullname').trim());
        //     $('#js-appointment-encid-modal').text($(currentTr).find('td:nth-child(2)').text().trim());
        //     $('#js-appointment-test-modal').text($(currentTr).find('td:nth-child(5)').text().trim());
        //     $('#js-appointment-modal').modal('show');
        // }
    }
});
$(document).on('click', '#js-appointment-save-modal', function() {
    var date = $('#js-appointment-date-modal').val() || '';
    var selecteTr = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
    if (date == '')
        showAlert('Date field is required.', 'fail');
    else {
        $.ajax({
            url: baseUrl + "/xray/saveAppointment",
            type: "POST",
            data: {
                fldid: $(selecteTr).data('fldid'),
                tblpatradiotestid: $(selecteTr).attr('tblpatradiotestid'),
                encounterid: $('#js-appointment-encid-modal').text().trim(),
                date: date,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#js-appointment-name-modal').text('');
                    $('#js-appointment-encid-modal').text('');
                    $('#js-appointment-test-modal').text('');
                    $('#js-appointment-modal').modal('hide');

                    $(selecteTr).attr('fldsample', 'CheckIn');
                    updatecolor('CheckIn');
                    $(selecteTr).attr('flduseridreport', response.data.flduserid_report);
                    $(selecteTr).find('td:last-child').css('color', '#b3b9bf');
                    showAlert('Data saved.');
                } else
                    showAlert('Failed to save data.', 'fail');
            }
        });
    }
});

$(document).on('click', '#js-sampling-labtest-tbody tr td:nth-child(10)', function() {
    var trElem = $(this).closest('tr');
    var status = $(trElem).attr('fldsample');
    var fldcategory = $(trElem).data('fldcategory');
    var fldid = $(trElem).data('fldid');
    var flduseridreport = $(trElem).attr('flduseridreport');
    var fldtestid = $(trElem).find('td:nth-child(6)').text().trim();
    selected_td('#js-sampling-labtest-tbody tr', trElem);

    if (status == 'CheckIn' || status == 'Verified'  || status == 'Reported' ) {
        // if (fldcategory == 'X-RAY') {
            $('#js-radiotemplate-name-modal').text($(trElem).data('fldfullname').trim());
            $('#js-radiotemplate-encid-modal').text($(trElem).find('td:nth-child(3)').text().trim());
            $('#js-radiotemplate-test-modal').text($(trElem).find('td:nth-child(6)').text().trim());
            CKEDITOR.instances["js-radiotemplate-textarea-modal"].setData($(trElem).find('td:nth-child(10)').html().trim());
            $('#js-radiotemplate-modal').modal('show');
            var currentUser = $('#js-sampling-current-userid').val();
            var rowUser = $(trElem).attr('flduseridreport');

            if (status == 'Reported' && currentUser == rowUser) {
                $('#js-radiotemplate-save-modal').show();
                $('#js-radiotemplate-saveverify-modal').show();
            } else if (status == 'Verified' && currentUser == rowUser) {
                $('#js-radiotemplate-save-modal').show();
                $('#js-radiotemplate-saveverify-modal').show();
            } else if (status == 'CheckIn') {
                $('#js-radiotemplate-save-modal').show();
                $('#js-radiotemplate-saveverify-modal').show();
            } else {
                $('#js-radiotemplate-save-modal').hide();
                $('#js-radiotemplate-saveverify-modal').hide();
            }
        // } else {
        //     changeObservation.showModal(fldid, fldtestid, flduseridreport);
        // }
    } else
        showAlert('Please CheckIn first to enable reporting.', 'fail');
});

$('#js-radiotemplate-tbody-modal tr').click(function() {
    var value = CKEDITOR.instances["js-radiotemplate-textarea-modal"].getData() + $(this).data('flddescription');
    CKEDITOR.instances["js-radiotemplate-textarea-modal"].setData(value);
});

function updatecolor(fldstatus) {
    var trColor = '';
    if (fldstatus == 'Waiting' || fldstatus == 'Sampled')
        trColor = 'green';
    else if (fldstatus == 'Appointment')
        trColor = 'red';
    else if (fldstatus == 'CheckIn')
        trColor = 'blue';
    else if (fldstatus == 'Reported')
        trColor = 'yellow';
    else if (fldstatus == 'Verified')
        trColor = 'orange';

    $('#js-sampling-labtest-tbody tr[is_selected="yes"] td:nth-child(2) button').css('background', trColor);
}

function savecomment(isverify = 'false') {
    var selecteTr = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
    var comment = CKEDITOR.instances["js-radiotemplate-textarea-modal"].getData() || '';
    if (comment != '') {
        $.ajax({
            url: baseUrl + "/xray/savecomment",
            type: "POST",
            data: {
                fldid: $(selecteTr).attr('tblpatradiotestid'),
                fldbillingid: $(selecteTr).data('fldid'),
                comment: comment,
                isverify: isverify
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(selecteTr).attr('fldsample', response.data.fldstatus)
                    $(selecteTr).find('td:nth-child(10)').html(comment);
                    $('#js-radiotemplate-modal').modal('hide');
                    showAlert('Comment updated');
                    updatecolor(response.data.fldstatus);
                } else
                    showAlert('Failed to update comment', 'fail');
            }
        });
    } else
        showAlert('Comment cannot be empty', 'fail');
}

$('#js-radiotemplate-save-modal').click(function() {
    savecomment();
});

$('#js-radiotemplate-saveverify-modal').click(function() {
    savecomment('true');
});

var changeObservation = {
    showModal: function (fldid, fldtestid, flduserid_report) {
        var currentUser = $('#js-sampling-current-userid').val();
        if (flduserid_report == 'null' || currentUser == flduserid_report) {
            $.ajax({
                url: baseUrl + '/radiology/getModalContent',
                type: "GET",
                data: {fldid: fldid, fldtestid: fldtestid, module: 'xray'},
                dataType: "json",
                success: function (response) {
                    $('#radiology-observation-modal').html(response.view_data);
                    $('#radiology-observation-modal').modal('show');
                    $('#radiology-observation-encounter-input-modal').val($('#js-sampling-labtest-tbody tr[is_selected="yes"]').data('encounterid'));
                }
            });
        } else
            showAlert('Authentication fail', 'fail');
    }
}

var changeRadioData = {
    saveData: function(currentElem) {
        $('.full-width').each(function(e){
            CKEDITOR.instances[this.id].updateElement();
        });

        var modalElement = $(currentElem).closest('.modal');
        var selectedData = $('#js-sampling-labtest-tbody tr[is_selected="yes"]');
        var data = $('#js-examination-modal-form').serialize();
        if ($(currentElem).attr('id') == 'js-examination-saveverify-btn-modal')
            data += '&isverify=true';
        else
            data += '&isverify=false';

        $.ajax({
            url: baseUrl + '/radiology/updateRadioObservation',
            type: "POST",
            data: data,
            success: function (response) {
                $('#radiology-observation-modal').modal('hide');
                showAlert(response.message);
            }
        });
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
