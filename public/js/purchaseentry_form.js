var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$("#js-purchaseentry-medicine-modal").on('shown.bs.modal', function(){
    $(this).find('#js-purchaseentry-flditem-input-modal').focus();
});

$('#js-purchaseentry-medicine-modal').on('hidden.bs.modal', function (e) {
    $('#js-purchaseentry-batch-input').focus();
})


$('#js-purchaseentry-reforderno-select').keydown(function(e) {
    var code = e.keyCode || e.which;
    e.preventDefault();
    if (code === 9) {
        $('#js-purchaseentry-medicine-input').trigger('mousedown');
    }
});

$(document).on('change', '#js-purchaseentry-supplier-select', function() {
    var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
    $('#js-purchaseentry-address-input').val($(selectedOption).data('fldsuppaddress'));
    var isOpeningStock = 0;
    if ($('#isOpeningStock').length) {
        if ($('#isOpeningStock').prop("checked") == true) {
            isOpeningStock = 1;
        }
    }

    $.ajax({
        url: baseUrl + "/purchaseentry/getRefrence",
        type: "GET",
        data: {
            fldsuppname: $(selectedOption).val(),
            billno: $('#js-purchaseentry-billno-input').val(),
            paymenttype: $('#js-purchaseentry-payment-type-select').val(),
            isOpening: isOpeningStock
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                var optionData = '';
                var subtotal = 0.0;
                var totaldisc = 0.0;
                var totaltax = 0.0;
                var totalamt = 0.0;
                var totalccost = 0.0;
                var vatmtdr =  $('#js-purchaseentry-vatableamt-input').val();
                var nonvatmtdr =  $('#js-purchaseentry-nonvatableamt-input').val();
                optionData += '<option value="">-- Select --</option>';
                $.each(response.refDatas, function(i, option) {
                    optionData += '<option value="' + option + '">' + option + '</option>';
                });
                $('#js-purchaseentry-reforderno-select').empty().html(optionData);
                $.each(response.pendingPurchaseEntries, function(i, entry) {
                    var trData = '';
                    trData += '<tr data-fldid="' + entry.fldid + '">';
                    trData += '<td>' + ($('#js-purchaseentry-entry-tbody tr').length+1) + '</td>';
                    trData += '<input type="hidden" value="' + entry.fldid + '" name="purchaseid[]">';
                    trData += '<input type="hidden" value="' + entry.hasvat + '" name="hasvat[]">';
                    trData += '<td>' + entry.fldstockid + '</td>';
                    trData += '<td>' + entry.fldbatch + '</td>';
                    trData += '<td>' + entry.fldexpiry + '</td>';
                    trData += '<td>' + (entry.fldnetcost ? entry.fldnetcost : '') + '</td>';
                    trData += '<td>' + (entry.flsuppcost ? entry.flsuppcost : '') + '</td>';

                    trData += '<td class="vat-amt-td">' + (entry.fldvatamt ? entry.fldvatamt : '0') + '</td>';
                    var ccost = (entry.fldcarcost ? entry.fldcarcost : 0.00);

                    // trData += '<td>0</td>';
                    var total_qty = Number(entry.fldtotalqty ? entry.fldtotalqty : 0) + Number(entry.fldqtybonus ? entry.fldqtybonus : 0);
                    trData += '<td>' + total_qty + '</td>';
                    // trData += '<td>' + (entry.fldtotalqty ? entry.fldtotalqty : '') + '</td>';

                    vatmtdr =  $('#js-purchaseentry-vatableamt-input').val();
                    nonvatmtdr =  $('#js-purchaseentry-nonvatableamt-input').val();
                 //   alert(entry.fldvatamt)

                   if(Number(entry.fldvatamt) > 0){
              //   var ttp =  (parseFloat(entry.flsuppcost)*(parseFloat(entry.fldtotalqty ? entry.fldtotalqty : 0 )-parseFloat(entry.fldqtybonus ? entry.fldqtybonus : 0 )));
                       var ttp =  (parseFloat(entry.fldnetcost)*(parseFloat(total_qty ? total_qty : 0 )-parseFloat(entry.fldqtybonus ? entry.fldqtybonus : 0 )));

                       vatmtdr =  Number(vatmtdr) + (Number(ttp) - entry.fldvatamt) + Number(ccost);



                   }else{
                       var ttp =  (parseFloat(entry.fldnetcost)*(parseFloat(total_qty ? total_qty : 0 )-parseFloat(entry.fldqtybonus ? entry.fldqtybonus : 0 )));
                       nonvatmtdr = Number(nonvatmtdr) + Number(ttp) + Number(ccost);

                   }



                   if(vatmtdr >= 0){
                    $('#js-purchaseentry-vatableamt-input').val(vatmtdr);
                    $('#js-purchaseentry-vatableamt-input-vtt').val(vatmtdr);
                   }

                   if(nonvatmtdr >= 0){
                    $('#js-purchaseentry-nonvatableamt-input').val(nonvatmtdr);
                    $('#js-purchaseentry-nonvatableamt-input-vtt').val(nonvatmtdr);
                   }



                    trData += '<td>' + (entry.fldcasdisc ? entry.fldcasdisc : '') + '</td>';
                    trData += '<td>' + (entry.fldcashbonus ? entry.fldcashbonus : '') + '</td>';
                    trData += '<td>' + (entry.fldqtybonus ? entry.fldqtybonus : '') + '</td>';

                    trData += '<td>' + ccost + '</td>';
                    trData += '<td>' + (entry.fldcurrcost ? entry.fldcurrcost : '') + '</td>';
                    trData += '<td>0</td>';
                    trData += '<td>' + (entry.fldsellprice ? entry.fldsellprice : '') + '</td>';
                    var totalcost = (entry.fldtotalcost ? entry.fldtotalcost : 0.00);
                    var tax = entry.fldvatamt ? Number(entry.fldvatamt) : 0;
                    var stotal = Number(totalcost) - Number(tax);
                    trData += '<td>' + stotal + '</td>';
                    var costwithcc = Number(totalcost) + Number(ccost);
                    trData += '<td>' + costwithcc + '</td>';
                    var qty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);

                    var cost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                    var total = qty * cost;
                    var disc = entry.fldcasdisc ? Number(entry.fldcasdisc) : 0;

                    trData += '<td><button class="btn btn-danger" onclick="deleteentry(' + entry.fldid + ','+total+','+disc+','+tax+','+totalcost+','+stotal+','+ccost+','+(entry.fldnetcost ?  entry.fldnetcost : 0) +','+(total_qty ? total_qty : 0)+','+(entry.fldqtybonus ? entry.fldqtybonus : 0)+')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    trData += '</tr>';

                    subtotal = (parseFloat(subtotal) + stotal);

                    totaltax = parseFloat(totaltax) + (entry.fldvatamt ? parseFloat(entry.fldvatamt) : 0);
                    totalamt = parseFloat(totalamt) + (costwithcc);
                    totalccost += Number(ccost);
                    $('#js-purchaseentry-entry-tbody').append(trData);

                });

                // alert(vatmtdr)
                // alert(nonvatmtdr)

                // if(vatmtdr >= 0){
                //     $('#js-purchaseentry-vatableamt-input').val(vatmtdr);
                //     $('#js-purchaseentry-vatableamt-input-vtt').val(vatmtdr);
                //    }

                //    if(nonvatmtdr >= 0){
                //     $('#js-purchaseentry-nonvatableamt-input').val(nonvatmtdr);
                //     $('#js-purchaseentry-nonvatableamt-input-vtt').val(nonvatmtdr);
                //    }

                var hasvat = $("input[name='hasvat[]']").map(function(){return $(this).val();}).get();
                if(jQuery.inArray("Yes", hasvat) != -1) {
                    $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                }



                if(response.pendingPurchaseEntries.length > 0){
                    $('#js-purchaseentry-payment-type-select').attr('readonly','readonly');
                    $('#js-purchaseentry-billno-input').attr('readonly','readonly');
                    $('#js-purchaseentry-supplier-select').attr('readonly','readonly');
                    $('#js-purchaseentry-reforderno-select').attr('readonly','readonly');
                    $('#js-purchaseentry-subtotal-input').val(subtotal);
                    // $('#js-purchaseentry-discount-input').val(totaldisc);
                    $('#js-purchaseentry-totaltax-input').val(totaltax);
                    $('#js-purchaseentry-totalamt-input').val(totalamt);
                    $('#js-purchaseentry-amt-input').val(totalamt);
                    $('#js-purchaseentry-ccost-input').val(totalccost);
                    if(Number(totaltax) > 0){
                        alert('Group Tax cannot be given...')
                        document.getElementById("grouptaxon").disabled = true;
                        $('#js-purchaseentry-grouptax-input').val(0);

                    }else{
                        var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
                        amt = (13/100)*parseFloat(totalamt);
                        $('#js-purchaseentry-grouptax-input').val(amt);
                        document.getElementById("grouptaxon").disabled = false;

                        var vatamt = $('#js-purchaseentry-grouptax-input').val() || 0;

                        var discountedamt = Number(totalamt) + Number(vatamt);
                        $('#js-purchaseentry-amt-input').val(discountedamt);
                    }
                }

            }
        }
    });
});

