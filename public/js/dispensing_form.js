$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

var currentDispensingDepartment = 'InPatient';

function getDepartments(dispensingDepartment) {
    if (dispensingDepartment != currentDispensingDepartment) {
        currentDispensingDepartment = dispensingDepartment;

        var usedOwnElem = $('input[name="fldorder"][type="radio"][value="usedown"]');
        if (dispensingDepartment == 'OutPatient')
            $(usedOwnElem).hide();
        else
            $(usedOwnElem).show();

        $.ajax({
            url: baseUrl + '/dispensingList/getDepartments',
            type: "GET",
            data: {dispensingDepartment: dispensingDepartment},
            dataType: "json",
            success: function (response) {
                var optionData = '<option value="" disabled selected>-- Select --</option>';
                $.each(response, function (i, option) {
                    optionData += '<option value="' + option.flddept + '">' + option.flddept + '</option>';
                });
                $('#js-dispensinglist-department-select').empty().html(optionData);
            }
        });
    }
}


$(document).on('click', '#js-dispensinglist-refresh-btn', function () {

    var currentlocation = $('#js-dispensinglist-department-select').val();

    $.ajax({
        url: baseUrl + '/dispensingList/getPatients',
        type: "GET",
        data: $('#js-dispenseinglist-form').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, elem) {
                if(elem.encounter.fldcurrlocat){
                    var location  = elem.encounter.fldcurrlocat;
                }else{
                    var location = '';
                }
                trData += '<tr data-fldencounterval="' + elem.encounter.fldencounterval + '">';
                trData += '<td>' + (i + 1) + '</td>';
                trData += '<td>' + location + '</td>';
                trData += '<td>' + elem.encounter.fldencounterval + '</td>';
                trData += '<td>' + (elem.encounter && elem.encounter.patient_info ? elem.encounter.patient_info.fldfullname : '') + '</td>';
                trData += '</tr>';
            });
            $('#js-dispensinglist-patientlist-tbody').empty().html(trData);
        }
    });

});

$(document).on('click', '#js-dispenseinglist-dispensigform-buttom', function () {
    var fldencounterval = $('#js-dispensinglist-patientlist-tbody tr[is_selected="yes"]').data('fldencounterval') || '';
    if (fldencounterval != '')
        window.open(baseUrl + '/dispensingForm?encounter_id=' + fldencounterval, '_blank');
});

$(document).on('click', '#js-dispensinglist-patientlist-tbody tr', function () {
    selected_td('#js-dispensinglist-patientlist-tbody tr', this);
    $.ajax({
        url: baseUrl + '/dispensingList/getPatientMedicines',
        type: "GET",
        data: {encounterId: $(this).data('fldencounterval'), fldlevel: $('#js-dispensinglist-status-select').val()},
        dataType: "json",
        success: function (response) {
            var address = (response.patientInfo.patient_info.fldptaddvill ? response.patientInfo.patient_info.fldptaddvill : '')
                + ', '
                + (response.patientInfo.patient_info.fldptadddist ? response.patientInfo.patient_info.fldptadddist : '');
            $('#js-dispensinglist-fullname-input').val(response.patientInfo.patient_info.fldfullname);
            $('#js-dispensinglist-address-input').val(address);
            $('#js-dispensinglist-gender-input').val(response.patientInfo.patient_info.fldptsex);
            $('#js-dispensinglist-location-input').val(response.patientInfo.fldcurrlocat);
            $('#patient_encounter').val(response.patientInfo.fldencounterval);
            var trData = '';
            $.each(response.medicines, function (i, medicine) {
                trData += '<tr data-fldid="' + medicine.fldid + '" data-flduserid_order="' + medicine.flduserid_order + '" data-fldencounterval="' + medicine.fldencounterval + '">';
                trData += '<td>' + (i + 1) + '</td>';
                trData += '<td>' + medicine.fldtime_order + '</td>';
                trData += '<td>' + medicine.fldroute + '</td>';
                trData += '<td>' + medicine.flditem + '</td>';
                trData += '<td onclick="changeQuantity.showModal(\'Dose\', \'' + medicine.fldid + '\', this)">' + medicine.flddose + '</td>';
                trData += '<td onclick="changeQuantity.showModal(\'Frequency\', \'' + medicine.fldid + '\', this)">' + medicine.fldfreq + '</td>';
                trData += '<td onclick="changeQuantity.showModal(\'Day\', \'' + medicine.fldid + '\', this)">' + medicine.flddays + '</td>';
                trData += '<td>' + medicine.fldqtydisp + '</td>';
                trData += '<td><input type="checkbox"  class="js-dispensing-label-checkbox" value="' + medicine.fldid + '"></td>';
                trData += '<td>' + '&nbsp;' + '</td>';
                trData += '<td><button type="button" class="btn btn-primary btn-sm js-dispensinglist-dispense-btn">Dispense</button></td>';
                trData += '</tr>';
            });
            $('#js-dispensinglist-medicinelist-tbody').empty().html(trData);
        }
    });
});

$(document).on('click', '.js-dispensinglist-dispense-btn', function () {
    var fldid = $(this).closest('tr').data('fldid');
    var fldencounterval = $(this).closest('tr').data('fldencounterval');
    if (fldid != '' || fldencounterval != '')
        window.open(baseUrl + '/dispensingList/dispense?fldid=' + fldid + '&fldencounterval=' + fldencounterval, '_blank');
});

var changeQuantity = {
    showModal: function (type, fldid, currentElem) {
        var flduserid_order = $(currentElem).closest('tr').data('flduserid_order');
        var currentUser = $('#js-dispensinglist-currentUser-input').val();

        if (flduserid_order == currentUser) {
            var quantity = $(currentElem).text().trim();
            $('#js-dispensinglist-modal-span').val(type);
            $('#js-dispensinglist-modal-type-input').val(type);
            $('#js-dispensinglist-modal-fldid-input').val(fldid);
            if (type == 'Frequency') {
                $('#js-dispensinglist-modal-qty-input').attr('disabled', true).hide();
                $('#js-dispensinglist-modal-freq-input').attr('disabled', false).show();
                $('#js-dispensinglist-modal-freq-input').val(quantity);
            } else {
                $('#js-dispensinglist-modal-freq-input').attr('disabled', true).hide();
                $('#js-dispensinglist-modal-qty-input').attr('disabled', false).show();
                $('#js-dispensinglist-modal-qty-input').val(quantity);
            }

            $('#js-dispensinglist-change-data').modal('show');
        } else
            showAlert('Unauthorize access!!', 'fail');
    },
    saveData: function () {
        var type = $('#js-dispensinglist-modal-type-input').val();
        var quantity = '';
        if (type == 'Frequency')
            quantity = $('#js-dispensinglist-modal-freq-input').val();
        else
            quantity = $('#js-dispensinglist-modal-qty-input').val();
        var data = {
            fldid: $('#js-dispensinglist-modal-fldid-input').val(),
            type: type,
            quantity: quantity,
        }
        $.ajax({
            url: baseUrl + '/dispensingList/changeQuantity',
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    var column_count = '';
                    if (data.type == 'Dose')
                        column_count = '5';
                    else if (data.type == 'Frequency')
                        column_count = '6';
                    else if (data.type == 'Day')
                        column_count = '7';

                    $('#js-dispensinglist-medicinelist-tbody tr[data-fldid="' + data.fldid + '"] td:nth-child(' + column_count + ')').text(data.quantity);
                }
                showAlert(response.message, (response.status ? 'success' : 'fail'));
            }
        });
        $('#js-dispensinglist-change-data').modal('hide');
    }
}


function getPatientDetailTrString(i, item) {
    var trData = '<tr>';
    trData += '<td>' + (i + 1) + '</td>';
    trData += '<td>' + item.fldordtime + '</td>';
    trData += '<td>' + item.flditemtype + '<input type="hidden" name="itemType[]" class="itemType" value="' + item.flditemtype + '"</td>';
    trData += '<td>' + item.flditemname + '<input type="hidden" name="itemName[]" class="itemName" value="' + item.flditemname + '"></td>';
    trData += '<td>' + numberFormatDisplay(item.flditemrate) + '</td>';
    trData += '<td><input class="form-control" oldqty="' + item.flditemqty + '" onblur="changeQuantityReturn(this)" value="' + item.flditemqty + '"></td>';
    trData += '<td>' + numberFormatDisplay(item.fldtaxper) + '</td>';
    trData += '<td>' + numberFormatDisplay(item.flddiscper) + '</td>';
    trData += '<td>' + numberFormatDisplay(item.fldditemamt) + '</td>';
    trData += '<td>' + (item.fldorduserid ? item.fldorduserid : '') + '</td>';
    trData += '<td><button data-fldid="' + item.fldid + '" class="btn btn-danger btn-sm-in js-btn-return-delete"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
    trData += '</tr>';

    return trData;
}

$(document).on('click', '.js-btn-return-delete', function () {
    var fldid = $(this).data('fldid' || '');
    if (fldid != '' && confirm('Are you sure to delete refund??')) {
        $.ajax({
            url: baseUrl + "/returnForm/deleteReturnEntry",
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status) {
                    $("#js-returnform-show-btn").trigger("click");
                }
                showAlert(response.message, status);
            }
        });
    } else
        showAlert('Invalid id', 'fail');
});

