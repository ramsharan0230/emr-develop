@extends('frontend.layouts.master')
@section('content')

    <div class="iq-top-navbar second-nav">
        <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="ri-menu-3-line"></i>
                </button> -->
                <!-- <div class="iq-menu-bt align-self-center">
                  <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-more-fill"></i></div>
                    <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                  </div>
                </div> -->
                {{--<div class="navbar-collapse">
                  <ul class="navbar-nav navbar-list">
                    <li class="nav-item">
                      <a
                      class="search-toggle iq-waves-effect language-title"
                      href="#"
                      >User <i class="ri-arrow-down-s-line"></i
                        ></a>
                        <div class="iq-sub-dropdown">
                          <a class="iq-sub-card" href="#">Blank Form</a>
                          <a class="iq-sub-card" href="#">Waiting</a>
                          <a class="iq-sub-card" href="#">Search</a>
                          <a class="iq-sub-card" href="#">Last EncID</a>
                        </div>
                      </li>
                      <li class="nav-item">
                        <a
                        class="search-toggle iq-waves-effect language-title"
                        href="#"
                        >Group <i class="ri-arrow-down-s-line"></i
                          ></a>
                          <div class="iq-sub-dropdown">
                            <a class="iq-sub-card" href="#">Blank Form</a>
                            <a class="iq-sub-card" href="#">Waiting</a>
                            <a class="iq-sub-card" href="#">Search</a>
                            <a class="iq-sub-card" href="#">Last EncID</a>
                          </div>
                        </li>
                        <li class="nav-item">
                          <a
                          class="search-toggle iq-waves-effect language-title"
                          href="#"
                          >Group Mac <i class="ri-arrow-down-s-line"></i
                            ></a>
                            <div class="iq-sub-dropdown">
                              <a class="iq-sub-card" href="#">Blank Form</a>
                              <a class="iq-sub-card" href="#">Waiting</a>
                              <a class="iq-sub-card" href="#">Search</a>
                              <a class="iq-sub-card" href="#">Last EncID</a>
                            </div>
                          </li>
                          <li class="nav-item">
                            <a
                            class="search-toggle iq-waves-effect language-title"
                            href="#"
                            >Mac Request <i class="ri-arrow-down-s-line"></i
                              ></a>
                              <div class="iq-sub-dropdown">
                                <a class="iq-sub-card" href="#">Blank Form</a>
                                <a class="iq-sub-card" href="#">Waiting</a>
                                <a class="iq-sub-card" href="#">Search</a>
                                <a class="iq-sub-card" href="#">Last EncID</a>
                              </div>
                            </li>
                          </ul>
                        </div>--}}
            </nav>
        </div>
    </div>
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Groups Management</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <input type="text" class="form-control" id="myInput" onkeyup="myFunctionSearchGroup()" placeholder="Search for permission..">
                            </div>
                        </div>
                        <div id="table" class="table-responsive">
                            <table id="datatable" class="table table-bordered table-hover table-striped text-center">
                                <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Group Name</th>
                                    <th>Status</th>
                                    <th>Operation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <form action="{{ route('admin.user.groups.store') }}" class="panel-body form-horizontal form-padding" method="post">
                                        {{ csrf_field() }}
                                        <td style="text-align: center;"></td>

                                        <td>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Group Name">
                                            <small class="help-block text-danger">{{$errors->first('name')}}</small>
                                        </td>

                                        <td style="text-align: center;">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input id="status" class="custom-control-input" type="radio" name="status" value="active" checked>
                                                <label class="custom-control-label" for="status">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input id="status-2" class="custom-control-input" type="radio" name="status" value="inactive">
                                                <label class="custom-control-label" for="status-2">Inactive</label>
                                            </div>
                                        </td>

                                        <td style="text-align: center;">
                                            <input type="submit" class="btn btn-sm btn-success" name="submit" value="CREATE">
                                        </td>

                                    </form>
                                </tr>
                                @if( count($groups) > 0 )
                                    <?php $i = 1; ?>
                                    @foreach($groups as $g)
                                        <tr>
                                            <td style="text-align: center;">{{ $i++ }} </td>
                                            <td>
                                                @if($g->id == config('constants.role_super_admin'))
                                                    <strong>{{ strtoupper($g->name) }}</strong>
                                                @else
                                                    {{ $g->name }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                {!! $g->status == 'active' ? '<strong style="color:green;">Active</strong>' : '<strong style="color:#bf302f;">Inactive</strong>' !!}
                                            </td>
                                            <td width="230" style="text-align: center;">
                                                @if($g->id == config('constants.role_super_admin'))
                                                    <span class="text-pink text-semibold">You cannot edit this user group.</span>
                                                @else
                                                    <a href="{{ route('admin.user.groups.permission',[$g->id]) }}" class="btn btn-sm btn-primary adminMgmtTableBtn" title="Edit Group Permission"><i class="fa fa-cogs"></i></a>

                                                    @if(!in_array($g->id, [config('constants.role_super_admin'),config('constants.role_default_user')]))
                                                        <a href="{{ route('admin.user.groups.edit',[$g->id]) }}" class="btn btn-sm btn-info adminMgmtTableBtn" title="Edit User Group"><i class="fa fa-edit"></i></a>
                                                        <a href="{{ route('admin.user.groups.destroy',[$g->id]) }}" class="btn btn-sm btn-danger" data-toggle="confirmation" onclick="return confirm('Delete?');"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td align="center" colspan="10">
                                            User Groups not found. &nbsp;
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
@endsection
@push('after-script')
    <script type="text/javascript">
        $(function () {
            $('#select-all').click(function () {
                console.log($(this).is(':checked'));
                $(this).closest('form').find('input.permission').prop('checked', $(this).is(':checked'));
            });
        });

        $(document).ready(function () {
            $("#searchPermission").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".search_permission tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function myFunctionSearchGroup() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("datatable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
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
