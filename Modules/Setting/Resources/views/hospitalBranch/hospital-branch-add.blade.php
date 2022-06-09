@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create Hospital Branch</h4>
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
                    <form action="{{ route('hospital.branch.store') }}" method="POST" enctype="multipart/form-data" id="create-hospital-branch-form" class="form-horizontal">
                        @csrf

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Branch Name*:</label>
                            <div class="col-sm-4">
                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ old('branch_name') }}" placeholder="Enter Branch Name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Slogan:</label>
                            <div class="col-sm-4">
                                <input type="text" id="branch_slogan" name="branch_slogan" class="form-control" value="{{ old('branch_slogan') }}" placeholder="Enter Slogan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Branch Code*:</label>
                            <div class="col-sm-4">
                                <input type="text" id="branch_code" name="branch_code" class="form-control" value="{{ old('branch_code') }}" placeholder="Enter Code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Address*:</label>
                            <div class="col-sm-4">
                                <input type="text" id="branch_address" name="branch_address" class="form-control" value="{{ old('branch_address') }}" placeholder="Enter Address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Feedback/Support Email*:</label>
                            <div class="col-sm-4">
                                <input type="text" id="branch_email" name="branch_email" class="form-control" value="{{ old('branch_email') }}" placeholder="Enter Email Address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Telephone No.:</label>
                            <div class="col-sm-4">
                                <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}" placeholder="Enter Telephone No.">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Mobile No.:</label>
                            <div class="col-sm-4">
                                <input type="text" id="mobile_no" name="mobile_no" class="form-control" value="{{ old('mobile_no') }}" placeholder="Enter Mobile No.">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Show Rank*:</label>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="system_patient_rank" value="yes" checked class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio1">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="system_patient_rank" value="no" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio1">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <img src="#" id="previewLogo">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Branch Logo:</label>
                            <div class="col-sm-4">
                                <div class="custom-file">
                                    <input type="file" name="logo" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Status:</label>
                            <div class="col-sm-4">
                                <select name="status" id="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('hospital.branch') }}" class="btn iq-bg-danger">Cancel</a>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewLogo').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFile").change(function(){
            readURL(this);
        });
    </script>
@endpush