function getPatientDetail() {
    var queryvalue = $('#js-returnform-queryvalue-input').val() || '';
    if (queryvalue != '') {
        $.ajax({
            url: baseUrl + '/returnForm/getPatientDetail',
            type: "GET",
            data: {
                queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
                queryValue: queryvalue
            },
            dataType: "json",
            success: function (response) {
                console.log(response.patbilldetail)
                if (response.status) {
                    clear();
                    if (response.patientInfo) {
                        var address = (response.patientInfo.patient_info.fldptaddvill ? response.patientInfo.patient_info.fldptaddvill : '')
                            + ', '
                            + (response.patientInfo.patient_info.fldptadddist ? response.patientInfo.patient_info.fldptadddist : '');
                        $('#js-returnform-fullname-input').val(response.patientInfo.patient_info.fldfullname);
                        $('#js-returnform-address-input').val(address);
                        $('#js-returnform-gender-input').val(response.patientInfo.patient_info.fldptsex);
                        $('#js-returnform-location-input').val(response.patientInfo.fldcurrlocat);
                        $('#js-returnform-queryvalue-input').val(response.value);
                    }
                    if(response.patbilldetail){
                        $('#billType').text(response.patbilldetail.fldbilltype);
                        $('#paymentMode').text(response.patbilldetail.payment_mode);
                    }
                    if(response.patbilldetail.fldbilltype == 'Cash' && (response.patbilldetail.payment_mode == 'Cash' || response.patbilldetail.payment_mode == 'Card' || response.patbilldetail.payment_mode == 'Fonepay')){
                        $('#credit_payment').hide();
                        $('#cash_payment').show();
                        $('#cash_payment').addClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $("#Credit").removeAttr("checked");
                        $("#Cash").prop("checked", true);
                    }else if(response.patbilldetail.fldbilltype == 'Credit' && response.patbilldetail.payment_mode == 'Credit' && response.patbilldetail.fldpayitemname != 'Discharge Clearence' && response.patbilldetail.fldcurdeposit < 0){
                        $('#credit_payment').show();
                        $('#cash_payment').hide();
                        $('#cash_payment').removeClass('checked-bak');
                        $('#credit_payment').addClass('checked-bak');
                        $("#Credit").prop("checked",true);
                        $("#Cash").removeAttr("checked");
                    }else{
                        $('#credit_payment').show();
                        $('#cash_payment').show();
                        $('#cash_payment').addClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $("#Credit").removeAttr("checked", false);
                        $("#Cash").prop("checked", true);
                    }
                    if (response.items) {
                        var optionData = '<option value="" disabled selected>-- Select --</option>';
                        $.each(response.items, function (i, option) {
                            var dataAttributes = " data-flditemqty='" + option.flditemqty + "'";
                            dataAttributes += " data-fldexpiry='" + option.fldexpiry + "'";
                            dataAttributes += ' data-fldstockno="' + option.fldstockno + '"';
                            dataAttributes += ' data-fldid="' + option.fldid + '"';
                            optionData += '<option value="' + option.itemname + '" ' + dataAttributes + '>' + option.itemname + '</option>';
                        });
                        $('#js-returnform-particulars-select').empty().html(optionData);
                    }

                    var trNewData = '';
                    var subTotal = 0;
                    var netTotal = 0;
                    var totalDiscount = 0;
                    var trSavedData = '';
                    var trLength = $('#js-returnform-return-tbody tr').length || 0;
                    $.each(response.returnItems, function (i, item) {
                        var total = Number(item.flditemrate)*Number(item.flditemqty);
                        var discount = ((total*(item.flddiscper))/100);
                        var tax = (((total - discount)*(item.fldtaxper))/100);
                        netTotal += (Number(total) + Number(tax) - Number(discount));
                        subTotal += (Number(total) + Number(tax));
                        totalDiscount += Number(discount);


                        trNewData += getPatientDetailTrString(trLength++, item);
                    });
                    $('#js-returnform-return-tbody').empty().html(trNewData);
                    $('#sub-total-data').text(subTotal ? numberFormatDisplay(subTotal) : 0);
                    $('#discount-total').text(totalDiscount ? numberFormatDisplay(totalDiscount) : 0);
                    $('#grand-total-data').text(netTotal ? numberFormatDisplay(netTotal) : 0);
                    $('#return-amount').val(netTotal ? numberFormatDisplay(netTotal) : 0);

                    var trLength = $('#js-returnform-saved-tbody tr').length || 0;
                    $.each(response.savedItems, function (i, item) {
                        trSavedData += getPatientDetailTrString(trLength++, item);
                    });
                    $('#js-returnform-saved-tbody').empty().html(trSavedData);
                } else
                    showAlert(response.message, 'fail');
            }
        });
    }
}

$(document).on('keyup', '#return-percentage', function () {
    var total = $('#grand-total-data').text() || '';
    total = parseFloat(numberFormat(total)) || 0;

    var returnPer = $('#return-percentage').val() || '';
    returnPer = parseFloat(numberFormat(returnPer)) || 0;
    $('#return-percentage').val(numberFormatDisplay(returnPer));

    var returnAmt = ((total * returnPer) / 100);
    $('#return-amount').val(numberFormatDisplay(returnAmt));
});


$(document).on('click', '#js-returnform-show-btn', function () {
    getPatientDetail();
});

$('#js-returnform-queryvalue-input').keydown(function (e) {
    if (e.which == 13)
        getPatientDetail();
});

$('#js-returnform-particulars-select').change(function () {
    $('#js-returnform-expiry-input').val($('#js-returnform-particulars-select option:selected').data('fldexpiry') || '');
    $('#js-returnform-qty-input').val($('#js-returnform-particulars-select option:selected').data('flditemqty') || '');
    $('#js-returnform-fldstockno-input').val($('#js-returnform-particulars-select option:selected').data('fldstockno') || '');
    $('#js-returnform-id-input').val($('#js-returnform-particulars-select option:selected').data('fldid') || '');
});

$(document).on('click', '#js-returnform-return-btn', function () {
    var queryValue = $('#js-returnform-queryvalue-input').val() || '';
    var flditemname = $('#js-returnform-particulars-select').val() || '';
    var retqty = $('#js-returnform-retqty-input').val() || '0';
    var reason = $("#js-returnform-reason-input").val() || '';
    var fldstockno = $('#js-returnform-fldstockno-input').val() || '';
    var id = $('#js-returnform-id-input').val() || '';
    if (queryValue == ''){
        showAlert('Invoice no is required', 'fail');
        return;
    }
    if(flditemname == '') {
        showAlert('Item is required.', 'fail');
        return;
    }
    if(reason == '') {
        showAlert('Reason is required.', 'fail');
        return;
    }
    if(retqty == '' || retqty == 0) {
        showAlert('Quantity must be greater than 0.', 'fail');
        return;
    }
    if(fldstockno == '') {
        showAlert('Failed to entry.', 'fail');
        return;
    }
    if(id == '') {
        showAlert('Failed to entry.', 'fail');
        return;
    }

    var qty = $('#js-returnform-qty-input').val() || '0';
    if (Number(retqty) > Number(qty)) {
        showAlert('Return quantity cannot be greater than purchased quantity.', 'fail');
        return;
    }

    $.ajax({
        url: baseUrl + '/returnForm/returnEntry',
        type: "POST",
        data: {
            fldstockno: fldstockno,
            retqty: retqty,
            flditemname: flditemname,
            queryValue: queryValue,
            queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
            fldreason: reason,
            id: id,
        },
        dataType: "json",
        success: function (response) {
            var status = (response.status) ? 'success' : 'fail';
            if (response.status) {
                $("#js-returnform-show-btn").trigger("click");
            }
            showAlert(response.message, status);
        }
    });
});

$(document).on('click', '#saveAndBill', function () {
    var queryvalue = $('#js-returnform-queryvalue-input').val() || '';
    var authorizedby = $('#js-authorizedby-input').val() || '';
    if ($("#js-returnform-return-tbody tr").length == 0) {
        showAlert('Please add an item first', 'error');
        return false;
    }

    var paymode = $("input[name='payment_mode']:checked").val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }

    var queryValue = $('#js-returnform-queryvalue-input').val() || '';
    if (queryValue != '') {
        if ($('#js-returnform-return-tbody tr').length > 0) {
            $.ajax({
                url: baseUrl + '/returnForm/save-and-bill',
                type: "POST",
                data: {
                    queryValue: queryValue,
                    queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
                    returnAmt: $('#return-amount').val() || '',
                    authorizedby: authorizedby,
                    payment_mode : $("input[name='payment_mode']:checked").val(),
                },
                dataType: "json",
                success: function (response) {
                    var status = (response.status) ? 'success' : 'fail';
                    if (response.status) {
                        $("input[type=text], input[type=number]").val("");
                        $('#js-returnform-return-tbody').html("");
                        $('#sub-total-data').html("");
                        $('#discount-total').html("");
                        $('#grand-total-data').html("");
                        showAlert(response.message, status);

                        window.open(baseUrl + '/billing/service/displayReturnBilling?invoice_number=' + response.billno, '_blank');
                    } else {
                        showAlert(response.message, status);
                    }

                    showAlert(response.message, status);
                }
            });
        }
    }
});

