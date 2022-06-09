@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        @include('menu::toggleButton')
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Consultant List
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="javascript:;" id="consultant-data-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-3">Date:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="consult_date" class="form-control" id="date_nepali" autocomplete="off">
                                            <input type="hidden" name="date_eng" id="date_eng">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label class="col-3">Dept:</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="department">
                                                <option value="%">%</option>
                                                @if(count($department))
                                                    @forelse($department as $dept)
                                                        <option value="{{$dept->flddept}}">{{$dept->flddept}}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group form-row">
                                        <label class="col-2">Mode:</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="consult_mode">
                                                <option value="%">%</option>
                                                @if(count($modes))
                                                    @foreach($modes as $mode)
                                                        <option value="{{ $mode->fldsetname }}">{{ $mode->fldsetname }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <label class="col-2">Status:</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="status">
                                                <option value="Planned">Planned</option>
                                                <option value="Calling">Calling</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Done">Done</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label class="col-2">Cons:</label>
                                        <div class="col-sm-8">
                                            @php
                                                $consultantList = Helpers::getConsultantList();
                                            @endphp

                                            <select name="consultant" id="consultant" class="form-control select2">
                                                <option value="">--Select--</option>
                                                @if(count($consultantList))
                                                    @foreach($consultantList as $con)
                                                        <option value="{{ $con->username }}">{{ $con->firstname .' '.$con->lastname }}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                        {{--<div class="col-sm-6">
                                            <button class="btn btn-primary"><i class="fa fa-mobile" aria-hidden="true"></i></button>&nbsp;<button class="btn btn-primary"><i class="fa fa-calculator" aria-hidden="true"></i></button>&nbsp;
                                            <button class="btn btn-primary"><i class="fa fa-user" aria-hidden="true"></i></button>
                                        </div>--}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label class="col-3">Billing</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="billing">
                                                <option value="%">%</option>
                                                <option value="Billed">Billed</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <a class="btn btn-primary btn-sm" href="javascript:;" onclick="consultantList.searchData()"><i class="fa fa-sync" aria-hidden="true"></i></a>&nbsp;
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-consult-modal" title="Add Consult"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label class="col-3">Visit</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="visit">
                                                <option value="%">%</option>
                                                <option value="New">New</option>
                                                <option value="Old">Old</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search-by-encounter-modal" title="Search By Encounter"><i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search-by-name-modal" title="Search By Name"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <a class="btn btn-primary btn-sm" href="javascript:;" onclick="consultantList.searchData()"><i class="fa fa-sync" aria-hidden="true"></i></a>&nbsp;
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-consult-modal" title="Add Consult"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="form-group form-row">
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search-by-encounter-modal" title="Search By Encounter"><i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search-by-name-modal" title="Search By Name"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-dispensing">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Date/Time</th>
                                    <!-- <th>Department</th> -->
                                    <th>EncId</th>
                                    <th>Patient Detail</th>
                                    <!-- <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Contact</th> -->
                                    <th>Doctor Detail</th>
                                    <th>Comment</th>
                                    <th>FileNo</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="consultant-list-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--search by encounter--}}
    <div class="modal fade" id="search-by-encounter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="javascript:;" id="search-by-encounter-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search by Encounter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Encounter</label>
                        <input type="text" name="encounter" class="form-control" placeholder="Encounter">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="consultantList.searchByEncounter()">Search</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--search by patient name--}}
    <div class="modal fade" tabindex="-1" id="search-by-name-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="javascript:;" id="search-by-name-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Search by Patient Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Name</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="consultantList.searchByName()">Search</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--search by patient name--}}
    <div class="modal fade" tabindex="-1" id="add-consult-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="javascript:;" id="add-consult-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Consult</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="" class="col-3">Encounter</label>
                            <input type="text" name="encounter" id="encounter" class="form-control col-4" placeholder="Encounter">
                            <div class="col-4">
                                <a href="javascript:;" class="brn btn-primary btn-sm" onclick="consultantList.getEncounterData()"><i class="fa fa-play fa-sm" aria-hidden="true"></i> Show</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Full Name</label>
                            <input type="text" id="fullname" name="fullname" class="form-control col-8" placeholder="Full Name">
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Billing Mode</label>
                            <select class="form-control col-8" name="billing_mode" id="billing_mode">
                                <option value="">--Select--</option>
                                @if(count($modes))
                                    @foreach($modes as $mode)
                                        <option value="{{ $mode->fldsetname }}">{{ $mode->fldsetname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Department</label>
                            <select name="department" id="department" class="form-control col-8">
                                <option value="">--Select--</option>
                                @if(count($departmentConsult))
                                    @forelse($departmentConsult as $dept)
                                        <option value="{{ $dept->flddept }}">{{ $dept->flddept }}</option>
                                    @empty
                                    @endforelse
                                @endif
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Date</label>
                            <input type="text" name="consult_date_add" class="form-control col-8" id="date_nepali_consult" placeholder="Consult Date">
                            <input type="hidden" name="date_eng_add" id="date_eng_add">
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Time</label>
                            <input type="text" name="consult_time_add" class="form-control col-8" id="consult_timepicker" placeholder="Consult Time">
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Consultant</label>
                            @php
                                $consultantList = Helpers::getConsultantList();
                            @endphp
                            <select name="consultant_add" id="consultant_add" class="form-control col-8">
                                <option value="">--Select--</option>
                                @if(count($consultantList))
                                    @foreach($consultantList as $con)
                                        <option value="{{ $con->username }}">{{ $con->firstname .' '.$con->lastname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Comment</label>
                            <textarea name="comment" class="form-control col-8" id="comment_add" rows="2" placeholder="Comment"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="consultantList.consultationCreate()">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--modal edit--}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="edit-form"></div>
            </div>
        </div>
    </div>

    {{--modal follow up--}}
    <div class="modal fade" id="followUpModal" tabindex="-1" role="dialog" aria-labelledby="followUpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="javascript:;" id="follow-up-consult-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followUpModalLabel">Consult Time</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <input type="hidden" id="encounter-id-dynamic" name="encounter_id_follow_up">
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Date</label>
                            <input type="text" name="consult_date_follow_up" class="form-control col-8" id="date_nepali_consult_follow_up" placeholder="Consult Date">
                            <input type="hidden" name="date_eng_follow_up" id="date_eng_follow_up" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">Time</label>
                            <input type="text" name="consult_time_edit" class="form-control col-8" id="consult_timepicker" placeholder="Consult Time" value="{{ date('H:i') }}">
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3">After</label>
                            <input type="number" name="after_days" class="form-control col-8" id="after_days">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="consultantList.addFollowUpDate()">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script type="text/javascript">
        $(window).ready(function () {
            $('#date_nepali').val(AD2BS('{{date('Y-m-d')}}'));
            $('#date_nepali_consult').val(AD2BS('{{date('Y-m-d')}}'));
            $('#date_nepali_consult_follow_up').val(AD2BS('{{date('Y-m-d')}}'));

            $('#date_nepali_consult_follow_up').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('#date_nepali').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('#date_nepali_consult').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('input#consult_timepicker').timepicker({});
        });
        var consultantList = {
            searchData: function () {
                $('#date_eng').val(BS2AD($('#date_nepali').val()));
                // consultant-data-form
                $.ajax({
                    url: "{{ route('consultantlist.search.data') }}",
                    type: "POST",
                    data: $('#consultant-data-form').serialize(),
                    success: function (response) {
                        // console.log(response);
                        $('#consultant-list-table').empty().append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            searchByEncounter: function () {
                // consultant-data-form
                $.ajax({
                    url: "{{ route('consultantlist.search.data.by.encounter') }}",
                    type: "POST",
                    data: $('#search-by-encounter-form').serialize(),
                    success: function (response) {
                        $('#consultant-list-table').empty().append(response);
                        $("#search-by-encounter-modal").modal("toggle");
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            searchByName: function () {
                // consultant-data-form
                $.ajax({
                    url: "{{ route('consultantlist.search.data.by.name') }}",
                    type: "POST",
                    data: $('#search-by-name-form').serialize(),
                    success: function (response) {
                        $('#consultant-list-table').empty().append(response);
                        $("#search-by-name-modal").modal("toggle");
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            getEncounterData: function () {
                // consultant-data-form
                $.ajax({
                    url: "{{ route('consultantlist.encounter.data') }}",
                    type: "POST",
                    data: {encounter: $('#encounter').val()},
                    success: function (response) {
                        $('#fullname').empty().val(response.fullName);
                        $('#billing_mode option:contains(' + response.billingMode + ')').attr('selected', 'selected');
                        $('#department option:contains(' + response.currLocation + ')').attr('selected', 'selected');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            consultationCreate: function () {
                $('#date_eng_add').val(BS2AD($('#date_nepali_consult').val()));
                $.ajax({
                    url: "{{ route('consultantlist.consultant.create') }}",
                    type: "POST",
                    data: $('#add-consult-form').serialize(),
                    success: function (response) {
                        if (response.success === true) {
                            showAlert(response.message);
                        } else {
                            showAlert(response.message, 'error');
                        }
                        $("#add-consult-modal").modal("toggle");
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            consultantListUpdate: function () {
                $('#date_eng_edit').val(BS2AD($('#date_nepali_consult_edit').val()));
                $.ajax({
                    url: "{{ route('consultantlist.consultant.list.update') }}",
                    type: "POST",
                    data: $("#edit-consult-form").serialize(),
                    success: function (response) {
                        if (response.success === true) {
                            showAlert(response.message);
                        } else {
                            showAlert(response.message, 'error');
                        }
                        // $('#edit-form').empty().html(response);
                        $("#editModal").modal("toggle");
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        // console.log(xhr);
                    }
                });
            },
            followUpDateAdd: function (encounter) {
                $('#encounter-id-dynamic').val(encounter);
                $('#followUpModal').modal('toggle');
            },
            addFollowUpDate: function () {

                $('#date_eng_follow_up').val(BS2AD($('#date_nepali_consult_follow_up').val()));
                $.ajax({
                    url: "{{ route('consultantlist.consultant.follow.up.date.add') }}",
                    type: "POST",
                    data: $('#follow-up-consult-form').serialize(),
                    success: function (response) {
                        // console.log(response)
                        if (response.success === true) {
                            showAlert(response.message);
                        } else {
                            showAlert(response.message, 'error');
                        }
                        $("#followUpModal").modal("toggle");
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        // console.log(xhr);
                    }
                });
            }
        }

        function editConsultantList(fldid) {
            $.ajax({
                url: "{{ route('consultantlist.consultant.edit') }}",
                type: "POST",
                data: {fldid: fldid},
                success: function (response) {
                    $('#edit-form').empty().html(response);
                    $("#editModal").modal("toggle");
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    // console.log(xhr);
                }
            });
        }

    </script>

@endpush
