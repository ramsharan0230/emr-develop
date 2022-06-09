
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});


$('#btnsearch_stockrate').on('click',function(){

    
    $('#stockratelist').empty();
    getItems();

});

$('#btnsearch_meds').on('click',function(){

    
    $('#medicinelist').empty();
    getMedicineItems();

});

$('#arrow-right').on('click',function(){

    var stocknamearr = [];
    var medbrandarr = [];
    var stocknamebrand = [];

    $("input:checkbox[name='stockname']:checked").each(function(){
        stocknamearr.push($(this).val());
    });

    $("input:checkbox[name='stocknamebrand']:checked").each(function(){
        stocknamebrand.push($(this).val());
    });


    var stockname = $('input[name="stockname"]:checked').val();

    $("input:checkbox[name='medbrand']:checked").each(function(){
        medbrandarr.push($(this).val());
    });

    var billingmode = $('.billingmode').val();
    var itemtype = $("input:radio[name='search_type']:checked").val();


    if(stocknamearr.length > 0 && stocknamebrand.length > 0){

        $.ajax({

            url: baseUrl + '/account/inventoryItem/deletehiitem',
            type: "GET",
            data: {
                stocknamearr: stocknamearr,
                medbrandarr: medbrandarr,
                billingmode: billingmode,
                stocknamebrand: stocknamebrand,
                itemtype: itemtype,
            },
            success: function(response){
                if(response.status == true){
                    showAlert(response.message);
                    $('#stockratelist').empty();
                    getItems();
                }else{
                    showAlert(response.message,'error');
                }
                
            }
    
        });

    }else{
        alert('Select Items From Particulars!')
    }
    


});

$('#arrow-left').on('click',function(){

    var stocknamearr = [];
    var medbrandarr = [];

    $("input:checkbox[name='stockname']:checked").each(function(){
        stocknamearr.push($(this).val());
    });

    var stockname = $('input[name="stockname"]:checked').val();

    $("input:checkbox[name='medbrand']:checked").each(function(){
        medbrandarr.push($(this).val());
    });

    var billingmode = $('.billingmode').val();
    var itemtype = $("input:radio[name='search_type']:checked").val();


    if(stocknamearr.length > 0 && medbrandarr.length > 0){

        $.ajax({

            url: baseUrl + '/account/inventoryItem/savehiitem',
            type: "GET",
            data: {
                stocknamearr: stocknamearr,
                medbrandarr: medbrandarr,
                billingmode: billingmode,
                itemtype: itemtype,
            },
            success: function(response){
                if(response.status == true){
                    showAlert(response.message);
                    $('#stockratelist').empty();
                    getItems();
                }else{
                    showAlert(response.message,'error');
                }
                
            }
    
        });

    }else{
        alert('Select Items from both side!')
    }
    

});

$(".unauthorised").click(function () {
    permit_user = $(this).attr('permit_user');
    showAlert('Authorization with  '+permit_user);
});

$('#js-inventory-refresh-btn').click(function() {
    getItems();
    getMedicineItems();
});

$('#js-inventory-bill-mode-select').change(function() {
    getItems();
    getMedicineItems();
});

// $(document).ready(function(){
//     $('input[type="radio"][name="search_type"]').change(function() {
//         alert('test');
//         getMedicineItems();
//     });
// });


$('.custom-radio').click(function(){
    // console.log($(this).find('#js-itemtype').val());
    var itemtype = $(this).find('#js-itemtype').val();
    $('#search_meds').val('');
    getMedicineItems(itemtype);
});


$('#search_stockrate').keyup(function(e){

    if (e.keyCode == 8 && $(this).val() == '') {
        getItems();
       
    }else if(e.keyCode == 13){
        getItems();
    }


});

$('#search_meds').keyup(function(e){

    if (e.keyCode == 8 && $(this).val() == '') {
        getMedicineItems();
       
    }else if(e.keyCode == 13){
        getMedicineItems();
    }

});