function calculateTotal() {
    var subTotal = 0;
    var netTotal = 0;
    var totalDiscount = 0;
    $.each($('#js-returnform-return-tbody tr'), function (i, tr) {
        var taxPer = parseFloat(numberFormat($(tr).find('td:nth-child(7)').text().trim()) || 0);
        var discountPer = parseFloat(numberFormat($(tr).find('td:nth-child(8)').text().trim()) || 0);
        var itemrate = parseFloat(numberFormat($(tr).find('td:nth-child(5)').text().trim()) || 0);
        var quantity = parseFloat(numberFormat($(tr).find('td:nth-child(6) input').val().trim()) || 0);

        var total = parseFloat(numberFormat(itemrate))*parseFloat(numberFormat(quantity));
        var discount = (total * (discountPer)) / 100;
        var tax = ((total-discount) * (taxPer)) / 100;
        netTotal += parseFloat(total) + parseFloat(tax) - parseFloat(discount);
        totalDiscount += parseFloat(discount);
        subTotal += total;
    });

    $('#sub-total-data').text(subTotal ? numberFormatDisplay(subTotal) : 0);
    $('#discount-total').text(totalDiscount ? numberFormatDisplay(totalDiscount) : 0);
    $('#grand-total-data').text(netTotal ? numberFormatDisplay(netTotal) : 0);
    $('#return-amount').val(netTotal ? numberFormatDisplay(netTotal) : 0);
}

function calculateTotals(type,currentdeposit) {
    var subTotal = 0;
    var netTotal = 0;
    var totalDiscount = 0;
    $.each($('#js-returnform-return-tbody tr'), function (i, tr) {
        var taxPer = parseFloat(numberFormat($(tr).find('td:nth-child(7)').text().trim()) || 0);
        var discountPer = parseFloat(numberFormat($(tr).find('td:nth-child(8)').text().trim()) || 0);
        var itemrate = parseFloat(numberFormat($(tr).find('td:nth-child(5)').text().trim()) || 0);
        var quantity = parseFloat(numberFormat($(tr).find('td:nth-child(6) input').val().trim()) || 0);

        var total = parseFloat(numberFormat(itemrate))*parseFloat(numberFormat(quantity));
        var discount = (total * (discountPer)) / 100;
        var tax = ((total-discount) * (taxPer)) / 100;
        netTotal += parseFloat(total) + parseFloat(tax) - parseFloat(discount);
        totalDiscount += parseFloat(discount);
        subTotal += total;
    });
    if(type == 'Credit' && currentdeposit > netTotal){
        if(currentdeposit == 0){
            netTotal = netTotal;
        }else{
            netTotal = 0;
        }

    }else{
        netTotal = netTotal;
    }

    $('#sub-total-data').text(subTotal ? numberFormatDisplay(subTotal) : 0);
    $('#discount-total').text(totalDiscount ? numberFormatDisplay(totalDiscount) : 0);
    $('#grand-total-data').text(netTotal ? numberFormatDisplay(netTotal) : 0);
    $('#return-amount').val(netTotal ? numberFormatDisplay(netTotal) : 0);
}

function displayRoute(moduleName) {
    if (moduleName == 'outpatient')
        $('option[module="other"]').hide();
    else
        $('option[module="other"]').show();
}

displayRoute('outpatient');

$('#js-dispensing-billingmode-select').change(function () {
    getMedicineList();
});

function getMedicineList() {
    setTimeout(function () {
        var orderBy = $('input[type="radio"][name="orderBy"]:checked').val();
        var billtype = $('input[type="radio"][name="radio1"]:checked').val();

        $.ajax({
            url: baseUrl + '/dispensingForm/getMedicineList',
            type: "GET",
            data: {
                orderBy: orderBy,
                is_expired: $('#js-dispensing-isexpired-checkbox').prop('checked'),
                medcategory: $('input[type="radio"][name="medcategory"]:checked').val(),
                billingmode: $('#js-dispensing-billingmode-select').val() || 'General',
                billtype: billtype,
            },
            dataType: "json",
            success: function (response) {
                var trData = '<option value="" disabled selected>--Select--</option>';
                $.each(response, function (i, medicine) {
                    var fldexpiry = medicine.fldexpiry.split(' ')[0];
                    var fldstockid = (orderBy == 'brand') ? medicine.fldbrand : medicine.fldstockid;
                    var dataAttributes = " data-route='" + medicine.fldroute + "'";
                    dataAttributes += " data-fldstockno='" + medicine.fldstockno + "'";
                    dataAttributes += " data-fldid='" + medicine.fldid + "'";
                    dataAttributes += " data-flditemtype='" + medicine.fldcategory + "'";
                    dataAttributes += " data-fldnarcotic='" + medicine.fldnarcotic + "'";
                    dataAttributes += " data-fldpackvol='" + medicine.fldpackvol + "'";
                    dataAttributes += " data-fldvolunit='" + medicine.fldvolunit + "'";
                    dataAttributes += " fldqty='" + medicine.fldqty + "'";

                    trData += '<option value="' + medicine.fldstockid + '" ' + dataAttributes + '>';
                    trData += medicine.fldroute + ' | ';
                    trData += fldstockid + ' | ';
                    trData += medicine.fldbatch + ' | ';
                    trData += fldexpiry + ' | QTY ';
                    trData += medicine.fldqty + ' | Rs. ';
                    trData += medicine.fldsellpr;
                    trData += '</option>';
                });
                $('#js-dispensing-medicine-input').html(trData).select2();

                getPatientMedicine();


            }
        });
    }, 500);
}

$(document).on('click', '#js-dispensing-table-modal tr', function () {
    selected_td('#js-dispensing-table-modal tr', this);
});

$(document).on('change', '#js-dispensing-medicine-input', function () {
    $('#js-dispensing-consultant-hidden-input').val('');
    var currentElem = $('#js-dispensing-medicine-input option[value="' + $('#js-dispensing-medicine-input').val() + '"]');

    var doseunit = $(currentElem).data('fldpackvol') + ' ' + $(currentElem).data('fldvolunit');
    $('#js-dispensing-doseunit-input').val(doseunit);

    var fldnarcotic = ($(currentElem).data('fldnarcotic') || 'No').toLowerCase();
    if (fldnarcotic == 'yes')
        $('#consultant_list').modal('show');
});


$(document).on('click', '#submitconsultant_list', function () {
    var consultant = $('input[type="radio"][name="consultant"]:checked').val() || '';
    var freetextconsultant = $('#newconsultname').val();
    if (consultant !== '') {
        $('#consultant_list').modal('hide');
        $('#js-dispensing-consultant-hidden-input').val(consultant);
    } else if(freetextconsultant !=''){
        $('#consultant_list').modal('hide');
        $('#js-dispensing-consultant-hidden-input').val(freetextconsultant);

    }else{
        showAlert('Please select consultant.', 'fail');
    }
});

function toggleReadonly() {
    if ($('#js-dispensing-medicine-tbody tr').length > 0) {
        $('js-dispensing-billingmode-select').attr('readonly', true);
        $('js-dispensing-billingmode-select').click(function () {
            return false;
        });
    }
}

$(document).on('change', '#discount_type_change', function () {
    var discount_type = $(this).val() || '';
    if (discount_type == 'no_discount') {
        $('#js-dispensing-discounttotal-input').val(0);
        $('#js-dispensing-discount-input').val(0);
        $('#js-dispensing-discount-input').trigger('keyup');
        $('#js-dispensing-discounttotal-input').attr('readonly', true);
        $('#js-dispensing-discount-input').attr('readonly', true);
    } else if  (discount_type == 'fixed') {
        $('#js-dispensing-discounttotal-input').attr('readonly', false);
        $('#js-dispensing-discount-input').attr('readonly', true);
    } else if  (discount_type == 'percentage') {
        $('#js-dispensing-discounttotal-input').attr('readonly', true);
        $('#js-dispensing-discount-input').attr('readonly', false);
    }
});


$(document).on('click', '#js-dispensing-add-btn-modal', function () {

    var selectedTr = $('#js-dispensing-table-modal tr[is_selected="yes"]');
    var particular = $(selectedTr).data('fldstockid') || '';
    var fldsellpr = $(selectedTr).data('fldsellpr') || '';
    var fldqty = $(selectedTr).data('fldqty') || '';
    var flditemtype = $(selectedTr).data('flditemtype') || '';
    if (particular != '') {
        $.ajax({
            url: baseUrl + '/dispensingForm/validateDispense',
            type: "GET",
            data: {
                medicine: particular,
                route: $('#js-dispensing-route-select').val(),
            },
            dataType: "json",
            success: function (response) {
                if (response.count != '0')
                    showAlert(particular + ' was recently dispensed to current patient.', 'Fail');
                else {
                    if (response.meddetail) {
                        $('#js-dispensing-medicine-input').val(response.meddetail.fldstockid);
                        $('#js-dispensing-fldsellpr-input').val(response.meddetail.fldsellpr);
                        $('#js-dispensing-fldqty-input').val(response.meddetail.fldqty);
                        $('#js-dispensing-flditemtype-input').val(flditemtype);
                        $('#js-dispensing-table-modal').empty().html('');
                        $('#js-dispensing-medicine-modal').modal('hide');
                    } else
                        showAlert(particular + '  not in stock.', 'Fail');
                }
            }
        });
    } else
        showAlert('Please select medicine to save.', 'fail');
});

