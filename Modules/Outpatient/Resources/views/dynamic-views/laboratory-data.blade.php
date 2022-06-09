<form action="{{ route('patient.laboratory.form.save.waiting') }}" class="laboratory-form"
      id="laboratory-request-submit" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="flditemtype" value="Diagnostic Tests">
    <input type="hidden" name="patientLocation" value="{{ $encounterData->fldcurrlocat }}">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Refer By:</label>
                {{--                <input type="text" readonly class="form-input" id="staticEmail" value="">--}}
                {{-- <div class="col-sm-10">
                    <input type="text" readonly class="form-input" id="staticEmail" value="">
                </div> --}}
                <div class="col-sm-10">
                    <select name="referer_by" id="refer_by_change_lab" class="form-control">
                        <option value=""></option>
                        @if(count($refer_by))
                            @foreach($refer_by as $refer)
                                <option
                                    value="{{ $refer->username }}">{{ $refer->firstname.' '. $refer->lastname }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <ul class="nav nav-tabs" id="yourTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="lab-pending-tab" data-toggle="tab" href="#lab-pending" role="tab"
                           aria-controls="lab-pending" aria-selected="true">Pending </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lab-request-tab" data-toggle="tab" href="#lab-request" role="tab"
                           aria-controls="lab-request" aria-selected="false">Request </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lab-reported-tab" data-toggle="tab" href="#lab-reported" role="tab"
                           aria-controls="lab-reported" aria-selected="false">Reported </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="extra-request" data-toggle="tab" href="#extra-lab-request" role="tab"
                           aria-controls="extra-lab-request" aria-selected="false">Extra Lab Request </a>
                    </li>
                </ul>
                <div class="tab-content" id="nav-tabContent">

                    {{--Extra Lab Request--}}
                    <div class="tab-pane fade" id="extra-lab-request" role="tabpanel"
                         aria-labelledby="extra-lab-request-tab">
                        <div class="row mt-4">
                            <div class="col-md-8">
                                <textarea name="extra-order" class="select2" id="extra-lab-textarea" cols="30" rows="10"></textarea>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-primary btn-action disableInsertUpdate"
                                   onclick="saveExtraOrder()"><i class="fas fa-save"></i>&nbsp;Save</a>
                            </div>
                        </div>
                    </div>
                    {{--Extra Lab Request--}}

                    {{--pending--}}
                    <div class="tab-pane fade show active" id="lab-pending" role="tabpanel"
                         aria-labelledby="lab-pending-tab">
                        <div class="res-table top-req">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Date Time</th>
                                    <th>Test Name</th>
                                    <th>ReferBy</th>
                                </tr>
                                </thead>
                                <tbody id="pending-list-lab-done">
                                @if(count($patBilling))
                                    @foreach($patBilling as $pat)
                                        @if($pat->fldstatus == 'Done')
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pat->fldtime }}</td>
                                                <td>{{ $pat->flditemname }}</td>
                                                <td>{{ $pat->fldrefer }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-1">Sampled But Not Reported</h5>
                            </div>
                        </div>
                        <div class="row top-req" style="margin-top: 0">
                            <div class="col-md-12">
                                <div class="res-table">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Specimen</th>
                                            <th>Test Name</th>
                                            <th>Sample</th>
                                            <th>Method</th>
                                            <th>Sample Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($patBilling))
                                            @foreach($patBilling as $pat)
                                                @if($pat->fldstatus == 'Cleared')
                                                    <tr>
                                                        <td>{{ $pat->fldtime }}</td>
                                                        <td>{{ $pat->flditemname }}</td>
                                                        <td>{{ $pat->fldrefer }}</td>
                                                        <td>{{ $pat->fldtarget }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end pending--}}
                    {{--request--}}
                    <div class="tab-pane fade" id="lab-request" role="tabpanel" aria-labelledby="lab-request-tab">
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <input id="search-new-request-lab" type="text" class="form-control"
                                       placeholder="Search.." autocomplete="off">
                                <div id="style-1 mt-2 mb-2">
                                    <ul class="res-table list-group" id="search-new-request-table"
                                        style="padding: .2rem;">
                                        @if(count($itemsForMultiselect))
                                            @foreach($itemsForMultiselect as $fldName)
                                                <li class="list-group-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="labreport[]"
                                                               class="custom-control-input lab-radio-check"
                                                               {{--id="items{{ $counter }}" --}}value="{{ $fldName->fldgroupname }}">
                                                        <label
                                                            class="custom-control-label">{{ $fldName->fldgroupname }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>


                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <select name="" id="list-by-group" onchange="requestedLaboratory.listByGroup();"
                                            class="form-control">
                                        <option value="">Select Group By</option>
                                        @if(count($costGroup))
                                            @foreach($costGroup as $CG)
                                                <option value="{{ $CG->fldgroup }}">{{ $CG->fldgroup }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-1">
                                <button type="button" class="btn btn-primary btn-sm disableInsertUpdate" id="save-request-waiting" style="display: inline-block;"><i class="fas fa-caret-left"></i> <i class="fas fa-caret-right"></i></button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary btn-sm disableInsertUpdate"
                                            id="save-request-waiting"
                                            style="display: inline-block; margin-right: 79px;"><i
                                            class="fas fa-caret-left"></i> <i class="fas fa-caret-right"></i></button>

                                    <div>
                                        <!-- <a class="btn btn-primary btn-action btn_button disableInsertUpdate" href="javascript:void(0)"><i class="fas fa-share"></i>&nbsp;Re-order</a> -->
                                        <a href="javascript:void(0)"
                                           class="btn btn-primary btn-action disableInsertUpdate"
                                           onclick="insertUpdateRequestLab.updateRequest();"><i class="fas fa-save"></i>&nbsp;Save</a>
                                        <a href="javascript:void(0)"
                                           class="btn btn-danger btn-action  disableInsertUpdate"
                                           onclick="insertUpdateRequestLab.cancelRequest()"><i class="fas fa-times"></i>&nbsp;Cancel</a>
                                    </div>
                                </div>
                                <div class="res-table mt-2">
                                    <table
                                        class="table table-bordered append-request-data-laboratory table-hover table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th></th>
                                            <th>Date Time</th>
                                            <th>Test Name</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody class="laboratory-request-table" id="patbillingData">
                                        @if(count($patBillingListPunched))
                                            @foreach($patBillingListPunched as $bill)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="laboratory-request-check[]"
                                                                   value="{{ $bill->fldid }}"
                                                                   class="custom-control-input">
                                                            <label for="" class="custom-control-label"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="hidden" name="fldid-request[]"
                                                                   value="{{ $bill->fldid }}"
                                                                   class="custom-control-input">
                                                            <label for="" class="">{{ $bill->fldordtime }}</label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        {{ $bill->flditemname }}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="status-request[]"
                                                               value="{{ $bill->fldstatus }}">
                                                        {{ $bill->fldstatus }}
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;"
                                                           onclick="insertUpdateRequestLab.deleteRequestedData('{{ $bill->fldid }}')">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end request--}}
                    {{--reported--}}
                    <div class="tab-pane fade" id="lab-reported" role="tabpanel" aria-labelledby="lab-reported-tab">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="list-group">
                                    @if(count($patlabtestRequest))
                                        @foreach($patlabtestRequest as $pltr)
                                            <li class="list-group-item">
                                                <a href="javascript:void(0)"
                                                   onclick="requestedLaboratory.reportedSelected('{{ $pltr->col }}')">{{ $pltr->col }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-8">
                                <div class="res-table">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Specimen</th>
                                            <th>Method</th>
                                            <th></th>
                                            <th>Observation</th>
                                            <th>Status</th>
                                            <th>SampleTime</th>
                                            <th>ReportedTime</th>
                                        </tr>
                                        </thead>
                                        <tbody class="laboratory-append-reported">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end reported--}}


                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
    });
    var insertUpdateRequestLab = {
        updateRequest: function () {
            $.ajax({
                url: "{{ route('patient.laboratory.form.save.done') }}",
                type: "POST",
                data: $('#laboratory-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                success: function (data) {
                    // console.log(data);
                    $('#pending-list-lab-done').empty();
                    $('#patbillingData').empty();

                    $('#pending-list-lab-done').append(data.done);
                    $('#patbillingData').append(data.punched);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        insertRequest: function () {
            $.ajax({
                url: $('#laboratory-request-submit').attr('action'),
                type: $('#laboratory-request-submit').attr('method'),
                data: $('#laboratory-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                success: function (data) {
                    // alert('Submitted');
                    // console.log(data)
                    $('#patbillingData').empty();
                    $('.lab-radio-check').prop("checked", false);
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },

        cancelRequest: function () {
            $.ajax({
                url: "{{ route('patient.laboratory.cancel.laboratory.reported') }}",
                type: "POST",
                data: $('#laboratory-request-submit').serialize() + "&billing_mode=" + $("#billingmode").val(),
                success: function (data) {
                    // console.log(data);
                    $('#pending-list-lab').empty();
                    $('#pending-list-lab').append(data);
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        deleteRequestedData: function (fldid) {
            confirmDelete = confirm("Delete?");

            if (confirmDelete === false) {
                return false;
            }
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.laboratory.delete.laboratory.requested') }}",
                type: "POST",
                data: {fldid: fldid, encounterId: encounterId},
                success: function (data) {
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        }
    };

    var requestedLaboratory = {
        reportedSelected: function (fldtestid) {
            // unitMedecine = document.querySelector('input[name="si-metric"]:checked').value;
            unitMedecine = '';
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.laboratory.list.laboratory.reported') }}",
                type: "POST",
                data: {encounterId: encounterId, fldtestid: fldtestid, MedUnit: unitMedecine},
                success: function (data) {
                    // console.log(data);
                    $('.laboratory-append-reported').empty();
                    $('.laboratory-append-reported').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        listByGroup: function () {
            $.ajax({
                url: "{{ route('patient.laboratory.request.list.by.group') }}",
                type: "POST",
                data: {billingmode: $('#billingmode').val(), fldgroup: $('#list-by-group').val()},
                success: function (data) {
                    // console.log(data);
                    $('#search-new-request-table').empty();
                    $('#search-new-request-table').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    }

    $(document).ready(function () {



        $('#save-request-waiting').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequestLab.insertRequest();
        });

        $('#save-request').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequestLab.updateRequest();
        });
        $("#search-new-request-lab").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#search-new-request-table li").filter(function () {
                let item = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(item);
            });
        });

    });

   function saveExtraOrder () {
        var extraOrder = CKEDITOR.instances['extra-lab-textarea'].getData();
        var encounterId = $('#fldencounterval').val();
        $.ajax({
            url: "{{ route('patient.laboratory.request.save.extra') }}",
            type: "POST",
            data: {encounterId: encounterId, extraOrder: extraOrder},
            success: function (data) {
                // console.log(data);
                $('#general-modal').modal('hide');
                $('.general-modal-title').empty();
                $('.general-form-data').empty();
                $('#new_orders_list').empty().append(data);
                showAlert('Extra order created Successful.');
            },
            error: function (xhr, err) {
                console.log(xhr);
            }
        });
    }

    CKEDITOR.replace('extra-lab-textarea', {height:'200px'});



</script>