$(document).on('change', '#js-purchaseentry-reforderno-select', function() {
    if($('#js-purchaseentry-reforderno-select').val() != ""){
        var selectedOption = $('#js-purchaseentry-supplier-select option:selected')
        var isOpeningStock = 0;
        var ccost = 0.0;
        if ($('#isOpeningStock').length) {
            if ($('#isOpeningStock').prop("checked") == true) {
                isOpeningStock = 1;
            }
        }
        $.ajax({
            url: baseUrl + "/purchaseentry/getPendingPurchaseByRefNo",
            type: "GET",
            data: {
                fldsuppname: $(selectedOption).val(),
                billno: $('#js-purchaseentry-billno-input').val(),
                paymenttype: $('#js-purchaseentry-payment-type-select').val(),
                refNo: $('#js-purchaseentry-reforderno-select').val(),
                isOpening: isOpeningStock
            },
            dataType: "json",
            success: function (response) {
                if(response.status){
                    var subtotal = 0.0;
                    var totaldisc = 0.0;
                    var totaltax = 0.0;
                    var totalamt = 0.0;
                    var totalccost = 0.0;
                    var ccost = 0.0;

                    $.each(response.pendingPurchaseEntries, function(i, entry) {
                        var trData = '';
                        trData += '<tr data-fldid="' + entry.fldid + '">';
                        trData += '<td>' + ($('#js-purchaseentry-entry-tbody tr').length+1) + '</td>';
                        trData += '<input type="hidden" value="' + entry.fldid + '" name="purchaseid[]">';
                        trData += '<input type="hidden" value="' + entry.hasvat + '" name="hasvat[]">';
                        trData += '<td>' + entry.fldstockid + '</td>';
                        trData += '<td>' + entry.fldbatch + '</td>';
                        trData += '<td>' + entry.fldexpiry + '</td>';
                        trData += '<td>' + (entry.fldnetcost ? entry.fldnetcost : '') + '</td>';
                        trData += '<td>' + (entry.flsuppcost ? entry.flsuppcost : '') + '</td>';
                        trData += '<td class="vat-amt-td">' + (entry.fldvatamt ? entry.fldvatamt : '0') + '</td>';
                        var total_qty = Number(entry.fldtotalqty ? entry.fldtotalqty : 0) + Number(entry.fldqtybonus ? entry.fldqtybonus : 0);
                        trData += '<td>' + total_qty + '</td>';
                        trData += '<td>' + (entry.fldcasdisc ? entry.fldcasdisc : '') + '</td>';
                        trData += '<td>' + (entry.fldcashbonus ? entry.fldcashbonus : '') + '</td>';
                        trData += '<td>' + (entry.fldqtybonus ? entry.fldqtybonus : '') + '</td>';
                        var ccost = (entry.fldcarcost ? entry.fldcarcost : 0.00);
                        trData += '<td>' + ccost + '</td>';
                        trData += '<td>' + (entry.fldcurrcost ? entry.fldcurrcost : '') + '</td>';
                        trData += '<td>0</td>';
                        trData += '<td>' + (entry.fldsellprice ? entry.fldsellprice : '') + '</td>';
                        var totalcost = (entry.fldtotalcost ? entry.fldtotalcost : 0.00);
                        var tax = entry.fldvatamt ? Number(entry.fldvatamt) : 0;
                        var stotal = Number(totalcost) - Number(tax);
                        trData += '<td>' + stotal + '</td>';
                        var costwithcc = Number(totalcost) + Number(ccost);
                        trData += '<td>' + costwithcc + '</td>';
                        var qty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);
                        var cost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                        var total = parseFloat(total) + (qty * cost);
                        var disc = entry.fldcasdisc ? Number(entry.fldcasdisc) : 0;

                        trData += '<td><button class="btn btn-danger" onclick="deleteentry(' + entry.fldid + ','+total+','+disc+','+tax+','+totalcost+','+stotal+','+ccost+','+(entry.fldnetcost ? entry.fldnetcost : 0) +','+(total_qty ? total_qty : 0)+','+(entry.fldqtybonus ?  entry.fldqtybonus : 0)+')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                        trData += '</tr>';

                        subtotal = (parseFloat(subtotal) + stotal);

                        totaltax = parseFloat(totaltax) + (entry.fldvatamt ? parseFloat(entry.fldvatamt) : 0);
                        totalamt = parseFloat(totalamt) + costwithcc;
                        totalccost += Number(ccost);
                        $('#js-purchaseentry-entry-tbody').append(trData);
                    });

                    var hasvat = $("input[name='hasvat[]']").map(function(){return $(this).val();}).get();
                    if(jQuery.inArray("Yes", hasvat) != -1) {
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                    }else{
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                    }

                    if(response.pendingPurchaseEntries.length > 0){
                        $('#js-purchaseentry-payment-type-select').attr('readonly','readonly');
                        $('#js-purchaseentry-billno-input').attr('readonly','readonly');
                        $('#js-purchaseentry-supplier-select').attr('readonly','readonly');
                        $('#js-purchaseentry-reforderno-select').attr('readonly','readonly');
                        $('#js-purchaseentry-subtotal-input').val(subtotal);
                        // $('#js-purchaseentry-discount-input').val(totaldisc);
                        $('#js-purchaseentry-totaltax-input').val(totaltax);
                        $('#js-purchaseentry-totalamt-input').val(totalamt);
                        $('#js-purchaseentry-amt-input').val(totalamt);
                        $('#js-purchaseentry-ccost-input').val(totalccost);
                    }
                }
            }
        });
    }
});

$('#js-purchaseentry-flditem-input-modal').keyup(function() {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-purchaseentry-table-modal tr td:first-child'), function(i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        var trElem = $(e).closest('tr');

        if (tdText.search(searchText) >= 0)
            $(trElem).show();
        else
            $(trElem).hide();
    });
});

