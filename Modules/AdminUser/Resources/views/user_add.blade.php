@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Create New User</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @include('frontend.common.alert_message')

                        <form action="{{ route('admin.user.store') }}" method="POST" id="create-user-form" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            @php
                                $arraydata = ['Dr','Medical','Lab Incharge','Medical Officer','Nursing Officer','Pharmacy Assistant','BMET','Medical Assistant','Health Assistant','ART Counsellor','MDGP','Anesthesia Assistant','Cashier','Computer Operator','Dental Surgeon','Pharmacy Officer','ANM'];
                            @endphp
                            <div class="form-group row">
                                <label class="col-sm-2" for="">Designation/ Category:<span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input name="designation" id="designation" type="text" list="category" class="form-control" required/>
                                    <datalist id="category">
                                        <option selected disabled value="">Choose...</option>
                                        <option {{ old("designation") == 'Dr' ? "selected" : '' }} value="Dr">Dr</option>
                                        <option {{ old("designation") == 'CMA' ? "selected" : '' }} value="CMA">CMA</option>
                                        <option {{ old("designation") == 'Lab Assistant' ? "selected" : '' }} value="Lab Assistant">Lab Assistant</option>
                                        <option {{ old("designation") == 'Medical Superitendent' ? "selected" : '' }} value="Medical Superitendent">Medical Superitendent</option>
                                        <option {{ old("designation") == 'Lab Incharge' ? "selected" : '' }} value="Lab Incharge">Lab Incharge</option>
                                        <option {{ old("designation") == 'Medical Officer' ? "selected" : '' }} value="Medical Officer">Medical Officer</option>
                                        <option {{ old("designation") == 'Nursing Officer' ? "selected" : '' }} value="Nursing Officer">Nursing Officer</option>
                                        <option {{ old("designation") == 'Pharmacy Assistant' ? "selected" : '' }} value="Pharmacy Assistant">Pharmacy Assistant</option>
                                        <option {{ old("designation") == 'BMET' ? "selected" : '' }} value="BMET">BMET</option>
                                        <option {{ old("designation") == 'Medical Assistant' ? "selected" : '' }} value="Medical Assistant">Medical Assistant</option>
                                        <option {{ old("designation") == 'Health Assistant' ? "selected" : '' }} value="Health Assistant">Health Assistant</option>
                                        <option {{ old("designation") == 'ART Counsellor' ? "selected" : '' }} value="ART Counsellor">ART Counsellor</option>
                                        <option {{ old("designation") == 'MDGP' ? "selected" : '' }} value="MDGP">MDGP</option>
                                        <option {{ old("designation") == 'Anesthesia Assistant' ? "selected" : '' }} value="Anesthesia Assistant">Anesthesia Assistant</option>
                                        <option {{ old("designation") == 'Cashier' ? "selected" : '' }} value="Cashier">Cashier</option>
                                        <option {{ old("designation") == 'Computer Operator' ? "selected" : '' }} value="Computer Operator">Computer Operator</option>
                                        <option {{ old("designation") == 'Dental Surgeon' ? "selected" : '' }} value="Dental Surgeon">Dental Surgeon</option>
                                        <option {{ old("designation") == 'Pharmacy Officer' ? "selected" : '' }} value="Pharmacy Officer">Pharmacy Officer</option>
                                        <option {{ old("designation") == 'ANM' ? "selected" : '' }} value="ANM">ANM</option>
                                        @if(isset($user_category) and !empty($user_category))
                                            @foreach($user_category as $category)
                                                @if(!in_array($category->fldcategtory,$arraydata))
                                                    <option {{ old("designation") == $category->fldcategory ? "selected" : '' }} value="{{$category->fldcategory}}">{{$category->fldcategory}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </datalist>

                                </div>
                            <!--  <div class="col-sm-3">
                            <input type="text" name="designation_free" class="form-control" id="designation_free" value="{{ old('designation_free') }}">
                        </div> -->
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2" for="">Full Name:</label>
                                <div class="col-sm-4">
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname') }}" placeholder="First Name">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" id="middlename" name="middlename" class="form-control" value="{{ old('middlename') }}" placeholder="Middle Name">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="lastname" name="lastname" class="form-control form-control-sm" value="{{ old('lastname') }}" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Signature Title:</label>
                                <div class="col-sm-4">
                                    <input id="signature_title" class="form-control" type="text" name="signature_title" value="{{ old('signature_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Address:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control form-control-sm" name="address" value="{{ old('address') }}">
                                </div>
                                <label class="control-label col-sm-2" for="">Contact:</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" name="phone" value="{{ old('phone') }}" maxlength="10">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Userame:<span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control form-control-sm" autocomplete="new-username" name="username" id="username" value="{{ old('username') }}">
                                </div>
                                <label class="control-label col-sm-2" for="">Email:</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" autocomplete="new-email" id="email" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Password:<span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control form-control-sm" autocomplete="new-password" name="password" id="password" value="{{ old('password') }}">
                                    <small class="help-block text-danger">{{$errors->first('password')}}</small>
                                </div>
                                <label class="control-label col-sm-2" for="">Confirm Password:<span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control form-control-sm" name="re_password" autocomplete="new-re_password" id="re_password" value="{{ old('re_password') }}">
                                    <small class="help-block text-danger">{{$errors->first('re_password')}}</small>
                                </div>
                            </div>

                            <!-- Adde by Anish for HMIS-->
{{--                            <div class="form-group row">--}}
{{--                                <label class="col-sm-2" for="">Hospital Branch:</label>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <select class="form-control" name="branches">--}}
{{--                                        <option value="">--Select--</option>--}}
{{--                                        @if($hospital_branches)--}}
{{--                                            @forelse($hospital_branches as $hospital_branch)--}}
{{--                                                <option value="{{ $hospital_branch->id }}"> {{ $hospital_branch->name }} </option>--}}
{{--                                            @empty--}}
{{--                                            @endforelse--}}
{{--                                        @endif--}}
{{--                                    </select>--}}
{{--                                    --}}{{--                                        <input class="magic-checkbox" type="checkbox" name="branches" value="{{ $hospital_branch->id }}" {{ in_array( $hospital_branch->id,collect(old('branches'))->toArray()) ? "checked" : '' }}>--}}
{{--                                    --}}{{--                                        <label for=""> {{ $hospital_branch->name }}</label>--}}
{{--                                </div>--}}
{{--                                <div class="clearfix"></div>--}}
{{--                                <small class="help-block text-danger">{{$errors->first('branches')}}</small>--}}
{{--                            </div>--}}
{{--                            <hr>--}}

                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Permission:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        @if( count($groups) >0 )
                                            @foreach($groups as $g)
                                                <div class="col-sm-4">
                                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                        <input class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="groups[]" value="{{ $g->id }}" {{ in_array( $g->id,collect(old('groups'))->toArray()) ? "checked" : '' }}>
                                                        <label for="" class="custom-control-label"> {{ $g->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="clearfix"></div>
                                        <small class="help-block text-danger">{{$errors->first('groups')}}</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <p class="control-label col-sm-2" for="">Patient Department: </p>
                                <div class="col-sm-9">
                                    <div class="row">
                                        @if( count($department) >0 )
                                            @foreach($department as $dep)
                                                <div class="col-md-4">
                                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                        <input class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="department[]" value="{{$dep->fldid}}" {{ in_array( $dep->flddept, collect(old('department'))->toArray()) ? "checked" : '' }}>
                                                        <label for="" class="custom-control-label"> {{ $dep->flddept }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Profile Image:</label>
                                <div class="col-sm-3">
                                    <div class="custom-file">

                                        <img id="preview">
                                        <br>
                                        <span class="col-4">
                                    <span></span>
                                    <input type="file" name="profile_image" class="custom-file-input" id="image_file" onchange="fileSelectHandler()">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                    {{--                                <input type="file" name="profile_image" id="image_file" onchange="fileSelectHandler()"/>--}}
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
                                <label class="control-label col-sm-2" for="">Signature Image:</label>
                                <div class="col-sm-4">
                                    <div class="custom-file">
                                        <img id="preview_signature_image">
                                        <br>
                                        <span class="col-4">
                                    <span></span>
                                    <input type="file" class="custom-file-input" name="signature_image" id="signature_image" onchange="fileSelectHandlerSignature()">
                                    <label class="custom-file-label" for="signature_image">Choose file</label>
                                    {{--                                <input type="file" name="profile_image" id="image_file" onchange="fileSelectHandler()"/>--}}
                                    <small>Note: max 1MB file is allowed.</small>
                                </span>

                                        <div class="image-error" style="color:red"></div>

                                        <input type="hidden" id="x1s" name="x1s"/>
                                        <input type="hidden" id="y1s" name="y1s"/>
                                        <input type="hidden" id="x2s" name="x2s"/>
                                        <input type="hidden" id="y2s" name="y2s"/>
                                        <input type="hidden" id="ws" name="ws"/>
                                        <input type="hidden" id="hs" name="hs"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Registration Type:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="identification_type" id="identification">
                                        <option value="">--Select Registration Type--</option>
                                        <option value="NMC" {{ old("identification_type") == 'NMC' ? "selected" : '' }}>NMC</option>
                                        <option value="NHPC" {{ old("identification_type") == 'NHPC' ? "selected" : '' }}>NHPC</option>
                                        <option value="NNC" {{ old("identification_type") == 'NNC' ? "selected" : '' }}>NNC</option>
                                        <option value="NPC" {{ old("identification_type") == 'NPC' ? "selected" : '' }}>NPC</option>
                                    </select>

                                </div>

                            </div>
                            <div class="form-group row" id="nmc_number" style="display: none;">
                                <label class="control-label col-sm-2" for="">NMC:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="nmc" id="nmc" placeholder="NMC" value="{{ old('nmc') }}">
                                </div>

                            </div>
                            <div class="form-group row" id="nhpc_number" style="display: none;">
                                <label class="control-label col-sm-2 align-self-center id=" nhpc" mb-0" for="">NHPC:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="nhpc" placeholder="NHPC" value="{{ old('nhpc') }}">
                                </div>
                            </div>
                            <div class="form-group row" id="nnc_number" style="display: none;">
                                <label class="control-label col-sm-2" for="">NNC:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="nnc" id="nnc" placeholder="NNC" value="{{ old('nnc') }}">
                                </div>
                            </div>
                            <div class="form-group row" id="npc_number" style="display: none;">
                                <label class="control-label col-sm-2" for="">NPC:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="npc" id="npc" placeholder="NPC" value="{{ old('npc') }}">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <p class="col-md-2">Role:</p>
                                <div class="col-md-9">
                                    <div class="checkbox row">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="faculty1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="faculty" {{ old("faculty") == 'faculty' ? "checked" : '' }} value="faculty">
                                                <label for="faculty" class="custom-control-label">Faculty</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="payable1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="payable" {{ old("payable") == 'payable' ? "checked" : '' }} value="payable">
                                                <label class="custom-control-label" for="payable">Payable</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="referral1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="referral" {{ old("referral") == 'referral' ? "checked" : '' }} value="referral">
                                                <label class="custom-control-label" for="referral">Referral</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="consultant1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="consultant" {{ old("consultant") == 'consultant' ? "checked" : '' }} value="consultant">
                                                <label class="custom-control-label" for="consultant">Consultant</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="ip_clinician1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="ip_clinician" {{ old("ip_clinician") == 'ip_clinician' ? "checked" : '' }} value="ip_clinician">
                                                <label class="custom-control-label" for="ip_clinician">IP Clinician</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="signature1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="signature" {{ old("signature") == 'signature' ? "checked" : '' }} value="signature">
                                                <label class="custom-control-label" for="signature">Signature</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input id="data_export1" class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="data_export" {{ old("data_export") == 'data_export' ? "checked" : '' }} value="data_export">
                                                <label class="custom-control-label" for="data_export">Data Export</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Nurse:</label>
                                <div class="col-sm-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="nurse" value="1">
                                        <label class="custom-control-label" for="">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" checked="" name="nurse" value="0">
                                        <label class="custom-control-label" for="">No</label>
                                    </div>
                                </div>
                                <!--  <label class="control-label col-sm-1" for="">Active:</label>
                                <div class="col-sm-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" checked="" name="status" value="active">
                                        <label class="custom-control-label" for="">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="status" value="inactive">
                                        <label class="custom-control-label" for="">Inative</label>
                                    </div>
                                </div> -->
                            </div>
                            <hr>
                            <div class="form-group row">
                                <p class="col-sm-2" for="">Hospital Department: </p>
                                <div class="col-sm-9">
                                    <div class="row">
                                        @if( count($hospital_departments) >0 )
                                            @foreach($hospital_departments as $hospital_department)
                                                <div class="col-md-4">
                                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                        <input class="magic-checkbox custom-control-input bg-primary" type="checkbox" name="hospital_department[]" value="{{$hospital_department->id}}" {{ in_array( $hospital_department->id, collect(old('hospital_department'))->toArray()) ? "checked" : '' }}>
                                                        <label for="" class="custom-control-label"> {{ $hospital_department->name }} ( {{ isset($hospital_department->branchData) ? $hospital_department->branchData->name : "Main Branch" }} )</label>
                                                    </div>
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
                                    <input type="text" name="expirydate" class="form-control" id="expirydate" value="{{ old('expirydate') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="">2 FA:</label>
                                <div class="col-sm-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="two_fa" value="1" checked>
                                        <label class="custom-control-label" for="">True</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="two_fa" value="0">
                                        <label class="custom-control-label" for="">False</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-2" for="">Active:</label>
                                <div class="col-sm-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" checked="" name="status" value="active">
                                        <label class="custom-control-label" for="">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="status" value="inactive">
                                        <label class="custom-control-label" for="">Inative</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-5 text-right">
                                <button type="submit" class="btn btn-primary">Create</button>&nbsp;
                                <button type="submit" class="btn btn-danger">Cancel</button>
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
                $('#nhpc').val('');
                $('#nnc').val('');
                $('#npc').val('');
            } else if (type == 'NHPC') {
                $('#nhpc_number').show();
                $('#nmc_number').hide();
                $('#nnc_number').hide();
                $('#npc_number').hide();
                $('#nmc').val('');
                $('#nnc').val('');
                $('#npc').val('');
            } else if (type == 'NNC') {
                $('#nnc_number').show();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').hide();
                $('#nmc').val('');
                $('#nhpc').val('');
                $('#npc').val('');
            } else if (type == 'NPC') {
                $('#nnc_number').hide();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').show();
                $('#nmc').val('');
                $('#nhpc').val('');
                $('#nnc').val('');
            } else {
                $('#nnc_number').hide();
                $('#nhpc_number').hide();
                $('#nmc_number').hide();
                $('#npc_number').hide();
                $('#nmc').val('');
                $('#nhpc').val('');
                $('#nnc').val('');
                $('#npc').val('');
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
                password: {
                    required: true,
                    minlength: 5
                },
                re_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                /*email: {
                    required: true,
                    email: true
                },*/
                username: {
                    required: true,
                    minlength: 5
                },
                /*'groups[]': {
                    required: true,
                    maxlength: 2
                },
                'department[]': {
                    required: true,
                    maxlength: 2
                }*/
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
                'groups[]': {
                    required: "You must check at least 1 group",
                    maxlength: "Check no more than {0} boxes"
                },
                'department[]': {
                    required: "You must check at least 1 department",
                    maxlength: "Check no more than {0} boxes"
                }
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
