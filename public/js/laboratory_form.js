$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$('#js-sampling-test-report').click(function () {
    var category = $('select[name="category"]').val() || '';
    var url = baseUrl + '/admin/laboratory/sampling/samplingPatientReport';
    url = (category == '') ? url : url + '?category=' + category;

    window.open(url);
})

$(document).on('click', '#sampling-labtest-list tr td', function () {
    if($(this).index()==7){
        var eventId = $(this).data('testid');
        $("#modal-testid").val( eventId );
    }
})

/*
    Sampling Start
*/
$('#js-sampling-update-specimen').click(function () {
    var testids = [];
    var specimen = $('#js-sampling-specimen-input').val() || '';
    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
        // var presample = $(this).closest('tr').find('td:nth-child(8)').text().trim() || '';
        // if (presample != '')
        testids.push($(ele).val());
        // else
        //     $(this).prop('checked', false);
    });

    if (testids.length == 0)
        alert('Please select at least one test.');
    else if (specimen == '')
        alert('Please select specimen.');
    else {
        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/updateSpecimen',
            type: "POST",
            data: {testids: testids, specimen: specimen},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
                        console.log(ele)
                        $(ele).closest('tr').find('td:nth-child(8)').text(specimen);
                    });
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-sampling-update-specimen-modal').click(function () {
    var modal_testids=$('#modal-testid').val()
    var testids = [modal_testids];
    var specimen = $('#js-sampling-specimen-input-modal').val() || '';
    if (testids.length == 0)
        alert('Please select at least one test.');
    else if (specimen == '')
        alert('Please select specimen.');
    else {
        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/updateSpecimen',
            type: "POST",
            data: {testids: testids, specimen: specimen},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    // $("#js-sampling-specimen-input-modal").select2("val", "");
                    $("#js-sampling-specimen-input-modal").val('').trigger('change')
                    $('#specimen-list-modal').modal('hide');
                    $('#js-sampling-labtest-tbody tr[is_selected="yes"]').find('td:nth-child(8)').text(specimen);
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-sampling-select-all-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-sampling-labtest-checkbox').prop('checked', true);
    else
        $('.js-sampling-labtest-checkbox').prop('checked', false);
});
$('#js-sampling-select-all-w-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-sampling-labtest-checkbox-for-worksheet').prop('checked', true);
    else
        $('.js-sampling-labtest-checkbox-for-worksheet').prop('checked', false);
});

$(document).on('change', '.js-sampling-labtest-checkbox', function () {
    // $('.js-sampling-labtest-checkbox').not(this).prop('checked', false);

    var trElem = $(this).closest('tr');
    $('#js-sampling-specimen-input option').attr('selected', false);
    $('#js-sampling-specimen-input option[value="' + $(trElem).find('td:nth-child(8)').text().trim() + '"]').attr('selected', true);
    // $('#js-sampling-sampleid-input').val($(trElem).find('td:nth-child(7)').text().trim());
    $('#js-sampling-comment-textarea').val(($(trElem).attr('fldcomment') || ''));
    // $('#js-sampling-date-input').val($(trElem).find('td:nth-child(6)').text().trim().split(' ')[0]);
    // $('#js-sampling-time-input').val($(trElem).find('td:nth-child(6)').text().trim().split(' ')[1]);
    $('#js-sampling-referal-input').val($(trElem).find('td:nth-child(10)').text().trim());
});

// $(document).on('change', '.js-addition-labtest-checkbox', function () {
//     var trElem = $(this).closest('tr');
//     $('#js-addition-specimen-input option').attr('selected', false);
//     $('#js-addition-specimen-input option[value="' + $(trElem).find('td:nth-child(7)').text().trim() + '"]').attr('selected', true);
//     $('#js-addition-date-input').val($(trElem).find('td:nth-child(5)').text().trim().split(' ')[0]);
//     $('#js-addition-sampleid-input').val($(trElem).find('td:nth-child(6)').text().trim());
//     $('#js-addition-comment-textarea').val(($(trElem).attr('fldcomment') || ''));
//     $('#js-addition-referred-input').val($(trElem).find('td:nth-child(9)').text().trim());
//     $('#js-addition-invoice-input').val(($(trElem).attr('fldbillno') || ''));

//     $('#js-addition-condition-input option').attr('selected', false);
//     $('#js-addition-condition-input option[value="' + ($(trElem).attr('fldcondition') || '') + '"]').attr('selected', true);
// });

function plotDiagnosis(diagnosis) {
    var liElem = '';
    $.each(diagnosis, function (i, diag) {
        liElem += '<option>' + diag.fldcode + '</option>';
    });

    $('#js-lab-common-diagnosis-ul').html(liElem);
}

function samplingLatestCheckbox(event){
    let isChecked = $(event.target).prop("checked")
    $(event.target).prop("checked", isChecked ? false : true)
    let id = event.target.getAttribute("patbillingid");
    if ($(`.child-sampling-checkbox-${id}:checked`).length > 0) {
        $(`.child-sampling-checkbox-${id}`).prop('checked', false);
    } else {
        $(`.child-sampling-checkbox-${id}`).prop('checked', true);
    }
}



function samplingLatestWorksheetCheckbox(event){
    let isChecked = $(event.target).prop("checked")
    $(event.target).prop("checked", isChecked ? false : true)
    let id = event.target.getAttribute("patbillingid");
    if ($(`.child-sampling-worksheet-checkbox-${id}:checked`).length > 0) {
        $(`.child-sampling-worksheet-checkbox-${id}`).prop('checked', false);
    } else {
        $(`.child-sampling-worksheet-checkbox-${id}`).prop('checked', true);
    }
}

$(document).off('click', '#js-sampling-patient-tbody tr');
$(document).on('click', '#js-sampling-patient-tbody tr', function () {
    selected_td('#js-sampling-patient-tbody tr', this);
    $('#js-sampling-labtest-tbody').empty();
    var encounterid = $(this).data('encounterid');
    var rejected_checkbox= $('#rejected:checked').val();

    $("#get_test_pdf").attr('rel', encounterid);
    $.ajax({
        url: baseUrl + '/admin/laboratory/sampling/getTest',
        type: "GET",
        data: {encounter_id: encounterid, category: $('#js-sampling-category-select').val(),rejected_checkbox:rejected_checkbox},
        dataType: "json",
        success: function (response) {
            let pcr_patient_info=response.encounter_data.patient_info;
            plotPatientData(response.encounter_data);
            plotDiagnosis(response.encounter_data.pat_findings);

            if (response.status) {
                var trData = '';
                var checked = $('#js-sampling-select-all-checkbox').prop('checked') ? 'checked' : '';
                $.each(response.test_data, function (i, sample) {
                    trData += `<tr><td> ${(i + 1)}</td>`;
                    trData += `<td><input type="checkbox"  ${checked} patbillingid="${sample.patbillingid}" name="testids[]" class="js-sampling-labtest-checkbox child-sampling-checkbox-${sample.patbillingid}" value="${sample.fldid}" data-test="${sample.fldtestid}" onchange="samplingLatestCheckbox(event)"></td>`;
                    trData += `<td><input type="checkbox" patbillingid="${sample.patbillingid}" name="testidsprint[]" class="js-sampling-labtest-checkbox-for-worksheet child-sampling-worksheet-checkbox-${sample.patbillingid}" value="${sample.fldid}" onchange="samplingLatestWorksheetCheckbox(event)">`;
                    trData += '<td>' + sample.flditemname + '</td>';
                    trData += '<td>' + sample.fldtestid + '</td>';
                    trData += '<td></td>';
                    trData += '<td></td>';
                    trData += `<td data-toggle="modal" data-target="#specimen-list-modal" data-testid="${sample.fldid}">${(sample.fldsampletype ? sample.fldsampletype : '')}</td>`;
                    trData += '<td>' + (sample.fldvial ? sample.fldvial : '') + '</td>';
                    trData += '<td></td></tr>';
                });

                $('#js-sampling-labtest-tbody').append(trData);
                $('#js-sampling-sampleid-input').val(response.autoid);

                if (response.referable_doctor != '' && response.referable_doctor != null) {
                    $('#js-sampling-referal-input option').attr('selected', false);
                    $('#js-sampling-referal-input option[value="' + response.referable_doctor + '"]').attr('selected', true);
                }
                // $('#js-sampling-test-tbody tr[is_selected="yes"]').remove();
                // $('#js-sampling-test-tbody option:selected').remove();
            }
            showAlert(response.message);
            showPcrDataModal(pcr_patient_info);
            // var trData = '';
            // $.each(response.tests, function (i, e) {
            //     trData += '<option value="' + e.fldid + '">' + e.flditemname + '</option>'
            // });

            // $('#js-sampling-test-tbody').html(trData);
        }
    });
});

function showPcrDataModal(pcr_patient_info){
    $('#js-pcr-first-name').val(pcr_patient_info.fldptnamefir);
        $('#js-pcr-middle-name').val(pcr_patient_info.fldmidname);
        $('#js-pcr-last-name-free-text').val(pcr_patient_info.fldptnamelast);
        if(pcr_patient_info.fldptsex==="Male"){
            $('#js-pcr-gender option[value="1"]').attr('selected', true);
        }else{
            $('#js-pcr-gender option[value="2"]').attr('selected', true);
        }
        $('#js-pcr-age').val(pcr_patient_info.fldage);
        $('#js-pcr-dob').val(pcr_patient_info.fldptbirday);
        province_data=JSON.parse(localStorage.getItem('province_data'));
        districts_data=JSON.parse(localStorage.getItem('districts_data'));
        municipalities_data=JSON.parse(localStorage.getItem('municipalities_data'));
        municipalities=JSON.parse(municipalities);
        $.each(province_data, function (i, province) {
            if(province.province_name==pcr_patient_info.fldprovince){
                $(`#js-pcr-province option[value="${province.id}"]`).attr('selected', true);
            }
        });
        $.each(districts_data, function (i, districts) {
            if(districts.district_name==pcr_patient_info.fldptadddist){
                $(`#js-district option[value="${districts.id}"]`).attr('selected', true);
            }
        });

        $.each(municipalities_data, function (i, municipalities) {
            if(municipalities.municipality_name==pcr_patient_info.fldmunicipality){
                $(`#js-municipality option[value="${municipalities.id}"]`).attr('selected', true);
            }
        });
        $('#js-pcr-wardno').val(pcr_patient_info.fldwardno);
        $('#js-pcr-contact').val(pcr_patient_info.fldptcontact);
        $('#js-pcr-register-date').val(pcr_patient_info.fldtime);
}

$(document).on("click", "#get_test_pdf", function () {
    var encounter_id = $('#js-sampling-encounterid-input').val() || '';
    var showall = $('#js-sampling-show-all-checkbox').prop('checked') ? 'true' : 'false';

    if (encounter_id != '')
        window.open(baseUrl + '/admin/laboratory/test-pdf?encounterid=' + encounter_id + '&showall=' + showall, '_blank');
    else
        alert('Please view patient data first to export data.');
});

