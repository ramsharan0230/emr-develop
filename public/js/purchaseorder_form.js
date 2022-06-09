$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(document).on('change', '#js-purchaseorder-supplier-select', function() {
    var selectedOption = $('#js-purchaseorder-supplier-select option:selected')
    $('#js-purchaseorder-address-input').val($(selectedOption).data('fldsuppaddress'));

    $.ajax({
        url: baseUrl + "/store/purchaseorder/getRefrence",
        type: "GET",
        data: {
            fldsuppname: $(selectedOption).val(),
        },
        dataType: "json",
        success: function (response) {
            var optionData = '';
            optionData += '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldreference + '">' + option.fldreference + '</option>';
            });
            $('#js-purchaseorder-refrence-select').empty().html(optionData);
        }
    });
});

$('#js-purchaseorder-flditem-input-modal').keyup(function() {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-purchaseorder-table-modal tr td:first-child'), function(i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        var trElem = $(e).closest('tr');

        if (tdText.search(searchText) >= 0)
            $(trElem).show();
        else
            $(trElem).hide();
    });
});

// $(document).on('change', '#js-purchaseorder-refrence-select', function() {
//     $.ajax({
//         url: baseUrl + "/store/purchaseorder/getLocation",
//         type: "GET",
//         data: {
//             fldreference: $(this).val(),
//         },
//         dataType: "json",
//         success: function (response) {
//             var optionData = '';
//             optionData += '<option value="">-- Select --</option>';
//             $.each(response, function(i, option) {
//                 optionData += '<option value="' + option.fldlocat + '">' + option.fldlocat + '</option>';
//             });
//             $('#js-purchaseorder-location-select').empty().html(optionData);

//         }
//     });
// });

$('#js-purchaseorder-medicine-input').on('mousedown', function(e) {
    e.preventDefault();
    var route = $('#js-purchaseorder-route-select').val() || '';
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
                // var trData = '';
                // $.each(response, function(i, medicine) {
                //     var dataAttributes =  "data-fldstockid='" + medicine.col + "'";
                //     trData += '<tr ' + dataAttributes + '>';
                //     trData += '<td>' + medicine.col + '</td>';
                //     trData += '</tr>';
                // });
                $('#js-purchaseorder-table-modal').empty().html(response.html);
                $('#js-purchaseorder-medicine-modal').modal('show');
            }
        });
    }
});
$(document).on('click', '#js-purchaseorder-table-modal tr', function() {
    selected_td('#js-purchaseorder-table-modal tr', this);
});

$('#js-purchaseorder-add-btn-modal').click(function() {
    var selectedTr = $('#js-purchaseorder-table-modal tr[is_selected="yes"]');
    var particular = $(selectedTr).data('fldstockid') || '';
    if (particular != '') {
        $.ajax({
            url: baseUrl + '/store/purchaseorder/getMedicineDetail',
            type: "GET",
            data: {
                fldstockid: particular
            },
            dataType: "json",
            success: function (response) {
                $('#js-purchaseorder-stock-input').val(response.quantity);
                $('#js-purchaseorder-rate-input').val(response.rate);
            }
        });
        $('#js-purchaseorder-medicine-input').val(particular);
        $('#js-purchaseorder-table-modal').empty().html('');
        $('#js-purchaseorder-medicine-modal').modal('hide');
    } else
        showAlert('Please select medicine to save.', 'fail');
});

function deleteOrder(fldid) {
    if (confirm('Are you sure to delete?')) {
        $.ajax({
            url: baseUrl + "/store/purchaseorder/delete",
            type: "POST",
            data: {
                fldid: fldid
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status)
                    $('#js-purchaseorder-order-tbody tr[data-fldid="' + fldid + '"]').remove();

                showAlert(response.message, status);
            }
        });
    }
}

