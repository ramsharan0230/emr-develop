$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$('#js-purchase-fixed-textfile-input').change(function() {
    var data = new FormData();
    if ($(this)[0].files !== undefined && $(this)[0].files.length > 0) {
        var image = $(this)[0].files[0];
        data.append('fldtext', image);
    }

    $.ajax({
        url: baseUrl + '/inpatient/prog/readtextfile',
        type: "POST",
        enctype: 'multipart/form-data',
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (response) {
            var optionData = '<select id="js-prog-planning-drop-input" name="fldcomp" class="select-01 form-input"><option>-- Select --<option>';
            $.each(response, function(i, val) {
                optionData += '<option>' + val + '<option>';
            });
            optionData += "</select>"

            $('#js-purchase-fixed-input-parent').html(optionData);
        }
    });
});

$('#js-purchase-fixed-sync-btn').click(function() {

});

$('#js-purchase-fixed-items-select').change(function() {
	var selectedOption = $('#js-purchase-fixed-items-select option:selected');
	$('#js-purchase-fixed-group-input').val($(selectedOption).data('fldgroup'));
	$('#js-purchase-fixed-ledger-input').val($(selectedOption).data('fldledger'));
});

$('#js-purchase-fixed-add-item-btn').click(function() {
	var trData = '';
	$.each($('#js-purchase-fixed-items-select option'), function(i, e) {
		var value = $(e).val();
		if (value !== '')
			trData += '<tr data-fldid="' + $(e).data('fldid') + '"><td>' + i + '</td><td>' + value + '</td><td>' + $(e).data('fldledger') + '</td></tr>';
	});

	$('#js-purchase-fixed-table-modal').html(trData);
	$('#js-purchase-fixed-add-item-modal').modal('show');
});

$('#js-purchase-fixed-add-btn-modal').click(function() {
	$.ajax({
		url: baseUrl + '/purchase/fixed-asset/addItem',
		type: "POST",
		data: $('#js-purchase-fixed-modal-form').serialize(),
		dataType: "json",
		success: function(response) {
            if (response.status) {
    			var trData = '<tr data-fldid="' + response.data.fldid + '">';
    			trData += '<td>' + ($('#js-purchase-fixed-table-modal tr').length+1) + '</td>';
    			trData += '<td>' + response.data.flditemname + '</td>';
    			trData += '<td>' + response.data.fldledger + '</td></tr>';
                $('#js-purchase-fixed-table-modal').append(trData);
            }
            showAlert(response.message);
		}
	});
});

$('#js-purchase-fixed-table-modal tr').click(function() {
    selected_td('#js-purchase-fixed-table-modal tr', this);
});

$('#js-purchase-fixed-delete-btn-modal').click(function() {
	$.ajax({
		url: baseUrl + '/purchase/fixed-asset/deleteItem',
		type: "POST",
		data: { fldid: $('#js-purchase-fixed-table-modal tr[is_selected="yes"]').data('fldid') },
		dataType: "json",
		success: function(response) {
            if (response.status)
                $('#js-purchase-fixed-table-modal tr[is_selected="yes"]').remove();

                showAlert(response.message);
		}
	});
});

$('#js-purchase-fixed-add-item-modal').on('hidden.bs.modal', function () {
	$('#js-purchase-fixed-table-modal').html('');
    $('#js-purchase-fixed-modal-form')[0].reset();
	$.ajax({
        url: baseUrl + '/purchase/fixed-asset/getItems',
        type: "GET",
        success: function (response) {
        	var items = '<option value="">-- Select --</option>';
        	$.each(response, function(i, e) {
        		items += '<option data-fldid="' + e.fldid + '" data-fldgroup="' + e.fldgroup + '" data-fldledger="' + e.fldledger + '" value="' + e.flditemname + '">' + e.flditemname + '</option>'
        	});
        	$('#js-purchase-fixed-items-select').html(items);
        }
    });
});

/*
js-purchase-fixed-assetsentry-save-btn
js-purchase-fixed-assetsentry-update-btn
*/

$('#js-purchase-fixed-total-input').focusin(function() {
    var qty = $('#js-purchase-fixed-qty-input').val() || '0';
    var rate = $('#js-purchase-fixed-rate-input').val() || '0';

    $('#js-purchase-fixed-total-input').val(qty*rate);
});

