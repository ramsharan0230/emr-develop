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

                            <form class="form-inline">
                                <select name="method" class="form-control form-control-sm mb-2 mr-sm-2">
                                    <option value="">---Select Method---</option>
                                    <option value="Add" {{ $method == 'Add' ? 'selected' : '' }}>Add</option>
                                    <option value="Edit" {{ $method == 'Edit' ? 'selected' : '' }}>Edit</option>
                                    <option value="Delete" {{ $method == 'Delete' ? 'selected' : '' }}>Delete</option>
                                </select>
                                <select name="formName" class="form-control form-control-sm mb-2 mr-sm-2">
                                    <option value="">---Select Form Name---</option>
                                    <option value="User" {{ $formName == 'User' ? 'selected' : '' }}>User Form</option>
                                    <option value="User Share" {{ $formName == 'User Share' ? 'selected' : '' }}>User
                                        Share Form</option>
                                    <option value="Laboratory" {{ $formName == 'Laboratory' ? 'selected' : '' }}>
                                        Laboratory Form</option>
                                </select>
                                <select name="user" class="form-control form-control-sm mb-2 mr-sm-2" required>
                                    <option value="">---Select User---</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $user_id ? 'selected' : '' }}>
                                            {{ $user->username }}</option>
                                    @endforeach
                                </select>
                                <input type="date" class="form-control form-control-sm mb-2 mr-sm-2" name="date"
                                    value="{{ $date }}" required>

                                <button type="submit" class="btn btn-primary mb-2 mr-sm-2" title="Search">
                                    <i class="fa fa-search"></i>
                                </button>
                                <a href="{{ route("logs") }}" type="submit" class="btn btn-danger mb-2 mr-sm-2" title="Reset">
                                    <i class="fa fa-recycle"></i>
                                </a>
                            </form>
                            <div class="table-responsive">
                                <table class="table expandable-table custom-table table-bordered table-striped mt-c-15">
                                    <thead>
                                        <tr>
                                            <th>Log Traces</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logTraces as $logTrace)
                                            @if(strpos($logTrace, "[".$method."]") !== false && strpos($logTrace, "[".$formName."]") !== false)
                                                <tr>
                                                    <td>
                                                        <pre>{{ $logTrace }}</pre>
                                                    </td>
                                                </tr>
                                            @endif
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
