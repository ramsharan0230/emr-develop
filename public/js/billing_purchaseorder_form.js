$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(document).on('change', '#js-demandform-supplier-select', function() {
    $('#js-demandform-address-input').val($('#js-demandform-supplier-select option:selected').data('fldsuppaddress'));
    var quotationno = $('#js-demandform-quotation-input').val();
    if(quotationno == ""){
        $.ajax({
            url: baseUrl + '/store/demandform/getPendingSupplierDemands',
            type: "GET",
            data: {
                supplierName: $('#js-demandform-supplier-select').val(),
                quotationno: quotationno
            },
            dataType: "json",
            success: function (response) {
                var hasFldpono = false;
                var trData = '';
                var total = 0;
                if(response.length > 0){
                    $('#js-demandform-supplier-select').attr('readonly', true);
                }
                $.each(response, function(i, order) {
                    hasFldpono = (order.fldpono !== null);
                    trData += getDemandOrderTr(order, (i+1));
                    total += Number(order.fldtotal);
                });
                if (hasFldpono)
                    $('#js-demandform-item-add-div').remove();

                $('#js-demandform-order-tbody').html(trData);
                $('#js-demandform-grandtotal-input').val(total);
            }
        });
    }
});

$('#js-demandform-medicine-input').on('mousedown', function(e) {
    e.preventDefault();
    $('#js-demandform-department-select').attr('readonly', true);
    $('#js-demandform-supplier-select').attr('readonly', true);
    $('#js-demandform-department-select').mousedown(function(e) { return false; });
    $('#js-demandform-supplier-select').mousedown(function(e) { return false; });
    var route = $('#js-demandform-route-select').val() || '';
    if (route != '') {
        $.ajax({
            url: baseUrl + '/store/purchaseorder/getMedicineList',
            type: "GET",
            data: {
                route: route,
                orderBy: $('input[type="radio"][name="type"]:checked').val(),
            },
            dataType: "json",
            success: function (response) {
                $('#js-demandform-table-modal').empty().html(response.html);
                $('#js-demandform-medicine-modal').modal('show');
            }
        });
    }
});
$(document).on('click', '#js-demandform-table-modal tr', function() {
    selected_td('#js-demandform-table-modal tr', this);
});

$('#js-demandform-flditem-input-modal').keyup(function() {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-demandform-table-modal tr td:first-child'), function(i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        var trElem = $(e).closest('tr');

        if (tdText.search(searchText) >= 0)
            $(trElem).show();
        else
            $(trElem).hide();
    });
});

$('#js-demandform-add-btn-modal').click(function() {
    var particular = $('#js-demandform-table-modal tr[is_selected="yes"]').data('fldstockid') || '';
    if (particular != '') {
        $('#js-demandform-medicine-input').val(particular);
        $('#js-demandform-table-modal').empty().html('');
        $('#js-demandform-medicine-modal').modal('hide');
    } else
        showAlert('Please select medicine to save.', 'fail');
});

function deleteOrder(fldid,fldtotal) {
    if (confirm('Are you sure to delete?')) {
        $.ajax({
            url: baseUrl + "/store/demandform/delete",
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status)
                    var newtotal = $('#js-demandform-grandtotal-input').val() || 0.0;
                    if(newtotal != 0.0){
                        newtotal = parseFloat(newtotal) - parseFloat(fldtotal);
                    }
                    $('#js-demandform-grandtotal-input').val(newtotal);
                    $('#js-demandform-order-tbody tr[data-fldid="' + fldid + '"]').remove();

                showAlert(response.message, status);
            }
        });
    }
}

$('#js-demandform-fldtotal-input').focusin(function() {
    var fldquantity = Number($('#js-demandform-fldquantity-input').val());
    var fldrate = Number($('#js-demandform-fldrate-input').val());

    if (isNaN(fldquantity)){
        $('#js-demandform-fldquantity-input').focus();
        showAlert('Quantity must be number.', 'fail');
        return;
    }

    if (isNaN(fldrate)){
        $('#js-demandform-fldrate-input').focus();
        showAlert('Rate must be number.', 'fail');
        return;
    }

    $('#js-demandform-fldtotal-input').val(fldquantity*fldrate);
});

