// Select 2
$(document).ready(function () {
    $(".suture_variables").select2();
    $(".suture_types").select2();
    $(".item_name_surgical").select2();
});

// Insert Surgical Variable
$(document).on("click", "#insert_surgical_name_variable", function(){
	var fldsurgname = $("#surgical_name_variable").val();
	var fldsurgcateg = 'suture';
	var url = $(this).attr('url');
	formData = {
		fldsurgname: fldsurgname,
		fldsurgcateg: fldsurgcateg
	}
	if(fldsurgname == ''){
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
	            getSurgicalVariables(fldsurgcateg);
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});


// Delete Surgical Variable
$(document).on("click", "#delete_surgical_name_variable", function(){
	var fldid = $("input[name='selected_surgical_variable']:checked").val();
	var value = $("input[name='selected_surgical_variable']:checked").attr('rel');
	var url = $(this).attr('url');
	formData = {
		fldid: fldid
	}
	if(fldid == ''){
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
	            $("input[name='selected_surgical_variable']:checked + li").remove();
	            $('select[name="get_selected_itemName"]').find('option[value="'+value+'"]').remove();
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// To Get Surgical Variable
function getSurgicalVariables(fldsurgcateg){
	$('.surgical-name-list').empty();
	$('.suture_variables').empty();
	$.get('surgical/get-all-surgical-variables?fldsurgcateg=' + fldsurgcateg, function(data) {
		$('.suture_variables').append('<option>---Select Suture---</option>');
        $.each(data, function(index, getVariables) {
            $('.suture_variables').append('<option value="'+ getVariables.col +'">'+ getVariables.col +'</option>');
            $('.surgical-name-list').append('<input type="radio" name="selected_surgical_variable" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'" rel="'+getVariables.col+'"><li><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.col +'</label></li>');
        });
    });
}

// To Get Surgical Variable according to ortho/msurg
function getSelectedSurgicalVariables(fldsurgcateg){
	$('.selected-surgical-list').empty();
	$('.item_name_surgical').empty();
	$.get('surgical/get-all-surgical-variables?fldsurgcateg=' + fldsurgcateg, function(data) {
		$('.item_name_surgical').append('<option>---Select Item Name---</option>');
        $.each(data, function(index, getVariables) {
            $('.item_name_surgical').append('<option value="'+ getVariables.col +'">'+ getVariables.col +'</option>');
            $('.selected-surgical-list').append('<input type="radio" name="selected_itemName" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'" rel="'+getVariables.col+'"><li><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.col +'</label></li>');
        });
    });
}

$(document).on("click", ".get_all_variables", function(){
	var fldsurgcateg = 'suture';
	getSurgicalVariables(fldsurgcateg);
});

$(document).on("click",".checkifselectedcategory-disable",function(){
	alert("Please select category first!");
});

// Insert Surgical Item Variable
$(document).on("click", "#insert_surgical_itemName_variable", function(){
	var fldsurgname = $("#surgical_itemName_variable").val();
	var fldsurgcateg = $("#selected_variable_category option:selected").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgname: fldsurgname,
		fldsurgcateg: fldsurgcateg
	}
	if(fldsurgname == ''){
		alert('Please Fill The Field');
		return false;
	}
	if(fldsurgcateg == ''){
		alert('Please Select Category First');
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
	            getSelectedSurgicalVariables(fldsurgcateg);
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Delete Surgical Item Variable
$(document).on("click", "#delete_surgical_itemName_variable", function(){
	var fldid = $("input[name='selected_itemName']:checked").val();
	var value = $("input[name='selected_itemName']:checked").attr('rel');
	var url = $(this).attr('url');
	formData = {
		fldid: fldid
	}
	if(fldid == ''){
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
	            $("input[name='selected_itemName']:checked + li").remove();
	            $('select[name="get_selected_itemName"]').find('option[value="'+value+'"]').remove();
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Insert Surgical Type Variable
$(document).on("click", "#insert_surgical_name_type", function(){
	var fldsuturetype = $("#surgical_type_name").val();
	var fldsuturecode = $("#surgical_type_code").val();
	var url = $(this).attr('url');
	formData = {
		fldsuturetype: fldsuturetype,
		fldsuturecode: fldsuturecode
	}
	if(fldsuturetype == '' || fldsuturecode == ''){
		alert('Please Fill All The Fields');
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
	            getTestName();
	        } else {
	            alshowAlertert(data.message);
	        }
	    }
	});
});

// Delete Surgical Type Variable
$(document).on("click", "#delete_surgical_name_type", function(){
	var fldid = $(this).attr('rel');
	var url = $(this).attr('url');
	formData = {
		fldid: fldid
	}
	if(fldid == ''){
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
	            $(".suture_type_remove_id_"+fldid).remove();
	            $('select[name="get_selected_name_type"]').find('option[rel="'+fldid+'"]').remove();
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

function getTestName(){
	$('.surgical-type-code-list').empty();
	$('.suture_types').empty();
	var num = 1;
	$.get('surgical/get-all-surgical-types', function(data) {
		$('.suture_types').append('<option>---Select Type---</option>');
        $.each(data, function(index, getType) {
        	var html = '';
        		html += '<tr rel="'+ getType.fldid +'" rel1="'+ getType.type +'" rel2="'+ getType.code +'" class="select_suture_type_row suture_type_remove_id_'+getType.fldid+'">';
        		html += '<td>'+ num +'</td>';
        		html += '<td>' + getType.type + '</td>';
        		html += '<td>' + getType.code + '</td>';
        		html += '</tr>';
            $('.suture_types').append('<option value="'+ getType.type +'" rel="'+ getType.fldid +'" rel1="'+ getType.code +'">'+ getType.type +'</option>');
            $('.surgical-type-code-list').append(html);
            num++;
        });
    });
}

$(document).on("click", ".select_suture_type_row", function(){
	$(".select_suture_type_row").removeClass("select_the_element");
	$(this).addClass("select_the_element");
});

$(document).on("click", ".get_suture_type_variable", function(){
	getTestName();
});

$(document).on("click", ".surgical-type-code-list tr", function(){
	var fldid = $(this).attr('rel');
	var type = $(this).attr('rel1');
	var code = $(this).attr('rel2');

	$('#surgical_type_name').val(null);
	$('#surgical_type_code').val(null);

	$('#surgical_type_name').val(type);
	$('#surgical_type_code').val(code);
	$('#delete_surgical_name_type').attr('rel', fldid);
});

$(document).on("change", ".suture_types", function(){
	var code = $('.suture_types option:selected').attr('rel1');
	$("#get_selected_suture_code_here").val(code);
});

// Disable Buttons
// Enable
$(document).on("change", ".suture_variables", function(){
	$(".disabled-opacity").addClass('hide-this-element');
	$(".after-selected").removeClass('hide-this-element');
});

$(document).on("click", ".clear-fields-and-save", function(){
	/*$("#suture_size_surgical").val(null);
	$('option:selected', 'select[name="get_selected_name_type"]').removeAttr('selected');
	$("#get_selected_suture_code_here").val(null);
	$("#suture_code_surgical").val(null);
	$("#flddetail_surgical").val(null);*/
	$(this).addClass('hide-this-element');
	$("#insert_surgical_data").removeClass('hide-this-element');
});

$(document).on("click", ".checkifexist", function(){
	var code = $("#get_selected_suture_code_here").val();
	if(code == ''){
		alert('Please Select Code First');
		return false;
	}
	$(this).addClass('hide-this-element');
	$("#update_surgical_data").removeClass('hide-this-element');
});

// Second Box msurg/ortho
// Enable
$(document).on("change", "#selected_variable_category", function(e){
	var fldsurgcateg = e.target.value;
	$('#change-this-category-name').empty();
	if(fldsurgcateg != 0){
		$('#change-this-category-name').append(fldsurgcateg);
		$('.checkifselectedcategory').removeClass('hide-this-element');
		$('.checkifselectedcategory-disable').addClass('hide-this-element');
		$(".disabled-opacity2").addClass('hide-this-element');
		$(".after-selected2").removeClass('hide-this-element');
	}else{
		$('.checkifselectedcategory-disable').removeClass('hide-this-element');
		$('.checkifselectedcategory').addClass('hide-this-element');
		$(".disabled-opacity2").removeClass('hide-this-element');
		$(".after-selected2").addClass('hide-this-element');
	}
	getSelectedSurgicalVariables(fldsurgcateg);
});

$(document).on("click", ".clear-fields-and-save-second", function(){
	$('option:selected', 'select[name="get_selected_itemName"]').removeAttr('selected');
	$("#item_size_bottom_surgical").val(null);
	$("#item_type_bottom_surgical").val(null);
	$("#flddetail_surgical_bottom").val(null);
	$(this).addClass('hide-this-element');
	$("#insert_surgical_data_second").removeClass('hide-this-element');
});

$(document).on("click", ".checkifexist-second", function(){
	var category = $("#selected_variable_category option:selected").val();
	if(category == ''){
		alert('Please Select Category First');
		return false;
	}
	$(this).addClass('hide-this-element');
	$("#update_surgical_data_second").removeClass('hide-this-element');
});

// List On click
$(document).on("click", ".get_clicked_surgical_data", function(){
	var fldsurgid = $(this).attr('rel');
	var fldsurgcateg = $(this).attr('rel1');
	var fldsurgname = $(this).attr('rel2');
	var fldsurgsize = $(this).attr('rel3');
	var fldsurgtype = $(this).attr('rel4');
	var fldsurgcode = $(this).attr('rel5');
	var fldsurgdetail = $(this).attr('rel6');
	$("#surgical_fldsurgid").val(null);
	$("#surgical_fldsurgid").val(fldsurgid);

	$("#selected_surg_id").val(null);
	$("#selected_surg_id").val(fldsurgid);

	if(fldsurgcateg == 'suture'){
		$('option:selected', 'select[name="suture_variables"]').removeAttr('selected');
		$("#suture_size_surgical").val(null);
		$("#get_selected_suture_code_here").val(null);
		$("#suture_code_surgical").val(null);
		$("#flddetail_surgical").val(null);

		$('select[name="suture_variables"]').append('<option value="' + fldsurgname + '" selected >' + fldsurgname + '</option>');
		$("#suture_size_surgical").val(fldsurgsize);
		$("#get_selected_suture_code_here").val(fldsurgtype);
		$("#suture_code_surgical").val(fldsurgcode);
		$("#flddetail_surgical").text(fldsurgdetail);
		// Enable Buttons
		$(".disabled-opacity").addClass('hide-this-element');
		$("#insert_surgical_data").addClass('hide-this-element');
		$("#update_surgical_data").addClass('hide-this-element');
		$("#delete_surgical_data").addClass('hide-this-element');
		$(".after-selected").removeClass('hide-this-element');

		// Dissable Buttons msurg/ortho
		$(".disabled-opacity2").removeClass('hide-this-element');
		$("#insert_surgical_data_second").addClass('hide-this-element');
		$("#update_surgical_data_second").addClass('hide-this-element');
		$("#delete_surgical_data_second").addClass('hide-this-element');
		$(".after-selected2").addClass('hide-this-element');
	}else{
		$('option:selected', 'select[name="selected_variable_category"]').removeAttr('selected');
		$('option:selected', 'select[name="get_selected_itemName"]').removeAttr('selected');
		$("#item_size_bottom_surgical").val(null);
		$("#item_type_bottom_surgical").val(null);
		$("#flddetail_surgical_bottom").val(null);

		$('select[name="selected_variable_category"]').find('option[value="'+fldsurgcateg+'"]').attr("selected",true);
		$('select[name="get_selected_itemName"]').append('<option value="' + fldsurgname + '" selected >' + fldsurgname + '</option>');
		$("#item_size_bottom_surgical").val(fldsurgsize);
		$("#item_type_bottom_surgical").val(fldsurgtype);
		$("#flddetail_surgical_bottom").val(fldsurgdetail);
		// Enable Buttons
		$(".disabled-opacity2").addClass('hide-this-element');
		$("#insert_surgical_data_second").addClass('hide-this-element');
		$("#update_surgical_data_second").addClass('hide-this-element');
		$("#delete_surgical_data_second").addClass('hide-this-element');
		$(".after-selected2").removeClass('hide-this-element');

		// Dissable Buttons suture
		$(".disabled-opacity").removeClass('hide-this-element');
		$("#insert_surgical_data").addClass('hide-this-element');
		$("#update_surgical_data").addClass('hide-this-element');
		$("#delete_surgical_data").addClass('hide-this-element');
		$(".after-selected").addClass('hide-this-element');
	}

});

// Insert Surgical Suture
$(document).on("click", "#insert_surgical_data", function(){
	var fldsurgname 	= $(".suture_variables option:selected").val();
	var fldsurgcateg 	= "suture";
	var fldsurgsize 	= $("#suture_size_surgical").val();
	var fldsurgtype 	= $("#get_selected_suture_code_here").val();
	var fldsurgcode 	= $("#suture_code_surgical").val();
	var fldsurgdetail 	= $("#flddetail_surgical").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgname: fldsurgname,
		fldsurgcateg: fldsurgcateg,
		fldsurgsize: fldsurgsize,
		fldsurgtype: fldsurgtype,
		fldsurgcode: fldsurgcode,
		fldsurgdetail: fldsurgdetail
	}
	if(fldsurgname == '' || fldsurgsize == '' || fldsurgtype == '' || fldsurgcode == ''){
		alert('Please Fill All The Fields');
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
	            // getTestName();
	            $(".clear-fields-and-save").removeClass('hide-this-element');
				$("#insert_surgical_data").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Insert Surgical msurg/ortho
$(document).on("click", "#insert_surgical_data_second", function(){
	var fldsurgname 	= $(".item_name_surgical option:selected").val();
	var fldsurgcateg 	= $("#selected_variable_category option:selected").val();
	var fldsurgsize 	= $("#item_size_bottom_surgical").val();
	var fldsurgtype 	= $("#item_type_bottom_surgical").val();
	var fldsurgdetail 	= $("#flddetail_surgical_bottom").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgname: fldsurgname,
		fldsurgcateg: fldsurgcateg,
		fldsurgsize: fldsurgsize,
		fldsurgtype: fldsurgtype,
		fldsurgdetail: fldsurgdetail
	}
	if(fldsurgname == '' || fldsurgsize == '' || fldsurgtype == ''){
		alert('Please Fill All The Fields');
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
	            // getTestName();
	            $(".clear-fields-and-save-second").removeClass('hide-this-element');
				$("#insert_surgical_data_second").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Update Surgical Suture
$(document).on("click", "#update_surgical_data", function(){
	var fldsurgid 		= $("#surgical_fldsurgid").val();
	var fldsurgcateg 	= "suture";
	var fldsurgsize 	= $("#suture_size_surgical").val();
	var fldsurgtype 	= $("#get_selected_suture_code_here").val();
	var fldsurgcode 	= $("#suture_code_surgical").val();
	var fldsurgdetail 	= $("#flddetail_surgical").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgid: fldsurgid,
		fldsurgcateg: fldsurgcateg,
		fldsurgsize: fldsurgsize,
		fldsurgtype: fldsurgtype,
		fldsurgcode: fldsurgcode,
		fldsurgdetail: fldsurgdetail
	}
	if(fldsurgid == ''){
		alert('Please Select Surgical Name From The Sidebar');
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
	            // getTestName();
	            $(".checkifexist").removeClass('hide-this-element');
				$("#update_surgical_data").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Update Surgical msurg/ortho
$(document).on("click", "#update_surgical_data_second", function(){
	var fldsurgid 		= $("#surgical_fldsurgid").val();
	var fldsurgcateg 	= $("#selected_variable_category option:selected").val();
	var fldsurgsize 	= $("#item_size_bottom_surgical").val();
	var fldsurgtype 	= $("#item_type_bottom_surgical").val();
	var fldsurgdetail 	= $("#flddetail_surgical_bottom").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgid: fldsurgid,
		fldsurgcateg: fldsurgcateg,
		fldsurgsize: fldsurgsize,
		fldsurgtype: fldsurgtype,
		fldsurgdetail: fldsurgdetail
	}
	if(fldsurgid == ''){
		alert('Please Select Surgical Name From The Sidebar');
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
	            // getTestName();
	            $(".checkifexist-second").removeClass('hide-this-element');
				$("#update_surgical_data_second").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Delete Surgical Suture
$(document).on("click", ".delete_surgical_data", function(){
	var fldsurgid = $("#surgical_fldsurgid").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgid: fldsurgid,
	}
	if(fldsurgid == ''){
		alert('Please Select Surgical Name From The Sidebar');
		return false;
	}

    $("#confirmation_dialog_surgical").html("You want to delete "+fldsurgid+"?");
	$("#confirmation_dialog_surgical").dialog({
	    resizable: false,
	    modal: true,
	    title: "Are you sure?",
	    height: 240,
	    width: 420,
	    buttons: {
	      "Yes": function() {
	        $(this).dialog('close');
	        $.ajax({
				url: url,
				type: "POST",
			    dataType: "json",
			    data: formData,
			    success: function (data) {
			        if (data.status) {
			            showAlert(data.message);
			            // getTestName();
			        } else {
			            showAlert(data.message);
			        }
			    }
			});
	      },
	      "No": function() {
	        $(this).dialog('close');
	        return false;
	      }
	    }
	});
});

$(document).on("click", ".item-sub-brand-list li", function(){
	var fldbrandid 		= $(this).attr('rel');
	var fldsurgid 		= $(this).attr('rel1');
	var fldbrand 		= $(this).attr('rel2');
	var fldmanufacturer = $(this).attr('rel3');
	var flddetail 		= $(this).attr('rel4');
	var fldstandard 	= $(this).attr('rel5');
	var fldmaxqty 		= $(this).attr('rel6');
	var fldminqty 		= $(this).attr('rel7');
	var fldleadtime 	= $(this).attr('rel8');
	var fldtaxcode 		= $(this).attr('rel9');
	var fldactive 		= $(this).attr('rel11');

	$("#selected_surg_brand_id").val(null);
	$("#selected_surg_id").val(null);
	$("#selected_surg_brand_name").val(null);
	$("#selected_surg_standard").val(null);
	$("#selected_surg_manufacturer").val(null);
	$("#selected_surg_description").val(null);
	$("#selected_surg_min_stock").val(null);
	$("#selected_surg_max_stock").val(null);
	$("#selected_surg_lead_time").val(null);
	$('option:selected', 'select[name="selected_surg_current_status"]').removeAttr('selected');
	$("#selected_surg_tax_code").val(null);

	$("#selected_surg_brand_id").val(fldbrandid);
	$("#selected_surg_id").val(fldsurgid);
	$("#selected_surg_brand_name").val(fldbrand);
	$("#selected_surg_standard").val(fldstandard);
	$("#selected_surg_manufacturer").val(fldmanufacturer);
	$("#selected_surg_description").val(flddetail);
	$("#selected_surg_min_stock").val(fldminqty);
	$("#selected_surg_max_stock").val(fldmaxqty);
	$("#selected_surg_lead_time").val(fldleadtime);
	$('select[name="selected_surg_current_status"]').find('option[value="'+fldactive+'"]').attr("selected",true);
	$("#selected_surg_tax_code").val(fldtaxcode);

	brandinfoTab();
});

function brandinfoTab(){
 	$('[href="#brandinfo"]').tab('show');
}

$(document).on("click", ".clear-field-save-btn", function(){
	var surgid  = $("#selected_surg_id").val();
	if(surgid == ''){
		alert('Please Select Surgical Name From The Sidebar To Add Brand');
		return false;
	}
	$("#selected_surg_brand_name").val(null);
	$("#selected_surg_standard").val(null);
	$("#selected_surg_manufacturer").val(null);
	$("#selected_surg_description").val(null);
	$("#selected_surg_min_stock").val(null);
	$("#selected_surg_max_stock").val(null);
	$("#selected_surg_lead_time").val(null);
	$("#selected_surg_current_status").val(null);
	$("#selected_surg_tax_code").val(null);

	$(this).addClass('hide-this-element');
	$("#insert_surg_brand").removeClass('hide-this-element');
});

$(document).on("click", ".check-before-update", function(){
	var brandid = $("#selected_surg_brand_id").val();
	var surgid  = $("#selected_surg_id").val();
	if(brandid == '' || surgid == ''){
		alert('Please Select Brant Before Editing');
		return false;
	}
	$(this).addClass('hide-this-element');
	$("#update_surg_brand").removeClass('hide-this-element');
});


// Insert Surgical Brand
$(document).on("click", "#insert_surg_brand", function(){
	var fldsurgid 		= $("#selected_surg_id").val();
	var fldbrand 		= $("#selected_surg_brand_name").val();
	var fldmanufacturer = $("#selected_surg_manufacturer").val();
	var flddetail 		= $("#selected_surg_description").val();
	var fldstandard 	= $("#selected_surg_standard").val();
	var fldminqty 		= $("#selected_surg_min_stock").val();
	var fldmaxqty 		= $("#selected_surg_max_stock").val();
	var fldleadtime 	= $("#selected_surg_lead_time").val();
	var fldactive 		= $("#selected_surg_current_status option:selected").val();
	var fldtaxcode 		= $("#selected_surg_tax_code").val();
	var url = $(this).attr('url');
	formData = {
		fldsurgid: fldsurgid,
		fldbrand: fldbrand,
		fldmanufacturer: fldmanufacturer,
		flddetail: flddetail,
		fldstandard: fldstandard,
		fldmaxqty: fldmaxqty,
		fldminqty: fldminqty,
		fldleadtime: fldleadtime,
		fldactive: fldactive,
		fldtaxcode: fldtaxcode,
	}
	if(fldsurgid == ''){
		alert('Please Select Surgical Name From Sidbar Before Inserting');
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
	            $(".clear-field-save-btn").removeClass('hide-this-element');
				$("#insert_surg_brand").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Update Surgical Brand
$(document).on("click", "#update_surg_brand", function(){
	var fldbrandid		= $("#selected_surg_brand_id").val();
	var fldsurgid 		= $("#selected_surg_id").val();
	var fldbrand 		= $("#selected_surg_brand_name").val();
	var fldmanufacturer = $("#selected_surg_manufacturer").val();
	var flddetail 		= $("#selected_surg_description").val();
	var fldstandard 	= $("#selected_surg_standard").val();
	var fldminqty 		= $("#selected_surg_min_stock").val();
	var fldmaxqty 		= $("#selected_surg_max_stock").val();
	var fldleadtime 	= $("#selected_surg_lead_time").val();
	var fldactive 		= $("#selected_surg_current_status option:selected").val();
	var fldtaxcode 		= $("#selected_surg_tax_code").val();
	var url = $(this).attr('url');
	formData = {
		fldbrandid: fldbrandid,
		fldsurgid: fldsurgid,
		fldbrand: fldbrand,
		fldmanufacturer: fldmanufacturer,
		flddetail: flddetail,
		fldstandard: fldstandard,
		fldmaxqty: fldmaxqty,
		fldminqty: fldminqty,
		fldleadtime: fldleadtime,
		fldactive: fldactive,
		fldtaxcode: fldtaxcode,
	}
	if(fldsurgid == '' || fldbrandid == ''){
		alert('Please Select Surgical Brand From Sidbar Before Updating');
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
	            $(".check-before-update").removeClass('hide-this-element');
				$("#update_surg_brand").addClass('hide-this-element');
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});

// Delete Surgical Brand
$(document).on("click", "#delete_surg_brand", function(){
	var fldbrandid		= $("#selected_surg_brand_id").val();
	var url = $(this).attr('url');
	formData = {
		fldbrandid: fldbrandid
	}
	if(fldbrandid == ''){
		alert('Please Select Surgical Brand From Sidbar Before Deleting');
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
                location.reload();
	        } else {
	            showAlert(data.message);
	        }
	    }
	});
});
