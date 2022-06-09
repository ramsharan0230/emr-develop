<form action="javascript:;" class="pharmacy-form container-fluid" id="pharmacy-request-submit" method="post">
    @csrf
    @php
        $encounterDataPatientInfo = $encounterData->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="flditemtype" value="Radio Diagnostics">
    <input type="hidden" name="med_ortho_msurge" id="med-ortho-surge">

    <div class="row">
        <div class="col-md-5">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control input_disabled" id="staticEmail"
                           value="{{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $encounterDataPatientInfo->fldptnamefir??''}} {{ $encounterDataPatientInfo->fldmidname??''}} {{ $encounterDataPatientInfo->fldptnamelast??'' }}">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Address</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="pharmacy_address"
                           value="{{ $encounterDataPatientInfo ? $encounterDataPatientInfo->fldptaddvill??'' . ', '. $encounterDataPatientInfo->fldptadddist??'':'' }}">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Gender</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="staticEmail"
                           value="{{ $encounterDataPatientInfo->fldptsex??'' }}">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Bed No</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="pharmacy_address"
                           value="{{ Helpers::getBedNumber($encounterId) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <nav>
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pharmacy-current-tab" data-toggle="tab"
                               href="#pharmacy-current" role="tab" aria-controls="pharmacy-current"
                               aria-selected="true">Current </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pharmacy-selection-tab" data-toggle="tab" href="#pharmacy-selection"
                               role="tab" aria-controls="pharmacy-selection" aria-selected="false">Selection </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pharmacy-reorder-tab" data-toggle="tab" href="#pharmacy-reorder"
                               role="tab" aria-controls="pharmacy-reorder" aria-selected="false">Reorder </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pharmacy-new-order-tab" data-toggle="tab" href="#pharmacy-new-order"
                               role="tab" aria-controls="pharmacy-new-order" aria-selected="false">New Orders </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="extra-pharmacy-tab" data-toggle="tab" href="#extra-pharmacy"
                               role="tab" aria-controls="extra-pharmacy" aria-selected="false">Extra Pharmacy Orders </a>
                        </li>

                    </ul>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="pharmacy-current" role="tabpanel"
                         aria-labelledby="pharmacy-current-tab">
                        <div class="res-table table-pharmacy">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Route</th>
                                    <th>Medicine</th>
                                    <th>Dose</th>
                                    <th>Freq</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($currentData))
                                    @foreach($currentData as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->fldstarttime }}</td>
                                            <td>{{ $data->fldroute }}</td>
                                            <td>{{ $data->flditem }}</td>
                                            <td>{{ $data->flddose }}</td>
                                            <td>{{ $data->fldfreq }}</td>
                                            <td>{{ $data->flddays }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pharmacy-selection" role="tabpanel"
                         aria-labelledby="pharmacy-selection-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <select id="pharmacy_select_data" class="form-control">
                                    @if(count($patFindings))
                                        @foreach($patFindings as $pat)
                                            <option value="{{ $pat->col }}">{{ $pat->col }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:;" class="btn btn-primary"
                                   onclick="pharmacyPopup.selectionListData()">Show</a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="res-table table-pharmacy">
                                    <table class="table table-hover table-striped table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Drug</th>
                                            <th>Type</th>
                                            <th>Dose</th>
                                            <th>Unit</th>
                                            <th>Frequency</th>
                                            <th>Day</th>
                                        </tr>
                                        </thead>
                                        <tbody class="select_pharmacy_list"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pharmacy-reorder" role="tabpanel"
                         aria-labelledby="pharmacy-reorder-tab">
                        <div class="res-table table-pharmacy">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th><input id='selectall' type="checkbox"></th>
                                    <th>Route</th>
                                    <th>Medicine</th>
                                    <th>Dose</th>
                                    <th>Freq</th>
                                    <th>Days</th>
                                    <th>Qty</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($currentData))
                                    @foreach($currentData as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><input type="checkbox" name="reorder_ids" class="reorder_bulk"
                                                       value="{{ $data->fldid }}">
                                            </td>
                                            <td>{{ $data->fldstarttime }}</td>
                                            <td>{{ $data->fldroute }}</td>
                                            <td>{{ $data->flditem }}</td>
                                            <td>{{ $data->flddose }}</td>
                                            <td>{{ $data->fldfreq }}</td>
                                            <td>{{ $data->flddays }}</td>
                                            <td><a href="javascript:;" title="Re-order"
                                                   onclick="pharmacyPopup.reorder('{{ $data->fldid }}')" class="text-info"><i
                                                        class="fas fa-arrow-circle-right"></i></a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <a href="javascript:;" class="btn btn-success btn-sm mt-2"
                           onclick="pharmacyPopup.reorderBulk()">Bulk Reorder</a>
                    </div>
                    <div class="tab-pane fade" id="pharmacy-new-order" role="tabpanel"
                         aria-labelledby="pharmacy-new-order-tab">
                        <div class="row form-group">
                            {{--<div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3">
                                        <div class="custom-control custom-radio">
                                            <input value="Generic" name="generic_brand" type="radio" class="custom-control-input generic_brand" checked="">
                                            <label class="custom-control-label" for="pharmacy_Generic">Generic</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" value="Brand" name="generic_brand" class="custom-control-input generic_brand">
                                            <label class="custom-control-label" for="pharmacy_Brand">Brand</label>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="pharmacy_Request" value="Requested"
                                                   name="generic_request_use_own" checked="checked"
                                                   class="custom-control-input">
                                            <label class="custom-control-label" for="pharmacy_Request">Request</label>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="pharmacy_UseOwn" value="UseOwn"
                                                   name="generic_request_use_own" class="custom-control-input">
                                            <label class="custom-control-label" for="pharmacy_UseOwn">Use Own</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <label for="in_stock" class="col-6">In Stock</label>
                                    <select name="in_stock" id="in_stock" class="form-control col-6">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select name="request_department_pharmacy" class="form-control"
                                            id="request_department_pharmacy">
                                        <option value=""></option>
                                        @if(count($departments))
                                            @foreach($departments as $department)
                                                <option
                                                    value="{{ $department }}" {{ $encounterData->fldcurrlocat == $department?"selected":'' }}>{{ $department }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        <!--                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select name="route" id="pharmacy_route" class="form-control"
                                            onchange="pharmacyPopup.selectMedicine()">
                                        <option value="">Select Route</option>
                                        <option value="oral">oral</option>
                                        <option value="liquid">liquid</option>
                                        <option value="fluid">fluid</option>
                                        <option value="injection">injection</option>
                                        <option value="resp">resp</option>
                                        <option value="topical">topical</option>
                                        <option value="eye/ear">eye/ear</option>
                                        <option value="anal/vaginal">anal/vaginal</option>
                                        <option value="msurg">msurg</option>
                                        <option value="ortho">ortho</option>
                                        <option value="extra">extra</option>
                                    </select>
                                    <select name="route" id="pharmacy_route" class="form-control" onchange="pharmacyPopup.selectMedicine()">
                                        <option value="">Select Route</option>
                                        @if(count($newOrders))
                            @foreach($newOrders as $route)
                                <option value="{{ $route->fldroute }}">{{ $route->fldroute }}</option>
                                            @endforeach
                        @endif
                        @if(count($newOrdersSurgcat))
                            @foreach($newOrdersSurgcat as $route)
                                @if($route->fldsurgcateg == "msurg" || $route->fldsurgcateg == "ortho")
                                    <option value="{{ $route->fldsurgcateg }}">{{ $route->fldsurgcateg }}</option>
                                                @endif
                            @endforeach
                        @endif
                            </select>
                        </div>
                    </div>-->
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="hidden" name="route" id="route-on-change">
                                    <select id="itemNameToShowDropdown" name="itemName" class="form-control pharmacy_item_new_order" onchange="pharmacyPopup.clickMedSelect(this)">
                                        <option value="">--Select--</option>
                                    </select>
                                    {{--                                    <input type="text" id="itemNameToShowDropdown" name="itemNameToShow"--}}
                                    {{--                                           class="form-control pharmacy_item_new_order"--}}
                                    {{--                                           onclick="pharmacyPopup.selectMedicine()">--}}
                                    {{--                                    <input type="hidden" name="itemName" class="form-control pharmacy_item_new_order">--}}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <input type="text" name="pharnmacy_dose" id="pharnmacy_dose" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <select name="pharnmacy_freq" id="pharnmacy_freq" class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="PRN">PRN</option>
                                        <option value="SOS">SOS</option>
                                        <option value="stat">stat</option>
                                        <option value="AM">AM</option>
                                        <option value="HS">HS</option>
                                        <option value="Pre">Pre</option>
                                        <option value="Post">Post</option>
                                        <option value="Hourly">Hourly</option>
                                        <option value="Alt day">Alt day</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Biweekly">Biweekly</option>
                                        <option value="Tryweekly">Tryweekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Tapering">Tapering</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <input type="text" name="pharnmacy_day" id="pharnmacy_day" class="form-control"
                                           value="0" onblur="pharmacyPopup.calculateQuantity()">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <input type="text" name="pharnmacy_qty" id="pharnmacy_qty" class="form-control"
                                       value="0">
                            </div>
                            <div class="col-sm-1">
                                <!--                                <a href="javascript:void(0)" class="btn btn-info btn-sm disableInsertUpdate"
                                                                   onclick="pharmacyPopup.calculateQuantity()"><i class="fas fa-calculator"></i></a>-->
                                <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                   onclick="pharmacyPopup.clickSaveNewOrder()">Add</a>
                            </div>
                        </div>
                        <div class="res-table table-pharmacy">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Start Date</th>
                                    <th>Route</th>
                                    <th>Particulars</th>
                                    <th>Dose</th>
                                    <th>Freq</th>
                                    <th>Day</th>
                                    <th>Qty</th>
                                    <th>Comment</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="new_orders_list">
                                @if(count($newOrdersPathDosing))
                                    @foreach($newOrdersPathDosing as $dose)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                   onclick="pharmacyPopup.changeDate('{{ $dose->fldid }}')">
                                                    {{ $dose->fldstarttime }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $dose->fldroute }}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle" id="menu1{{ $loop->iteration }}"
                                                       type="button" data-toggle="dropdown">{{ $dose->flditem }}</a>
                                                    <ul class="dropdown-menu" role="menu"
                                                        aria-labelledby="menu1{{ $loop->iteration }}">
                                                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                                   href="javascript:;">Tapering
                                                                Regimen</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                                   href="javascript:;">Review
                                                                Problems</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                                   href="javascript:;"
                                                                                   onclick="pharmacyPopup.directDispensing('{{ $dose->fldid }}')">Direct
                                                                Dispensing</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                                   href="javascript:;">Hide F2 List</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                   onclick="pharmacyPopup.changeDose('{{ $dose->fldid }}')">
                                                    {{ $dose->flddose }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                   onclick="pharmacyPopup.changeFrequency('{{ $dose->fldid }}')">
                                                    {{ $dose->fldfreq }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                   onclick="pharmacyPopup.changeDay('{{ $dose->fldid }}')">
                                                    {{ $dose->flddays }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;"
                                                   onclick="pharmacyPopup.changeQuantity('{{ $dose->fldid }}')">
                                                    {{ $dose->fldqtydisp }}
                                                </a>
                                            </td>
                                            <td>
                                                <textarea name="commentPharmacy" class="commentPharmacy" cols="20"
                                                          rows="2" onblur="pharmacyPopup.comment({{ $dose->fldid }});"
                                                          id="comment-fldid-{{ $dose->fldid }}">{{ $dose->fldcomment }}</textarea>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="text-danger"
                                                   onclick="pharmacyPopup.deleteNewOrder('{{ $dose->fldid }}')"><i
                                                        class="ri-delete-bin-6-fill"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="extra-pharmacy" role="tabpanel" aria-labelledby="extra-pharmacy-tab">
                        <div class="row">
                            <div class="col-7">
                                <textarea name="extra-order" class="form-control" id="extra-order-textarea" cols="30" rows="10"></textarea>
                            </div>
                            <div class="col-5">
                                <strong>Extra Order List</strong>
                                <ul class="ul-pharmacy-request res-table list-group">
                                    @if($extraOrder)
                                        @foreach($extraOrder as $extra)
                                            <li class=" list-group-item pt-1 pb-1"><span data-fldreportquali="{{ $extra->fldreportquali }}" class="fldreportquali-li">{{ substr(strip_tags($extra->fldreportquali), 0, 50) }}</span>
                                                <a href="javascript:;" onclick="pharmacyPopup.deleteExtraOrder({{ $extra->fldid }})" class="text-danger float-right"><i class="fas fa-trash"></i></a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-primary" id="pharmacy-popup-save-button" type="button" onclick="pharmacyPopup.saveExtraOrder()">Save</button>
                                <button class="btn btn-warning" id="pharmacy-popup-save-button-enable">Enable Save</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(window).ready(function () {
        pharmacyPopup.selectMedicine();
        CKEDITOR.replace('extra-order-textarea', {height: '200px'});
        $("#pharmacy-popup-save-button-enable").hide();

        $('.ul-pharmacy-request').on('click', '.fldreportquali-li', function () {
            CKEDITOR.instances['extra-order-textarea'].setData($(this).data('fldreportquali'));
            $("#pharmacy-popup-save-button").prop('disabled', true);
            $("#pharmacy-popup-save-button-enable").show();
        })


        $('#pharmacy-popup-save-button-enable').on('click', function () {
            CKEDITOR.instances['extra-order-textarea'].setData('');
            $("#pharmacy-popup-save-button").prop('disabled', false);
            $("#pharmacy-popup-save-button-enable").hide();
        })
    });
    var pharmacyPopup = {
        selectMedicine: function () {
            var drug = $('#pharmacy_route option:selected').val();
            var in_stock = $('#in_stock option:selected').val();
            var generic_brand = $("input[name=generic_brand]:checked").val();

            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order') }}",
                type: "POST",
                data: {drug: drug, generic_brand: generic_brand, in_stock: in_stock},
                success: function (data) {
                    // console.log(data);
                    $('.add_new_medicine').html(data);
                    $("#itemNameToShowDropdown").select2();
                    $("#itemNameToShowDropdown").empty().append(data);
                    $('#pharnmacy_freq').prop('selectedIndex', 0);
                    $('#pharnmacy_freq').prop('selectedIndex', 0);
                    $('#pharnmacy_dose').val(0);
                    // $('#pharnmacy_freq').val(unitdose);
                    $('#pharnmacy_day').val(0);
                    $('#pharnmacy_qty').val(0);
                    $('.pharmacy_item_new_order').val("");
                    // $('#add_new_order').modal({show: true});

                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        clickMedSelect: function (selectData) {
            var drug = $("#itemNameToShowDropdown").val();
            // var drug = $("input[name='neworder_add']:checked").val();
// console.log($(selectData).find(':selected').data('type'))
//             var drugType = $('#pharmacy_route option:selected').val();
            var drugType = $(selectData).find(':selected').data('type');
            $("#route-on-change").val(drugType);
            if (drugType == 'msurg' || drugType == 'ortho') {
                $('.pharmacy_item_new_order').val(drug);
                $('#pharnmacy_dose').prop('disabled', true).val(0);
                $('#pharnmacy_freq').prop('disabled', true);
                $('#pharnmacy_day').prop('disabled', true).val(0);
                $('#pharnmacy_qty').val(0);
                $('#med_ortho_msurge').val('Yes');
                // $('#add_new_order').modal('hide');
            } else {
                $.ajax({
                    url: "{{ route('patient.pharmacy.add.new.order') }}",
                    type: "POST",
                    data: {drug: drug},
                    success: function (data) {
                        // console.log(data);
                        /*unitdose1 = 0;
                        if (data.dose_unit.unitdose !== null) {
                            unitdose1 = data.dose_unit.unitdose;
                        }*/

                        $('.pharmacy_item_new_order').val(data.medicineBrand);
                        $('#pharnmacy_dose').prop('disabled', false);
                        $('#pharnmacy_freq').prop('disabled', false);
                        $('#pharnmacy_day').prop('disabled', false);
                        $('#pharnmacy_dose').val(data.strength);
                        // $('#pharnmacy_freq').val(unitdose1);
                        $('#pharnmacy_day').val(0);
                        $('#pharnmacy_qty').val(0);
                        $('#add_new_order').modal('hide');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }

        },
        clickSaveNewOrder: function () {
            var drug = $("input[name='neworder_add']:checked").val();

            if ($("#request_department_pharmacy").val() === "") {
                showAlert('Please select department.');
                return false;
            }

            // var drugType = $('#pharmacy_route option:selected').val();
            var drugType = $("#itemNameToShowDropdown").find(':selected').data('type');
            if (drugType != 'msurg' && drugType != 'ortho') {
                if ($('#pharnmacy_day').val() == 0 || $('#pharnmacy_day').val() == "") {
                    showAlert('Day cannot be 0.');
                    return false;
                }
                if (isNaN($('#pharnmacy_qty').val()) || isNaN($('#pharnmacy_qty').val())) {
                    showAlert('Day and quantity must be number.');
                    return false;
                }
            }
            if ($('#pharmacy_route').val() === "") {
                showAlert('Route must be selected');
                return false;
            }

            if (drug === '') {
                showAlert('Select Route');
            } else {
                $.ajax({
                    url: "{{ route('patient.pharmacy.save.new.order') }}",
                    type: "POST",
                    data: $('#pharmacy-request-submit').serialize(),
                    success: function (data) {
                        // console.log(data);
                        $('#new_orders_list').empty();
                        $('#new_orders_list').append(data);

                        $('.pharmacy_item_new_order').val(0);
                        $('#pharmacy_route').prop('selectedIndex', 0);
                        $('#pharnmacy_freq').prop('selectedIndex', 0);
                        $('#pharnmacy_dose').val(0);
                        // $('#pharnmacy_freq').val(unitdose);
                        $('#pharnmacy_day').val(0);
                        $('#pharnmacy_qty').val(0);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
        },
        deleteNewOrder: function (fldid) {
            var confirmDelete = confirm('Delete?');
            if (confirmDelete == false) {
                return false;
            }
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.delete') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('#new_orders_list').empty();
                    $('#new_orders_list').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

        },
        changeDate: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order.date.change') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Change Date');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        changeDose: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order.dose') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Change Dose');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        changeDay: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order.day') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Change Day');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        changeQuantity: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order.quantity') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Change Quantity');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        changeFrequency: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.order.frequency') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Change Frequency');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        directDispensing: function (fldid) {
            var confirmDelete = confirm('Dispense?');
            if (confirmDelete == false) {
                return false;
            }
            $.ajax({
                url: "{{ route('patient.pharmacy.form.new.direct.dispensing') }}",
                type: "POST",
                data: {fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('#new_orders_list').empty();
                    $('#new_orders_list').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        selectionListData: function () {

            $.ajax({
                url: "{{ route('patient.pharmacy.selection') }}",
                type: "POST",
                data: {
                    searchName: $('#pharmacy_select_data option:selected').val(),
                    encounterId: $('#fldencounterval').val()
                },
                success: function (data) {
                    // console.log(data);
                    $('#select_pharmacy_list').empty();
                    $('#select_pharmacy_list').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        calculateQuantity: function () {
            if (isNaN($('#pharnmacy_day').val()) || isNaN($('#pharnmacy_qty').val())) {
                showAlert('Day and quantity must be number.');
                return false;
            }
            $.ajax({
                url: "{{ route('patient.pharmacy.calculate.quantity') }}",
                type: "POST",
                data: $('#pharmacy-request-submit').serialize(),
                success: function (data) {
                    $('#pharnmacy_qty').empty().val(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        comment: function (fldid) {
            $.ajax({
                url: "{{ route('patient.pharmacy.add.comment') }}",
                type: "POST",
                data: {fldid: fldid, comment: $('#comment-fldid-' + fldid).val()},
                success: function (data) {
                    if (data.status) {
                        showAlert('Comment added successfully.');
                    } else {
                        showAlert("{{ __('messages.error') }}", 'error');
                    }
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        reorder: function (fldid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.reorder.form') }}",
                type: "POST",
                data: {encounterId: encounterId, fldid: fldid},
                success: function (data) {
                    // console.log(data);
                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Reorder');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        calculateQuantityReorder: function () {
            if (isNaN($('#pharnmacy_day').val()) || isNaN($('#pharnmacy_qty').val())) {
                showAlert('Day and quantity must be number.');
                return false;
            }
            $.ajax({
                url: "{{ route('patient.pharmacy.calculate.quantity') }}",
                type: "POST",
                data: $('#pharmacy-reorder-form').serialize(),
                success: function (data) {
                    // console.log(data);
                    $('#pharnmacy_qty_reorder').val(data);

                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        clickSaveReOrder: function () {
            var drug = $("input[name='neworder_add']:checked").val();

            if ($("#request_department_pharmacy_reorder").val() === "") {
                showAlert('Please select department.');
                return false;
            }

            var drugType = $('#route_reorder').val();
            if (drugType != 'msurg' && drugType != 'ortho') {
                if ($('#pharnmacy_day_reorder').val() == 0 || $('#pharnmacy_day_reorder').val() == "") {
                    showAlert('Day cannot be 0.');
                    return false;
                }
                if (isNaN($('#pharnmacy_qty_reorder').val()) || isNaN($('#pharnmacy_qty_reorder').val())) {
                    showAlert('Day and quantity must be number.');
                    return false;
                }
            }
            if ($('#route_reorder').val() === "") {
                showAlert('Route must be selected');
                return false;
            }

            if (drug === '') {
                showAlert('Select Route');
            } else {
                $.ajax({
                    url: "{{ route('patient.pharmacy.save.new.order') }}",
                    type: "POST",
                    data: $('#pharmacy-reorder-form').serialize(),
                    success: function (data) {
                        // console.log(data);

                        $('#general-modal').modal('hide');
                        $('.general-modal-title').empty();
                        $('.general-form-data').empty();
                        $('#new_orders_list').empty();
                        $('#new_orders_list').append(data);
                        showAlert('Reorder Successful.');
                        $('.nav-tabs a[href="#pharmacy-new-order"]').tab('show');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
        },
        reorderBulk: function () {
            if ($('input[name^=reorder_ids]:checked').length <= 0) {
                showAlert("Select at least one medicine for reorder.");
            } else {
                var selectedReorder = new Array();
                // console.log($('.reorder_bulk').val());
                encounterId = $('#fldencounterval').val();
                department = $('#request_department_pharmacy').val();
                $("input:checkbox[name='reorder_ids']:checked").each(function () {
                    selectedReorder.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('patient.pharmacy.form.reorder.bulk') }}",
                    type: "POST",
                    data: {reorderData: selectedReorder, encounterId: encounterId, department: department},
                    success: function (data) {
                        // console.log(data);
                        $('#general-modal').modal('hide');
                        $('.general-modal-title').empty();
                        $('.general-form-data').empty();
                        $('#new_orders_list').empty();
                        $('#new_orders_list').append(data);
                        showAlert('Reorder Successful.');
                        $('.nav-tabs a[href="#pharmacy-new-order"]').tab('show');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
        },
        saveExtraOrder: function () {
            var extraOrder = CKEDITOR.instances['extra-order-textarea'].getData();
            if (extraOrder === "") {
                showAlert('Extra order cannot be empty', 'error');
                return false;
            }
            var encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.extra.order.save') }}",
                type: "POST",
                data: {encounterId: encounterId, extraOrder: extraOrder},
                success: function (data) {
                    // console.log(data);
                    var extraOrder = CKEDITOR.instances['extra-order-textarea'].setData('');
                    $('.ul-pharmacy-request').empty().html(data.extra_order);

                    showAlert('Extra order created Successful.');
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        deleteExtraOrder: function (fldid) {
            if (fldid === "") {
                showAlert('Extra order cannot be empty', 'error');
                return false;
            }
            if (!confirm("Delete?")) {
                return false;
            }
            var encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.pharmacy.form.extra.order.delete') }}",
                type: "POST",
                data: {fldid: fldid, encounterId: encounterId},
                success: function (data) {
                    // console.log(data);
                    $('.ul-pharmacy-request').empty().html(data.extra_order);

                    showAlert('Extra order created Successful.');
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }

    }

    $('#selectall').change(function () {
        if ($(this).prop('checked')) {
            $('.reorder_bulk').prop('checked', true);
        } else {
            $('.reorder_bulk').prop('checked', false);
        }
    });
</script>
<style>
    #new_orders_list ul li {
        display: block;
        padding: 2px 6px;
    }

    #new_orders_list .dropdown-toggle::after {
        content: none;
    }
</style>