$('#js-purchaseentry-medicine-input').on('mousedown', function(e) {
    e.preventDefault();
    $('#js-purchaseentry-flditem-input-modal').val("");
    if ($('#isOpeningStock').length) {
        if ($('#isOpeningStock').prop("checked") == false) {
            // if(directPurchaseEntry == "Yes"){
                if($('#js-purchaseentry-supplier-select').val() == ""){
                    alert("Please select supplier");
                    return false;
                }
            // }
        }
    }
    $('.markreadonly').attr('readonly', true);
    var route = $('#js-purchaseentry-route-select').val() || '';
    var reforderno = $('#js-purchaseentry-reforderno-select').val() || '';
    if (reforderno != '') {
        var orderBy = $('input[type="radio"][name="type"]:checked').val();
        $.ajax({
            url: baseUrl + '/purchaseentry/getMedicineList',
            type: "GET",
            data: {
                route: route,
                orderBy: orderBy,
                reforderno: reforderno,
            },
            dataType: "json",
            success: function (response) {
                var trData = '';
                $.each(response, function(i, medicine) {
                    if(medicine.fldremqty > 0){
                        var flditemname = (orderBy == 'brand') ? medicine.flditemname : medicine.flditemname;

                        var dataAttributes =  "data-fldstockid='" + medicine.flditemname + "'";
                        dataAttributes +=  " data-fldquantity='" + medicine.fldremqty + "'";
                        dataAttributes +=  " data-fldid='" + medicine.fldid + "'";
                        dataAttributes +=  " data-fldrate='" + medicine.fldrate + "'";
                        trData += '<tr ' + dataAttributes + '>';
                        trData += '<td>' + flditemname + '</td>';
                        trData += '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                        trData += '</tr>';
                    }
                });
                $('#js-purchaseentry-table-modal').empty().html(trData);
                $('#js-purchaseentry-medicine-modal').modal('show');
            }
        });
    } else {
        if ($('#isOpeningStock').length) {
            if ($('#isOpeningStock').prop("checked") == true) {
                getMedicineModal();
            }else{
                getMedicineModal();
            }
        }else{
            getMedicineModal();
        }
    }
});

function getMedicineModal(){
    $.ajax({
        url: baseUrl + '/store/purchaseorder/getMedicineList',
        type: "GET",
        data: {
            // route: route,
            orderBy: $('input[type="radio"][name="type"]:checked').val(),
        },
        dataType: "json",
        success: function (response) {
            // var trData = '';
            // $.each(response, function(i, medicine) {
            //     var dataAttributes =  "data-fldstockid='" + medicine.col + "'";
            //     trData += '<tr ' + dataAttributes + '>';
            //     trData += '<td>' + medicine.col + '</td>';
            //     trData += '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
            //     trData += '</tr>';
            // });
            $('#js-purchaseentry-table-modal').empty().html(response.html);
            $('#js-purchaseentry-medicine-modal').modal('show');
        }
    });
}

$(document).on('click', '#js-purchaseentry-table-modal tr', function() {
    selected_td('#js-purchaseentry-table-modal tr', this);
});

$('#js-purchaseentry-add-btn-modal').click(function() {
    var selectedTr = $('#js-purchaseentry-table-modal tr[is_selected="yes"]');
    var particular = $(selectedTr).data('fldstockid') || '';
    if (particular != '') {
        var quantity = $(selectedTr).data('fldquantity');
        var rate = $(selectedTr).data('fldrate');
        $('#js-purchaseentry-medicine-input').val(particular);
        $('#js-purchaseentry-totalqty-input').attr('data-max', quantity);
        $('#ordfldid').val($(selectedTr).data('fldid'));
        $('#js-purchaseentry-table-modal').empty().html('');
        $('#js-purchaseentry-medicine-modal').modal('hide');
        $('#js-purchaseentry-totalqty-input').val(quantity);
        $('#js-purchaseentry-rate-input').val(0);
        if(typeof rate !== 'undefined' && rate !== false){
            $('#js-purchaseentry-totalcost-input').val(parseFloat($(selectedTr).data('fldrate')) * parseFloat(quantity));
            $('#js-purchaseentry-totalcost-input').prop('readonly',true);
            $('#js-purchaseentry-rate-input').val(parseFloat(rate));
        }
    } else
        showAlert('Please select medicine to save.', 'fail');
});

function checkNumber(currentElement) {
    var number = $(currentElement).val().replace(/[^0-9.]/g, '');
    if (isNaN(Number(number))){
        showAlert('Please enter valid number', 'fail');
        number = number.substring(0, number.length - 1);
    }

    return number;
}

function calculateTax() {
    var hasvat = ($('#js-purchaseentry-tax-inex-select').val() == 'Inclusive');
    var amt = Number($('#js-purchaseentry-totalcost-input').val()) || 0;
    var vat = 0;
    var finalAmt = amt;

    var cccharge = Number($('#js-purchaseentry-carrycostpercentage-input').val()) || 0;
    var taxableamt =  Number(amt)+Number(cccharge);

    if (hasvat) {
        vat = parseFloat(0.13*(taxableamt)).toFixed(2);
        finalAmt = Number(finalAmt) + Number(vat);
    }

    $('#js-purchaseentry-vat-input').val(vat);
    $('#js-purchaseentry-amtaftervat-input').val(finalAmt);
    var totalcostin =  Number($('#js-purchaseentry-totalcost-input').val()) || 0;
    var totalqty =  Number($('#js-purchaseentry-totalqty-input').val()) || 0;
    var profit =  Number($('#js-purchaseentry-profitpercentage-input').val()) || 0;
    var qwt = ( totalcostin / totalqty);
    var pprofit  = qwt + (profit/100) * (totalcostin / totalqty);

    var newselllprice =  parseFloat(pprofit).toFixed(2) || 0;


    $('#js-purchaseentry-newsellprice-input').val(newselllprice);
    calculateUnitrate();
}

function calculateUnitrate() {
    var quantity =  Number($('#js-purchaseentry-totalqty-input').val()) || 0;

    if (quantity != 0) {
        var profit = Number($('#js-purchaseentry-profitpercentage-input').val()) || 0;
        var totalcost =  Number($('#js-purchaseentry-totalcost-input').val()) || 0;
        var totalcostvat =  Number($('#js-purchaseentry-amtaftervat-input').val()) || 0;
        var vat = Number($('#js-purchaseentry-vat-input').val()) || 0;
        profit = (profit/100)*(totalcostvat/quantity);
        // if($('#js-purchaseentry-reforderno-select').val() == ""){
        //     var rate = totalcostvat/quantity;
        // }else{
        //     var rate = parseFloat($('#js-purchaseentry-rate-input').val()).toFixed(2);
        // }

        var netunitcost = parseFloat(totalcost/quantity).toFixed(2) || 0;
        var distunitcost = parseFloat(totalcostvat/quantity).toFixed(2)  || 0;
        $('#js-purchaseentry-netunitcost-input').val(netunitcost);

        $('#js-purchaseentry-distunitcost-input').val(distunitcost);
        var totalcostin = $('#js-purchaseentry-totalcost-input').val()  || 0;
        var totalqty = $('#js-purchaseentry-totalqty-input').val()  || 0;
        var profit = $('#js-purchaseentry-profitpercentage-input').val()  || 0;
        var qwt = ( totalcostin / totalqty);
        var pprofit  = qwt + (profit/100) * ( totalcostin / totalqty);

        var newselllprice =  parseFloat(pprofit).toFixed(2)  || 0;
       $('#js-purchaseentry-newsellprice-input').val(newselllprice);
    }
}

$('#js-purchaseentry-newsellprice-input').keyup(function(e) {
    var newsellingprice = $('#js-purchaseentry-newsellprice-input').val() || 0;
    var totalcost =  $('#js-purchaseentry-totalcost-input').val() || 0;
    var quantity =  $('#js-purchaseentry-totalqty-input').val() || 0;
    if(newsellingprice != 0 && totalcost != 0 && quantity != 0){
        var netunitcost = parseFloat(totalcost/quantity).toFixed(2);
        var profit = ((newsellingprice-netunitcost)/netunitcost)*100;
        $('#js-purchaseentry-profitpercentage-input').val(profit);
    }
});

