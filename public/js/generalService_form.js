
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$(".unauthorised").click(function () {
    permit_user = $(this).attr('permit_user');
    showAlert('Authorization with  '+permit_user);
});


$(document).on('click', '#js-generalService-table-modal tr', function () {
    selected_td('#js-generalService-table-modal tr', this);
});
$('.js-generalService-add-item').click(function() {
	var tr_data = '';
	$.each($(this).closest('.form-group').find('select.table-data option'), function(i, e) {
		var value = $(e).val();
		if (value !== '')
			tr_data += '<tr data-flditem="' + value + '"><td>' + value + '</td></tr>';
	});

	$('#js-generalService-type-input-modal').val($(this).data('variable'))
	$('#js-generalService-table-modal').html(tr_data);
	$('#js-generalService-add-item-modal').modal('show');
});

$('#js-generalService-add-btn-modal').click(function() {
    var data = {
        flditem: $('#js-generalService-flditem-input-modal').val(),
        type: $('#js-generalService-type-input-modal').val(),
        category: $('#js-generalService-category-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/account/otheritem/addVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;

                var trData = '<tr data-flditem="' + val + '"><td>' + val + '</td></tr>';
                $('#js-generalService-table-modal').append(trData);
                $('#js-generalService-flditem-input-modal').val('');
            }
            showAlert(response.message);
        }
    });
});

$('#js-generalService-delete-btn-modal').click(function() {
    var data = {
        flditem: $('#js-generalService-table-modal tr[is_selected="yes"]').data('flditem'),
        type: $('#js-generalService-type-input-modal').val(),
    };
    $.ajax({
        url: baseUrl + '/account/otheritem/deleteVariable',
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-generalService-table-modal tr[is_selected="yes"]').remove();

                showAlert(response.message);
        }
    });
});

$('#js-generalService-modal-import-btn').click(function() {
    if ($('#js-generalService-modal-file-iput')[0].files !== undefined && $('#js-generalService-modal-file-iput')[0].files.length > 0) {
        var data = new FormData();
        var image = $('#js-generalService-modal-file-iput')[0].files[0];
        data.append('fldtext', image);
        data.append('type', $('#js-generalService-type-input-modal').val());
        data.append('category', $('#js-generalService-category-input-modal').val());

        $.ajax({
            url: baseUrl + '/account/otheritem/importVariable',
            type: "POST",
            enctype: 'multipart/form-data',
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                var trData = '';
                $.each(response.data, function(i, val) {
                    trData += '<tr data-flditem="' + val + '"><td>' + val + '</td></tr>';
                });
                $('#js-generalService-table-modal').append(trData);
                $('#js-generalService-modal-file-iput').val('');
                showAlert(response.message);
            }
        });
    }

});

function refresh_generalService_options() {
    $.ajax({
        url: baseUrl + '/account/generalService/getSelectOptions',
        type: "GET",
        success: function (response) {
            var billitems = '<option value="">-- Select --</option>';
            $.each(response.billitems, function(i, e) {
                billitems += '<option value="' + e.fldbillitem + '">' + e.fldbillitem + '</option>'
            });

            var sections = '<option value="">-- Select --</option>';
            $.each(response.sections, function(i, e) {
                sections += '<option value="' + e.fldsection + '">' + e.fldsection + '</option>'
            });

            $('#js-generalService-billitem-input').html(billitems);
            $('#js-generalService-section-input').html(sections);
        }
    });
}

$('#js-generalService-add-item-modal').on('hidden.bs.modal', function () {
    $('#js-generalService-flditem-input-modal').val('');
    $('#js-generalService-type-input-modal').val('');
    $('#js-generalService-table-modal').html('');

    refresh_generalService_options();
});

