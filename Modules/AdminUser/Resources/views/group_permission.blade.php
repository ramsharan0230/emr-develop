@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Group Permission | {{ $group_details->name }}</h4>
                    </div>
                </div>
                @if(Session::get('success_message'))
                    <div class="alert alert-success containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('success_message') }}
                    </div>
                @endif

                @if(Session::get('error_message'))
                    <div class="alert alert-danger containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('error_message') }}
                    </div>
                @endif
                <div class="iq-card-body">

                    <form method="POST" action="{{ route('admin.user.groups.permission-store') }}">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <input type="text" class="form-control" id="permision-search" onkeyup="myFunctionSearchPermission()" placeholder="Search for permission..">
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <input type="hidden" name="_group_id" value="{{ $group_details->id }}">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="permission-table-search">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center">
                                        <div class="checkbox" style="margin-top: 0px;margin-bottom: 0px;">
                                            <input id="select-all" class="magic-checkbox" type="checkbox">
                                            <label for="select-all" style="color: #000023;"></label>
                                        </div>
                                    </th>
                                    <th>Permission Name</th>
                                    <th>Permission Slug</th>
                                    <th>Permission Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( count( $modules ) > 0 )
                                    @foreach( $modules as $module )
                                        {{--<tr>
                                            <td></td>
                                            <td colspan="5">
                                                <strong>{{ strtoupper( $module->name ) }}</strong>
                                            </td>
                                        </tr>--}}
                                        @if ( isset( $module->permission_references ) && $module->permission_references->count() > 0 )
                                            @foreach( $module->permission_references as $per_ref )
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="checkbox" style="margin-top: 0px;margin-bottom: 0px;">
                                                            <input id="permission-{{ $per_ref->code }}" class="magic-checkbox permission" type="checkbox" name="permission_reference_id[]" value="{{ $per_ref->id }}" {{ in_array($per_ref->id,$group_permissions) ? "checked" : ""}}>

                                                        </div>
                                                    </td>
                                                    <td><label for="permission-{{ $per_ref->code }}" style="color: #000023;">{{ ltrim($per_ref->short_desc, 'View ') }}</label></td>
                                                    <td>{{ $per_ref->code }}</td>
                                                    <td>{{ $per_ref->description }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="6">
                                            <strong>No records found!!!</strong>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="form-group" style="position:fixed;bottom:100px;right:5px; z-index: 99">
                                {{--<label class="col-md-5 control-label"></label>--}}
                                <div class="">
                                    <input type="submit" class="btn btn-primary float-right" name="submit" value="UPDATE PERMISSIONS">
                                </div>
                            </div>

                        </div>
                    </form>
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

        function myFunctionSearchPermission() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("permision-search");
            filter = input.value.toUpperCase();
            table = document.getElementById("permission-table-search");
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