function getTrString(medicine, i = 0) {
    var trData = '<tr data-fldid="' + medicine.fldid + '" data-taxpercentage="' + medicine.fldtaxper + '">';
    if (i == 0) {
        i = $('#js-dispensing-medicine-tbody tr').length + 1
    }
    var itemtype = medicine.flditemtype;
    var ftotal = (medicine.medicine_by_setting ? (medicine.medicine_by_setting.fldsellpr) * (medicine.fldqtydisp) : '0');
    if (itemtype == 'Surgicals') {
        var damount = $('#surgicaldiscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else if (itemtype == 'Medicines') {
        var damount = $('#medicinediscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;

    } else if (itemtype == 'Extra Items') {
        var damount = $('#extradiscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else {
        var finaltot = (medicine.medicine_by_setting ? (medicine.medicine_by_setting.fldsellpr) * (medicine.fldqtydisp) : '0');
    }
    var total = (ftotal) - (medicine.flddiscamt) + (medicine.fldtaxamt);
    trData += '<td>' + i + '</td>';
    trData += '<td>' + medicine.fldroute + '</td>';
    trData += '<td>' + medicine.flditem + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldexpirydateonly : '') + '</td>';
    trData += '<td>' + medicine.flddose + '</td>';
    trData += '<td>' + medicine.fldfreq + '</td>';
    trData += '<td>' + medicine.flddays + '</td>';

    trData += '<td>' + medicine.fldqtydisp + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldsellpr : '0') + '</td>';
    trData += '<td>' + medicine.flduserid_order + '</td>';
    trData += '<td>' + numberFormatDisplay(ftotal) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.fldtaxamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.flddiscamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(total) + '</td>';
    trData += '<td><a href="javascript:void(0);" class="btn btn-primary " onclick="editMedicine(' + medicine.fldid + ')"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete" ><i class="fa fa-trash"></i></a></td>';

    trData += '</tr>';

    return trData;
}

function getTrStringHI(medicine, i = 0) {
    var trData = '<tr data-fldid="' + medicine.fldid + '" data-stocknumber="' + medicine.fldstockno + '" data-taxpercentage="' + medicine.fldtaxper + '">';
    if (i == 0) {
        i = $('#js-dispensing-medicine-tbody tr').length + 1
    }
    var itemtype = medicine.flditemtype;
    var ftotal = (medicine.medicine_by_stock_rate ? (medicine.medicine_by_stock_rate.fldrate) * (medicine.fldqtydisp) : '0');
    if (itemtype == 'Surgicals') {
        var damount = $('#surgicaldiscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else if (itemtype == 'Medicines') {
        var damount = $('#medicinediscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;

    } else if (itemtype == 'Extra Items') {
        var damount = $('#extradiscount').val();
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else {
        var finaltot = (medicine.medicine_by_stock_rate ? (medicine.medicine_by_stock_rate.fldrate) * (medicine.fldqtydisp) : '0');
    }
    var total = (ftotal) - (medicine.flddiscamt) + (medicine.fldtaxamt);
    trData += '<td>' + i + '</td>';
    trData += '<td>' + medicine.fldroute + '</td>';
    trData += '<td>' + medicine.flditem + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldexpirydateonly : '') + '</td>';
    trData += '<td>' + medicine.flddose + '</td>';
    trData += '<td>' + medicine.fldfreq + '</td>';
    trData += '<td>' + medicine.flddays + '</td>';

    trData += '<td>' + medicine.fldqtydisp + '</td>';
    trData += '<td>' + (medicine.medicine_by_stock_rate ? medicine.medicine_by_stock_rate.fldrate : '0') + '</td>';
    trData += '<td>' + medicine.flduserid_order + '</td>';
    trData += '<td>' + numberFormatDisplay(ftotal) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.fldtaxamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.flddiscamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(total) + '</td>';
    trData += '<td><a href="javascript:void(0);" class="btn btn-primary " onclick="editMedicine(' + medicine.fldid + ')"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete" ><i class="fa fa-trash"></i></a></td>';

    trData += '</tr>';

    return trData;
}

function getTrNewString(medicine, fldstockno, i = 0) {
    var trData = '<tr data-fldid="' + medicine.fldid + '" data-taxpercentage="' + medicine.fldtaxper + '" data-stocknumber="' + fldstockno + '">';
    if (i == 0) {
        i = $('#js-dispensing-medicine-tbody tr').length + 1
    }
    // var itemtype = medicine.flditemtype;
    var itemtype = medicine.flditemtype;
    var ftotal = (medicine.medicine_by_setting ? (medicine.medicine_by_setting.fldsellpr) * (medicine.fldqtydisp) : '0');
    if (itemtype == 'Surgicals') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else if (itemtype == 'Medicines') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;

    } else if (itemtype == 'Extra Items') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else {
        var finaltot = (medicine.medicine_by_setting ? (medicine.medicine_by_setting.fldsellpr) * (medicine.fldqtydisp) : '0');
    }
    // alert(discountamount);
    var total = (ftotal) - (medicine.flddiscamt) + (medicine.fldtaxamt);
    trData += '<td>' + i + '</td>';
    trData += '<td>' + medicine.fldroute + '</td>';
    trData += '<td>' + medicine.flditem + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldexpirydateonly : '') + '</td>';
    trData += '<td>' + medicine.flddose + '</td>';
    trData += '<td>' + medicine.fldfreq + '</td>';
    trData += '<td>' + medicine.flddays + '</td>';

    trData += '<td>' + medicine.fldqtydisp + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldsellpr : '0') + '</td>';
    trData += '<td>' + medicine.flduserid_order + '</td>';
    trData += '<td>' + numberFormatDisplay(ftotal) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.fldtaxamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(discountamount) + '</td>';
    trData += '<td>' + numberFormatDisplay(total) + '</td>';
    trData += '<td><a href="javascript:void(0);" class="btn btn-primary " onclick="editMedicine(' + medicine.fldid + ',' + fldstockno +')"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete" ><i class="fa fa-trash"></i></a></td>';

    trData += '</tr>';

    return trData;
}

function getTrNewString1(medicine, fldstockno, i = 0) {
    var trData = '<tr data-fldid="' + medicine.fldid + '" data-taxpercentage="' + medicine.fldtaxper + '" data-stocknumber="' + fldstockno + '">';
    if (i == 0) {
        i = $('#js-dispensing-medicine-tbody tr').length + 1
    }
    var itemtype = medicine.flditemtype;
    var ftotal = (medicine.medicine_by_stock_rate ? (medicine.medicine_by_stock_rate.fldrate) * (medicine.fldqtydisp) : '0');
    if (itemtype == 'Surgicals') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else if (itemtype == 'Medicines') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;

    } else if (itemtype == 'Extra Items') {
        var damount = medicine.flddiscper;
        var discountamount = (damount / 100) * ftotal;
        var finaltot = ftotal - discountamount;
    } else {
        var finaltot = (medicine.medicine_by_stock_rate ? (medicine.medicine_by_stock_rate.fldrate) * (medicine.fldqtydisp) : '0');
    }
    // alert(discountamount);
    var total = (ftotal) - (medicine.flddiscamt) + (medicine.fldtaxamt);
    trData += '<td>' + i + '</td>';
    trData += '<td>' + medicine.fldroute + '</td>';
    trData += '<td>' + medicine.flditem + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? medicine.medicine_by_setting.fldexpirydateonly : '') + '</td>';
    trData += '<td>' + medicine.flddose + '</td>';
    trData += '<td>' + medicine.fldfreq + '</td>';
    trData += '<td>' + medicine.flddays + '</td>';

    trData += '<td>' + medicine.fldqtydisp + '</td>';
    trData += '<td>' + (medicine.medicine_by_stock_rate ? medicine.medicine_by_stock_rate.fldrate : '0') + '</td>';
    trData += '<td>' + medicine.flduserid_order + '</td>';
    trData += '<td>' + numberFormatDisplay(ftotal) + '</td>';
    trData += '<td>' + numberFormatDisplay(medicine.fldtaxamt) + '</td>';
    trData += '<td>' + numberFormatDisplay(discountamount) + '</td>';
    trData += '<td>' + numberFormatDisplay(total) + '</td>';
    trData += '<td><a href="javascript:void(0);" class="btn btn-primary " onclick="editMedicine(' + medicine.fldid + ',' + fldstockno +')"><i class="fa fa-edit"></i></a><a href="javascript:void(0);" class="btn btn-outline-primary js-dispensing-alternate-button"><i class="fa fa-reply"></i></a><a href="javascript:void(0);" class="btn btn-danger delete" ><i class="fa fa-trash"></i></a></td>';

    trData += '</tr>';


    return trData;


}

