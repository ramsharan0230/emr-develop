// DataTable Search
var table = $('table.datatable-store-consume').DataTable({
    "paging":   false 
});

// Insert Target Variable
$(document).on("click", "#insert_target_variable", function(){
	var flditem = $("#target_variable_name").val();
	var url = $(this).attr("url");
    var formData = {
        flditem: flditem
    };

    if(flditem == '')
    {
    	alert('Please Insert Variable');
    	return false;
    }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                getTargetVariables();
            } else {
                showAlert(data.message);
            }
        }
    });
});

// Delete Target Variable
$(document).on("click", "#delete_target_variable", function(){
	var fldid = $("input[name='selected_target_variable']:checked").val();
	var value = $("input[name='selected_target_variable']:checked").attr('rel');
	var url = $(this).attr("url");
    var formData = {
        fldid: fldid
    };

    if(fldid == '')
    {
    	alert('Please Select Variable To Delete');
    	return false;
    }

    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (data) {
            if (data.status) {
                showAlert(data.message);
                $("input[name='selected_target_variable']:checked + li").remove();
	            $('select[name="get_target_variables"]').find('option[value="'+value+'"]').remove();
            } else {
                showAlert(data.message);
            }
        }
    });
});

function getTargetVariables(){
	$('#get_target_variables').empty();
	$('.target-box-list').empty();
	$.get('store/target/get-variable', function(data) {
		$('#get_target_variables').append('<option>---Select---</option>');
        $.each(data, function(index, getVariables) {
            $('#get_target_variables').append('<option value="'+ getVariables.col +'">'+ getVariables.col +'</option>');
            $('.target-box-list').append('<input type="radio" name="selected_target_variable" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'" rel="'+getVariables.col+'"><li><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.col +'</label></li>');
        });
    });
}

$(document).on("click", "#onclick_stock_consume", function(){
	getTargetVariables();
});

$(document).on("click", "#get_related_med_stock_consume", function(){
	var cat = $("#get_categroy_stock_consume").val();
	$('.get_related_med_list').empty();
	$.get('store/tblentry/get-med?cat=' + cat, function(data) {
        $.each(data, function(index, getVariables) {
        	var html = '';
        		html += '<tr rel="'+getVariables.fldstockid+'">';
        		html += '<td>';
        		html += getVariables.fldstockid;
        		html += '<td>';
        		html += '</tr>';
            $('.get_related_med_list').append(html);
        });
    });
});