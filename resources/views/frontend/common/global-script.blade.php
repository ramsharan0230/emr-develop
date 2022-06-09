<script>
    $(document).ready(function () {
        $('#success-for-all-container').hide();
        $('#error-for-all-container').hide();
    })
    var get_abnoraml_btn = function (abnormal) {
        var abnormalVal = (abnormal == '0') ? '<div class="btn btn-success btn-sm"></div>' : '<div class="btn btn-danger btn-sm"></div>';
        return abnormalVal;
    }

    function showAlert(msg, status = 'success', duration = 3000) {
        if (status === "success") {
            // console.log(msg)
            $('#success-for-all-container').show();
            $('#success-for-all').empty();
            $('#success-for-all').html(msg);
            setTimeout(function () {
                $('#success-for-all-container').hide();
            }, duration);
        } else {
            $('#error-for-all-container').show();
            $('#error-for-all').empty();
            $('#error-for-all').html(msg);
            setTimeout(function () {
                $('#error-for-all-container').hide();
            }, duration);
        }
    }

    function selected_td(elemId, currentElem) {
        $(elemId).css('background-color', '#ffffff');
        $(currentElem).css('background-color', '#c8dfff');

        $.each($(elemId), function (i, e) {
            $(e).attr('is_selected', 'no');
        });
        $(currentElem).closest('tr').attr('is_selected', 'yes');
    }

    function selected_li(elemId, currentElem) {
        $(elemId).css('background-color', '#ffffff');
        $(currentElem).css('background-color', '#c8dfff');

        $.each($(elemId), function (i, e) {
            $(e).attr('is_selected', 'no');
        });
        $(currentElem).closest('li').attr('is_selected', 'yes');
    }

    var globalEncounter = $('#encounter_id').val();
    var laboratory = {
        displayModal: function (encId) {
            $('form').submit(false);
            $('.lab-radio-head').empty();
            $('.form-data-for-lab-radio').empty();
            billingmode = $('#billingmode').val();
            // alert(billingmode);
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.laboratory.form') }}',
                type: "POST",
                data: {encounterId: encounterLocal, billingmode: billingmode},
                success: function (response) {
                    // console.log(response);
                    $('.lab-radio-head').empty();
                    $('.lab-radio-head').text('Laboratory');
                    $('.form-data-for-lab-radio').html(response);
                    $('#laboratory-radiology-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var radiology = {
        displayModal: function (encId) {
            $('form').submit(false);
            $('.lab-radio-head').empty();
            $('.form-data-for-lab-radio').empty();
            billingmode = $('#billingmode').val();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.radiology.form') }}',
                type: "POST",
                data: {encounterId: encounterLocal, billingmode: billingmode},
                success: function (response) {
                    // console.log(response);
                    $('.lab-radio-head').text('');
                    $('.lab-radio-head').text('Radiology');
                    $('.form-data-for-lab-radio').empty();
                    $('.form-data-for-lab-radio').html(response);
                    $('#laboratory-radiology-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var pharmacy = {
        displayModal: function (encId) {
            $('form').submit(false);
            $('.pharmacy-form-data').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            $.ajax({
                url: '{{ route('patient.pharmacy.form') }}',
                type: "POST",
                data: {encounterId: encounterLocal},
                success: function (response) {
                    // console.log(response);

                    $('.pharmacy-form-data').html(response);
                    $('#pharmacy-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var services = {
        displayModal: function (encId) {
            $('form').submit(false);
            $('.lab-radio-head').empty();
            $('.form-data-for-lab-radio').empty();
            billingmode = $('#billingmode').val();
            // alert(billingmode);
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.services.form') }}',
                type: "POST",
                data: {encounterId: encounterLocal, billingmode: billingmode},
                success: function (response) {
                    // console.log(response);
                    $('.lab-radio-head').empty();
                    $('.lab-radio-head').text('General Services');
                    $('.form-data-for-lab-radio').html(response);
                    $('#laboratory-radiology-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var ipd_round_request = {
        displayModal: function (encId) {
            $('form').submit(false);
            $('.lab-radio-head').empty();
            $('.form-data-for-lab-radio').empty();
            billingmode = $('#billingmode').val();
            // alert(billingmode);
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route("patient.ipd-round.form") }}',
                type: "POST",
                data: {encounterId: encounterLocal, billingmode: billingmode},
                success: function (response) {
                    $('.lab-radio-head').empty();
                    $('.lab-radio-head').text('IP Round');
                    $('.form-data-for-lab-radio').html(response);
                    $('#laboratory-radiology-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var consultation = {
        displayModal: function (encId) {
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');
            $('.file-modal-title').empty();
            $('.file-form-data').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.menu.request.consultation') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);

                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Consultation');
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

            $('#file-modal').modal('show');
        },
        addConsultation: function (encId) {
            $('.body-consultation-request-list').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.menu.request.consultation.add') }}',
                type: "POST",
                data: $('#consultation-request-submit').serialize(),
                success: function (response) {
                    // console.log(response);
                    $('.patient-current-location').empty();
                    $('.patient-current-location').val(response.currentLocation);
                    $('.body-consultation-request-list').append(response.html);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        deleteConsultation: function (fldid, encId) {
            $('.body-consultation-request-list').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            var confirmDelete = confirm('Delete?');
            if (confirmDelete == false) {
                return false;
            }
            var encounter_id = $('#encounter_id').val();
            $.ajax({
                url: '{{ route('patient.menu.request.consultation.delete') }}',
                type: "POST",
                data: {fldid: fldid, encounter_id: encounter_id},
                success: function (response) {
                    // console.log(response);

                    $('.body-consultation-request-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

// For Admission Request
    var admissionRequest = {
        displayModal: function (encId) {
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');
            $('.file-modal-title').empty();
            $('.file-form-data').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.menu.request.admission') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);

                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Admission');
                    $('.file-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

            $('#file-modal').modal('show');
        },
        addConsultation: function (encId) {
            $('.body-consultation-request-list').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $.ajax({
                url: '{{ route('patient.menu.request.consultation.add') }}',
                type: "POST",
                data: $('#consultation-request-submit').serialize(),
                success: function (response) {
                    // console.log(response);
                    $('.patient-current-location').empty();
                    $('.patient-current-location').val(response.currentLocation);
                    $('.body-consultation-request-list').append(response.html);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        deleteConsultation: function (fldid, encId) {
            $('.body-consultation-request-list').empty();
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            var confirmDelete = confirm('Delete?');
            if (confirmDelete == false) {
                return false;
            }
            var encounter_id = $('#encounter_id').val();
            $.ajax({
                url: '{{ route('patient.menu.request.consultation.delete') }}',
                type: "POST",
                data: {fldid: fldid, encounter_id: encounter_id},
                success: function (response) {
                    // console.log(response);

                    $('.body-consultation-request-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }


    var obstetric = {
        displayModal: function (encId) {
            if (globalEncounter === "" && encId === "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }
            $('.form-data-obstetric').empty();

            $.ajax({
                url: '{{ route('patient.diagnosis.obstetric') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.form-data-obstetric').html(response);
                    $('#diagnosis-obstetric-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var menuMinorProcedure = {
        displayModal: function () {
            $('.file-modal-title').empty();
            $('.file-form-data').empty();
            $('#size').removeClass('modal-dialog modal-lg');
            $('#size').addClass('modal-dialog modal-xl');

            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            var encounter_id = $('#encounter_id').val();
            $.ajax({
                url: '{{ route('patient.minor.procedure.form') }}',
                type: "POST",
                data: {encounterId: encounter_id, billingmode: $('#billingmode').val()},
                success: function (response) {
                    // console.log(response);
                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Minor Procedure');
                    $('.file-form-data').html(response);
                    $('#file-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }

    var dosingRecord = {
        displayModal: function (encId) {
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            $.ajax({
                url: baseUrl + '/inpatient/stat/getDosingRecord',
                type: 'GET',
                data: {encounterId: encounterLocal},
                success: function (data) {
                    var liData = '';
                    $.each(data, function (i, d) {
                        liData += '<li data-fldid="' + d.fldid + '" data-flditem="' + d.flditem + '">' + d.flditem + '</li>'
                    });

                    $('#js-statprn-dosing-record-ul').empty().html(liData);
                }
            });

            $('#js-statprn-modal-medicine-label').text('');
            $('#js-statprn-modal-qty-input').val('0');
            $('#js-statprn-modal-unit-label').text('');
            $('#js-statprn-modal-regimen-label').text('');
            $('#js-statprn-modal-total-dose-input').val('0');
            $('#js-statprn-modal-count-today-input').val('0');
            $('#js-statprn-modal-count-total-input').val('0');
            $('#js-statprn-flddrug-hidden-input').val('');
            $('#js-statprn-fldvolunit-hidden-input').val('');
            $('#js-statprn-flddose-hidden-input').val('');
            $('#js-statprn-fldroute-hidden-input').val('');

            $('#js-statprn-dosing-record-modal').modal('show');
        },
    }

    var changeAbnormalStatus = {
        displayModal: function (tbodyId, tdCount, fldid) {
            $('#js-global-fldid-input').val(fldid);

            var status = ($(tbodyId + ' tr td:nth-child(' + tdCount + ')').find('div.btn').hasClass('btn-danger')) ? '1' : '0';
            $('#js-global-status-select option').attr('selected', false);
            $('#js-global-status-select option[value="' + status + '"]').attr('selected', true);
            $('#js-global-tbodyId-input').val(tbodyId);
            $('#js-global-tdCount-input').val(tdCount);

            $('#js-global-status-modal').modal('show');
        },
        save: function () {
            var fldid = $('#js-global-fldid-input').val() || '';
            var fldabnormal = $('#js-global-status-select').val() || '';
            var location = $(this).attr('location') || 'onexam';

            if (fldabnormal !== '' || fldid !== '') {
                $.ajax({
                    url: baseUrl + '/inpatient/onexamination/changeOnExamStatus',
                    type: "POST",
                    data: {fldid: fldid, fldabnormal: fldabnormal},
                    dataType: "json",
                    success: function (response) {
                        if (response.status) {
                            var tbodyId = $('#js-global-tbodyId-input').val();
                            var tdCount = $('#js-global-tdCount-input').val();
                            var btnElem = tbodyId + ' tr[is_selected="yes"] td:nth-child(' + tdCount + ') div.btn';
                            var addClass = (fldabnormal === '0') ? 'btn-success' : 'btn-danger';
                            var removeClass = (fldabnormal === '0') ? 'btn-danger' : 'btn-success';
                            $(btnElem).removeClass(removeClass);
                            $(btnElem).addClass(addClass);

                            $('#js-global-status-modal').modal('hide');
                        }
                        showAlert(response.message);
                    }
                });
            } else {
                alert('Invalid data selected for update.');
            }
        }
    }

    var ckeditor_textarea = '';
    var td_element = '';
    var updateExamObservation = {
        displayModal: function (currentElem, fldid, examtable) {
            $('#js-global-exam-observation-edit').empty();
            td_element = currentElem;
            $.ajax({
                url: baseUrl + '/global/getExamObservationModal',
                type: 'GET',
                data: {fldid: fldid, examtable: examtable},
                success: function (response) {
                    $('#js-global-exam-observation-edit').html(response);
                    if ($('#js-observation-textarea-input').length !== 0)
                        ckeditor_textarea = CKEDITOR.replace('js-observation-textarea-input');

                    $('#js-global-exam-observation-edit').modal('show');
                }
            });
        },
        updateObservation: function (currentElem) {
            var modalElement = $(currentElem).closest('.modal');
            var fldid = $(modalElement).find('#js-observation-fldid-hidden').val();
            var examOption = $(modalElement).find('#js-observation-type-hidden').val();
            var qualitative = '';
            var quantative = '0';

            if (examOption === 'Clinical Scale') {
                quantative = 0;
                qualitative = "{";
                $.each($(modalElement).find('.js-observation-scale-text'), function (i, e) {
                    var valueee = $(e).val();
                    quantative += Number(valueee);

                    qualitative += "\"" + $(this).closest('tr').find('td.title').text().trim() + "\": " + valueee + ", ";
                });
                qualitative = qualitative.substring(0, qualitative.length - 2);
                qualitative += "}";
            } else if (examOption === 'Left and Right') {
                qualitative = "{\"Left\": \"" + $(modalElement).find('#js-observation-left-tbody').val() + "\", \"Right\": \"" + $(modalElement).find('#js-observation-right-tbody').val() + "\"}";
            } else if (examOption == 'No Selection') {
                qualitative = $(modalElement).find('#js-observation-input-element').val();
                quantative = qualitative;
            } else if (examOption == 'Fixed Components' || examOption == 'Text Table' || examOption == 'Text Addition' || examOption == 'Text Reference') {
                qualitative = ckeditor_textarea.getData();
            } else {
                qualitative = $(modalElement).find('#js-observation-input-element').val();
            }

            var data = {
                fldid: fldid,
                qualitative: qualitative,
                quantative: quantative,
            };

            $.ajax({
                url: baseUrl + '/global/updateExamObservation',
                type: 'POST',
                data: data,
                success: function (response) {
                    showAlert(response.message);
                    var observationVal = (examOption == 'No Selection' || examOption == 'Clinical Scale') ? data.quantative : data.qualitative;

                    $('#js-global-exam-observation-edit').modal('hide');
                    $(td_element).html(observationVal);
                }
            });
        },
    }

    var essenseExam = {
        displayModal: function (encId) {
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            getEssentialExaminations('#js-prog-essential-tbody');
            getPatientColor('#js-prog-main-color', encounterLocal);

            $('#js-prog-essential-modal').modal('show');
        },
    }

    var triageExam = {
        displayModal: function (encId) {

            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }


            getPatientColor('span.table-patient-img', encounterLocal);

            $.ajax({
                url: baseUrl + '/inpatient/onexamination/getExaminationsOptions',
                type: "GET",
                success: function (data) {
                    var optionData = '';
                    optionData += '<option value="">-- Select --</option>';
                    $.each(data.data, function (i, option) {
                        optionData += '<option value="' + option.fldexamid + '" data-opt="' + option.fldoption + '">' + option.fldexamid + '</option>';
                    });
                    $('#js-triage-examination-option').empty().html(optionData);
                }
            });
            $.ajax({
                url: baseUrl + '/inpatient/onexamination/getExaminations',
                type: "GET",
                data: {date: 'all', examid: 'Triage examinations', encounterId: encounterLocal},
                dataType: "json",
                success: function (data) {
                    var trData = '';
                    $.each(data.examinations, function (i, val) {
                        var observationVal = (val.fldoption == 'No Selection' || val.fldoption == 'Clinical Scale') ? val.fldrepquanti : val.fldrepquali;
                        var abnormalVal = get_abnoraml_btn(val.fldabnormal);
                        trData += '<tr data-fldid="' + val.fldid + '">';
                        trData += '<td>' + (i + 1) + '</td>';
                        trData += '<td>' + val.fldtime + '</td>';
                        trData += '<td>' + val.fldhead + '</td>';
                        trData += '<td onclick="changeAbnormalStatus.displayModal(\'#js-triage-examinations-list\', 4, ' + val.fldid + ')">' + abnormalVal + '</td>';
                        trData += '<td>' + observationVal + '</td>';
                        trData += '<td onclick="triageExam.deleteExam(this, ' + val.fldid + ')"><i class="far fa-trash-alt"></i></td></tr>';
                    });
                    $('#js-triage-examinations-list').empty().html(trData);
                }
            });
            $('#js-menu-triage-modal').modal('show');
        },
        deleteExam: function (currentElem, fldid) {
            if (confirm('Are you sure to delete?')) {
                $.ajax({
                    url: baseUrl + '/inpatient/onexamination/deleteExamination',
                    type: "POST",
                    data: {fldid: fldid},
                    dataType: "json",
                    success: function (data) {
                        if (data.status)
                            $(currentElem).closest('tr').remove();

                        showAlert(data.message);
                    }
                });
            }
        }
    }

    var getPatientColor = function (element, encounterId) {
        $.ajax({
            url: baseUrl + '/inpatient/prog/getColor',
            type: "GET",
            data: {encounterId: encounterId},
            success: function (data) {
                $(element).css('background-color', data);
            }
        });
    }

    var getEssentialExaminations = function (element) {
        $.ajax({
            url: baseUrl + '/inpatient/prog/getEssentialExamination',
            type: "GET",
            success: function (data) {
                if (data.status) {
                    $(element).html(data.html);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    var getPatientProfileColor = function (encounterId) {
        if (encounterId !== undefined || encounterId !== '')
            encounterId = globalEncounter;

        if (encounterId !== undefined || encounterId !== '') {
            $.ajax({
                url: baseUrl + '/inpatient/prog/getColor',
                type: "GET",
                data: {encounterId: encounterId},
                success: function (data) {
                    element = document.getElementById("traicolor");
                    if (typeof (element) != 'undefined' && element != null)
                        $(".traicolor").css("background-color", data);
                    else
                        $(".traicolor").css("background-color", data);
                }
            });
        }
    }

    var demographics = {
        displayModal: function (encId) {
            if (encId) {
                encounterLocal = encId;
            } else {
                encounterLocal = globalEncounter;
            }

            $.ajax({
                url: baseUrl + '/inpatient/dataEntryMenu/getClinicalDemographics',
                type: "GET",
                data: {encounterId: encounterLocal},
                success: function (data) {
                    var trData = '';
                    $.each(data, function (i, val) {
                        trData += '<tr>';
                        trData += '<td>' + (i + 1) + '</td>';
                        trData += '<td>' + (val.flditem == null ? '' : val.flditem) + '</td>';
                        trData += '<td>' + val.fldreportquali + '</td></tr>';
                    });
                    $('#js-clinical-demographics-tbody').empty().html(trData);
                }
            });
            $('#js-clinical-demographics-modal').modal('show');
        },
    }

    var allergyfreetext = {
        displayModal: function () {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            $('.form-data-allergy-freetext').empty();
            $.ajax({
                url: '{{ route('patient.allergy.freetext') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    $('.form-data-allergy-freetext').html(response);
                    $('#allergy-freetext-modal').modal('show');
                    // $('#allergy-freetext-modal').on('show.bs.modal', function (event) {
                    //     $('#custom_allergy').focus();
                    // });
                    setTimeout(function () {
                        $('#custom_allergy').focus();
                    }, 1500);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }
    var diagnosisfreetext = {
        displayModal: function () {
            // alert('obstetric');

            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            $('.form-data-diagnosis-freetext').empty();
            $.ajax({
                url: '{{ route('patient.diagnosis.freetext') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response); 
                    $('.form-data-diagnosis-freetext').html(response);
                    $('#diagnosis-freetext-modal').modal('show');
                    setTimeout(function () {
                        $('#custom_diagnosis').focus();
                    }, 300);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        }
    }
    var equipment = {
        displayModal: function () {

            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            $('.file-modal-title').empty();
            $('.file-form-data').empty();
            $.ajax({
                url: '<?php echo e(route('patient.minor.equipment.form')); ?>',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    console.log(response);
                    $('#size').removeClass('modal-dialog modal-lg');
                    $('#size').addClass('modal-dialog modal-xl');
                    $('.file-modal-title').empty();
                    $('.file-modal-title').text('Equipment');
                    $('.file-form-data').html(response);
                    $('#file-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var testGroup = {
        displayModal: function (modulename, type, encounterIdSelector) {
            var encounterId = $(encounterIdSelector).val() || '';
            var billingmode = $('#user_billing_mode').val() || '';

            if (encounterId !== '') {
                if (type == 'group')
                    $('#js-header').show();
                else
                    $('#js-header').hide();

                $('#js-general-test-group-modulename-modal').val(modulename);
                $('#js-general-test-group-type-modal').val(type);
                $('#js-general-test-group-encounterId-modal').val(encounterId);
                $.ajax({
                    url: baseUrl + '/admin/laboratory/sampling/getTestGroupList',
                    type: "GET",
                    data: {modulename: modulename, type: type, billingmode: billingmode},
                    success: function (data) {
                        var trData = '';
                        $.each(data, function (i, val) {
                            trData += '<tr><td style="background-color: #fff;"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="' + val.fldexamid + '" value="' + val.fldexamid + '"><label class="custom-control-label">' + val.fldexamid + '</label></div></td></tr>';
                        });
                        $('#js-general-test-group-list-tbody').empty().html(trData);
                    }
                });
                $('#js-general-test-group-modal').modal('show');
            }
        },
        save: function () {
            var testids = [];
            $.each($('input.custom-control-input[type="checkbox"]:checked'), function (i, e) {
                testids.push($(e).val());
            });

            if (testids.length !== 0) {
                var modulename = $('#js-general-test-group-modulename-modal').val();
                var type = $('#js-general-test-group-type-modal').val();
                var encounterId = $('#js-general-test-group-encounterId-modal').val();
                var tableSelector = (modulename == 'lab') ? '#js-addition-labtest-tbody' : '#js-sampling-labtest-tbody';

                $.ajax({
                    url: baseUrl + '/admin/laboratory/sampling/saveTestGroupList',
                    type: "POST",
                    data: {testids: testids, modulename: modulename, type: type, encounterId: encounterId},
                    dataType: "json",
                    success: function (response) {
                        if (response.status) {
                            var checkbox_class = (modulename == 'lab') ? 'js-addition-labtest-checkbox' : 'js-addition-labtest-checkbox';
                            var length = tableSelector + ' tr';
                            length = $(length).length || 0;
                            var trData = '';

                            if (modulename == 'lab') {
                                $.each(response.data, function (i, e) {
                                    trData += '<tr>';
                                    trData += '<td>' + (++length) + '</td>';
                                    trData += '<td><input type="checkbox" class="' + checkbox_class + '" value="' + e.fldid + '"></td>';
                                    trData += '<td>' + e.fldtestid + '</td>';
                                    trData += '<td>' + e.fldmethod + '</td>';
                                    trData += '<td></td>';
                                    trData += '<td></td>';
                                    trData += '<td>' + (e.fldsampletype ? e.fldsampletype : '') + '</td>';
                                    trData += '<td>' + (e.fldvial ? e.fldvial : '') + '</td>';
                                    trData += '<td></td></tr>';
                                });
                            } else if (modulename == 'radio') {
                                $.each(response.data, function (i, e) {
                                    trData += '<tr data-fldid="' + e.fldid + '" fldmethod="' + e.fldmethod + '" flvisible="' + e.flvisible + '" fldcomment="" fldcondition="" fldsampletype="">';
                                    trData += '<td>' + (++length) + '</td>';
                                    trData += '<td><input type="checkbox" class="' + checkbox_class + '" value="' + e.fldid + '"></td>';
                                    trData += '<td>' + e.fldtestid + '</td>';
                                    trData += '<td onclick="changeObservation.showModal(' + e.fldid + ', \'' + e.fldtestid + '\')"></td>';
                                    trData += '<td class="flvisible" onclick="changeRadioData.showModal(\'flvisible\')">' + e.flvisible + '</td>';
                                    trData += '<td class="fldmethod" onclick="changeRadioData.showModal(\'fldmethod\')">' + e.fldmethod + '</td>';

                                    trData += '<td class="fldsampletype" onclick="changeRadioData.showModal(\'fldsampletype\')"></td>';
                                    trData += '<td>' + (e.fldtime_report ? e.fldtime_report : '') + '</td>';
                                    trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addComment(' + e.fldid + ', this)"></textarea></td>';
                                    trData += '<td><textarea cols="5" rows="5" onblur="changeSamplingData.addCondition(' + e.fldid + ', this)"></textarea></td>';

                                    trData += '</tr>';
                                });
                            }

                            $(tableSelector).append(trData);
                        }
                        $('#js-general-test-group-modal').modal('hide');
                        showAlert(response.message);
                    }
                });
            } else
                alert('Please select atleast one test.');
        }
    }

    var finish = {
        displayModal: function () {
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }

            $('#finish_box').modal('show');
        },
    }
    var imagePop = {
        displayModal: function () {
            // alert('obstetric');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.', 'error');
                return false;
            }
            $.ajax({
                url: '{{ route('patient.image.form') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val()},
                success: function (response) {
                    // console.log(response);
                    $('.form-data-patient-image').html(response);
                    $('#patient-image-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }

    function load_data(query) {
        var term = $('input[name="dccat"]:checked').val();

        $.get("getDiagnosisByCodeSearch", {term: term, query: query}).done(function (data) {
            // Display the returned data in browser
            $("#sublist").html(data);
        });
    }

    $('#search_diagnosis_sublist').keyup(function () {
        // alert('eta');
        var search = $(this).val();

        if (search != '') {
            load_data(search);
        } else {
            load_data();
        }

    });


    $('#deletealdiagno').on('click', function () {
        if (confirm('Delete Diagnosis ??')) {
            $('#select-multiple-diagno').each(function () {
                var finalval = $(this).val().toString();
                var url = $('.delete_pat_findings').val();

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {ids: finalval},
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Data Deleted !!');
                            $('#select-multiple-diagno option:selected').remove();
                        } else
                            showAlert('Something went wrong!!', 'error');
                    }
                });
            });
        }
    });

    function updateDiagnosis() {
        // alert('diagn');
        var url = "{{route('diagnosisStore')}}";

        $.ajax({
            url: url,
            type: "POST",
            data: $("#opd-diagnosis").serialize(), "_token": "{{ csrf_token() }}",
            success: function (response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-diagno').html(response);
                $('#diagnosis').modal('hide');
                showAlert('Data Added !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!', 'error);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function saveAllergyDrugs() {
        // alert('add allergy drugs');

        var url = "{{route('allergydrugstore')}}";
        $.ajax({
            url: url,
            type: "POST",
            data: $("#allergyform").serialize(), "_token": "{{ csrf_token() }}",
            success: function (response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-aldrug').empty().append(response);
                $('#allergicdrugs').modal('hide');
                showAlert('Data Added !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!', 'error);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    $('#searchbygroup').on('click', function () {
        // alert('searchbygroup');
        var groupname = $('#diagnogroup').val();
        // alert(groupname);
        if (groupname.length > 0) {
            $.get("getDiagnosisByGroup", {term: groupname}).done(function (data) {
                // Display the returned data in browser
                $("#diagnosiscat").html(data);
            });
        }
    });
    $('#searchbygroups').on('click', function () {
        // alert('searchbygroup');
        var groupname = $('#diagnogroup').val();
        // alert(groupname);
        if (groupname.length > 0) {
            $.get("getDiagnosisByGroup", {term: groupname}).done(function (data) {
                // Display the returned data in browser
                $("#procedureExcel").html(data);
            });
        }
    });
    $('#closesearchgroup').on('click', function () {
        $('#diagnogroup').val('');
        $.get("getInitialDiagnosisCategoryAjax", {term: ''}).done(function (data) {
            // Display the returned data in browser
            $("#diagnosiscat").html(data);
        });

    });

    $('.onclose').on('click', function () {

        $('input[name="dccat"]').prop("checked", false);
        $('#code').val('');
        $("#diagnosissubname").val('');
        $("#sublist").val('');
    });
</script>
