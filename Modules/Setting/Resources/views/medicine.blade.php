@extends('frontend.layouts.master')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('new/minicolor/jquery.minicolors.css') }}"/>
@endpush

@section('content')
    @php
        $patient_credential_setting = Options::get('patient_credential_setting');
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Registration Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <form action="{{ route('setting.medicine.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-4">Expired color code:</label>
                                        <div class="col-sm-3">
                                            <input autocomplete="off" type="text" name="expire_color_code" value="{{ Options::get('expire_color_code') }}" id="expire_color_code" placeholder="Expired color code" class="form-control colorpicker">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-4">Near Expiry color code:</label>
                                        <div class="col-sm-3">
                                            <input autocomplete="off" type="text" name="near_expire_color_code" value="{{ Options::get('near_expire_color_code') }}" id="near_expire_color_code" placeholder="Near Expiry color code" class="form-control colorpicker">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-4">Near Expiry Duration (in days):</label>
                                        <div class="col-sm-3">
                                            <input autocomplete="off" type="text" name="near_expire_duration" value="{{ Options::get('near_expire_duration') }}" id="near_expire_duration" placeholder="Near Expiry Duration (in days)" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-4">Non exipry color code:</label>
                                        <div class="col-sm-3">
                                            <input autocomplete="off" type="text" name="non_expire_color_code" value="{{ Options::get('non_expire_color_code') }}" id="non_expire_color_code" placeholder="Non exipry color code" class="form-control colorpicker">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-4"></label>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary btn-action">Update</button>
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
    <script src="{{asset('new/minicolor/jquery.minicolors.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $('.colorpicker').minicolors();
        });
    </script>
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