$('#js-purchaseorder-add-btn').click(function() {
    var fldsuppname = $('#js-purchaseorder-supplier-select').val();
    var fldroute = $('#js-purchaseorder-route-select').val();
    var flditemname = $('#js-purchaseorder-medicine-input').val();
    var fldqty = $('#js-purchaseorder-qty-input').val();
    var fldrate = $('#js-purchaseorder-rate-input').val();
    var fldlocat = $('#js-purchaseorder-location-select').val();
    var fldactualorddt = $('#js-purchaseorder-date-input').val();
    var flddelvdate = $('#js-purchaseorder-deliverydate-input').val();
    $('#js-storecodding-fldstockid-input').val('');
    $('#js-storecodding-fldcode-input').val('');
    if (fldqty != '' || fldqty != '0') {
        $.ajax({
            url: baseUrl + "/store/purchaseorder/saveOrder",
            type: "POST",
            data: {
                fldsuppname: fldsuppname,
                fldroute: fldroute,
                flditemname: flditemname,
                fldqty: fldqty,
                fldrate: fldrate,
                fldlocat: fldlocat,
                fldactualorddt: fldactualorddt,
                flddelvdate: flddelvdate,
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status) {
                    var order = response.data;
                    var trData = '<tr data-fldid="' + order.fldid + '">';
                    trData += '<td>' + ($('#js-purchaseorder-order-tbody tr').length+1) + '</td>';
                    trData += '<td>' + order.fldid + '</td>';
                    trData += '<td>' + order.fldsuppname + '</td>';
                    trData += '<td>' + order.fldroute + '</td>';
                    trData += '<td>' + order.flditemname + '</td>';
                    trData += '<td>' + order.fldqty + '</td>';
                    trData += '<td>' + order.fldrate + '</td>';
                    trData += '<td>' + order.fldamt + '</td>';
                    trData += '<td>' + order.flduserid + '</td>';
                    trData += '<td>' + order.fldorddate + '</td>';
                    trData += '<td>' + order.fldcomp + '</td>';
                    trData += '<td><button class="btn btn-danger" onclick="deleteOrder(' + order.fldid + ')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    trData += '</tr>';
                    $('#js-purchaseorder-order-tbody').append(trData);

                    $('#js-purchaseorder-supplier-select').val('');
                    $('#js-purchaseorder-route-select').val('');
                    $('#js-purchaseorder-medicine-input').val('');
                    $('#js-purchaseorder-qty-input').val('');
                    $('#js-purchaseorder-rate-input').val('');
                    $('#js-purchaseorder-location-select').val('');
                    $('#js-purchaseorder-date-input').val('');
                    $('#js-purchaseorder-deliverydate-input').val('');

                    var newtotal = Number($('#js-purchaseorder-grandtotal-input').val()) + Number(order.fldamt);
                    $('#js-purchaseorder-grandtotal-input').val(newtotal);
                }
                showAlert(response.message, status);
            }
        });
    }
});

$('#js-purchaseorder-finalupdate-btn').click(function() {
    var fldreference = $('#js-purchaseorder-refrence-select').val() || '';
    if (fldreference != '') {
        var url = baseUrl + '/store/purchaseorder/finalupdate?fldreference=' + fldreference;
        window.open(url, '_blank');
    }
});


$('#js-purchaseorder-add-item-btn').click(function() {
    var tr_data = '';
    $.each($('#js-purchaseorder-location-select option'), function(i, e) {
        var value = $(e).val();
        if (value !== '')
            tr_data += '<tr data-flditem="' + value + '"><td>' + value + '</td></tr>';
    });

    $('#js-purchaseorder-table-item-modal').html(tr_data);
    $('#js-purchaseorder-add-item-modal').modal('show');
});

$('#js-purchaseorder-flditem-input-item-modal').keyup(function() {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-purchaseorder-table-item-modal tr td:first-child'), function(i, e) {
        var tdText = $(e).text().trim().toUpperCase();

        if (tdText.search(searchText) >= 0)
            $(e).closest('tr').show();
        else
            $(e).closest('tr').hide();
    });
});

$(document).on('click', '#js-purchaseorder-table-item-modal tr', function() {
    selected_td('#js-purchaseorder-table-item-modal tr', this);
});

$('#js-purchaseorder-add-btn-item-modal').click(function() {
    var data = {
        flditem: $('#js-purchaseorder-flditem-input-item-modal').val(),
        type: $('#js-purchaseorder-type-input-item-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/store/purchaseorder/addVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;

                var trData = '<tr data-flditem="' + val.flditem + '"><td>' + val.flditem + '</td></tr>';
                $('#js-purchaseorder-table-item-modal').append(trData);
                $('#js-purchaseorder-flditem-input-item-modal').val('');
                $('#js-purchaseorder-table-item-modal tr').show();
            }
            showAlert(response.message);
        }
    });
});

$('#js-purchaseorder-delete-btn-item-modal').click(function() {
    var data = {
        flditem: $('#js-purchaseorder-table-item-modal tr[is_selected="yes"]').data('flditem'),
        type: $('#js-purchaseorder-type-input-item-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/store/purchaseorder/deleteVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-purchaseorder-table-item-modal tr[is_selected="yes"]').remove();

                showAlert(response.message);
        }
    });
});

$('#js-purchaseorder-add-item-modal').on('hidden.bs.modal', function () {
    var optionData = '';
    optionData += '<option value="">-- Select --</option>';
    $.each($('#js-purchaseorder-table-item-modal tr'), function(i, option) {
        var text = $(option).find('td:first-child').text().trim();
        optionData += '<option value="' + text + '">' + text + '</option>';
    });
    $('#js-purchaseorder-location-select').empty().html(optionData);
    $('#js-purchaseorder-table-item-modal').html('');
});