$(document).on('click', '#js-purchase-fixed-assetsentry-tbody tr', function() {
    selected_td('#js-purchase-fixed-assetsentry-tbody tr', this);
    $.ajax({
        url: baseUrl + '/purchase/fixed-asset/getAssetsEntry',
        type: "GET",
        data: {fldid: $(this).data('fldid')},
        success: function(response) {
            $('#js-purchase-fixed-items-select option').attr('selected', false);
            $('#js-purchase-fixed-items-select option[value="' + response.flditemname + '"]').attr('selected', true);
            $('#js-purchase-fixed-group-input').val(response.fldgroup);
            $('#js-purchase-fixed-ledger-input').val(response.fldledger);
            $('#js-purchase-fixed-specification-input').val(response.fldspecs);
            $('#js-purchase-fixed-remarks-input').val(response.fldcomment);
            $('#js-purchase-fixed-manufacturer-input').val(response.fldmanufacturer);
            $('#js-purchase-fixed-supplier-input option').attr('selected', false);
            $('#js-purchase-fixed-supplier-input option[value="' + response.fldsuppname + '"]').attr('selected', true);
            $('#js-purchase-fixed-location-input').val(response.fldcomp);
            $('#js-purchase-fixed-purchase-input').val(response.fldpurdate);
            $('#js-purchase-fixed-repair-input').val(response.fldrepairdate);
            $('#js-purchase-fixed-discount-input').val(response.flddiscamt);
            $('#js-purchase-fixed-tax-input').val(response.fldtaxamt);
            $('#js-purchase-fixed-code-input').val(response.fldcode);
            $('#js-purchase-fixed-model-input').val(response.fldmodel);
            $('#js-purchase-fixed-serial-input').val(response.fldserial);
            $('#js-purchase-fixed-condition-input').val(response.fldcondition);
            $('#js-purchase-fixed-qty-input').val(response.fldqty);
            $('#js-purchase-fixed-unit-input').val(response.fldunit);
            $('#js-purchase-fixed-rate-input').val(response.flditemrate);
            $('#js-purchase-fixed-total-input').val(response.fldditemamt);
        }
    });
});

function saveUpdateAssetsentry(type, data) {
    var url = baseUrl + '/purchase/fixed-asset/saveAssetsEntry';
    if (type == 'update')
        url = baseUrl + '/purchase/fixed-asset/updateAssetsEntry';
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json",
        success: function(response) {
            if (response.status) {
                $('#js-purchase-fixed-assetsentry-form')[0].reset();

                var trData = '<td>' + response.data.flditemname + '</td>';
                trData += '<td>' + response.data.fldmanufacturer + '</td>';
                trData += '<td>' + response.data.fldledger + '</td>';
                trData += '<td>' + response.data.fldmodel + '</td>';
                trData += '<td>' + response.data.fldserial + '</td>';
                trData += '<td>' + response.data.fldqty + '</td>';
                trData += '<td>' + response.data.fldditemamt + '</td>';
                trData += '<td>' + response.data.fldcomp + '</td>';

                if (type == 'save') {
                    var prefix = '<tr data-fldid="' + response.data.fldid + '">';
                    prefix += '<td>' + ($('#js-purchase-fixed-assetsentry-tbody tr').length+1) + '</td>';

                    trData = prefix + trData + '</tr>';
                    $('#js-purchase-fixed-assetsentry-tbody').append(trData);
                } else {
                    trData = '<td>' + ($('#js-purchase-fixed-assetsentry-tbody tr[is_selected="yes"] td:first-child').text().trim()) + '</td>' + prefix + trData;
                    $('#js-purchase-fixed-assetsentry-tbody tr[is_selected="yes"]').html(trData);
                }
            }
            showAlert(response.message);
        }
    });
}

$('#js-purchase-fixed-assetsentry-save-btn').click(function() {
    saveUpdateAssetsentry('save', $('#js-purchase-fixed-assetsentry-form').serialize());
});

$('#js-purchase-fixed-assetsentry-update-btn').click(function() {
    var fldid = $('#js-purchase-fixed-assetsentry-tbody tr[is_selected="yes"]').data('fldid') || '';
    if (fldid !== '') {
        var data = $('#js-purchase-fixed-assetsentry-form').serialize();
        data += '&fldid=' + fldid;
        saveUpdateAssetsentry('update', $('#js-purchase-fixed-assetsentry-form').serialize());
    } else
        alert('Select data to update.')
});

$('#js-purchase-fixed-sync-btn').click(function() {
    $.ajax({
        url: baseUrl + '/purchase/fixed-asset/getAssetsEntry',
        type: "GET",
        data: {flditemname: $('#js-purchase-fixed-items-select').val()},
        success: function(response) {
            var trData = '';
            $.each(response, function(i, data) {
                trData += '<tr data-fldid="' + data.fldid + '">';
                trData += '<td>' + ($('#js-purchase-fixed-assetsentry-tbody tr').length+1) + '</td>';
                trData += '<td>' + data.flditemname + '</td>';
                trData += '<td>' + data.fldmanufacturer + '</td>';
                trData += '<td>' + data.fldledger + '</td>';
                trData += '<td>' + data.fldmodel + '</td>';
                trData += '<td>' + data.fldserial + '</td>';
                trData += '<td>' + data.fldqty + '</td>';
                trData += '<td>' + data.fldditemamt + '</td>';
                trData += '<td>' + data.fldcomp + '</td></tr>';
            });

            $('#js-purchase-fixed-assetsentry-tbody').html(trData);
        }
    });
});