$(document).on('focusout','#js-purchaseentry-batch-input',function(e) {
    $.ajax({
        url: baseUrl + "/purchaseentry/check-batch",
        type: "get",
        data: {
            batch: $('#js-purchaseentry-batch-input').val(),
            particulars: $('#js-purchaseentry-medicine-input').val()
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                $('#js-purchaseentry-expiry-input').val(response.expiryDate);
                $('#js-purchaseentry-expiry-input').prop('readonly',true);
            }else{
                $('#js-purchaseentry-expiry-input').prop('readonly',false);
            }
        }
    });
});

$('#js-purchaseentry-qtybonus-input').keyup(function(e){
    var qtybonus = Number($(this).val());
    var quantity = Number($('#js-purchaseentry-totalqty-input').val());
    var entryqty = Number(quantity) + Number(qtybonus);
    $('#js-purchaseentry-totalentryqty-input').val(entryqty);
});

$('#js-purchaseentry-totalqty-input').keyup(function(e) {
    var quantity = checkNumber($(this));
    if ($('#isOpeningStock').prop("checked") != true) {
        var route = $('#js-purchaseentry-route-select').val() || '';
        var reforderno = $('#js-purchaseentry-reforderno-select').val() || '';
        if (route != '' && reforderno != '') {
            var max = $(this).data('max') || 0;
            if (Number(quantity) > Number(max)) {
                showAlert('Quantity cannot be greater than ' + max, 'fail');
                quantity = max;
            }

            $(this).val(quantity);

            if($('#js-purchaseentry-totalcost-input').is('[readonly]')) {
                var rate = $('#js-purchaseentry-rate-input').val();
                $('#js-purchaseentry-totalcost-input').val(Number(quantity) * rate);
            }
        }
    }
    var qtybonus = $('#js-purchaseentry-qtybonus-input').val();
    var entryqty = Number(quantity) + Number(qtybonus);
    $('#js-purchaseentry-totalentryqty-input').val(entryqty);
});

$('#js-purchaseentry-totalcost-input').keyup(function() {
    var totalcost = checkNumber($(this));
    $(this).val(totalcost);
    calculateTax();
});

$('#js-purchaseentry-carrycostpercentage-input').keyup(function() {
    calculateTax();
});

$('.js-number-validation').keyup(function() {
    var totalcost = checkNumber($(this));
    $(this).val(totalcost);
});

$('#js-purchaseentry-tax-inex-select').change(function() {
    calculateTax();
});
$('#js-purchaseentry-totalqty-input,#js-purchaseentry-profitpercentage-input').keyup(function() {
    calculateUnitrate();
});

function deleteentry(fldid,total,disc,tax,totalcost,stotal,ccost,unitcost,totalqty,bonusqty) {
    if (confirm('Are you sure to delete?')) {
        $.ajax({
            url: baseUrl + "/purchaseentry/delete",
            type: "POST",
            data: {
                fldid: fldid,
            },
            dataType: "json",
            success: function (response) {
                var vatableamt =  $('#js-purchaseentry-vatableamt-input').val();
                var nonvatableamt =  $('#js-purchaseentry-nonvatableamt-input').val();
                console.log('first stotal',total);
                console.log('vatableamt',vatableamt);
                console.log('nonvatableamt',nonvatableamt);
                var status = (response.status) ? 'success' : 'fail';
                if (response.status){
                    $('#js-purchaseentry-entry-tbody tr[data-fldid="' + fldid + '"]').remove();
                }

                var subtotal = $('#js-purchaseentry-subtotal-input').val() || 0;
                subtotal = (parseFloat(subtotal) - total);
                $('#js-purchaseentry-subtotal-input').val(subtotal);
                var ccosttotal = $('#js-purchaseentry-ccost-input').val() || 0;
                ccosttotal = Number(ccosttotal) - ccost;
                $('#js-purchaseentry-ccost-input').val(ccosttotal);
                var totaltax = $('#js-purchaseentry-totaltax-input').val() || 0;
                totaltax = Number(totaltax) - tax;
                $('#js-purchaseentry-totaltax-input').val(totaltax);
                var ttp = (parseFloat(unitcost)*(parseFloat(totalqty ? totalqty : 0 )-parseFloat(bonusqty ? bonusqty : 0 ))+parseFloat(ccost));

                var vatmtdr = $('#js-purchaseentry-vatableamt-input-vtt').val();
                var nonvatmtdr = $('#js-purchaseentry-nonvatableamt-input-vtt').val();
               // if(Number(tax) > 0 ){
                     if(vatmtdr > 0){
                        var deductedamt = ttp;
                    //    alert(deductedamt);
                        vatmtdr =Number(vatmtdr) - Number(deductedamt);

                        // $('#js-purchaseentry-vatableamt-input').val(vatmtdr);

                        $('#js-purchaseentry-vatableamt-input-vtt').val(vatmtdr);
                     }




               // }
                //if(Number(tax) <= 0){
                 if(nonvatmtdr > 0){
                        nonvatmtdr =Number(nonvatmtdr) -  Number(ttp);

                        // $('#js-purchaseentry-nonvatableamt-input').val(nonvatmtdr);
                        $('#js-purchaseentry-nonvatableamt-input-vtt').val(nonvatmtdr);
                     }



                //}

                if(Number(totaltax) > 0){
                    alert('Group Tax cannot be given')
                    document.getElementById("grouptaxon").disabled = true;
                    $('#js-purchaseentry-grouptax-input').val(0);

                }else{
                    var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
                    amt = (13/100)*parseFloat(totalamt);
                    $('#js-purchaseentry-grouptax-input').val(amt);
                    document.getElementById("grouptaxon").disabled = false;

                    var vatamt = $('#js-purchaseentry-grouptax-input').val() || 0;

                    var discountedamt = Number(totalamt) + Number(vatamt);
                    $('#js-purchaseentry-amt-input').val(discountedamt);
                }

                var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
                totalamt = Number(totalamt) - Number(totalcost) - Number(ccost);
                $('#js-purchaseentry-totalamt-input').val(totalamt);
                $('#js-purchaseentry-amt-input').val(totalamt);

                var hasvat = $("input[name='hasvat[]']").map(function(){return $(this).val();}).get();
                if(jQuery.inArray("Yes", hasvat) != -1) {
                    $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                }else{
                    $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                }

                changeTaxAmt();
                var hasVat = 0;

                $('.vat-amt-td').each(function(){
                    var val =  $(this).html();
                    if(val != 0 || val != 0.0){
                        hasVat =1;
                        $('#grouptaxon').prop('checked',false);
                        $('#grouptaxon').attr('disabled',true);
                        $('#js-purchaseentry-discount-input').attr('disabled',true);
                        $('#js-purchaseentry-tax-inex-select').val('Inclusive');
                        $('#js-purchaseentry-tax-inex-select').attr('disabled',true);
                    }
                })

                if(tax == 0){
                    nonvatableamt -=total;
                    $('#js-purchaseentry-nonvatableamt-input').val(Number(nonvatableamt).toFixed(2));
                    $('#js-purchaseentry-vatableamt-input').val(Number(vatableamt).toFixed(2));
                }else{
                    // var total_tax_input = $('#js-purchaseentry-totaltax-input').val();
                    // total_tax_input -= tax;
                    // $('#js-purchaseentry-totaltax-input').val(total_tax_input);
                    vatableamt -=total;
                    $('#js-purchaseentry-vatableamt-input').val(Number(vatableamt).toFixed(2));
                    $('#js-purchaseentry-nonvatableamt-input').val(Number(nonvatableamt).toFixed(2));
                }

                if(hasVat == 0){
                    $('#grouptaxon').prop('checked',false);
                    $('#grouptaxon').attr('disabled',false);
                    $('#js-purchaseentry-discount-input').attr('disabled',false);
                    $('#js-purchaseentry-tax-inex-select').val('Exclusive');
                    $('#js-purchaseentry-tax-inex-select').attr('disabled',false);
                }


                showAlert(response.message, status);
            }
        });
    }
}