$(document).on('click', '#js-generalService-item-tbody tr', function () {
    $("#js-generalService-update-btn").attr('disabled', false);
    selected_td('#js-generalService-item-tbody tr', this);

    $('select[name="fldbillitem"]').val($(this).attr('fldbillitem')).trigger('change');
    $('input[name="flditemcost"]').val($(this).attr('flditemcost'));
    $('select[name="fldtarget"]').val($(this).attr('fldtarget'));
    $('select[name="fldgroup"]').val($(this).attr('fldgroup'));
    $('select[name="fldreport"]').val($(this).attr('fldreport')).trigger('change');
    $('select[name="fldstatus"]').val($(this).attr('fldstatus'));
    $('select[name="fldcode"]').val($(this).attr('fldcode'));
    $('input[name="flditemname"]').val($(this).attr('flditemname'));
    $('input[name="flditemcode"]').val($(this).attr('fldbillitemcode'));
    $('select[name="accountledger"]').val($(this).attr('accountledger')).change();
    $('input[name="hi_code"]').val($(this).attr('hi_code'));
    if($(this).attr('fldrate') == 1){
        $('input[name="rate"]').prop('checked', true);
    }else{
        $('input[name="rate"]').prop('checked', false);
    }
    if($(this).attr('flddiscount') == 1){
        $('input[name="discount"]').prop('checked', true);
    }else{
        $('input[name="discount"]').prop('checked', false);
    }

    $('input[name="hospital_share"]').val($(this).attr('fldhospitalshare'));
    $('input[name="other_share"]').val($(this).attr('flddocshare'));

    let category =  JSON.parse($(this).attr('fldcategory'));
    let select_category = $('select[name="category[]"]');
    select_category.val(category);
    select_category.select2();

    var save_btn = $("#js-generalService-save-btn");
    save_btn.attr('disabled', true);
});

$('#js-generalService-save-btn').click(function() {
    $.ajax({
        url: baseUrl + '/account/generalService/saveUpdate',
        type: "POST",
        data: $('#js-generalService-add-form').serialize(),
        success: function (response) {
            if (response.status) {
                let fldcategory = JSON.stringify(JSON.parse(response.data.category));
                $('#js-generalService-add-form')[0].reset();

                var trData = '<tr fldbillitem="' + response.data.fldbillitem + '" flditemcost="' + response.data.flditemcost + '" fldtarget="' + response.data.fldtarget + '" fldgroup="' + response.data.fldgroup + '" fldreport="' + response.data.fldreport + '" fldstatus="' + response.data.fldstatus + '" fldcode="' + response.data.fldcode + '" flditemname="' + response.data.flditemname + '" flddocshare="'+response.data.other_share+'" fldhospitalshare="'+response.data.hospital_share+'" fldid="'+response.data.fldid+'" fldrate="'+response.data.rate+'" flddiscount="'+response.data.discount+'">';
                trData += '<td>' + ($('#js-generalService-item-tbody tr').length +1) + '</td>';
                trData += '<td>' + response.data.flditemname + '</td>';
                trData += '<td>' + response.data.flditemcost + '</td>';
                trData += '<td>' + response.data.fldtarget + '</td>';
                trData += '<td>' + response.data.fldstatus + '</td>';
                trData += '<td>' + response.data.fldgroup + '</td>';
                trData += '<td>' + response.data.fldreport + '</td></tr>';

                $('#js-generalService-item-tbody').append(trData);
                let ctr = $(document).find('#js-generalService-item-tbody>tr[fldid="'+response.data.fldid+'"]');
                ctr.attr('fldcategory', fldcategory);
                $("#select-category").val('').trigger('change');
            }

            showAlert(response.message);
        },
        error: function (error) {
            let msg = "";
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function(i, v) {
                    msg += v + " ";
                });
            }
            showAlert(msg, ' ');
        }
    });
});

$(document).on('click','#js-generalService-clear-btn',function(){
    $('#js-generalService-add-form')[0].reset();
    $("#select-category").val('').trigger('change');
});

