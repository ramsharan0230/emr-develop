$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

function getPatientDetailTrString(i, item) {
    var trData = '<tr>';
    trData += '<td>' + (i+1) + '</td>';
    trData += '<td>' + item.fldordtime + '</td>';
    trData += '<td>' + item.flditemtype + '<input type="hidden" name="itemType[]" class="itemType" value="'+item.flditemtype+'"</td>';
    trData += '<td>' + item.service_cost.fldbillitem + '<input type="hidden" name="itemName[]" class="itemName" value="'+item.flditemname+'"></td>';
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

$(document).on('click', '.js-btn-return-delete', function() {
    var fldid = $(this).data('fldid' || '');
    if (fldid != '' && confirm('Are you sure to delete refund??')) {
        $.ajax({
            url: baseUrl + "/returnFormCashier/deleteReturnEntry",
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = response.status ? 'success' : 'fail';
                if (response.status) {
                    $('#js-returnformCashier-show-btn').trigger("click");
                }
                showAlert(response.message, status);
            }
        });
    } else
        showAlert('Invalid id', 'fail');
});

function getPatientDetail() {
    var queryvalue = $('#js-returnformCashier-queryvalue-input').val() || '';
    if (queryvalue != '') {
        $.ajax({
            url: baseUrl + '/returnFormCashier/getPatientDetail',
            type: "GET",
            data: {
                queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
                queryValue: queryvalue
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    if (response.patientInfo) {
                        var address = (response.patientInfo.patient_info.fldptaddvill ? response.patientInfo.patient_info.fldptaddvill : '')
                            + ', '
                            + (response.patientInfo.patient_info.fldptadddist ? response.patientInfo.patient_info.fldptadddist : '');
                        $('#js-returnformCashier-fullname-input').val(response.patientInfo.patient_info.fldfullname);
                        $('#js-returnformCashier-address-input').val(address);
                        $('#js-returnformCashier-gender-input').val(response.patientInfo.patient_info.fldptsex);
                        $('#js-returnformCashier-location-input').val(response.patientInfo.fldcurrlocat);
                        $('#js-returnformCashier-queryvalue-input').val(response.value);
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

                    if(response.items) {
                        var optionData = '<option value="" disabled selected>-- Select --</option>';
                        $.each(response.items, function(i, option) {
                            var dataAttributes = ' data-flditemqty="' + option.flditemqty + '"';
                            dataAttributes += ' data-flditemrate="' + option.flditemrate + '"';
                            dataAttributes += ' data-fldtaxper="' + option.fldtaxper + '"';
                            dataAttributes += ' data-flddiscper="' + option.flddiscper + '"';
                            dataAttributes += ' data-fldid="' + option.fldid + '"';
                            optionData += '<option value="' + option.flditemname + '" ' + dataAttributes + '>' + option.service_cost.fldbillitem + '</option>';
                        });
                        $('#js-returnformCashier-item-select').html(optionData);
                        $('#js-returnformCashier-rate-input').val('');
                        $('#js-returnformCashier-discount-percent').val('');
                        $('#js-returnformCashier-tax-percent').val('');
                        $('#js-returnformCashier-qty-input').val('');
                        $('#js-returnformCashier-retqty-input').val('');
                    }

                    var trNewData = '';
                    var subTotal = 0;
                    var totalDiscount = 0;
                    var netTotal = 0;
                    var trLength = 0;
                    $.each(response.returnItems, function(i, item) {
                        var total = Number(item.flditemrate)*Number(item.flditemqty);

                        var discount = ((total*(item.flddiscper))/100);
                        var tax = (((total - discount)*(item.fldtaxper))/100);
                        netTotal += (Number(total) + Number(tax) - Number(discount));
                        subTotal += (Number(total) + Number(tax));
                        totalDiscount += Number(discount);



                        trNewData += getPatientDetailTrString(trLength++, item);
                    });
                    $('#sub-total-data').text(subTotal ? numberFormatDisplay(subTotal) : 0);
                    $('#discount-total').text(totalDiscount ? numberFormatDisplay(totalDiscount) : 0);
                    $('#grand-total-data').text(netTotal ? numberFormatDisplay(netTotal) : 0);
                    $('#return-amount').val(netTotal ? numberFormatDisplay(netTotal) : 0);

                    $('#js-returnformCashier-return-tbody').empty().html(trNewData);

                    var trSavedData = '';
                    var trLength = 0;
                    $.each(response.savedItems, function(i, item) {
                        trSavedData += getPatientDetailTrString(trLength++, item);
                    });
                    $('#js-returnformCashier-saved-tbody').empty().html(trSavedData);
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

$('#js-returnformCashier-show-btn').click(function () {
    getPatientDetail();
});
$('#js-returnformCashier-queryvalue-input').keydown(function (e) {
    if (e.which == 13)
        getPatientDetail();
});

$('#js-returnformCashier-itemtype-select').change(function() {
    var flditemtype = $('#js-returnformCashier-itemtype-select').val() || '';
    var queryvalue = $('#js-returnformCashier-queryvalue-input').val() || '';

    if (flditemtype != '' && queryvalue != '') {
        $.ajax({
            url: baseUrl + '/returnFormCashier/getEntryList',
            type: "GET",
            data: {
                queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
                queryValue: queryvalue,
                flditemtype: flditemtype
            },
            dataType: "json",
            success: function (response) {
                var optionData = '<option value="" disabled selected>-- Select --</option>';
                $.each(response, function(i, option) {
                    var dataAttributes = ' data-flditemqty="' + option.flditemqty + '"';
                    dataAttributes += ' data-flditemrate="' + option.flditemrate + '"';
                    dataAttributes += ' data-fldtaxper="' + option.fldtaxper + '"';
                    dataAttributes += ' data-flddiscper="' + option.flddiscper + '"';
                    dataAttributes += ' data-fldid="' + option.fldid + '"';
                    optionData += '<option value="' + option.flditemname + '" ' + dataAttributes + '>' + option.flditemname + '</option>';
                });
                $('#js-returnformCashier-item-select').html(optionData);
                $('#js-returnformCashier-rate-input').val('');
                $('#js-returnformCashier-discount-percent').val('');
                $('#js-returnformCashier-tax-percent').val('');
                $('#js-returnformCashier-qty-input').val('');
                $('#js-returnformCashier-retqty-input').val('');
            }
        });
    }
});

$('#js-returnformCashier-item-select').change(function() {
    var selectedOption = $('#js-returnformCashier-item-select option:selected');

    $('#js-returnformCashier-rate-input').val($(selectedOption).data('flditemrate'));
    $('#js-returnformCashier-discount-percent-input').val($(selectedOption).data('flddiscper'));
    $('#js-returnformCashier-tax-percent-input').val($(selectedOption).data('fldtaxper'));
    $('#js-returnformCashier-qty-input').val($(selectedOption).data('flditemqty'));
    $('#js-returnformCashier-id-input').val($(selectedOption).data('fldid'));
});

$('#js-returnformCashier-bill-return-btn').on('click', function() {
    var reason = $('#js-returnformCashier-reason-input').val() || '';
    var queryValue = $('#js-returnformCashier-queryvalue-input').val() || '';

    if (queryValue == '') {
        showAlert('Bill cannot be empty.', 'fail');
        $('#js-returnformCashier-queryvalue-input').focus();
        return false;
    }

    if (reason == '') {
        showAlert('Reason cannot be empty.', 'fail');
        $('#js-returnformCashier-reason-input').focus();
        return false;
    }

    $.ajax({
        url: baseUrl + '/returnFormCashier/returnBill',
        type: "POST",
        data: {
            reason: reason,
            queryValue: queryValue,
        },
        dataType: "json",
        success: function (response) {
            var status = (response.status) ? 'success' : 'fail';
            if (response.status) {
                $('#js-returnformCashier-show-btn').trigger("click");
            }
            showAlert(response.message, status);
        }
    });
});

$('#js-returnformCashier-return-btn').click(function() {
    var rate = $('#js-returnformCashier-rate-input').val() || '';
    var discount = $('#js-returnformCashier-discount-percent-input').val() || '';
    var tax = $('#js-returnformCashier-tax-percent-input').val() || '';

    var queryValue = $('#js-returnformCashier-queryvalue-input').val() || '';
    var flditemname = $("#js-returnformCashier-item-select").val() || '';
    var retqty = $('#js-returnformCashier-retqty-input').val() || '';
    var reason = $('#js-returnformCashier-reason-input').val() || '';
    var id = $('#js-returnformCashier-id-input').val() || '';

    if (queryValue == ''){
        showAlert('Invoice no is required', 'fail');
        return;
    }
    if(flditemname == '') {
        showAlert('Item is required.', 'fail');
        return;
    }
    if(retqty == '' || retqty == 0) {
        showAlert('Quantity must be greater than 0.', 'fail');
        return;
    }
    if(id == '') {
        showAlert('Failed to entry.', 'fail');
        return;
    }

    var qty = $('#js-returnformCashier-qty-input').val() || '0';
    if (retqty > qty) {

        showAlert('Return quantity cannot be greater than purchased quantity.', 'fail');
        return;
    }

    $.ajax({
        url: baseUrl + '/returnFormCashier/returnEntry',
        type: "POST",
        data: {
            retqty: retqty,
            flditemname: flditemname,
            queryValue: queryValue,
            rate: rate,
            discount: discount,
            tax: tax,
            reason: reason,
            id: id,
            queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
        },
        dataType: "json",
        success: function (response) {
            var status = (response.status) ? 'success' : 'fail';
            if (response.status) {
                $('#js-returnformCashier-show-btn').trigger("click");
            }

            showAlert(response.message, status);
        }
    });
});

$(document).on('click','#saveAndBill',function(){
    var queryValue = $('#js-returnformCashier-queryvalue-input').val() || '';
    var authorizedby = $('#js-authorizedby-input').val() || '';
    var paymode = $("input[name='payment_mode']:checked").val();

    if (paymode == '' || paymode == undefined) {
        showAlert('Please choose payment mode', 'fail');
        return false;
    }
    if (queryValue != '') {
        if($('#js-returnformCashier-return-tbody tr').length > 0){
            $.ajax({
                url: baseUrl + '/returnFormCashier/save-and-bill',
                type: "POST",
                data: {
                    queryValue: queryValue,
                    queryColumn: $('input[name="queryColumn"]:checked').val() || 'invoice',
                    authorizedby: authorizedby,
                    returnAmt : $('#return-amount').val() || '',
                    payment_mode : $("input[name='payment_mode']:checked").val(),
                },
                dataType: "json",
                success: function (response) {
                    var status = (response.status) ? 'success' : 'fail';
                    if (response.status) {
                        $("input[type=text], input[type=number]").val("");
                        $('#js-returnformCashier-return-tbody').html("");
                        $('#sub-total-data').html("");
                        $('#discount-total').html("");
                        $('#grand-total-data').html("");
                        showAlert(response.message, status);
                        $("#js-returnformCashier-bill-return-btn").prop('disabled', false);
                        window.open(baseUrl + '/billing/service/displayReturnBilling?invoice_number=' + response.billno, '_blank');
                    }else{
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
    $.each($('#js-returnformCashier-return-tbody tr'), function(i, tr) {
        var taxPer = parseFloat(numberFormat($(tr).find('td:nth-child(7)').text().trim()) || 0);
        var discountPer = parseFloat(numberFormat($(tr).find('td:nth-child(8)').text().trim()) || 0);
        var itemrate = parseFloat(numberFormat($(tr).find('td:nth-child(5)').text().trim()) || 0);
        var quantity = parseFloat(numberFormat($(tr).find('td:nth-child(6) input').val().trim()) || 0);

        var total = parseFloat(numberFormat(itemrate))*parseFloat(numberFormat(quantity));
        var discount = ((total * (discountPer)) / 100);
        var tax = (((total - discount) * (taxPer)) / 100);
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
    $.each($('#js-returnformCashier-return-tbody tr'), function(i, tr) {
        var taxPer = parseFloat(numberFormat($(tr).find('td:nth-child(7)').text().trim()) || 0);
        var discountPer = parseFloat(numberFormat($(tr).find('td:nth-child(8)').text().trim()) || 0);
        var itemrate = parseFloat(numberFormat($(tr).find('td:nth-child(5)').text().trim()) || 0);
        var quantity = parseFloat(numberFormat($(tr).find('td:nth-child(6) input').val().trim()) || 0);

        var total = parseFloat(numberFormat(itemrate))*parseFloat(numberFormat(quantity));
        var discount = (total * (discountPer)) / 100;
        var tax = (((total - discount) * (taxPer)) / 100);
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
    $('#discount-total').text(totalDiscount ? numberFormatDisplay(totalDiscount) : 0);;
    $('#grand-total-data').text(netTotal ? numberFormatDisplay(netTotal) : 0);
    $('#return-amount').val(netTotal ? numberFormatDisplay(netTotal) : 0);
}

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
            quantity: quantity,
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
