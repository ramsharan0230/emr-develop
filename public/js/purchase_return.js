$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$('#supplier').change( function () {
    var supplier = $('#supplier').val();
    if (supplier != '' ) {
        $.ajax({
            url: baseUrl + '/purchase-return/medicine-with-reference',
            type: "GET",
            data: {
                supplier: supplier,
            },
            dataType: "json",
            success: function (response) {
                if(response.status){
                    $('#medicine').empty().append(response.html);
                    $('#returnform').html(response.pendingStockReturns);
                    $('#batch').html("<option value=''>--Select--</option>");
                    $('#expiry').val("");
                    $('#carcost').val(0);
                    $('#netcost').val(0);
                    $('#qty').val(0);
                    $('#retqty').val(0);
                }else {
                    $('#medicine').empty().append('<option>Not availlable</option>');
                }
            }
        });
    }
});

// $('#supplier').change( function () {
//     var supplier = $('#supplier').val();
//     if (supplier != '' ) {
//         $.ajax({
//             url: baseUrl + '/purchase-return/reference',
//             type: "GET",
//             data: {
//                 supplier: supplier,
//             },
//             dataType: "json",
//             success: function (response) {
//                 if(response){
//                     $('#reference').empty().append(response);
//                     $('#route').val("");
//                     $('#medicine').html("<option value=''>--Select--</option>");
//                     $('#batch').html("<option value=''>--Select--</option>");
//                     $('#expiry').val("");
//                     $('#carcost').val(0);
//                     $('#netcost').val(0);
//                     $('#qty').val(0);
//                     $('#retqty').val(0);
//                 }else {
//                     $('#reference').empty().append('<option>Not availlable</option>');
//                 }

//             }
//         });
//     }
// })
$('#retqty').keyup( function () {
    let retqty = $(this).val();
    let vatamt = $('#vatamtclone').val();
    let disamt = $('#disamtclone').val();
    let cashdisamtclone = $('#cashdisamtclone').val();
    let totqty = $('#qty').val();
    let tax = (vatamt/totqty)*retqty;
    let dis = (disamt/totqty)*retqty;
    let cashdisamt = (cashdisamtclone/totqty)*retqty;
    $('#vatamt').val(tax);
    $('#disamt').val(dis);
    $('#cashdisamt').val(cashdisamt);
});
$('#reference').change( function () {
    var reference = $('#reference').val();
    var supplier = $('#supplier').val();
    $('#route').val("");
    $('#medicine').html("<option value=''>--Select--</option>");
    $('#batch').html("<option value=''>--Select--</option>");
    $('#expiry').val("");
    $('#carcost').val(0);
    $('#netcost').val(0);
    $('#qty').val(0);
    $('#retqty').val(0);
    $('#returnform').empty();
    if (supplier != '' && reference!='' ) {
        $.ajax({
            url: baseUrl + '/purchase-return/getPendingStockReturns',
            type: "GET",
            data: {
                supplier: supplier,
                reference:reference,
            },
            dataType: "json",
            success: function (response) {
                if(response.status){
                    $('#returnform').append(response.pendingStockReturns);
                }
            }
        });
    }
});

$('#medicine').change( function () {
    var reference = $("#medicine option:selected").attr("data-ref");
    var supplier = $('#supplier').val();
    var medicine = $('#medicine').val();
    var route = $("#medicine option:selected").attr("data-categ");
    if (medicine != '') {
        $.ajax({
            url: baseUrl + '/purchase-return/batch',
            type: "GET",
            data: {
                medicine: medicine,
                reference:reference,
                supplier:supplier,
                route:route,
            },
            dataType: "json",
            success: function (response) {
                if(response){
                    $('#batch').empty().append(response);
                    $('#expiry').val("");
                    $('#carcost').val(0);
                    $('#netcost').val(0);
                    $('#qty').val(0);
                    $('#retqty').val(0);
                    $('#bonusqty').val(0);
                    $('#bonusretqty').val(0);
                    $('#vatamt').val(0);
                    $('#disamt').val(0);
                    $('#cashdisamt').val(0);
                }else {
                    $('#batch').empty().append('<option>Not availlable</option>');
                }
                if(response.error){
                    showAlert(response.error,'error');
                }

            }
        });
    }
})

// $('#medicine').change( function () {
//     var reference = $('#reference').val();
//     var supplier = $('#supplier').val();
//     var medicine = $('#medicine').val();
//     var route = $('#route').val();
//     if (medicine != '') {
//         $.ajax({
//             url: baseUrl + '/purchase-return/batch',
//             type: "GET",
//             data: {
//                 medicine: medicine,
//                 reference:reference,
//                 supplier:supplier,
//                 route:route,
//             },
//             dataType: "json",
//             success: function (response) {
//                 if(response){
//                     $('#batch').empty().append(response);
//                     $('#expiry').val("");
//                     $('#carcost').val(0);
//                     $('#netcost').val(0);
//                     $('#qty').val(0);
//                     $('#retqty').val(0);
//                 }else {
//                     $('#batch').empty().append('<option>Not availlable</option>');
//                 }
//                 if(response.error){
//                     showAlert(response.error,'error');
//                 }