function getOrderTr(order, sn) {
    var trData = '<tr data-fldid="' + order.fldid + '">';
    trData += '<td>' + sn + '</td>';
    trData += '<td><input type="checkbox" class="js-demandform-select-class-checkbox" data-fldid="' + order.fldid + '"></td>';
    trData += '<td>' + order.fldtime_order + '</td>';
    trData += '<td>' + order.fldsuppname + '</td>';
    trData += '<td>' + order.fldstockid + '</td>';
    var maxQty = order.fldremqty;
    trData += '<td data-maxqty="'+maxQty+'" class="ordqtytd"><input type="number" oninput="this.value=(parseInt(this.value)||0)" max="'+maxQty+'" value="'+parseInt(maxQty)+'" class="ordqty" readonly></td>';
    trData += '<td class="fldrate" data-fldrate="'+order.fldrate+'">' + order.fldrate + '</td>';
    trData += '<td class="fldtotal">' + (maxQty * order.fldrate).toFixed(2) + '</td>';
    trData += '<td>' + order.flduserid_order + '</td>';
    trData += '<td><button class="btn btn-success editQty"><i class="fa fa-edit" aria-hidden="true"></i></button></td>';
    // trData += '<td><button class="btn btn-danger" onclick="deleteOrder(' + order.fldid + ')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
    trData += '</tr>';

    return trData;
}

function getDemandOrderTr(order, sn) {
    var trData = '<tr data-fldid="' + order.fldid + '">';
    trData += '<td>' + sn + '</td>';
    trData += '<td><input type="checkbox" class="js-demandform-select-class-checkbox" data-fldid="' + order.fldid + '"></td>';
    trData += '<td>' + order.fldtime_order + '</td>';
    trData += '<td>' + order.fldsuppname + '</td>';
    trData += '<td>' + order.fldstockid + '</td>';
    trData += '<td>'+order.fldquantity+'</td>';
    trData += '<td>' + order.fldrate + '</td>';
    trData += '<td>' + order.fldtotal + '</td>';
    trData += '<td>' + order.flduserid_order + '</td>';
    trData += '<td><button class="btn btn-danger" onclick="deleteOrder(' + order.fldid + ',' + order.fldtotal + ')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
    trData += '</tr>';

    return trData;
}

$('#js-demandform-add-btn').click(function() {
    var error = false;
    if(checkValidation("js-demandform-department-select") == true){
        error = true;
    }
    if(checkValidation("js-demandform-supplier-select") == true){
        error = true;
    }
    if(checkValidation("js-demandform-route-select") == true){
        error = true;
    }
    if(checkValidation("js-demandform-medicine-input") == true){
        error = true;
    }
    if(checkValidation("js-demandform-fldquantity-input") == true){
        error = true;
    }
    if(checkValidation("js-demandform-fldrate-input") == true){
        error = true;
    }
    if(checkValidation("js-demandform-fldtotal-input") == true){
        error = true;
    }

    if(!error){
        $.ajax({
            url: baseUrl + '/store/demandform/add',
            type: "POST",
            data: $('#js-demandform-form').serialize(),
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var trData = getDemandOrderTr(response.data, ($('#js-demandform-order-tbody tr').length+1));
                    $('#js-demandform-order-tbody').append(trData);

                    $('#js-demandform-route-select').val('');
                    $('#js-demandform-medicine-input').val('');
                    $('#js-demandform-fldquantity-input').val('');
                    $('#js-demandform-fldrate-input').val('');
                    $('#js-demandform-fldtotal-input').val('');

                    var newtotal = $('#js-demandform-grandtotal-input').val() || 0;
                    newtotal = Number(newtotal) + Number(response.data.fldtotal);
                    $('#js-demandform-grandtotal-input').val(newtotal);
                }
                showAlert(response.message, status);
            }
        });
    }
});

$(document).on('change', '#js-demandform-selectall-id-checkbox', function() {
    if ($(this).prop('checked'))
        $('.js-demandform-selectall-class-checkbox').prop('checked', true);
    else
        $('.js-demandform-selectall-class-checkbox').prop('checked', false);
});

$('#js-demandform-department-select').change(function() {
    $.ajax({
        url: baseUrl + '/store/demandform/getSupplierStore',
        type: "GET",
        data: {
            department: $(this).val()
        },
        dataType: "json",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldsuppname + '" data-fldsuppaddress="' + option.fldsuppaddress + '">' + option.fldsuppname + '</option>';
            });
            $('#js-demandform-supplier-select').html(optionData);
        }
    });
});

