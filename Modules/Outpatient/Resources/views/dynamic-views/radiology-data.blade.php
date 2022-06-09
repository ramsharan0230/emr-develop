<form action="{{ route('patient.radiology.form.save.waiting') }}" class="radiology-request-submit radiology-form container-fluid" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="flditemtype" value="Radio Diagnostics">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-3">Refer By:</label>
                <div class="col-sm-9">
                    <select name="referer_by" id="refer_by_change_radio" class="form-control">
                        <option value=""></option>
                        @if(count($refer_by))
                            @foreach($refer_by as $refer)
                                <option value="{{ $refer->username }}">{{ $refer->firstname.' '. $refer->lastname }}</option>
                            @endforeach
                        @endif
                    </select>
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
                            <a class="nav-link active" id="radiology-pending-tab" data-toggle="tab" href="#radiology-pending" role="tab" aria-controls="radiology-pending" aria-selected="true">Pending </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="radiology-request-tab" data-toggle="tab" href="#radiology-request" role="tab" aria-controls="radiology-request" aria-selected="false">Request </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="radiology-reported-tab" data-toggle="tab" href="#radiology-reported" role="tab" aria-controls="radiology-reported" aria-selected="false">Reported </a>
                        </li>
                    </ul>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="radiology-pending" role="tabpanel" aria-labelledby="radiology-pending-tab">
                        <div class="res-table">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th>Date Time</th>
                                    <th>Test Name</th>
                                    <th>ReferBy</th>
                                    <th>Target</th>
                                </tr>
                                </thead>
                                <tbody id="pending-list-radio">
                                @if(count($patBilling))
                                    @foreach($patBilling as $pat)
                                        @if($pat->fldstatus == 'Done')
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
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="my-3">Sampled But Not Reported</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="res-table">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="tittle-th">Specimen</th>
                                            <th class="tittle-th">Test Name</th>
                                            <th class="tittle-th">Sample</th>
                                            <th class="tittle-th">Method</th>
                                            <th class="tittle-th">Sample Date</th>
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
                    <div class="tab-pane fade" id="radiology-request" role="tabpanel" aria-labelledby="radiology-request-tab">
                        <div class="row top-req">
                            <div class="col-md-4">
                                <input id="search-new-request-radio" class="form-control mb-2" type="text" placeholder="Search" autocomplete="off">
                                <div class="" id="style-1">
                                    <ul class="res-table list-group red-table" id="search-new-request-table">
                                        @if(count($itemsForMultiselect))
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach($itemsForMultiselect as $fldName)
                                                <li class="list-group-item" style="padding: .2rem;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input lab-radio-check" id="items{{ $counter }}" value="{{ $fldName->fldgroupname }}" name="labreport[]">
                                                        <label class="custom-control-label">{{ $fldName->fldgroupname }}</label>
                                                    </div>
                                                </li>
                                                @php
                                                    $counter++;
                                                @endphp
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <select name="" id="list-by-group" onchange="requestedRadiology.listByGroup();" class="form-control">
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
                                <div class="group__box d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary disableInsertUpdate" id="save-request-waiting"><i class="fas fa-caret-left"></i> <i class="fas fa-caret-right"></i></button>

                                    <div>
                                        <!-- <a class="btn  default-btn btn_button disableInsertUpdate" href="javascript:void(0)"><i class="fas fa-share"></i>&nbsp;Re-order</a> -->
                                        <a href="javascript:void(0)" class="btn btn-primary btn-action disableInsertUpdate" onclick="insertUpdateRequestRadio.updateRequest();"><i class="fas fa-save"></i>&nbsp;Save</a>

                                        <a href="javascript:void(0)" class="btn-danger btn-action btn  disableInsertUpdate" onclick="insertUpdateRequestRadio.cancelRequest()"><i class="fas fa-times"></i>&nbsp;Cancel</a>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="res-table">
                                            <table class="table table-bordered table-striped table-hover append-request-data-radiology">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>Date Time</th>
                                                    <th>Test Name</th>
                                                    <th>Status</th>
                                                    <th>Comment</th>
                                                    <th>Target</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody class="radiology-request-table" id="patbillingData">
                                                @if(count($patBillingListPunched))
                                                    @foreach($patBillingListPunched as $bill)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="radiology-request-check[]" value="{{ $bill->fldid }}">
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="fldid-request[]" value="{{ $bill->fldid }}">
                                                                {{ $bill->fldordtime }}
                                                            </td>
                                                            <td>
                                                                {{ $bill->flditemname }}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="status-request[]" value="{{ $bill->fldstatus }}">
                                                                {{ $bill->fldstatus }}
                                                            </td>
                                                            <td>
                                                                <textarea name="commentRadio" class="commentRadio" cols="20" rows="3" onblur="insertUpdateRequestRadio.comment({{ $bill->fldid }});" id="comment-fldid-{{ $bill->fldid }}">{{ $bill->fldreason }}</textarea>
                                                            </td>
                                                            <td>
                                                                {{ $bill->fldtarget }}
                                                            </td>
                                                            <td>
                                                                <a href="javascript:;" onclick="insertUpdateRequestRadio.deleteRequestedData('{{ $bill->fldid }}')">
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
                        </div>
                    </div>
                    <div class="tab-pane fade" id="radiology-reported" role="tabpanel" aria-labelledby="radiology-reported-tab">
                        <div class="row top-req">
                            <div class="col-md-4">
                                <div class="si-metric">
                                    {{--<input type="radio" name="si-metric" id="si-unit" value="si">
                                    <label for="si-unit">SI Unit</label>

                                    <input type="radio" name="si-metric" id="si-metric" value="metric">
                                    <label for="si-metric">Metric</label>--}}

                                </div>
                                <ul class="list-group res-table">
                                    @if(count($patlabtestRequest))
                                        @foreach($patlabtestRequest as $pltr)
                                            <li class="list-group-item">
                                                <a href="javascript:void(0)" onclick="requestedRadiology.reportedSelected('{{ $pltr->col }}')">{{ $pltr->col }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-8">
                                <div class="res-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Method</th>
                                            <th></th>
                                            <th>Observation</th>
                                            <th>Status</th>
                                            <th>ReportedTime</th>
                                        </tr>
                                        </thead>
                                        <tbody class="radiology-append-reported">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</form>

<script type="text/javascript">
    var insertUpdateRequestRadio = {
        insertRequest: function () {
            $.ajax({
                url: $('.radiology-request-submit').attr('action'),
                type: $('.radiology-request-submit').attr('method'),
                data: $('.radiology-request-submit').serialize()+"&billing_mode="+$("#billingmode").val(),
                success: function (data) {
                    // alert('Submitted');
                    // console.log(data)
                    $('.lab-radio-check').prop("checked", false);
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        updateRequest: function () {
            $.ajax({
                url: "{{ route('patient.radiology.form.save.done') }}",
                type: "POST",
                data: $('.radiology-request-submit').serialize(),
                success: function (data) {

                    console.log(data);
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data.done);
                    $('#pending-list-radio').empty();
                    $('#pending-list-radio').append(data.cancelled);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        cancelRequest: function () {
            $.ajax({
                url: "{{ route('patient.radiology.cancel.radiology.reported') }}",
                type: "POST",
                data: $('.radiology-request-submit').serialize(),
                success: function (data) {
                    // console.log(data);
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                    $('#pending-list-radio').empty();
                    $('#pending-list-radio').append(data);
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
                url: "{{ route('patient.radiology.delete.radiology.reported') }}",
                type: "POST",
                data: {fldid: fldid, encounterId:encounterId},
                success: function (data) {
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                    $('#pending-list-radio').empty();
                    $('#pending-list-radio').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        comment:function (fldid) {
            $.ajax({
                url: "{{ route('patient.radiology.comment.request') }}",
                type: "POST",
                data: {fldid: fldid, comment:$('#comment-fldid-'+fldid).val()},
                success: function (data) {
                    if(data.status){
                        showAlert('Comment added successfully.');
                    }else{
                        showAlert("{{ __('messages.error') }}", 'error');
                    }
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    };

    var requestedRadiology = {
        reportedSelected: function (fldtestid) {
            encounterId = $('#fldencounterval').val();
            $.ajax({
                url: "{{ route('patient.radiology.list.radiology.reported') }}",
                type: "POST",
                data: {encounterId: encounterId, fldtestid: fldtestid},
                success: function (data) {
                    // console.log(data);
                    $('.radiology-append-reported').empty();
                    $('.radiology-append-reported').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        listByGroup: function () {
            $.ajax({
                url: "{{ route('patient.radiology.request.list.by.group') }}",
                type: "POST",
                data: {billingmode: $('#billingmode').val(), fldgroup: $('#list-by-group').val()},
                success: function (data) {
                    console.log(data);
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
            insertUpdateRequestRadio.insertRequest();
        });

        $('#save-request').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequestRadio.updateRequest();
        });
        $("#search-new-request-radio").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#search-new-request-table li").filter(function () {
                let item = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(item);
            });
        });
    });
</script>
