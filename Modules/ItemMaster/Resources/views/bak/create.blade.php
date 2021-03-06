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
            min-height: 67px;
        }

        .custom-checkbox-input:focus {
            border-color: #66afe9;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 4px rgba(102, 175, 233, .6);
        }

    </style>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" style="background-color: unset;" aria-current="page" href="javascript:void(0)">Add
                    Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="background-color: unset;" aria-current="page"
                    href="{{ route('itemmaster.index') }}">Manage</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <h4 style="margin: 5px 0 5px 0;">Item Add</h4>
                        <form id="js-laboratory-add-form" method="post" action="{{ route('itemmaster.store') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-row">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Category</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="flditemtype" id="category" required>
                                                <option value="" selected disabled>-- Select --</option>
                                                @foreach ($category as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
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
                                            <button type="button" data-toggle="modal"
                                                data-target="#js-laboratory-add-item-name-modal" class="btn btn-primary"
                                                tabindex="-1">
                                                <i class="fa fa-plus"></i>
                                                &nbsp;Add new
                                            </button>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldbillitem" id="items" required>
                                                <option value="" selected disabled>-- Select --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Code</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="item-code" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Tax Type</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="fldcode" required>
                                                <option value="" selected disabled>-- Select --</option>
                                                <option value="TDS">TDS</option>
                                                <option value="VAT">VAT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Target</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldtarget" id="target-type" required>
                                                <option value="" selected disabled>-- Select --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <div class="d-flex justify-content-between align-items-center col-lg-12 col-sm-12">
                                            <label>Department</label>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#js-laboratory-add-dept-modal" tabindex="-1">
                                                <i class="fa fa-plus"></i>
                                                &nbsp;Add new
                                            </button>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="fldreport" id="fldreport" required>
                                                <option value="" selected disabled>-- Select --</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->fldsection }}">
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
                                                <option value="" selected disabled>-- Select --</option>
                                                @foreach ($accountLedgers as $ledger)
                                                    <option value="{{ $ledger->AccountNo }}">
                                                        {{ $ledger->AccountName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Status</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control" name="fldstatus" required>
                                                <option value="" selected disabled>-- Select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 style="margin: 5px 0 5px 0;">Price Setups</h5>
                                <button type="button" class="btn btn-primary" id="price-setup" tabindex="-1">
                                    <i class="fa fa-plus"></i> Add
                                </button>
                            </div>
                            <div class="form-row" id="item-list"></div>
                            <div class="form-row">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Billing Mode</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" id="billing-mode"
                                                name="items[0][fldgroup]" required>
                                                <option value="" selected disabled>-- Select --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Item Name</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="actual-item"
                                                name="items[0][flditemname]" required readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Price</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="number" class="form-control" id="item-price"
                                                name="items[0][flditemcost]" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="d-flex align-items-center justify-content-center col-sm-6 col-md-1 col-lg-3 mt-3">
                                </div>

                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <h6 class="col-lg-12 col-sm-12">Fraction Category</h6>
                                        <div class=" col-lg-12 col-sm-12 category-multiselect">
                                            <select class="form-control select2" multiple name="category[]">
                                                <option value="" disabled>-- Multi Select --</option>
                                                @foreach ($categories as $key => $cat)
                                                    <option value="{{ $cat }}">{{ ucfirst($cat) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <h6 class="col-lg-12 col-sm-12">Description</h6>
                                        <div class=" col-lg-12 col-sm-12">
                                            <textarea name="flddescription" style="width: 100%" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h6 class="">Editable</h6>
                                    <div class="col-sm-8 col-md-8 col-lg-8">
                                        <div class="form-group form-row align-items-center">
                                            <div class="col-sm-4 col-md-3 col-lg-2">
                                                <input class="custom-checkbox-input" type="checkbox" name="rate" value="1">
                                                <label for="">Rate</label>
                                            </div>
                                            <div class="col-sm-4 col-md-3 col-lg-2">
                                                <input class="custom-checkbox-input" type="checkbox" name="discount"
                                                    value="1">
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
    <div class="modal fade" id="js-laboratory-add-item-name-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Item Name Add</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row flex-column">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group form-row flex-column align-items-start">
                                <label class="col-lg-12 col-sm-12">Category</label>
                                <div class="col-lg-12 col-sm-12">
                                    <select class="form-control" id="add-form-item-category">
                                        <option value="" selected disabled>-- Select --</option>
                                        @foreach ($category as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group form-row flex-column align-items-start">
                                <label class="col-lg-12 col-sm-12">Item Name</label>
                                <div class="col-lg-12 col-sm-12">
                                    <input type="text" class="form-control" id="add-form-item-name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group form-row flex-column align-items-start">
                                <label class="col-lg-12 col-sm-12">Item Code</label>
                                <div class="col-lg-12 col-sm-12">
                                    <input type="text" class="form-control" id="add-form-item-code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose mr-2" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary onclose" id="js-add-item">
                        <i class="fa fa-save"></i>
                        &nbsp;Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="js-laboratory-add-dept-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Department Add</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row flex-column">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group form-row flex-column align-items-start">
                                <label class="col-lg-12 col-sm-12">Category</label>
                                <div class="col-lg-12 col-sm-12">
                                    <select class="form-control" id="add-department-category">
                                        <option value="" selected disabled>-- Select --</option>
                                        @foreach ($category as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group form-row flex-column align-items-start">
                                <label class="col-lg-12 col-sm-12">Department</label>
                                <div class="col-lg-12 col-sm-12">
                                    <input type="text" class="form-control" id="add-department-name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose mr-2" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary onclose" id="js-add-department">
                        <i class="fa fa-save"></i>
                        &nbsp;Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        const items = {!! $items !!}
        const billingModes = {!! $billingset !!}
        const equipmentTargets = {!! $equipmentTargets !!}
        const procedureTargets = {!! $procedureTargets !!}
        const otherTargets = {!! $otherTargets !!}
        let data = [];

        $("#category").change(function() {
            data = [];
            const category = $(this).val();
            $('#items').find('option').remove().end().append(
                '<option value="" selected disabled>--Select--</option>').val('');
            $('#item-code').val('');
            $('#billing-mode').find('option').remove().end().append(
                '<option value="" selected disabled>--Select--</option>').val('');
            $('#actual-item').val('');
            $('#item-price').val('');
            $("#item-list").html('');

            var select = document.getElementById('items');

            items.forEach(function(billitem, index) {
                if (billitem.flditemcateg === category) {
                    const option = window.document.createElement("option");
                    option.text = billitem.fldbillitem;
                    option.setAttribute("value", billitem.fldbillitem);
                    option.setAttribute("data-code", billitem.fldbillitemcode || '');
                    select[select.options.length] = option;
                }
            });

            changeTarget(category);
        })

        $('#items').change(function() {
            data = [];
            $('#item-code').val($('option:selected', this).attr('data-code'));
            $('#actual-item').val('');
            $('#item-price').val('');
            $("#item-list").html('');
            changeBillingMode();
        })

        $('#items').on("select2:selecting", async function(e) {
            await getItemValue();
            const item = e.target.value
            if (item && item != '') {
                $.ajax({
                    url: '{{ route('itemmaster.check') }}',
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        item: item,
                    },
                    success: function(response) {
                        if (response.status) {
                            $('select[name="fldcode"]').val(response.data[0].fldcode).change();
                            $('select[name="fldtarget"]').val(response.data[0].fldtarget).change();
                            $('select[name="fldreport"]').val(response.data[0].fldreport).change();
                            $('select[name="accountledger"]').val(response.data[0].account_ledger)
                                .change();
                            $('select[name="fldstatus"]').val(response.data[0].fldstatus).change();
                            $('select[name*="category"]').val(response.data[0].category).change();
                            $('textarea[name="flddescription"]').val(response.data[0]
                                .flddescription);
                            if (response.data[0].rate) {
                                $('input[name="rate"]').prop('checked', true);
                            } else {
                                $('input[name="rate"]').prop('checked', false);
                            }
                            if (response.data[0].discount) {
                                $('input[name="discount"]').prop('checked', true);
                            } else {
                                $('input[name="discount"]').prop('checked', false);
                            }
                            for (const item of response.data) {
                                data.push({
                                    billMode: item.fldgroup,
                                    itemName: item.flditemname,
                                    price: item.flditemcost,
                                    fldid: item.fldid
                                })
                            }
                            $("#item-list").html('').append(generateHtml());
                            changeBillingMode()
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        showAlert(errorMessage, 'fail');
                        console.log(errorMessage);
                    }
                });
            }
        });

        function getItemValue() {
            return new Promise((resolve) => setTimeout(resolve, 0));
        }

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

            data.push({
                billMode: billMode,
                itemName: actualItem,
                price: price,
                fldid: '',
            })

            $("#item-list").html('').append(generateHtml());

            changeBillingMode();
            $('#actual-item').val('');
            $('#item-price').val('');
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
                // arr.splice(2, 0, "Lene");
                $("#item-list").html('').append(generateHtml());
                changeBillingMode()
            }
        })

        $('#js-laboratory-add-form').submit(function(event) {
            var $loadingContainer = $('.loader-ajax-start-stop-container').show();
        });

        $('#js-add-item').click(function() {
            const item = $('#add-form-item-name').val();
            const code = $('#add-form-item-code').val();
            const category = $('#add-form-item-category').val();

            if (item == '') {
                showAlert('Item name is required.', 'fail');
                return;
            }

            if (category == '') {
                showAlert('Item category is required.', 'fail');
                return;
            }

            $.ajax({
                url: '{{ route('itemmaster.create.item') }}',
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    fldbillitem: item,
                    fldbillitemcode: code,
                    flditemcateg: category,
                    hospital_department_id: "{{ Helpers::getUserSelectedHospitalDepartmentIdSession() }}"
                },
                success: function(response) {
                    if (response.status) {
                        items.push(response.data);
                        $("#category").val(response.data.flditemcateg).change();
                        $("#items").val(response.data.fldbillitem).change();
                        $('#js-laboratory-add-item-name-modal').modal('toggle');
                        $('#add-form-item-name').val('').change();
                        $('#add-form-item-code').val('');
                        $('#add-form-item-category').val('');
                    } else {
                        showAlert(response.message, 'fail');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert(errorMessage, 'fail');
                    console.log(errorMessage);
                }
            });
        })

        $('#js-add-department').click(function() {
            const name = $('#add-department-name').val();
            const category = $('#add-department-category').val();

            if (name == '') {
                showAlert('Department name is required.', 'fail');
                return;
            }

            if (category == '') {
                showAlert('Department category is required.', 'fail');
                return;
            }

            $.ajax({
                url: '{{ route('itemmaster.create.department') }}',
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    fldsection: name,
                    fldcateg: category,
                    hospital_department_id: "{{ Helpers::getUserSelectedHospitalDepartmentIdSession() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('#fldreport').append($('<option>', {
                            value: response.data.fldsection,
                            text: response.data.fldsection
                        }));
                        $('#fldreport').val(response.data.fldsection).change();
                        $('#js-laboratory-add-dept-modal').modal('toggle');
                        $('#add-department-name').val('');
                        $('add-department-category').val('');
                    } else {
                        showAlert(response.message, 'fail');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    showAlert(errorMessage, 'fail');
                    console.log(errorMessage);
                }
            });
        })

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
                        }
                        if (value == 'price')
                            html += '<div class="col-sm-6 col-md-2 col-lg-3">';
                        else
                            html += '<div class="col-sm-6 col-md-4 col-lg-3">';
                        html += '<div class="form-group form-row flex-column align-items-start">';
                        html += '<label class="col-lg-12 col-sm-12">' + label + '</label>';
                        html += '<div class="col-lg-12 col-sm-12">';
                        if (value == 'billMode')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][fldgroup]" value="' +
                            item.billMode + '"  readonly>';
                        else if (value == 'itemName')
                            html += '<input type="text" class="form-control" name="items[' + index +
                            '][flditemname]" value="' +
                            item.itemName + '"  readonly>';
                        else
                            html += '<input type="number" class="form-control" name="items[' + index +
                            '][flditemcost]" value="' +
                            item
                            .price +
                            '"  readonly>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                })
                html +=
                    '<div class="d-flex align-items-center justify-content-center col-sm-6 col-md-1 col-lg-3 mt-3">';
                html += '<button type= "button" class="btn btn-danger delete-item" id="' + index +
                    '" fldid="' + item.fldid + '"><i class="fa fa-times"></i></button>';
                html += '</div>';
            })

            $('#billing-mode').attr('name', 'items[' + data.length + '][fldgroup]')
            $('#actual-item').attr('name', 'items[' + data.length + '][flditemname]')
            $('#item-price').attr('name', 'items[' + data.length + '][flditemcost]')

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

        function changeTarget(category) {
            var selectTarget = document.getElementById('target-type');
            if (category == 'Equipment' || category == 'General Services') {
                $('#target-type').parent().prev().text('Rate For');
                $('#target-type').find('option').remove().end().append(
                    '<option value="" selected disabled>--Select--</option>').val('');
                equipmentTargets.forEach(function(target) {
                    const option = window.document.createElement("option");
                    option.text = target;
                    option.setAttribute("value", target);
                    selectTarget[selectTarget.options.length] = option;
                });
            } else if (category == 'Procedures') {
                $('#target-type').parent().prev().text('Type');
                $('#target-type').find('option').remove().end().append(
                    '<option value="" selected disabled>--Select--</option>').val('');
                procedureTargets.forEach(function(target) {
                    const option = window.document.createElement("option");
                    option.text = target;
                    option.setAttribute("value", target);
                    selectTarget[selectTarget.options.length] = option;
                });
            } else {
                $('#target-type').parent().prev().text('Target');
                $('#target-type').find('option').remove().end().append(
                    '<option value="" selected disabled>--Select--</option>').val('');
                otherTargets.forEach(function(target) {
                    const option = window.document.createElement("option");
                    option.text = target.name;
                    option.setAttribute("value", target.fldcomp);
                    selectTarget[selectTarget.options.length] = option;
                });
            }
        }
    </script>
@endpush