function plotPatientData(encounter_data) {
    var fullname = (encounter_data && encounter_data.patient_info) ? encounter_data.patient_info.fldfullname : '';
    var agesex = ((encounter_data && encounter_data.patient_info) ? encounter_data.patient_info.fldagestyle : '');
    agesex += ((encounter_data && encounter_data.patient_info) ? '/' + encounter_data.patient_info.fldptsex : '');
    $('#js-sampling-encounterid-input').val(encounter_data.fldencounterval);
    $('#js-sampling-fullname-input').val(fullname);
    $('#js-sampling-address-input').val(((encounter_data && encounter_data.patient_info) ? encounter_data.patient_info.fldptadddist : ''));
    $('#js-sampling-agesex-input').val(agesex);
    $('#js-sampling-location-input').val(((encounter_data) ? encounter_data.fldcurrlocat : ''));
}

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
            url: baseUrl + '/admin/laboratory/sampling/addPatLabTest',
            type: "GET",
            data: {fldid: fldid, payto: payto, encounterid: encounterid},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var trData = '';
                    var checked = $('#js-sampling-select-all-checkbox').prop('checked') ? 'checked' : '';
                    $.each(response.data, function (i, sample) {
                        trData += '<tr><td>' + (++length) + '</td>';
                        trData += '<td><input type="checkbox" name="testids[]" ' + checked + ' class="js-sampling-labtest-checkbox" value="' + sample.fldid + '"></td>';
                        trData += '<td><input type="checkbox" name="testidsprint[]" class="js-sampling-labtest-checkbox-for-worksheet" value="' + sample.fldid + '"></td>';
                        trData += '<td>' + sample.flditemname + '</td>';
                        trData += '<td>' + sample.fldtestid + '</td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td>' + (sample.fldsampletype ? sample.fldsampletype : '') + '</td>';
                        trData += '<td>' + (sample.fldvial ? sample.fldvial : '') + '</td>';
                        trData += '<td></td></tr>';
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



function show_sampling_patient_tests() {
    var encounterid = $('#js-sampling-encounterid-input').val() || '';
    if (encounterid != '') {
        var showall = $('#js-sampling-show-all-checkbox').prop('checked') ? 'true' : 'false';
        $.ajax({
            url: baseUrl + '/admin/laboratory/sampling/getPatLabTest',
            type: "GET",
            data: {encounterid: encounterid, showall: showall},
            dataType: "json",
            success: function (response) {
                plotPatientData(response.encounter_data);

                var trData = '';
                var checked = $('#js-sampling-select-all-checkbox').prop('checked') ? 'checked' : '';
                $.each(response.patlabtest, function (i, sample) {
                    var checkbox = '<input type="checkbox" ' + checked + ' name="testids[]" class="js-sampling-labtest-checkbox" value="' + sample.fldid + '">';
                    if (sample.flduptime_sample)
                        checkbox = '';
                    trData += '<tr fldcomment="' + (sample.fldcomment ? sample.fldcomment : '') + '"><td>' + (i + 1) + '</td>';
                    trData += '<td>' + checkbox + '</td>';
                    trData += '<td><input type="checkbox" name="testidsprint[]" class="js-sampling-labtest-checkbox-for-worksheet" value="' + sample.fldid + '"></td>';
                    trData += '<td>' + (sample.bill ? sample.bill.flditemname : '') + '</td>';
                    trData += '<td>' + sample.fldtestid + '</td>';
                    trData += '<td>' + (sample.fldtime_sample ? AD2BS(sample.fldtime_sample.split(' ')[0]) + ' ' + sample.fldtime_sample.split(' ')[1] : '') + '</td>';
                    trData += '<td>' + (sample.fldsampleid ? sample.fldsampleid : '') + '</td>';
                    trData += '<td>' + (sample.fldsampletype ? sample.fldsampletype : '') + '</td>';
                    trData += '<td>' + ((sample.test && sample.test.fldvial) ? sample.test.fldvial : '') + '</td>';
                    trData += '<td>' + (sample.fldrefername ? sample.fldrefername : '') + '</td>';
                    trData += '</tr>';
                });

                $('#js-sampling-labtest-tbody').html(trData);
                if (response.referable_doctor != '' && response.referable_doctor != null) {
                    $('#js-sampling-referal-input option').attr('selected', false);
                    $('#js-sampling-referal-input option[value="' + response.referable_doctor + '"]').attr('selected', true);
                }
            }
        });
    }
}

$('#js-sampling-view-btn').click(function () {
    show_sampling_patient_tests();
});

$('#js-sampling-encounterid-input').keydown(function (e) {
    if (e.which == 13)
        show_sampling_patient_tests();
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


$(document).on('keyup', '.select2-search__field', function (e) {
    var id = $(this).closest('.select2-dropdown').find('.select2-results ul').attr('id');
    var flditem = $(this).val() || '';
    if ((id == "select2-js-sampling-fldsamplelocation-select-results") && e.keyCode === 13 && flditem != '') {
        var newOption = new Option(flditem, flditem, true, true);
        $('#js-sampling-fldsamplelocation-select').append(newOption).trigger('change');
        $('#js-sampling-fldsamplelocation-select').val(flditem).trigger('change');
        $('#js-sampling-fldsamplelocation-select').select2("close");
    }
});

$('#js-sampling-test-update-btn').click(function (e) {
    e.preventDefault();
    var testids = [];
    var isPcr = false;
    var allTests = [];
    var patbillingids = [];
    var testidsprintData = [];
    var fldid = $('#js-sampling-test-tbody tr[is_selected="yes"]').data('fldid') || '';
    var sample_id = '';
    var hasSpecimen = true;
    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
        var specimen = $(ele).closest('tr').find('td:nth-child(8)').text() || '';
        hasSpecimen = (hasSpecimen && specimen != '');

        var test_id = $(ele).data('test');
        if(test_id.includes('PCR'))
        isPcr = true;

        testids.push($(ele).val());
        sample_id = $(ele).closest('tr').find('td:nth-child(6)').text().trim();
        patbillingids.push($(ele).attr('patbillingid'));
    });
    if (!hasSpecimen) {
        showAlert("One or more specimen is missing.", 'fail');
        return false;
    }

    if (testids.length == 0) {
        showAlert("Please select one or more test to update.", 'fail');
        return false;
    }

    if (isPcr) {
        $('#pcr-test-modal').modal('show');
        return;
    }
    console.log(testids)
    updateTest(sample_id,testids,patbillingids , fldid);
    localStorage.setItem('testids_localstorage', JSON.stringify(testids));


});

function updateTest(sample_id = null, testids = null, patbillingids =null, fldid=null){
    var testidsprintData = [];
    if(sample_id == null || patbillingids == null || testids == null || fldid == null){
        var testids = [];
        var patbillingids = [];
        var fldid = $('#js-sampling-test-tbody tr[is_selected="yes"]').data('fldid') || '';
        var sample_id = '';
        $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
            testids.push($(ele).val());
            sample_id = $(ele).closest('tr').find('td:nth-child(6)').text().trim();
            patbillingids.push($(ele).attr('patbillingid'));
        });
    }

    $.each($('.js-sampling-labtest-checkbox-for-worksheet:checked'), function (i, elemenArray) {
        testidsprintData.push($(elemenArray).val());
    });

    var generate_worksheet = $('input[id="id-generate-worksheet"]:checked').length > 0;
    var generate_barcode = $('input[id="id-generate-barcode"]:checked').length > 0;
    var noOfPage = 1;
    if (generate_barcode)
        noOfPage = parseInt(prompt("Please enter no of page to print:", noOfPage));
    noOfPage = isNaN(noOfPage) ? 1 : noOfPage;

    if (generate_worksheet && testidsprintData.length == 0)
        alert('Please select test to print worksheet.');
    else {
        var data = {
            patbillingids: patbillingids,
            fldid: fldid,
            testids: testids,
            fldsampletype: $('#js-sampling-specimen-input').val(),
            fldencounterval: $('#js-sampling-encounterid-input').val(),
            fldsampleid: $('#js-sampling-sampleid-input').val(),
            fldcomment: $('#js-sampling-comment-textarea').val(),
            fldsamplelocation: $('#js-sampling-fldsamplelocation-select').val(),
            fldtime_start: $('#js-sampling-date-input').val(),
            fldtime: $('#js-sampling-time-input').val(),
            fldrefername: $('#js-sampling-referal-input').val(),
            testidsprint: testidsprintData,
            generate_worksheet: $('input[id="id-generate-worksheet"]:checked').length > 0,
            generate_barcode: $('input[id="id-generate-barcode"]:checked').length > 0,
            fldencounterval: $('#js-sampling-encounterid-input').val(),
        };

        $.ajax({
            url: baseUrl + '/admin/laboratory/sampling/updateTest',
            type: "POST",
            data: data,
            dataType: 'json',
            success: function (response, status, xhr) {
                // console.log(response);
                if (response.status) {
                    $('#js-sampling-specimen-input').val('');
                    $('#js-sampling-sampleid-input').val('');
                    $('#js-sampling-comment-textarea').val('');
                    $('#js-sampling-time-input').val('');
                    $('#js-sampling-date-input').val('');
                    $('#js-sampling-referal-input').val('');
                    $.each($('.js-sampling-labtest-checkbox:checked'), function (i, ele) {
                        $(ele).closest('tr').remove();
                    });
                    setTimeout(() => {
                        if ($('#js-sampling-labtest-tbody tr').length == 0) {
                            var encid = $('#js-sampling-encounterid-input').val();
                            $('#js-sampling-patient-tbody tr[data-encounterid="' + encid + '"]').remove();
                        }
                    }, 200);
                }
                showAlert(response.message);
                console.log('generate_barcode',generate_barcode);
                console.log('generate_worksheet',generate_worksheet);
                if (generate_worksheet === true) {
                    // var urlReport = baseUrl + "/admin/laboratory/sampling/worksheet?" + $.param(data) + "&_token=" + "{{ csrf_token() }}";
                    // window.open(urlReport, '_blank');

                    $.PrintPlugin({
                        remotefetch: {
                            loadFormRemote : true,
                            requestType : "GET",
                            origin : baseUrl + "/admin/laboratory/sampling/worksheet?" + $.param(data) + "&_token=" + "{{ csrf_token() }}",
                            // responseProperty : 'printview',
                            responseProperty : null,
                        }
                    });
                }
                if (generate_barcode === true) {
                    $.PrintPlugin({
                        remotefetch: {
                            loadFormRemote : true,
                            requestType : "GET",
                            origin : baseUrl + "/admin/laboratory/sampling/barcode?" + $.param(data) + "&noOfPage=" + noOfPage + "&_token=" + "{{ csrf_token() }}",
                            // responseProperty : 'printview',
                            responseProperty : null,
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                showAlert(error);
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    }
}


$('#js-sampling-nextid').click(function () {
    var location = $('#get_related_fldcurrlocat').text().trim() || $('#js-sampling-location-input').val() || '';
    var current_sampleid_value=$('#js-sampling-sampleid-input').val();
    // alert(current_sampleid_value);
    console.log(current_sampleid_value);
    $.ajax({
        url: baseUrl + '/admin/laboratory/sampling/getAutoId',
        type: "get",
        data:{current_sampleid_value:current_sampleid_value,location:location},
        success: function (response) {
            if(response.status==false){
                console.log('dsfasdf',response.nextid)
                $('#js-sampling-sampleid-input').val(response.nextid);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "This sample id is not allocated.so you can't increase the sample id"
                  })
                return;
            }
            console.log('fadsf',response.nextid)
            $('#js-sampling-sampleid-input').val(response);
            $('#js-addition-sampleid-input').val(response);
        }
    });
})

/*
    Sampling End
*/


/*
    Addition Start
*/

$('#js-addition-update-specimen').click(function () {
    var testids = [];
    var specimen = $('#js-addition-specimen-input').val() || '';
    $.each($('.js-addition-labtest-checkbox:checked'), function (i, ele) {
        // var presample = $(ele).closest('tr').find('td:nth-child(7)').text().trim() || '';
        // if (presample == '')
        testids.push($(ele).val());
        // else
        //     $(ele).prop('checked', false);
    });

    if (testids.length == 0)
        showAlert('Please select at least one test.', 'fail');
    else if (specimen == '')
        showAlert('Please select specimen.', 'fail');
    else {
        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/updateSpecimen',
            type: "POST",
            data: {testids: testids, specimen: specimen},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $.each($('.js-addition-labtest-checkbox:checked'), function (i, ele) {
                        $(ele).closest('tr').find('td:nth-child(7)').text(specimen);
                    });
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-addition-select-all-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-addition-labtest-checkbox').prop('checked', true);
    else
        $('.js-addition-labtest-checkbox').prop('checked', false);
});

// $(document).on('click', '#js-addition-bill-tbody tr', function () {
//     selected_td('#js-addition-bill-tbody tr', this);
// });

$('#js-addition-bill-add-btn').click(function () {
    var fldid = [];
    $.each($('#js-addition-bill-tbody option:selected'), function (i, ele) {
        fldid.push($(ele).val());
    });
    // var fldid = $('#js-addition-bill-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid.length > 0) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/addTest',
            type: "GET",
            data: {fldid: fldid},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var length = $('#js-addition-labtest-tbody tr').length || 0;
                    var trData = '';

                    $.each(response.data, function (i, sample) {
                        trData += '<tr><td>' + (++length) + '</td>';
                        trData += '<td><input type="checkbox" class="js-addition-labtest-checkbox" value="' + sample.fldid + '"></td>';
                        trData += '<td>' + sample.fldtestid + '</td>';
                        trData += '<td>' + sample.fldactive + '</td>';
                        trData += '<td></td>';
                        trData += '<td></td>';
                        trData += '<td>' + (sample.fldsampletype ? sample.fldsampletype : '') + '</td>';
                        trData += '<td>' + (sample.fldvial ? sample.fldvial : '') + '</td>';
                        trData += '<td></td>';
                        trData += '<td><button class="btn btn-danger btn-sm js-addition-test-delete-btn"><i class="fa fa-trash-alt"></i></button></td>';
                        trData += '</tr>';
                    });

                    $('#js-addition-labtest-tbody').append(trData);
                    $('#js-addition-bill-tbody option:selected').remove();
                }
                showAlert(response.message);
            }
        });
    }
});

$('#js-addition-bill-delete-btn').click(function () {
    var fldid = [];
    $.each($('#js-addition-bill-tbody option:selected'), function (i, ele) {
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
                    $('#js-addition-bill-tbody option:selected').remove();

                showAlert(response.message);
            }
        });
    }
});

