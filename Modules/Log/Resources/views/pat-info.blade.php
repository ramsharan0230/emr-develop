@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        @include('frontend.common.alert_message')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Log</h4>
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
                                            <label for="">Username</label>
                                            <select name="user" class="form-control">
                                                <option value="">---Select User---</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" {{ $user->id == $user_id ? 'selected' : '' }}>
                                                        {{ $user->username }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 col-lg-2 mt-1 mb-1">
                                        <div class="d-flex flex-column align-items-start">
                                            <label for="">Error Type</label>
                                            <select name="user" class="form-control">
                                                <option value="">--Select Error--</option>                                                
                                                    <option value="">Error 1</option>                                                
                                                    <option value="">Error 2</option>                                                
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $key => $log)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $log->message }}</td>
                                                <td>{{ $log->record_datetime }}</td>
                                                <td>{{ $log->user->username ?? '' }}</td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="14">
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
