@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Stock Adjustment</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(Session::get('success_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('success_message') }}
                            </div>
                        @endif

                        @if(Session::get('error_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('error_message') }}
                            </div>
                        @endif
                        <form action="javascript:;">
                            @csrf
                            <div class="row">
                                <div class="col-12 form-row form-group">
                                    <label class="col-lg-1 col-sm-1">
                                        Reason
                                    </label>
                                    <div class="col-lg-2 col-sm-2">
                                        <input type="text" class="form-control" id="reason" placeholder="Enter Reason">
                                    </div>
                                    <div class="col-lg-2 col-sm-4">
                                        <input type="radio" name="type-generic-brand" id="type-generic">
                                        <label for="type-generic">Generic</label>&nbsp;&nbsp;

                                        <input type="radio" name="type-generic-brand" id="type-brand" checked>
                                        <label for="type-brand">Brand</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 form-row">
                                    <div class="col-sm-2">
                                        <label>Route</label>
                                        <select name="route" id="route" class=" form-control">
                                            <option value="">--Select--</option>
                                            <option value="Medicines">Medicines</option>
                                            <option value="Surgicals">Surgicals</option>
                                            <option value="Extra Items">Extra Items</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Particulars</label>
                                        <select name="medicine" id="medicine" class="form-control select2">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Batch</label>
                                        <select name="batch" id="batch" class="form-control">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Expiry Date</label>
                                        <input type="text" id="expiry_date" class="form-control"
                                               value=" " name="expiry" readonly>
                                    </div>
                                    <div class="col-1">
                                        <label>Qty</label>
                                        <input type="text" name="quantity" id="quantity" class="form-control" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label>Adjustment Qty</label>
                                        <input type="text" name="adjustqty" id="adjustqty" class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                        <input type="hidden" name="category" id="category">
                                    </div>
                                    <div class="col-1">
                                        <a href="javascript:;" class="btn btn-sm btn-primary mt-4"
                                           onclick="stockAdjustment.addStockAdjustment()"><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-container tablefixedHeight">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th"><input type="checkbox" id="js-stockadjust-selectall-id-checkbox"></th>
                                    <th class="tittle-th">Category</th>
                                    <th class="tittle-th">Particulars</th>
                                    <th class="tittle-th">Batch</th>
                                    <th class="tittle-th">Expiry</th>
                                    <th class="tittle-th">QTY</th>
                                    <th class="tittle-th">Cost</th>
                                </tr>
                                </thead>
                                <tbody id="stock-adjustment-dynamic-views">
                                    @if($stockAdjusted)
                                        @forelse($stockAdjusted as $stock)
                                            <tr data-fldid="{{$stock->fldids}}">
                                                <td>
                                                    <input type="checkbox" class="js-stockadjust-select-class-checkbox" data-fldid="{{$stock->fldids}}">
                                                </td>
                                                <td>{{ $stock->fldcategory }}</td>
                                                <td>{{ $stock->fldstockid }}</td>
                                                <td>{{ $stock->fldbatch }}</td>
                                                <td>{{ $stock->fldexpiry }}</td>
                                                <td>{{ $stock->fldcurrqty }}</td>
                                                <td>{{ $stock->fldnetcost }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                        <div class="form-group text-right">
                            <label class="">Stock Adjustment No</label>
                            <input type="text" id="js-stockadjustment-input">
                            <button class="btn btn-action btn-warning" id="exportStockAdjust"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
                            <button class="btn btn-action btn-primary" id="finalSave"><i class="fa fa-check"></i>&nbsp;&nbsp;Final Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('store::stockadjustment.authenticate-user')
@stop

@push('after-script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var stockAdjustment = {
            addStockAdjustment: function () {
                var reason = $('#reason').val();
                var route = $('#route').val();
                var selected_quantity = Number($('#quantity').val() || 0);
                var adjust_qty = Number($('#adjustqty').val() || 0);
                var flditem = $('#medicine').val();
                var batch = $('#batch').val();

                if (reason == "" || route == "" || flditem == "" || batch == "" || selected_quantity == "" || adjust_qty == "") {
                    showAlert('Please select all the data', 'error');
                    return false;
                }
                if(adjust_qty == 0){
                    showAlert('Adjustment qty must be greater than zero', 'error');
                    return false;
                }
                if (adjust_qty > selected_quantity) {
                    showAlert('Adjustment Qty cannot be greater than Qty', 'error');
                    return false;
                }

                if (!confirm('Are you sure?')) {
                    return false;
                }

                $('#submitType').val("Add");
                $('#authenticate-user').modal('show');

                // var data = {
                //     reason: reason,
                //     route: route,
                //     selected_quantity: selected_quantity,
                //     adjust_qty: adjust_qty,
                //     flditem: flditem,
                //     batch: batch
                // };
                // $.ajax({
                //     method: "POST",
                //     url: "{{ route('inventory.stock-adjustment.stock.medicine.add') }}",
                //     data: data
                // }).done(function (msg) {
                //     $('#route').prop('selectedIndex',0);
                //     $('#medicine').empty().append('<option>--Select--</option>');
                //     $('#batch').empty().append('<option>--Select--</option>');
                //     $('#expiry_date').val("");
                //     $('#quantity').val("");
                //     $('#adjustqty').val("");
                //     $("#stock-adjustment-dynamic-views").empty().append(msg.data);

                // }).fail(function (jqXHR, textStatus) {
                //     alert("Request failed: " + textStatus);
                // });
            }
        }

        $(document).on('click','#authenticate',function(){
            if($('#username').val() == "" || $('#password').val() == ""){
                showAlert("Please enter username and password!","error");
            }
            var data = {
                username: $('#username').val(),
                password: $('#password').val()
            };
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-adjustment.authenticate.user') }}",
                data: data
            }).done(function (response) {
                if(response.status){
                    if($('#submitType').val() == "Add"){
                        var reason = $('#reason').val();
                        var route = $('#route').val();
                        var selected_quantity = Number($('#quantity').val() || 0);
                        var adjust_qty = Number($('#adjustqty').val() || 0);
                        var flditem = $('#medicine').val();
                        var batch = $('#batch').val();

                        if (reason == "" || route == "" || flditem == "" || batch == "" || selected_quantity == "" || adjust_qty == "") {
                            showAlert('Please select all the data', 'error');
                            return false;
                        }
                        if(adjust_qty == 0){
                            showAlert('Adjustment qty must be greater than zero', 'error');
                            return false;
                        }
                        if (adjust_qty > selected_quantity) {
                            showAlert('Adjustment Qty cannot be greater than Qty', 'error');
                            return false;
                        }
                        var data = {
                            reason: reason,
                            route: route,
                            selected_quantity: selected_quantity,
                            adjust_qty: adjust_qty,
                            flditem: flditem,
                            batch: batch
                        };
                        $.ajax({
                            method: "POST",
                            url: "{{ route('inventory.stock-adjustment.stock.medicine.add') }}",
                            data: data
                        }).done(function (msg) {
                            $('#route').prop('selectedIndex',0);
                            $('#medicine').empty().append('<option>--Select--</option>');
                            $('#batch').empty().append('<option>--Select--</option>');
                            $('#expiry_date').val("");
                            $('#quantity').val("");
                            $('#adjustqty').val("");
                            $("#stock-adjustment-dynamic-views").empty().append(msg.data);
                            $('#username').val("");
                            $('#password').val("");
                            $('#submitType').val("");
                            $('#authenticate-user').modal('hide');
                        }).fail(function (jqXHR, textStatus) {
                            alert("Request failed: " + textStatus);
                        });
                    }else{
                        var adjustids = [];
                        var itemData = $.map($('#stock-adjustment-dynamic-views tr'), function(trElem, i) {
                            if($(trElem).find('.js-stockadjust-select-class-checkbox').hasClass("selected")){
                                var elemVal = $(trElem).find('.js-stockadjust-select-class-checkbox').data('fldid');
                                var items = elemVal.toString().split(',');
                                $.each(items, function(i, item) {
                                    adjustids.push(item);
                                });
                            }
                        });
                        if(!(adjustids.length > 0)){
                            alert("Please select row first!");
                            return false;
                        }
                        $.ajax({
                            url: baseUrl + '/inventory/stock-adjustment/stock-adjustment-save',
                            type: "POST",
                            data: {
                                adjustids: adjustids,
                                target: $('#target-select').val(),
                                isFinalSave: true
                            },
                            dataType: "json",
                            success: function (response) {
                                if(response.status){
                                    $('#stock-adjustment-dynamic-views').empty().append(response.html);
                                    $('#js-stockadjustment-input').val(response.fldreference)
                                    $('#route').prop('selectedIndex',0);
                                    $('#medicine').empty().append('<option>--Select--</option>');
                                    $('#batch').empty().append('<option>--Select--</option>');
                                    $('#expiry_date').val("");
                                    $('#quantity').val("");
                                    $('#adjustqty').val("");
                                    showAlert('Stock adjustment saved successfully.');
                                    $('#username').val("");
                                    $('#password').val("");
                                    $('#submitType').val("");
                                    $('#authenticate-user').modal('hide');
                                    $('#exportStockAdjust').trigger('click');
                                }else{
                                    showAlert('An error has occured!','error');
                                }
                            },
                            error: function (xhr, err) {
                                console.log(xhr);
                            }
                        });
                    }
                }else{
                    showAlert(response.msg,'error');
                }
            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        });

        $('#route').change(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-adjustment.change.route.stock') }}",
                data: {route: $('#route').val()}
            }).done(function (msg) {
                // $("#stock-adjustment-dynamic-views").append(msg.data);
                $("#medicine").empty().append(msg);

            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        })

        $('#medicine').change(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-adjustment.stock.medicine.details') }}",
                data: {particularSelect: $('#medicine').val()}
            }).done(function (msg) {
                // $("#stock-adjustment-dynamic-views").append(msg.data);
                $("#batch").empty().append(msg);

            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        })

        $('#batch').change(function () {
            if ($('#batch').val() == '')
                return false;
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-adjustment.stock.medicine.batch.change') }}",
                data: {
                    medicineSelect: $('#medicine').val(),
                    batch: $('#batch').val(),
                }
            }).done(function (msg) {
                $('#expiry_date').empty().val(msg.expiry);
                $('#quantity').empty().val(msg.qty);
            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        })

        $('#exportStockAdjust').click(function () {
            var url = baseUrl + '/inventory/stock-adjustment/export-report?fldreference=' + $('#js-stockadjustment-input').val();
            window.open(url, '_blank');
        })

        $("#adjustqty").on('blur', function () {
            var adjustqty = Number($(this).val() || 0);
            var qty = Number($('#quantity').val() || 0);
            if(adjustqty > qty){
                $("#adjustqty").val(qty);
                showAlert('Adjustment Quantity cannot be greater than '+$('#quantity').val()+'.', 'fail');
            }
        });

        $(document).on('click','#finalSave',function(){
            var adjustids = [];
            var itemData = $.map($('#stock-adjustment-dynamic-views tr'), function(trElem, i) {
                if($(trElem).find('.js-stockadjust-select-class-checkbox').hasClass("selected")){
                    var elemVal = $(trElem).find('.js-stockadjust-select-class-checkbox').data('fldid');
                    var items = elemVal.toString().split(',');
                    $.each(items, function(i, item) {
                        adjustids.push(item);
                    });
                }
            });
            if(!(adjustids.length > 0)){
                alert("Please select row first!");
                return false;
            }
            if (!confirm('Are you sure?')) {
                return false;
            }
            $('#submitType').val("FinalSave");
            $('#authenticate-user').modal('show');
            // $.ajax({
            //     url: baseUrl + '/inventory/stock-adjustment/stock-adjustment-save',
            //     type: "POST",
            //     data: {
            //         adjustids: adjustids,
            //         target: $('#target-select').val(),
            //         isFinalSave: true
            //     },
            //     dataType: "json",
            //     success: function (response) {
            //         if(response.status){
            //             $('#stock-adjustment-dynamic-views').empty().append(response.html);
            //             $('#js-stockadjustment-input').val(response.fldreference)
            //             $('#route').prop('selectedIndex',0);
            //             $('#medicine').empty().append('<option>--Select--</option>');
            //             $('#batch').empty().append('<option>--Select--</option>');
            //             $('#expiry_date').val("");
            //             $('#quantity').val("");
            //             $('#adjustqty').val("");
            //             showAlert('Stock adjustment saved successfully.');
            //             $('#exportStockAdjust').trigger('click');
            //         }else{
            //             showAlert('An error has occured!','error');
            //         }
            //     },
            //     error: function (xhr, err) {
            //         console.log(xhr);
            //     }
            // });
        });

        $(document).on('change','#js-stockadjust-selectall-id-checkbox',function(){
            if($(this).is(":checked")){
                $('.js-stockadjust-select-class-checkbox').prop('checked','checked');
                $.each($('.js-stockadjust-select-class-checkbox'), function(i, option) {
                    if(!$(option).hasClass('selected')){
                        $(option).addClass('selected');
                    }
                });
            }else{
                $('.js-stockadjust-select-class-checkbox').prop('checked','');
                $.each($('.js-stockadjust-select-class-checkbox'), function(i, option) {
                    if($(option).hasClass('selected')){
                        $(option).removeClass('selected');
                    }
                });
            }
        });

        $(document).on('change','.js-stockadjust-select-class-checkbox',function(){
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

    </script>

@endpush
