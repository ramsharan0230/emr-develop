@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Stock Consume Return
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Target</label>
                                <select class="form-control" name="target"
                                    id="js-target-select">
                                    <option value="">Select Target</option>
                                    @if(count($targets))
                                        @foreach($targets as $target)
                                            <option value="{{ $target->flditem }}">{{ $target->flditem }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Ref Order No</label>
                                <select class="form-control markreadonly" name="fldreference"
                                        id="reference">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-3">Route</label>
                                <select id="route" class="form-control" name="route">
                                    <option value="">--Select--</option>
                                    <option value="Medicines">Medicines</option>
                                    <option value="Surgicals">Surgicals</option>
                                    <option value="Extra Items">Extra Items</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-4">Particulars</label>
                                <select id="particulars" class="form-control" name="particulars">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-3">Batch</label>
                                <select id="batch" class="form-control" name="batch">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-5">Expiry</label>
                                <input readonly type="text" id="expiry" name="expiry" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <label class="">Quantity</label>
                                <input readonly type="text" id="qty" class="form-control" name="qty" placeholder="0">
                            </div>
                            <div class="col-sm-2">
                                <label>Return Quantity</label>
                                <input type="number" id="retqty" class="form-control" name="retqty" placeholder="0" onkeydown="if(event.key==='.'){event.preventDefault();}">
                            </div>
                            <div class="col-sm-1">
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
                                                <th><input type="checkbox" id="js-stockconsumereturn-selectall-id-checkbox"></th>
                                                <th>Category</th>
                                                <th>Particulars</th>
                                                <th>Batch</th>
                                                <th>Expiry</th>
                                                <th>QTY</th>
                                                <th>Cost</th>
                                            </tr>
                                            </thead>
                                            <tbody id="consumereturn_tbody"></tbody>
                                        </table>
                                        <div id="bottom_anchor"></div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <label class="">Consume Return Reference No</label>
                                    <input type="text" id="js-stockconsumereturn-input">
                                    <button class="btn btn-action btn-warning" id="exportConsumeReturn"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
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
        $(document).on('change', '#js-target-select', function () {
            $.ajax({
                url: baseUrl + "/inventory/stock-consume-return/get-reference-number",
                type: "GET",
                data: {
                    target: $(this).val(),
                },
                dataType: "json",
                success: function (response) {
                    if(response){
                        $('#reference').empty().append(response);
                        clearData();
                    }else {
                        $('#reference').empty().append('<option>Not availlable</option>');
                    }
                    if(response.error){
                        showAlert(response.error,'error');
                    }
                    $('#route').prop('selectedIndex',0);
                    $('#particulars').empty().append('<option>--Select--</option>');
                }
            });
        });

        $('#particulars').change( function () {
            var reference = $('#reference').val();
            var selectedOption = $('#js-target-select option:selected');
            var medicine = $('#particulars').val();
            var route = $('#route').val();
            if (medicine != '') {
                $.ajax({
                    url: baseUrl + '/inventory/stock-consume-return/batch',
                    type: "GET",
                    data: {
                        medicine: medicine,
                        reference:reference,
                        target: $(selectedOption).val(),
                        route:route,
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response){
                            $('#batch').empty().append(response);
                            $('#expiry').empty().val('');
                            $('#qty').empty().val('');
                            $('#retqty').empty().val('');
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
        $('#batch').change( function () {
            var reference = $('#reference').val();
            var batch = $('#batch').val();
            var medicine = $('#particulars').val();
            var route = $('#route').val();
            var target = $('#js-target-select').val();
            if (medicine != '' || batch !='') {
                $.ajax({
                    url: baseUrl + '/inventory/stock-consume-return/expiry',
                    type: "GET",
                    data: {
                        medicine: medicine,
                        batch:batch,
                        route:route,
                        reference:reference,
                        target:target
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response){
                            $('#expiry').empty().val(response.expiry);
                            $('#qty').empty().val(response.qty);
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }

                    }
                });
            }
        })
        $('#route').change( function () {
            var route = $(this).val();
            var reference = $('#reference').val();
            var selectedOption = $('#js-target-select option:selected');

            if (selectedOption != '' && reference!='' && route !='' ) {
                $.ajax({
                    url: baseUrl + '/inventory/stock-consume-return/medicine',
                    type: "GET",
                    data: {
                        target: $(selectedOption).val(),
                        reference:reference,
                        route:route,
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response){
                            $('#particulars').empty().append(response);
                            $('#batch').empty().append('<option>--Select--</option>');
                        }else {
                            $('#particulars').empty().append('<option>Not availlable</option>');
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }
                        $('#expiry').val("");
                        $('#qty').val("");
                        $('#retqty').val("");
                    }
                });
            }else {
                showAlert('Please select supplier and reference','error');
            }
        });

        $(document).on('change','#reference',function(){
            if($(this).val() != ""){
                $.ajax({
                    url: "{{ route('inventory.stock-consume-return.get.pendingStockconsumereturns') }}",
                    type: "GET",
                    data: {
                            reference: $('#reference').val(),
                        },
                    success: function (data) {
                        $('#consumereturn_tbody').empty().append(data);
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
            $('#particulars').empty().append('<option>--Select--</option>');
            // $('#batch').empty().append('<option>--Select--</option>');
            // $('#expiry').val("");
            $('#qty').val("");
            $('#retqty').val("");
        }

        $("#retqty").on('blur', function () {
            var retqty = Number($(this).val() || 0);
            var qty = Number($('#qty').val() || 0);
            if(retqty > qty){
                $("#retqty").val(qty);
                showAlert('Quantity cannot be greater than '+$('#qty').val()+'.', 'fail');
            }
        });

        $('#saveBtn').click(function () {
            var qty = Number($('#qty').val() || 0);
            var retqty = Number($('#retqty').val() || 0);
            var batch = $('#batch').val();
            var medicine = $('#particulars').val();
            var selectedOption = $('#js-target-select option:selected');
            var reference = $('#reference').val();
            var route = $('#route').val();
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

            if (retqty != '' || qty !='') {
                $.ajax({
                    url: baseUrl + '/inventory/stock-consume-return/save-consume-return',
                    type: "POST",
                    data: {
                        medicine: medicine,
                        batch:batch,
                        qty:qty,
                        retqty:retqty,
                        fldtarget: $(selectedOption).val(),
                        reference:reference,
                        route:route,
                        reason: reason
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response){
                            $('#qty').val(qty - retqty);
                            $('#consumereturn_tbody').empty().append(response);
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
            var itemData = $.map($('#consumereturn_tbody tr'), function(trElem, i) {
                if($(trElem).find('.js-stockconsumereturn-select-class-checkbox').hasClass("selected")){
                    var elemVal = $(trElem).find('.js-stockconsumereturn-select-class-checkbox').data('fldid');
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
                url: baseUrl + '/inventory/stock-consume-return/save-final',
                type: "POST",
                data: {
                    returnids: returnids
                },
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        $('#consumereturn_tbody').empty().append(response.html);
                        $('#js-stockconsumereturn-input').val(response.fldnewreference)
                        clearData();
                        showAlert('Consume returned successfully.');
                        $('#exportConsumeReturn').trigger('click');
                    }else{
                        showAlert('An error has occured!','error');
                    }
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        });

        $(document).on('change','#js-stockconsumereturn-selectall-id-checkbox',function(){
            if($(this).is(":checked")){
                $('.js-stockconsumereturn-select-class-checkbox').prop('checked','checked');
                $.each($('.js-stockconsumereturn-select-class-checkbox'), function(i, option) {
                    if(!$(option).hasClass('selected')){
                        $(option).addClass('selected');
                    }
                });
            }else{
                $('.js-stockconsumereturn-select-class-checkbox').prop('checked','');
                $.each($('.js-stockconsumereturn-select-class-checkbox'), function(i, option) {
                    if($(option).hasClass('selected')){
                        $(option).removeClass('selected');
                    }
                });
            }
        });

        $(document).on('change','.js-stockconsumereturn-select-class-checkbox',function(){
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

        $(document).on('click','#exportConsumeReturn',function(){
            var url = baseUrl + '/inventory/stock-consume-return/exportpdfreprint?fldnewreference=' + $('#js-stockconsumereturn-input').val();
            window.open(url, '_blank');
        });

    </script>
@endpush

