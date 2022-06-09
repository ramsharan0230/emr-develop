@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Permission Settings</h4>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="{{ route('permission.setting.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="">Permission Module
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="">
                                                    <select name="permission_module" class="form-control">
                                                        <option value="">--Select--</option>
                                                        @forelse($permission_modules as $module)
                                                            <option value="{{ $module->module }}">{{ $module->module }} </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    <small class="help-block text-danger">{{$errors->first('permission_module')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="" class="">Permission Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="form-row">
                                                    <div class="col-sm-10">
                                                        <input type="text" name="name" id="name" value="" class="form-control" placeholder="enter permission name">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="col-sm-4">--}}
{{--                                            <button class="btn btn-primary btn-action mt-4">Update</button>--}}
{{--                                        </div>--}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@push('after-script')
    <script>

        var labSettings = {
            save: function (settingTitle) {
                settingValue = $('#' + settingTitle).val();
                if (settingValue === "") {
                    alert('Selected field is empty.')
                }

                $.ajax({
                    url: '{{ route('setting.lab.save') }}',
                    type: "POST",
                    data: {settingTitle: settingTitle, settingValue: settingValue},
                    success: function (response) {
                        showAlert(response.message)
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }
    </script>
@endpush
