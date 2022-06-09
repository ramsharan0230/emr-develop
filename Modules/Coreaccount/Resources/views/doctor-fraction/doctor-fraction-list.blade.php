@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        @if ($message = Session::get('success_message'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error_message'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account List for Not Mapped
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>From
                                        <form action="javascript:;" id="sync-form-all" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control form-control-sm from_date" autocomplete="off" id="fromall">
                                                    <input type="hidden" name="from_date" class="from_date_eng" id="from_engall" value="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control form-control-sm to_date" autocomplete="off" id="toall">
                                                    <input type="hidden" name="to_date" class="to_date_eng" value="{{ date('Y-m-d') }}" id="to_engall">
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="float-right">
                                                        <button type="button" onclick="syncAccountAll()" class="btn btn-primary mr-1">Sync</button>
                                                        <a href="{{ route('transaction.doctor.sync.transaction.all') }}" class="btn btn-primary mr-1">Transaction</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($doctors)
                                    @foreach($doctors as $doctor)
                                        <tr>
                                            <td>{{ $doctor->full_name }}</td>
                                            <td>
                                                <form action="javascript:;" id="sync-form-{{ $doctor->id }}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control form-control-sm from_date" autocomplete="off" id="from{{ $doctor->id }}">
                                                            <input type="hidden" name="from_date" class="from_date_eng" id="from_eng{{ $doctor->id }}" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control form-control-sm to_date" autocomplete="off" id="to{{ $doctor->id }}">
                                                            <input type="hidden" name="to_date" class="to_date_eng" value="{{ date('Y-m-d') }}" id="to_eng{{ $doctor->id }}">
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="float-right">
                                                                <button type="button" onclick="syncAccount({{ $doctor->id }})" class="btn btn-primary mr-1">Sync</button>
                                                                <a href="{{ route('transaction.create.doctor.sync.transaction', $doctor->id) }}" class="btn btn-primary mr-1">Transaction</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {!! $doctors->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        $(window).ready(function () {
            $('.from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('.from_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('.to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });
            $('.to_date').val(AD2BS('{{date('Y-m-d')}}'));
        });

        function syncAccount(doctorId) {
            $("#from_eng" + doctorId).val(BS2AD($("#from" + doctorId).val()))
            $("#to_eng" + doctorId).val(BS2AD($("#to" + doctorId).val()))

            let route = "{!! route('transaction.create.doctor.temp', ':DOCTOR_ID') !!}";
            route = route.replace(':DOCTOR_ID', doctorId);

            $.ajax({
                url: route,
                type: "POST",
                data: $('#sync-form-' + doctorId).serialize(),
                success: function (response) {
                    if (response.success) {
                        showAlert('Sync Successful');
                    } else {
                        showAlert('Something went wrong', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function syncAccountAll() {
            $("#from_engall").val(BS2AD($("#fromall").val()));
            var fromDate = $("#from_engall").val();
            $("#to_engall").val(BS2AD($("#toall").val()));
            var toDate = $("#to_engall").val();

            $.ajax({
                url: "{{ route('transaction.sync.all.doctor') }}",
                type: "POST",
                data: {from_date:fromDate, to_date:toDate},
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        showAlert('Sync Successful');
                    } else {
                        showAlert('Something went wrong', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    </script>
@endpush
