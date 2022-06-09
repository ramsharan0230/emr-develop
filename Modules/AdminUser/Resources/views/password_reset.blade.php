@extends('frontend.layouts.master')
@section('content')



<!-- TOP Nav Bar END -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

           

            @if(Session::get('success_message_special'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                &nbsp;&nbsp;{!! Session::get('success_message_special') !!}
            </div>
            @endif

            @if(Session::get('error_message'))
            <div class="alert alert-danger containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                &nbsp;&nbsp;{{ Session::get('error_message') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <span class="table-add float-right mb-3 mr-2">

                    </span>
                </div>
                <div class="iq-card-body">
                    <form action="{{ route('admin.user.password-reset.store') }}" method="POST" class="form-horizontal form-padding">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label class="col-md-2 control-label" for="admin-first-name">Current Password <span class="required_color">*</span></label>
                            <div class="col-md-4">
                                <input type="password" name="current_password" class="form-control" id="current_password" value="{{ old('current_password') }}" placeholder="Current Password">
                                <small class="help-block text-danger">{{$errors->first('current_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 control-label" for="admin-first-name">New Password <span class="required_color">*</span></label>
                            <div class="col-md-4">
                                <input type="password" name="new_password" class="form-control" id="new_password" value="{{ old('new_password') }}" placeholder="New Password">
                                <small class="help-block text-danger">{{$errors->first('new_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 control-label" for="admin-first-name">Confirm Password <span class="required_color">*</span></label>
                            <div class="col-md-4">
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" value="{{ old('confirm_password') }}" placeholder="Confirm Password">
                                <small class="help-block text-danger">{{$errors->first('confirm_password')}}</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                  <input type="checkbox" onclick="showPassowrdToggell()" id="show-password1">
                                    <label class="mb-1" onclick="showPassowrdToggell()">&nbsp;Show Password</label>
                                </div>
                               
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-block btn-primary" name="submit" value="Reset Password">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>
    function showPassowrdToggell() {
        var x = document.getElementById("current_password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        var y = document.getElementById("new_password");
        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
        }
        var z = document.getElementById("confirm_password");
        if (z.type === "password") {
            z.type = "text";
        } else {
            z.type = "password";
        }
    }
</script>
@stop