$('#js-addition-test-update-btn').click(function () {
    var testids = [];
    $.each($('.js-addition-labtest-checkbox:checked'), function (i, ele) {
        var presample = $(this).closest('tr').find('td:nth-child(7)').text().trim() || '';
        if (presample != '')
            testids.push($(ele).val());
        else
            $(this).prop('checked', false);
    });

    if (testids.length > 0) {
        var generate_worksheet = $('input[id="id-generate-worksheet"]:checked').length > 0;
        var generate_barcode = $('input[id="id-generate-barcode"]:checked').length > 0;
        var noOfPage = 1;
        if (generate_barcode)
            noOfPage = parseInt(prompt("Please enter your name", noOfPage));
        noOfPage = isNaN(noOfPage) ? 1 : noOfPage;

        var data = {
            testids: testids,
            // fldsampletype: $('#js-addition-specimen-input').val(),
            fldcondition: $('#js-addition-condition-input').val(),
            fldcomment: $('#js-addition-comment-textarea').val(),
            fldtime_start: $('#js-addition-date-input').val(),
            fldtime: $('#js-addition-time-input').val(),
            fldsampleid: $('#js-addition-sampleid-input').val(),
            fldrefername: $('#js-addition-referred-input').val(),
            fldbillno: $('#js-addition-invoice-input').val(),
            fldencounterval: $('#encounter_id').val(),
            generate_worksheet: $('input[id="id-generate-worksheet"]:checked').length > 0,
            generate_barcode: $('input[id="id-generate-barcode"]:checked').length > 0,
        };

        $.ajax({
            url: baseUrl + '/admin/laboratory/addition/updateTest',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (generate_worksheet === true) {
                    var urlReport = baseUrl + "/admin/laboratory/addition/worksheet?" + $.param(data) + "&_token=" + "{{ csrf_token() }}";
                    window.open(urlReport, '_blank');
                }
                if (generate_barcode === true) {
                    var urlReportBarcode = baseUrl + "/admin/laboratory/addition/barcode?" + $.param(data) + "&noOfPage=" + noOfPage + "&_token=" + "{{ csrf_token() }}";
                    window.open(urlReportBarcode, '_blank');
                }
                if (response.status) {
                    $.each($('#js-addition-labtest-tbody tr td:nth-child(2) input:checked'), function (i, elem) {
                        $(elem).closest('tr').remove();
                    });
                }
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
            }
        });
    } else
        alert('Please select atleast one test.');
});


$('.js-addition-add-item').click(function () {
    var tr_data = '';
    $.each($(this).closest('.half_box2').find('select.form-input option'), function (i, e) {
        var value = $(e).val();
        if (value !== '')
            tr_data += '<tr data-flditem="' + value + '"><td>' + value + '</td></tr>';
    });

    $('#js-addition-type-input-modal').val($(this).data('variable'))
    $('#js-addition-table-modal').html(tr_data);
    $('#js-addition-add-item-modal').modal('show');
});

$('#js-addition-flditem-input-modal').keyup(function () {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-addition-table-modal tr td:first-child'), function (i, e) {
        var tdText = $(e).text().trim().toUpperCase();

        if (tdText.search(searchText) >= 0)
            $(e).show();
        else
            $(e).hide();
    });
});

$(document).on('click', '#js-addition-table-modal tr', function () {
    selected_td('#js-addition-table-modal tr', this);
});

$('#js-addition-add-btn-modal').click(function () {
    var data = {
        flditem: $('#js-addition-flditem-input-modal').val(),
        type: $('#js-addition-type-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/admin/laboratory/addition/addVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;

                var trData = '<tr data-flditem="' + data.flditem + '"><td>' + data.flditem + '</td></tr>';
                $('#js-addition-table-modal').append(trData);
                $('#js-addition-flditem-input-modal').val('');
            }
            showAlert(response.message);
        }
    });
});

$('#js-addition-delete-btn-modal').click(function () {
    var data = {
        flditem: $('#js-addition-table-modal tr[is_selected="yes"]').data('flditem'),
        type: $('#js-addition-type-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/admin/laboratory/addition/deleteVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-addition-table-modal tr[is_selected="yes"]').remove();

            showAlert(response.message);
        }
    });
});

$('#js-addition-add-item-modal').on('hidden.bs.modal', function () {
    $('#js-addition-flditem-input-modal').val('');
    $('#js-addition-type-input-modal').val('');
    $('#js-addition-table-modal').html('');

    refresh_addition_options();
});

function refresh_addition_options() {
    $.ajax({
        url: baseUrl + '/admin/laboratory/addition/getSelectOptions',
        type: "GET",
        success: function (response) {
            var specimens = '<option value="">-- Select --</option>';
            $.each(response.specimens, function (i, e) {
                specimens += '<option value="' + e.fldsampletype + '">' + e.fldsampletype + '</option>'
            });

            var conditions = '<option value="">-- Select --</option>';
            $.each(response.conditions, function (i, e) {
                conditions += '<option value="' + e.fldtestcondition + '">' + e.fldtestcondition + '</option>'
            });

            $('#js-addition-specimen-input').html(specimens);
            $('#js-addition-condition-input').html(conditions);
        }
    });
}

/*
    Addition End
*/


/*
    Reporting Start
*/

$(document).on('click', '#js-reporting-samples-tbody tr', function () {
    selected_td('#js-reporting-samples-tbody tr', this);
});

