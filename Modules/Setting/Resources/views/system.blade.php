@extends('frontend.layouts.master')

@section('content')
<style>
    .preview-img {
        height: 120px;
        width: 120px;
    }

    h5 {
        font-weight: 500;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Hospital Information Settings</h4>
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
                        <form action="{{ route('setting.system.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row flex-row-reverse">
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-2">
                                                @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                                                    <div class="form-group form-row align-items-center">
                                                        <div class="col-sm-12">
                                                            <div class="d-flex align-items-center preview-img">
                                                                <img id="previewLogo" src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" height="100" width="100" style="height: 100%; border-radius: 5px; object-fit: contain;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center preview-img">
                                                        <img src="../assets/images/logobackground.jpg" alt="Logo" height="100" width="100" style="height: 100%; border-radius: 5px; object-fit: contain;">
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <input type="file" name="logo" id="customFile" style="display:none;"/> 
                                                <a class="btn btn-primary" id="OpenImgUpload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-wrap">
                                        <div class="col-md-12 mt-2 mb-3">
                                            <h5>1. Basic Information</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row">
                                                <label for="" class="col-sm-12">Hopsital Name*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="system_name" class="form-control"
                                                        value="{{ old('system_name') ? old('system_name') : (isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'') }}"
                                                        placeholder="System Name">
                                                    <small class="help-block text-danger">{{$errors->first('system_name')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Hospital Code*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="hospital_code" class="form-control"
                                                        value="{{ old('hospital_code') ? old('hospital_code') : (isset(Options::get('siteconfig')['hospital_code'])?Options::get('siteconfig')['hospital_code']:'') }}" placeholder="Hospital Code">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Slogan*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="system_slogan" class="form-control"
                                                        value="{{ old('system_slogan') ? old('system_slogan') : (isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'') }}" placeholder="System Slogan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Feedback/Support Email*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="system_feedback_email" class="form-control"
                                                        value="{{ old('system_feedback_email') ? old('system_feedback_email') : Options::get('system_feedback_email') }}" placeholder="Feedback Email">
                                                    <small
                                                        class="help-block text-danger">{{$errors->first('system_feedback_email')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Address*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="system_address" class="form-control"
                                                        value="{{ old('system_address') ? old('system_address') : (isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'') }}" placeholder="System Address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Telephone No.*</label>
                                                <div class="col-sm-12">
                                                    <input type="number" name="system_telephone_no" class="form-control"
                                                        value="{{ old('system_telephone_no') ? old('system_telephone_no') : Options::get('system_telephone_no') }}"
                                                        placeholder="Telephone Number" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Mobile No.*</label>
                                                <div class="col-sm-12">
                                                    <input type="number" name="system_mobile" class="form-control"
                                                        value="{{ old('system_mobile') ? old('system_mobile') : Options::get('system_mobile') }}"
                                                        placeholder="Mobile Number" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex flex-wrap">
                                        <div class="col-md-12 mt-2 mb-3">
                                            <h5>2. Other Information</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Hospital PAN</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="hospital_pan" class="form-control" id="hospital_pan"
                                                        value="{{ old('hospital_pan') ? old('hospital_pan') : Options::get('hospital_pan') }}" placeholder="Hospital PAN">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Hospital VAT</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="hospital_vat" class="form-control" id="hospital_vat"
                                                        value="{{ old('hospital_vat') ? old('hospital_vat') : Options::get('hospital_vat') }}" placeholder="Hospital VAT">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <div class="col-sm-12 d-flex justify-content-between mb-1">
                                                    <label for="" class="">Pharmacy PAN</label>
                                                    <a href="javascript:void(0);" type="button" class = "btn btn-light" id="pharmacy_pan_click"> Copy Hospital PAN</a>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" name="pharmacy_pan" class="form-control" id="pharmacy_pan"
                                                        value="{{ old('pharmacy_pan') ? old('pharmacy_pan') : Options::get('pharmacy_pan') }}" placeholder="Pharmacy PAN">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <div class="col-sm-12 d-flex justify-content-between mb-1">
                                                    <label for="">Pharmacy VAT</label>
                                                    <a href="javascript:void(0);" type="button" class = "btn btn btn-light" id="pharmacy_vat_click">Copy Hospital VAT</a>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" name="pharmacy_vat" class="form-control" id="pharmacy_vat"
                                                        value="{{ old('pharmacy_vat') ? old('pharmacy_vat') : Options::get('pharmacy_vat') }}" placeholder="Pharmacy VAT">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">DDA No.*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="dda_number" class="form-control"
                                                        value="{{ old('dda_number') ? old('dda_number') : Options::get('dda_number') }}"
                                                        placeholder="DDA No.">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Licensed For*</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="licensed_by" class="form-control"
                                                        value="{{ old('licensed_by') ? old('licensed_by') : Options::get('licensed_by') }}" placeholder="Licensed For">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Show Rank*</label>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="system_patient_rank" value="1" @if(Options::get('system_patient_rank') == 1) checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Yes</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="system_patient_rank" value="0" @if(Options::get('system_patient_rank') == 0) checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Enable 2FA</label>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="system_2fa" value="1" @if(Options::get('system_2fa') == 1) checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Yes</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="system_2fa" value="0" @if(Options::get('system_2fa') == 0) checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex flex-wrap">
                                        <div class="col-md-12 mt-2 mb-3">
                                            <h5>3. Color Information</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Hospital Login Color</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="hospital_login_color" class="form-control"
                                                        value="{{ old('hospital_login_color') ? old('hospital_login_color') : Options::get('hospital_login_color') }}"
                                                        placeholder="Hospital Login Color">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-12">Hospital Default Color</label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="hospital_default_color" class="form-control"
                                                        value="{{ old('hospital_default_color') ? old('hospital_default_color') : Options::get('hospital_default_color') }}"
                                                        placeholder="Hospital Default Color">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(can('hospital-info-setup-update'))
                            <div class="float-right mt-4">
                                <button class="btn btn-primary btn-action mr-4" type="submit"><i class="fa fa-check"></i>&nbsp;Update</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@push('after-script')
    <script>
        $('#OpenImgUpload').click(function(){ 
            $('#customFile').trigger('click'); 
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

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewLogo').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFile").change(function () {
            readURL(this);
        });

        function readHospitalURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewHospitalLogo').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customHospitalFile").change(function () {
            readHospitalURL(this);
        });
// hospital_pan pharmacy_pan
        $('#pharmacy_pan_click').click(function(){
            const hospital_pan = document.getElementById('hospital_pan').value;
            const pharmacy_pan = document.getElementById('pharmacy_pan').value;
            $('#pharmacy_pan').val(hospital_pan);
        });

        $('#pharmacy_vat_click').click(function(){
            const hospital_vat = document.getElementById('hospital_vat').value;
            const pharmacy_vat = document.getElementById('pharmacy_vat').value;
            $('#pharmacy_vat').val(hospital_vat);
        });
    </script>
@endpush
