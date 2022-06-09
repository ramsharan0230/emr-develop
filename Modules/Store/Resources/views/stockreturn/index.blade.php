@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Stock Return
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Supplier</label>
                                <select class="form-control markreadonly select2" name="fldsuppname"
                                        id="js-purchaseentry-supplier-select">
                                    <option value="">--Select--</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->fldsuppname }}"
                                                data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Address</label>
                                <input type="text" id="js-purchaseentry-address-input" class="form-control" readonly>
                            </div>
{{--                            <div class="col-sm-2">--}}
{{--                                <label>Ref Order No</label>--}}
{{--                                <select class="form-control markreadonly" name="fldreference"--}}
{{--                                        id="reference">--}}
{{--                                    <option value="">--Select--</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-2">--}}
{{--                                <label class="col-sm-3">Route</label>--}}
{{--                                <select id="route" class="form-control" name="route">--}}
{{--                                    <option value="">--Select--</option>--}}
{{--                                    <option value="Medicines">Medicines</option>--}}
{{--                                    <option value="Surgicals">Surgicals</option>--}}
{{--                                    <option value="Extra Items">Extra Items</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-2">--}}
{{--                                <label class="col-sm-4">Particulars</label>--}}
{{--                                <select id="medicine" class="form-control" name="medicine">--}}
{{--                                    <option value="">--Select--</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div class="col-sm-2">
                                <label class="col-sm-4">Particulars</label>
                                <select id="medicine" class="form-control select2" name="medicine">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-3">Batch</label>
                                <select id="batch" class="form-control select2" name="batch">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            {{-- <input type="hidden" name="stockNo" id="stockNo" value=""> --}}
                            <div class="col-sm-2">
                                <label class="col-sm-5">Expiry</label>
                                <input readonly type="text" id="expiry" name="expiry" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <label class="">Quantity</label>
                                <input readonly type="text" id="qty" class="form-control" name="qty" placeholder="0">
                            </div>
                            <div class="col-sm-2">
                                <label>Return Quantity</label>
                                <input type="number" id="retqty" class="form-control" name="retqty" placeholder="0" onkeydown="if(event.key==='.'){event.preventDefault();}">
                            </div>
                            <div class="col-sm-2">
                                <label class="">Net Cost</label>
                                <input readonly type="text" id="netcost" class="form-control" name="netcost" placeholder="0">
                            </div>
                            <div class="col-sm-2">
                                <label>Reason</label>
                                <select id="reason" class="form-control select2" name="reason">
                                    <option value="">--Select--</option>
                                    <option value="Expired Items">Expired Items</option>
                                    <option value="Near Expiry">Near Expiry</option>
                                    <option value="Broken Items">Broken Items</option>
                                    <option value="Not Ordered">Not Ordered</option>
                                    <option value="QTY Error">QTY Error</option>
                                    <option value="Other Category">Other Category</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary btn-sm-in mt-4" id="saveBtn" title="Save"><i class="fa fa-save" aria-hidden="true"></i></button>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="table-responsive table-container">
                                        <table class="table table-bordered table-hover table-striped ">
                                            <thead class="thead-light">
                                            <tr>
                                                <th><input type="checkbox" id="js-stockreturn-selectall-id-checkbox"></th>
                                                <th>Category</th>
                                                <th>Particulars</th>
                                                <th>Batch</th>
                                                <th>Expiry</th>
                                                <th>QTY</th>
                                                <th>Cost</th>
                                                <th>Vendor</th>
                                                <th>Ref No</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="purchase_tbody"></tbody>
                                        </table>
                                        <div id="bottom_anchor"></div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <label class="">Stock Return Reference No</label>
                                    <input type="text" id="js-stockreturn-input">
                                    <button class="btn btn-action btn-warning" id="exportStockReturn"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
                                    <button class="btn btn-action btn-primary" id="finalSave"><i class="fa fa-check"></i>&nbsp;&nbsp;Final Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push("after-script")
    <script>
        $(document).on('change', '#js-purchaseentry-supplier-select', function () {
            var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
            var supplier = $('#js-purchaseentry-supplier-select option:selected').val();
            $('#js-purchaseentry-address-input').val($(selectedOption).data('fldsuppaddress'));

            if (supplier != '' ) {
                $.ajax({
                    url: baseUrl + '/inventory/stockreturn/medicine-with-reference',
                    type: "GET",
                    data: {
                        fldsuppname: supplier,
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.status){
                            $('#medicine').empty().append(response.html);
                            $('#returnform').append(response.pendingStockReturns);
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

        // $('#medicine').change( function () {
        //     var reference = $('#reference').val();
        //     var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
        //     var medicine = $('#medicine').val();
        //     var route = $('#route').val();
        //     if (medicine != '') {
        //         $.ajax({
        //             url: baseUrl + '/inventory/stockreturn/batch',
        //             type: "GET",
        //             data: {
        //                 medicine: medicine,
        //                 reference:reference,
        //                 fldsuppname: $(selectedOption).val(),
        //                 route:route,
        //             },
        //             dataType: "json",
        //             success: function (response) {
        //                 if(response){
        //                     $('#batch').empty().append(response);
        //                     $('#expiry').empty().val('');
        //                     $('#qty').empty().val('');
        //                     $('#netcost').empty().val('');
        //                     $('#retqty').empty().val('');
        //                 }else {
        //                     $('#batch').empty().append('<option>Not availlable</option>');
        //                 }
        //                 if(response.error){
        //                     showAlert(response.error,'error');
        //                 }
        //
        //             }
        //         });
        //     }
        // })

        $('#medicine').change( function () {
            var reference = $("#medicine option:selected").attr("data-ref");
            var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
            var medicine = $('#medicine').val();
            var route = $("#medicine option:selected").attr("data-categ");
            if (medicine != '') {
                $.ajax({
                    url: baseUrl + '/inventory/stockreturn/batch',
                    type: "GET",
                    data: {
                        medicine: medicine,
                        reference:reference,
                        fldsuppname: $(selectedOption).val(),
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

        // $('#batch').change( function () {
        //     var batch = $('#batch').val();
        //     var medicine = $('#medicine').val();
        //     var route = $('#route').val();
        //     if (medicine != '' || batch !='') {
        //         $.ajax({
        //             url: baseUrl + '/inventory/stockreturn/expiry',
        //             type: "GET",
        //             data: {
        //                 medicine: medicine,
        //                 batch:batch,
        //                 route:route,
        //             },
        //             dataType: "json",
        //             success: function (response) {
        //                 if(response){
        //                     $('#expiry').empty().val(response.expiry);
        //                     $('#qty').empty().val(response.qty);
        //                     $('#netcost').empty().val(response.netcost);
        //                     // $('#stockNo').empty().val(response.expiry.fldstockno);
        //                 }
        //                 if(response.error){
        //                     showAlert(response.error,'error');
        //                 }
        //
        //             }
        //         });
        //     }
        // })

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
                    url: baseUrl + '/inventory/stockreturn/expiry',
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
                            $('#netcost').empty().val(Number(response.fldcost));
                            $('#purchase_tbody').empty().append(response.html);
                            // $('#stockNo').empty().val(response.fldstockno);
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }

                    }
                });
            }else{
                $('#expiry').empty().val("");
                $('#qty').empty().val(0);
            }
        })

        $('#route').change( function () {
            var route = $(this).val();
            var reference = $('#reference').val();
            var selectedOption = $('#js-purchaseentry-supplier-select option:selected');

            if (selectedOption != '' && reference!='' && route !='' ) {
                $.ajax({
                    url: baseUrl + '/inventory/stockreturn/medicine',
                    type: "GET",
                    data: {
                        fldsuppname: $(selectedOption).val(),
                        reference:reference,
                        route:route,
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response){
                            $('#medicine').empty().append(response);
                            $('#batch').empty().append('<option>--Select--</option>');
                        }else {
                            $('#medicine').empty().append('<option>Not availlable</option>');
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }
                        $('#expiry').val("");
                        $('#qty').val("");
                        $('#retqty').val("");
                        $('#netcost').val('');
                    }
                });
            }else {
                showAlert('Please select supplier and reference','error');
            }
        });

        $(document).on('change','#reference',function(){
            if($(this).val() != ""){
                $.ajax({
                    url: "{{ route('inventory.stock-return.get.pendingStockreturns') }}",
                    type: "GET",
                    data: {
                            reference: $('#reference').val(),
                        },
                    success: function (data) {
                        $('#purchase_tbody').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
            clearData();
        });

        function clearData(){
            $('#route').prop('selectedIndex',0);
            $('#medicine').empty().append('<option>--Select--</option>');
            $('#batch').empty().append('<option>--Select--</option>');
            $('#expiry').val("");
            $('#qty').val("");
            $('#netcost').val('');
            $('#retqty').val("");
        }

        // $("#retqty").on('blur', function () {
        //     var retqty = Number($(this).val() || 0);
        //     var qty = Number($('#qty').val() || 0);
        //     if(retqty > qty){
        //         $("#retqty").val(qty);
        //         showAlert('Quantity cannot be greater than '+$('#qty').val()+'.', 'fail');
        //     }
        // });

        $('#saveBtn').click(function () {
            var qty = Number($('#qty').val() || 0);
            var netcost = Number($('#netcost').val() || 0);
            var retqty = Number($('#retqty').val() || 0);
            if(retqty > qty){
                showAlert('Quantity cannot be greater than '+$('#qty').val()+'.', 'fail');
                return;
            }
            var batch = $('#batch').val();
            var medicine = $('#medicine').val();
            // var stockNo = $('#stockNo').val();
            var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
            var reference = $("#medicine option:selected").attr("data-ref");
            var route = $("#medicine option:selected").attr("data-categ");
            var reason = $('#reason').val();
            if(selectedOption.val() == "" || reference == "" || route == "" || medicine == "" || batch == "" || qty == 0 || retqty == 0 || reason == ""){
                showAlert("Please fill all data first","error");
                return false;
            }
            if( retqty === null ||  typeof retqty=== undefined || retqty=== '')
            {
                showAlert('Enter return quantity','error');
                return false;
            }
            if( retqty > qty){
                showAlert('Return quantity cannot be more than Quntity','error');
                return false;
            }
            const data = {
                medicine: medicine,
                batch:batch,
                qty:qty,
                retqty:retqty,
                // stockNo:stockNo,
                fldsuppname: $(selectedOption).val(),
                reference:reference,
                route:route,
                reason: reason,
                netcost: netcost,
            }
            if (retqty != '' || qty !='') {
                $.ajax({
                    url: baseUrl + '/inventory/stockreturn/save-stock-return',
                    type: "POST",
                    data: data,
                    success: function (response) {
                        if(response){
                            $('#qty').val(qty - retqty);
                            $('#purchase_tbody').empty().append(response);
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }

                    }
                });
            }


        })

        $(document).on('click','#finalSave',function(){
            var returnids = [];
            var reference = $("#medicine option:selected").attr("data-ref");
            var itemData = $.map($('#purchase_tbody tr'), function(trElem, i) {
                if($(trElem).find('.js-stockreturn-select-class-checkbox').hasClass("selected")){
                    var elemVal = $(trElem).find('.js-stockreturn-select-class-checkbox').data('fldid');
                    var items = elemVal.toString().split(',');
                    $.each(items, function(i, item) {
                        returnids.push(item);
                    });
                }
            });
            if(!(returnids.length > 0)){
                alert("Please select row first!");
                return false;
            }
            $.ajax({
                url: baseUrl + '/inventory/stockreturn/save-final',
                type: "POST",
                data: {
                    returnids: returnids,
                    reference: reference
                },
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        $('#purchase_tbody').empty().append(response.html);
                        $('#js-stockreturn-input').val(response.fldnewreference)
                        // $('#js-purchaseentry-address-input').val("");
                        // $('#js-purchaseentry-supplier-select').prop('selectedIndex',0).change();
                        // $('#reference').empty().append(response);
                        clearData();
                        showAlert('Stock returned successfully.');
                        $('#exportStockReturn').trigger('click');
                    }else{
                        showAlert('An error has occured!','error');
                    }
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        });


        // $('#finalSave').click(function () {
        //     if($("#purchase_tbody tr").length < 1){
        //         showAlert('Please add an item first','error');
        //         return false;
        //     }

        //     var retqty = $('#retqty').val();
        //     var batch = $('#batch').val();
        //     var medicine = $('#medicine').val();
        //     var stockNo = $('#stockNo').val();
        //     var expiry = $('#expiry').val();
        //     var reference = $('#reference').val();
        //     // var selectedOption = $('#js-purchaseentry-supplier-select option:selected');
        //     var route = $('#route').val();
        //     if( retqty === null ||  typeof retqty=== undefined || retqty=== '')
        //     {
        //         showAlert('Enter return quantity','error');
        //         return false;
        //     }

        //     $.ajax({
        //         url: baseUrl + '/inventory/stockreturn/save-final',
        //         type: "POST",
        //         data: {
        //             medicine: medicine,
        //             batch:batch,
        //             retqty:retqty,
        //             stockNo:stockNo,
        //             expiry:expiry,
        //             reference:reference,
        //             route:route,
        //         },
        //         dataType: "json",
        //         success: function (response) {

        //             if(response){
        //                 showAlert(response);
        //             }
        //             if(response.error){
        //                 showAlert(response.error,'error');
        //             }

        //         }
        //     });
        // })

        $(document).on('change','#js-stockreturn-selectall-id-checkbox',function(){
            if($(this).is(":checked")){
                $('.js-stockreturn-select-class-checkbox').prop('checked','checked');
                $.each($('.js-stockreturn-select-class-checkbox'), function(i, option) {
                    if(!$(option).hasClass('selected')){
                        $(option).addClass('selected');
                    }
                });
            }else{
                $('.js-stockreturn-select-class-checkbox').prop('checked','');
                $.each($('.js-stockreturn-select-class-checkbox'), function(i, option) {
                    if($(option).hasClass('selected')){
                        $(option).removeClass('selected');
                    }
                });
            }
        });

        $(document).on('change','.js-stockreturn-select-class-checkbox',function(){
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

        $(document).on('click','#exportStockReturn',function(){
            var url = baseUrl + '/inventory/stockreturn/exportpdfreprint?fldnewreference=' + $('#js-stockreturn-input').val();
            window.open(url, '_blank');
        });

        function deleteentry(currelem){
            if (confirm('Are you sure to delete?')) {
                var stockreturnids = $(currelem).closest('tr').attr('data-fldid');
                $.ajax({
                    url: baseUrl + "/inventory/stockreturn/delete",
                    type: "POST",
                    data: {
                        fldids: stockreturnids
                    },
                    dataType: "json",
                    success: function (response) {
                        var status = (response.status) ? 'success' : 'fail';
                        if (response.status){
                            $(currelem).closest('tr').remove();
                            // $('#purchase_tbody tr[data-fldid="' + stockreturnid + '"]').remove();
                        }
                        $('#route').val("");
                        $('#medicine').html("<option value=''>--Select--</option>");
                        $('#batch').html("<option value=''>--Select--</option>");
                        $('#expiry').val("");
                        // $('#carcost').val(0);
                        $('#netcost').val(0);
                        $('#qty').val(0);
                        $('#retqty').val(0);
                        showAlert(response.message, status);
                    }
                });
            }
        }

    </script>
@endpush