$('#js-demandform-save-btn').click(function() {
    var quotationno = $('#js-demandform-quotation-input').val();
    if(!confirm("Do you want to save?")){
        return false;
    }
    var demandids = [];
    var qty = 0;
    var itemData = $.map($('#js-demandform-order-tbody tr'), function(trElem, i) {
        if($(trElem).find('.js-demandform-select-class-checkbox').hasClass("selected")){
            demandids.push($(trElem).data('fldid'));
            if($(trElem).find('td:nth-child(6) .ordqty').length == 0){
                qty = $(trElem).find('td:nth-child(6)').html();
            }else{
                qty = $(trElem).find('td:nth-child(6) .ordqty').val();
            }
            return {
                demandid: $(trElem).data('fldid'),
                quantity: qty,
            };
        }
    });
    if(demandids.length == 0){
        showAlert("Select order first!",'error');
        return false;
    }
    if(!$('#js-demandform-supplier-select').is('[readonly]')){
        if($('#js-demandform-supplier-select').val() == ""){
            alert('Please select supplier first');
            return false;
        }
    }
    $.ajax({
        url: baseUrl + '/billing/purchaseorder/finalsave',
        type: "POST",
        data: {
            quotationno: quotationno,
            itemData: itemData,
            demandids: demandids,
            supplier: $('#js-demandform-supplier-select').val()
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                if(quotationno != ""){
                    // $('#js-demandform-quotation-input').val(response.quotationno);
                    var e = $.Event( "keydown", { which: 13 } );
                    $('#js-demandform-quotation-input').trigger(e);
                }else{
                    $('#js-demandform-supplier-select').attr('readonly', false);
                    $('#js-demandform-supplier-select').prop('selectedIndex',0);
                    $('#js-demandform-address-input').attr('readonly', false);
                    $('#js-demandform-address-input').val('');
                    $('#js-demandform-grandtotal-input').val('');
                    $('#js-demandform-selectall-id-checkbox').prop('checked','');
                    $('#js-demandform-order-tbody').html("");
                }
                $('#js-demandform-purchaseno-input').val(response.purchaseNo);
                showAlert('Purchase order saved successfully.');
                window.open(baseUrl + '/billing/purchaseorder/printBill?fldreference=' + response.purchaseNo, '_blank');
            }else{
                showAlert(response.msg,'error');
            }
        }
    });

});

$('#js-demandform-verify-btn').click(function() {
    var quotationno = $('#js-demandform-quotation-input').val() || '';
    if (quotationno !== '') {
        $.ajax({
            url: baseUrl + '/store/demandform/verify',
            type: "POST",
            data: {
                quotationno: quotationno,
            },
            dataType: "json",
            success: function (response) {
                showAlert('Demand order verified successfully.');
                // $('#js-demandform-purchaseno-input').val(response.purchaseNo);
            }
        });
    } else
        showAlert('Please provide valid quotation number.', 'fail');
});

$('#js-demandform-quotation-input').keydown(function(e) {
    var quotationno = $('#js-demandform-quotation-input').val() || '';
    if (e.which == 13 && quotationno != '') {
        $.ajax({
            url: baseUrl + '/billing/purchaseorder/getQuotationNoOrders',
            type: "GET",
            data: {
                quotationno: quotationno,
                showall: $('#js-demandform-showall-checkbox').prop('checked')
            },
            dataType: "json",
            success: function (response) {

                if(response.length > 0){
                    if(response[0].fldisgenericdemand != 1){
                        $('#js-demandform-supplier-select').val(response[0].fldsuppname).change();
                        $('#js-demandform-address-input').attr('readonly', true);
                        $('#js-demandform-supplier-select').attr('readonly', true);
                    }else{
                        $('#js-demandform-supplier-select').prop('selectedIndex',0).change();
                        $('#js-demandform-address-input').attr('readonly', false);
                        $('#js-demandform-supplier-select').attr('readonly', false);
                    }
                }

                var trData = '';
                var total = 0.0;
                var hasPurchaseNo = false;
                var purchaseNo = '';
                $.each(response, function(i, order) {
                    if (hasPurchaseNo == false && order.fldpono !== null) {
                        hasPurchaseNo = true;
                        purchaseNo = order.fldpono;
                    }

                    trData += getOrderTr(order, (i+1));
                    // total += Number(order.fldtotal);
                    total = total + parseFloat((parseFloat(order.fldremqty) * parseFloat(order.fldrate)).toFixed(2));
                });
                $('#js-demandform-medicine-div').hide();
                $('#js-demandform-order-tbody').html(trData);
                $('#js-demandform-grandtotal-input').val(total);
                $('#js-demandform-purchaseno-input').val(purchaseNo);

                if($('#js-demandform-order-tbody').find('tr').length > 0){
                    $('#js-demandform-save-btn').show();
                }else{
                    $('#js-demandform-save-btn').hide();
                }
                // if(hasPurchaseNo)
                //     $('#js-demandform-save-btn').hide();
                // else
                //     $('#js-demandform-save-btn').show();
            }
        });
    }
});

