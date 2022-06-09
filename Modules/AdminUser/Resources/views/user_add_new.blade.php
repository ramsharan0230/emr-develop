@extends('frontend.layouts.master')

@push('after-script')
    <link rel="stylesheet" href="{{ asset('assets/jcrop/css/jquery.Jcrop.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" style="background-color: unset;" aria-current="page"
                    href="{{ route('admin.user.userview') }}">View User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" style="background-color: unset;" aria-current="page" href="javascript:void(0)">Add
                    User</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Add New User</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="{{ route('admin.user.store.new') }}" method="POST" id="create-user-form"
                            enctype="multipart/form-data">
                            @csrf
                            @php
                                $arraydata = ['Dr', 'Medical', 'Lab Incharge', 'Medical Officer', 'Nursing Officer', 'Pharmacy Assistant', 'BMET', 'Medical Assistant', 'Health Assistant', 'ART Counsellor', 'MDGP', 'Anesthesia Assistant', 'Cashier', 'Computer Operator', 'Dental Surgeon', 'Pharmacy Officer', 'ANM'];
                            @endphp
                            <div class="d-flex flex-row flex-wrap">
                                <div class="col-md-12 col-lg-9">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12" for="">Designation/ Category</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input name="designation" id="designation" type="text" list="category"
                                                        class="form-control" value="{{ old('designation') }}"
                                                        required />
                                                    <datalist id="category">
                                                        <option selected disabled value="">Choose...</option>
                                                        <option value="Dr">Dr</option>
                                                        <option value="CMA">CMA</option>
                                                        <option value="Lab Assistant">Lab Assistant</option>
                                                        <option value="Medical Superitendent">Medical Superitendent</option>
                                                        <option value="Lab Incharge">Lab Incharge</option>
                                                        <option value="Medical Officer">Medical Officer</option>
                                                        <option value="Nursing Officer">Nursing Officer</option>
                                                        <option value="Pharmacy Assistant">Pharmacy Assistant</option>
                                                        <option value="BMET">BMET</option>
                                                        <option value="Medical Assistant">Medical Assistant</option>
                                                        <option value="Health Assistant">Health Assistant</option>
                                                        <option value="ART Counsellor">ART Counsellor</option>
                                                        <option value="MDGP">MDGP</option>
                                                        <option value="Anesthesia Assistant">Anesthesia Assistant</option>
                                                        <option value="Cashier">Cashier</option>
                                                        <option value="Computer Operator">Computer Operator</option>
                                                        <option value="Dental Surgeon">Dental Surgeon</option>
                                                        <option value="Pharmacy Officer">Pharmacy Officer</option>
                                                        <option value="ANM">ANM</option>
                                                        @foreach ($user_category as $category)
                                                            @if (!in_array($category->fldcategtory, $arraydata))
                                                                <option value="{{ $category->fldcategory }}"
                                                                    {{ old('designation') == $category->fldcategory ? 'selected' : '' }}>
                                                                    {{ $category->fldcategory }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Name</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-md-12">Gender</label>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="d-flex flex-row">
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="gender"
                                                                {{ old('gender') == 'male' ? 'checked' : '' }}
                                                                value="male" required>
                                                            <label for="" class="custom-control-label">Male</label>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="gender"
                                                                {{ old('gender') == 'female' ? 'checked' : '' }}
                                                                value="female" required>
                                                            <label for="" class="custom-control-label">Female</label>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="gender"
                                                                {{ old('gender') == 'others' ? 'checked' : '' }}
                                                                value="others" required>
                                                            <label for="" class="custom-control-label">Others</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Address</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" class="form-control" name="address"
                                                        value="{{ old('address') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Username</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" class="form-control" name="username"
                                                        value="{{ old('username') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Email Address</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Mobile Number</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ old('phone') }}" required pattern="[9][6-8][0-9]{8}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Signature Title</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" class="form-control" name="signature_title"
                                                        value="{{ old('signature_title') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Profile Assigned</label>
                                                <div class=" col-lg-12 col-sm-12 category-multiselect">
                                                    <select class="form-control select2" multiple name="groups[]">
                                                        <option value="" disabled>-- Multi Select --</option>
                                                        @foreach ($groups as $g)
                                                            <option value="{{ $g->id }}"
                                                                {{ in_array($g->id, old('groups') ?? []) ? 'selected' : '' }}>
                                                                {{ $g->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Patient Department</label>
                                                <div class=" col-lg-12 col-sm-12 category-multiselect">
                                                    <select class="form-control select2" multiple name="department[]">
                                                        <option value="" disabled>-- Multi Select --</option>
                                                        @foreach ($department as $dep)
                                                            <option value="{{ $dep->fldid }}"
                                                                {{ in_array($dep->fldid, old('department') ?? []) ? 'selected' : '' }}>
                                                                {{ $dep->flddept }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Hospital Department</label>
                                                <div class=" col-lg-12 col-sm-12 category-multiselect">
                                                    <select class="form-control select2" multiple
                                                        name="hospital_department[]">
                                                        <option value="" disabled>-- Multi Select --</option>
                                                        @foreach ($hospital_departments as $hospital_department)
                                                            <option value="{{ $hospital_department->id }}"
                                                                {{ in_array($hospital_department->id, old('hospital_department') ?? []) ? 'selected' : '' }}>
                                                                {{ $hospital_department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Roles</label>
                                                <div class=" col-lg-12 col-sm-12 category-multiselect">
                                                    <select class="form-control select2" multiple name="role[]">
                                                        <option value="" disabled>-- Multi Select --</option>
                                                        <option value="faculty"
                                                            {{ in_array('faculty', old('role') ?? []) ? 'selected' : '' }}>
                                                            Faculty
                                                        </option>
                                                        <option value="payable"
                                                            {{ in_array('payable', old('role') ?? []) ? 'selected' : '' }}>
                                                            Payable
                                                        </option>
                                                        <option value="referral"
                                                            {{ in_array('referral', old('role') ?? []) ? 'selected' : '' }}>
                                                            Referral
                                                        </option>
                                                        <option value="consultant"
                                                            {{ in_array('consultant', old('role') ?? []) ? 'selected' : '' }}>
                                                            Consultant
                                                        </option>
                                                        <option value="ip_clinician"
                                                            {{ in_array('ip_clinician', old('role') ?? []) ? 'selected' : '' }}>
                                                            IP
                                                            Clinician</option>
                                                        <option value="signature"
                                                            {{ in_array('signature', old('role') ?? []) ? 'selected' : '' }}>
                                                            Signature
                                                        </option>
                                                        <option value="data_export"
                                                            {{ in_array('data_export', old('role') ?? []) ? 'selected' : '' }}>
                                                            Data
                                                            Export
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row align-items-start">
                                                <label class="col-md-12 col-lg-12">Registration Type</label>
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <select class="form-control" name="identification_type"
                                                        id="identification">
                                                        <option value="" disabled selected>-- Select --</option>
                                                        <option value="nmc"
                                                            {{ old('identification_type') == 'nmc' ? 'selected' : '' }}>
                                                            NMC
                                                        </option>
                                                        <option value="nhbc"
                                                            {{ old('identification_type') == 'nhbc' ? 'selected' : '' }}>
                                                            NHPC
                                                        </option>
                                                        <option value="nnc"
                                                            {{ old('identification_type') == 'nnc' ? 'selected' : '' }}>
                                                            NNC
                                                        </option>
                                                        <option value="npc"
                                                            {{ old('identification_type') == 'npc' ? 'selected' : '' }}>
                                                            NPC
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 col-md-6 col-lg-6" style="display: none;">
                                                    <input type="text" name="identification" class="form-control"
                                                        value="{{ old('identification') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Expiry Date</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input type="text" name="expirydate" class="form-control"
                                                        id="expirydate" value="{{ old('expirydate') }}"
                                                        placeholder="YYYY-MM-DD" required autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-md-12">Nurse</label>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="d-flex">
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="nurse"
                                                                value="1" {{ old('nurse') == '1' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">Yes</label>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="nurse"
                                                                value="0" {{ !old('nurse') || old('nurse') == '0' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-md-12 col-lg-12">Status</label>
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="d-flex">
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="status"
                                                                value="active"
                                                                {{ !old('status') || old('status') == 'active' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">Active</label>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="status"
                                                                value="inactive"
                                                                {{ old('status') == 'inactive' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">Inactive</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-md-12">2 FA</label>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="d-flex">
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="two_fa"
                                                                value="1" {{ old('two_fa') == '1' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">True</label>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class=" custom-control-input" name="two_fa"
                                                                value="0" {{ !old('two_fa') || old('two_fa') == '0' ? 'checked' : '' }}
                                                                required>
                                                            <label for="" class="custom-control-label">False</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="row flex-column align-items-center">
                                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-12 text-center">
                                            <div class="traicolor mt-2">
                                                <img id="preview" src="{{ asset('assets/images/dummy-img.jpg') }}"
                                                    style="width: 100%; border-radius: 50%;">
                                            </div>
                                            <label for="image_file" class="btn btn-outline-primary mt-3">
                                                Upload Image
                                            </label>
                                            <input type="file" name="profile_image" class="d-none" id="image_file"
                                                onchange="fileSelectHandler()">
                                            <input type="hidden" id="x1" name="x1" />
                                            <input type="hidden" id="y1" name="y1" />
                                            <input type="hidden" id="x2" name="x2" />
                                            <input type="hidden" id="y2" name="y2" />
                                            <input type="hidden" id="w" name="w" />
                                            <input type="hidden" id="h" name="h" />
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-12 text-center pt-4">
                                            <div class="bg-light" style="width: 100%;">
                                                <img src="{{ asset('assets/images/blank.png') }}"
                                                    id="preview_signature_image" style="width: 100%;">
                                            </div>
                                            <label for="signature_image" class="btn btn-outline-primary mt-3">
                                                Upload Signature
                                            </label>
                                            <input type="file" name="signature_image" class="d-none"
                                                id="signature_image" onchange="fileSelectHandlerSignature()">
                                            <input type="hidden" id="x1s" name="x1s" />
                                            <input type="hidden" id="y1s" name="y1s" />
                                            <input type="hidden" id="x2s" name="x2s" />
                                            <input type="hidden" id="y2s" name="y2s" />
                                            <input type="hidden" id="ws" name="ws" />
                                            <input type="hidden" id="hs" name="hs" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary btn-action">
                                        <i class="fa fa-save"></i>&nbsp;Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script src="{{ asset('assets/jcrop/js/jquery.Jcrop.min.js') }}"></script>
    <script>
        $('#expirydate').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: "1600:2050",
            minDate: "+0d",
        });
        var identification_type = "{{ old('identification_type') }}";
        if (identification_type) {
            $('#identification').parent()
                .removeClass()
                .addClass('col-sm-6 col-md-6 col-lg-6')
                .next()
                .show()
        }

        $('#identification').on('change', function() {
            const type = $(this).val();
            $(this).parent()
                .removeClass()
                .addClass('col-sm-6 col-md-6 col-lg-6')
                .next()
                .show()
                .children()
                .attr('placeholder', type);
        });

        // convert bytes into friendly format
        function bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB'];
            if (bytes == 0) return 'n/a';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
        };

        // update info by cropping (onChange and onSelect events handler)
        function updateInfo(e) {
            if (!e) return;
            $('#x1').val(e.x);
            $('#y1').val(e.y);
            $('#x2').val(e.x2);
            $('#y2').val(e.y2);
            $('#w').val(e.w);
            $('#h').val(e.h);
        };

        // clear info by cropping (onRelease event handler)
        function clearInfo(e) {
            if (!e) return;
            $('#x1').val(e.x);
            $('#y1').val(e.y);
            $('#x2').val(e.x2);
            $('#y2').val(e.y2);
            $('#w').val(400);
            $('#h').val(400);
        }

        // update info by cropping (onChange and onSelect events handler)
        function updateInfoSignature(e) {
            if (!e) return;
            $('#x1s').val(e.x);
            $('#y1s').val(e.y);
            $('#x2s').val(e.x2);
            $('#y2s').val(e.y2);
            $('#ws').val(e.w);
            $('#hs').val(e.h);
        };

        // clear info by cropping (onRelease event handler)
        function clearInfoSignature(e) {
            if (!e) return;
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

            // get selected file
            var oFile = $('#image_file')[0].files[0];
            if (!$('#image_file')[0].files[0]) {
                return;
            }
            // check for image type (jpg and png are allowed)
            var rFilter = /^(image\/jpeg|image\/png|image\/jpg)$/i;
            if (!rFilter.test(oFile.type)) {
                showAlert('Please select a valid image file (jpg and png are allowed)', 'fail');
                return;
            }
            // preview element
            var oImage = document.getElementById('preview');
            // prepare HTML5 FileReader
            var oReader = new FileReader();
            oReader.onload = function(e) {
                // e.target.result contains the DataURL which we can use as a source of the image
                oImage.src = e.target.result;
                oImage.onload = function() { // onload event handler
                    var height = oImage.naturalHeight;
                    var width = oImage.naturalWidth;
                    window.URL.revokeObjectURL(oImage.src);

                    if (height < 400 || width < 400) {
                        oImage.src = "";
                        $('#image_file').val('');
                        showAlert(
                            'You have selected too small file, please select a one image with minimum size 400 X 400 px',
                            'fail');
                    }
                    // display step 2
                    $('.step2').fadeIn(500);
                    // display some basic image info
                    var sResultFileSize = bytesToSize(oFile.size);
                    $('#preview').css({
                        'border-radius': 'unset'
                    });
                    // destroy Jcrop if it is existed
                    if (typeof jcrop_api != 'undefined') {
                        jcrop_api.destroy();
                        jcrop_api = null;
                        $('#preview').css({
                            'width': '100%',
                            'height': 'auto',
                            'border-radius': 'unset'
                        });
                    }
                    setTimeout(function() {
                        // initialize Jcrop
                        $('#preview').Jcrop({
                            setSelect: [0, 0, 400, 400],
                            boxWidth: 800,
                            // boxHeight: 600,
                            minSize: [400, 400], // min crop size
                            aspectRatio: 1,
                            bgFade: true, // use fade effect
                            bgOpacity: .3, // fade opacity
                            onChange: updateInfo,
                            onSelect: updateInfo,
                            onRelease: clearInfo,
                            trueSize: [oImage.naturalWidth, oImage.naturalHeight],
                        }, function() {
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
            // get selected file
            var oFilesig = $('#signature_image')[0].files[0];
            if (!$('#signature_image')[0].files[0]) {
                return;
            }
            // check for image type (jpg and png are allowed)
            var rFilter = /^(image\/jpeg|image\/png|image\/jpg)$/i;
            if (!rFilter.test(oFilesig.type)) {
                showAlert('Please select a valid image file (jpg and png are allowed)', 'fail')
                return;
            }

            // preview_signature_image element
            var oImagesig = document.getElementById('preview_signature_image');
            // prepare HTML5 FileReader
            var oReadersig = new FileReader();
            oReadersig.onload = function(e) {
                // e.target.result contains the DataURL which we can use as a source of the image
                oImagesig.src = e.target.result;
                oImagesig.onload = function() { // onload event handler
                    var height = oImagesig.naturalHeight;

                    var width = oImagesig.naturalWidth;

                    // console.log(height);
                    // console.log(width);
                    window.URL.revokeObjectURL(oImagesig.src);

                    if (height < 400 || width < 200) {
                        oImagesig.src = "";
                        $('#signature_image').val('');
                        showAlert(
                            'You have selected too small file, please select a one image with minimum size 400 X 200 px',
                            'fail')
                    }
                    // display step 2
                    $('.step2').fadeIn(500);
                    // display some basic image info
                    var sResultFileSize = bytesToSize(oFilesig.size);

                    // destroy Jcrop if it is existed
                    if (typeof jcrop_api_Signature != 'undefined') {
                        jcrop_api_Signature.destroy();
                        jcrop_api_Signature = null;
                        $('#preview_signature_image').css({
                            'width': '100%',
                            'height': 'auto'
                        });
                    }
                    setTimeout(function() {
                        // initialize Jcrop
                        $('#preview_signature_image').Jcrop({
                            setSelect: [0, 0, 400, 200],
                            boxWidth: 800,
                            // boxHeight: 600,
                            minSize: [400, 200], // min crop size
                            aspectRatio: 2,
                            bgFade: true, // use fade effect
                            bgOpacity: .3, // fade opacity
                            onChange: updateInfoSignature,
                            onSelect: updateInfoSignature,
                            onRelease: clearInfoSignature,
                            trueSize: [oImagesig.naturalWidth, oImagesig.naturalHeight],
                        }, function() {
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