function get_patient_detail(encounter_id, category_id, sample_id) {
    
    if (sample_id !== '' || encounter_id !== '') {
        var showall = $('#js-reporting-show-all-checkbox').prop('checked') ? 'true' : 'false';
        var showOtherLocation = $('#js-reporting-other-location-checkbox').prop('checked') ? 'true' : 'false';
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/getPatientDetail',
            type: "GET",
            data: {
                encounter_id: encounter_id,
                category_id: category_id,
                sample_id: sample_id,
                showall: showall,
                showOtherLocation: showOtherLocation
            },
            dataType: "json",
            success: function (response) {
                encounter_id = response.encounter_data ? response.encounter_data.fldencounterval : '';
                if (response.encounter_data) {
                    console.log(response.encounter_data);
                    var fullname = ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamefir : '') + ' ' + ((response.encounter_data.patient_info.fldmidname) ? response.encounter_data.patient_info.fldmidname : '') + ' ' + ((response.encounter_data) ? response.encounter_data.patient_info.fldptnamelast : '');
                    var displayId = $('input[type="radio"][name="type"]:checked').val() || 'Encounter';
                    console.log(fullname);
                    $('#js-reporting-fullname-input').val(fullname);
                    $('#js-reporting-encounter-id').val(encounter_id);
                    $('#js-reporting-address-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptadddist : ''));
                    $('#js-reporting-agesex-input').val(((response.encounter_data) ? response.encounter_data.patient_info.fldptsex : ''));
                    $('#js-reporting-location-input').val(((response.encounter_data) ? response.encounter_data.fldcurrlocat : ''));
                    plotDiagnosis(response.encounter_data.pat_findings);

                    var trData = '';
                    var methodSelect = $('#js-method-select-template').html();

                    var index = 1;
                    $.each(response.samples, function (i, sample) {
                        if (sample.test) {
                            if (i == 0) {
                                displayId = (displayId == 'Encounter') ? sample.fldencounterval : sample.fldsampleid;
                                console.log('displayId',displayId);
                                $('#js-reporting-encounter-input').val(displayId);
                            }

                            var trMethodSelect = "<option value='Regular'>Regular</option>";
                            $.map(sample.test.methods, function (d) {
                                var selected = (d.fldmethod == sample.fldmethod) ? 'selected' : '';
                                trMethodSelect += "<option value='" + d.fldmethod + "' " + selected + ">" + d.fldmethod + "</option>";
                            });

                            var url = baseUrl + '/admin/laboratory/reporting/getTestGraphData?encounter_id=' + encounter_id + '&testid=' + sample.fldtestid;
                            trData += '<tr data-fldid="' + sample.fldid + '" data-fldoption="' + sample.test.fldoption + '" data-fldtestid="' + sample.fldtestid + '">';
                            trData += '<td>' + (index++) + '</td>';
                            trData += '<td>' + sample.fldsampleid + '</td>';
                            trData += '<td><a href="' + url + '" target="_blank">' + sample.fldtestid + '</a></td>';
                            trData += '<td class="abnormal-' + sample.fldid + '">';
                            if (sample.fldtest_type === "Quantitative") {
                                trData += get_abnoraml_btn(sample.fldabnormal);
                            }
                            trData += '</td>';
                            if (sample.fldtest_type === "Quantitative") {
                                trData += '<td>' + '<input type="text" id="quantity-' + sample.fldid + '" value="' + (sample.fldreportquali ? sample.fldreportquali : '') + '" style="width: 100px" class="form-control js-reporting-observation-input"></td>';

                                if (sample.refrance_range_helper) {
                                    var refrancerange = sample.refrance_range_helper;
                                    var refrance = refrancerange.substring(0, refrancerange.lastIndexOf(" ") + 1);
                                    var unit = refrancerange.substring(refrancerange.lastIndexOf(" ") + 1, refrancerange.length);

                                    trData += '<td>' + unit + '</td>';
                                    trData += '<td>' + refrance + '</td>';
                                } else {
                                    trData += '<td></td>';
                                    trData += '<td></td>';
                                }
                            } else if (sample.test.fldoption == 'Single Selection') {
                                trData += '<td><select id="quantity-' + sample.fldid + '"  onchange="quantityObservation.changeQuantity(' + sample.fldid + ')" class="form-control">';
                                trData += '<option value="">--Select--</option>';
                                $.each(sample.test.testoptions, function (i, option) {
                                    if (option.fldanswertype == 'Single Selection') {
                                        var selected = (option.fldanswer == sample.fldreportquali) ? 'selected="selected"' : '';
                                        trData += '<option value="' + option.fldanswer + '" ' + selected + '>' + option.fldanswer + '</option>';
                                    }
                                });
                                trData += '</select></td>';
                                trData += '<td></td>';
                                trData += '<td></td>';
                            } else if (sample.fldtest_type === "Qualitative") {
                                var testDisplayText = sample.fldtestid;
                                if (sample.test && sample.test.fldoption != 'Fixed Components' && sample.fldtestid != 'Culture & Sensitivity')
                                    testDisplayText = (sample.fldreportquali) ? sample.fldreportquali : testDisplayText;
                                trData += '<td><a href="javascript:;" id="qualitative-' + sample.fldid + '" onclick="quantityObservation.displayQualitativeForm(' + sample.fldid + ')" testid="' + sample.fldtestid + '" fldid="' + sample.fldid + '">' + testDisplayText + '</a></td>';
                                trData += '<td></td>';
                                trData += '<td></td>';
                            }
                            var commentLab;
                            if (sample.fldcomment === null) {
                                commentLab = "";
                            } else {
                                commentLab = sample.fldcomment;
                            }
                            trData += '<td>' + sample.fldsampletype + '</td>';
                            // trData += '<td><select class="form-control" onchange="method.saveSelectOnChange(this)">' + trMethodSelect + '</select></td>';
                            trData += '<td>' + sample.fldtime_sample + '</td>';
                            trData += '<td class="report-date-' + sample.fldid + '">' + (sample.fldtime_report ? sample.fldtime_report : '') + '</td>';
                            trData += '<td>';
                            trData += '<button class="btn btn-secondary" type="button" onclick="labPrintingNote(\'' + sample.fldid + '\', \'' + commentLab + '\')"><i class="fas fa-sticky-note"></i></button>';
                            trData += '</td>';
                            trData += '</tr>';
                        }
                    });

                    $('#js-reporting-samples-tbody').html(trData);
                } else {
                    $('#js-reporting-fullname-input').val('');
                    $('#js-reporting-encounter-id').val('');
                    $('#js-reporting-address-input').val('');
                    $('#js-reporting-agesex-input').val('');
                    $('#js-reporting-location-input').val('');

                    $('#js-reporting-samples-tbody').html('');
                    $('#js-lab-common-diagnosis-ul').html('')
                    showAlert('Tests has been verified or printed.', 'fail');
                }
                if(!response.samples.length){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "No record available"
                      })
                }
            }
        });
    }
}

function deleteTest(currentElem) {
    var currentTr = $(currentElem).closest('tr');
    var fldid = $(currentTr).data('fldid') || '';

    if (fldid != '' && confirm('Are you sure you want to delete test??')) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/deleteTest',
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
                if (response.status)
                    $(currentTr).remove();
            }
        });
    }
}

$('#js-reporting-refresh-btn').click(function () {
    $('#js-reporting-samples-tbody').empty();
    $('#js-reporting-fullname-input').val('');
    $('#js-reporting-address-input').val('');
    $('#js-reporting-agesex-input').val('');
    $('#js-reporting-location-input').val('');
    $.ajax({
        url: baseUrl + '/admin/laboratory/reporting/getLabTestPatient',
        type: "GET",
        data: $('#js-reporting-search-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, val) {
                if (val.patient_encounter) {
                    var fullname = (val.patient_encounter && val.patient_encounter.patient_info && val.patient_encounter.patient_info.fldrankfullname) ? val.patient_encounter.patient_info.fldrankfullname : '';
                    trData += '<tr data-encounterid="' + val.fldencounterval + '">';
                    trData += '<td>' + val.fldencounterval + '</td>';
                    trData += '<td style="display: none;">' + val.fldsampleid + '</td>';
                    trData += '<td class="js-patient-name">' + fullname + '</td>';
                    trData += '<td>' + ((val.patient_encounter && val.patient_encounter.consultant && val.patient_encounter.consultant.fldconsultname) ? val.patient_encounter.consultant.fldconsultname : '') + '</td>';
                    trData += '<td>' + (val.flduserid_sample ? val.flduserid_sample : '') + '</td>';
                    trData += '<td>' + (val.fldtime_sample ? val.fldtime_sample : '') + '</td>';
                    trData += '</tr>';
                }
            });
            $('#js-reporting-name-tbody').html(trData);
            encountersampletoggle('type', 'js-reporting-name-tbody');
        }
    });
});

$('.js-reporting-encsamp-radio-div').click(function () {
    encountersampletoggle('type', 'js-reporting-name-tbody');
});

$('.js-printing-encsamp-radio-div').click(function () {
    encountersampletoggle('type', 'js-printing-patient-tbody');
});


function encountersampletoggle(inputname, selector) {
    if ($('input[type="radio"][name="' + inputname + '"]:checked').val().toLowerCase() == 'encounter') {
        $('#' + selector + '').prev('thead').find('th:first-child').show();
        $('#' + selector + '').prev('thead').find('th:nth-child(2)').hide();
        $('#' + selector + ' td:first-child').show();
        $('#' + selector + ' td:nth-child(2)').hide();
    } else {
        $('#' + selector + '').prev('thead').find('th:first-child').hide();
        $('#' + selector + '').prev('thead').find('th:nth-child(2)').show();
        $('#' + selector + ' td:first-child').hide();
        $('#' + selector + ' td:nth-child(2)').show();
    }
}

$(document).on('click', '#js-reporting-name-tbody tr', function () {
    selected_td('#js-reporting-name-tbody tr', this);
    get_patient_detail($(this).data('encounterid'), $('#js-reporting-category-select').val(), '');
});

$('#js-reporting-show-btn').click(function () {
    var encounter_id = $('#js-reporting-encounter-input').val() || '';
    var sample_id = '';
    var type = $('input[name="type"][type="radio"]:checked').val();
    if (type == 'Sample') {
        sample_id = encounter_id;
        encounter_id = '';
    }
    get_patient_detail(encounter_id, $('#js-reporting-category-select').val(), sample_id);
});

$('#js-reporting-report-btn').click(function () {
    var encounter_id = $('#js-reporting-encounter-input').val();
    var category_id = $('#js-reporting-category-select').val();
    if (encounter_id !== null && encounter_id !== '')
        window.location.href = baseUrl + '/admin/laboratory/reporting/sampleReport?encounter_id=' + encounter_id + '&category_id=' + category_id;
});

$(document).on('click', '#js-reporting-samples-tbody tr td:nth-child(4)', function () {
    var text = $(this).html() || '';

    if (text != '') {
        $('#js-reporting-fldid-input').val($(this).closest('tr').data('fldid'));
        var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
        $('#js-reporting-status-select option').attr('selected', false);
        $('#js-reporting-status-select option[value="' + status + '"]').attr('selected', true);
        $('#js-reporting-status-modal').modal('show');
    }
});

function verifyEditQualitative() {
    var text = $(this).html() || '';
    console.log(text);
    if (text != '') {
        $('#js-reporting-fldid-input').val($(this).closest('tr').data('fldid'));
        var status = ($(this).find('div.btn').hasClass('btn-danger')) ? '1' : '0';
        $('#js-reporting-status-select option').attr('selected', false);
        $('#js-reporting-status-select option[value="' + status + '"]').attr('selected', true);
        $('#js-reporting-status-modal').modal('show');
    }
}

$('#js-reporting-status-save-modal').click(function () {
    var fldid = $('#js-reporting-fldid-input').val() || '';
    var fldabnormal = $('#js-reporting-status-select').val() || '';

    if (fldabnormal !== '' || fldid !== '') {
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/changeStatus',
            type: "POST",
            data: {fldid: fldid, fldabnormal: fldabnormal},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var btnElem = '#js-reporting-samples-tbody tr[data-fldid="' + fldid + '"] td:nth-child(4) div.btn';

                    var addClass = (fldabnormal === '0') ? 'btn-success' : 'btn-danger';
                    var removeClass = (fldabnormal === '0') ? 'btn-danger' : 'btn-success';
                    $(btnElem).removeClass(removeClass);
                    $(btnElem).addClass(addClass);

                    $('#js-reporting-status-modal').modal('hide');
                }
                showAlert(response.message);
            }
        });
    } else
        alert('Invalid data selected for update.');
});

function displayReportingModal(response) {
    // var allHisto = ['histopathology', 'histo pathology', 'hesto pathology', 'cytology'];
    // var category = response.category.trim().toLowerCase();
    var type = response.type.trim().toLowerCase();

    if (type == 'fixed components' || type == 'custom components') {
        $('#laboratory-observation-modal').find('div.modal-dialog').removeClass('modal-xl').addClass('modal-xxl');
        $('#laboratory-observation-modal').find('div.observation-modal-data').addClass('xxl-content');
    } else {
        $('#laboratory-observation-modal').find('div.modal-dialog').removeClass('modal-xxl').addClass('modal-xl');
        $('#laboratory-observation-modal').find('div.observation-modal-data').removeClass('xxl-content');
    }

    $('.observation-modal-data').empty();
    $('.observation-modal-data').html(response.view_data);
    $('#laboratory-observation-modal').modal('show');
}

$(document).on('keydown', '.js-reporting-observation-input', function (e) {
    if (e.which == 13) {
        var fldid = $(this).closest('tr').data('fldid');
        quantityObservation.changeQuantity(fldid);
    }
})


