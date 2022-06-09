@extends('frontend.layouts.master')
@section('content')
    <style>
        .ellipsis {
            width: auto;
        }
    </style>
    @if(Session::get('success_message'))
        <div class="alert alert-success containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                    class="sr-only">Close</span></button>
            {{ Session::get('success_message') }}
        </div>
    @endif

    @if(Session::get('success_message_special'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                    class="sr-only">Close</span></button>
            {!! Session::get('success_message_special') !!}
        </div>
    @endif

    @if(Session::get('error_message'))
        <div class="alert alert-danger containerAlert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                    class="sr-only">Close</span></button>
            {{ Session::get('error_message') }}
        </div>
    @endif
    <!-- TOP Nav Bar END -->
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                            <h4 class="card-title">User List</h4>
                        </div>
                        <a href="{{ route('admin.user.add.new') }}" class="btn btn-primary"><i
                            class="ri-add-fill"><span class="pl-1">Add New</span></i>
                        </a>
                    </div>
                    <div class="iq-card-body">
                       <!--  <div class="row">
                            <div class="col-4 mb-2">
                                <input type="text" class="form-control" id="myInput" onkeyup="searchUser()" placeholder="Search for user..">
                            </div>
                        </div> -->
                        <div id="table" class="table-responsive">
                            <table id="user-list-table" class="table table-bordered table-hover table-striped text-center ">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>User Type</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if( count($users) > 0 )
                                    <?php $i = 1; ?>
                                    @foreach($users as $ur)
                                        @if ( $ur->user_is_superadmin->count() <= 0 || count(Auth::guard('admin_frontend')->user()->user_is_superadmin) != 0)
                                        <tr>
                                            <td style="text-align: center;">{{ $i++ }} </td>
                                            <td>
                                                <?php
                                                $grps_str = "";
                                                $counter = 1;
                                                ?>
                                                @if( isset($ur->user_group) )
                                                    @foreach($ur->user_group as $ugrp)
                                                        <?php
                                                        if ($counter != 1)
                                                            $grps_str .= " | ";

                                                        $grps_str .= isset($ugrp->group_detail->name) ? $ugrp->group_detail->name : "";

                                                        $counter++;
                                                        ?>
                                                    @endforeach
                                                @endif
                                                <strong>{{ $grps_str }}</strong>
                                            </td>
                                            <td>{{ $ur->firstname." ".$ur->middlename." ".$ur->lastname }}</td>
                                            <td>{{ $ur->username }}</td>
                                            <td>{{ $ur->email }}</td>
                                            <td>{{ isset($ur->user_details->phone) ? $ur->user_details->phone : "" }}</td>
                                            <td style="text-align: center;">
                                                {!! $ur->status == 'active' ? '<strong style="color:green;">Active</strong>' : '<strong
                                                style="color:#bf302f;">Inactive</strong>' !!}
                                            </td>
                                            <td width="150" style="text-align: center;">
                                                <a href="{{ route('admin.user.edit',[$ur->id]) }}"
                                                   class="btn btn-info btn-sm adminMgmtTableBtn" title="Edit User Group"><i
                                                        class="fa fa-edit"></i></a>

                                                <a href="#" class="btn btn-sm btn-primary adminMgmtTableBtn btnResetPass"
                                                   title="Reset Password" data-user-id="{{ $ur->id }}"
                                                   data-user-name="{{ $ur->firstname." ".$ur->middlename." ".$ur->lastname }}"
                                                   data-user-email="{{ $ur->email }}">
                                                    <i class="fa fa-unlock"></i>
                                                </a>

                                                @if ( isset( $ur->user_is_superadmin ) && $ur->user_is_superadmin->count() == 0 )
                                                    <a href="{{ route('admin.user.report',[$ur->id]) }}" target="_blank"
                                                       class="btn btn-secondary btn-sm adminMgmtTableBtn" title="User Report"><i
                                                            class="fa fa-clipboard"></i>
                                                    </a>

<!--                                                    <a href="{{ route('admin.user.destroy',[$ur->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete?');" data-toggle="confirmation"><i class="fa fa-trash"></i>
                                                    </a>-->
                                                @endif

                                                <a href="{{ route('admin.user.reset.2fa',[$ur->id]) }}"
                                                   class="btn btn-info btn-sm adminMgmtTableBtn" title="Reset 2fa"><i class="fas fa-redo-alt"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td align="center" colspan="10">
                                            User record not found. &nbsp;
                                        </td>
                                    </tr>
                                @endif
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
                                        <input name="st_user_password" type="password" class="form-control input-md" value="" required>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();

            $('.btnResetPass').click(function () {

                var user_id = $(this).attr('data-user-id');
                if (user_id == '' || user_id == null) {
                    return false;
                }
                var user_name = $(this).attr('data-user-name');
                var user_email = $(this).attr('data-user-email');

                $('#_user_id').val(user_id);
                $('#_user_name').html(user_name);
                $('#_user_email').html(user_email);

                $('#reset-password-modal').modal({show: true});
            });

            /*search user*/
            $("#searchUser").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".searchUserTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $('#user-list-table').DataTable();
        });

        function searchUser() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("user-list-table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endpush