$(document).on('change','#js-demandform-selectall-id-checkbox',function(){
    if($(this).is(":checked")){
        $('.js-demandform-select-class-checkbox').prop('checked','checked');
        $.each($('.js-demandform-select-class-checkbox'), function(i, option) {
            if(!$(option).hasClass('selected')){
                $(option).addClass('selected');
            }
        });
    }else{
        $('.js-demandform-select-class-checkbox').prop('checked','');
        $.each($('.js-demandform-select-class-checkbox'), function(i, option) {
            if($(option).hasClass('selected')){
                $(option).removeClass('selected');
            }
        });
    }
});

$(document).on('change','.js-demandform-select-class-checkbox',function(){
    if($(this).is(":checked")){
        $(this).prop('checked','checked');
        if(!$(this).hasClass('selected')){
            $(this).addClass('selected');
        }
    }else{
        $(this).prop('checked','');
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }
    }
});

$(document).on('click','.editQty',function(){
    $(this).closest('tr').find('.ordqty').prop('readonly',false);
    $(this).closest('tr').find('.ordqty').focus();
});

$(document).on('keyup','.ordqty',function(){
    var max = $(this).attr('max');
    if($(this).val() > parseFloat(max)){
        $(this).val(max);
        showAlert('Quantity cannot be greater than ' + max, 'fail');
    }
    var rate = $(this).closest('tr').find('.fldrate').attr('data-fldrate');
    var qty = $(this).val();
    $(this).closest('tr').find('.fldtotal').html((parseFloat(rate) * parseFloat(qty)).toFixed(2));
    var total = 0.0;
    $.each($('.fldtotal'), function(i, option) {
        total = total + parseFloat($(option).html())
    });
    $('#js-demandform-grandtotal-input').val(total);
});

$(document).on('focusout','.ordqty',function(){
    var max = $(this).attr('max');
    if($(this).val() > parseFloat(max)){
        $(this).val(max);
        showAlert('Quantity cannot be greater than ' + max, 'fail');
    }
    var rate = $(this).closest('tr').find('.fldrate').attr('data-fldrate');
    var qty = $(this).val();
    $(this).closest('tr').find('.fldtotal').html((parseFloat(rate) * parseFloat(qty)).toFixed(2));
    var total = 0.0;
    $.each($('.fldtotal'), function(i, option) {
        total = total + parseFloat($(option).html())
    });
    $('#js-demandform-grandtotal-input').val(total);
    $(this).attr('readonly', true);
});
$('#brandForm').find("select").prop('selectedIndex',0).change();
function checkValidation(idName){
    var hasError = false;
    if($('#'+idName).val() == ""){
        hasError = true;
        $('#'+idName).closest('div').append('<span class="error text-danger">This field is required</span>');
    }else{
        if($('#'+idName).closest('div').find('.error').length != 0){
            $('#'+idName).closest('div').find('.error').remove();
        }
    }
    return hasError;
}

$(document).on("mousedown", "select[readonly]", function (e) {
    return false;
});

$(document).on('click','#js-purchaseorderform-export-btn',function(){
    if($('#js-demandform-purchaseno-input').val() != ""){
        window.open(baseUrl + '/billing/purchaseorder/printBill?fldreference=' + $('#js-demandform-purchaseno-input').val(), '_blank');
    }
});

$(document).on("click",".addModalMedicine",function(){
    var selectedTr = $(this).closest('tr');
    var particular = $(selectedTr).data('fldstockid') || '';
    if (particular != '') {
        $('#js-demandform-medicine-input').val(particular);
        $('#js-demandform-table-modal').empty().html('');
        $('#js-demandform-medicine-modal').modal('hide');
    } else
        showAlert('Please select medicine to save.', 'fail');
});

