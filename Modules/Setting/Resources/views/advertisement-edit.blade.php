@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create Advertisement</h4>
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
                    <form action="{{ route('advertisement.update',$advertisement->id) }}" method="POST" id="create-user-form" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Title:</label>
                            <div class="col-sm-4">
                                <input type="text" id="title" name="title" class="form-control" value="{{$advertisement->title}}" placeholder="Title">
                            </div>

                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Description:</label>
                            <div class="col-sm-4">
                            <textarea id="description" name="description" class="form-control" >{{$advertisement->description}}</textarea>
                            </div>

                        </div>
                        @if($advertisement->image !='')
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="">Current Image:</label>
                                <div class="col-sm-4">
                                <img  src="data:image/jpg;base64,{{ $advertisement->image }}" alt="" class="img-ad">
                                </div>

                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Change Image:</label>
                            <div class="col-sm-8">
                                <div class="custom-file">

                                    <img id="preview">
                                    <br>
                                    <span class="col-4">
                                    <span></span>
                                    <input type="file" name="image" class="custom-file-input" id="image_file" onchange="fileSelectHandler()">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                    {{--                                <input type="file" name="image" id="image_file" onchange="fileSelectHandler()"/>--}}
                                    <small>Note: max 1MB file is allowed.</small>
                                </span>

                                    <div class="image-error" style="color:red"></div>

                                    <input type="hidden" id="x1" name="x1"/>
                                    <input type="hidden" id="y1" name="y1"/>
                                    <input type="hidden" id="x2" name="x2"/>
                                    <input type="hidden" id="y2" name="y2"/>
                                    <input type="hidden" id="w" name="w"/>
                                    <input type="hidden" id="h" name="h"/>
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('advertisement') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <link rel="stylesheet" href="{{ asset('assets/jcrop/css/jquery.Jcrop.min.css') }}">
    <script src="{{ asset('assets/jcrop/js/jquery.Jcrop.min.js') }}"></script>
    <style>
        .error {
            color: red;
            font-size: 10px;
        }
    </style>
    <script src="{{ asset('js/jquery.validate.min.js')}}"></script>

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
            $('#w').val(900);
            $('#h').val(632);
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



        // Create variables (in this scope) to hold the Jcrop API and image size
        var jcrop_api_Signature, boundx_Signature, boundy_Signature;
        var jcrop_api, boundx, boundy;

        function fileSelectHandler() {

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

                    if (height < 632 || width < 900) {

                        oImage.src = "";
                        $('#image_file').val('');
                        // $('#submit').prop("disabled","disabled");

                        $('.image-error').html('You have selected too small file, please select a one image with minimum size 900 X 632 px').show();

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
                            setSelect: [0, 0, 900, 632],
                            boxWidth: 800,
                            // boxHeight: 600,
                            minSize: [900, 632], // min crop size
                            // aspectRatio: 900 / 632,
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


    </script>
@endpush