function getModalTrString(medicine) {
    var trData = '<tr data-fldid="' + medicine.fldid + '" data-taxpercentage="' + medicine.fldtaxper + '" data-stocknumber="' + medicine.fldstockno + '" class="dispensed-medicine">';
    trData += '<td><input type="checkbox" name="med" class="js-dispensed-label-checkbox" value="' + medicine.fldid + '"></td>';
    trData += '<td>' + medicine.fldroute + '</td>';
    trData += '<td>' + medicine.flditem + '</td>';
    trData += '<td>' + medicine.flddose + '</td>';
    trData += '<td>' + medicine.fldfreq + '</td>';
    trData += '<td>' + medicine.flddays + '</td>';
    trData += '<td>' + medicine.fldqtydisp + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? numberFormatDisplay(medicine.medicine_by_setting.fldsellpr) : '0') + '</td>';
    trData += '<td>' + medicine.flduserid_order + '</td>';
    trData += '<td>' + medicine.flddiscper + '</td>';
    trData += '<td>' + medicine.fldtaxper + '</td>';
    trData += '<td>' + (medicine.medicine_by_setting ? numberFormatDisplay((medicine.medicine_by_setting.fldsellpr) * (medicine.fldqtydisp)) : '0') + '</td>';
    trData += '</tr>';

    return trData;
}

var type = 'ordered';

function getPatientMedicine(forceget = false) {
    var newtype = $('input[name="radio1"][type="radio"]:checked').val();
    var billingmode = $('#js-dispensing-billingmode-select').val();

    if (forceget || type != newtype) {
        type = newtype;
        $.ajax({
            url: baseUrl + "/dispensingForm/getPatientMedicine",
            type: "GET",
            data: {
                type: type,
                fldencounterval: $('#fldencounterval').val(),
                billingmode: billingmode,
            },
            dataType: "json",
            success: function (response) {
                var trData = '';
                $.each(response, function (i, medicine) {
                    if(billingmode.toLowerCase() == 'health insurance' || billingmode.toLowerCase() == 'healthinsurance' || billingmode.toLowerCase() == 'hi'){
                        trData += getTrStringHI(medicine, (i + 1));
                    }else{
                        trData += getTrString(medicine, (i + 1));
                    }
                    //trData += getTrString(medicine, (i + 1));
                });
                $('#js-dispensing-medicine-tbody').html(trData);
            }
        });
    }
}

function getDispensedPatientMedicine(forceget = false) {
    var newtype = $('input[name="radio1"][type="radio"]:checked').val();

    if (forceget || type != newtype) {
        type = newtype;
        $.ajax({
            url: baseUrl + "/dispensingForm/getPatientMedicine",
            type: "GET",
            data: {
                type: type,
                fldencounterval: $('#fldencounterval').val(),
            },
            dataType: "json",
            success: function (response) {
                $('#dispensed-medicine-modal').modal('show');
                var trData = '';
                $.each(response, function (i, medicine) {
                    trData += getModalTrString(medicine);
                });
                $('#js-dispensed-medicine-tbody').html(trData);
            }
        });
    }
}

function getTPBillList() {
    $.ajax({
        url: baseUrl + "/dispensingForm/getTPBillList",
        type: "POST",
        data: {
            fldencounterval: $('#fldencounterval').val(),
        },
        success: function (response) {
            $('#tpbill-modal').modal('show');
            $('#js-tp-bill-list-tbody').html(response);
        }
    });

}

$(document).on('click', '#js-dispensing-add-btn', function () {
    $('#js-dispensing-totalvat-input').val('');
    var quant = $('#js-dispensing-quantity-input').val();
    if (quant === '') {
        showAlert('Please input quantity', 'fail');
        return false;
    }

    if (quant === '0') {
        showAlert('Please input quantity greater than 0', 'fail');
        return false;
    }
    var medicine = $('#js-dispensing-medicine-input').val() || '';
    var stocknumber = $('#js-dispensing-medicine-input').find(':selected').data('fldstockno');
    var route = $('#js-dispensing-medicine-input option[value="' + medicine + '"]').data('route') || '';
    var flditemtype = $('#js-dispensing-medicine-input option[value="' + medicine + '"]').data('flditemtype') || '';
    var quantity = $('#js-dispensing-quantity-input').val();
    var stock = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').attr('fldqty') || '';
    var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text().split(' | ');
    var remainingStock = stock - quantity;

    if (remainingStock < 0) {
        showAlert('Quantity cannot be greater than ' + stock, 'fail');
        return false;
    }
    var currentqty = optionAll[4].split(' ');
    if(currentqty[1] == 0){
        showAlert('Medicine is out of stock');
        return false;
    }

    if(Number(quantity) > Number(currentqty[1])){
        showAlert('Quantity cannot be greater than ' + currentqty[1], 'fail');
        return false;
    }

    var currentElem = $('#js-dispensing-medicine-input option[value="' + medicine + '"]');
    var fldnarcotic = ($(currentElem).data('fldnarcotic') || 'No').toLowerCase();
    var consult = $('#js-dispensing-consultant-hidden-input').val();
    var newconsult = $('#newconsultname').val();
    var newconsultnmc = $('newconsultnmc').val();
    if (fldnarcotic == 'yes' && consult == '') {
        showAlert('Please choose consultant name for' + medicine, 'fail');
        return false;
    }
    var mymedicine = [];
    $('#js-dispensing-medicine-tbody tr').each(function (i, el) {
        var value1 = $(el).children().eq(2).text();
        mymedicine.push(value1);
    });
    if ($.inArray(medicine, mymedicine) > -1) {
        if (!confirm(medicine + ' has already been added. Do you want to continue'))
            return false;
    }

    if (route != '' && medicine != '') {
        $.ajax({
            url: baseUrl + '/dispensingForm/saveMedicine',
            type: "POST",
            data: {
                fldstockno: $('#js-dispensing-medicine-input option:selected').data('fldstockno'),
                route: route,
                medicine: medicine,
                doseunit: $('#js-dispensing-doseunit-input').val(),
                frequency: $('#js-dispensing-frequency-select').val(),
                duration: $('#js-dispensing-duration-input').val(),
                quantity: quantity,
                consultant: $('#js-dispensing-consultant-hidden-input').val(),
                newconsult: $('#newconsultname').val(),
                newconsultnmc: $('#newconsultnmc').val(),
                flditemtype: flditemtype,
                fldencounterval: $('#fldencounterval').val(),
                mode: $("input[name='radio']:checked").val(),
                department: $('input[type="radio"][name="radio"]:checked').val(),
                batch: optionAll[2],
                discountmode: $('#discount-scheme-change :selected').val(),
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    $("#discount-scheme-change").prop('disabled', true);
                    var remainingqty = currentqty[1] - quantity;
                    optionAll[4] = "QTY " + remainingqty;
                    $('#js-dispensing-medicine-input option:selected').text(optionAll.join(' | '));
                    $('#js-dispensing-medicine-input').select2();

                    if ($('input[name="radio1"][type="radio"]:checked').val() == 'ordered') {
                        // alert(response.data.flddiscper);
                        // return false;
                        var trData = getTrNewString(response.data,response.stocknumber);
                        $('#js-dispensing-medicine-tbody').append(trData);
                        var itemtype = response.data.flditemtype;
                        var finettotal = (response.data.medicine_by_setting ? (response.data.medicine_by_setting.fldsellpr) * (response.data.fldqtydisp) : '0');
                        if (itemtype == 'Surgicals') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;


                        } else if (itemtype == 'Medicines') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;
                        } else if (itemtype == 'Extra Items') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;


                        } else {js-dispensing-subtotal-input
                            var stotal = finettotal;
                        }
                        // alert(discountamount);
                        var subtotal = parseFloat(numberFormat($('#js-dispensing-subtotal-input').val().trim()) || 0) + finettotal;
                        var totaldiscount = parseFloat(numberFormat($('#js-dispensing-discounttotal-input').val().trim()) || 0) + discountamount;
                        var totalvat = parseFloat(numberFormat($('#js-dispensing-totalvat-input').val().trim()) || 0) + response.data.fldtaxamt;
                        var finatotal = parseFloat(numberFormat($('#js-dispensing-nettotal-input').val().trim()) || 0) + stotal;

                        var fnettotal = finatotal + totalvat;

                        $('#js-dispensing-subtotal-input').val(numberFormatDisplay(response.subtotal));
                        $('#js-dispensing-totalvat-input').val(numberFormatDisplay(response.totaltax));
                        $('#js-dispensing-nettotal-input').val(numberFormatDisplay(fnettotal));

                    }else if ($('input[name="radio1"][type="radio"]:checked').val() == 'hibill'){

                        var trData = getTrNewString1(response.data,response.stocknumber);
                        $('#js-dispensing-medicine-tbody').append(trData);
                        var itemtype = response.data.flditemtype;
                        var finettotal = (response.data.medicine_by_stock_rate ? (response.data.medicine_by_stock_rate.fldrate) * (response.data.fldqtydisp) : '0');
                        if (itemtype == 'Surgicals') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;


                        } else if (itemtype == 'Medicines') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;
                        } else if (itemtype == 'Extra Items') {
                            var damount = response.data.flddiscper;
                            var discountamount = (damount / 100) * finettotal;
                            var stotal = finettotal - discountamount;


                        } else {
                            var stotal = finettotal;
                        }
                        // alert(discountamount);
                        var subtotal = parseFloat(numberFormat($('#js-dispensing-subtotal-input').val().trim()) || 0) + finettotal;
                        var totaldiscount = parseFloat(numberFormat($('#js-dispensing-discounttotal-input').val().trim()) || 0) + discountamount;
                        var totalvat = parseFloat(numberFormat($('#js-dispensing-totalvat-input').val().trim()) || 0) + response.data.fldtaxamt;
                        var finatotal = parseFloat(numberFormat($('#js-dispensing-nettotal-input').val().trim()) || 0) + stotal;

                        var fnettotal = finatotal + totalvat;

                        $('#js-dispensing-subtotal-input').val(numberFormatDisplay(response.subtotal));
                        $('#js-dispensing-totalvat-input').val(numberFormatDisplay(response.totaltax));
                        $('#js-dispensing-nettotal-input').val(numberFormatDisplay(fnettotal));
                    }
                    var discountpercent = (totaldiscount*100)/response.subtotal;
                    // alert(discountpercent);
                    $('#discount_type_change').val('');
                    $('#js-dispensing-discounttotal-input').val(numberFormatDisplay(totaldiscount));
                    $('#js-dispensing-discount-input').removeAttr('readonly');
                    $('#js-dispensing-discount-input').val(numberFormatDisplay(discountpercent));
                    $('#js-dispensing-discount-input').trigger('keyup');
                    $('#js-dispensing-discount-input').attr('readonly');

                    $('#js-dispensing-route-select').val('');
                    $('#js-dispensing-medicine-input').val('').select2();
                    $('#js-dispensing-doseunit-input').val('');
                    $('#js-dispensing-quantity-input').val('');
                    $('#js-dispensing-flditemtype-input').val('');
                    $('#js-dispensing-fldsellpr-input').val('');
                    $('#js-dispensing-fldqty-input').val('');
                    $('#js-dispensing-blank1-input').val('');
                    $('#js-dispensing-blank2-input').val('');
                    $('#js-dispensing-amt-input').val('');
                    if (typeof $('#js-dispensing-duration-input').attr('disabled') === typeof undefined)
                        $('#js-dispensing-duration-input').val('');
                    if (typeof $('#js-dispensing-frequency-select').attr('disabled') === typeof undefined)
                        $('#js-dispensing-frequency-select').val('');

                    $('[aria-labelledby="select2-js-dispensing-medicine-input-container"]').focus();

                }
                showAlert(response.message, status);
            }
        });
    } else
        showAlert('Route and medicine cannot be empty.', 'fail')
});