function checkValidation(idName){
    var hasError = false;
    if($('#'+idName).val() == ""){
        hasError = true;
        if($('#'+idName).closest('div').find('.error').length == 0){
            $('#'+idName).closest('div').append('<span class="error text-danger">This field is required</span>');
        }
    }else{
        if($('#'+idName).closest('div').find('.error').length != 0){
            $('#'+idName).closest('div').find('.error').remove();
        }
    }
    return hasError;
}

$('#js-purchaseentry-add-btn').click(function(e) {
    e.preventDefault();
    var vattype = $('#js-purchaseentry-tax-inex-select').val();
    var error = false;
    var oldtax = $('#js-purchaseentry-totaltax-input').val();
    var ctype = $('#js-purchaseentry-tax-inex-select option:selected').val();
    // if(Number(oldtax) > 0 && ctype == 'Exclusive'){
    //     alert('Group Tax cannot be given so exclusive item cannot be added')
    //     document.getElementById("grouptaxon").disabled = true;
    //     error = true;
    // }

    var checkBox = document.getElementById("grouptaxon");
    var oldtaxgrp = $('#js-purchaseentry-grouptax-input').val();
    //alert(checkBox.checked);


    if (checkBox.checked === true && Number(oldtaxgrp) > 0 && ctype == 'Inclusive'){
        alert('Group Tax cannot be given so inclusive item cannot be added');
        var inditax = Number($('#js-purchaseentry-totaltax-input').val()) || 0;
        // alert(inditax);
         if(inditax > 0){
             document.getElementById("grouptaxon").disabled = true;
         }else{
             document.getElementById("grouptaxon").disabled = false;
         }


        error = true;
    }


    if(checkValidation("js-purchaseentry-billno-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-totalcost-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-totalqty-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-newsellprice-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-medicine-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-batch-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-expiry-input") == true){
        error = true;
    }
    if(checkValidation("js-purchaseentry-tax-inex-select") == true){
        error = true;
    }

    if(!error){
        if(new Date($('#js-purchaseentry-expiry-input').val()).setHours(0,0,0,0) <= new Date(today))
        {
            if($('#js-purchaseentry-expiry-input').closest('div').find('.error').length == 0){
                $('#js-purchaseentry-expiry-input').closest('div').append('<span class="error text-danger">Expiry date must greater than today date</span>');
            }
            return false;
        }else{
            if($('#js-purchaseentry-expiry-input').closest('div').find('.error').length != 0){
                $('#js-purchaseentry-expiry-input').closest('div').find('.error').remove();
            }
        }
        $.ajax({
            url: baseUrl + "/purchaseentry/save",
            type: "POST",
            data: $('#js-purchaseentry-form').serialize(),
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var entry = response.data;
                    var trData = '<tr data-fldid="' + entry.fldid + '">';
                    trData += '<td>' + ($('#js-purchaseentry-entry-tbody tr').length+1) + '</td>';
                    trData += '<input type="hidden" value="' + entry.fldid + '" name="purchaseid[]">';
                    trData += '<input type="hidden" value="' + entry.hasvat + '" name="hasvat[]">';
                    trData += '<td>' + entry.fldstockid + '</td>';
                    trData += '<td>' + entry.fldbatch + '</td>';
                    trData += '<td>' + entry.fldexpiry + '</td>';
                    trData += '<td>' + (entry.fldnetcost ? entry.fldnetcost : '') + '</td>';
                    trData += '<td>' + (entry.flsuppcost ? entry.flsuppcost : '') + '</td>';
                    trData += '<td class="vattt vat-amt-td">' + (entry.fldvatamt ? entry.fldvatamt : '0') + '</td>';


                    var total_qty = Number(entry.fldtotalqty ? entry.fldtotalqty : 0) + Number(entry.fldqtybonus ? entry.fldqtybonus : 0);
                    trData += '<td>' + total_qty + '</td>';
                    trData += '<td>' + (entry.fldcasdisc ? entry.fldcasdisc : '') + '</td>';
                    trData += '<td>' + (entry.fldcashbonus ? entry.fldcashbonus : '') + '</td>';
                    trData += '<td>' + (entry.fldqtybonus ? entry.fldqtybonus : '') + '</td>';
                    var ccost = (entry.fldcarcost ? entry.fldcarcost : 0.00);
                    trData += '<td>' + ccost + '</td>';
                    trData += '<td>' + (entry.fldcurrcost ? entry.fldcurrcost : '') + '</td>';
                    trData += '<td>0</td>';
                    trData += '<td>' + (entry.fldsellprice ? entry.fldsellprice : '') + '</td>';
                    var totalcost = (entry.fldtotalcost ? entry.fldtotalcost : 0.00);
                    var tax = entry.fldvatamt ? Number(entry.fldvatamt) : 0;
                    var stotal = Number(totalcost) - Number(tax);
                    trData += '<td>' + stotal + '</td>';
                    var costwithcc = Number(totalcost) + Number(ccost);
                    trData += '<td>' + costwithcc + '</td>';

                    //var qty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);
                    var qty =  Number(entry.fldtotalqty ? entry.fldtotalqty : 0) + Number(entry.fldqtybonus ? entry.fldqtybonus : 0);
                    var cost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                    var total = qty * cost;
                    var disc = entry.fldcasdisc ? Number(entry.fldcasdisc) : 0;

                    trData += '<td><button class="btn btn-danger" onclick="deleteentry(' + entry.fldid + ','+total+','+disc+','+tax+','+totalcost+','+stotal+','+ccost+','+(entry.fldnetcost ? entry.fldnetcost : 0) +','+(entry.fldtotalqty ? entry.fldtotalqty : 0)+','+(entry.fldqtybonus ? entry.fldqtybonus: 0) +')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    trData += '</tr>';
                    $('#js-purchaseentry-entry-tbody').append(trData);
                    $('.markreset').val('');
                    $('#js-purchaseentry-expiry-input').val(expiry);

                    var subtotal = $('#js-purchaseentry-subtotal-input').val() || 0;
                    // var totqty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);
                    // var netcost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                    subtotal = (parseFloat(subtotal) + stotal);
                    // subtotal = (parseFloat(subtotal) + (totqty * netcost));
                    $('#js-purchaseentry-subtotal-input').val(subtotal);
                    var ccosttotal = $('#js-purchaseentry-ccost-input').val() || 0;
                    ccosttotal = Number(ccosttotal) + Number(ccost);
                    $('#js-purchaseentry-ccost-input').val(ccosttotal);
                    var totaltax = $('#js-purchaseentry-totaltax-input').val() || 0;
                    totaltax = Number(totaltax) + (entry.fldvatamt ? Number(entry.fldvatamt) : 0);
                    $('#js-purchaseentry-totaltax-input').val(totaltax);
                    var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
                    totalamt = Number(totalamt) + costwithcc;
                    $('#js-purchaseentry-totalamt-input').val(totalamt);





                    var checkBox = document.getElementById("grouptaxon");
                    var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;

                    if (checkBox.checked == true){
                        amt = (13/100)*parseFloat(totalamt);
                        $('#js-purchaseentry-grouptax-input').val(amt);
                        $('#grouptaxon').val(1);
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');
                    } else {
                        $('#grouptaxon').val(0);
                        $('#js-purchaseentry-grouptax-input').val(0);
                        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                    }
                    var vatamt = $('#js-purchaseentry-grouptax-input').val() || 0;

                    var vatamts = Number(totalamt) + Number(vatamt);

                    $('#js-purchaseentry-amt-input').val(vatamts);


                    $ivat =  $('#js-purchaseentry-totaltax-input').val() || 0;

                    if(Number($ivat) > 0){
                        alert('Group Tax cannot be given.')
                        document.getElementById("grouptaxon").disabled = true;
                        $('#js-purchaseentry-grouptax-input').val(0);

                    }

                    if(Number(entry.fldvatamt) > 0){
                        var ttp =  (parseFloat(entry.fldnetcost)*(parseFloat(total_qty ? total_qty : 0 )-parseFloat(entry.fldqtybonus ? entry.fldqtybonus : 0 )) +parseFloat(ccost));
                        var vatmtdr = $('#js-purchaseentry-vatableamt-input').val();
                        vatmtdr =Number(vatmtdr) + (Number(ttp)) ;
                        if(vatmtdr >= 0){
                            $('#js-purchaseentry-vatableamt-input').val(vatmtdr);
                            $('#js-purchaseentry-vatableamt-input-vtt').val(vatmtdr);
                        }


                    }else{
                      //  alert('sdsd');
                        var ttp =  (parseFloat(entry.fldnetcost)*(parseFloat(total_qty ? total_qty : 0 )-parseFloat(entry.fldqtybonus ? entry.fldqtybonus : 0 ))+parseFloat(ccost)) ;
                        var nonvatmtdr = $('#js-purchaseentry-nonvatableamt-input').val();
                        nonvatmtdr =parseFloat(nonvatmtdr)+ parseFloat(ttp);
                        // alert(ttp);
                        // alert(nonvatmtdr);
                        if(nonvatmtdr >= 0){
                            $('#js-purchaseentry-nonvatableamt-input').val(nonvatmtdr);
                            $('#js-purchaseentry-nonvatableamt-input-vtt').val(nonvatmtdr);
                        }


                    }



                    var hasvat = $("input[name='hasvat[]']").map(function(){return $(this).val();}).get();
                    if(jQuery.inArray("Yes", hasvat) != -1) {
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                    }else{
                        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                    }


                }
                changeTaxAmt();
                // totalindivialtaxchange();
                // alert('tes');
                var inditax = Number($('#js-purchaseentry-totaltax-input').val()) || 0;
                // alert(inditax);
                 if(inditax > 0){
                     document.getElementById("grouptaxon").disabled = true;
                 }else{
                     document.getElementById("grouptaxon").disabled = false;
                 }
                showAlert(response.message, status);
            }
        });
    }

});

