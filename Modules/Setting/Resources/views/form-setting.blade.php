@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Form Settings</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{ route('setting.form.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                @csrf
                                <div class="form-row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">Free Text
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="">
                                                <select name="free_text" class="form-control">
                                                    <option {{ Options::get('free_Text') == '1'?'selected':'' }} value="1">Yes</option>
                                                    <option {{ Options::get('free_Text') == '0'?'selected':'' }} value="0">No</option>
                                                </select>
                                                <small class="help-block text-danger">{{$errors->first('free_text')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">EDD Calculation Days
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="form-row">
                                                <div class="col-sm-10">
                                                    <input type="text" name="edd_days" id="edd_days" value="{{ Options::get('edd_days')}}" class="form-control">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" class="btn btn-primary" onclick="labSettings.save('edd_days')"><i class="fa fa-check"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-primary btn-action mt-4">Update</button>
                                    </div>
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
