@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <h5>IRD Settings</h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">

                        @if(Session::get('success_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('success_message') }}
                            </div>
                        @endif

                        @if(Session::get('error_message'))
                            <div class="alert alert-danger containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('error_message') }}
                            </div>
                        @endif

                        <form action="{{ route('save.ird') }}" method="POST" class="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test Server URL</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_test_server_url" class="form-control" value="{{ old('ird_test_server_url') ? old('ird_test_server_url') : Options::get('ird_test_server_url') }}" placeholder="Test Server URL">
                                            <small class="help-block text-danger">{{$errors->first('ird_test_server_url')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test IRD Username</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_test_username" class="form-control" value="{{ old('ird_test_username') ? old('ird_test_username') : Options::get('ird_test_username') }}" placeholder="Test IRD Username">
                                            <small class="help-block text-danger">{{$errors->first('ird_test_username')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test Password</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_test_password" class="form-control" value="{{ old('ird_test_password') ? old('ird_test_password') : Options::get('ird_test_password') }}" placeholder="Test Password">
                                            <small class="help-block text-danger">{{$errors->first('ird_test_password')}}</small>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live Server URL</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_live_server_url" class="form-control" value="{{ old('ird_live_server_url') ? old('ird_live_server_url') : Options::get('ird_live_server_url') }}" placeholder="Live Server URL">
                                            <small class="help-block text-danger">{{$errors->first('ird_live_server_url')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live IRD Username</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_live_username" class="form-control" value="{{ old('ird_live_username') ? old('ird_live_username') : Options::get('ird_live_username') }}" placeholder="Live IRD Username">
                                            <small class="help-block text-danger">{{$errors->first('ird_live_username')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live Password</label>
                                        <div class="col-md-7">
                                            <input type="text" name="ird_live_password" class="form-control" value="{{ old('ird_live_password') ? old('ird_live_password') : Options::get('ird_live_password') }}" placeholder="Live Password">
                                            <small class="help-block text-danger">{{$errors->first('ird_live_password')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label"></label>
                                        <div class="col-md-4">
                                            <input type="submit" class="btn btn-block btn-primary" name="submit" value="UPDATE">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                                        <div class="col-md-7">
                                            <div class="radio">
                                                <input id="ird_status" class="magic-radio" type="radio" name="ird_sync_status" value="active" {{ Options::get('ird_sync_status') == 'active' ? "checked" : "" }}>
                                                <label for="ird_status" style="color:#000000;">YES</label>
                                                <input id="ird_status-2" class="magic-radio" type="radio" name="ird_sync_status" value="inactive" {{ Options::get('ird_sync_status') == 'inactive' ? "checked" : "" }}>
                                                <label for="ird_status-2" style="color:#000000;">NO</label>
                                            </div>
                                            <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable IRD from the system.</small>
                                            <br>
                                            <small class="help-block text-danger">{{$errors->first('ird_sync_status')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                                        <div class="col-md-7">
                                            <div class="radio">
                                                <input id="status" class="magic-radio" type="radio" name="ird_mode" value="test" {{ Options::get('ird_mode') == 'test' ? "checked" : "" }}>
                                                <label for="status" style="color:#000000;">TEST</label>
                                                <input id="status-2" class="magic-radio" type="radio" name="ird_mode" value="live" {{ Options::get('ird_mode') == 'live' ? "checked" : "" }}>
                                                <label for="status-2" style="color:#000000;">LIVE</label>
                                                <br>
                                                <small class="help-block text-danger">{{$errors->first('ird_mode')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
