@extends('frontend.layouts.master')
@section('content')
    <style>
        .alert {
            display: flex;
            flex-direction: unset;
            justify-content: space-between;
        }

        .alert>div:first-child {
            display: flex;
            flex-direction: column;
        }

        .close span {
            color: red;
        }

    </style>
    @if (Session::get('success_message'))
        <div class="alert alert-success containerAlert">
            <div>{{ Session::get('success_message') }}</div>
            <div>
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
            </div>
        </div>
    @endif

    @if (Session::get('success_message_special'))
        <div class="alert alert-success">
            <div>{!! Session::get('success_message_special') !!}</div>
            <div>
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
            </div>
        </div>
    @endif

    @if (Session::get('error_message'))
        <div class="alert alert-danger containerAlert">
            <div>{{ Session::get('error_message') }}</div>
            <div>
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
            </div>
        </div>
    @endif
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" style="background-color: unset;" aria-current="page"
                    href="javascript:void(0)">View User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="background-color: unset;" aria-current="page"
                    href="{{ route('admin.user.add.new') }}">Add User</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">

                        <!-- Filter  -->
                        <form id="filter">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 style="margin: 5px 0 5px 0;">Filter Users</h5>
                                <a href="{{ route('admin.user.userview') }}" class="btn btn-primary">
                                    <i class="fa fa-sync"></i> Reset
                                </a>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Profile</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="group" data-variable="">
                                                <option value="">-- Select --</option>
                                                @foreach ($groups as $g)
                                                    <option value="{{ $g->id }}"
                                                        {{ $group == $g->id ? 'selected' : '' }}>{{ ucfirst($g->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Department</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="department" data-variable="">
                                                <option value="">-- Select --</option>
                                                @foreach ($departments as $dep)
                                                    <option value="{{ $dep->fldid }}"
                                                        {{ $department == $dep->fldid ? 'selected' : '' }}>
                                                        {{ $dep->flddept }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Hospital Department</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="hospital_department"
                                                data-variable="">
                                                <option value="">-- Select --</option>
                                                @foreach ($hospital_departments as $hospital_dep)
                                                    <option value="{{ $hospital_dep->id }}"
                                                        {{ $hospital_department == $hospital_dep->id ? 'selected' : '' }}>
                                                        {{ $hospital_dep->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Roles</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" name="role">
                                                <option value="">-- Select --</option>
                                                <option value="faculty"
                                                    {{ $role == 'faculty' ? 'selected' : '' }}>
                                                    Faculty
                                                </option>
                                                <option value="payable"
                                                    {{ $role == 'payable' ? 'selected' : '' }}>
                                                    Payable
                                                </option>
                                                <option value="referral"
                                                    {{ $role == 'referral' ? 'selected' : '' }}>
                                                    Referral
                                                </option>
                                                <option value="consultant"
                                                    {{ $role == 'consultant' ? 'selected' : '' }}>
                                                    Consultant
                                                </option>
                                                <option value="ip_clinician"
                                                    {{ $role == 'ip_clinician' ? 'selected' : '' }}>
                                                    IP
                                                    Clinician</option>
                                                <option value="signature"
                                                    {{ $role == 'signature' ? 'selected' : '' }}>
                                                    Signature
                                                </option>
                                                <option value="data_export"
                                                    {{ $role == 'data_export' ? 'selected' : '' }}>
                                                    Data
                                                    Export
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="d-flex flex-row justify-content-end align-items-center col-md-12 col-lg-12">
                                    <!-- <button type="button" class="btn btn-secondary btn-action mr-2">Close</button> -->
                                    <button type="submit" class="btn btn-primary btn-action"><i class="fa fa-filter"></i>
                                        &nbsp;Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">

                        <!-- Table  -->
                        <div class="d-flex align-items-center justify-content-between pb-3">
                            <h5>User Details</h5>
                            {{-- <button type="button" class="btn btn-primary btn-action">
                                <i class="fa fa-plus"></i>&nbsp;Add New
                            </button> --}}
                        </div>
                        <div class="table-sticky-th" style="width: 100%;">
                            <table class="table expandable-table custom-table table-bordered table-striped mt-c-15"
                                id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                data-pagination="true" data-resizable="true">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 3%">S.N.</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Mobile no.</th>
                                        <th>Gender</th>
                                        <th>Profile</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        @php
                                            $grps_str = '';
                                            $counter = 1;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname }}
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->user_details ? $user->user_details->phone : '' }}</td>
                                            <td>{{ $user->user_details ? $user->user_details->gender : '' }}</td>
                                            <td>
                                                @foreach ($user->user_group as $userGroup)
                                                    @php
                                                        if ($counter != 1) {
                                                            $grps_str .= ' | ';
                                                        }
                                                        $grps_str .= $userGroup->group_detail ? $userGroup->group_detail->name : '';
                                                        $counter++;
                                                    @endphp
                                                @endforeach
                                                <strong>{{ $grps_str }}</strong>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                        type="button" id="" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item bed_exchange"
                                                            href="{{ route('admin.user.edit.new', $user->id) }}">
                                                            <i class="fa fa-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="resetPassword(this)"
                                                            class="dropdown-item bed_exchange"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname }}"
                                                            data-user-email="{{ $user->email }}">
                                                            <i class="fa fa-lock"></i>&nbsp;Reset Password
                                                        </a>
                                                        @if (isset($user->user_is_superadmin) && $user->user_is_superadmin->count() == 0)
                                                            <a href="{{ route('admin.user.report', [$user->id]) }}"
                                                                target="_blank" class="dropdown-item bed_exchange">
                                                                <i class="fa fa-clipboard"></i>&nbsp;User Report
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('admin.user.reset.2fa', [$user->id]) }}"
                                                            class="dropdown-item bed_exchange">
                                                            <i class="fa fa-lock"></i>&nbsp;Reset 2FA
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal reset Password -->
    <div id="reset-password-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Reset Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <form class="form-horizontal" action="{{ route('admin.user.password-reset-user') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_user_id" id="_user_id" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-row">
                                    <label class="col-md-4 control-label" for="name">Customer Name: </label>
                                    <div class="col-md-6">
                                        <label id="_user_name" style="font-weight: 600;"></label>
                                    </div>
                                </div>

                                <div class="form-group form-row">
                                    <label class="col-md-4 control-label" for="name">Email: </label>
                                    <div class="col-md-6">
                                        <label id="_user_email" style="font-weight: 600;"></label>
                                    </div>
                                </div>

                                <div class="form-group form-row">
                                    <label class="col-md-4 control-label" for="name">Reset Password</label>
                                    <div class="col-md-6">
                                        <input name="st_user_password" type="password" class="form-control input-md"
                                            value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reset</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        $(function() {
            $('#myTable1').bootstrapTable();
            $('#filter').submit(function() {
                $('.loader-ajax-start-stop-container').show();
            })
        })

        function resetPassword(currentElement) {
            var user_id = $(currentElement).attr('data-user-id');
            if (user_id == '' || user_id == null) {
                return false;
            }
            var user_name = $(currentElement).attr('data-user-name');
            var user_email = $(currentElement).attr('data-user-email');

            $('#_user_id').val(user_id);
            $('#_user_name').html(user_name);
            $('#_user_email').html(user_email);

            $('#reset-password-modal').modal({
                show: true
            });
        }
    </script>
@endpush