function getItems() {

    var search_stockrate = $('#search_stockrate').val();
    $.ajax({
        url: baseUrl + '/account/inventoryItem/getItems',
        type: "GET",
        data: {fldbillingmode: $('#js-inventory-bill-mode-select').val() , search_stockrate: search_stockrate},
        success: function (data) {
            // var trData = '';
            // $.each(data, function(i, d) {
            //     trData += getTrData(d, i);
            // });

            $('#stockratelist').html(data);
        }
    });
}

$(document).on('click', '.stockratepaginate .pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
   
    searchstockratepaginate(page);
});


function searchstockratepaginate(page) {

    var url = baseUrl + '/account/inventoryItem/getItems';
    var search_stockrate = $('#search_stockrate').val();

    if (page !== undefined) {
        url = url + "?page=" + page
    }

    $.ajax({
        url: url,
        type: "GET",
        data: {fldbillingmode: $('#js-inventory-bill-mode-select').val() , search_stockrate: search_stockrate},
        success: function (response) {

            $('#stockratelist').empty();
            $('#stockratelist').html(response);
  

        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

$(document).on('click', '.medicinepaginate .pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
   
    searchmedspaginate(page);
});

// $(document).on('click', '.medicinepaginate .pagination a', function (event) {
//     event.preventDefault();
//     var page = $(this).attr('href').split('page=')[1];
//     alert(meds);
//     searchmedspaginate(page);
// });

function searchmedspaginate(page) {

    var url = baseUrl + '/account/inventoryItem/getMedicineItem';
    $medtype = $('input[name="search_type"]:checked').val();
    var search_meds = $('#search_meds').val();

    if (page !== undefined) {
        url = url + "?page=" + page
    }

    $.ajax({
        url: url,
        type: "GET",
        // data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
        data: {fldbillingmode: $('#js-inventory-bill-mode-select').val(),medtype:$medtype, search_meds: search_meds},
        success: function (response) {

            $('#medicinelist').empty();
            $('#medicinelist').html(response);
  

        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function getMedicineItems(itemtype) {

    if(itemtype){
        $medtype = itemtype;
    }else{
        $medtype = $('input[name="search_type"]:checked').val();
    }
    
    var search_meds = $('#search_meds').val();

 

    $.ajax({
        url: baseUrl + '/account/inventoryItem/getMedicineItem',
        type: "GET",
        data: {fldbillingmode: $('#js-inventory-bill-mode-select').val(),medtype:$medtype, search_meds: search_meds},
        success: function (data) {
            // var trData = '';
            // $.each(data, function(i, d) {
            //     trData += getTrData(d, i);
            // });

            $('#medicinelist').html(data);
        }
    });
}

$(document).on('click', '#js-inventory-item-tbody tr', function () {
    selected_td('#js-inventory-item-tbody tr', this);

    $('input[name="flditemname"]').val($(this).attr('flditemname'));
    $('input[name="fldrate"]').val($(this).attr('fldrate'));
    $('input[name="fldcategory"]').val($(this).attr('fldcategory'));
    $('select[name="fldbillingmode"]').val($(this).attr('fldbillingmode'));
});

$('#js-inventory-save-btn').click(function() {
    $.ajax({
        url: baseUrl + '/account/inventoryItem/saveUpdate',
        type: "POST",
        data: $('#js-inventory-add-form').serialize(),
        success: function (response) {
            if (response.status) {
                $('#js-inventory-add-form')[0].reset();

                var trData = getTrData(response.data, $('#js-inventory-item-tbody tr').length);
                $('#js-inventory-item-tbody').append(trData);
            }

            showAlert(response.message);
        }
    });
});

function getTrData(data, length) {
    var trData = '<tr flditemname="' + data.flditemname + '" fldbillingmode="' + data.fldbillingmode + '" fldcategory="' + data.fldcategory + '" flddrug="' + data.flddrug + '" fldstockid="' + data.fldstockid + '" fldrate="' + data.fldrate + '" fldid="' + data.fldid + '">';
    trData += '<td>' + (length +1) + '</td>';
    trData += '<td>' + data.flditemname + '</td>';
    trData += '<td>' + data.fldbillingmode + '</td>';
    trData += '<td>' + (data.flddrug ? data.flddrug : '') + '</td>';
    trData += '<td>' + (data.fldstockid ? data.fldstockid : '') + '</td>';
    trData += '<td>' + data.fldrate + '</td></tr>';

    return trData;
}

$('#js-inventory-update-btn').click(function() {
    var selectedTd = $('#js-inventory-item-tbody tr[is_selected="yes"]');
    var postData = $('#js-inventory-add-form').serialize() + '&fldid=' + $(selectedTd).attr('fldid');
    $.ajax({
        url: baseUrl + '/account/inventoryItem/saveUpdate',
        type: "POST",
        data: postData,
        success: function (response) {
            if (response.status) {
                $('#js-inventory-add-form')[0].reset();
                $(selectedTd).attr('flditemname', response.data.flditemname);
                $(selectedTd).attr('fldbillingmode', response.data.fldbillingmode);
                $(selectedTd).attr('flddrug', response.data.flddrug);
                $(selectedTd).attr('fldstockid', response.data.fldstockid);
                $(selectedTd).attr('fldrate', response.data.fldrate);

                $(selectedTd).find('td:nth-child(2)').text(response.data.flditemname);
                $(selectedTd).find('td:nth-child(3)').text(response.data.fldbillingmode);
                $(selectedTd).find('td:nth-child(4)').text((response.data.flddrug ? response.data.flddrug : ''));
                $(selectedTd).find('td:nth-child(5)').text((response.data.fldstockid ? response.data.fldstockid : ''));
                $(selectedTd).find('td:nth-child(6)').text(response.data.fldrate);

            }

            showAlert(response.message);
        }
    });
});

$('#js-inventory-delete-btn').click(function() {
    var selectedTd = $('#js-inventory-item-tbody tr[is_selected="yes"]');
    $.ajax({
        url: baseUrl + '/account/inventoryItem/delete',
        type: "POST",
        data: {fldid: $(selectedTd).attr('fldid')},
        success: function (response) {
            if (response.status)
                $(selectedTd).remove();
            showAlert(response.message);
        }
    });
});



/*

*/

$('#js-inventory-route-select').change(function() {
    $.ajax({
        url: baseUrl + '/account/inventoryItem/getMedicines',
        type: "POST",
        data: {drug: $(this).val()},
        success: function (data) {
            var options = '<option value="">-- Select --</option>';
            // $.each(data, function(i, d) {
            //     options += '<option value="' + d.col + '" data-category="' + d.fldcategory + '">' + d.col + '</option>';
            // });
            // $('#js-inventory-medicine-input').html(options);

            $("#js-inventory-medicine-input").html(data);
            $("#js-inventory-medicine-input").select2();
        }
    });
});

$('#js-inventory-medicine-input').change(function() {
    $.ajax({
        url: baseUrl + '/account/inventoryItem/getBrandName',
        type: "GET",
        data: {fldbrandid: $(this).val()},
        success: function (data) {
            $('#js-inventory-category-input').val($('#js-inventory-medicine-input option:selected').data('category'));
            $('#js-inventory-brand-name-select').html('<option value="">-- Select --</option><option value="' + data.fldbrand + '">' + data.fldbrand + '</option>');
        }
    });
});


$('#export').on('click',function(){

    var billingmode = $('#js-inventory-bill-mode-select').val();
    var value = $('#export').val();

    if(billingmode){

        var urlReport = baseUrl + "/account/inventoryItem/exportStockRate?billingmode=" + billingmode + "&value=" + value + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport);

    }else{
        alert('Please Select Billing Mode!');
    }
    
});

$('#mapitem').on('click',function(){

    var billingmode = $('#js-inventory-bill-mode-select').val();
    var value = $('#mapitem').val();


    if(billingmode){

        var urlReport = baseUrl + "/account/inventoryItem/exportStockRate?billingmode=" + billingmode + "&value=" + value + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport);

    }else{
        alert('Please Select Billing Mode!');
    }
    
});
