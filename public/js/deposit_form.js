$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

var depositForm = {
    getExpensesList: function () {
        encounter = $("#encounter_id").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }

        $.ajax({
            url: baseUrl + '/depositForm/get-expenses',
            type: "POST",
            data: {encounter: encounter},
            success: function (response) {
                // console.log(response);
                if (response.success.status) {
                    $("#expenses-table").empty().append(response.success.html);
                } else {
                    showAlert('Something went wrong.', 'error')
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert('Something went wrong.')
            }
        });
    },
    getInvoiceList: function () {
        encounter = $("#encounter_id").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }

        $.ajax({
            url: baseUrl + '/depositForm/get-invoices',
            type: "POST",
            data: {encounter: encounter},
            success: function (response) {
                // console.log(response);
                if (response.success.status) {
                    $("#invoice-table").empty().append(response.success.html);
                } else {
                    showAlert('Something went wrong.', 'error')
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert('Something went wrong.')
            }
        });
    },
    saveComment: function () {
        encounter = $("#encounter_id").val();
        comment = $("#comment").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }
        $.ajax({
            url: baseUrl + '/depositForm/save-comment',
            type: "POST",
            data: {encounter: encounter, comment: comment},
            success: function (response) {
                // console.log(response);
                if (response.success.status) {
                    showAlert('Comment added successfully.')
                } else {
                    showAlert('Something went wrong.', 'error')
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert('Something went wrong.')
            }
        });
    },
    saveDiaryNumber: function () {
        encounter = $("#encounter_id").val();
        diary_number = $("#diary_number").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }
        $.ajax({
            url: baseUrl + '/depositForm/save-diary-number',
            type: "POST",
            data: {encounter: encounter, diary_number: diary_number},
            success: function (response) {
                // console.log(response);
                if (response.success.status) {
                    showAlert('Diary number added successfully.');
                } else {
                    showAlert('Something went wrong.', 'error')
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert('Something went wrong.')
            }
        });
    },
    saveGuardian: function() {
        var encounter = $("#encounter_id").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }

        $.ajax({
            url: baseUrl + '/depositForm/save-admit-consultant',
            type: "POST",
            data: {
                encounter: encounter,
                fldptguardian: $('#js-depositform-guardian').val(),
                fldrelation: $('#js-depositform-relation').val(),
            },
            success: function (response) {
                if (response.success.status) {
                    showAlert('Information updated.');
                } else {
                    showAlert('Something went wrong.', 'error');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                showAlert('Something went wrong.')
            }
        });

    }
}

// $('#admitted').change(function() {
//             var encounter = $("#encounter_id").val();
//             if (encounter === "") {
//                 showAlert('Enter encounter first.', 'error');
//                 return false;
//             }

//             if(encounter.substring(0, 2) != "IP" && encounter.substring(0, 2) != "ER"){
//                 showAlert("Only Inpatient and Emergency patient can be admitted!","error");
//                 return false;
//             } else {
//                 $('#assign-bed-emergency').modal('show');
//             }
//         });
$('#admitted').next().on("click",function(event) {
    $('#admitted').trigger('click');
});
$('#assign-bed-emergency').on('hidden.bs.modal', function () {
    if(!$('#assign-bed-emergency').hasClass("complete")) {
        $('input[name="admitted"]').prop('checked', false);
    }
});

$('#admitted').on("click",function() {
    if($('input[name="admitted"]').is(':checked'))
    {
        var encounter = $("#encounter_id").val();
        if (encounter === "") {
            showAlert('Enter encounter first.', 'error');
            return false;
        }

        if(encounter.substring(0, 2) != "IP" && encounter.substring(0, 2) != "ER"){
            showAlert("Only Inpatient and Emergency patient can be admitted!","error");
            return false;
        } else {
            $('#assign-bed-emergency').removeClass("complete");
            $('#assign-bed-emergency').modal('show');
        }
    }
});

$('#js-deposit-form-submit-button').click(function (e) {
    e.preventDefault();
    var encounterId = $('#encounter_id').val() || '';
    var receivedAmount = $('input[name="received_amount"]').val() || '';
    var depositFor = $('select[name="deposit_for"]').val() || '';

    if (encounterId == '') {
        showAlert('Encounter id is empty.', 'fail');
        return false;
    }

    if (depositFor == '') {
        showAlert('Please select deposit for value.', 'fail');
        return false;
    }

    if (receivedAmount == '') {
        showAlert('Received amount cannot be empty.', 'fail');
        return false;
    }

    var paymode = $("input[name='payment_mode']:checked").val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }

    var formData = $('#js-deposit-form').serialize();
    formData += '&encounterId=' + encounterId;
    $.ajax({
        url: baseUrl + "/depositForm/saveDeposit",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            var status = response.status ? 'success' : 'fail';
            if (response.status) {
                $('#js-deposit-form-prevDeposit-input').val(response.data.previousDeposit);
                $('#js-deposit-form-currDeposit-input').val(response.data.receivedAmount);
                hideAll();
                depositForm.getInvoiceList();
                $('#js-deposit-form')[0].reset();

                var url = baseUrl + '/depositForm/printBill?fldbillno=' + response.data.billno;
                window.open(url, '_blank');
            }
            showAlert(response.message, status);
        }
    });
});

$('#js-deposit-form-submit-button-credit-clerance').click(function (e) {
    e.preventDefault();
    var encounterId = $('#encounter_id').val() || '';
    var receivedAmount = $('input[name="received_amount"]').val() || '';
    var depositFor = $('select[name="deposit_for"]').val() || '';

    if (encounterId == '') {
        showAlert('Encounter id is empty.', 'fail');
        return false;
    }

    if (depositFor == '') {
        showAlert('Please select deposit for value.', 'fail');
        return false;
    }

    if (receivedAmount == '') {
        showAlert('Received amount cannot be empty.', 'fail');
        return false;
    }

    var paymode = $("input[name='payment_mode']:checked").val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }

    var formData = $('#js-deposit-form').serialize();
    formData += '&encounterId=' + encounterId;
    $.ajax({
        url: baseUrl + "/deposit-credit/save-deposit",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            var status = response.status ? 'success' : 'fail';
            if (response.status) {
                $('#js-deposit-form-prevDeposit-input').val(response.data.previousDeposit);
                $('#js-deposit-form-currDeposit-input').val(response.data.receivedAmount);
                hideAll();
                depositForm.getInvoiceList();
                $('#js-deposit-form')[0].reset();

                var url = baseUrl + '/deposit-credit/printBill?fldbillno=' + response.data.billno;
                window.open(url, '_blank');
            }
            showAlert(response.message, status);
        }
    });
});

$('.js-deposit-form-sticker-btn').click(function () {
    let patientId = $('#patient_id').val() || '';
    let fldencounterval = $('#encounter_id').val() || '';
    if (patientId !== '') {

        window.open(baseUrl + '/registrationform/printticket/' + patientId + '?fldencounterval=' + fldencounterval, '_blank');

    } else {
        showAlert("Enter Patient ID", 'Error');
    }

});

$('.js-deposit-form-band-btn').click(function () {
    let patientId = $('#patient_id').val() || '';
    if (patientId !== '') {
        window.open(baseUrl + '/registrationform/print-bar-code/' + patientId, '_blank');
    } else {
        showAlert("Enter Patient ID", 'Error');
    }
});

$('#js-deposit-form-return-btn').click(function () {
    let encounterId = $('#encounter_id').val() || '';
    if (encounterId !== '') {
        window.location.href = baseUrl + '/depositForm/returnDeposit';
    } else {
        showAlert("Enter Encounter ID", 'Error');
    }
});