var quantityObservation = {
    changeQuantity: function (fldid) {
        var quantity = $('#quantity-' + fldid).val();
        var fldoption = $('#js-reporting-samples-tbody tr[data-fldid="' + fldid + '"]').data('fldoption') || '';
        var nextFocusTd = $('#quantity-' + fldid).closest('tr').next('tr').find('td:nth-child(5)');

        if ((Number(quantity) !== 0 && !isNaN(Number(quantity))) || fldoption == 'Single Selection') {
            $.ajax({
                url: baseUrl + '/admin/laboratory/reporting/changeQuantity',
                type: "POST",
                data: {
                    fldid: fldid,
                    quantity: quantity,
                    fldoption: fldoption,
                    fldtestunit: $('input[type="radio"][name="fldtestunit"]:checked').val() || 'SI',
                },
                success: function (response) {
                    // console.log(response.abnormal)
                    if (response.abnormal === true) {
                        htmlData = get_abnoraml_btn(1);
                        $('.report-date-' + fldid).html(response.report_date);
                        $('.abnormal-' + fldid).html(htmlData);
                    } else {
                        htmlData = get_abnoraml_btn(0);
                        $('.report-date-' + fldid).html(response.report_date);
                        $('.abnormal-' + fldid).html(htmlData);
                    }
                    if (response.status === true) {
                        showAlert('Value changed');

                        if (!$('#js-reporting-show-all-checkbox').prop('checked')) {
                            $('#quantity-' + fldid).closest('tr').remove();

                            if ($('#js-reporting-samples-tbody tr').length == 0) {
                                var encounterId = $('#js-reporting-encounter-input').val() || '';
                                $('#js-reporting-name-tbody tr[data-encounterid="' + encounterId + '"]').remove();
                            }
                        }
                    }
                },
                error: function (xhr, status, error) {
                    // showAlert(error);
                    // var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            // $('#quantity-' + fldid).focus();
            showAlert('Please provide numeric value.', 'error', 3000);
        }

        $(nextFocusTd).trigger('click');
        $(nextFocusTd).find('input').focus();
        $(nextFocusTd).find('select').focus();
        $(nextFocusTd).find('a').focus().trigger('click');
    },
    displayQualitativeForm: function (fldid) {
        var testId = $('#qualitative-' + fldid).attr('testid');
        // alert(testId);
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/displayQualitativeForm',
            type: "POST",
            data: {fldid: fldid, testId: testId},
            success: function (response) {
                displayReportingModal(response);
            }
        });
    },
    displayQualitativeFormUpdate: function (fldid) {
        var testId = $('#qualitative-' + fldid).attr('testid');
        // alert(testId);
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/displayQualitativeFormUpdate',
            type: "POST",
            data: {fldid: fldid, testId: testId},
            success: function (response) {
                displayReportingModal(response);
            }
        });
    },
    saveQualitativeData: function (currentElem) {
        var modalElement = $(currentElem).closest('.modal');
        var fldid = $(modalElement).find('#js-observation-fldid-hidden').val();
        var examOption = $(modalElement).find('#js-observation-type-hidden').val();
        var fldencounterval = $(modalElement).find('#js-observation-fldencounterval-hidden').val();
        var examid = $(modalElement).find('#js-observation-examid-hidden').val();
        var qualitative = '';
        var quantative = '0';

        if (examid == 'Culture & Sensitivity') {
            if (CKEDITOR.instances["js-reporting-single-selection-textarea"])
                qualitative = CKEDITOR.instances["js-reporting-single-selection-textarea"].getData();
            else {
                qualitative = [];
                $.each($('#js-culture-subtest-tbody tr[data-fldid]'), function (i, tr) {
                    qualitative.push({
                        subtestid: $(tr).data('fldid'),
                        abnormal: $(tr).find('input[type="checkbox"]').prop('checked') || false
                    });
                });
            }
        } else if (examOption === 'Clinical Scale') {
            quantative = 0;
            qualitative = "{";
            $.each($(modalElement).find('.js-observation-scale-text'), function (i, e) {
                var valueee = $(e).val();
                quantative += Number(valueee);

                qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
            });
            qualitative = qualitative.substring(0, qualitative.length - 2);
            qualitative += "}";
        } else if (examOption == 'Single Components') {
            quantative = 0;
            qualitative = "{";
            $.each($(modalElement).find('.js-observation-scale-text:checked'), function (i, e) {
                var valueee = $(e).val();
                quantative += Number(valueee);

                qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
            });
            qualitative = qualitative.substring(0, qualitative.length - 2);
            qualitative += "}";
        } else if (examOption === 'Fixed Components') {
            var subtesttype = 'Percent Sum';
            // var testid = $('#js-reporting-samples-tbody tr[is_selected="yes"] td:nth-child(3)').text().trim();
            var parentTestType = $('#js-observation-type-hidden').val().trim() || '';
            if ($(modalElement).find('.fldanswertype[value="' + subtesttype + '"]').length > 0) {
                // if (testid == 'Differential Leucocytes Count' || testid == 'Differential Leukocyte Count' || parentTestType == 'Percentage') {
                var count = 0;
                $.each($('.fldanswertype'), function (i, ele) {
                    var trElem = $(ele).closest('tr');
                    if ($(trElem).find('.fldanswertype').val() == subtesttype)
                        count += Number($(trElem).find('.answer').val()) || 0;
                });
                if (count != 100) {
                    alert('Sum of observation must be 100. Given is ' + count);
                    return false;
                }
            }


            // qualitative = $('#js-fixed-components-form').serialize();
            qualitative = [];
            $.each($('.js-fixed-components-tr'), function (i, ele) {
                if ($(ele).css('display') !== 'none') {
                    var fldanswertype = $(ele).find('.fldanswertype').val();
                    var answer = $(ele).find('.answer').attr('id');
                    if (CKEDITOR.instances[answer])
                        answer = CKEDITOR.instances[answer].getData();
                    else
                        answer = $(ele).find('.answer').val();

                    if (fldanswertype == 'Left and Right')
                        answer = "{\"Left\": \"" + $(ele).find('#js-observation-left-tbody').val() + "\", \"Right\": \"" + $(ele).find('#js-observation-right-tbody').val() + "\"}";

                    qualitative.push({
                        fldsubtest: $(ele).find('.fldsubtest').val(),
                        fldanswertype: fldanswertype,
                        abnormal: $(ele).find('.abnormal').prop('checked') || false,
                        answer: answer,
                    });
                }
            });
        } else if (examOption === 'Left and Right') {
            qualitative = "{\"Left\": \"" + $(modalElement).find('#js-observation-left-tbody').val() + "\", \"Right\": \"" + $(modalElement).find('#js-observation-right-tbody').val() + "\"}";
        } else if (examOption == 'No Selection') {
            qualitative = $(modalElement).find('#js-input-element').val();
            quantative = qualitative;
        } else if (examOption == 'Text Table' || examOption == 'Text Addition' || examOption == 'Text Reference') {
            if (qualitative = CKEDITOR.instances['js-input-element'])
                qualitative = CKEDITOR.instances['js-input-element'].getData();
            else
                qualitative = $(modalElement).find('#js-input-element').val();
        } else {
            qualitative = $(modalElement).find('#js-input-element').val();
        }

        if (!isNaN(qualitative))
            quantative = Number(qualitative);

        if (examOption === 'Custom Components') {
            qualitative=$('.answer').val();
            console.log('qualitative',qualitative)
        }
        var data = {
            fldid: fldid,
            examid: examid,
            fldencounterval: fldencounterval,
            examOption: examOption,
            qualitative: qualitative,
            quantative: quantative,
            fldtestunit: $('input[type="radio"][name="fldtestunit"]:checked').val() || 'SI',
            isNormal: $('#js-observation-normal-hidden-input').val() || '0',
        };
        console.log(data);
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/saveQualitativeData',
            type: 'POST',
            data: data,
            dataType: "json",
            success: function (response) {
                $('.report-date-' + fldid).text(response.report_date);
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
                $('#laboratory-observation-modal').modal('hide');
                if (response.status && $('#js-reporting-show-all-checkbox').prop('checked') == false)
                    $('#js-reporting-samples-tbody tr[is_selected="yes"]').remove();

                if ($('#js-reporting-samples-tbody tr').length == 0) {
                    var encounterId = $('#js-reporting-encounter-input').val() || '';
                    $('#js-reporting-name-tbody tr[data-encounterid="' + encounterId + '"]').remove();
                }

                if (response.status) {
                    $('#js-printing-show-btn').trigger('click');
                    $('#js-bulkverify-refresh-btn').trigger('click');
                }
            }
        });
    },
    saveQualitativeDataUpdate: function (currentElem) {
        var modalElement = $(currentElem).closest('.modal');
        var fldid = $(modalElement).find('#js-observation-fldid-hidden').val();
        var examOption = $(modalElement).find('#js-observation-type-hidden').val();
        var fldencounterval = $(modalElement).find('#js-observation-fldencounterval-hidden').val();
        var examid = $(modalElement).find('#js-observation-examid-hidden').val();
        var qualitative = '';
        var quantative = '0';

        if (examid == 'Culture & Sensitivity') {
            qualitative = [];
            $.each($('#js-culture-subtest-tbody tr[data-fldid]'), function (i, tr) {
                qualitative.push({
                    subtestid: $(tr).data('fldid'),
                    abnormal: $(tr).find('input[type="checkbox"]').prop('checked')
                });
            });
        } else if (examOption === 'Clinical Scale') {
            quantative = 0;
            qualitative = "{";
            $.each($(modalElement).find('.js-observation-scale-text'), function (i, e) {
                var valueee = $(e).val();
                quantative += Number(valueee);

                qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
            });
            qualitative = qualitative.substring(0, qualitative.length - 2);
            qualitative += "}";
        } else if (examOption == 'Single Components') {
            quantative = 0;
            qualitative = "{";
            $.each($(modalElement).find('.js-observation-scale-text:checked'), function (i, e) {
                var valueee = $(e).val();
                quantative += Number(valueee);

                qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
            });
            qualitative = qualitative.substring(0, qualitative.length - 2);
            qualitative += "}";
        } else if (examOption === 'Fixed Components') {
            var subtesttype = 'Percent Sum';
            // var testid = $('#js-reporting-samples-tbody tr[is_selected="yes"] td:nth-child(3)').text().trim();
            var parentTestType = $('#js-observation-type-hidden').val().trim() || '';
            if ($(modalElement).find('.fldanswertype[value="' + subtesttype + '"]').length > 0) {
                // if (testid == 'Differential Leucocytes Count' || testid == 'Differential Leukocyte Count' || parentTestType == 'Percentage') {
                var count = 0;
                $.each($('.fldanswertype'), function (i, ele) {
                    var trElem = $(ele).closest('tr');
                    if ($(trElem).find('.fldanswertype').val() == subtesttype)
                        count += Number($(trElem).find('.answer').val()) || 0;
                });
                if (!(count >= 99 && count <= 101)) {
                    alert('Sum of observation must be between 99 and 101. Given is ' + count);
                    return false;
                }
            }


            // qualitative = $('#js-fixed-components-form').serialize();
            qualitative = [];
            $.each($('.js-fixed-components-tr'), function (i, ele) {
                var fldanswertype = $(ele).find('.fldanswertype').val();
                var answer = $(ele).find('.answer').attr('id');
                if (CKEDITOR.instances[answer])
                    answer = CKEDITOR.instances[answer].getData();
                else
                    answer = $(ele).find('.answer').val();

                if (fldanswertype == 'Left and Right')
                    answer = "{\"Left\": \"" + $(ele).find('#js-observation-left-tbody').val() + "\", \"Right\": \"" + $(ele).find('#js-observation-right-tbody').val() + "\"}";

                qualitative.push({
                    fldsubtest: $(ele).find('.fldsubtest').val(),
                    fldanswertype: fldanswertype,
                    abnormal: $(ele).find('.abnormal').prop('checked'),
                    answer: answer,
                });
            });
        } else if (examOption === 'Left and Right') {
            qualitative = "{\"Left\": \"" + $(modalElement).find('#js-observation-left-tbody').val() + "\", \"Right\": \"" + $(modalElement).find('#js-observation-right-tbody').val() + "\"}";
        } else if (examOption == 'No Selection') {
            qualitative = $(modalElement).find('#js-input-element').val();
            quantative = qualitative;
        } else if (examOption == 'Text Table' || examOption == 'Text Addition' || examOption == 'Text Reference') {
            if (CKEDITOR.instances['js-input-element'])
                qualitative = CKEDITOR.instances['js-input-element'].getData();
            else
                qualitative = $('#js-input-element').val();
        } else {
            qualitative = $(modalElement).find('#js-input-element').val();
        }

        if (!isNaN(qualitative))
            quantative = Number(qualitative);

        var data = {
            fldid: fldid,
            examid: examid,
            fldencounterval: fldencounterval,
            examOption: examOption,
            qualitative: qualitative,
            quantative: quantative,
            fldtestunit: $('input[type="radio"][name="fldtestunit"]:checked').val() || 'SI',
        };

        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/save-Qualitative-Data-Update',
            type: 'POST',
            data: data,
            dataType: "json",
            success: function (response) {
                $('.report-date-' + fldid).text(response.report_date);
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
                $('#laboratory-observation-modal').modal('hide');
                if (response.status && $('#js-reporting-show-all-checkbox').prop('checked') == false)
                    $('#js-reporting-samples-tbody tr[is_selected="yes"]').remove();

                if ($('#js-reporting-samples-tbody tr').length == 0) {
                    var encounterId = $('#js-reporting-encounter-input').val() || '';
                    $('#js-reporting-name-tbody tr[data-encounterid="' + encounterId + '"]').remove();
                }

                if (response.status) {
                    $('#js-printing-show-btn').trigger('click');
                    $('#js-bulkverify-refresh-btn').trigger('click');
                }
            }
        });
    },
    addComment: function (fldid) {
        // alert(testId);
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/addComment',
            type: "POST",
            data: {fldid: fldid, lab_comment: $('#comment-' + fldid).val()},
            success: function (response) {
                if (response.status === true) {
                    showAlert(response.message);
                    // setTimeout(function(){ alert('Value changed'); }, 2000);
                }
            }
        });
    },
    addCondition: function (fldid) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/addCondition',
            type: "POST",
            data: {fldid: fldid, condition: $('#condition-' + fldid).val()},
            success: function (response) {
                if (response.status === true)
                    showAlert(response.message);
            }
        });
    },
    printPage: function () {
        window.open(baseUrl + '/admin/laboratory/reporting/load-pdf/' + $("#js-reporting-encounter-id").val(), '_blank');
    },
    printPageHistory: function () {
        window.open(baseUrl + '/admin/laboratory/reporting/history-pdf/' + $("#js-reporting-encounter-id").val(), '_blank');
    },
    generateFullPdf: function () {
        window.open(baseUrl + '/admin/laboratory/reporting/all-pdf/' + $('#js-reporting-category-select').val(), '_blank');
    },
    printReport: function () {
        var encounter = $("#js-reporting-encounter-id").val() || '';

        if (encounter != '') {
            var url = {
                'encounter_id': encounter,
                'encounter_sample': encounter,
                'type': ($('input[type="radio"][name="type"]:checked').val() || 'encounter').toLowerCase(),
                'fromdate': $('#js-fromdate-input-nepaliDatePicker').val(),
                'todate': $('#js-todate-input-nepaliDatePicker').val(),
            };
            window.open(baseUrl + '/admin/laboratory/printing/printReport?' + $.param(url), '_blank');
        }
    }
}

