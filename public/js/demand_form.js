$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(document).on('change', '#js-demandform-supplier-select', function() {
    if($(this).val() != ""){
        $('#js-demandform-address-input').val($('#js-demandform-supplier-select option:selected').data('fldsuppaddress'));
        var quotationno = $('#js-demandform-quotation-input').val() || '';
        $.ajax({
            url: baseUrl + '/store/demandform/getSupplierDemands',
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
                $.each(response, function(i, order) {
                    hasFldpono = (order.fldpono !== null);
                    trData += getOrderTr(order, (i+1));
                    total += Number(order.fldtotal);
                });
                if(response.length > 0){
                    $('#js-demandform-department-select').attr('readonly', true);
                    $('#js-demandform-supplier-select').attr('readonly', true);
                }
                if (hasFldpono)
                    $('#js-demandform-item-add-div').remove();

                $('#js-demandform-order-tbody').html(trData);
                $('#js-demandform-grandtotal-input').val(total);
            }
        });
    }
});

function getMedicineModal(route){
    $.ajax({
        url: baseUrl + '/store/purchaseorder/getMedicineList',
        type: "GET",
        data: {
            route: route,
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
            $('#js-demandform-table-modal').empty().html(response.html);
            $('#js-demandform-medicine-modal').modal('show');
        }
    });
}