$('#js-generalService-update-btn').click(function() {
    var selectedTd = $('#js-generalService-item-tbody tr[is_selected="yes"]');
    var postData = $('#js-generalService-add-form').serialize() + '&fldid=' + $(selectedTd).attr('fldid');
    $.ajax({
        url: baseUrl + '/account/generalService/saveUpdate',
        type: "POST",
        data: postData,
        success: function (response) {
            if (response.status) {
                // $('#js-generalService-add-form')[0].reset();

                $(selectedTd).attr('fldcategory', JSON.stringify(response.data.category));
                $(selectedTd).attr('fldbillitem', response.data.fldbillitem);
                $(selectedTd).attr('flditemcost', response.data.flditemcost);
                $(selectedTd).attr('fldtarget', response.data.fldtarget);
                $(selectedTd).attr('fldgroup', response.data.fldgroup);
                $(selectedTd).attr('fldreport', response.data.fldreport);
                $(selectedTd).attr('fldstatus', response.data.fldstatus);
                $(selectedTd).attr('fldcode', response.data.fldcode);
                $(selectedTd).attr('flditemname', response.data.flditemname);
                $(selectedTd).attr('fldhospitalshare', response.data.hospital_share);
                $(selectedTd).attr('flddocshare', response.data.other_share);
                $(selectedTd).attr('fldrate', response.data.rate);
                $(selectedTd).attr('flddiscount', response.data.discount);

                $(selectedTd).find('td:nth-child(2)').text(response.data.flditemname);
                $(selectedTd).find('td:nth-child(3)').text(response.data.flditemcost);
                $(selectedTd).find('td:nth-child(4)').text(response.data.fldtarget);
                $(selectedTd).find('td:nth-child(5)').text(response.data.fldstatus);
                $(selectedTd).find('td:nth-child(6)').text(response.data.fldgroup);
                $(selectedTd).find('td:nth-child(7)').text(response.data.fldreport);

                // reset category select2
                // $("#select-category").val('').trigger('change');
            }

            showAlert(response.message);
        },
        complete: function() {
            var save_btn = $("#js-generalService-save-btn");
            save_btn.attr('disabled', false);
        }
    });
});
$('#js-generalService-billitem-input,#js-generalService-bill-mode-select').change(function() {
// $('#js-generalService-item-name-input').focusin(function() {
    var value = $('#js-generalService-billitem-input').val() + " (" + $('#js-generalService-bill-mode-select').val() + ")";
    $('#js-generalService-item-name-input').val(value);

    $.ajax({
        url : baseUrl + '/account/otheritem/getbillitemcode',
        data : {
            fldbillitem : $('#js-generalService-billitem-input').val()
        },
        success: function (response){

            var codeid = response.codeid;
            $('#flditemcode').val(codeid);

        }
    });
});

$('#js-generalService-search-btn').click(function() {
    window.location.href = baseUrl + '/account/generalService?fldstatus=' + $('#js-generalService-status-input').val();
});

$('#js-generalService-search-input').keyup(function() {
    var searchText = $(this).val().toUpperCase();
    $.each($('#js-generalService-item-tbody tr td:nth-child(2)'), function(i, e) {
        var tdText = $(e).text().trim().toUpperCase();
        var currentTr = $(e).closest('tr');

        if (tdText.search(searchText) >= 0)
            $(currentTr).show();
        else
            $(currentTr).hide();
    });
});

$(document).on('keyup', '.select2-search__field' , function (e) {
    var id = $(this).closest('.select2-dropdown').find('.select2-results ul').attr('id');
    var newId = id.replace('select2-', '').replace('-results', '');

    if(id == "select2-js-generalService-billitem-input-results" || id == 'select2-js-generalService-section-input-results') {
        if(e.keyCode === 13) {
            $.ajax({
                url: baseUrl + '/account/otheritem/addVariable',
                type: "POST",
                data: {
                    flditem: $(this).val(),
                    type: $('#' + newId).data('variable'),
                    category: $('#js-generalService-category-input-modal').val(),
                },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        var val = response.data;
                        var codeid = response.codeid;

                        var newOption = new Option(val, val, true, true);
                        $('#' + newId).append(newOption).trigger('change');
                        $('#' + newId).val(val).trigger('change');
                        $("#" + newId).select2("close");
                        $('#flditemcode').val(codeid);
                    }
                    showAlert(response.message, (response.status) ? 'success': 'fail');
                }
            });
        }
    }
 });