/*
    Reporting End
*/

/*
    Printing Start
*/

/*$('#js-printing-show-btn').click(function(e) {
    e.preventDefault();*/
function getPrintingEncounterData() {
    var type = $('input[name="type"][type="radio"]:checked').val();
    if (type == 'sample')
        $('#js-printing-hform-sample').val($('#js-printing-encounter-input').val())
    else
        $('#js-printing-hform-encounter').val($('#js-printing-encounter-input').val())
    $('#js-printing-hform-category').val($('#js-printing-category-select').val());
    $('#js-printing-status-hidden-input').val($('input[type="radio"][name="status"]:checked').val());

    $('#js-printing-hform').submit();
}

$('#js-printing-encounter-input').keydown(function (e) {
    if (e.which == 13)
        $('#js-printing-show-btn').click();
});

$('#js-printing-show-btn').click(function () {
    getPrintingEncounterData();
});

$(document).on('click', '#js-printing-samples-tbody tr', function () {
    selected_td('#js-printing-samples-tbody tr', this);

    var subtestData = $(this).data('subtest');
    var trData = '';
    $.each(subtestData, function (i, data) {
        trData += '<tr>';
        trData += '<td>' + (i + 1) + '</td>'
        trData += '<td><input type="checkbox" value="' + data.fldsubtest + '" name="report_subtest[]"></td>'
        trData += '<td>' + data.fldsubtest + '</td>'
        trData += '<td>' + get_abnoraml_btn(data.fldabnormal) + '</td>'
        trData += '<td>' + data.fldreport + '</td>'
        trData += '</tr>';
    });

    $('#js-printing-samples-subtest-tbody').html(trData);
});

$('#js-printing-search-patient-btn-modal').click(function () {
    $.ajax({
        url: baseUrl + '/admin/laboratory/printing/searchPatient',
        type: "POST",
        data: $('#js-printing-search-patient-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, data) {
                trData += '<tr>';
                trData += '<td>' + (i + 1) + '</td>';
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

$('#js-printing-add-btn-modal').click(function () {
    $.ajax({
        url: baseUrl + '/admin/laboratory/printing/saveReport',
        type: "POST",
        data: $('#js-printing-hform').serialize() + '&fldtitle=' + $('#js-printing-title-modal-input').val(),
        success: function (response) {
            showAlert(response.message);
            $('#js-printing-save-report-modal').modal('hide');
            $('#js-printing-title-modal-input').val('%')
        }
    });
});

$('#js-printing-select-all-checkbox').change(function () {
    if ($(this).prop('checked'))
        $('.js-printing-labtest-checkbox').prop('checked', true);
    else
        $('.js-printing-labtest-checkbox').prop('checked', false);
});


/*
    Printing End
*/

$('.js-printing-verify-btn').on('change', function () {
    if (confirm('Are you sure you want to change status?')) {
        var verify = this.checked ? 1 : 0;
        $.ajax({
            url: baseUrl + '/admin/laboratory/verify/verifyReport',
            type: "POST",
            data: {fldid: $(this).data('fldid'), verify: verify},
            dataType: "json",
            success: function (response) {
                console.log(response)
                showAlert(response.message);
                $('#js-printing-samples-tbody tr[is_selected="yes"] td:nth-child(11)').find('input[type="checkbox"]').attr('checked', (verify == 1));
                checkAllVerified();
            }
        });
    } else {
        return false;
    }
});

function changeAll(verify) {
    var fldid = [];
    $.each($('.js-printing-labtest-checkbox'), function (i, elem) {
        if ($(elem).prop('checked'))
            fldid.push($(elem).closest('tr').data('fldid'));
    });

    if (fldid.length > 0) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/verify/verifyReport',
            type: "POST",
            data: {fldid: fldid, verify: verify},
            dataType: "json",
            success: function (response) {
                console.log(response)
                showAlert(response.message);
                $('#js-printing-samples-tbody tr[is_selected="yes"] td:nth-child(11)').find('input[type="checkbox"]').attr('checked', (verify == 1));
                checkAllVerified();
            }
        });
    } else
        showAlert('Select atleast one test to change status.', 'Fail');
}

$('#checkAll').click(function () {
    changeAll(1);
    console.log('asd');
    $(this).find('input[type="checkbox"][value="checkAll"]').attr('checked', false);
});
$('#unCheckAll').click(function () {
    changeAll(0);
    console.log('asd');
    $(this).find('input[type="checkbox"][value="unCheckAll"]').attr('checked', false);
});

function checkAllVerified() {
    var allVerified = true;
    var encounterId = $('#js-printing-encounter-input').val();
    $.each($('.js-printing-verify-btn'), function (i, elem) {
        console.log($(elem));
        console.log(i + ': ' + $(elem).prop('checked'));
        if ($(elem).prop('checked') == false)
            allVerified = false;
    });
    console.log(allVerified);

    if (allVerified) {
        $('#js-printing-patient-tbody tr[data-encounterid="' + encounterId + '"]').remove();

        $('#js-printing-encounter-input').val('');
        $('#js-printing-name-input').val('');
        $('#js-printing-address-input').val('');
        $('#js-printing-agesex-input').val('');
        $('#js-printing-location-input').val('');
        $('#js-printing-samples-tbody').html('');
    }
}

$('#js-sampling-search-submit-btn').click(function (e) {
    e.preventDefault();
    console.log($('#js-sampling-search-form').serialize());
    $.ajax({
        url: baseUrl + '/admin/laboratory/sampling',
        type: "POST",
        data: $('#js-sampling-search-form').serialize(),
        success: function (response) {
            $('#js-sampling-patientList-div').html(response);
        }
    });
});

$(document).on('click', '#js-sampling-patient-pagination .pagination li a', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        url: url,
        type: "GET",
        data: $('#js-sampling-search-form').serialize(),
        success: function (data) {
            $('#js-sampling-patientList-div').html(data);
        }
    });
});

$(document).on('click', '#js-printing-patient-tbody tr', function () {
    selected_td('#js-printing-patient-tbody tr', this);

    var displayId = $('input[type="radio"][name="type"]:checked').val() || 'encounter';
    displayId = (displayId == 'encounter') ? 'encounterid' : 'sampleid';
    $('#js-printing-encounter-input').val($(this).data(displayId));
    $('#js-printing-category-select option').attr('selected', false);
    $('#js-printing-category-select option[value="' + $('#js-printing-category-search-select').val() + '"]').attr('selected', true);
    $('#js-printing-show-btn').click();
});

$('input:radio[name="status"]').click(function() {
    $('#js-printing-search-submit-btn').trigger('click');
});

$('#js-printing-search-submit-btn').click(function (e) {
    var url = location.href;
    e.preventDefault();
    $.ajax({
        url: url,
        type: "POST",
        data: {
            category_id: $('#js-printing-category-search-select').val(),
            fromdate: $('#js-fromdate-input-nepaliDatePicker').val(),
            todate: $('#js-todate-input-nepaliDatePicker').val(),
            status: $('[type="radio"][name="status"]:checked').val() || '',
            name: $('#js-printing-search-name-input').val(),
            encounterId: $('#js-printing-search-encounter-input').val(),
            new: $('#input-check-new').prop('checked') ? 'new' : '',
            printed: $('#input-check-printed').prop('checked') ? 'printed' : '',
            markprinted: $('#input-check-mark-printed').prop('checked') ? 'markprinted' : '',
        },
        dataType: "json",
        success: function (response) {
            var trData = '';
            $.each(response, function (i, pat) {
                trData += '<tr data-encounterid="' + pat.fldencounterval + '" data-sampleid="' + pat.fldsampleid + '">';
                trData += '<td>' + pat.fldencounterval + '</td>';
                trData += '<td>' + pat.fldsampleid + '</td>';
                trData += '<td class="js-patient-name">' + (pat.patient_encounter && pat.patient_encounter.patient_info ? pat.patient_encounter.patient_info.fldrankfullname : '') + '</td>';
                trData += '<td>' + ((pat.patient_encounter && pat.patient_encounter.consultant && pat.patient_encounter.consultant.fldconsultname) ? pat.patient_encounter.consultant.fldconsultname : '') + '</td>';
                trData += '<td>' + (pat.flduserid_report ? pat.flduserid_report : '') + '</td>';
                trData += '<td>' + (pat.fldtime_report ? pat.fldtime_report : '') + '</td>';
                trData += '</tr>';
            });
            $('#js-printing-patient-tbody').html(trData);
            encountersampletoggle('type', 'js-printing-patient-tbody');
        }
    });
});