$('#js-demandform-medicine-input').on('mousedown', function(e) {
    e.preventDefault();

    var supplier = $('#js-demandform-supplier-select').val() || '';
    if ($('#js-demandform-make-generic-demand').prop("checked") == true) {
        $('#js-demandform-department-select').attr('readonly', true);
        $('#js-demandform-supplier-select').attr('readonly', true);
        $('#js-demandform-quotation-input').attr('readonly', true);
        $('#js-demandform-department-select').mousedown(function(e) { return false; });
        $('#js-demandform-supplier-select').mousedown(function(e) { return false; });
        var route = $('#js-demandform-route-select').val() || '';
        getMedicineModal(route);
        return true;
    }

    if(supplier == ""){
        alert('Please select supplier/department first!');
        return false;
    }

    $('#js-demandform-department-select').attr('readonly', true);
    $('#js-demandform-supplier-select').attr('readonly', true);
    $('#js-demandform-quotation-input').attr('readonly', true);
    $('#js-demandform-department-select').mousedown(function(e) { return false; });
    $('#js-demandform-supplier-select').mousedown(function(e) { return false; });
    var route = $('#js-demandform-route-select').val() || '';
    var department = $('#js-demandform-department-select').val() || 'Outside';
    if (route != '') {
        $.ajax({
            url: baseUrl + '/store/demandform/getMedicineList',
            type: "GET",
            data: {
                route: route,
                orderBy: $('input[type="radio"][name="type"]:checked').val(),
                department: department,
                supplier: supplier,
                fldcomp: $('#js-demandform-supplier-select option:selected').data('fldcomp') || '',
                isStock: $('#js-demandform-isstock-checkbox').prop('checked'),
            },
            dataType: "json",
            success: function (response) {
                var trData = '';
                $.each(response, function(i, medicine) {
                    var dataAttributes =  "data-fldstockid='" + medicine.col + "'";
                    if (department == 'Inside') {
                        dataAttributes +=  " data-fldqty='" + medicine.fldqty + "'";
                        dataAttributes +=  " data-fldsellpr='" + medicine.fldsellpr + "'";
                        dataAttributes +=  " data-fldbatch='" + medicine.fldbatch + "'";
                    }

                    trData += '<tr ' + dataAttributes + '>';
                    trData += '<td>' + medicine.col + '</td>';
                    trData += '<td width="10%" class="text-center"><button class="btn btn-primary addModalMedicine">Add</button></td>';
                    trData += '</tr>';
                });
                $('#js-demandform-table-modal').empty().html(trData);
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

function selectMedicine(currentTr) {
    var particular = $(currentTr).data('fldstockid') || '';
    if (particular != '') {
        $('#js-demandform-medicine-input').val(particular);
        $('#js-demandform-table-modal').empty().html('');
        $('#js-demandform-medicine-modal').modal('hide');

        var department = $('#js-demandform-department-select').val() || 'Outside';
        if (department == 'Inside') {
            var fldqty = $(currentTr).data('fldqty') || 0;
            var fldsellpr = $(currentTr).data('fldsellpr') || 0;
            var fldbatch = $(currentTr).data('fldbatch') || '';

            $('#js-demandform-fldstock-input').val(fldqty);
            $('#js-demandform-fldrate-input').val(fldsellpr);
            $('#js-demandform-fldbatch-input').val(fldbatch);
        }
    } else
        showAlert('Please select medicine to save.', 'fail');
}

$('#js-demandform-add-btn-modal').click(function() {
    selectMedicine($('#js-demandform-table-modal tr[is_selected="yes"]'));
});

$(document).on("click", ".addModalMedicine",function(){
    selectMedicine($(this).closest('tr'));
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
                if (response.status){
                    var newtotal = $('#js-demandform-grandtotal-input').val() || 0.0;
                    if(newtotal != 0.0){
                        newtotal = parseFloat(newtotal) - parseFloat(fldtotal);
                    }
                    $('#js-demandform-grandtotal-input').val(newtotal);
                    $('#js-demandform-order-tbody tr[data-fldid="' + fldid + '"]').remove();
                }
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
    // if (can_verify)
        trData += '<td><input type="checkbox" class="js-demandform-select-class-checkbox"></td>';
    trData += '<td>' + AD2BS(order.fldtime_order.split(' ')[0]) + ' ' + order.fldtime_order.split(' ')[1] + '</td>';
    trData += '<td>' + order.fldsuppname + '</td>';
    trData += '<td>' + order.fldstockid + '</td>';
    trData += '<td>' + order.fldquantity + '</td>';
    trData += '<td>' + order.fldrate + '</td>';
    trData += '<td>' + order.fldtotal + '</td>';
    trData += '<td>' + order.flduserid_order + '</td>';
    if(order.fldsave_order == 0){
        trData += '<td><button class="btn btn-danger" onclick="deleteOrder(' + order.fldid + ',' + order.fldtotal + ')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
    }else{
        trData += '<td></td>';
    }
    trData += '</tr>';

    return trData;
}

$('#js-demandform-add-btn').click(function() {
    var fldquantity = Number($("#js-demandform-fldquantity-input").val() || 0);
    if (fldquantity == 0) {
        showAlert('Quantity cannot be zero.', 'fail');
        return false;
    }

    var departmentType = $('#js-demandform-department-select').val() || 'Outside';
    if (departmentType == 'Inside') {
        var fldstock = Number($("#js-demandform-fldstock-input").val() || 0);
        if ((fldquantity > fldstock) && !$('#js-demandform-isstock-checkbox').prop('checked')) {
            showAlert('Quantity cannot be greater than stock.', 'fail');
            return false;
        }
    }
    var selectedDepart = $('#js-demandform-supplier-select option:selected');
    var postData = $('#js-demandform-form').serialize();
    postData += '&fldordbranch=' + ($(selectedDepart).data('fldsuppaddress') || '') + '&fldordcomp=' + ($(selectedDepart).data('fldcomp') || '');

    var error = false;
    if(checkValidation("js-demandform-department-select") == true){
        error = true;
    }
    if ($('#js-demandform-make-generic-demand').prop("checked") == false) {
        if(checkValidation("js-demandform-supplier-select") == true){
            error = true;
        }
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
            data: postData,
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var trData = getOrderTr(response.data, ($('#js-demandform-order-tbody tr').length+1));
                    $('#js-demandform-order-tbody').append(trData);

                    $('#js-demandform-route-select').val('');
                    $('#js-demandform-medicine-input').val('');
                    $('#js-demandform-fldquantity-input').val('');
                    $('#js-demandform-fldrate-input').val('');
                    $('#js-demandform-fldtotal-input').val('');
                    $('#js-demandform-fldstock-input').val('');

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
    $('.js-demandform-selectall-id-checkbox').prop('checked', $(this).prop('checked'));
});

$(document).on('click', '#js-demandform-order-tbody tr td:nth-child(6)', function() {
    if($('#isQuotationFiltered').val() != "1"){
        $('#js-demandform-fldid-input-modal').val($(this).closest('tr').data('fldid'));
        $('#js-demandform-fldquantity-input-modal').val($(this).text().trim());

        $('#js-demandform-changequantity-modal').modal('show');
    }
});

$(document).on('click', '#js-demandform-order-tbody tr td:nth-child(7)', function() {
    if($('#isQuotationFiltered').val() != "1"){
        $('#js-demandform-fldid-input-modal').val($(this).closest('tr').data('fldid'));
        $('#js-demandform-fldrate-input-modal').val($(this).text().trim());

        $('#js-demandform-changerate-modal').modal('show');
    }
});

$('#js-demandform-updatequantity-btn').click(function() {
    var fldquantity = $('#js-demandform-fldquantity-input-modal').val();
    var fldid = $('#js-demandform-fldid-input-modal').val();

    if (!isNaN(fldquantity)) {
        $.ajax({
            url: baseUrl + '/store/demandform/updateQuantity',
            type: "POST",
            data: {
                fldid: fldid,
                fldquantity: fldquantity,
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var trElem = $('#js-demandform-order-tbody tr[data-fldid="' + fldid + '"]');
                    $('#js-demandform-changequantity-modal').modal('hide');
                    $(trElem).find('td:nth-child(6)').text(fldquantity);

                    var rate = $(trElem).find('td:nth-child(7)').text().trim();
                    var total = fldquantity*rate;
                    $(trElem).find('td:nth-child(8)').text(total);

                    var newtotal = 0;
                    $.each($('#js-demandform-order-tbody td:nth-child(8)'), function(i, e) {
                        newtotal += Number($(e).text().trim());
                    })
                    $('#js-demandform-grandtotal-input').val(newtotal);

                    $('#js-demandform-fldid-input-modal').val('');
                    $('#js-demandform-fldquantity-input-modal').val('');
                }
                showAlert(response.message, status);
            }
        });
    } else
        showAlert('Please enter valid Quantity.', 'fail');
});

$('#js-demandform-updaterate-btn').click(function() {
    var fldrate = $('#js-demandform-fldrate-input-modal').val();
    var fldid = $('#js-demandform-fldid-input-modal').val();

    if (!isNaN(fldrate)) {
        $.ajax({
            url: baseUrl + '/store/demandform/updateRate',
            type: "POST",
            data: {
                fldid: fldid,
                fldrate: fldrate,
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var trElem = $('#js-demandform-order-tbody tr[data-fldid="' + fldid + '"]');
                    $('#js-demandform-changerate-modal').modal('hide');
                    $(trElem).find('td:nth-child(7)').text(fldrate);

                    var quantity = $(trElem).find('td:nth-child(6)').text().trim();
                    var total = fldrate*quantity;
                    $(trElem).find('td:nth-child(8)').text(total);

                    var newtotal = 0;
                    $.each($('#js-demandform-order-tbody td:nth-child(8)'), function(i, e) {
                        newtotal += Number($(e).text().trim());
                    })
                    $('#js-demandform-grandtotal-input').val(newtotal);

                    $('#js-demandform-fldid-input-modal').val('');
                    $('#js-demandform-fldrate-input-modal').val('');
                }
                showAlert(response.message, status);
            }
        });
    } else
        showAlert('Please enter valid Rate.', 'fail');
});

$('#js-demandform-department-select').change(function() {
    var department = $(this).val();
    if (department == 'Inside') {
        $('#js-demandform-fldstock-input').closest('div').show();
        $('#js-demandform-fldrate-input').attr('readonly', true);
    } else {
        $('#js-demandform-fldstock-input').closest('div').hide();
        $('#js-demandform-fldrate-input').attr('readonly', false);
    }
    $.ajax({
        url: baseUrl + '/store/demandform/getSupplierStore',
        type: "GET",
        data: {
            department: department
        },
        dataType: "json",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                if (department == 'Outside') {
                    var dataAttributes = ' data-fldsuppaddress="' + option.fldsuppaddress + '"';
                    optionData += '<option value="' + option.fldsuppname + '" ' + dataAttributes + '>' + option.fldsuppname + '</option>';
                } else {
                    var dataAttributes = ' data-fldsuppaddress="' + (option.branch_data ? option.branch_data.name : '') + '"';
                    dataAttributes += ' data-fldcomp="' + option.fldcomp + '"';
                    optionData += '<option value="' + option.name + '" ' + dataAttributes + '>' + option.name + '(' + (option.branch_data ? option.branch_data.name : '') + ')' + '</option>';
                }
            });
            $('#js-demandform-supplier-select').html(optionData);
        }
    });
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

$('#js-demandform-save-btn').click(function() {
    if(!$(this).hasClass('disableSave')){
        if(!confirm("Do you want to save?")){
            return false;
        }
        var demandids = [];
        var itemData = $.map($('#js-demandform-order-tbody tr'), function(trElem, i) {
            if($(trElem).find('.js-demandform-select-class-checkbox').hasClass("selected")){
                demandids.push($(trElem).data('fldid'));
            }
        });
        if(!(demandids.length > 0)){
            alert("Please select demand first!");
            return false;
        }
        var quotationno = $('#js-demandform-quotation-input').val() || '';
        $.ajax({
            url: baseUrl + '/store/demandform/finalsave',
            type: "POST",
            data: {
                quotationno: quotationno,
                demandids: demandids,
                fldpurtype: $('#js-demandform-department-select').val()
            },
            dataType: "json",
            success: function (response) {
                if(response.status){
                    $('#js-demandform-quotation-input').attr('readonly', true);
                    if (quotationno == '')
                        $('#js-demandform-quotation-input').val(response.fldquotationno);
                    $.each($('#js-demandform-order-tbody tr'), function(i, option) {
                        $(option).find(' td:eq(9)').text("")
                    });
                    $('#js-demandform-route-select').attr('readonly',true);
                    $('#js-demandform-medicine-input').attr('readonly',true);
                    $('#js-demandform-fldquantity-input').attr('readonly',true);
                    $('#js-demandform-fldrate-input').attr('readonly',true);
                    $('#js-demandform-fldtotal-input').attr('readonly',true);
                    if(!$("#js-demandform-save-btn").hasClass('disableSave')){
                        $("#js-demandform-save-btn").addClass('disableSave');
                    }
                    $('#js-demandform-export-btn').trigger('click');
                    showAlert('Demand order saved successfully.');
                }else{
                    showAlert(response.msg,'error');
                }
            }
        });
    }else{
        return false;
    }
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
                $('#js-demandform-export-btn').trigger('click');
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
            url: baseUrl + '/store/demandform/getQuotationNoOrders',
            type: "GET",
            data: {
                quotationno: quotationno,
                showall: $('#js-demandform-showall-checkbox').prop('checked')
            },
            dataType: "json",
            success: function (response) {

                if(response.length > 0){
                    $('#js-demandform-department-select').val(response[0].fldpurtype);
                    $('#js-demandform-supplier-select').val(response[0].fldsuppname);
                    $('#js-demandform-department-select').attr('readonly', true);
                    $('#js-demandform-supplier-select').attr('readonly', true);
                    if(!$("#js-demandform-save-btn").hasClass('disableSave')){
                        $("#js-demandform-save-btn").addClass('disableSave');
                        $('#js-demandform-route-select').attr('readonly',true);
                        $('#js-demandform-medicine-input').attr('readonly',true);
                        $('#js-demandform-fldquantity-input').attr('readonly',true);
                        $('#js-demandform-fldrate-input').attr('readonly',true);
                        $('#js-demandform-fldtotal-input').attr('readonly',true);
                    }
                    $('#isQuotationFiltered').val("1");
                }else{
                    if($("#js-demandform-save-btn").hasClass('disableSave')){
                        $("#js-demandform-save-btn").removeClass('disableSave');
                    }
                    $('#isQuotationFiltered').val("0");
                }

                var hasFldpono = false;
                var trData = '';
                var total = 0;
                $.each(response, function(i, order) {
                    hasFldpono = (order.fldpono !== null);
                    trData += getOrderTr(order, (i+1));
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

$('#js-demandform-export-btn').click(function() {
    var url = baseUrl + '/store/demandform/report?fldquotationno=' + $('#js-demandform-quotation-input').val();
    window.open(url, '_blank');
});

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

$(document).on('change','#js-demandform-make-generic-demand',function(){
    $('#js-demandform-order-tbody').html("");
    if($(this).prop("checked") == true){
        $('#js-demandform-department-select').prop('selectedIndex',0).change();
        $('#js-demandform-supplier-select').prop('selectedIndex',0).change();
        $('#js-demandform-department-select').attr('readonly',true);
        $('#js-demandform-supplier-select').attr('readonly',true);
        $.ajax({
            url: baseUrl + '/store/demandform/getUnsavedGeneralDemands',
            type: "GET",
            dataType: "json",
            success: function (response) {
                var total = 0;
                $.each(response.data, function(i, order) {
                    var trData = getOrderTr(order, i+1);
                    $('#js-demandform-order-tbody').append(trData);
                    total += Number(order.fldtotal);
                });
                $('#js-demandform-grandtotal-input').val(total);
            }
        });
    }else{
        $('#js-demandform-department-select').attr('readonly',false);
        $('#js-demandform-supplier-select').attr('readonly',false);
    }
});