function totalindivialtaxchange(){
     vat = parseFloat(0);
    $(".vattt").each(function( index ) {
       vat+= parseFloat($(this).html())
    });
    //alert(vat);

}

$('#js-purchaseentry-finalsave-btn').click(function () {
    var isOpeningStock = 0;
    if ($('#isOpeningStock').length > 0) {
        if ($('#isOpeningStock').prop("checked") == true) {
            isOpeningStock = 1;
        }
    }
    $.ajax({
        url: baseUrl + '/purchaseentry/finalSave',
        type: "POST",
        data: {
            vatableamt: $('#js-purchaseentry-vatableamt-input').val(),
            nonvatableamt: $('#js-purchaseentry-nonvatableamt-input').val(),
            cccharge :$('#js-purchaseentry-ccost-input').val(),
            fldpurtype: $('#js-purchaseentry-payment-type-select').val(),
            fldbillno: $('#js-purchaseentry-billno-input').val(),
            fldsuppname: $('#js-purchaseentry-supplier-select').val(),
            fldpurdate: $('#js-purchaseentry-date-input').val(),
            groupdiscount: $('#js-purchaseentry-discount-input').val(),
            totaltax: $('#js-purchaseentry-totaltax-input').val(),
            purchaseIds: $('input[name="purchaseid[]"]').map(function () { return $(this).val(); }).get(),
            isOpeningStock: isOpeningStock,
            grouptaxon: $('#grouptaxon').val(),
            groupvatamt: $('#js-purchaseentry-grouptax-input').val(),
            individualtax : $('#js-purchaseentry-totaltax-input').val()
        },
        dataType: "json",
        success: function (response) {
            var status = (response.status) ? 'success' : 'fail';
            if (response.status) {
                $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
                $('#js-purchaseentry-entry-tbody').empty();
                $('#js-purchaseentry-refno-input').val(response.purchaseRefNo);

                $('.markreadonly').attr('readonly', true);
                $('.markreset').val('');
                $('#js-purchaseentry-expiry-input').val(expiry);

                $("input[type=text], input[type=number]").not('input[name=fldpurdate]').not('#js-purchaseentry-refno-input').val("");
                $("input[type=text], input[type=number]").not('#js-purchaseentry-address-input').not('input[name=fldvatamt],input[name=flsuppcost],input[name=fldcurrcost],input[name=fldtotalcost],input[name=fldnetcost]').attr('readonly', false);
                $("select").attr('readonly', false);
                $('#js-purchaseentry-payment-type-select').prop('selectedIndex',0);
                $('#js-purchaseentry-supplier-select').prop('selectedIndex',0);
                $('#js-purchaseentry-reforderno-select').prop('selectedIndex',0);

                window.open(baseUrl + '/purchaseentry/export?fldreference=' + response.purchaseRefNo, '_blank');
                location.reload();
            }
            showAlert(response.message, status);
        }
    });

});

$('#js-purchaseentry-export-btn').click(function() {
    var refno = $('#js-purchaseentry-refno-input').val() || '';
    if (refno != '')
        window.open(baseUrl + '/purchaseentry/export?fldreference=' + refno, '_blank');
});

$('#js-purchaseentry-export-excel-btn').click(function() {
    var refno = $('#js-purchaseentry-refno-input').val() || '';
    if (refno != '')
        window.open(baseUrl + '/purchaseentry/excel/export?fldreference=' + refno);
});

$(document).ready(function () {
    $('#isOpeningStock').trigger('change');
})