$(document).on('click', '#js-xray-footer .pagination li a', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        url: url,
        type: "GET",
        data: $('#js-tat-form').serialize(),
        success: function (data) {
            $('#js-tat-report-tabledata').html(data);
        }
    });
});

$(document).on('click', '#js-tat-report-refresh-btn', function () {
    $.ajax({
        url: baseUrl + "/admin/laboratory/tat",
        type: "GET",
        data: $('#js-tat-form').serialize(),
        success: function (data) {
            $('#js-tat-report-tabledata').html(data);
        }
    });
});

$(document).on('click', '#js-tat-report-export-btn', function () {
    var url = baseUrl + "/admin/laboratory/tat/report?" + $('#js-tat-form').serialize();
    window.open(url, '_blank');
});

$(document).on('click', '#js-tat-report-excel-btn', function () {
    var url = baseUrl + "/admin/laboratory/tat/reportexcel?" + $('#js-tat-form').serialize();
    window.open(url);
});

function toggleAcceptRejectCheckbox() {
    var status = $('input[type="radio"][name="status"]:checked').val() || '';
    if (status == 'verified') {
        $('#unCheckAll input').attr('disabled', true);
        $('#checkAll input').attr('disabled', true);
    } else {
        $('#unCheckAll input').attr('disabled', false);
        $('#checkAll input').attr('disabled', false);
    }
}

toggleAcceptRejectCheckbox();
$('.js-printing-status-radio').click(function () {
    toggleAcceptRejectCheckbox();
});

// culture
$('#js-reporting-component-btn').click(function () {
    var selected_td = $('#js-reporting-samples-tbody tr[is_selected="yes"]');
    var testid = $(selected_td).data('fldtestid') || '';
    if (testid == 'Culture & Sensitivity') {
        $.ajax({
            url: baseUrl + "/admin/laboratory/getCultureComponents",
            type: "GET",
            data: {
                fldtestid: $(selected_td).data('fldid'),
            },
            dataType: "json",
            success: function (response) {
                var trData = '';
                $.each(response.testquali, function (i, component) {
                    var checked = (response.selectedids.includes(component.fldsubtest)) ? 'checked' : '';
                    trData += '<tr>';
                    trData += '<td>';
                    trData += '<div class="custom-control custom-checkbox">';
                    trData += '<input type="checkbox" class="custom-control-input" ' + checked + ' value="' + component.fldsubtest + '">';
                    trData += '<label class="custom-control-label">' + component.fldsubtest + '</label>';
                    trData += '</div>';
                    trData += '</td>';
                    trData += '</tr>';
                });
                $('#js-reporting-culture-modal-component-tbody').html(trData);
                $('#js-reporting-culture-modal').modal('show');
            }
        });
    } else
        showAlert('Please select Culture & Sensitivity test for Components.');
});

$('#js-reporting-culture-modal-search-input').keyup(function () {
    var searchText = $(this).val().toLowerCase();
    $.each($('#js-reporting-culture-modal-component-tbody tr td:first-child'), function (i, e) {
        var tdText = $(e).text().trim().toLowerCase();
        if (tdText.search(searchText) >= 0)
            $(e).show();
        else
            $(e).hide();
    });
});

$('#js-reporting-culture-modal-save-modal').click(function () {
    var components = [];
    $.each($('#js-reporting-culture-modal-component-tbody tr td:first-child input.custom-control-input[type="checkbox"]:checked'), function (i, e) {
        components.push($(e).val());
    });

    if (components.length !== 0) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/saveCultureComponents',
            type: "POST",
            data: {
                encounterid: $('#js-reporting-encounter-id').val(),
                testid: $('#js-reporting-samples-tbody tr[is_selected="yes"]').data('fldid'),
                components: components
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    showAlert(response.message);
                    $('#js-reporting-culture-modal').modal('hide');
                } else
                    showAlert(response.message, 'Fail');
            }
        });
    } else
        showAlert('Please select atleast one symptom.', 'Fail');
});

$(document).on('click', '.js-culture-subtables-info', function () {
    var fldid = $(this).closest('tr').data('fldid');
    $.ajax({
        url: baseUrl + "/admin/laboratory/getCultureComponentSubtests",
        type: "GET",
        data: {
            fldid: fldid,
        },
        dataType: "json",
        success: function (response) {
            var answers = ['Sensitive', 'Intermediate', 'Resistant'];
            var trData = '';
            $.each(response, function (i, e) {
                trData += '<tr>';
                trData += '<td><div class="custom-control custom-checkbox js-reporting-culture-subtable-modal-checkbox"><input type="checkbox" class="custom-control-input" value="' + e.fldanswer + '"><label class="custom-control-label">' + e.fldanswer + '</label></div></td>';
                trData += '<td><div class="form-group er-input">';
                $.each(answers, function (j, ans) {
                    trData += '<div class="custom-control custom-radio custom-control-inline">';
                    trData += '<input disabled type="radio" class="custom-control-input" name="js-check[' + i + ']" value="' + ans + '" name="type" id="' + i + j + ans + '">';
                    trData += '<label class="custom-control-label" for="' + i + j + ans + '">' + ans + '</label>';
                    trData += '</div>';
                })
                trData += '</div></td>';
                trData += '<td><input type="text" class="form-control js-culture-subtables-bacteria-input"></td>';
                trData += '</tr>';
            });
            $('#js-reporting-culture-subtable-modal-fldsubtestid-input').val(fldid);
            $('#js-reporting-culture-subtable-modal-tbody').html(trData);
            $('#js-reporting-culture-subtable-modal').modal('show');
        }
    });
});

$(document).on('click', '.js-reporting-culture-subtable-modal-checkbox', function () {
    var checked = $(this).find('input[type="checkbox"]').prop('checked') == false;
    $.each($(this).closest('td').next('td').find('input[type="radio"]'), function (i, radio) {
        $(radio).attr('disabled', checked);
    });
});

$(document).on('click', '#js-reporting-culture-subtable-modal-save-btn', function () {
    var subtables = [];
    var validation = true;
    $.each($('.js-reporting-culture-subtable-modal-checkbox input[type="checkbox"]:checked'), function () {
        var fldvalue = $(this).closest('td').next('td').find('input[type="radio"]:checked').val() || '';
        var comment = $(this).closest('tr').find('.js-culture-subtables-bacteria-input').val() || '';

        validation = (!validation || fldvalue == '') ? false : true;
        subtables.push({
            fldvariable: $(this).val(),
            fldvalue: fldvalue,
            comment: comment,
        });
    });

    if (!validation) {
        showAlert('One or more Drug Sensitivity type is missing.', 'fail');
        return false;
    }

    if (subtables.length > 0) {
        var fldsubtestid = $('#js-reporting-culture-subtable-modal-fldsubtestid-input').val();
        var formData = {
            'fldtestid': $('#js-reporting-samples-tbody tr[is_selected="yes"]').data('fldid'),
            'fldsubtestid': fldsubtestid,
            'subtables': subtables,
        };
        $.ajax({
            url: baseUrl + '/admin/laboratory/saveCultureSubtables',
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status) {
                    var trData = '';
                    $.each(response.all_data, function (i, subtable) {
                        var comment = (subtable.fldcolm2) ? subtable.fldcolm2 : '';
                        trData += '<tr><td>' + subtable.fldvariable + '</td><td>' + subtable.fldvalue + '</td><td>' + comment + '</td><td><button type="button" class="btn btn-danger js-culture-subtables-value-delete" data-fldid="' + subtable.fldid + '"><i class="fa fa-trash"></i></button></td></tr>'
                    });
                    $('tr[data-fldid="' + fldsubtestid + '"] .js-culture-subtable-tody').append(trData);
                    $('tr[data-fldid="' + fldsubtestid + '"] .js-culture-subtables-delete').remove();
                }
                showAlert(response.message, status);
                $('#js-reporting-culture-subtable-modal').modal('hide');
            }
        });
    } else
        showAlert('Please select atlaest one Drug Sensitivity.', 'fail');
});

$(document).on('click', '.js-culture-subtables-value-delete', function () {
    if (confirm('Are you sure to delete data??')) {
        var fldid = $(this).data('fldid');
        var currentTbody = $(this).closest('tbody.js-culture-subtable-tody');

        $.ajax({
            url: baseUrl + "/admin/laboratory/deleteSubtables",
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status)
                    $('.js-culture-subtables-value-delete[data-fldid="' + fldid + '"]').closest('tr').remove();

                if ($(currentTbody).find('tr').length == 0)
                    $(currentTbody).closest('table').closest('td').next('td').append('<button type="button" class="btn btn-danger js-culture-subtables-delete"><i class="fa fa-trash"></i></button>');


                showAlert(response.message, status);
            }
        });
    }
});

$(document).on('click', '.js-culture-subtables-delete', function () {
    if (confirm('Are you sure to delete data??')) {
        var currentTr = $(this).closest('tr');
        $.ajax({
            url: baseUrl + "/admin/laboratory/deleteCultureComponent",
            type: "POST",
            data: {
                fldid: $(currentTr).data('fldid')
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status)
                    $(currentTr).remove();
                showAlert(response.message, status);
            }
        });
    }
});

$('#js-reporting-allcomment-save-btn').click(function () {
    var comment = $('#js-reporting-allcomment-textarea').val() || '';
    var testids = [];
    $.each($('#js-reporting-samples-tbody tr'), function (i, elem) {
        var fldid = $(elem).data('fldid') || '';
        if (fldid != '')
            testids.push(fldid);
    });

    if (testids.length > 0 && comment != '') {
        $.ajax({
            url: baseUrl + '/admin/laboratory/reporting/addAllComment',
            type: "POST",
            data: {
                testids: testids,
                comment: comment,
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status);
            }
        });
    } else {
        if (testids.length == 0)
            showAlert('Test is empty.', 'fail');
        else if (comment == '')
            showAlert('Comment is empty.', 'fail');
    }
});

$('#js-reporting-verification-btn').click(function () {
    var url = baseUrl + '/admin/laboratory/verify';
    var encountersample = $('#js-reporting-encounter-input').val() || $('#js-printing-encounter-input').val() || '';
    if (encountersample != '') {
        var type = ($('input[type="radio"][name="type"]:checked').val() || 'encounter').toLowerCase();
        $('#js-reporting-type-hidden-form-input').val(type);
        $('#js-reporting-encountersample-hidden-form-input').val(encountersample);
        $('#js-reporting-popup-form').attr('action', url);
        $('#js-reporting-popup-form').submit();
    }
});

