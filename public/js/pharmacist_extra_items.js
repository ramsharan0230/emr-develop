// Select 2
$(document).ready(function () {
    $(".department_select_text").select2();
    $(document).on("keydown", ".select2-search__field", function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            //alert('You pressed a "enter" key in textbox');
            $('.department_select_text').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
        }
    });
});

// Insert Item Name Variable
$("#insert_item_name_variable").on("click", function(){
	var item_name = $("#items_name_variable").val();
	var url = $(this).attr('url');
	formData = {
		item_name: item_name
	}

	if(item_name == ''){
		alert('Please Fill The Field');
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
                getItemVariables();
                getSidebarList();
            } else {
                showAlert(data.message);
            }
        }
	});
});

// Delete Item Name Variable
$("#delete_item_name_variable").on("click", function(){
	var fldid = $('input[name="selected_item_variable"]:checked').val();
	var url = $(this).attr('url');
	formData = {
		fldid: fldid
	}

	if(fldid == ''){
		alert('Please Select The Variable For Delete');
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
                $(".item-variable-remove-"+fldid).remove();
                getSidebarList();
            } else {
                showAlert(data.message);
            }
        }
	});
});

function getItemVariables()
{
	$.get('extra-item/get-all-variables', function(data) {
		$('.item-name-list').empty();
        $.each(data, function(index, getVariables) {
            $('.item-name-list').append('<li class="item-variable-remove-'+getVariables.fldid+'"><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.col +'</label></li><input type="radio" name="selected_item_variable" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'">');
        });
    });
}

$(document).on("click", "#get_item_variables", function(){
	getItemVariables();
});

$(document).on("click", ".select-item-name", function(){
	var item_name = $(this).attr('rel');
	$("#selected_item_name").val(item_name);
	$("#selected_item_brand").val(null);
	$("#selected_pack_volume").val(null);
	$("#selected_volumn_unit").val(null);
	$("#selected_manufacturer").val(null);
	$('option:selected', 'select[name="selected_department"]').removeAttr('selected');
	$("#selected_description").text(null);
	$('option:selected', 'select[name="selected_category"]').removeAttr('selected');
	$("#selected_min_stock").val(null);
	$("#selected_max_stock").val(null);
	$("#selected_lead_time").val(null);
	$('option:selected', 'select[name="selected_current_status"]').removeAttr('selected');
});

$(".clear-field-save-btn").on("click", function(){
	$(this).addClass('hide-this-btn');
	$("#insert_extra_item").removeClass('hide-this-btn');

	$("#selected_item_brand").val(null);
	$("#selected_pack_volume").val(0);
	$("#selected_volumn_unit").val(null);
	$("#selected_manufacturer").val(null);
	$('option:selected', 'select[name="selected_department"]').removeAttr('selected');
	$("#selected_description").empty();
	$('option:selected', 'select[name="selected_category"]').removeAttr('selected');
	$("#selected_min_stock").val(0);
	$("#selected_max_stock").val(0);
	$("#selected_lead_time").val(0);
	$('option:selected', 'select[name="selected_current_status"]').removeAttr('selected');
});

$(".check-before-update").on("click", function(){
	var item_brand = $("#selected_item_brand").val();
	if(item_brand == ''){
		alert('Please Select Brand Before Editing');
		return false;
	}
	$(this).addClass('hide-this-btn');
	$("#update_extra_item").removeClass('hide-this-btn');
});

$(document).on("click", ".select-item-brand", function(){
	var item_brand_name = $(this).attr('rel');
	var level = $(this).attr('rel2');
	$("#delete_extra_item").attr('rel', level);
	$.get('extra-item/get-brand-details?fldbrandid='+ item_brand_name, function(data) {
		$("#selected_item_brand").val(null);
		$("#selected_pack_volume").val(null);
		$("#selected_volumn_unit").val(null);
		$("#selected_manufacturer").val(null);
		$('option:selected', 'select[name="selected_department"]').removeAttr('selected');
		$("#selected_description").text(null);
		$('option:selected', 'select[name="selected_category"]').removeAttr('selected');
		$("#selected_min_stock").val(null);
		$("#selected_max_stock").val(null);
		$("#selected_lead_time").val(null);
		$('option:selected', 'select[name="selected_current_status"]').removeAttr('selected');

		$("#selected_item_brand").val(data.fldbrand);
		$("#selected_item_brand").attr('rel', data.fldbrandid);
		$("#selected_pack_volume").val(data.fldpackvol);
		$("#selected_volumn_unit").val(data.fldvolunit);
		$("#selected_manufacturer").val(data.fldmanufacturer);
		if(data.flddepart != null){
			$("#selected_department").append('<option value="'+data.flddepart+'" selected="selected">'+data.flddepart+'</option>');
		}
		$("#selected_description").text(data.flddetail);
		$('select[name="selected_category"]').find('option[value="'+data.fldstandard+'"]').attr("selected",true);
		$("#selected_max_stock").val(data.fldmaxqty);
		$("#selected_min_stock").val(data.fldminqty);
		$("#selected_lead_time").val(data.fldleadtime);
		$('select[name="selected_current_status"]').find('option[value="'+data.fldactive+'"]').attr("selected",true);
    });
});

