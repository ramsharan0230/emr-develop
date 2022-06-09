@extends('frontend.layouts.master')
@section('content')
    <style>
        .form-row {
            line-height: 2.15;
        }

        h6 {
            margin-bottom: 5px;
        }

        .category-multiselect .select2-selection--multiple {
            height: 67px;
        }

    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <h4 style="margin: 5px 0 5px 0;">Item Edit</h4>
                        <form id="js-laboratory-add-form" method="post"
                            action="{{ route('itemmaster.update', base64_encode($items->first()->fldbillitem ?? '')) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-row">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Category</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="flditemtype" id="category" readonly>
                                                <option value="" selected disabled>-- Select --</option>
                                                @foreach ($category as $key => $value)
                                                    @if ($items->first()->flditemtype == $key)
                                                        <option selected value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <div class="d-flex justify-content-between align-items-center col-lg-12 col-sm-12">
                                            <div>
                                                <label>Item name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" name="fldbillitem" id="items"
                                                value="{{ $items->first()->fldbillitem }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Code</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="item-code"
                                                value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Tax Type</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="fldcode" required>
                                                <option value="" disabled selected>-- Select --</option>
                                                <option value="TDS" {{ $items->first()->fldcode == 'TDS' ? 'selected' : '' }}>TDS</option>
                                                <option value="VAT" {{ $items->first()->fldcode == 'VAT' ? 'selected' : '' }}>VAT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">{{ $targerLabelName }}</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldtarget" required>
                                                <option {{ !$items->first()->fldtarget ? 'selected' : '' }} value=""
                                                    disabled>--Select --</option>
                                                @foreach ($hospital_departments as $department)
                                                    <option
                                                        {{ $items->first()->fldtarget == $department->fldcomp ? 'selected' : '' }}
                                                        value="{{ $department->fldcomp }}">
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <div class="d-flex justify-content-between align-items-center col-lg-12 col-sm-12">
                                            <label>Department</label>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldreport" required>
                                                <option {{ !$items->first()->fldreport ? 'selected' : '' }} value=""
                                                    disabled>--Select --</option>
                                                @foreach ($sections as $section)
                                                    <option
                                                        {{ $items->first()->fldreport == $section->fldsection ? 'selected' : '' }}
                                                        value="{{ $section->fldsection }}">
                                                        {{ $section->fldsection }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Account ledger</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="accountledger" required>
                                                <option {{ !$items->first()->account_ledger ? 'selected' : '' }}
                                                    value="">-- Select --</option>
                                                @foreach ($accountLedgers as $ledger)
                                                    <option
                                                        {{ $items->first()->account_ledger == $ledger->AccountNo ? 'selected' : '' }}
                                                        value="{{ $ledger->AccountNo }}">
                                                        {{ $ledger->AccountName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 style="margin: 5px 0 5px 0;">Price Setups</h5>
                                <button type="button" class="btn btn-primary" id="price-setup">
                                    <i class="fa fa-plus"></i> Add
                                </button>
                            </div>
                            <div class="form-row" id="item-list"></div>
                            <div class="form-row">
                                <div class="col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Billing Mode</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" id="billing-mode">
                                                <option value="" selected disabled>-- Select --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Name</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="actual-item" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2 col-lg-2">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Price</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="item-price">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2 col-lg-2">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Status</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" id="item-status">
                                                <option value="">-- Select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center col-md-1 col-lg-1 mt-3">

                                </div>
                            </div>
                            <hr>
                            <!-- <h5 style="margin: 5px 0 5px 0;">Price Setups</h5> -->
                            <div class="form-row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <h6 class="col-lg-12 col-sm-12">Fraction Category</h6>
                                        <div class=" col-lg-12 col-sm-12 category-multiselect">
                                            <select class="form-control select2" multiple name="category[]">
                                                @php $itemCategories = $items->first()->category ?? []; @endphp
                                                <option value="" disabled>-- Multi Select --</option>
                                                @foreach ($categories as $key => $category)
                                                    <option {{ in_array($category, $itemCategories) ? 'selected' : '' }}
                                                        value="{{ $category }}">{{ ucfirst($category) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12 col-lg-6">
                                            <h6 class="col-lg-12 col-sm-12">Fraction Percentage</h6>
                                            <div class="d-flex flex-row">
                                                <div class="form-group form-row flex-column align-items-start col-md-6 col-lg-6">
                                                    <label>Other Share (in %)</label>
                                                    <input type="number" class="form-control" name="other_share" id="other_share" value="{{ $items->first()->other_share }}" required>
                                                </div>
                                                <div class="form-group form-row flex-column align-items-start col-md-6 col-lg-6">
                                                    <label>Hospital Share (in %)</label>
                                                    <input type="number" class="form-control" name="hospital_share" id="hospital_share" value="{{ $items->first()->hospital_share }}" required>
                                                </div>
                                            </div>
                                        </div> -->
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <h6 class="col-lg-12 col-sm-12">Description</h6>
                                        <div class=" col-lg-12 col-sm-12">
                                            <textarea name="flddescription" style="width: 100%"
                                                rows="2">{!! $items->first()->flddescription !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h6 class="col-sm-4 col-md-4 col-lg-4">Editable</h6>
                                    <div class="col-sm-8 col-md-8 col-lg-8">
                                        <div class="form-group form-row align-items-center">
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <input class="magic-checkbox" type="checkbox" name="rate" value="1"
                                                    {{ $items->first()->rate ? 'checked' : '' }}>
                                                <label for="">Rate</label>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <input class="magic-checkbox" type="checkbox" name="discount" value="1"
                                                    {{ $items->first()->discount ? 'checked' : '' }}>
                                                <label for="">Discount</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="d-flex flex-row justify-content-end align-items-center col-sm-12 col-md-12 col-lg-12">
                                    <a href="{{ route('itemmaster.index') }}"
                                        class="btn btn-secondary btn-action mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-action">
                                        <i class="fa fa-save"></i>
                                        &nbsp;Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        const billingModes = {!! $billingset !!}
        let data = [];
        @foreach ($items as $item)
            data.push({
            billMode: "{{ $item->fldgroup }}",
            itemName: "{{ $item->flditemname }}",
            price: "{{ $item->flditemcost }}",
            status: "{{ $item->fldstatus }}",
            fldid: "{{ $item->fldid }}"
            })
        @endforeach
        $("#item-list").html('').append(generateHtml());
        changeBillingMode()

        $('#billing-mode').change(function() {
            const billMode = $(this).val();
            const item = $('#items').val();
            $('#actual-item').val(`${item}(${billMode})`);
            $('#item-price').val('');
        })

        $('#price-setup').click(function() {
            const billMode = $('#billing-mode').val();
            const actualItem = $('#actual-item').val();
            const price = $('#item-price').val();
            const status = $('#item-status').val();

            if (billMode == '') {
                showAlert('Billing mode is required.', 'fail');
                return;
            }

            if (actualItem == '') {
                showAlert('Item name is required.', 'fail');
                return;
            }

            if (price == '' || price < 1) {
                showAlert('Price is required.', 'fail');
                return;
            }

            if (status == '') {
                showAlert('Item status is required.', 'fail');
                return;
            }

            data.push({
                billMode: billMode,
                itemName: actualItem,
                price: price,
                status: status,
                fldid: ''
            })

            $("#item-list").html('').append(generateHtml());

            changeBillingMode();
            $('#actual-item').val('');
            $('#item-price').val('');
            $('#item-status').val()
        })

        $("#item-list").on('click', '.delete-item', function() {
            const index = $(this).attr('id');
            const fldid = $(this).attr('fldid');

            if (fldid && fldid != '') {
                $.ajax({
                    url: '{{ route('itemmaster.delete') }}',
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        id: fldid,
                    },
                    success: function(response) {
                        if (response.status) {
                            data.splice(index, 1);
                            $("#item-list").html('').append(generateHtml());
                            changeBillingMode()
                            showAlert('Item deleted.');
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        showAlert(errorMessage, 'fail');
                        console.log(errorMessage);
                    }
                });

            } else {
                data.splice(index, 1);
                $("#item-list").html('').append(generateHtml());
                changeBillingMode()
                showAlert('Item deleted.');
            }
        })

        // $("#hospital_share").on('keyup', function(event) {
        //     let e = $(this);
        //     let h_share_value = e.val();
        //     let doc_share = $("#other_share");

        //     if (h_share_value > 100) {
        //         e.val(100);
        //         doc_share.val(0);
        //     }

        //     if (h_share_value <= 100) {
        //         doc_share.val(100 - h_share_value);
        //     }
        // });

        // $("#other_share").on('keyup', function(event) {
        //     let e = $(this);
        //     let doc_share_value = e.val();
        //     let hopspital_share = $("#hospital_share");
        //     if (doc_share_value > 100) {
        //         e.val(100);
        //         hopspital_share.val(0);
        //     }

        //     if (doc_share_value <= 100) {
        //         hopspital_share.val(100 - doc_share_value);
        //     }
        // });

        $('#js-laboratory-add-form').submit(function(event) {
            var $loadingContainer = $('.loader-ajax-start-stop-container').show();
        });

        function generateHtml() {
            let html = '';
            data.forEach(function(item, index) {
                Object.keys(item).forEach(function(value) {
                    if (value != 'fldid') {

                        var label = '';
                        if (value == 'billMode') {
                            label = "Billing Mode";
                        } else if (value == 'itemName') {
                            label = "Item Name";
                        } else if (value == 'price') {
                            label = "Price";
                        } else if (value == 'status') {
                            label = "Status";
                        }
                        if (value == 'itemName')
                            html += '<div class="col-sm-6 col-md-4 col-lg-4">';
                        else if (value == 'billMode')
                            html += '<div class="col-sm-6 col-md-3 col-lg-3">';
                        else
                            html += '<div class="col-sm-6 col-md-2 col-lg-2">';
                        html += '<div class="form-group form-row flex-column align-items-start">';
                        html += '<label class="col-lg-12 col-sm-12">' + label + '</label>';
                        html += '<div class="col-lg-12 col-sm-12">';
                        if (value == 'billMode')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][fldgroup]" value="' + item.billMode + '" readonly>';
                        else if (value == 'itemName')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][flditemname]" value="' +
                            item.itemName + '" readonly>';
                        else if (value == 'price')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][flditemcost]" value="' +
                            item
                            .price +
                            '">';
                        else {
                            html += '<select class="form-control" name="items[' + index +
                                '][fldstatus]" required>';
                            if (item.status == "Active") {
                                html += '<option value="" disabled>-- Select --</option>';
                                html += '<option selected value="Active">Active</option>';
                                html += '<option value="Inactive">Inactive</option>';
                            } else if (item.status == "Inactive") {
                                html += '<option value="" disabled>-- Select --</option>';
                                html += '<option value="Active">Active</option>';
                                html += '<option selected value="Inactive">Inactive</option>';
                            } else {
                                html += '<option selected value="" disabled>-- Select --</option>';
                                html += '<option value="Active">Active</option>';
                                html += '<option value="Inactive">Inactive</option>';
                            }
                            html += '</select>';
                        }
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                })
                html +=
                    '<div class="d-flex align-items-center justify-content-center col-sm-12 col-md-1 col-lg-1 mt-3">';
                html += '<button type= "button" class="btn btn-danger delete-item" id="' + index +
                    '" fldid="' + item.fldid + '"><i class="fa fa-times"></i></button>';
                html += '</div>';
            })

            $('#billing-mode').attr('name', 'items[' + data.length + '][fldgroup]')
            $('#actual-item').attr('name', 'items[' + data.length + '][flditemname]')
            $('#item-price').attr('name', 'items[' + data.length + '][flditemcost]')
            $('#item-status').attr('name', 'items[' + data.length + '][fldstatus]')

            return html;
        }

        function changeBillingMode() {
            $('#billing-mode').find('option').remove().end().append(
                '<option value="" selected disabled>--Select--</option>').val('');
            var select = document.getElementById('billing-mode');
            billingModes.forEach(function(billMode, index) {
                var isExist = data.find(d => d.billMode == billMode.fldsetname);
                if (!isExist) {
                    const option = window.document.createElement("option");
                    option.text = billMode.fldsetname;
                    option.setAttribute("value", billMode.fldsetname);
                    select[select.options.length] = option;
                }
            });
        }
    </script>
@endpush
