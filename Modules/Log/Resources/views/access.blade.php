@extends('frontend.layouts.master')

@section('content')
    <style>
        .success {
            background-color: green;
            color: white;
            padding: 4px 5px;
            border-radius: 5px;
        }

        .failure {
            background-color: red;
            color: white;
            padding: 4px 5px;
            border-radius: 5px;
        }

        .btn-toggle button {
            border-color: #007bff;
        }

    </style>
    <div class="container-fluid">
        @include('frontend.common.alert_message')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Access Log</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form id="filter">
                                <div class="row flex-row align-items-end">
                                    <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <div class="d-flex flex-column align-items-start">
                                            <label for="">From</label>
                                            <input type="date" class="form-control" name="from_date"
                                                value="{{ $from_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <div class="d-flex flex-column align-items-start">
                                            <label for="">To</label>
                                            <input type="date" class="form-control" name="to_date"
                                                value="{{ $to_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 mt-1 mb-1">
                                        <div class="d-flex flex-column align-items-start">
                                            <label for="">User</label>
                                            <select name="user" class="form-control">
                                                <option value="">---Select User---</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $user->id == $user_id ? 'selected' : '' }}>
                                                        {{ $user->username }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <button type="submit" class="btn btn-primary btn-action" title="Search">
                                            <i class="fa fa-filter"></i>&nbsp;Filter
                                        </button>
                                        <a href="{{ route('logs.access') }}" type="submit"
                                            class="btn btn-outline-danger btn-action" title="Reset">
                                            <i class="fa fa-sync"></i>&nbsp;Reset
                                        </a>
                                    </div>
                                    {{-- <div class="btn-group btn-toggle mt-1 mb-1">
                                        <button type="button" class="btn btn-lg btn-default btn-action">BS</button>
                                        <button type="button" class="btn btn-lg btn-primary btn-action active">AD</button>
                                    </div> --}}
                                </div>
                            </form>
                            <hr class="mb-0">
                            <div class="table-responsive">
                                <table class="table expandable-table custom-table table-bordered table-striped mt-c-15"
                                    id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                    data-pagination="true" data-resizable="true">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Log Date/Time</th>
                                            <th>IP Address</th>
                                            <th>User</th>
                                            <th>OS</th>
                                            <th>Browser</th>
                                            <th>Status</th>
                                            <th>Log Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $key => $log)
                                            @php
                                                $isSuccess = preg_match('/logged in/i', $log->message);
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($log->record_datetime)->format('M d, Y') }}
                                                    <br>
                                                    <i class="fa fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($log->record_datetime)->format('h:i:s A') }}
                                                </td>
                                                <td>{{ $log->remote_addr }}</td>
                                                <td>{{ $log->user->username ?? '' }}</td>
                                                <td>{{ Helpers::getOS($log->user_agent) }}</td>
                                                <td>
                                                    <i class="fab fa-{{ strtolower(Helpers::getBrowser($log->user_agent)) }}"
                                                        aria-hidden="true"></i>
                                                    {{ Helpers::getBrowser($log->user_agent) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="{{ $isSuccess ? 'success' : 'failure' }}">{{ $isSuccess ? 'Success' : 'Failure' }}</span>
                                                </td>
                                                <td>{{ $log->message }}</td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="8">
                                                    <em>No data available</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('after-script')
    <script>
        $(function() {
            $('#myTable1').bootstrapTable()
        })
        $('#filter').submit(function() {
            $('.loader-ajax-start-stop-container').show();
        })
        $('.btn-toggle').click(function() {
            $(this).find('.btn').toggleClass('active');
            if ($(this).find('.btn-primary').length > 0) {
                $(this).find('.btn').toggleClass('btn-primary');
            }
            $(this).find('.btn').toggleClass('btn-default');
        });
    </script>
@endpush