$(document).on("click", "#insert_extra_item", function(){
	var item_name = $("#selected_item_name").val();
	var item_brand = $("#selected_item_brand").val();
	var vol_pack = $("#selected_pack_volume").val();
	var vol_unit = $("#selected_volumn_unit").val();
	var manufacturer = $("#selected_manufacturer").val();
	var department = $("#selected_department option:selected").val();
	var description = $("#selected_description").val();
	var category = $("#selected_category option:selected").val();
	var min_stock = $("#selected_min_stock").val();
	var max_stock = $("#selected_max_stock").val();
	var lead_time = $("#selected_lead_time").val();
	var status = $("#selected_current_status option:selected").val();
	var url = $(this).attr('url');
	formData = {
		item_name: item_name,
		item_brand: item_brand,
		vol_pack: vol_pack,
		vol_unit: vol_unit,
		manufacturer: manufacturer,
		department: department,
		description: description,
		category: category,
		min_stock: min_stock,
		max_stock: max_stock,
		lead_time: lead_time,
		status: status
	}
	if(item_name == ''){
		alert('Please Select Item First');
		return false;
	}
	if(item_brand == '' || vol_pack == '' || vol_unit == '' || min_stock == '' || max_stock == '' || lead_time == '') {
		alert('Please Fill the required fields');
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
                getSidebarList();
                $(".clear-field-save-btn").removeClass('hide-this-btn');
				$("#insert_extra_item").addClass('hide-this-btn');
            } else {
                showAlert(data.message);
                $(".clear-field-save-btn").removeClass('hide-this-btn');
				$("#insert_extra_item").addClass('hide-this-btn');
            }
        }
	});
});

$(document).on("click", "#update_extra_item", function(){
	var brandid = $("#selected_item_brand").attr('rel');
	var item_brand = $("#selected_item_brand").val();
	var vol_pack = $("#selected_pack_volume").val();
	var vol_unit = $("#selected_volumn_unit").val();
	var manufacturer = $("#selected_manufacturer").val();
	var department = $("#selected_department option:selected").val();
	var description = $("#selected_description").val();
	var category = $("#selected_category option:selected").val();
	var min_stock = $("#selected_min_stock").val();
	var max_stock = $("#selected_max_stock").val();
	var lead_time = $("#selected_lead_time").val();
	var status = $("#selected_current_status option:selected").val();
	var url = $(this).attr('url');
	formData = {
		brandid: brandid,
		item_brand: item_brand,
		vol_pack: vol_pack,
		vol_unit: vol_unit,
		manufacturer: manufacturer,
		department: department,
		description: description,
		category: category,
		min_stock: min_stock,
		max_stock: max_stock,
		lead_time: lead_time,
		status: status
	}
	if(brandid == '') {
		alert('Please Select The Item For Update');
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
                getSidebarList();
                $(".check-before-update").removeClass('hide-this-btn');
				$("#update_extra_item").addClass('hide-this-btn');
            } else {
                showAlert(data.message);
                $(".check-before-update").removeClass('hide-this-btn');
				$("#update_extra_item").addClass('hide-this-btn');
            }
        }
	});
});

$(document).on("click", "#delete_extra_item", function(){
	var brandid = $("#selected_item_brand").attr('rel');
	var level = $(this).attr('rel');
	var url = $(this).attr('url');
	formData = {
		brandid: brandid,
	}
	if(brandid == '') {
		alert('Please Select The Item For Delete');
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
                $(".delete-this-brand-"+level).closest("li").remove();
            } else {
                showAlert(data.message);
            }
        }
	});
});

function getSidebarList()
{
	$.get('extra-item/sidebar-item-list', function(data) {
		$.get('extra-item/sidebar-brand-list', function(data2) {
			$('.list-all-the-item').empty();
			var num = 1;
	        $.each(data, function(index, item) {
			var html = '';
	        	html += '<li>';
	        	html += '<a href="#item-name-'+num+'" data-toggle="collapse" class="select-item-name" rel="'+item.fldparent+'">';
	        	html += '<i class="fas fa-angle-right"></i>&nbsp;&nbsp;<i class="fas fa-list"></i>&nbsp;&nbsp;'+item.fldparent;
	        	html += '</a>';
	        	html += '<ul id="item-name-'+num+'" class="collapse item-brand-list" data-parent="#collapse-parent">';
	        	$.each(data2, function(index, brand) {
		        	var count = 1;
	        		if(index == item.fldparent){
		        		$.each(brand, function(index, b) {
			        		html += '<li class="select-item-brand delete-this-brand-'+num+'-'+count+'" " rel="'+b.fldchild+'" rel2="'+num+'-'+count+'">'+b.fldchild+'</li>';
			        	count++;
			        	});
		        	}
	        	});
	        	html += '</ul>';
	        	html += '</li>';
	            $('.list-all-the-item').append(html);
	            num++;
	        });
	    });
    });
}