$('#js-dispensing-discount-input').keyup(function () {
    var discount = $(this).val() || 0;
    if (isNaN(discount)) {
        showAlert('Enter valid number.', 'fail');

    } else if (discount > 100) {
        showAlert('Discount cannot be greater than 100 %', 'fail');
        $('#js-dispensing-discounttotal-input').val('');
        $('#js-dispensing-discount-input').val('');
    } else {
        var subtotal = 0;
        var totalvat = 0;
        var discountamount = 0;
        var finalnet = 0;
        $.each($('#js-dispensing-medicine-tbody tr'), function(i, trElem) {
            var trSubTotal = $(trElem).find('td:nth-child(11)').text();
            trSubTotal = trSubTotal.replace(/[^\d\.\-]/g, "");
            trSubTotal = parseFloat(numberFormat(trSubTotal));
            var taxpercentage = parseFloat(numberFormat($(trElem).data('taxpercentage')) || 0);
            trSubTotal = (isNaN(trSubTotal) ? 0 : trSubTotal);

            var trdiscount = parseFloat(numberFormat(trSubTotal)) * (parseFloat(discount)/100);
            var tax = parseFloat(((numberFormat(trSubTotal)-numberFormat(trdiscount)) * numberFormat(taxpercentage))/100);
            var nettotal = parseFloat(numberFormat(trSubTotal))-parseFloat(numberFormat(trdiscount))+parseFloat(numberFormat(tax));
            nettotal = parseFloat(numberFormat(nettotal));

            $(trElem).find('td:nth-child(13)').text(numberFormatDisplay(trdiscount));
            $(trElem).find('td:nth-child(12)').text(numberFormatDisplay(tax));
            $(trElem).find('td:nth-child(14)').text(numberFormatDisplay(nettotal));

            subtotal += parseFloat(numberFormat(trSubTotal));
            totalvat += parseFloat(numberFormat(tax));
            discountamount += parseFloat(numberFormat(trdiscount));
            finalnet += parseFloat(numberFormat(nettotal));
        });

        subtotal = parseFloat(numberFormat(subtotal));
        totalvat = parseFloat(numberFormat(totalvat));
        discountamount = parseFloat(numberFormat(discountamount));
        finalnet = parseFloat(numberFormat(finalnet));

        $('#js-dispensing-subtotal-input').val(numberFormatDisplay(subtotal));
        $('#js-dispensing-discounttotal-input').val(numberFormatDisplay(discountamount));
        $('#js-dispensing-totalvat-input').val(numberFormatDisplay(totalvat));
        $('#js-dispensing-nettotal-input').val(numberFormatDisplay(finalnet));
    }
});

$('#js-dispensing-medicine-input').on('select2:select', function (e) {
    $('#js-dispensing-quantity-input').focus();
});

$('#js-dispensing-quantity-input').keydown(function (e) {

    if (e.which == 13){

        $('#js-dispensing-add-btn').click();
        $('#js-dispensing-add-btn').prop('disabled', true);
        setTimeout(function(){
            $('#js-dispensing-add-btn').prop('disabled', false);
        }, 3000);

    }

});

$('#js-dispensing-quantity-input').on('keyup', function () {
    var medicine = $('#js-dispensing-medicine-input option:selected').data('fldstockno') || '';
    var qty = parseInt($(this).val());
    var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + medicine + '"]').text().split(' | ');
    var stringrate = optionAll[5].split(' ');
    var rate = stringrate[1];
    var finalamt = rate * qty;
    $('#js-dispensing-amt-input').val(numberFormatDisplay(finalamt));
});

$('#js-dispensing-flditem-input-modal').keyup(function () {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-dispensing-table-modal tr td:first-child'), function (i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        var currentTr = $(e).closest('tr');

        if (tdText.search(searchText) >= 0)
            $(currentTr).show();
        else
            $(currentTr).hide();
    });
});

$(document).on('click', '#js-dispensing-medicine-tbody tr', function () {
    selected_td('#js-dispensing-medicine-tbody tr', this);
});

$(document).ready(function () {
    $(document).on('click', '.js-dispensing-alternate-button', function () {

        selected_td('#js-dispensing-medicine-tbody tr', this);
        var type = 'Alternate';
        var medicine = $('#js-dispensing-medicine-tbody tr[is_selected="yes"] td:nth-child(3)').text().trim();

        var route = $('#js-dispensing-medicine-tbody tr[is_selected="yes"] td:nth-child(2)').text().trim();

        if (medicine != '' && route != '') {
            if (type == 'Pricing' || type == 'Current' || type == 'Inventory' || type == 'Alternate') {
                $.ajax({
                    url: baseUrl + "/dispensingForm/showInfo",
                    type: "GET",
                    data: {
                        type: type,
                        medicine: medicine,
                        route: route,
                    },
                    dataType: "json",
                    success: function (response) {
                        var status = (response.status) ? 'success' : 'fail';
                        if (response.status === 'true') {
                            $('#js-dispensing-modal-title-modal').html(type);
                            $('#js-dispensing-modal-body-modal').html(response.view);
                            $('#js-dispensing-info-modal').modal('show');
                        } else
                            showAlert('No Alternate Found', status);
                    }
                });
            } else if (type == 'Drug Info' || type == 'Review') {
                var fldid = $('#js-dispensing-medicine-tbody tr[is_selected="yes"]').data('fldid') || '';
                var url = baseUrl + "/dispensingForm/generatePdf?type=" + type + "&fldid=" + fldid;

                window.open(url, '_blank');
            }
            $('#js-dispensing-info-select').val('');
        }

    });

    $("input[name='queryColumn']").click(function() {
        if($("input[name='queryColumn']:checked").val() === "encounter") {
            $("#js-returnform-bill-return-btn").hide();
        } else {
            $("#js-returnform-bill-return-btn").show();
        }
        clear();
    })
});

