@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <label for="" class="col-sm-3">Plan For:</label>
                        <div class="col-sm-6">
                            <input type="datetime-local" class="form-control" id="exampleInputdatetime" value="2019-12-19T13:45:00"/>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary full-width"><i class="fa fa-sync" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="from_date" autocomplete="off">
                            <input type="hidden" name="from_date" id="from_date_eng" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" name="department" id="department">
                                <option value="%">%</option>
                                @if($department)
                                @forelse($department as $dept)
                                <option value="{{ $dept->flddept }}">{{ $dept->flddept }}</option>
                                @empty
                                @endforelse
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-primary" onclick="searchFollowUpList()"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>&nbsp;
                            <button class="btn btn-primary" data-toggle="modal" data-target="#search-by-encounter-modal" title="Search By Encounter"><i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
                            <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-sticky-th">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Particulars</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-sticky-th">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Time</th>
                                    <th>Particulars</th>
                                    <th>EncID</th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Contact</th>
                                    <th>Consultant</th>

                                    <th>FileNo</th>
                                </tr>
                            </thead>
                            <tbody id="follow-up-table-list"></tbody>
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
                    <input type="text" name="encounter" id="encounter_search" class="form-control" placeholder="Encounter">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="searchFollowUpListByEncounter()">Search</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateFollowUpDate()">Add</button>
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
        $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
        $('#from_date_eng').val('{{date('Y-m-d')}}');

        $('#date_nepali_consult_follow_up').val(AD2BS('{{date('Y-m-d')}}'));

        $('#date_nepali_consult_follow_up').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

        $('input#consult_timepicker').timepicker({});
    });

    function searchFollowUpList() {
        $('#from_date_eng').val(BS2AD($('#from_date').val()));
        var from_date_eng = $('#from_date_eng').val();
        var department = $('#department').val();
            // alert(txSearch);
            $.ajax({
                url: '{{ route('follow.up.search') }}',
                type: "POST",
                data: {from_date_eng: from_date_eng, department: department, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $("#follow-up-table-list").empty().append(response.html);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function searchFollowUpListByEncounter() {
            var encounter_search = $('#encounter_search').val();
            // alert(txSearch);
            $.ajax({
                url: '{{ route('follow.up.search.by.encounter') }}',
                type: "POST",
                data: {encounter_search: encounter_search, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('#encounter_search').val('');
                    $("#search-by-encounter-modal").modal('toggle');
                    $("#follow-up-table-list").empty().append(response.html);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function changeFollowUpDate(encounter) {
            $('#encounter-id-dynamic').val(encounter);
            $("#followUpModal").modal('toggle');
        }

        function updateFollowUpDate() {
            $('#follow-up-consult-form')[0].reset();
            $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#from_date_eng').val('{{date('Y-m-d')}}');
            $('#date_eng_follow_up').val(BS2AD($('#date_nepali_consult_follow_up').val()));
            $.ajax({
                url: "{{ route('follow.up.update.follow.up.date.time') }}",
                type: "POST",
                data: $('#follow-up-consult-form').serialize(),
                success: function (response) {
                    // console.log(response)
                    if (response.success === true) {
                        $("#follow-up-table-list").empty().append(response.html);
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
    </script>
    @endpush
