$(document).ready(function () {
    $("#get_starting_date").datetimepicker({
        dateFormat: "yy-mm-dd",
    });
});

// Reset All Fields
$(".reset-this").on("click", function(){
	$("#get_supplier_name").val(null);
	$("#get_supplier_address").val(null);
	$("#get_supplier_phone").val(null);
	$("#get_contact_name").val(null);
	$("#get_credit_day").val(null);
	$("#get_starting_date").val(null);
	$("#get_contact_phone").val(null);
});


// Insert Suppliers Info
$("#insert_supplier_info").on("click", function(){
	var suppname = $("#get_supplier_name").val();
	var suppaddress = $("#get_supplier_address").val();
	var suppphone = $("#get_supplier_phone").val();
	var contactname = $("#get_contact_name").val();
	var startdate = $("#get_starting_date").val();
	var paymentmode = $("#get_payment_mode option:selected").val();
	var active = $("#get_active option:selected").val();
	var creditday = $("#get_credit_day").val();
	var contactphone = $("#get_contact_phone").val();
	var url = $(this).attr('url');
	var formData = {
        suppname: suppname,
        suppaddress: suppaddress,
        suppphone: suppphone,
        contactname: contactname,
        contactphone: contactphone,
        startdate: startdate,
        paymentmode: paymentmode,
        creditday: creditday,
        active: active
    };

    if(suppname == '')
    {
    	alert('Please Insert Supplier Name');
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
                getAllSupplierInfo();
            } else {
                showAlert(data.message);
            }
        }
    });
});

function getAllSupplierInfo()
{
	var num = 1;
	var paid_dabit = 0;
	var left_credit = 0;
	var total_balance = 0;
	$('#get_all_supplier_info_list').empty();
	$.get('supplier-info/get-all-suppliers-info', function(data) {
        $.each(data, function(index, getInfo) {
        	var tot = getInfo.fldleftcredit - getInfo.fldpaiddebit;
        	paid_dabit = paid_dabit + getInfo.fldpaiddebit;
        	left_credit = left_credit + getInfo.fldleftcredit;
        	total_balance = total_balance + tot;
            $('#get_all_supplier_info_list').append('<tr class="remove-supplier-'+num+'" rel="'+getInfo.fldsuppname+'" rel2="'+num+'"><td>'+num+'</td><td>'+getInfo.fldsuppname+'</td><td>'+getInfo.fldsuppaddress+'</td><td>'+getInfo.fldactive+'</td><td>'+getInfo.fldpaiddebit+'</td><td>'+getInfo.fldleftcredit+'</td><td>'+tot+'</td></tr>');
			num++;
		});
        $("#total_fldpaiddebit").val(paid_dabit);
        $("#total_fldleftcredit").val(left_credit);
        $("#total_credit_balance").val(total_balance);
    });
}

$(document).on("click", "#get_all_supplier_info_list tr", function(){
	var suppname = $(this).attr('rel');
	var id = $(this).attr('rel2');
	$.get('supplier-info/get-supplier-info?suppname=' + suppname, function(data) {
		$("#get_supplier_name").val(null);
		$("#get_supplier_address").val(null);
		$("#get_supplier_phone").val(null);
		$("#get_contact_name").val(null);
		$("#get_credit_day").val(null);
		$("#get_starting_date").val(null);
		$("#get_contact_phone").val(null);
		$('option:selected', 'select[name="payment_method"]').removeAttr('selected');
		$('option:selected', 'select[name="active_status"]').removeAttr('selected');
		$("#supplier-fldpaiddebit").val(null);
		$("#supplier-fldleftcredit").val(null);

        $("#get_supplier_name").val(data.fldsuppname);
		$("#get_supplier_address").val(data.fldsuppaddress);
		$("#get_supplier_phone").val(data.fldsuppphone);
		$("#get_contact_name").val(data.fldcontactname);
		$("#get_credit_day").val(data.fldcreditday);
		$("#get_starting_date").val(data.fldstartdate);
		$("#get_contact_phone").val(data.fldcontactphone);
		$('select[name="payment_method"]').find('option[value="'+data.fldpaymentmode+'"]').attr("selected",true);
		$('select[name="active_status"]').find('option[value="'+data.fldactive+'"]').attr("selected",true);

		$("#delete_supplier_info").attr('rel', id);
		$("#supplier-fldpaiddebit").val(data.fldpaiddebit);
		$("#supplier-fldleftcredit").val(data.fldleftcredit);

    });
});

// Edit Suppliers Info
$("#update_supplier_info").on("click", function(){
	var suppname = $("#get_supplier_name").val();
	var suppaddress = $("#get_supplier_address").val();
	var suppphone = $("#get_supplier_phone").val();
	var contactname = $("#get_contact_name").val();
	var startdate = $("#get_starting_date").val();
	var paymentmode = $("#get_payment_mode option:selected").val();
	var active = $("#get_active option:selected").val();
	var creditday = $("#get_credit_day").val();
	var contactphone = $("#get_contact_phone").val();
	var url = $(this).attr('url');
	var formData = {
        suppname: suppname,
        suppaddress: suppaddress,
        suppphone: suppphone,
        contactname: contactname,
        contactphone: contactphone,
        startdate: startdate,
        paymentmode: paymentmode,
        creditday: creditday,
        active: active
    };

    if(suppname == '')
    {
    	alert('Please Select Supplier To Update');
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
                getAllSupplierInfo();
            } else {
                showAlert(data.message);
            }
        }
    });
});

// Delete Suppliers Info
$("#delete_supplier_info").on("click", function(){
	var suppname = $("#get_supplier_name").val();
	var getRowId = $("#delete_supplier_info").attr('rel');

	// To Calculate Values At Buttom
	// var tot_debit = $("#total_fldpaiddebit").val();
 //    var tot_credit = $("#total_fldleftcredit").val();
 //    var tot_balance = $("#total_credit_balance").val();

    var deleted_debit = $("#supplier-fldpaiddebit").val();
    var deleted_credit = $("#supplier-fldleftcredit").val();
    // var deleted_balance = deleted_credit - deleted_debit;

	var url = $(this).attr('url');
	var formData = {
        suppname: suppname,
        paiddebit: deleted_debit,
        leftcredit: deleted_credit
    };

    if(suppname == '')
    {
    	alert('Please Select Supplier To Delete');
    	return false;
    }

    if (confirm("Are you sure? You Want To Delete " + suppname)) {
    	$.ajax({
    	    url: url,
    	    type: "POST",
    	    dataType: "json",
    	    data: formData,
    	    success: function (data) {
    	        if (data.status) {
    	            showAlert(data.message);
    	            // Remove Deleted Row
    	            $('.remove-supplier-'+getRowId).closest("tr").remove();
    	            // Re-calculated Buttom Inputs
    	            // var total_debit = tot_debit - deleted_debit;
    	            // var total_credit = tot_credit - deleted_credit;
    	            // var total_balance = tot_balance - deleted_balance;
    	            // $("#total_fldpaiddebit").val(total_debit);
    	            // $("#total_fldleftcredit").val(total_credit);
    	            // $("#total_credit_balance").val(total_balance);
    	        } else {
    	            showAlert(data.message);
    	        }
    	    }
    	});
    }
});