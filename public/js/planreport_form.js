$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$('#js-planreport-refresh-left').click(function() {
    $.ajax({
        url: baseUrl + '/costing/datawise/getPatientList',
        type: "GET",
        data: {moduleName: moduleName},
        dataType: "json",
        success: function (response) {
            var trData = '';
            $.each(response, function (i, e) {
                trData += '<tr>';
                trData += '<td>' + e.fldencounterval + '</td>';
                trData += '<td>' + e.flditemname + '</td>';
                trData += '</tr>';
            });

            $('#js-planreport-tbody-left').html(trData);
        }
    });
});

function plotPatientDeatil(response) {
    var trData = '';
    $.each(response, function (i, e) {
        var patient_info = e.encounter.patient_info;
        var consultant = e.encounter.consultant ? e.encounter.consultant.fldconsultname : '';
        var date = e.fldnewdate.split(' ')[0];
        var contact = patient_info.fldptcontact ? patient_info.fldptcontact : '';
        trData += '<tr>';
        trData += '<td>' + (i+1) + '</td>';
        trData += '<td>' + date + '</td>';
        trData += '<td>' + e.fldtestid + '</td>';
        trData += '<td>' + e.fldencounterval + '</td>';
        trData += '<td>' + patient_info.fldfullname + '</td>';
        trData += '<td>' + patient_info.fldagestyle + '/' + patient_info.fldptsex + '</td>';
        trData += '<td>' + contact + '</td>';
        trData += '<td>' + consultant + '</td>';
        trData += '<td>&nbsp;</td>';
        trData += '</tr>';
    });

    $('#js-planreport-tbody-right').html(trData);
}

$('#js-planreport-refresh-right').click(function() {
    $.ajax({
        url: baseUrl + '/costing/datawise/getPatientDetail',
        type: "GET",
        data: {
            moduleName: moduleName,
            category: $('#js-planreport-category-right').val(),
            date: $('#js-planreport-date-right').val()
        },
        dataType: "json",
        success: function (response) {
            plotPatientDeatil(response);
        }
    });
});

$('#js-planreport-export-right').click(function() {
    var url = baseUrl + '/costing/datawise/patientDetailReport?moduleName=' +  moduleName + '&category=' + $('#js-planreport-category-right').val() + '&date=' +  $('#js-planreport-date-right').val();
    window.open(url, '_blank');
});

$('#js-planreport-encounter-search-modal').click(function() {
    $('#js-planreport-category-right option').attr('selected', false);
    $('#js-planreport-category-right option[value=""]').attr('selected', true);
    $.ajax({
        url: baseUrl + '/costing/datawise/getPatientDetail',
        type: "GET",
        data: {
            moduleName: moduleName,
            encounterId: $('#js-planreport-encounter-input').val(),
            date: $('#js-planreport-date-right').val()
        },
        dataType: "json",
        success: function (response) {
            plotPatientDeatil(response);
            $('#js-planreport-encounter-modal').modal('hide');
            $('#js-planreport-encounter-input').val('');
        }
    });
});