$(document).on('click', '#js-dispensing-print-btn', function () {

    var allowedamt = parseFloat(numberFormat($('#allowedamt').val()));
    var chargedamt = parseFloat(numberFormat($('#chargedamt').val()));
    var billingmode = $('#billingmode').val();

    var rowCount = $('#dispensing_medicine_list tr').length;

    if (rowCount <= 1) {
        showAlert('No medicine found to dispense', 'fail');
        return false;
    }
    var paymode = $("input[name='payment_mode']:checked").val();

    var expecteddate = $('#expected_payment_date').val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }
    if (paymode == 'Cheque') {
        var chequnumber = $('#cheque_number_input').val();
        var bank = $('#bank-name').val();
        if (chequnumber == "") {
            showAlert('Please enter cheque number', 'fail');
            return false;
        }

        if (bank == "") {
            showAlert('Please choose bank', 'fail');
            return false;
        }
    }
    var printIds = [];
    $.each($('.js-dispensing-label-checkbox:checked'), function (i, e) {
        printIds.push($(e).val());
    });
    printIds = printIds.join(',');
    var queryString = {
        receive_amt: numberFormat($('#js-dispensing-nettotal-input').val()),
        tax_amt: numberFormat($('#js-dispensing-totalvat-input').val()),
        sub_total: numberFormat($('#js-dispensing-subtotal-input').val()),
        fldbillingmode: $('#js-dispensing-billingmode-select').val(),
        discountmode: $('#discount-scheme-change').val(),
        printIds: printIds,
        discountamt: numberFormat($('#js-dispensing-discounttotal-input').val()),
        discountpercentage: $('#js-dispensing-discount-input').val(),
        opip: $('input[type="radio"][name="radio"]:checked').val(),
        itemtype: $('input[type="radio"][name="medcategory"]:checked').val(),
        fldencounterval: $('#fldencounterval').val(),
        //type: 'ordered',
        type: $('input[name="radio1"][type="radio"]:checked').val(),
        fldremark: $('#js-dispensing-remarks-textarea').val(),
        // payment_mode: $('#payment_mode option:selected').val(),
        payment_mode: $("input[name='payment_mode']:checked").val(),
        cheque_number: $('#cheque_number_input').val(),
        bankname: $('#bank-name option:selected').val(),
        expecteddate: expecteddate,
        fonepaylog_id: $('.js-fonepaylog-id-hidden').val(),
    };

    var sub_total = numberFormat($('#js-dispensing-subtotal-input').val())

    var total = parseFloat(chargedamt) + parseFloat(numberFormat(sub_total));

    if(billingmode.toLowerCase() == 'healthinsurance' || billingmode.toLowerCase() == 'hi' || billingmode.toLowerCase() == 'health insurance'){
        if( total < allowedamt ){
            queryString = $.param(queryString);
            var url = baseUrl + '/dispensingForm/print?' + queryString;
            $('#js-dispensing-medicine-tbody').empty();
            $('#js-dispensing-amt-input').val('');
            $('#js-dispensing-subtotal-input').val('');
            $('#js-dispensing-discount-input').val('');
            $('#js-dispensing-discounttotal-input').val('');
            $('#js-dispensing-nettotal-input').val('');
            $('#payment_mode').val('');
            $('#js-dispensing-remarks-textarea').val('');
            $('#bank-name').val(null).trigger('change');
            $('#cheque_number_input').val('');
            $('#payment_mode').val('Cash');
            window.open(url, '_blank');
        }else{
            Swal.fire({
                title: 'Allowed Amount (Rs.' + allowedamt + ') is greater that Billed amount (Rs.' + total + ') for HI Patient. Billing Has Been Cancelled.',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: 'Ok',
                denyButtonText: `Cancel`,
                allowOutsideClick: false,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    //cancelled for now
                    // queryString = $.param(queryString);
                    // var url = baseUrl + '/dispensingForm/print?' + queryString;
                    // $('#js-dispensing-medicine-tbody').empty();
                    // $('#js-dispensing-amt-input').val('');
                    // $('#js-dispensing-subtotal-input').val('');
                    // $('#js-dispensing-discount-input').val('');
                    // $('#js-dispensing-discounttotal-input').val('');
                    // $('#js-dispensing-nettotal-input').val('');
                    // $('#payment_mode').val('');
                    // $('#js-dispensing-remarks-textarea').val('');
                    // $('#bank-name').val(null).trigger('change');
                    // $('#cheque_number_input').val('');
                    // $('#payment_mode').val('Cash');
                    // window.open(url, '_blank');
                } else if (result.isDenied) {
                    $(this).prop('disabled', false);
                  Swal.fire('Billing Cancelled.', '', 'info')
                }
              })

            // if(confirm('Allowed Amount (Rs.' + allowedamt + ') is greater that Billed amount (Rs.' + total + ') for HI Patient! Do you really want to continue?')) {
            //     queryString = $.param(queryString);
            //     var url = baseUrl + '/dispensingForm/print?' + queryString;
            //     $('#js-dispensing-medicine-tbody').empty();
            //     $('#js-dispensing-amt-input').val('');
            //     $('#js-dispensing-subtotal-input').val('');
            //     $('#js-dispensing-discount-input').val('');
            //     $('#js-dispensing-discounttotal-input').val('');
            //     $('#js-dispensing-nettotal-input').val('');
            //     $('#payment_mode').val('');
            //     $('#js-dispensing-remarks-textarea').val('');
            //     $('#bank-name').val(null).trigger('change');
            //     $('#cheque_number_input').val('');
            //     $('#payment_mode').val('Cash');
            //     window.open(url, '_blank');
            // }else{
            //     $(this).prop('disabled', false);
            // }

        }
    }else{
        queryString = $.param(queryString);
        var url = baseUrl + '/dispensingForm/print?' + queryString;
        $('#js-dispensing-medicine-tbody').empty();
        $('#js-dispensing-amt-input').val('');
        $('#js-dispensing-subtotal-input').val('');
        $('#js-dispensing-discount-input').val('');
        $('#js-dispensing-discounttotal-input').val('');
        $('#js-dispensing-nettotal-input').val('');
        $('#payment_mode').val('');
        $('#js-dispensing-remarks-textarea').val('');
        $('#bank-name').val(null).trigger('change');
        $('#cheque_number_input').val('');
        $('#payment_mode').val('Cash');
        window.open(url, '_blank');
    }

   
});

$(document).on('click', '#js-dispensing-tp-bill-btn', function () {

    var rowCount = $('#dispensing_medicine_list tr').length;

    if (rowCount <= 1) {
        showAlert('No medicine found to dispense', 'fail');
        return false;
    }
    var paymode = $("input[name='payment_mode']:checked").val();

    var expecteddate = $('#expected_payment_date').val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }

    var printIds = [];
    $.each($('.js-dispensing-label-checkbox:checked'), function (i, e) {
        printIds.push($(e).val());
    });
    printIds = printIds.join(',');
    var queryString = {
        receive_amt: numberFormat($('#js-dispensing-receive-input').val()),
        tax_amt: numberFormat($('#js-dispensing-totalvat-input').val()),
        sub_total: numberFormat($('#js-dispensing-subtotal-input').val()),
        fldbillingmode: $('#js-dispensing-billingmode-select').val(),
        discountmode: $('#discount-scheme-change').val(),
        printIds: printIds,
        discountamt: numberFormat($('#js-dispensing-discounttotal-input').val()),
        discountpercentage: $('#js-dispensing-discount-input').val(),
        opip: $('input[type="radio"][name="radio"]:checked').val(),
        itemtype: $('input[type="radio"][name="medcategory"]:checked').val(),
        fldencounterval: $('#fldencounterval').val(),
        type: 'ordered',
        fldremark: $('#js-dispensing-remarks-textarea').val(),
        payment_mode: $("input[name='payment_mode']:checked").val(),
        cheque_number: $('#cheque_number_input').val(),
        bankname: $('#bank-name option:selected').val(),
        expecteddate: expecteddate,
    };
    queryString = $.param(queryString);
    var url = baseUrl + '/dispensingForm/tpbill?' + queryString;
    $('#js-dispensing-medicine-tbody').empty();
    $('#js-dispensing-amt-input').val('');
    $('#js-dispensing-subtotal-input').val('');
    $('#js-dispensing-totalvat-input').val('');
    $('#js-dispensing-discount-input').val('');
    $('#js-dispensing-discounttotal-input').val('');
    $('#js-dispensing-nettotal-input').val('');
    $('#js-dispensing-remarks-textarea').val('');
    $('#bank-name').val(null).trigger('change');
    $('#cheque_number_input').val('');


    setTimeout(() => {
        location.reload();
    }, 200);
    window.open(url, '_blank');
    // location.reload();
});

$(document).on('click', '#js-dispensing-clear-btn', function () {
    window.location.href = baseUrl + '/dispensingForm/resetEncounter';
});

$(document).on('click', '#js-dispensing-or-refresh-modal', function () {
    $.ajax({
        url: baseUrl + "/dispensingForm/getOnlineRequest",
        type: "GET",
        data: $('#js-dispensing-or-form-modal').serialize(),
        success: function (response) {
            var trData = '';
            $.each(response, function (i, res) {
                trData += '<tr data-fldencounterval="' + res.fldencounterval + '">';
                trData += '<td>' + (i + 1) + '</td>';
                trData += '<td>' + res.fldencounterval + '</td>';
                trData += '<td>' + res.encounter.patient_info.fldrankfullname + '</td>';
                trData += '<td>' + res.encounter.fldcurrlocat + '</td>';
                trData += '<td>' + res.fldstatus + '</td>';
                trData += '</tr>';
            });
            $('#js-dispensing-or-table-modal').empty().html(trData);
        }
    });
});