$('#js-reporting-printing-btn').click(function () {
    var url = baseUrl + '/admin/laboratory/printing';
    var encountersample = $('#js-reporting-encounter-input').val() || $('#js-printing-encounter-input').val() || '';
    if (encountersample != '') {
        var type = ($('input[type="radio"][name="type"]:checked').val() || 'encounter').toLowerCase();
        $('#js-reporting-type-hidden-form-input').val(type);
        $('#js-reporting-encountersample-hidden-form-input').val(encountersample);
        $('#js-reporting-popup-form').attr('action', url);
        $('#js-reporting-popup-form').submit();
    }
});


$('#js-reporting-reporting-btn').click(function () {
    var encounterId = $('#js-printing-hform-encounter').val() || '';
    if (encounterId != '') {
        var url = baseUrl + '/admin/laboratory/reporting?encounterId=' + encounterId;
        window.open(url, '_blank');
    }
});

$(document).on('click', '.js-addition-test-delete-btn', function () {
    if (confirm('Are you sure to delete data??')) {
        var currentTr = $(this).closest('tr');
        var fldid = $(currentTr).find('td:nth-child(2) input').val();
        $.ajax({
            url: baseUrl + "/admin/laboratory/addition/deleteTestData",
            type: "POST",
            data: {
                fldid: fldid,
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status)
                    $(currentTr).remove();
                showAlert(response.message, status);
            }
        });
    }
});

$(document).on('click', '#js-printing-samples-tbody tr td:nth-child(8)', function () {
    var closestTr = $(this).closest('tr');
    $('#js-printing-observation-examination-modal').text($(closestTr).find('td:nth-child(3)').text().trim());
    $('#js-printing-observation-specimen-modal').text($(closestTr).find('td:nth-child(4)').text().trim());
    $('#js-printing-observation-sampleid-modal').text($(closestTr).find('td:nth-child(5)').text().trim());
    $('#js-printing-observation-text-modal').html($(closestTr).find('td:nth-child(8)').html().trim());

    setTimeout(() => {
        $.each($('#js-printing-observation-text-modal tr'), function (i, elem) {
            $(elem).removeAttr('is_selected').removeAttr('style');
        });
    }, 200);
    $('#js-printing-observation-modal').modal('show');
});

$(document).on('click', '#js-bulkverify-tbody tr td:nth-child(10)', function () {
    var closestTr = $(this).closest('tr');
    $('#js-printing-observation-examination-modal').text($(closestTr).find('td:nth-child(5)').text().trim());
    $('#js-printing-observation-specimen-modal').text($(closestTr).find('td:nth-child(6)').text().trim());
    $('#js-printing-observation-sampleid-modal').text($(closestTr).find('td:nth-child(7)').text().trim());
    $('#js-printing-observation-text-modal').html($(closestTr).find('td:nth-child(10)').html().trim());

    setTimeout(() => {
        $.each($('#js-printing-observation-text-modal tr'), function (i, elem) {
            $(elem).removeAttr('is_selected').removeAttr('style');
        });
    }, 200);

    $('#js-printing-observation-modal').modal('show');
});

$('#js-printing-observation-modal').on('hidden.bs.modal', function () {
    $('#js-printing-observation-examination-modal').text('');
    $('#js-printing-observation-specimen-modal').text('');
    $('#js-printing-observation-sampleid-modal').text('');
    $('#js-printing-observation-text-modal').text('');
});

/*
bulk verificatiion
*/

$('#js-bulkverify-verify-btn').click(function () {
    var fldid = [];
    $.each($('#js-bulkverify-tbody input.js-printing-labtest-checkbox:checked'), function (i, elem) {
        fldid.push($(elem).closest('tr').data('fldid'));
    });

    if (fldid.length > 0) {
        $.ajax({
            url: baseUrl + '/admin/laboratory/verify/verifyReport',
            type: "POST",
            data: {fldid: fldid, verify: 1},
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                showAlert(response.message, status, 10000);

                if (response.updatedIds)
                    $.each(response.updatedIds, function (i, updatedId) {
                        $('#js-bulkverify-tbody tr[data-fldid="' + updatedId + '"]').remove();
                    });
            }
        });
    } else
        showAlert('Select atleast one test to change status.', 'Fail');
});

$(document).on('click', '#js-bulkverify-print-btn', function () {
    var newtext = $('#input-check-new').prop('checked') ? 'new' : '';
    var printed = $('#input-check-printed').prop('checked') ? 'printed' : '';
    var markprinted = $('#input-check-mark-printed').prop('checked') ? 'markprinted' : '';

    $('#js-bulkprint-new-hidden-input').val(newtext);
    $('#js-bulkprint-printed-hidden-input').val(printed);
    $('#js-bulkprint-markprinted-hidden-input').val(markprinted);

    var fldid = [];
    $.each($('#js-bulkverify-tbody input.js-printing-labtest-checkbox:checked'), function (i, elem) {
        fldid.push($(elem).closest('tr').data('fldid'));
    });

    if (fldid.length > 0) {
        var querystring = $('#js-bulkverify-form').serialize();
        querystring += '&testIds=' + fldid.join(',');

        if (printed == '') {
            $.each($('#js-bulkverify-tbody tr td:nth-child(2) input:checked'), function (i, elem) {
                $(elem).closest('tr').remove();
            });
        }

        var url = baseUrl + '/admin/laboratory/bulk/printReport?' + querystring;
        window.open(url);
    } else
        showAlert('Select atleast one test to view report.', 'Fail');
});

$(document).on('click', '#js-bulkverify-refresh-btn', function (e) {
    e.preventDefault();

    var newtext = $('#input-check-new').prop('checked') ? 'new' : '';
    var printed = $('#input-check-printed').prop('checked') ? 'printed' : '';
    var markprinted = $('#input-check-mark-printed').prop('checked') ? 'markprinted' : '';

    $('#js-bulkprint-new-hidden-input').val(newtext);
    $('#js-bulkprint-printed-hidden-input').val(printed);
    $('#js-bulkprint-markprinted-hidden-input').val(markprinted);

    $('#js-bulkverify-form').trigger('submit');
});

function changeQuantityVerify(fldid, quali, fldtestid) {
    $('.file-form-data').empty();
    $(".file-form-data").append('<form id="change-quantity-form" method="POST">');
    $(".file-modal-title").empty().text(fldtestid);
    $(".file-form-data form").append('<input type="text" value="' + quali + '" name="quantity_update" id="quantity_update" class="form-control"/>');
    $(".file-form-data form").append('<input type="hidden" value="' + fldid + '" name="quantity_fldid" id="quantity_fldid"/>');

    // $('.observation-modal-data').html(form);
    $("#file-modal .modal-dialog").removeClass('modal-lg').addClass('modal-sm');
    $('#file-modal').modal('show');
}

function updateQuantity() {
    // alert('here');
    $.ajax({
        url: baseUrl + '/admin/laboratory/verify/change-verify-quantity',
        type: "POST",
        data: $('#change-quantity-form').serialize(),
        dataType: "json",
        success: function (response) {
            var status = response.status ? 'success' : 'fail';
            showAlert(response.message, status);

            $('.quantity-' + $('#quantity_fldid').val()).empty().text($('#quantity_update').val());

            $('#file-modal').modal('hide');

            if (response.status) {
                $('#js-printing-show-btn').trigger('click');
                $('#js-bulkverify-refresh-btn').trigger('click');
            }
        }
    });
}

var method = {
    saveSelectOnChange: function (currentElem) {
        var fldmethod = $(currentElem).val() || '';
        if (fldmethod != '') {
            $.ajax({
                url: baseUrl + "/admin/laboratory/reporting/updateMethod",
                type: "POST",
                data: {
                    fldid: $(currentElem).closest('tr').data('fldid'),
                    fldmethod: fldmethod
                },
                dataType: "json",
                success: function (response) {
                    var status = response.status ? 'success' : 'fail';
                    showAlert(response.message, status);
                }
            });
        } else
            showAlert('Please select method.', 'fail');
    },
};

$(document).on('change', '.js-fixed-components-tr td select.answer', function () {
    var examid = ($('#js-observation-examid-hidden').val() || '').toLowerCase();
    var fldsubtest = $(this).closest('tr').find('input[type="hidden"].fldsubtest').val() || '';
    var answer = ($(this).val() || '').toLowerCase();

    if (fldsubtest == 'Result') {
        var gradingTrElem;
        $.each($(this).closest('tbody').find('tr.js-fixed-components-tr'), function (i, elems) {
            if ($(elems).find('input[type="hidden"].fldsubtest').val() == 'Grading')
                gradingTrElem = elems;
        });

        if (answer == 'afb seen')
            $(gradingTrElem).show();
        else
            $(gradingTrElem).hide();
    }
});

$(document).on('click', '#js-sampling-labtest-tbody tr', function () {
    selected_td('#js-sampling-labtest-tbody tr', this);
});

$(document).on('click', '#js-sampling-sample-barcode-reprint', function () {
    var encounter = $('#js-sampling-encounterid-input').val() || '';
    // var testids = $('#js-sampling-labtest-tbody tr[is_selected="yes"] td:nth-child(3) input.js-sampling-labtest-checkbox-for-worksheet').val() || '';
    // var sampleid = $('#js-sampling-labtest-tbody tr[is_selected="yes"] td:nth-child(7)').text() || '';
    var testids_localstorage =JSON.parse(localStorage.getItem('testids_localstorage'));
    if (encounter != '' && testids_localstorage!='') {
        // var url = baseUrl + '/admin/laboratory/sampling/barcode?generate_barcode=true&fldencounterval=' + encounter + '&testids[]=' + testids_localstorage;
        // window.open(url, '_blank');

        $.PrintPlugin({
            remotefetch: {
                loadFormRemote : true,
                requestType : "GET",
                origin : baseUrl + '/admin/laboratory/sampling/barcode?generate_barcode=true&fldencounterval=' + encounter + '&testids[]=' + testids_localstorage,
                // responseProperty : 'printview',
                responseProperty : null,
            }
        });
    }
});

$('#js-reporting-search-form input').on('keydown', function (e) {
    if (e.which == 13)
        $('#js-reporting-refresh-btn').trigger('click');
});

$('#js-print-search-div input').on('keydown', function (e) {
    if (e.which == 13)
        $('#js-printing-search-submit-btn').trigger('click');
});

$('#js-sampling-search-form').on('keydown', function (e) {
    if (e.which == 13)
        $('#js-sampling-search-submit-btn').trigger('click');
});

$(document).on('keyup', '#js-reporting-culture-subtable-serach-input-modal', function () {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-reporting-culture-subtable-modal-tbody tr td:first-child'), function (i, e) {
        var tdText = $(e).text().trim().toUpperCase();

        if (tdText.search(searchText) >= 0)
            $(e).closest('tr').show();
        else
            $(e).closest('tr').hide();
    });
});

$(document).on('keyup', '.js-lab-module-name-search-input', function () {
    var searchText = $(this).val().toUpperCase();
    $.each($('.js-lab-module-name-search-tbody tr td.js-patient-name'), function (i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        if (tdText.startsWith(searchText))
            $(e).closest('tr').show();
        else
            $(e).closest('tr').hide();
    });
});
