@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Edit User</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @include('frontend.common.alert_message')

                        <div class="iq-card-body">
                            <form action="{{ route('admin.user.update') }}" method="POST" id="create-user-form" class="form-horizontal form-padding" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @php
                                    $arraydata = ['Dr','Medical','Lab Incharge','Medical Officer','Nursing Officer','Pharmacy Assistant','BMET','Medical Assistant','Health Assistant','ART Counsellor','MDGP','Anesthesia Assistant','Cashier','Computer Operator','Dental Surgeon','Pharmacy Officer','ANM'];
                                @endphp
                                <input type="hidden" name="_id" value="{{ $user_details->id }}">
                                <input type="hidden" name="old_username" value="{{ $user_details->username }}">
                                <input type="hidden" name="old_email" value="{{ $user_details->email }}">
                                <input type="hidden" name="user_type" value="{{ $user_type }}">
                                <div class="form-group row">

                                    <label class="col-sm-2" for="">Designation/ Category:</label>
                                    <div class="col-sm-4">
                                        <input name="designation" id="designation" type="text" list="category" class="form-control" value="{{$user_details->fldcategory}}"/>
                                        <datalist id="category">
                                            <option selected disabled value="">Choose...</option>
                                            <option value="Dr" {{ $user_details->fldcategory == "Dr"?'selected':'' }}>Dr</option>
                                            <option value="CMA" {{ $user_details->fldcategory == "CMA"?'selected':'' }}>CMA</option>
                                            <option value="Lab Assistant" {{ $user_details->fldcategory == "Lab Assistant"?'selected':'' }}>Lab Assistant</option>
                                            <option value="Medical Superitendent" {{ $user_details->fldcategory == "Medical Superitendent"?'selected':'' }}>Medical Superitendent</option>
                                            <option value="Lab Incharge" {{ $user_details->fldcategory == "Lab Incharge"?'selected':'' }}>Lab Incharge</option>
                                            <option value="Medical Officer" {{ $user_details->fldcategory == "Medical Officer"?'selected':'' }}>Medical Officer</option>
                                            <option value="Nursing Officer" {{ $user_details->fldcategory == "Nursing Officer"?'selected':'' }}>Nursing Officer</option>
                                            <option value="Pharmacy Assistant" {{ $user_details->fldcategory == "Pharmacy Assistant"?'selected':'' }}>Pharmacy Assistant</option>
                                            <option value="BMET" {{ $user_details->fldcategory == "BMET"?'selected':'' }}>BMET</option>
                                            <option value="Medical Assistant" {{ $user_details->fldcategory == "Medical Assistant"?'selected':'' }}>Medical Assistant</option>
                                            <option value="Health Assistant" {{ $user_details->fldcategory == "Health Assistant"?'selected':'' }}>Health Assistant</option>
                                            <option value="ART Counsellor" {{ $user_details->fldcategory == "ART Counsellor"?'selected':'' }}>ART Counsellor</option>
                                            <option value="MDGP" {{ $user_details->fldcategory == "MDGP"?'selected':'' }}>MDGP</option>
                                            <option value="Anesthesia Assistant" {{ $user_details->fldcategory == "Anesthesia Assistant"?'selected':'' }}>Anesthesia Assistant</option>
                                            <option value="Cashier" {{ $user_details->fldcategory == "Cashier"?'selected':'' }}>Cashier</option>
                                            <option value="Computer Operator" {{ $user_details->fldcategory == "Computer Operator"?'selected':'' }}>Computer Operator</option>
                                            <option value="Dental Surgeon" {{ $user_details->fldcategory == "Dental Surgeon"?'selected':'' }}>Dental Surgeon</option>
                                            <option value="Pharmacy Officer" {{ $user_details->fldcategory == "Pharmacy Officer"?'selected':'' }}>Pharmacy Officer</option>
                                            <option value="ANM" {{ $user_details->fldcategory == "ANM"?'selected':'' }}>ANM</option>
                                            @if(isset($user_category) and !empty($user_category))
                                                @foreach($user_category as $category)
                                                    @if(!in_array($category->fldcategtory,$arraydata))
                                                        <option {{ $user_details->fldcategory == $category->fldcategory ? "selected" : '' }} value="{{$category->fldcategory}}">{{$category->fldcategory}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </datalist>
                                    </div>
                                <!-- <div class="col-sm-6">
                                <input type="text" name="designation_free" class="form-control" id="designation_free" value="{{ $user_details->fldcategory }}">
                            </div> -->
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Full Name:</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="firstname" name="firstname" class="form-control" placeholder="First Name" value="{{ old('firstname') ? old('firstname') : $user_details->firstname }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="middlename" name="middlename" class="form-control" placeholder="Middle Name" value="{{ old('middlename') ? old('middlename') : $user_details->middlename }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="lastname" name="lastname" class="form-control form-control-sm" value="{{ old('lastname') ? old('lastname') : $user_details->lastname }}" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Signature Title:</label>
                                    <div class="col-sm-4">
                                        <input id="signature_title" class="form-control" type="text" name="signature_title" value="{{ $user_details->signature_title }}">
                                    </div>
                                    <label class="col-md-1 control-label border-none" for="demo-address-input">Email{{--<span class="required_color">*</span>--}}</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') ? old('email') : $user_details->email }}">
                                        {{--<small class="help-block text-danger">{{$errors->first('email')}}</small>--}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2 " for="">Address:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-sm" name="address" value="{{ isset($user_details->user_details->address) ? $user_details->user_details->address : '' }}">
                                    </div>
                                    <label class="control-label col-sm-1" for="">Contact:</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control form-control-sm" name="phone" value="{{ isset($user_details->user_details->phone) ? $user_details->user_details->phone : '' }}">
                                    </div>
                                </div>

                                @if ( $user_type != 'superadmin' )
                                    <div class="form-group row mt-2">
                                        <p class="col-md-2 control-label border-none">Group:<span class="required_color">*</span></p>
                                        <div class="col-md-9">
                                            <div class="checkbox row">
                                                @if( count($groups) >0 )
                                                    @foreach($groups as $g)
                                                        <div class="col-md-3">
                                                            <input class="magic-checkbox" type="checkbox" name="groups[]" value="{{ $g->id }}" {{ in_array($g->id,$current_user_grps) ? "checked" : '' }}>
                                                            <label for="">{{ $g->name }}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <small class="help-block text-danger">{{$errors->first('groups')}}</small>
                                        </div>
                                    </div>
                                    <hr>
                                @endif

                                <div class="form-group row mt-3">
                                    <p class="col-md-2 control-label border-none">Department:{{-- <span class="required_color">*</span>--}}</p>
                                    <div class="col-md-9">
                                        <div class="checkbox row">
                                            @if( count($department) >0 )
                                                @foreach($department as $dep)
                                                    <div class="col-md-6">
                                                        <input id="department-{{$dep->fldid}}" class="magic-checkbox" type="checkbox" name="department[]" value="{{$dep->fldid}}" {{ in_array($dep->fldid, $current_user_dept)?'checked':'' }}>
                                                        <label for="" style="color: #000023; border: none;"> {{ $dep->flddept }}</label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <small class="help-block text-danger">{{$errors->first('department')}}</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-2 control-label border-none" for="profile_image">Profile Image
                                    </label>
                                    <div class="col-md-5">
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
                                        <img class="w-50 profile_image_old" src="data:image/jpg;base64,{{ $user_details->profile_image }}" alt="">
                                    </div>
                                </div>
                                <hr>

                                {{--<div class="form-group row">
                                    <label class="col-md-3 control-label border-none" for="profile_image">Profile Image:</label>
                                    <div class="col-md-3">
                                        <input type="file" class="" name="profile_image" id="profile_image">
                                        <small>Note: max 1MB file is allowed.</small>
                                    </div>
                                </div>--}}

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
                                        <img class="w-50 signature_image_old" src="data:image/jpg;base64,{{ $user_details->signature_image }}" alt="">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Registration Type:</label>
                                    <div class="col-sm-4">
                                        <select name="identification_type" class="form-control" id="identification">
                                            <option value="">--Select Registration Type--</option>
                                            <option value="NMC" {{ $user_details->nmc !='' ? 'selected' : ''}}>NMC</option>
                                            <option value="NHPC" {{ $user_details->nhbc !='' ? 'selected' : ''}}>NHPC</option>
                                            <option value="NNC" {{ $user_details->nnc !='' ? 'selected' : ''}}>NNC</option>
                                            <option value="NPC" {{ $user_details->npc !='' ? 'selected' : ''}}>NPC</option>
                                        </select>

                                    </div>

                                </div>

                                <div id="nmc_number" style="{{ ($user_details->nmc !='' and !is_null($user_details->nmc)) ? 'display:block;' : 'display:none;'}}">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-2" for="">NMC:</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="nmc" class="form-control" placeholder="NMC" value="{{ $user_details->nmc }}">
                                        </div>

                                    </div>
                                </div>
                                <div id="nhpc_number" style="{{($user_details->nhbc !='' and !is_null($user_details->nhbc)) ? 'display:block;' : 'display:none;'}}">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-2" for="">NHPC:</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="nhbc" class="form-control" placeholder="NHPC" value="{{ $user_details->nhbc }}">
                                        </div>
                                    </div>
                                </div>
                                <div id="nnc_number" style="{{($user_details->nnc !='' and !is_null($user_details->nnc)) ? 'display:block;' : 'display:none;'}}">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-2" for="">NNC:</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="nnc" class="form-control" placeholder="NNC" value="{{ $user_details->nnc }}">
                                        </div>
                                    </div>
                                </div>
                                <div id="npc_number" style="{{($user_details->npc !='' and !is_null($user_details->npc)) ? 'display:block;' : 'display:none;'}}">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-2" for="">NPC:</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="npc" class="form-control" placeholder="NPC" value="{{ $user_details->npc }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <p class="col-md-2 control-label border-none">Role</p>
                                    <div class="col-md-9 mt-3">
                                        <div class="checkbox row">
                                            <div class="col-md-2">
                                                <input id="faculty" class="magic-checkbox" type="checkbox" name="faculty" {{ $user_details->fldfaculty == 1 ? "checked" : '' }} value="faculty">
                                                <label for="" style="color: #000023; border: none;">Faculty</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="payable" class="magic-checkbox" type="checkbox" name="payable" {{ $user_details->fldpayable == 1 ? "checked" : '' }} value="payable">
                                                <label for="" style="color: #000023; border: none;">Payable</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="referral" class="magic-checkbox" type="checkbox" name="referral" {{ $user_details->fldreferral == 1 ? "checked" : '' }} value="referral">
                                                <label for="" style="color: #000023; border: none;">Referral</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="consultant" class="magic-checkbox" type="checkbox" name="consultant" {{ $user_details->fldopconsult == 1 ? "checked" : '' }} value="consultant">
                                                <label for="" style="color: #000023; border: none;">Consultant</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="ip_clinician" class="magic-checkbox" type="checkbox" name="ip_clinician" {{ $user_details->fldipconsult == 1 ? "checked" : '' }} value="ip_clinician">
                                                <label for="" style="color: #000023; border: none;">IP Clinician</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="signature" class="magic-checkbox" type="checkbox" name="signature" {{ $user_details->fldsigna == 1 ? "checked" : '' }} value="signature">
                                                <label for="" style="color: #000023; border: none;">Signature</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="data_export" class="magic-checkbox" type="checkbox" name="data_export" {{ $user_details->fldreport == 1 ? "checked" : '' }} value="data_export">
                                                <label for="" style="color: #000023; border: none;">Data Export</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Nurse:</label>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="nurse" value="1" {{ $user_details->fldnursing == 1 ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="nurse" value="0" {{ $user_details->fldnursing == 0 ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">No</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row mt-3">
                                    <p class="col-md-2 control-label border-none">Hospital Department:</p>
                                    <div class="col-md-9">
                                        <div class="checkbox row">
                                            @if( count($hospital_departments) >0 )
                                                @foreach($hospital_departments as $hospital_department)
                                                    <div class="col-md-12">
                                                        <input id="hospital-department-{{$hospital_department->id}}" class="magic-checkbox" type="checkbox" name="hospital_department[]" value="{{$hospital_department->id}}" {{ in_array($hospital_department->id, $user_hospital_dept)?'checked':'' }}>
                                                        <label for="" style="color: #000023; border: none;"> {{ $hospital_department->name }} ( {{ isset($hospital_department->branchData) ? $hospital_department->branchData->name : "Main Branch" }} )</label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="clearfix"></div>
                                        <small class="help-block text-danger">{{$errors->first('hospital_department')}}</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Expiry Date:</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="expirydate" class="form-control" id="expirydate" value="{{(isset($user_details) and $user_details->fldexpirydate !='') ? $user_details->fldexpirydate : ''}}">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2 align-self-center mb-0" for="">2 FA:</label>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="two_fa" value="active" {{ $user_details->two_fa == 1 ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">True</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="two_fa" value="inactive" {{ $user_details->two_fa == 0 ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">False</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2" for="">Active:</label>
                                    <div class="col-sm-4">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="status" value="active" {{ $user_details->status == 'active' ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">True</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="status" value="inactive" {{ $user_details->status == 'inactive' ? "checked" : "" }}>
                                            <label class="custom-control-label" for="">False</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-5 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>&nbsp;
                                    <button type="submit" class="btn iq-bg-danger">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

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
        $('#expirydate').datepicker({

            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: "1600:2050",
            minDate: "+0d",

        });
        $('#identification').on('change', function () {
            var type = $(this).val();
            if (type == 'NMC') {
                $('#nmc_number').show();
                $('#nhpc_number').hide();
                $('#nnc_number').hide();
                $('#npc_number').hide();
                // $('#nhpc').val('');
                // $('#nnc').val('');
                // $('#npc').val('');
            } else if (type == 'NHPC') {
                $('#nhpc_number').show();
                $('#nmc_number').hide();
                $('#nnc_number').hide();
                $('#npc_number').hide();
                // $('#nmc').val('');
                // $('#nnc').val('');
                // $('#npc').val('');
            } else if (type == 'NNC') {
                $('#nnc_number').show();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').hide();
                // $('#nmc').val('');
                // $('#nhpc').val('');
                // $('#npc').val('');
            } else if (type == 'NPC') {
                $('#nnc_number').hide();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').show();
                // $('#nmc').val('');
                // $('#nhpc').val('');
                // $('#nnc').val('');
            } else {
                $('#nnc_number').hide();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').hide();
                // $('#nmc').val('');
                // $('#nhpc').val('');
                // $('#nnc').val('');
                // $('#npc').val('');
            }
        })
        $("#create-user-form").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                /*email: {
                    required: true,
                    email: true
                },*/
                username: {
                    required: true,
                    minlength: 5
                },
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                re_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                /*email: "Please enter a valid email address",*/
            }
        });

        $(document).ready(function () {
            $('#designation').change(function () {
                if ($('#designation').val() !== "") {
                    $('#designation_free').prop('disabled', true);
                } else {
                    $('#designation_free').prop('disabled', false);
                }
            })
        })
    </script>
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
                            // aspectRatio: 400 / 400,
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
                            // aspectRatio: 400 / 200,
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
