@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Sent to</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Receive</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form action="javascript:;" id="transfer-form">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-row">
                                                <label for="" class="col-sm-6 col-lg-6">Current Department: <b>{{Session::get('selected_user_hospital_department')->name}}</b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group form-row">
                                                <label for="" class="col-sm-6 col-lg-6">Target Department:</label>
                                                <div class="col-sm-6  col-lg-6">
                                                    <select name="department_to" class="form-control" id="department-to" onchange="pharmacyPopupStockTransfer.deptSelect()" required>
                                                        <option value="">Select Department</option>
                                                        @if($hospital_department)
                                                            @forelse($hospital_department as $dept)
                                                                @if(Session::get('selected_user_hospital_department')->name != $dept->name)
                                                                    <option value="{{ $dept->fldcomp }}">{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }})</option>
                                                                @endif
                                                            @empty

                                                            @endforelse
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-checkbox custom-control-inline" id="js-stock-transfer-demandno-div">
                                                <input type="checkbox" class="custom-control-input" id="js-stock-transfer-demandno-checkbox">
                                                <label class="custom-control-label">InsideDemandNo.</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group form-row">
                                                <input type="text" class="form-control" id="js-stock-transfer-demandno-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-checkbox custom-control-inline" id="js-stock-transfer-barcode-div">
                                                <input type="checkbox" class="custom-control-input" id="js-stock-transfer-barcode-checkbox">
                                                <label class="custom-control-label">Barcode</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group form-row">
                                                <input type="text" class="form-control" id="js-stock-transfer-barcode-input" disabled>
                                            </div>
                                        </div>
                                        <div class="col-12 d-none" id="med-detail-controller">
                                            <div class="form-row form-row">
                                                <div class="col-lg-1">
                                                    <label>Route</label>
                                                    <div class="form-group">
                                                        <select name="pharmacy_route" class="form-control" id="pharmacy_route" onchange="pharmacyPopupStockTransfer.selectMedicine()">
                                                            <option value="">Select Route</option>
                                                            <option value="Medicines">Medicines</option>
                                                            <option value="Surgicals">Surgicals</option>
                                                            <option value="Extra Items">Extra Items</option>
                                                            {{-- <option value="oral">oral</option>
                                                            <option value="liquid">liquid</option>
                                                            <option value="fluid">fluid</option>
                                                            <option value="injection">injection</option>
                                                            <option value="resp">resp</option>
                                                            <option value="topical">topical</option>
                                                            <option value="eye/ear">eye/ear</option>
                                                            <option value="anal/vaginal">anal/vaginal</option>
                                                            <option value="msurg">msurg</option>
                                                            <option value="ortho">ortho</option>
                                                            <option value="extra">extra</option> --}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Item Name</label>
                                                    <div class="form-group">
                                                        <select name="medicine_name" class="form-control" id="medicine_name" onchange="pharmacyPopupStockTransfer.getBatch()">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Batch</label>
                                                    <div class="form-group">
                                                        <select name="batch_medicine" class="form-control" id="batch-medicine" onchange="pharmacyPopupStockTransfer.batchSelect()">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Expiry</label>
                                                    <div class="form-group">
                                                       <input type="hidden" class="form-control" name="expiry" id="id-expiry" placeholder="Expiry">
                                                        <span id="id-expiry-span"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Avaiable</label>
                                                    <div class="form-group">
                                                       <div class="quantity-available">QTY</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Transfer Qty</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="qty" id="id-qty" placeholder="QTY" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                                        <input type="hidden" class="form-control" id="id-qty-in-stock">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Cost</label>
                                                    <div class="form-group">
                                                       <input type="text" class="form-control" name="cost" id="id-cost" placeholder="Cost" readonly>
                                                    </div>
                                                </div>
                                                 <div class="col-sm-1">
                                                    <button type="button" class="btn btn-primary btn-action mt-4" onclick="pharmacyPopupStockTransfer.addNewTransfer();"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <div class="table-responsive table-container tablefixedHeight">
                                                <table class="table table-bordered table-hover table-striped ">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Category</th>
                                                        <th>Particulars</th>
                                                        <th>Batch</th>
                                                        <th>Expiry</th>
                                                        <th>QTY</th>
                                                        <th>Unit Cost</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="sent-to-append-container">
                                                    @php
                                                        $totcost = 0;
                                                    @endphp
                                                    @if($transferRequest)
                                                        @forelse($transferRequest as $transfer)
                                                            <tr>
                                                                <input type='hidden' name='sent_to_medicine[]' value='{{$transfer->fldids}}'>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $transfer->fldcategory }}</td>
                                                                <td>{{ $transfer->fldstockid }}</td>
                                                                <td>{{ $transfer->fldbatch }}</td>
                                                                <td>{{ $transfer->fldexpiry }}</td>
                                                                {{-- <td>{{ $transfer->batch ? $transfer->batch->fldbatch : '' }}</td>
                                                                <td>{{ $transfer->batch ? $transfer->batch->fldexpiry : '' }}</td> --}}
                                                                <td>{{ $transfer->fldqty }}</td>
                                                                <td>{{ $transfer->fldsellpr }}</td>
                                                                @php
                                                                    $totcost += $transfer->fldnetcost;
                                                                @endphp
                                                            </tr>
                                                        @empty

                                                        @endforelse
                                                    @endif
                                                    </tbody>
                                                </table>
                                                <div id="bottom_anchor"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mt-3">
                                            <div class="form-group form-row">
                                                <label for="" class="col-sm-2">Total:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="total_cost" class="form-control" placeholder="0" value="{{$totcost}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9 text-right mt-3">
                                            <button type="button" class="btn btn-primary btn-action" onclick="pharmacyPopupStockTransfer.saveTransfer()"><i class="ri-check-fill"></i> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-6 col-lg-6">To Department:</label>
                                            <div class="col-sm-6  col-lg-6">
                                                <select class="form-control" id="department-pending">
                                                    <option value="">Select Department</option>
                                                    @if($hospital_department)
                                                        @forelse($hospital_department as $dept)
                                                            @if(Session::get('selected_user_hospital_department')->name != $dept->name)
                                                                <option value="{{ $dept->fldcomp }}">{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }})</option>
                                                            @endif
                                                        @empty

                                                        @endforelse
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Stock</th>
                                            <th>Category</th>
                                            <th>Qty</th>
                                            <th>Sell Price</th>
                                            <th>From Department</th>
                                            <th>To Department</th>
                                        </tr>
                                        <tbody id="pending-table-container">
                                        {{-- @if($pending)
                                            @forelse($pending as $pen)
                                                <tr>
                                                    <td>{{ $pen->fldstockid }}</td>
                                                    <td>{{ $pen->fldcategory }}</td>
                                                    <td>{{ $pen->fldqty }}</td>
                                                    <td>{{ $pen->fldsellpr }}</td>
                                                    <td>{{ \App\Utils\Helpers::getDepartmentFromCompID($pen->fldfromcomp) }}</td>
                                                    <td>{{ \App\Utils\Helpers::getDepartmentFromCompID($pen->fldtocomp) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10">No data</td>
                                                </tr>
                                            @endforelse
                                        @endif --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-6 col-lg-6">From Department:</label>
                                            <div class="col-sm-6  col-lg-6">
                                                <select class="form-control" id="department-receive">
                                                    <option value="">Select Department</option>
                                                    @if($hospital_department)
                                                        @forelse($hospital_department as $dept)
                                                            @if(Session::get('selected_user_hospital_department')->name != $dept->name)
                                                                <option value="{{ $dept->fldcomp }}">{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }})</option>
                                                            @endif
                                                        @empty

                                                        @endforelse
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table-container">
                                    <table class="table table-bordered table-hover table-striped ">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" id="js-receive-selectall-id-checkbox"></th>
                                            <th>Stock</th>
                                            <th>Category</th>
                                            <th>Qty</th>
                                            <th>Sell Price</th>
                                            <th>From Department</th>
                                            <th>To Department</th>
                                        </tr>
                                        </thead>
                                        <tbody id="received-append-container">
                                        {{-- @if($received)
                                            @forelse($received as $pen)
                                                <tr>
                                                    <td>{{ $pen->fldstockid }}</td>
                                                    <td>{{ $pen->fldcategory }}</td>
                                                    <td>{{ $pen->fldqty }}</td>
                                                    <td>{{ $pen->fldsellpr }}</td>
                                                    <td>{{ \App\Utils\Helpers::getDepartmentFromCompID($pen->fldfromcomp) }}</td>
                                                    <td>{{ \App\Utils\Helpers::getDepartmentFromCompID($pen->fldtocomp) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10">No data</td>
                                                </tr>
                                            @endforelse
                                        @endif --}}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5 mt-3">
                                        <input type="text" placeholder="Remarks" name="receive_remarks" id="receive_remarks">
                                        <button type="button" class="btn btn-primary btn-action" id="confirmReceived"><i class="ri-check-fill"></i> Save</button>
                                    </div>
                                    <div class="col-sm-7 text-right mt-3">
                                        <label class="">Stock Transfer No</label>
                                        <input type="text" id="js-stocktransfer-input">
                                        <button type="button" class="btn btn-primary btn-action" id="exportStockTransfer"><i class="ri-code-fill"></i> Export</button>
                                    </div>
                                </div>
                                {{-- <div class="row text-right mt-3">
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" placeholder="Remarks" name="receive_remarks" id="receive_remarks">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-primary btn-action" id="confirmReceived"><i class="ri-check-fill"></i> Save</button>
                                    </div>
                                </div> --}}
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
        $(document).on("mousedown", "select[readonly]", function (e) {
            return false;
        });

        var pharmacyPopupStockTransfer = {
            selectMedicine: function () {
                var drug = $('#pharmacy_route option:selected').val();

                $.ajax({
                    url: "{{ route('inventory.stock-transfer.get.list.of.medicine') }}",
                    type: "POST",
                    data: {drug: drug},
                    success: function (data) {
                        // console.log(data);
                        // $('.medicine_name').html(data);
                        $("#medicine_name").select2();
                        $("#medicine_name").empty().append(data);
                        /*$('#pharnmacy_freq').prop('selectedIndex', 0);
                        $('#pharnmacy_freq').prop('selectedIndex', 0);
                        $('#pharnmacy_dose').val(0);
                        // $('#pharnmacy_freq').val(unitdose);
                        $('#pharnmacy_day').val(0);
                        $('#pharnmacy_qty').val(0);
                        $('.pharmacy_item_new_order').val("");
                        $('#add_new_order').modal({show: true});*/
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            getBatch: function () {
                var drugRoute = $('#pharmacy_route option:selected').val();
                var medicine = $('#medicine_name option:selected').val();
                if (drugRoute == 'msurg' || drugRoute == 'ortho') {

                } else {
                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.get.list.of.medicine.batch') }}",
                        type: "POST",
                        data: {drug: drugRoute, medicine: medicine},
                        success: function (data) {
                            $("#batch-medicine").empty().append(data);
                            $('#id-expiry-span').val('');
                            $('.quantity-available').text('');
                            $('#id-qty').val('');
                            $('#id-cost').val('');
                            $('#department-to').attr('readonly', true);
                        },
                        error: function (xhr, err) {
                            console.log(xhr);
                        }
                    });
                }
            },
            batchSelect: function () {
                var drugRoute = $('#pharmacy_route option:selected').val();
                var price = $('#batch-medicine option:selected').data('price');
                var qty = $('#batch-medicine option:selected').data('qty');
                var expiry = $('#batch-medicine option:selected').data('expiry').split(' ')[0];
                $('#id-cost').empty().val(price * qty);
                $('#id-expiry').empty().val(expiry);
                $('#id-expiry-span').empty().text(expiry);
                $('.quantity-available').empty().append(qty);
                $('#id-qty-in-stock').empty().val(qty);
                $('#id-qty').empty().val(qty);
            },
            deptSelect: function () {
                if ($('#department-to option:selected').val().length <= 0) {
                    showAlert('Select Department before stock transfer.', 'error');
                    $('#med-detail-controller').hide();
                    return false;
                }

                if (!$('#js-stock-transfer-demandno-checkbox').prop('checked')) {
                    $('#med-detail-controller').removeClass('d-none');
                    $('#med-detail-controller').show();
                }
            },
            addNewTransfer: function () {
                var department_to = $('#department-to option:selected').val();
                var pharmacy_route = $('#pharmacy_route option:selected').val();
                var medicine_name = $('#medicine_name option:selected').val();
                var batch_medicine = $('#batch-medicine option:selected').val();
                var id_expiry = $('#id-expiry').val();
                var id_qty = Number($('#id-qty').val() || 0);
                var id_qty_in_stock = Number($('#id-qty-in-stock').val() || 0);
                var id_cost = Number($('#id-cost').val() || 0);

                var qtyinstock = Number($('.quantity-available').html());
                var qty = Number($('#id-qty').val());
                var unitPrice = $("#batch-medicine option:selected").attr("data-price") || 0;
                if(qty > qtyinstock){
                    console.log('qtyinstock',qtyinstock)
                    console.log('qty',qty)
                    showAlert('Quantity cannot be greater than '+$('#id-qty-in-stock').val()+'.', 'fail');
                    return;
                }
                if (id_qty_in_stock < id_qty) {
                    showAlert("Not enough quantity in stock. Stock qty: " + id_qty_in_stock, 'fail');
                    return false;
                }
                if(pharmacy_route == "" || medicine_name == "" || batch_medicine == "" || id_qty == 0){
                    showAlert("Please fill up all the fields", 'fail');
                    return false;
                }
                $.ajax({
                    url: "{{ route('inventory.stock-transfer.medicine.add.transfer') }}",
                    type: "POST",
                    data: {department_to: department_to, pharmacy_route: pharmacy_route, medicine_name: medicine_name, batch_medicine: batch_medicine, id_expiry: id_expiry, id_qty: id_qty, id_cost: id_cost},
                    success: function (data) {
                        var tcost = Number($('#total_cost').val() || 0);;
                        $('#total_cost').val(id_cost + tcost);
                        $('#id-cost').empty().val(0);
                        $('#id-expiry').empty().val(0);
                        $('#id-qty').empty().val(0);
                        $('#id-qty-in-stock').empty().val(0);
                        $('#id-expiry-span').empty();
                        $('.quantity-available').empty();
                        $("#sent-to-append-container").append(data.html);
                        $('#pharmacy_route').prop('selectedIndex', 0);
                        $("#medicine_name").empty();
                        $("#batch-medicine").empty();
                        // $("#transfer-form").trigger('reset');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            saveTransfer: function () {
                if ($('#js-stock-transfer-demandno-checkbox').prop('checked')) {
                    var valid = true;
                    var postData = [];
                    $.each($('#sent-to-append-container tr'), function(i, elem) {
                        var oldVal = Number($(elem).data('fldquantity') || 0);
                        var currVal = Number($(elem).find('input.form-control').val() || 0);

                        if (oldVal < currVal) {
                            valid = false;
                            $(this).attr('style','border-color:#dd4b39;');
                        } else {
                            $(this).removeAttr('style');
                            postData.push({
                                fldid: $(elem).data('fldid'),
                                fldquantity: currVal,
                            });
                        }
                    });

                    if (!valid) {
                        showAlert('One or more request quantity is greater than old value.', 'fail');
                        return false;
                    }

                    if (postData.length > 0) {
                        $.ajax({
                            url: "{{ route('inventory.stock-transfer.saveItemByDemandNumber') }}",
                            type: "POST",
                            data: {data: postData},
                            dataType: "json",
                            success: function (data) {
                                // $("#pending-table-container").empty().append(data.html);
                                $("#sent-to-append-container").empty();
                                showAlert("Data saved successfully.");
                            },
                            error: function (xhr, err) {
                                showAlert(xhr.responseJSON, 'fail');
                            }
                        });
                    }
                } else {
                    var values = $("input[name='sent_to_medicine[]']")
                        .map(function () {
                            var items = $(this).val().toString().split(',');
                            return items;
                        }).get();
                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.medicine.save.transfer') }}",
                        type: "POST",
                        data: {transferId: values},
                        success: function (data) {
                            $('#pharmacy_route').prop('selectedIndex', 0);
                            $('#select2-medicine_name-container').val(null).trigger('change');
                            $('#batch-medicine').empty().append('<option selected="selected" value="">Select</option>');
                            // $("#pending-table-container").empty().append(data);
                            $("#sent-to-append-container").empty();
                            $('#id-expiry-span').empty();
                            $('.quantity-available').empty();
                            $("#transfer-form").trigger('reset');
                            $('#department-pending').prop('selectedIndex', 0);
                            $('#department-receive').prop('selectedIndex', 0);
                            showAlert("Stock transfer successful!");
                        },
                        error: function (xhr, err) {
                            console.log(xhr);
                        }
                    });
                }
            }
        }

        $(document).ready(function () {
            $("#id-qty").on('blur', function () {
                var qty = Number($(this).val() || 0);
                var qtyinstock = Number($('#id-qty-in-stock').val() || 0);
                var unitPrice = $("#batch-medicine option:selected").attr("data-price") || 0;
                if(qty > qtyinstock){
                    // $("#id-qty").val(qtyinstock);
                    // $('#id-cost').val(unitPrice * qtyinstock);
                    // showAlert('Quantity cannot be greater than '+$('#id-qty-in-stock').val()+'.', 'fail');
                }else{
                    $('#id-cost').val(unitPrice * qty);
                }
            });

            $('#js-stock-transfer-demandno-div').click(function() {
                var isChecked = $('#js-stock-transfer-demandno-checkbox').prop('checked');
                $('#js-stock-transfer-demandno-input').attr('disabled', isChecked);

                if (isChecked) {
                    $('#med-detail-controller').removeClass('d-none');
                    $('#med-detail-controller').show();
                } else {
                    $('#med-detail-controller').hide();
                    $('#med-detail-controller').addClass('d-none');
                }
            });

            $('#js-stock-transfer-barcode-div').click(function() {
                var isChecked = $('#js-stock-transfer-barcode-checkbox').prop('checked');
                $('#js-stock-transfer-barcode-input').attr('disabled', isChecked);

                if (isChecked) {
                    $('#med-detail-controller').removeClass('d-none');
                    $('#med-detail-controller').show();
                } else {
                    $('#med-detail-controller').hide();
                    $('#med-detail-controller').addClass('d-none');
                }
            });

            $('#js-stock-transfer-demandno-input').keydown(function (e) {
                if (e.which == 13) {
                    var fldcomp = $('#department-to').val() || '';
                    var flddemandno = $('#js-stock-transfer-demandno-input').val() || '';

                    if (fldcomp == '') {
                        showAlert('Please select department.', 'fail');
                        return false;
                    }
                    if (flddemandno == '') {
                        showAlert('Please enter demand number.', 'fail');
                        return false;
                    }

                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.getItemByDemandNumber') }}",
                        type: "GET",
                        data: {
                            fldcomp: fldcomp,
                            flddemandno: flddemandno,
                        },
                        dataType: "json",
                        success: function (response) {
                            var trData = "";
                            var total = 0;
                            $.each(response, function(i, e) {
                                trData += "<tr data-fldid='" + e.fldid + "' data-fldquantity='" + e.fldquantity + "'>";
                                trData += "<td>" + (i+1) + "</td>";
                                trData += "<td>" + e.fldroute + "</td>";
                                trData += "<td>" + e.fldstockid + "</td>";
                                trData += "<td>" + e.fldbatch + "</td>";
                                trData += "<td>" + e.fldexpiry + "</td>";
                                trData += "<td><input type='text' class='form-control' value='" + e.fldquantity + "'></td>";
                                trData += "<td>" + e.fldrate + "</td>";
                                trData += "</tr>";

                                total += Number(e.fldquantity)*Number(e.fldrate);
                            });
                            $('#sent-to-append-container').html(trData);
                            $('#total_cost').val(total);
                        }
                    });
                }
            });

            $('#js-stock-transfer-barcode-input').keydown(function (e) {
                if (e.which == 13) {
                    // var fldcomp = $('#department-to').val() || '';
                    var fldcomp = "{{Session::get('selected_user_hospital_department')->fldcomp}}";
                    var barcode = $('#js-stock-transfer-barcode-input').val() || '';

                    if (fldcomp == '') {
                        showAlert('Please select department.', 'fail');
                        return false;
                    }
                    if (barcode == '') {
                        showAlert('Please enter barcode.', 'fail');
                        return false;
                    }

                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.getItemByBarcode') }}",
                        type: "GET",
                        data: {
                            fldcomp: fldcomp,
                            fldbarcode: barcode,
                        },
                        dataType: "json",
                        success: function (response) {
                            if(response.entryDetails != null){
                                $('#med-detail-controller').removeClass('d-none');
                                $('#med-detail-controller').show();
                                $('#department-to').attr('readonly', true);
                                // var dataqty = response.entryDetails.fldqty - response.pendingQty;
                                // var batchOption = '<option value=""></option><option value="'+response.entryDetails.fldbatch+'" data-price="'+response.entryDetails.fldsellpr+'" data-qty="'+dataqty+'" data-expiry="'+response.entryDetails.fldexpiry+'">'+response.entryDetails.fldbatch+'</option>';
                                $("#batch-medicine").empty().append(response.batchOption);
                                $("#pharmacy_route").val(response.itemRoute);
                                $("#medicine_name").empty().append(response.options);
                                $("#medicine_name option[value='"+response.itemName+"']").attr("selected", "selected");
                                $("#batch-medicine option[value='"+response.entryDetails.fldbatch+"']").attr("selected", "selected").change();
                            }else{
                                $("#pharmacy_route").prop('selectedIndex',0);
                                $("#medicine_name").empty();
                                $("#batch-medicine").empty();
                                $("#id-expiry").val("");
                                $("#id-expiry-span").html("");
                                $(".quantity-available").html("");
                                $("#id-qty").val("");
                                $("#id-cost").val("");
                                $('#med-detail-controller').hide();
                                $('#med-detail-controller').addClass('d-none');
                            }
                        }
                    });
                }
            });

            $(document).on('focusout', '#sent-to-append-container input.form-control', function() {
                var oldVal = Number($(this).closest('tr').data('fldquantity') || 0);
                var currVal = Number($(this).val() || 0);

                if (oldVal < currVal) {
                    showAlert('Request value cannot be greater than ' + oldVal, 'fail');
                    $(this).attr('style','border-color:#dd4b39;');
                } else
                    $(this).removeAttr('style');
            });

            $(document).on('change','#department-pending',function(){
                var fldcomp = $(this).val();
                if(fldcomp != ""){
                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.getPendingListsByDept') }}",
                        type: "GET",
                        data: {fldcomp: fldcomp},
                        success: function (data) {
                            $("#pending-table-container").empty().append(data);
                        },
                        error: function (xhr, err) {
                            console.log(xhr);
                        }
                    });
                }
            });

            $(document).on('change','#department-receive',function(){
                var fldcomp = $(this).val();
                if(fldcomp != ""){
                    $.ajax({
                        url: "{{ route('inventory.stock-transfer.getReceivedListsByDept') }}",
                        type: "GET",
                        data: {fldcomp: fldcomp},
                        success: function (data) {
                            $("#received-append-container").empty().append(data);
                        },
                        error: function (xhr, err) {
                            console.log(xhr);
                        }
                    });
                }
            });

            $(document).on('change','#js-receive-selectall-id-checkbox',function(){
                if($(this).is(":checked")){
                    $('.js-receive-select-class-checkbox').prop('checked','checked');
                    $.each($('.js-receive-select-class-checkbox'), function(i, option) {
                        if(!$(option).hasClass('selected')){
                            $(option).addClass('selected');
                        }
                    });
                }else{
                    $('.js-receive-select-class-checkbox').prop('checked','');
                    $.each($('.js-receive-select-class-checkbox'), function(i, option) {
                        if($(option).hasClass('selected')){
                            $(option).removeClass('selected');
                        }
                    });
                }
            });

            $(document).on('change','.js-receive-select-class-checkbox',function(){
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

            $(document).on('click','#confirmReceived',function(){
                var transferids = [];
                var itemData = $.map($('#received-append-container tr'), function(trElem, i) {
                    if($(trElem).find('.js-receive-select-class-checkbox').hasClass("selected")){
                        var elemVal = $(trElem).find('.js-receive-select-class-checkbox').data('fldid');
                        var items = elemVal.toString().split(',');
                        $.each(items, function(i, item) {
                            transferids.push(item);
                        });
                    }
                });
                if(!(transferids.length > 0)){
                    alert("Please select row first!");
                    return false;
                }
                $.ajax({
                    url: baseUrl + '/inventory/stock-transfer/confirmStockReceive',
                    type: "POST",
                    data: {
                        transferids: transferids,
                        remarks: $('#receive_remarks').val(),
                        fldcomp: $('#department-receive').val()
                    },
                    dataType: "json",
                    success: function (response) {
                        $('#received-append-container').empty().append(response.html);
                        $('#js-stocktransfer-input').val(response.stockReceivedReference)
                        showAlert('Stock received successfully.');
                        $('#exportStockTransfer').trigger('click');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            });
        });

        $('#exportStockTransfer').click(function () {
            if($('#js-stocktransfer-input').val() != ""){
                var url = baseUrl + '/inventory/stock-transfer/export-report?fldreference=' + $('#js-stocktransfer-input').val();
                window.open(url, '_blank');
            }
        })
    </script>
@endpush
