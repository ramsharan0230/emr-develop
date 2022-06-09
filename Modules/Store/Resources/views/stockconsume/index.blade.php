@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Stock Consume</h4>
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
                                    <label class="col-lg-2 col-sm-3">
                                        Target Comp
                                        <a href="javascript:;" class="btn btn-primary"
                                           onclick="stockConsume.addStockTarget()">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </label>
                                    <select name="target" class="col-lg-4 col-sm-3 form-control" id="target-select"
                                            onchange="stockConsume.changeTarget()">
                                        <option value="">Select Target</option>
                                        @if(count($targets))
                                            @foreach($targets as $target)
                                                <option value="{{ $target->flditem }}">{{ $target->flditem }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="col-lg-2 col-sm-2">
                                        <input type="text" class="form-control nepaliDatePicker" id="date_target"
                                               value="{{$date}}">
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
                                            {{-- @if(count($routes))
                                                @foreach($routes as $route)
                                                    <option value="{{ $route->fldroute }}">{{ $route->fldroute }}</option>
                                                @endforeach
                                            @endif --}}
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
                                    {{-- <div class="col-1"> --}}
                                        {{-- <input type="hidden" name="stock_no" class="form-control" id="stock_no" readonly> --}}
                                    {{-- </div> --}}
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
                                        <label>Consumed Qty</label>
                                        <input type="text" name="consumeqty" id="consumeqty" class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                        {{--                                    <input type="hidden" name="netcost" id="netcost" >--}}
                                        <input type="hidden" name="category" id="category">
                                    </div>
                                    <div class="col-1">
                                        <a href="javascript:;" class="btn btn-sm btn-primary mt-4"
                                           onclick="stockConsume.addStockConsume()"><i class="fas fa-plus"></i> Add</a>
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
                                    <th class="tittle-th"><input type="checkbox" id="js-stockconsume-selectall-id-checkbox"></th>
                                    <th class="tittle-th">Category</th>
                                    <th class="tittle-th">Particulars</th>
                                    <th class="tittle-th">Batch</th>
                                    <th class="tittle-th">Expiry</th>
                                    <th class="tittle-th">QTY</th>
                                    <th class="tittle-th">Cost</th>
                                    {{-- <th class="tittle-th">Vendor</th>
                                    <th class="tittle-th">Refn</th> --}}
                                </tr>
                                </thead>
                                <tbody id="stock-consume-dynamic-views"></tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                        <div class="form-group text-right">
                            <label class="">Stock Consume No</label>
                            <input type="text" id="js-stockconsume-input">
                            <button class="btn btn-action btn-warning" id="exportStockConsume"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
                            <button class="btn btn-action btn-primary" id="finalSave"><i class="fa fa-check"></i>&nbsp;&nbsp;Final Save</button>
                        </div>
                        {{-- <div class="row mt-1">
                            <div class="col-lg-8 col-sm-7"></div>
                            <div class="col-lg-4 col-sm-5">
                                <div class="row">
                                    <div class="col-lg-5 col-sm-5">
                                        <input type="checkbox" name="">
                                        <label>Print Report</label>
                                    </div>
                                    <div class="col-lg-7 col-sm-7">
                                        <button class="btn btn-action btn-primary"><i class="fas fa-check"></i> Save
                                        </button>&nbsp;
                                        <button type="button" class="btn btn-action btn-warning" name="export"
                                                id="export-report"><i class="fas fa-code"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var stockConsume = {
            addStockTarget: function () {
                var flditem = prompt("Please enter item name");
                if (flditem != null) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('inventory.stock-consume.add.stock.target') }}",
                        data: {flditem: flditem}
                    }).done(function (msg) {
                        if (msg.status === true) {
                            $("#target-select").append(new Option(flditem, flditem));
                        }
                    }).fail(function (jqXHR, textStatus) {
                        alert("Request failed: " + textStatus);
                    });
                }
            },
            changeTarget: function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('inventory.stock-consume.list.stock.target') }}",
                    data: {target: $('#target-select').val()}
                }).done(function (msg) {
                    $("#stock-consume-dynamic-views").append(msg.data);

                }).fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            },
            addStockConsume: function () {
                var target = $('#target-select').val();
                var date_target = $('#date_target').val();
                var route = $('#route').val();
                var selected_quantity = $('#quantity').val();
                var consume_qty = $('#consumeqty').val();
                var flditem = $('#medicine').val();
                var batch = $('#batch').val();

                if (target == '' || date_target == "" || route == "" || flditem == "" || batch == "" || selected_quantity == "" || consume_qty == "") {
                    showAlert('Please select all the data', 'error');
                    return false;
                }
                if(consume_qty == 0){
                    showAlert('Consumed qty must be greater than zero', 'error');
                    return false;
                }
                if ($('#returnqty').val() > $('#quantity').val()) {
                    showAlert('Consumed Qty cannot be greater than Qty', 'error');
                    return false;
                }

                var data = {
                    target: $('#target-select').val(),
                    date_target: $('#date_target').val(),
                    stock_category: $('#category').val(),
                    route: $('#route').val(),
                    selected_quantity: $('#quantity').val(),
                    consume_qty: $('#consumeqty').val(),
                    flditem: $('#medicine').val(),
                    batch: batch
                };
                $.ajax({
                    method: "POST",
                    url: "{{ route('inventory.stock-consume.stock.medicine.add') }}",
                    data: data
                }).done(function (msg) {
                    $('#route').prop('selectedIndex',0);
                    $('#medicine').empty().append('<option>--Select--</option>');
                    $('#batch').empty().append('<option>--Select--</option>');
                    $('#expiry_date').val("");
                    $('#quantity').val("");
                    $('#consumeqty').val("");
                    $("#stock-consume-dynamic-views").empty().append(msg.data);

                }).fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
        }

        $('#route').change(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-consume.change.route.stock') }}",
                data: {route: $('#route').val()}
            }).done(function (msg) {
                // $("#stock-consume-dynamic-views").append(msg.data);
                $("#medicine").empty().append(msg);

            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        })

        $('#medicine').change(function () {
            $.ajax({
                method: "POST",
                url: "{{ route('inventory.stock-consume.stock.medicine.details') }}",
                data: {particularSelect: $('#medicine').val()}
            }).done(function (msg) {
                // $("#stock-consume-dynamic-views").append(msg.data);
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
                url: "{{ route('inventory.stock-consume.stock.medicine.batch.change') }}",
                data: {
                    medicineSelect: $('#medicine').val(),
                    batch: $('#batch').val(),
                }
            }).done(function (msg) {
                // $('#stock_no').empty().val(msg.stock_no);
                $('#expiry_date').empty().val(msg.expiry);
                $('#quantity').empty().val(msg.qty);
                // $('#netcost').empty().val(msg.seller_price);
                // $('#category').empty().val(msg.category);

                // $("#stock-consume-dynamic-views").append(msg.data);
                // $("#batch").empty().append(msg);

            }).fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        })

        $('#exportStockConsume').click(function () {
            var url = baseUrl + '/inventory/stock-consume/export-report?fldreference=' + $('#js-stockconsume-input').val();
            window.open(url, '_blank');
        })

        $("#consumeqty").on('blur', function () {
            var consumeqty = Number($(this).val() || 0);
            var qty = Number($('#quantity').val() || 0);
            if(consumeqty > qty){
                $("#consumeqty").val(qty);
                showAlert('Consumed Quantity cannot be greater than '+$('#quantity').val()+'.', 'fail');
            }
        });

        $(document).on('click','#finalSave',function(){
            var consumeids = [];
            var itemData = $.map($('#stock-consume-dynamic-views tr'), function(trElem, i) {
                if($(trElem).find('.js-stockconsume-select-class-checkbox').hasClass("selected")){
                    var elemVal = $(trElem).find('.js-stockconsume-select-class-checkbox').data('fldid');
                    var items = elemVal.toString().split(',');
                    $.each(items, function(i, item) {
                        consumeids.push(item);
                    });
                }
            });
            if(!(consumeids.length > 0)){
                alert("Please select row first!");
                return false;
            }
            $.ajax({
                url: baseUrl + '/inventory/stock-consume/stock-consume-save',
                type: "POST",
                data: {
                    consumeids: consumeids,
                    target: $('#target-select').val(),
                    isFinalSave: true
                },
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        $('#stock-consume-dynamic-views').empty().append(response.html);
                        $('#js-stockconsume-input').val(response.fldreference)
                        $('#route').prop('selectedIndex',0);
                        $('#medicine').empty().append('<option>--Select--</option>');
                        $('#batch').empty().append('<option>--Select--</option>');
                        $('#expiry_date').val("");
                        $('#quantity').val("");
                        $('#consumeqty').val("");
                        showAlert('Stock consume saved successfully.');
                        $('#exportStockConsume').trigger('click');
                    }else{
                        showAlert('An error has occured!','error');
                    }
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        });

        $(document).on('change','#js-stockconsume-selectall-id-checkbox',function(){
            if($(this).is(":checked")){
                $('.js-stockconsume-select-class-checkbox').prop('checked','checked');
                $.each($('.js-stockconsume-select-class-checkbox'), function(i, option) {
                    if(!$(option).hasClass('selected')){
                        $(option).addClass('selected');
                    }
                });
            }else{
                $('.js-stockconsume-select-class-checkbox').prop('checked','');
                $.each($('.js-stockconsume-select-class-checkbox'), function(i, option) {
                    if($(option).hasClass('selected')){
                        $(option).removeClass('selected');
                    }
                });
            }
        });

        $(document).on('change','.js-stockconsume-select-class-checkbox',function(){
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