$(document).on('change','#isOpeningStock',function(){
    if ($(this).prop("checked") == true) {
        $('#download-purchaseentry-format').css("display", "inline-block");
        $('#import-purchaseentry').css("display", "inline-block");
        $('.markreadonly').attr('readonly', true);
        $('#js-purchaseentry-payment-type-select').prop('selectedIndex',1);
        $('#js-purchaseentry-billno-input').val("000");
        $('#js-purchaseentry-supplier-select').prop('selectedIndex',0);
        $('#js-purchaseentry-reforderno-select').prop('selectedIndex',0);
        $('#js-purchaseentry-address-input').val("");

        $.ajax({
            url: baseUrl + "/purchaseentry/getPendingOpeningStocks",
            type: "GET",
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status){
                    var subtotal = 0.0;
                    var sub_total = 0.0;
                    var final_sub_total = 0.0;
                    var totaldisc = 0.0;
                    var totaltax = 0.0;
                    var vatableamt = 0.0;
                    var nonvatableamt = 0.0;
                    var totalamt = 0.0;
                    var unitcost = 0.0;
                    var totalccost = 0.0;
                    $.each(response.pendingPurchaseEntries, function(i, entry) {
                        var trData = '';
                        var tax = entry.fldvatamt ? Number(entry.fldvatamt) : 0;
                        var total_qty = Number(entry.fldtotalqty ? entry.fldtotalqty : 0);
                        if((tax == 0) && (tax != null)){
                            nonvatableamt +=  Number(entry.fldnetcost) * Number(total_qty);
                            unitcost = entry.fldnetcost;
                        }else{
                            vatableamt +=  Number(entry.fldnetcost) * Number(total_qty);
                            unitcost = Number(entry.fldnetcost) + (Number(tax)/Number(total_qty));

                        }
                        trData += '<tr data-fldid="' + entry.fldid + '">';
                        trData += '<td>' + ($('#js-purchaseentry-entry-tbody tr').length+1) + '</td>';
                        trData += '<input type="hidden" value="' + entry.fldid + '" name="purchaseid[]">';
                        trData += '<td>' + entry.fldcategory + '</td>';
                        trData += '<td>' + entry.fldbatch + '</td>';
                        trData += '<td>' + entry.fldexpiry + '</td>';
                        trData += '<td>' +Number(entry.fldnetcost ? entry.fldnetcost : '').toFixed(2) + '</td>';
                        trData += '<td>' + Number(unitcost).toFixed(2) + '</td>';
                        trData += '<td class="vat-amt-td">' + (entry.fldvatamt ? entry.fldvatamt : '0') + '</td>';

                         sub_total = Number(entry.fldnetcost) * Number(total_qty);
                        final_sub_total  += sub_total;
                        trData += '<td>' + total_qty + '</td>';
                        trData += '<td>' + (entry.fldcasdisc ? entry.fldcasdisc : '') + '</td>';
                        trData += '<td>' + (entry.fldcasbonus ? entry.fldcasbonus : '') + '</td>';
                        trData += '<td>' + (entry.fldqtybonus ? entry.fldqtybonus : '') + '</td>';
                        var ccost = (entry.fldcarcost ? entry.fldcarcost : 0.00);
                        trData += '<td>' + ccost + '</td>';
                        trData += '<td>' + (entry.flsuppcost ? entry.flsuppcost : '') + '</td>';
                        trData += '<td>0</td>';
                        trData += '<td>' + (entry.fldsellprice ? entry.fldsellprice : '') + '</td>';
                        var totalcost = (entry.fldtotalcost ? entry.fldtotalcost : 0.00);


                        var stotal = Number(totalcost) - Number(tax);
                        trData += '<td>' + sub_total.toFixed(2) + '</td>';
                        var costwithcc = Number(sub_total.toFixed(2)) + Number(ccost);
                        trData += '<td>' + costwithcc.toFixed(2) + '</td>';

                        var qty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);
                        var cost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                        var total = qty * cost;
                        var disc = entry.fldcasdisc ? Number(entry.fldcasdisc) : 0;

                        trData += '<td><button class="btn btn-danger" onclick="deleteentry(' + entry.fldid + ','+total+','+disc+','+tax+','+totalcost+','+stotal+','+ccost+','+(entry.fldnetcost ? entry.fldnetcost: 0)+','+(total_qty ?  total_qty : 0 )+','+(entry.fldqtybonus ?  entry.fldqtybonus : 0)+')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                        trData += '</tr>';
                        // var totqty = (entry.fldtotalqty ? parseFloat(entry.fldtotalqty) : 0);
                        // var netcost = (entry.fldnetcost ? parseFloat(entry.fldnetcost) : 0);
                        subtotal = (parseFloat(subtotal) + stotal);

                        // subtotal = (parseFloat(subtotal) + (totqty * netcost));
                        // totaldisc = parseFloat(totaldisc) + (entry.fldcasdisc ? parseFloat(entry.fldcasdisc) : 0);
                        totaltax = parseFloat(totaltax) + (entry.fldvatamt ? parseFloat(entry.fldvatamt) : 0);
                        totalamt = parseFloat(totalamt) + costwithcc - disc;
                        totalccost += Number(ccost);
                        $('#js-purchaseentry-entry-tbody').append(trData);

                    });
                    totalamt = totalamt + totaltax;
                    var hasVat = 0;
                    $('.vat-amt-td').each(function(){
                       var val =  $(this).html();
                       if(val != 0 || val != 0.0){
                           hasVat =1;
                           $('#grouptaxon').attr('disabled',true);
                           $('#js-purchaseentry-tax-inex-select').val('Inclusive');
                           $('#js-purchaseentry-tax-inex-select').attr('disabled',true);
                           $('#js-purchaseentry-discount-input').attr('disabled',true);
                           $('#js-purchaseentry-vatableamt-input').val(vatableamt.toFixed(2));
                           $('#js-purchaseentry-nonvatableamt-input').val(nonvatableamt.toFixed(2));
                       }
                    })

                    if(hasVat == 0){
                        $('#js-purchaseentry-tax-inex-select').val('Exclusive');
                        $('#js-purchaseentry-vatableamt-input').val(vatableamt.toFixed(2));
                        $('#js-purchaseentry-nonvatableamt-input').val(nonvatableamt.toFixed(2));
                        $('#js-purchaseentry-tax-inex-select').attr('disabled',false);
                        $('#js-purchaseentry-discount-input').attr('disabled',false);
                        $('#grouptaxon').attr('disabled',false);
                    }

                    if(response.pendingPurchaseEntries.length > 0){
                        $('#js-purchaseentry-payment-type-select').prop('readonly',true);
                        $('#js-purchaseentry-billno-input').prop('readonly',true);
                        $('#js-purchaseentry-supplier-select').prop('readonly',true);
                        $('#js-purchaseentry-reforderno-select').prop('readonly',true);
                        // $('#js-purchaseentry-discount-input').val(totaldisc);
                        $('#js-purchaseentry-subtotal-input').val(final_sub_total.toFixed(2));
                        $('#js-purchaseentry-totaltax-input').val(totaltax.toFixed(2));
                        $('#js-purchaseentry-totalamt-input').val(totalamt.toFixed(2));
                        $('#js-purchaseentry-amt-input').val(totalamt.toFixed(2));
                        $('#js-purchaseentry-ccost-input').val(totalccost.toFixed(2));
                    }
                }
            }
        });
    } else {
        $('#download-purchaseentry-format').css("display", "none");
        $('#import-purchaseentry').css("display", "none");
        $('.markreadonly').attr('readonly', false);
        $('#js-purchaseentry-entry-tbody').html("");
    }
});

$(document).on('click', '#import-purchaseentry', function (){
    $('#purchaseEntryFile').trigger('click');
});

$(document).on('change', '#purchaseEntryFile', function () {
    $.ajax({
        url: baseUrl + '/purchaseentry/import-purchase-entry',
        method: "POST",
        data: new FormData($('#importPurchaseEntryForm')[0]),
        contentType: false,
        cache:false,
        processData: false,
        dataType:"json",
        success:function(data){
            if(data.status == false){
                showAlert(data.message, 'Error');
                return ;
            }
            if (data.status) {
                $('#js-purchaseentry-entry-tbody').html(data.html);
                $('#purchaseEntryFile').val('');
                $('#js-purchaseentry-subtotal-input').val(data.subtotal.toFixed(2));
                // $('#js-purchaseentry-discount-input').val(data.totaldisc.toFixed(2));
                $('#js-purchaseentry-totaltax-input').val(data.totaltax.toFixed(2));
                $('#js-purchaseentry-totalamt-input').val(data.totalamt.toFixed(2));
                $('#js-purchaseentry-ccost-input').val(data.totalCarryCost.toFixed(2));
                $('#js-purchaseentry-amt-input').val(data.totalamt.toFixed(2));
                var hasVat = 0;
                $('.vat-amt-td').each(function(){
                    var val =  $(this).html();
                    if(val != 0 || val != 0.0){
                        hasVat =1;
                        $('#grouptaxon').prop('checked',false);
                        $('#grouptaxon').attr('disabled',true);
                        $('#js-purchaseentry-discount-input').attr('disabled',true);
                        $('#js-purchaseentry-grouptax-input').val(0);
                        $('#js-purchaseentry-tax-inex-select').val('Inclusive');
                        $('#js-purchaseentry-tax-inex-select').attr('disabled',true);
                        $('#js-purchaseentry-vatableamt-input').val(data.vatableAmt.toFixed(2));
                        $('#js-purchaseentry-nonvatableamt-input').val(data.nonvatableAmt.toFixed(2));
                        // $('#js-purchaseentry-nonvatableamt-input').val(0);
                    }
                })

                if(hasVat == 0){
                    $('#grouptaxon').prop('checked',false);
                    $('#grouptaxon').attr('disabled',false);
                    $('#js-purchaseentry-discount-input').attr('disabled',false);
                    $('#js-purchaseentry-grouptax-input').val(0);
                    $('#js-purchaseentry-tax-inex-select').attr('disabled',false);
                    $('#js-purchaseentry-tax-inex-select').val('Exclusive');
                    $('#js-purchaseentry-vatableamt-input').val(data.vatableAmt.toFixed(2));
                    $('#js-purchaseentry-nonvatableamt-input').val(data.nonvatableAmt.toFixed(2));
                    // $('#js-purchaseentry-vatableamt-input').val(0);
                }

                showAlert(data.message);
            }else{
                showAlert("Something went wrong!!", 'Error');
            }
        }
    });
});

