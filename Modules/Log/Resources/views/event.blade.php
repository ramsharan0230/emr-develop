@extends('frontend.layouts.master')

@section('content')
    <style>
        pre {
            font-family: "Source Code Pro", monospace;
            background: #ededed;
            border: 1px solid #ddd;
            border-left: 3px solid #f36d33;
            color: #666;
            page-break-inside: avoid;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 1.6em;
            max-width: 100%;
            overflow: auto;
            padding: 1em 1.5em;
            display: block;
            word-wrap: break-word;
            max-width: 400px;
        }

    </style>
    <div class="container-fluid">
        @include('frontend.common.alert_message')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Event Log</h4>
                    </div>
                    <div>
                        <a href="{{ route('logs.access') }}" type="submit" class="btn btn-outline-primary" title="Reset">
                            <i class="fa fa-sync"></i>&nbsp;Reset
                        </a>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form>
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
                                    {{-- <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <div class="d-flex flex-column align-items-start">
                                            <label for="">Event Type</label>
                                            <select name="user" class="form-control">
                                                <option value="">--Select Event--</option>
                                                <option value="">Event 1</option>
                                                <option value="">Event 2</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <button type="submit" class="btn btn-primary btn-action" title="Search">
                                            <i class="fa fa-filter"></i>&nbsp;Filter
                                        </button>
                                    </div>
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
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Current Data</th>
                                            <th>Previous Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $key => $log)
                                            @php
                                                $data = (array) json_decode($log->context);
                                                $currentData = $data['current_data'] ?? null;
                                                $previousData = $data['previous_data'] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $log->message }}</td>
                                                <td>{{ $log->record_datetime }}</td>
                                                <td>{{ $log->user->username ?? '' }}</td>
                                                <td>
                                                    @if ($currentData)
                                                        {!! '<pre>' . json_encode($currentData, JSON_PRETTY_PRINT) . '</pre>' !!}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($previousData)
                                                        {!! '<pre>' . json_encode($previousData, JSON_PRETTY_PRINT) . '</pre>' !!}
                                                    @endif
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="6">
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
    </script>
@endpush
