@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row">
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

                        <form action="{{ route('admin.paymentgateway.convergent.store') }}" method="POST" class="" enctype="multipart/form-data">

                            {{--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border: 1px solid #c1c1c1;text-align: center;">
                                <span class="help-block" style="color: #b10e15;font-weight: 600;">
                                    Note : Convergent & nPay are mutually exlusive ie if Convergent is enabled nPay will be disabled and vice versa.
                                    Also if both are enabled Convergent will be used by default.
                                </span>
                            </div>
                            <div class="clearfix"></div>
                            <hr>--}}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test Server URL</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_test_server_url" class="form-control" value="{{ old('convergent_test_server_url') ? old('convergent_test_server_url') : Options::get('convergent_test_server_url') }}" placeholder="Test Server URL">
                                            <small class="help-block text-danger">{{$errors->first('convergent_test_server_url')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test Convergent PID</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_test_pid" class="form-control" value="{{ old('convergent_test_pid') ? old('convergent_test_pid') : Options::get('convergent_test_pid') }}" placeholder="Test Convergent PID">
                                            <small class="help-block text-danger">{{$errors->first('convergent_test_pid')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Test Secret Key</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_test_secret_key" class="form-control" value="{{ old('convergent_test_secret_key') ? old('convergent_test_secret_key') : Options::get('convergent_test_secret_key') }}" placeholder="Test Secret Key">
                                            <small class="help-block text-danger">{{$errors->first('convergent_test_secret_key')}}</small>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live Server URL</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_live_server_url" class="form-control" value="{{ old('convergent_live_server_url') ? old('convergent_live_server_url') : Options::get('convergent_live_server_url') }}" placeholder="Live Server URL">
                                            <small class="help-block text-danger">{{$errors->first('convergent_live_server_url')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live Convergent PID</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_live_pid" class="form-control" value="{{ old('convergent_live_pid') ? old('convergent_live_pid') : Options::get('convergent_live_pid') }}" placeholder="Live Convergent PID">
                                            <small class="help-block text-danger">{{$errors->first('convergent_live_pid')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="admin-first-name">Live Secret Key</label>
                                        <div class="col-md-7">
                                            <input type="text" name="convergent_live_secret_key" class="form-control" value="{{ old('convergent_live_secret_key') ? old('convergent_live_secret_key') : Options::get('convergent_live_secret_key') }}" placeholder="Live Secret Key">
                                            <small class="help-block text-danger">{{$errors->first('convergent_live_secret_key')}}</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                                        <div class="col-md-7">
                                            <div class="radio">
                                                <input id="status" class="magic-radio" type="radio" name="convergent_mode" value="test" {{ Options::get('convergent_mode') == 'test' ? "checked" : "" }}>
                                                <label for="status" style="color:#000000;">TEST</label>
                                                <input id="status-2" class="magic-radio" type="radio" name="convergent_mode" value="live" {{ Options::get('convergent_mode') == 'live' ? "checked" : "" }}>
                                                <label for="status-2" style="color:#000000;">LIVE</label>
                                            </div>
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
                                        <label class="col-md-3 control-label" for="admin-first-name">Convergent Logo</label>
                                        <div class="col-md-7">
                                            <input type="file" name="convergent_logo" class="form-control" value="{{ old('convergent_logo') ? old('convergent_logo') : Options::get('convergent_logo') }}">
                                            <small class="help-block" style="color: #000000;font-weight: 600;">Convergent Logo that will be displayed in payments page</small>
                                            <small class="help-block text-danger">{{$errors->first('convergent_logo')}}</small>
                                        </div>
                                    </div>

                                    @if ( Options::get('convergent_logo') && Options::get('convergent_logo') != "" )
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                            <div class="col-md-9">
                                                <img src="{{ asset('uploads/paymentpartner/'.Options::get('convergent_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                            </div>
                                            <label class="col-md-2 control-label" for="demo-contact-input"></label>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                                        <div class="col-md-7">
                                            <div class="radio">
                                                <input id="esewa_status" class="magic-radio" type="radio" name="convergent_payment_status" value="active" {{ Options::get('convergent_payment_status') == 'active' ? "checked" : "" }}>
                                                <label for="esewa_status" style="color:#000000;">YES</label>
                                                <input id="esewa_status-2" class="magic-radio" type="radio" name="convergent_payment_status" value="inactive" {{ Options::get('convergent_payment_status') == 'inactive' ? "checked" : "" }}>
                                                <label for="esewa_status-2" style="color:#000000;">NO</label>
                                            </div>
                                            <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable Convergent from the system.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="demo-contact-input">Generate QR</label>
                                        <div class="col-md-7">
                                            <div class="radio">
                                                <input id="generate_qr" class="magic-radio" type="radio" name="generate_qr" value="yes" {{ Options::get('generate_qr') == 'yes' ? "checked" : "" }}>
                                                <label for="generate_qr" style="color:#000000;">YES</label>
                                                <input id="generate_qr-2" class="magic-radio" type="radio" name="generate_qr" value="no" {{ Options::get('generate_qr') == 'no' ? "checked" : "" }}>
                                                <label for="generate_qr-2" style="color:#000000;">NO</label>
                                            </div>
                                            <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable Convergent QR from the system.</small>
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