$(document).on("mousedown", "select[readonly]", function (e) {
    return false;
});

$(document).on("click",".addModalMedicine",function(){
    var selectedTr = $(this).closest('tr');
    var particular = $(selectedTr).data('fldstockid') || '';
    if (particular != '') {
        var quantity = $(selectedTr).data('fldquantity');
        var rate = $(selectedTr).data('fldrate');
        $('#js-purchaseentry-medicine-input').val(particular);
        $('#js-purchaseentry-totalqty-input').attr('data-max', quantity);
        $('#ordfldid').val($(selectedTr).data('fldid'));
        $('#js-purchaseentry-table-modal').empty().html('');
        $('#js-purchaseentry-medicine-modal').modal('hide');
        $('#js-purchaseentry-totalqty-input').val(quantity);
        $('#js-purchaseentry-rate-input').val(0);
        if(typeof rate !== 'undefined' && rate !== false){
            $('#js-purchaseentry-totalcost-input').val(parseFloat(rate) * parseFloat(quantity));
            $('#js-purchaseentry-totalqty-input').val(parseFloat(quantity));

            $('#js-purchaseentry-distunitcost-input').val(parseFloat(rate));

            $('#js-purchaseentry-totalentryqty-input').val(parseFloat(quantity));
            $('#js-purchaseentry-amtaftervat-input').val(parseFloat(rate) * parseFloat(quantity));

            $('#js-purchaseentry-netunitcost-input').val(parseFloat(rate));
            $('#js-purchaseentry-newsellprice-input').val(parseFloat(rate));



            $('#js-purchaseentry-totalcost-input').prop('readonly',true);
            $('#js-purchaseentry-rate-input').val(parseFloat(rate));
        }
        calculateUnitrate();
        changeTaxAmt();
    } else
        showAlert('Please select medicine to save.', 'fail');
});

$(document).on('change','#js-purchaseentry-route-select',function(){
    $('#js-purchaseentry-medicine-input').val("");
});

$(document).on('blur','#js-purchaseentry-discount-input',function(){
    var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
    var discinput = $(this).val();
    if(Number(discinput) < 0){
        showAlert("Discount Amount cannot be less than zero",'error');
        $('#js-purchaseentry-discount-input').val(0);
        return false;
    }
    if(Number(discinput) > Number(totalamt)){
        showAlert("Discount Amount cannot be greater than total amount",'error');
        $('#js-purchaseentry-discount-input').val(0);
        return false;
    }
    changeTaxAmt();
});

// $(document).on('blur','#js-purchaseentry-carrycostpercentage-input',function(){
//     var totalcost = $('#js-purchaseentry-totalcost-input').val() || 0;
//     var carrycost = $(this).val();
//     if(Number(carrycost) < 0){
//         showAlert("Carry Cost Amount cannot be less than zero",'error');
//         $('#js-purchaseentry-carrycostpercentage-input').val(0);
//         return false;
//     }
//     totalcost = Number(totalcost) + Number(carrycost);
//     $('#js-purchaseentry-totalcost-input').val(totalcost);

//     var amtaftervat = $('#js-purchaseentry-amtaftervat-input').val();
//     amtaftervat =  Number(amtaftervat) + Number(carrycost);
//     $('#js-purchaseentry-amtaftervat-input').val(amtaftervat);
// });

function round(value, exp) {
    if (typeof exp === 'undefined' || +exp === 0)
      return Math.round(value);

    value = +value;
    exp = +exp;

    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
      return NaN;

    // Shift
    value = value.toString().split('e');
    value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

$(document).on("blur", "#js-purchaseentry-totaltax-input", function (e) {
    changeTaxAmt();
});
$(document).on("blur", "#js-purchaseentry-ccost-input", function (e) {
    changeTaxAmt();
});

function changeTaxAmt(){
    var taxinput = $('#js-purchaseentry-totaltax-input').val() || 0;

    if(Number(taxinput) < 0){
        showAlert("Tax Amount cannot be less than zero",'error');
        $('#js-purchaseentry-totaltax-input').val(0);
        return false;
    }

    var subtotal = $('#js-purchaseentry-subtotal-input').val() || 0;
    var disctotal = $('#js-purchaseentry-discount-input').val() || 0;
    var taxtotal = $('#js-purchaseentry-totaltax-input').val() || 0;
    var cctotal = $('#js-purchaseentry-ccost-input').val() || 0;



    var checkBox = document.getElementById("grouptaxon");


    if (checkBox.checked == true){

        $('#grouptaxon').val(1);
        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');
        var subamount = $('#js-purchaseentry-subtotal-input').val();
        var vvtamot = $('#js-purchaseentry-vatableamt-input-vtt').val();
        var nonvvtamot = $('#js-purchaseentry-nonvatableamt-input-vtt').val();
        var ttcharge = (parseFloat(subamount) - parseFloat(disctotal) + parseFloat(cctotal));
        $('#js-purchaseentry-vatableamt-input').val(subamount);
        $('#js-purchaseentry-vatableamt-input-vtt').val(ttcharge);
        $('#js-purchaseentry-nonvatableamt-input-vtt').val(ttcharge);
        $('#js-purchaseentry-nonvatableamt-input').val(0);
        var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;
        amt = (13/100)*parseFloat(ttcharge);
        $('#js-purchaseentry-grouptax-input').val(amt);



    } else {
        var vvtamot = $('#js-purchaseentry-subtotal-input').val();
        var nonvvtamot = $('#js-purchaseentry-subtotal-input').val();
        $('#js-purchaseentry-nonvatableamt-input').val(nonvvtamot);
        $('#js-purchaseentry-nonvatableamt-input-vtt').val(nonvvtamot);
        if (checkBox.checked == true){
            $('#js-purchaseentry-vatableamt-input').val(vvtamot);
            $('#js-purchaseentry-vatableamt-input-vtt').val(vvtamot);

        }
        var intax = $('#js-purchaseentry-totaltax-input').val();


        if (checkBox.checked == false && intax <=0){
            $('#js-purchaseentry-vatableamt-input').val(0);
        }else{
            $('#js-purchaseentry-vatableamt-input').val(vvtamot);
            $('#js-purchaseentry-vatableamt-input-vtt').val(vvtamot);
        }
        $('#grouptaxon').val(0);
        $('#js-purchaseentry-grouptax-input').val(0);
        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');
        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');

    }
    var vatamt = $('#js-purchaseentry-grouptax-input').val() || 0;

    var ttotal =  Number(subtotal) - Number(disctotal) + Number(taxtotal) + Number(cctotal) ;
    $('#js-purchaseentry-totalamt-input').val(ttotal);

    var amt = Number(subtotal) - Number(disctotal) + Number(taxtotal) + Number(cctotal) + Number(vatamt);

    $('#js-purchaseentry-amt-input').val(amt);

    var cccccharge = $('#js-purchaseentry-ccost-input').val() || 0;
    if(cccccharge > 0){
        $('#js-purchaseentry-ccost-input').attr('readonly','readonly');
    }else{
        // $('#js-purchaseentry-ccost-input').removeAttr('readonly');
    }
    var inditax = Number($('#js-purchaseentry-totaltax-input').val()) || 0;
   // alert(inditax);
    if(inditax > 0){
        document.getElementById("grouptaxon").disabled = true;
    }else{
        document.getElementById("grouptaxon").disabled = false;
    }




}





