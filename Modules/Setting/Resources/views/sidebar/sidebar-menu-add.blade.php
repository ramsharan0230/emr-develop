@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid extra-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create Menu</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(Session::get('success_message'))
                    <div class="alert alert-success containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('success_message') }}
                    </div>
                    @endif

                    @if(Session::get('error_message'))
                    <div class="alert alert-success containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('error_message') }}
                    </div>
                    @endif
                    <form action="{{ route('sidebar.menu.store') }}" method="POST" id="create-sidebarmenu-form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Module:</label>
                            <div class="col-sm-4">
                            <select name="permission_module" id="permission_module" class="form-control">
                            <option value="">--Select--</option>
                            @forelse($permission_modules as $module)
                            <option value="{{ $module->module }}">{{ $module->module }} </option>
                            @empty
                            @endforelse
                        </select>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Main Menu:</label>
                            <div class="col-sm-4">
                                <select name="mainmenu" id="mainmenu-name" class="form-control mainmenu">
                                    <option value="Setting">Setting</option>

                                    @foreach($mainmenus as $mainmenu)

                                    <option value="{{ $mainmenu->mainmenu }}">{{ $mainmenu->mainmenu }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Sub Menu:</label>
                            <div class="col-sm-4">
                                <input type="text" id="submenu" name="submenu" class="form-control" value="{{ old('submenu') }}" placeholder="Enter Sub Menu">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Route:</label>
                            <div class="col-sm-4">
                                <input type="text" id="routelink" name="routelink" class="form-control" value="{{ old('routelink') }}" placeholder="Enter Route">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Icon:</label>
                            <div class="col-sm-4">
                                <input type="text" id="icon" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="Enter icon">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Status:</label>
                            <div class="col-sm-4">
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Order(Big number on top):</label>
                            <div class="col-sm-4">
                                <input type="text" id="order_by" name="order_by" class="form-control"  placeholder="Enter Order Number" value="1">
                            </div>
                        </div>

                        <div class="form-group mt-5">

                            <a href="javascript:;" id="submitmenu" class="btn btn-primary">Create</a>
                            <a href="{{ route('sidebar.menu') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('after-script')
<style>
    .error {
        color: red;
        font-size: 10px;
    }
</style>


<script>
    $(document).ready(function() {

        setTimeout(function() {
            $(".mainmenu").select2();

        }, 1500);

        $(document).on('keyup', '.select2-search__field', function (e) {
            if (e.which === 13) {
                $('#mainmenu-name').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
            }
        });

        // $('select2-search-field > input.select2-input').on('keyup', function(e) {
        //     alert(e);
        //     if(e.keyCode === 13)
        //         $('#mainmenu-name').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')

        // });



        $(document).on("click", "#submitmenu", function(e) {
            $.ajax({
                url: '{{ route('sidebar.menu.store') }}',
                type: "POST",
                data: {
                    mainmenu: $('#mainmenu-name').val(),
                    submenu: $('#submenu').val(),
                    status: $('#status').val(),
                    routelink: $('#routelink').val(),
                    permission_module: $('#permission_module').val(),
                    order_by: $('#order_by').val(),
                    _token: "{{ csrf_token() }}",
                    icon: $('#icon').val()
                },
                success: function(response) {
                    showAlert(response.success.message)
                   // window.location.replace(response.success.url);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("An Error has occured!")
                }
            });
        });

    });
</script>


@endpush
