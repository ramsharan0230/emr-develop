@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
    @php
        $disableClass = 'disableInsertUpdate';
    @endphp
@else
    @php
        $disableClass = '';
    @endphp
@endif

@php
    $segment = Request::segment(1);

@endphp
<div class="row">
    <div class="col-md-6">
        <div class="form-group form-row align-items-center">
            <label for="name" class="col-sm-2">Name</label>
            <div class="col-sm-10">
                <input type="text" name="searchName" class="form-control" id="name" placeholder="Name" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{  $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{  $patient->fldptnamelast }}@endif" readonly>
            </div>
        </div>
        <div class="form-group form-row align-items-center">
            <label for="gender" class="col-sm-2">Gender</label>
            <div class="col-sm-10">
                <input type="text" name="gender" class=" form-control" id="gender" placeholder="Gender" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" readonly>
            </div>
            {{-- <div class="col-sm-5">
                <input type="text" name="gender" class="col-sm-5 form-control form-control" id="gender" placeholder="Gender" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" readonly>
            </div> --}}
        </div>
        <div class="form-group form-row align-items-center">
            <label for="gender" class="col-sm-2">Mode</label>
            <div class="col-sm-10">
                <input type="text" name="mode" class=" form-control" id="mode" placeholder="Mode" value="{{(isset($enpatient) and !empty($enpatient)) ? Helpers::getBillingMode($enpatient->fldencounterval) : ''}}" readonly>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group form-row align-items-center">
            <label for="gender" class="col-sm-3">Refer By</label>
            <div class="col-sm-9">
                <input type="text" name="refer_by" class="form-control" id="refer" placeholder="" value="@if(isset($enpatient)){{ $enpatient->flduserid }} @elseif(Helpers::getCurrentRole($segment) == '1') @endif">
            </div>
        </div>
        <div class="form-group form-row align-items-center">
            <label for="bedno" class="col-sm-3">Plan Date</label>
            <div class="col-sm-9">
                <input type="text" name="plan_date" class="form-control" id="plan_date" placeholder="Bed No" value="{{date('Y-m-d H:i:s')}}">
            </div>
        </div>

    </div>
    <div class="modal fade" id="consultant_list" tabindex="-1" role="dialog" aria-labelledby="consultant_listLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="consultant_listLabel" style="text-align: center;">Choose Consultants</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @if(!empty($consultants))
                            @foreach($consultants as $con)
                                <div class="form-modal">
                                    <input type="radio" name="consultant" class="consultant_choosed form-control" value="{{ $con->fldusername }}">
                                    <label> {{ $con->fldusername }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitconsultant_list" url="{{route('save_consultant')}}" data-dismiss="modal">Save changes</button>


                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <div class="">
            <nav>
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pendingtab" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="requested-tab" data-toggle="tab" href="#requestedtab" role="tab" aria-controls="requested" aria-selected="false">Requested</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="status-tab" data-toggle="tab" href="#statustab" role="tab" aria-controls="status" aria-selected="false">Status</a>
                    </li>

                </ul>
            </nav>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="pendingtab" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="res-table">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Procedure</th>
                                <th>Refer By</th>
                            </tr>
                            </thead>
                            <tbody id="pendingData">
                            @if(isset($pending_data) and count($pending_data) > 0)
                                @foreach($pending_data as $k=>$data)
                                    <tr>
                                        @php
                                            $sn = $k+1;
                                        @endphp
                                        <td>{{$sn}}</td>
                                        <td>{{$data->fldtime}}</td>
                                        <td>{{$data->flditemname}}</td>
                                        <td>{{$data->fldrefer}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="requestedtab" role="tabpanel" aria-labelledby="requested-tab">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="res-table">
                                <table class="table table-sm table-bordered datatable">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Group Name</th>
                                    </tr>
                                    </thead>
                                    <tbody class="groupData">
                                    @if(isset($requestGroup_data) and count($requestGroup_data) > 0)

                                        @foreach($requestGroup_data as $data)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="request_group" class="request_group" value="{{ $data->fldgroupname }}">
                                                </td>
                                                <td>{{ $data->fldgroupname }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" onclick="punchProcedure()"><i class="ri-arrow-right-line"></i></button>
                        </div>
                        <div class="col-md-7">
                            <div class="row" style="margin-top: 0;">
                                <div class="col-md-6">
                                    <!-- <input type="checkbox" name="add_components" id="add_components">Add Components -->
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-primary" onclick="saveExtraProcedure()"><i class="ri-check-fill"></i>&nbsp;Save</button>
                                </div>
                            </div>
                            <div class="res-table mt-2">
                                <table class="table table-striped table-hover table-bordered datatable">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Date Time</th>
                                        <th>Procedure</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="punched_data">
                                    @if(isset($punched_data) and count($punched_data) > 0)
                                        @foreach($punched_data as $data)
                                            <tr>
                                                <td><input type="checkbox" checked="checked" name="punched_procedure" value="{{$data->fldid}}" class="punched_procedure" style="display:none;"></td>
                                                <td>{{$data->fldordtime}}</td>
                                                <td>{{$data->flditemname}}</td>
                                                <td>{{$data->fldstatus}}</td>
                                                <td><a href="javascript:void(0);" onclick="deletePunchedProcedure({{$data->fldid}})"><i class="fas fa-trash-alt"></i></a></td>
                                            </tr>
                                        @endforeach
                                        <tr></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="statustab" role="tabpanel" aria-labelledby="status-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="general_procedure" id="general_procedure">
                                <option value="">Select</option>
                                @if(isset($patgeneraldata) and count($patgeneraldata) > 0)
                                    @foreach($patgeneraldata as $patgendata)
                                        <option value="{{$patgendata->flditem}}">{{$patgendata->flditem}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary" onclick="listPlannedData()"><i class="fas fa-sync"></i>&nbsp; &nbsp;Refresh</button>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Procedure</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th></th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody id="planned_Data"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
    $('#plan_date').datetimepicker({

        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd H:i:s'

    });

    function punchProcedure() {
        // alert('punch procedure');
        if ($('.request_group').is(':checked')) {
            var groups = [];
            $.each($("input[name='request_group']:checked"), function () {
                groups.push($(this).val());

            });
            $.ajax({
                url: '{{ route('patient.request.menu.addextraprocedure') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val(), referto: $('#refer').val(), procedures: groups.join(",")},
                success: function (response) {
                    if (response.html === "Error") {
                        showAlert('Something went wrong..');
                    } else {
                        $('#punched_data').empty().html(response.html);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        } else {
            alert('Choose Procedure');
        }

    }

    $('.groupData tr').click(function () {
        $(this).find('input:checkbox').each(function () {
            if (this.checked) this.checked = false; // toggle the checkbox
            else this.checked = true;
            // this.checked = true;
        })
    });

    function deletePunchedProcedure(val) {

        if (confirm('Are you sure ?')) {
            $.ajax({
                url: '{{ route('patient.request.menu.deleteextraprocedure') }}',
                type: "POST",
                data: {procId: val, encounterId: $('#encounter_id').val()},
                success: function (response) {

                    $('#punched_data').empty().html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            return false
        }
    }

    function saveExtraProcedure() {
        if ($('.punched_procedure').is(':checked')) {
            var procedures = [];
            $.each($("input[name='punched_procedure']:checked"), function () {
                procedures.push($(this).val());
            });
            // alert(procedures.join(", "))
            $.ajax({
                url: '{{ route('patient.request.menu.saveextraprocedure') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val(), referto: $('#refer').val(), procedures: procedures.join(",")},
                success: function (response) {
                    if (response.html === "Error") {
                        showAlert('Something went wrong..');
                    } else {

                        $('#punched_data').empty().html(response.html);
                        $('#pendingData').empty().html(response.phtml);
                        $('#general_procedure').append().html(response.shtml);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            alert('Choose Procedure');
        }
    }

    function listPlannedData() {
        var value = $('#general_procedure').val();
        alert(value);
        $.ajax({
            url: '{{ route('patient.request.menu.listplanneddata') }}',
            type: "POST",
            data: {encounterId: $('#encounter_id').val(), referto: $('#refer').val(), searchdata: value},
            success: function (response) {

                $('#planned_Data').empty().html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>
