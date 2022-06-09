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
            min-height: 60px;
        }

    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <h4 style="margin: 5px 0 5px 0;">Item Edit</h4>
                        <form id="js-laboratory-add-form" method="post"
                            action="{{ route('itemmaster.update', $items->first()->fldbillitem_id) }}">
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
                                            <input type="text" class="form-control" name="fldbillitem" id="fldbillitem"
                                                value="{{ $items->first()->fldbillitem }}" readonly>
                                            <input type="hidden" class="form-control" id="fldbillitem_id"
                                                name="fldbillitem_id" value="{{ $items->first()->fldbillitem_id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Code</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="item-code" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Tax Type</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="fldcode">
                                                <option value="" disabled selected>-- Select --</option>
                                                <option value="TDS"
                                                    {{ $items->first()->fldcode == 'TDS' ? 'selected' : '' }}>TDS</option>
                                                <option value="VAT"
                                                    {{ $items->first()->fldcode == 'VAT' ? 'selected' : '' }}>VAT</option>
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
                                            <select class="form-control select2" name="fldbillsection_id"
                                                id="fldbillsection_id" {{ $items->first()->flditemtype === 'Diagnostic Tests' ? 'required' : '' }}>
                                                <option {{ !$items->first()->fldbillsection_id ? 'selected' : '' }}
                                                    value="" disabled>--Select --</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->fldid }}"
                                                        {{ $items->first()->fldbillsection_id == $section->fldid ? 'selected' : '' }}>
                                                        {{ $section->fldsection }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="form-control" id="fldbillsection"
                                                name="fldbillsection" value="{{ $items->first()->fldreport }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Account ledger</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" id="account_ledger_id"
                                                name="account_ledger_id" required>
                                                <option {{ !$items->first()->account_ledger_id ? 'selected' : '' }}
                                                    value="">-- Select --</option>
                                                @foreach ($accountLedgers as $ledger)
                                                    <option
                                                        {{ $items->first()->account_ledger_id == $ledger->AccountId ? 'selected' : '' }}
                                                        value="{{ $ledger->AccountId }}"
                                                        account-number="{{ $ledger->AccountNo }}">
                                                        {{ $ledger->AccountName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="form-control" id="account_ledger"
                                                name="account_ledger" value="{{ $items->first()->account_ledger }}">
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
                                            <select class="form-control select2" id="fldbillingset_id">
                                                <option value="" selected disabled>-- Select --</option>
                                            </select>
                                            <input type="hidden" class="form-control" id="fldbillingset" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-4">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Name</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="flditemname" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2 col-lg-2">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Price</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="flditemcost">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2 col-lg-2">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Status</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" id="fldstatus">
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
                                <div class="col-md-12 col-lg-6">
                                    <h6 class="col-lg-12 col-sm-12">Fraction Percentage</h6>
                                    <div class="d-flex flex-row">
                                        <div class="form-group form-row flex-column align-items-start col-md-6 col-lg-6">
                                            <label>Other Share (in %)</label>
                                            <input type="number" class="form-control" name="other_share" id="other_share"
                                                value="{{ $items->first()->other_share }}" required>
                                        </div>
                                        <div class="form-group form-row flex-column align-items-start col-md-6 col-lg-6">
                                            <label>Hospital Share (in %)</label>
                                            <input type="number" class="form-control" name="hospital_share"
                                                id="hospital_share" value="{{ $items->first()->hospital_share }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <h6 class="col-lg-12 col-sm-12">Description</h6>
                                        <div class=" col-lg-12 col-sm-12">
                                            <textarea name="flddescription" style="width: 100%"
                                                rows="1">{!! $items->first()->flddescription !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <h6 class="col-sm-4 col-md-4 col-lg-4">Editable</h6>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-row align-items-center">
                                            <div class="d-flex flex-row align-items-center mr-5">
                                                <input class="magic-checkbox mr-1" type="checkbox" name="rate" value="1"
                                                    {{ $items->first()->rate ? 'checked' : '' }}>
                                                <label for="">Rate</label>
                                            </div>
                                            <div class="d-flex flex-row align-items-center mr-5">
                                                <input class="magic-checkbox mr-1" type="checkbox" name="discount" value="1"
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
            fldbillingset: "{{ $item->fldgroup }}",
            fldbillingset_id: "{{ $item->fldbillingset_id }}",
            flditemname: "{{ $item->flditemname }}",
            flditemcost: "{{ $item->flditemcost }}",
            fldstatus: "{{ $item->fldstatus }}",
            fldid: "{{ $item->fldid }}"
            })
        @endforeach

        $("#item-list").html('').append(generateHtml());
        changeBillingMode()

        $('#fldbillsection_id').change(function() {
            $('#fldbillsection').val($('option:selected', this).text());
        })

        $('#account_ledger_id').change(function() {
            $('#account_ledger').val($('option:selected', this).attr('account-number'));
        })

        $('#fldbillingset_id').change(function() {
            const fldbillingset = $("#fldbillingset_id option:selected").text();
            const item = $("#fldbillitem").val();
            $('#fldbillingset').val(fldbillingset);
            $('#flditemname').val(`${item}(${fldbillingset})`);
            $('#flditemcost').val('');
        })

        $('#price-setup').click(function() {
            const fldbillingset = $("#fldbillingset_id option:selected").text();
            const fldbillingset_id = $('#fldbillingset_id').val();
            const flditemname = $('#flditemname').val();
            const flditemcost = $('#flditemcost').val();
            const fldstatus = $('#fldstatus').val();

            if (fldbillingset == '') {
                showAlert('Billing mode is required.', 'fail');
                return;
            }

            if (flditemname == '') {
                showAlert('Item name is required.', 'fail');
                return;
            }

            if (flditemcost == '') {
                showAlert('Price is required.', 'fail');
                return;
            }

            if (fldstatus == '') {
                showAlert('Item status is required.', 'fail');
                return;
            }

            data.push({
                fldbillingset: fldbillingset,
                fldbillingset_id: fldbillingset_id,
                flditemname: flditemname,
                flditemcost: flditemcost,
                fldstatus: fldstatus,
                fldid: '',
            })

            $("#item-list").html('').append(generateHtml());

            changeBillingMode();
            $('#flditemname').val('');
            $('#flditemcost').val('');
            $('#fldstatus').val('')
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

        $('#js-laboratory-add-form').submit(function(event) {
            var $loadingContainer = $('.loader-ajax-start-stop-container').show();
        });

        $("#hospital_share").on('keyup', function(event) {
            let e = $(this);
            let h_share_value = e.val();
            let doc_share = $("#other_share");

            if (h_share_value > 100) {
                e.val(100);
                doc_share.val(0);
            }

            if (h_share_value <= 100) {
                doc_share.val(100 - h_share_value);
            }
        });

        $("#other_share").on('keyup', function(event) {
            let e = $(this);
            let doc_share_value = e.val();
            let hopspital_share = $("#hospital_share");
            if (doc_share_value > 100) {
                e.val(100);
                hopspital_share.val(0);
            }

            if (doc_share_value <= 100) {
                hopspital_share.val(100 - doc_share_value);
            }
        });

        function generateHtml() {
            let html = '';
            data.forEach(function(item, index) {
                Object.keys(item).forEach(function(value) {
                    if (value != 'fldid' && value != 'fldbillingset_id') {
                        var label = '';
                        if (value == 'fldbillingset') {
                            label = "Billing Mode";
                        } else if (value == 'flditemname') {
                            label = "Item Name";
                        } else if (value == 'flditemcost') {
                            label = "Price";
                        } else if (value == 'fldstatus') {
                            label = "Status";
                        }
                        if (value == 'flditemname')
                            html += '<div class="col-sm-6 col-md-4 col-lg-4">';
                        else if (value == 'fldbillingset')
                            html += '<div class="col-sm-6 col-md-3 col-lg-3">';
                        else
                            html += '<div class="col-sm-6 col-md-2 col-lg-2">';
                        html += '<div class="form-group form-row flex-column align-items-start">';
                        html += '<label class="col-lg-12 col-sm-12">' + label + '</label>';
                        html += '<div class="col-lg-12 col-sm-12">';
                        if (value == 'fldbillingset') {
                            html += '<input type="text" class="form-control" name="items[' + index +
                                '][fldbillingset]" value="' +
                                item.fldbillingset + '"  readonly>';
                            html += '<input type="hidden" class="form-control" name="items[' + index +
                                '][fldbillingset_id]" value="' +
                                item.fldbillingset_id + '"  readonly>';
                        } else if (value == 'flditemname')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][flditemname]" value="' + item.flditemname + '" readonly>';
                        else if (value == 'flditemcost')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][flditemcost]" value="' + item.flditemcost + '">';
                        else {
                            html += '<select class="form-control" name="items[' + index +
                                '][fldstatus]" required>';
                            if (item.fldstatus == "Active") {
                                html += '<option value="" disabled>-- Select --</option>';
                                html += '<option selected value="Active">Active</option>';
                                html += '<option value="Inactive">Inactive</option>';
                            } else if (item.fldstatus == "Inactive") {
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
                if (!item.fldid)
                    html += '<button type= "button" class="btn btn-danger delete-item" id="' + index +
                    '" fldid="' + item.fldid + '"><i class="fa fa-times"></i></button>';
                html += '</div>';
            })

            $('#fldbillingset_id').attr('name', 'items[' + data.length + '][fldbillingset_id]')
            $('#fldbillingset').attr('name', 'items[' + data.length + '][fldbillingset]')
            $('#flditemname').attr('name', 'items[' + data.length + '][flditemname]')
            $('#flditemcost').attr('name', 'items[' + data.length + '][flditemcost]')
            $('#fldstatus').attr('name', 'items[' + data.length + '][fldstatus]')

            return html;
        }

        function changeBillingMode() {
            $('#fldbillingset_id').find('option').remove().end().append(
                '<option value="" selected disabled>-- Select --</option>').val('');
            var select = document.getElementById('fldbillingset_id');
            billingModes.forEach(function(fldbillingset, index) {
                var isExist = data.find(d => d.fldbillingset == fldbillingset.fldsetname);
                if (!isExist) {
                    const option = window.document.createElement("option");
                    option.text = fldbillingset.fldsetname;
                    option.setAttribute("value", fldbillingset.fldid);
                    select[select.options.length] = option;
                }
            });
        }
    </script>
@endpush
