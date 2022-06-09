@extends('frontend.layouts.master')
@section('content')

    <div id="content-container">
        <ol class="breadcrumb">
            {!!$breadcrumbs!!}
            <a href="{{ route('admin.paymentgateway.list') }}" class="btn btn-primary breadCrumbRightBackBtn"> <i class="fa fa-chevron-left" aria-hidden="true" style="font-size: 11px;"></i> Back</a>
        </ol>

        <div id="page-content" class="adminMgmtPageContent">

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

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        nPay Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.npay.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">

                   {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border: 1px solid #c1c1c1;text-align: center;">
                        <span class="help-block" style="color: #b10e15;font-weight: 600;">
                            Note : Convergent & nPay are mutually exlusive ie if Convergent is enabled nPay will be disabled and vice versa.
                            Also if both are enabled Convergent will be used by default.
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <hr>--}}

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant ID</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_test_merchant_id" class="form-control" value="{{ old('npay_test_merchant_id') ? old('npay_test_merchant_id') : Options::get('npay_test_merchant_id') }}" placeholder="Test Merchant ID" >
                                <small class="help-block text-danger">{{$errors->first('npay_test_merchant_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant Username</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_test_merchant_username" class="form-control" value="{{ old('npay_test_merchant_username') ? old('npay_test_merchant_username') : Options::get('npay_test_merchant_username') }}" placeholder="Test Merchant Username" >
                                <small class="help-block text-danger">{{$errors->first('npay_test_merchant_username')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant password</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_test_merchant_password" class="form-control" value="{{ old('npay_test_merchant_password') ? old('npay_test_merchant_password') : Options::get('npay_test_merchant_password') }}" placeholder="Test Merchant Password" >
                                <small class="help-block text-danger">{{$errors->first('npay_test_merchant_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Signature Passcode</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_test_signature_password" class="form-control" value="{{ old('npay_test_signature_password') ? old('npay_test_signature_password') : Options::get('npay_test_signature_password') }}" placeholder="Test Signature Passcode" >
                                <small class="help-block text-danger">{{$errors->first('npay_test_signature_password')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant ID</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_live_merchant_id" class="form-control" value="{{ old('npay_live_merchant_id') ? old('npay_live_merchant_id') : Options::get('npay_live_merchant_id') }}" placeholder="Live Merchant ID" >
                                <small class="help-block text-danger">{{$errors->first('npay_live_merchant_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant Username</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_live_merchant_username" class="form-control" value="{{ old('npay_live_merchant_username') ? old('npay_live_merchant_username') : Options::get('npay_live_merchant_username') }}" placeholder="Live Merchant Username" >
                                <small class="help-block text-danger">{{$errors->first('npay_live_merchant_username')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant password</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_live_merchant_password" class="form-control" value="{{ old('npay_live_merchant_password') ? old('npay_live_merchant_password') : Options::get('npay_live_merchant_password') }}" placeholder="Live Merchant Password" >
                                <small class="help-block text-danger">{{$errors->first('npay_live_merchant_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Signature Passcode</label>
                            <div class="col-md-7">
                                <input type="text" name="npay_live_signature_password" class="form-control" value="{{ old('npay_live_signature_password') ? old('npay_live_signature_password') : Options::get('npay_live_signature_password') }}" placeholder="Live Signature Passcode" >
                                <small class="help-block text-danger">{{$errors->first('npay_live_signature_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="status" class="magic-radio" type="radio" name="npay_mode" value="test" {{ Options::get('npay_mode') == 'test' ? "checked" : "" }}>
                                    <label for="status" style="color:#000000;">TEST</label>
                                    <input id="status-2" class="magic-radio" type="radio" name="npay_mode" value="live" {{ Options::get('npay_mode') == 'live' ? "checked" : "" }}>
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
                            <label class="col-md-3 control-label" for="admin-first-name">nPay Logo</label>
                            <div class="col-md-7">
                                <input type="file" name="npay_logo" class="form-control" value="{{ old('npay_logo') ? old('npay_logo') : Options::get('npay_logo') }}">
                                <small class="help-block" style="color: #000000;font-weight: 600;">nPay Logo that will be displayed in payments page</small>
                                <small class="help-block text-danger">{{$errors->first('npay_logo')}}</small>
                            </div>
                        </div>

                        @if ( Options::get('npay_logo') && Options::get('npay_logo') != "" )
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                <div class="col-md-9">
                                    <img src="{{ asset('uploads/paymentpartner/'.Options::get('npay_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                </div>
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="esewa_status" class="magic-radio" type="radio" name="npay_payment_status" value="active" {{ Options::get('npay_payment_status') == 'active' ? "checked" : "" }}>
                                    <label for="esewa_status" style="color:#000000;">YES</label>
                                    <input id="esewa_status-2" class="magic-radio" type="radio" name="npay_payment_status" value="inactive" {{ Options::get('npay_payment_status') == 'inactive' ? "checked" : "" }}>
                                    <label for="esewa_status-2" style="color:#000000;">NO</label>
                                </div>
                                <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable nPay Payment from the system.</small>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