//             }
//         });
//     }
// })

$('#bonusretqty').change(function(){
    var bonusqty = $('#bonusqty').val();
    var bonusretqty = $(this).val();
    if( Number(bonusretqty) > Number(bonusqty)){
        showAlert('Return reward quantity cannot be more than Bonus Quntity','error');
        $(this).val(0);
        return false;
    }
});

$('#batch').change( function () {
    var batch = $('#batch').val();
    // var medicine = $('#medicine').val();
    // var route = $('#route').val();
    // var reference = $('#reference').val();

    var reference = $("#medicine option:selected").attr("data-ref");
    var medicine = $('#medicine').val();
    var route = $("#medicine option:selected").attr("data-categ");
    if (medicine != '' && batch !='') {
        $.ajax({
            url: baseUrl + '/purchase-return/expiry',
            type: "GET",
            data: {
                medicine: medicine,
                batch:batch,
                route:route,
                reference:reference,
            },
            dataType: "json",
            success: function (response) {
                if(response){
                    $('#expiry').empty().val(response.expiry);
                    $('#qty').empty().val(response.qty);
                    $('#bonusqty').empty().val(response.bonusqty);
                    $('#bonusretqty').empty().val(response.bonusqty);
                    $('#vatamt').empty().val(response.vatamt);
                    $('#disamt').empty().val(response.disamt);
                    $('#disamtclone').empty().val(response.disamt);
                    $('#cashdisamt').empty().val(response.fldcashdisc);
                    $('#cashdisamtclone').empty().val(response.fldcashdisc);
                    $('#vatamtclone').empty().val(response.vatamt);
                    var ccost = response.carrycost || 0;
                    if(Number(ccost) == 0){
                        $('#carcost').prop('readonly',true);
                    }else{
                        $('#carcost').prop('readonly',false);
                    }
                    $('#carcost').attr('data-maxcarrycost',Number(ccost));
                    $('#carcost').empty().val(Number(ccost));
                    $('#netcost').empty().val(Number(response.fldcost));
                    $('#stockNo').empty().val(response.fldstockno);
                }
                if(response.error){
                    showAlert(response.error,'error');
                }

            }
        });
    }else{
        $('#expiry').empty().val("");
        $('#qty').empty().val(0);
        $('#carcost').empty().val(0);
        $('#stockNo').empty().val("");
    }
})

$('#saveBtn').click(function () {
    var qty = $('#qty').val();
    var bonusqty = $('#bonusqty').val();
    var bonusretqty = $('#bonusretqty').val();
    var vatamt = $('#vatamt').val();
    var disamt = $('#disamt').val();
    var cashdisamt = $('#cashdisamt').val();
    var retqty = $('#retqty').val();
    var batch = $('#batch').val();
    var reference = $("#medicine option:selected").attr("data-ref");
    var route = $("#medicine option:selected").attr("data-categ");
    var medicine = $('#medicine').val();
    var stockNo = $('#stockNo').val();
    var supplier = $('#supplier').val();
    // var reference = $('#reference').val();
    // var route = $('#route').val();
    var carcost = $('#carcost').val();
    var netcost = $('#netcost').val();

    if(Number(qty) > 0){
        if( Number(retqty) <= 0){
            showAlert('Return quantity must be more than zero','error');
            return false;
        }
    }

    if(Number(bonusqty) > 0 && Number(qty) == 0){
        if(  Number(bonusretqty) == 0){
            showAlert('Return reward quantity must be more than zero','error');
            return false;
        }
    }

    if( Number(retqty) > Number(qty)){
        showAlert('Return quantity cannot be more than Quntity','error');
        return false;
    }

    if (retqty != '' || qty !='') {
        $.ajax({
            url: baseUrl + '/purchase-return/insertStockReturn',
            type: "POST",
            data: {
                medicine: medicine,
                batch:batch,
                qty:qty,
                bonusqty:bonusqty,
                bonusretqty:bonusretqty,
                vatamt:vatamt,
                disamt:disamt,
                cashdisamt:cashdisamt,
                retqty:retqty,
                stockNo:stockNo,
                supplier:supplier,
                reference:reference,
                route:route,
                carcost:carcost,
                netcost:netcost,
            },
            dataType: "json",
            success: function (response) {
                if(response.error){
                    showAlert(response.error,'error');
                    return;
                }
                if(response){
                    $('#qty').val(qty - retqty);
                    var ccost = $('#carcost').attr('data-maxcarrycost');
                    ccost = Number(ccost) - Number(carcost);
                    $('#carcost').attr('data-maxcarrycost',Number(ccost));
                    $('#route').val("");
                    $('#medicine').html("<option value=''>--Select--</option>");
                    $('#batch').html("<option value=''>--Select--</option>");
                    $('#expiry').val("");
                    $('#carcost').val(0);
                    $('#netcost').val(0);
                    $('#qty').val(0);
                    $('#bonusqty').val(0);
                    $('#bonusretqty').val(0);
                    $('#vatamt').val(0);
                    $('#disamt').val(0);
                    $('#cashdisamt').val(0);
                    $('#retqty').val(0);
                    $('#returnform').append(response);
                }


            }
        });
    }


})