function selectTd(currentElem, direction = 'up') {
    var firstTr = $('#js-dispensing-or-table-modal tr')[0];
    if (currentElem.length == 0)
        selected_td('#js-dispensing-or-table-modal tr', firstTr);
    else if (direction == 'up') {
        var prev = $('#js-dispensing-or-table-modal tr[is_selected="yes"]').prev();
        prev = (prev.length > 0) ? prev : firstTr;
        selected_td('#js-dispensing-or-table-modal tr', prev);
    } else if (direction == 'down') {
        var next = $('#js-dispensing-or-table-modal tr[is_selected="yes"]').next();
        next = (next.length > 0) ? next : firstTr;
        selected_td('#js-dispensing-or-table-modal tr', next);
    }
}

$('#js-dispensing-online-request-button').click(function (e) {
    $("#js-dispensing-online-request-modal").modal('show');
    $('#js-dispensing-or-table-modal').empty();
    $('#js-dispensing-or-refresh-modal').click();
});

$(document).on('keydown', '', function (e) {
    if (($("#js-dispensing-online-request-modal").data('bs.modal') || {})._isShown) {
        var selectedTr = $('#js-dispensing-or-table-modal tr[is_selected="yes"]') || null;
        if (e.which === 38) { // up arrow
            selectTd(selectedTr);
        } else if (e.which === 40) { // down arrow
            selectTd(selectedTr, 'down');
        } else if (e.which == 13) {
            var encid = $('#js-dispensing-or-table-modal tr[is_selected="yes"]').data('fldencounterval') || '';
            if (encid !== '') {
                $('#js-encounter-id-input').val(encid);
                $('#js-submit-button').click();
                $("#js-dispensing-online-request-modal").modal('hide');
            }
        }
    } else if (e.which === 113) {
        $('#js-dispensing-online-request-button').click();
    }
});

$(document).on('click', '.delete', function () {
    if (confirm('Are You Sure ?')) {
        var fldid = $(this).closest('tr').data('fldid');
        var currentRow = $(this).closest("tr");
        var medicine = currentRow.find("td:eq(2)").text();
        var qty = currentRow.find("td:eq(7)").text();
        var stocknumber = $(this).closest('tr').data('stocknumber');
        var stock = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').attr('fldqty') || '';
        var flditemtype = $('#js-dispensing-medicine-input option[value="' + medicine + '"]').data('flditemtype') || '';
        // if($('input[name="radio1"][type="radio"]:checked').val() == 'hibill'){
        //     var optionAll = '||||'
        // }else{
        //     var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text().split(' | ');
        // }


        var optionAll = $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text().split(' | ');

        var remainingStock = parseInt(stock) + parseInt(qty);
        var currentqty = optionAll[4].split(' ');
        var finalstock = parseInt(currentqty[1])+parseInt(qty);
        var fldencounterval = $('#fldencounterval').val();
        var type = $('input[name="radio1"][type="radio"]:checked').val();
        $.ajax({
            url: baseUrl + "/dispensingForm/deleteMedicine",
            type: "POST",
            // data: {fldid: fldid, medicine: medicine, qty: qty, batch: optionAll[2], flditemtype: flditemtype, fldencounterval:fldencounterval},
            data: {fldid: fldid, medicine: medicine, qty: qty, batch: optionAll[2], flditemtype: flditemtype, fldencounterval:fldencounterval, type:type},
            success: function (response) {
                $('#js-dispensing-medicine-tbody').html(response.html);
                $('#js-dispensing-subtotal-input').val(numberFormatDisplay(response.subtotal));
                $('#js-dispensing-totalvat-input').val(numberFormatDisplay(response.taxtotal));
                $('#js-dispensing-nettotal-input').val(numberFormatDisplay(response.total));
                optionAll[4] = "QTY " + finalstock;
                $('#js-dispensing-medicine-input option[data-fldstockno="' + stocknumber + '"]').text(optionAll.join(' | '));
                $('#js-dispensing-discounttotal-input').val(numberFormatDisplay(response.dsicountetotal));
                $('#js-dispensing-discount-input').val(numberFormatDisplay(response.discountpercent));
                $('#js-dispensing-medicine-input').select2("destroy").select2();

            }
        });

        if(type == "hibill"){
            getMedicineList();
        }
        
    }
})

function editMedicine(id,stocknumber) {
    $.ajax({
        url: baseUrl + '/dispensingForm/updateDetails',
        type: "POST",
        data: {
            fldid: id,
            fldencounterval:$('#fldencounterval').val(),
            fldstockno:stocknumber,
        },
        success: function (response) {
            $('#update-medicine-modal').modal('show');
            $('#dosehtml').empty().append(response.dosehtml);
            $('#med-field').empty().append(response.html);
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(errorMessage);
        }
    });
}

function editTPItem(id) {
    $.ajax({
        url: baseUrl + '/dispensingForm/updateTPItem',
        type: "POST",
        data: {
            fldid: id,
            fldencounterval:$('#fldencounterval').val(),
        },
        success: function (response) {
           $('#update-tp-item-modal').modal('show');
           $('#existing_qty').val(response.quantity);
           $('#new_qty').val(response.quantity);
           $('#patbill_fldid').val(response.fldid);
           $('#tp_encounterId').val(response.fldencounterval);
           $('#medicine_name').text(response.flditemname);
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(errorMessage);
        }
    });
}

$(document).on('click', '.tpdelete', function (e) {
    if (confirm('Are You Sure ?')) {
        var fldid = $(this).closest('tr').data('fldid');
        var fldencounterval = $(this).closest('tr').data('fldencounterval');

        $.ajax({
            url: baseUrl + "/dispensingForm/deleteTPItem",
            type: "POST",
            data: {fldid: fldid,fldencounterval:fldencounterval},
            dataType: "json",
            success: function (response) {
                 $('#js-tp-bill-list-tbody').html(response.data.mainhtml);
                 $('.depAmount').text(response.data.totalDepositAmountReceived);
                    $('.tpAmount').text(response.data.totalTPAmountReceived);
                    $('.remainingAmount').text(response.data.remaining_deposit);
                  getMedicineList();
                  showAlert('Data Deleted');

            }
        });
    }
})

// from anish

function clear() {
    $('#js-returnform-fullname-input').val('');
    $('#js-returnform-address-input').val('');
    $('#js-returnform-location-input').val('');
    $('#js-returnform-gender-input').val('');
    $('#js-returnform-queryvalue-input').val('');
    // $('#js-returnform-particulars-select').val('');
    var optionData = '<option value="" disabled selected>-- Select --</option>';
    $('#js-returnform-particulars-select').html(optionData);
    $('#js-returnform-expiry-input').val('');
    $('#js-returnform-qty-input').val('');
    $('#js-returnform-retqty-input').val('');
    $("#js-returnform-reason-input").val('');
    $('#js-returnform-return-tbody').empty()
}

$('#js-returnform-bill-return-btn').on('click', function () {
    var reason = $('#js-returnform-reason-input').val() || '';
    var queryValue = $('#js-returnform-queryvalue-input').val() || '';

    if (queryValue == '') {
        showAlert('Bill cannot be empty.', 'fail');
        $('#js-returnform-queryvalue-input').focus();
        return false;
    }

    if (reason == '') {
        showAlert('Reason cannot be empty.', 'fail');
        $('#js-returnform-reason-input').focus();
        return false;
    }

    $.ajax({
        url: baseUrl + '/returnForm/returnBill',
        type: "POST",
        data: {
            reason: reason,
            queryValue: queryValue,
        },
        dataType: "json",
        success: function (response) {
            var status = (response.status) ? 'success' : 'fail';
            if (response.status) {
                $("#js-returnform-show-btn").trigger("click");
            }

            showAlert(response.message, status);
        }
    });
});

function changeQuantityReturn(currentInput) {
    var selectedTr = $(currentInput).closest('tr')
    var quantity = $(currentInput).val() || 0;
    var oldQty = $(currentInput).attr('oldqty') || 0;
    var fldid = $(selectedTr).find('button.btn-danger').data('fldid');

    if (Number(oldQty) > Number(quantity) || Number(quantity) > -1) {
        $(currentInput).val(oldQty);
        showAlert("Quantity cannot be greater than " + oldQty + " and dhould be less than 0.", 'fail');
        return false;
    }

    $.ajax({
        url: baseUrl + "/returnForm/changeQuantity",
        type: "POST",
        data: {
            fldid: fldid,
            quantity: quantity
        },
        dataType: "json",
        success: function (response) {
            var status = response.status ? 'success' : 'fail';
            showAlert(response.message, status);
            if (response.status) {
                $(selectedTr).find('td:nth-child(7)').text(numberFormatDisplay(response.update.fldtaxamt));
                $(selectedTr).find('td:nth-child(8)').text(numberFormatDisplay(response.update.flddiscamt));
                $(selectedTr).find('td:nth-child(9)').text(numberFormatDisplay(response.update.fldditemamt));
                $(currentInput).attr('oldqty', quantity);
                setTimeout(() => {
                    calculateTotal();
                }, 100);
            }
        }
    });
}

$(document).on('focusout', '#js-dispensing-discounttotal-input', function () {
    var subtotal = $('#js-dispensing-subtotal-input').val() || 0;
    var discount = $('#js-dispensing-discounttotal-input').val() || 0;

    var percentage = (parseFloat(discount)*100)/parseFloat(numberFormat(subtotal));

    $('#js-dispensing-discount-input').val(percentage.toFixed(8));
    $('#js-dispensing-discount-input').trigger('keyup');
});
