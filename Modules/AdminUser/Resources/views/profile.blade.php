@extends('frontend.layouts.master')
@section('content')

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Profile</h4>
                        </div>
                        <span class="table-add float-right mb-3 mr-2">

                       </span>
                    </div>
                    <div class="iq-card-body">
                        <form action="{{ route('admin.user.profile.store') }}" method="POST" class="form-horizontal form-padding" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="hidden" name="old_username" value="{{ $user_details->username }}">
                            <input type="hidden" name="old_email" value="{{ $user_details->email }}">

                            <div class="form-group row">
                                <label class="col-md-1 control-label" for="admin-first-name">Full Name <span class="required_color">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" id="admin-first-name" name="firstname" class="form-control" value="{{ $user_details->firstname }}" placeholder="First Name" disabled>
                                    <small class="help-block text-danger">{{$errors->first('firstname')}}</small>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="admin-first-name" name="middlename" class="form-control" value="{{ $user_details->middlename }}" placeholder="Middle Name" disabled>
                                    <small class="help-block text-danger">{{$errors->first('middlename')}}</small>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="admin-last-name" name="lastname" class="form-control" value="{{ $user_details->lastname }}" placeholder="Last Name" disabled>
                                    <small class="help-block text-danger">{{$errors->first('lastname')}}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-1 control-label" for="demo-address-input">Address</label>
                                <div class="col-md-4">
                                    <input type="text" name="address" id="demo-address-input" class="form-control" placeholder="Enter your address" value="{{ isset($user_details->user_details->address) ? $user_details->user_details->address : '' }}">
                                    <small class="help-block text-danger">{{$errors->first('address')}}</small>
                                </div>

                                <label class="col-md-1 control-label" for="demo-contact-input">Contact</label>
                                <div class="col-md-4">
                                    <input type="text" name="phone" id="demo-contact-input" class="form-control" placeholder="Enter your contact number" value="{{ isset($user_details->user_details->phone) ? $user_details->user_details->phone : '' }}">
                                    <small class="help-block text-danger">{{$errors->first('phone')}}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-1 control-label" for="demo-address-input">Username</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="username" value="{{ old('username') ? old('username') : $user_details->username }}" disabled>
                                    <small class="help-block text-danger">{{$errors->first('username')}}</small>
                                </div>

                                <label class="col-md-1 control-label" for="demo-address-input">Email</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="email" value="{{ old('email') ? old('email') : $user_details->email }}">
                                    <small class="help-block text-danger">{{$errors->first('email')}}</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-1 control-label" for="demo-address-input">NMC Number</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="nmc_number" value="{{ old('nmc_number') ? old('nmc_number') : $user_details->nmc }}">
                                    <small class="help-block text-danger">{{$errors->first('nmc_number')}}</small>
                                </div>
                            </div>


                            {{--image--}}
                            <div class="form-group row">
                                <label class="col-md-2 control-label border-none" for="profile_image">Profile Image
                                </label>
                                <div class="col-md-4">
                                    <img id="preview">
                                    <br>
                                    <span class="">
                                        <span></span>
                                        <input type="file" name="profile_image" id="image_file" onchange="fileSelectHandler()"/>
                                        <small>Note: max 1MB file is allowed.<br><span class="tag-title">(400px * 400px)</span></small>
                                    </span>

                                    <div class="image-error" style="color:red"></div>

                                    <input type="hidden" id="x1" name="x1"/>
                                    <input type="hidden" id="y1" name="y1"/>
                                    <input type="hidden" id="x2" name="x2"/>
                                    <input type="hidden" id="y2" name="y2"/>
                                    <input type="hidden" id="w" name="w"/>
                                    <input type="hidden" id="h" name="h"/>
                                </div>
                                <div class="col-md-3 img-responsive">
                                    <img class="w-50 profile_image_old" src="{{Config::get('app.minio_url')}}{{ $user_details->profile_image_link }}" alt="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label border-none" for="signature_image">Signature Image:
                                </label>
                                <div class="col-md-4">
                                    <img id="preview_signature_image">
                                    <br>
                                    <span class="">
                                        <span></span>
                                        <input type="file" name="signature_image" id="signature_image" onchange="fileSelectHandlerSignature()"/>
                                        <small>Note: max 1MB file is allowed.<br><span class="tag-title">(200px * 400px)</span></small>

                                    </span>

                                    <div class="image-error-signature" style="color:red"></div>

                                    <input type="hidden" id="x1s" name="x1s"/>
                                    <input type="hidden" id="y1s" name="y1s"/>
                                    <input type="hidden" id="x2s" name="x2s"/>
                                    <input type="hidden" id="y2s" name="y2s"/>
                                    <input type="hidden" id="ws" name="ws"/>
                                    <input type="hidden" id="hs" name="hs"/>
                                </div>
                                <div class="col-md-3 img-responsive">
                                    <img class="w-50 signature_image_old" src="{{Config::get('app.minio_url')}}{{ $user_details->signature_image_link }}" alt="">
                                </div>
                            </div>
                            {{--image--}}

                            <div class="form-group row">
                                <label class="col-md-1 control-label"></label>
                                <div class="col-md-3">
                                    <input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Profile">
                                </div>
                            </div>
                            {{--<div class="form-group row">
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                                <div class="col-md-8">
                                    @if( isset($user_details->user_details->image) && $user_details->user_details->image != "" )
                                        <img src="{{ asset('uploads/profile/') }}/{{ $user_details->user_details->image }}" class="img-thumbnail">
                                    @else
                                        <img src="{{ asset('backend/img/default_profile.jpg') }}" class="img-thumbnail">
                                    @endif
                                </div>
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                            </div>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <link rel="stylesheet" href="{{ asset('assets/jcrop/css/jquery.Jcrop.min.css') }}">
    <script src="{{ asset('assets/jcrop/js/jquery.Jcrop.min.js') }}"></script>
    <script>

        // convert bytes into friendly format
        function bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB'];
            if (bytes == 0) return 'n/a';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
        };

        // check for selected crop region
        function checkForm() {
            if (parseInt($('#w').val())) return true;
            $('.image-error').html('Please select a crop region and then press Upload').show();
            return false;
        };

        // update info by cropping (onChange and onSelect events handler)
        function updateInfo(e) {
            $('#x1').val(e.x);
            $('#y1').val(e.y);
            $('#x2').val(e.x2);
            $('#y2').val(e.y2);
            $('#w').val(e.w);
            $('#h').val(e.h);
        };

        // clear info by cropping (onRelease event handler)
        function clearInfo(e) {
            $('#x1').val(e.x);
            $('#y1').val(e.y);
            $('#x2').val(e.x2);
            $('#y2').val(e.y2);
            $('#w').val(400);
            $('#h').val(400);
        }

        // update info by cropping (onChange and onSelect events handler)
        function updateInfoSignature(e) {
            $('#x1s').val(e.x);
            $('#y1s').val(e.y);
            $('#x2s').val(e.x2);
            $('#y2s').val(e.y2);
            $('#ws').val(e.w);
            $('#hs').val(e.h);
        };

        // clear info by cropping (onRelease event handler)
        function clearInfoSignature(e) {
            $('#x1s').val(e.x);
            $('#y1s').val(e.y);
            $('#x2s').val(e.x2);
            $('#y2s').val(e.y2);
            $('#ws').val(200);
            $('#hs').val(400);
        }

        // Create variables (in this scope) to hold the Jcrop API and image size
        var jcrop_api_Signature, boundx_Signature, boundy_Signature;
        var jcrop_api, boundx, boundy;

        function fileSelectHandler() {
            $('.profile_image_old').hide();

            // get selected file
            var oFile = $('#image_file')[0].files[0];

            if (!$('#image_file')[0].files[0]) {
                $('.jcrop-holder').remove();
                return;
            }
            // hide all errors
            $('.image-error').hide();
            // check for image type (jpg and png are allowed)
            var rFilter = /^(image\/jpeg|image\/png|image\/jpg|image\/gif|image\/xcf|image\/svg)$/i;
            if (!rFilter.test(oFile.type)) {
                $('#submit').prop("disabled", "disabled");
                $('.image-error').html('Please select a valid image file (jpg and png are allowed)').show();
                return;
            } else {
                $('#submit').prop("disabled", false);
            }

            // if (oFile.size < 950 * 550) {
            //     $('.image-error').html('You have selected too small file, please select a one image with minimum size 950 X 550 px').show();
            //     return;
            // }
            // preview element
            var oImage = document.getElementById('preview');
            // prepare HTML5 FileReader
            var oReader = new FileReader();
            oReader.onload = function (e) {
                // e.target.result contains the DataURL which we can use as a source of the image
                oImage.src = e.target.result;
                oImage.onload = function () { // onload event handler
                    var height = oImage.naturalHeight;

                    var width = oImage.naturalWidth;

                    // console.log(height);
                    // console.log(width);
                    window.URL.revokeObjectURL(oImage.src);

                    if (height < 400 || width < 400) {

                        oImage.src = "";
                        $('#image_file').val('');
                        // $('#submit').prop("disabled","disabled");

                        $('.image-error').html('You have selected too small file, please select a one image with minimum size 400 X 400 px').show();

                    } else {

                        $('#submit').prop("disabled", false);

                    }
                    // display step 2
                    $('.step2').fadeIn(500);
                    // display some basic image info
                    var sResultFileSize = bytesToSize(oFile.size);

                    // destroy Jcrop if it is existed
                    if (typeof jcrop_api != 'undefined') {
                        jcrop_api.destroy();
                        jcrop_api = null;
                        $('#preview').width(oImage.naturalWidth);
                        $('#preview').height(oImage.naturalHeight);
                    }
                    setTimeout(function () {
                        // initialize Jcrop
                        $('#preview').Jcrop({
                            setSelect: [0, 0, 400, 400],
                            boxWidth: 800,
                            // boxHeight: 600,
                            minSize: [400, 400], // min crop size
                            aspectRatio: 400 / 400,
                            bgFade: true, // use fade effect
                            bgOpacity: .3, // fade opacity
                            onChange: updateInfo,
                            onSelect: updateInfo,
                            onRelease: clearInfo,
                            trueSize: [oImage.naturalWidth, oImage.naturalHeight],
                        }, function () {
                            // use the Jcrop API to get the real image size
                            var bounds = this.getBounds();
                            boundx = bounds[0];
                            boundy = bounds[1];
                            // Store the Jcrop API in the jcrop_api variable
                            jcrop_api = this;
                        });
                    }, 500);
                };
            };
            // read selected file as DataURL
            oReader.readAsDataURL(oFile);
        }

        function fileSelectHandlerSignature() {
            $('.signature_image_old').hide();
            // get selected file
            var oFilesig = $('#signature_image')[0].files[0];

            if (!$('#signature_image')[0].files[0]) {
                $('.jcrop-holder').remove();
                return;
            }
            // hide all errors
            $('.image-error-signature').hide();
            // check for image type (jpg and png are allowed)
            var rFilter = /^(image\/jpeg|image\/png|image\/jpg|image\/gif|image\/xcf|image\/svg)$/i;
            if (!rFilter.test(oFilesig.type)) {
                $('#submit').prop("disabled", "disabled");
                $('.image-error').html('Please select a valid image file (jpg and png are allowed)').show();
                return;
            } else {
                $('#submit').prop("disabled", false);
            }

            // if (oFilesig.size < 950 * 550) {
            //     $('.image-error').html('You have selected too small file, please select a one image with minimum size 950 X 550 px').show();
            //     return;
            // }
            // preview_signature_image element
            var oImagesig = document.getElementById('preview_signature_image');
            // prepare HTML5 FileReader
            var oReadersig = new FileReader();
            oReadersig.onload = function (e) {
                // e.target.result contains the DataURL which we can use as a source of the image
                oImagesig.src = e.target.result;
                oImagesig.onload = function () { // onload event handler
                    var height = oImagesig.naturalHeight;

                    var width = oImagesig.naturalWidth;

                    // console.log(height);
                    // console.log(width);
                    window.URL.revokeObjectURL(oImagesig.src);

                    if (height < 400 || width < 200) {

                        oImagesig.src = "";
                        $('#signature_image').val('');
                        // $('#submit').prop("disabled","disabled");

                        $('.image-error').html('You have selected too small file, please select a one image with minimum size 400 X 200 px').show();

                    } else {

                        $('#submit').prop("disabled", false);

                    }
                    // display step 2
                    $('.step2').fadeIn(500);
                    // display some basic image info
                    var sResultFileSize = bytesToSize(oFilesig.size);

                    // destroy Jcrop if it is existed
                    if (typeof jcrop_api_Signature != 'undefined') {
                        jcrop_api_Signature.destroy();
                        jcrop_api_Signature = null;
                        $('#preview_signature_image').width(oImagesig.naturalWidth);
                        $('#preview_signature_image').height(oImagesig.naturalHeight);
                    }
                    setTimeout(function () {
                        // initialize Jcrop
                        $('#preview_signature_image').Jcrop({
                            setSelect: [0, 0, 400, 200],
                            boxWidth: 800,
                            // boxHeight: 600,
                            minSize: [400, 200], // min crop size
                            aspectRatio: 400 / 200,
                            bgFade: true, // use fade effect
                            bgOpacity: .3, // fade opacity
                            onChange: updateInfoSignature,
                            onSelect: updateInfoSignature,
                            onRelease: clearInfoSignature,
                            trueSize: [oImagesig.naturalWidth, oImagesig.naturalHeight],
                        }, function () {
                            // use the Jcrop API to get the real image size
                            var bounds = this.getBounds();
                            boundx_Signature = bounds[0];
                            boundy_Signature = bounds[1];
                            // Store the Jcrop API in the jcrop_api_Signature variable
                            jcrop_api_Signature = this;
                        });
                    }, 500);
                };
            };
            // read selected file as DataURL
            oReadersig.readAsDataURL(oFilesig);
        }
    </script>
@endpush