$('#finalSave').click(function () {
    var returnIds = $('input[name="stockreturnid[]"]').map(function () { return $(this).val(); }).get();
    if(returnIds.length <= 0){
        showAlert('Please add an item first','error');
        return false;
    }

    var retqty = $('#retqty').val();
    var bonusqty = $('#bonusqty').val();
    var bonusretqty = $('#bonusretqty').val();
    var batch = $('#batch').val();
    var medicine = $('#medicine').val();
    var stockNo = $('#stockNo').val();
    var expiry = $('#expiry').val();
    var reference = $('#reference').val();
    var supplier = $('#supplier').val();
    var route = $('#route').val();
    if( retqty === null ||  typeof retqty=== undefined || retqty=== '')
    {
        showAlert('Enter return quantity','error');
        return false;
    }



    $.ajax({
        url: baseUrl + '/purchase-return/finalsave',
        type: "POST",
        data: {
            medicine: medicine,
            batch:batch,
            retqty:retqty,
            stockNo:stockNo,
            expiry:expiry,
            reference:reference,
            route:route,
            returnIds: returnIds,
        },
        dataType: "json",
        success: function (response) {

            if(response){
                $('#supplier').val("");
                $('#reference').html("<option value=''>--Select--</option>");
                $('#route').val("");
                $('#medicine').html("<option value=''>--Select--</option>");
                $('#batch').html("<option value=''>--Select--</option>");
                $('#expiry').val("");
                $('#carcost').val(0);
                $('#netcost').val(0);
                $('#qty').val(0);
                $('#bonusqty').val(0);
                $('#bonusretqty').val(0);
                $('#vatamt').val(0);
                $('#disamt').val(0);
                $('#cashdisamt').val(0);
                $('#retqty').val(0);
                $('#returnform').empty();
                $('#carcost').prop('readonly',false);
                $('#js-stockreturn-input').val(response.stockReturnReference)
                showAlert('Purchase Returned successfully.');
                $('#export_button').trigger('click');
                //    showAlert(response);
                   location.reload(true);
            }
            if(response.error){
                showAlert(response.error,'error');
            }

        }
    });
})

$('#route').change( function () {
    var route = $(this).val();
    var reference = $('#reference').val();
    var supplier = $('#supplier').val();

    if (supplier != '' && reference!='' && route !='' ) {
        $.ajax({
            url: baseUrl + '/purchase-return/medicine',
            type: "GET",
            data: {
                supplier: supplier,
                reference:reference,
                route:route,
            },
            dataType: "json",
            success: function (response) {
                if(response){
                    $('#medicine').empty().append(response);
                    $('#batch').html("<option value=''>--Select--</option>");
                    $('#expiry').val("");
                    $('#carcost').val(0);
                    $('#netcost').val(0);
                    $('#bonusqty').val(0);
                    $('#bonusretqty').val(0);
                    $('#vatamt').val(0);
                    $('#cashdisamt').val(0);
                    $('#disamt').val(0);
                    $('#qty').val(0);
                    $('#retqty').val(0);
                }else {
                    $('#medicine').empty().append('<option>Not availlable</option>');
                }
                if(response.error){
                    showAlert(response.error,'error');
                }

            }
        });
    }else {
        showAlert('Please select supplier and reference','error');
    }
});

$('#export_button').click( function () {
    var url = baseUrl + '/purchase-return/export-report?fldnewreference=' + $('#js-stockreturn-input').val();
    window.open(url, '_blank');
})

function deleteentry(stockreturnid){
    if (confirm('Are you sure to delete?')) {
        $.ajax({
            url: baseUrl + "/purchase-return/delete",
            type: "POST",
            data: {
                fldid: stockreturnid
            },
            dataType: "json",
            success: function (response) {
                var status = (response.status) ? 'success' : 'fail';
                if (response.status){
                    $('#returnform tr[data-fldid="' + stockreturnid + '"]').remove();
                }
                $('#route').val("");
                $('#medicine').html("<option value=''>--Select--</option>");
                $('#batch').html("<option value=''>--Select--</option>");
                $('#expiry').val("");
                $('#carcost').val(0);
                $('#netcost').val(0);
                $('#bonusqty').val(0);
                $('#bonusretqty').val(0);
                $('#vatamt').val(0);
                $('#cashdisamt').val(0);
                $('#disamt').val(0);
                $('#qty').val(0);
                $('#retqty').val(0);
                showAlert(response.message, status);
                location.reload(true);
            }
        });
    }
}

$(document).on('blur','#carcost',function(){
    var returncarryvost = Number($(this).val() || 0);
    var maxcarrycost = Number($('#carcost').attr('data-maxcarrycost'));
    if(returncarryvost > maxcarrycost || returncarryvost < 0){
        $("#carcost").val(maxcarrycost);
        showAlert('Carry Cost cannot be greater than '+maxcarrycost+'.', 'error');
    }
});

