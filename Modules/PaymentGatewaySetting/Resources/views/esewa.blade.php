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
                        eSewa Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.esewa.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Server URL</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_test_server_url" class="form-control" value="{{ old('esewa_test_server_url') ? old('esewa_test_server_url') : Options::get('esewa_test_server_url') }}" placeholder="Test Server URL" >
                                <small class="help-block text-danger">{{$errors->first('esewa_test_server_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Transaction Verify URL</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_test_transactionverf_url" class="form-control" value="{{ old('esewa_test_transactionverf_url') ? old('esewa_test_transactionverf_url') : Options::get('esewa_test_transactionverf_url') }}" placeholder="Test Transaction Verify URL" >
                                <small class="help-block text-danger">{{$errors->first('esewa_test_transactionverf_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant Code</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_test_merchant_code" class="form-control" value="{{ old('esewa_test_merchant_code') ? old('esewa_test_merchant_code') : Options::get('esewa_test_merchant_code') }}" placeholder="Test Merchant Code" >
                                <small class="help-block text-danger">{{$errors->first('esewa_test_merchant_code')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Server URL</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_live_server_url" class="form-control" value="{{ old('esewa_live_server_url') ? old('esewa_live_server_url') : Options::get('esewa_live_server_url') }}" placeholder="Live Server URL" >
                                <small class="help-block text-danger">{{$errors->first('esewa_live_server_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Transaction Verify URL</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_live_transactionverf_url" class="form-control" value="{{ old('esewa_live_transactionverf_url') ? old('esewa_live_transactionverf_url') : Options::get('esewa_live_transactionverf_url') }}" placeholder="Live Transaction verify URL" >
                                <small class="help-block text-danger">{{$errors->first('esewa_live_transactionverf_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant Code</label>
                            <div class="col-md-7">
                                <input type="text" name="esewa_live_merchant_code" class="form-control" value="{{ old('esewa_live_merchant_code') ? old('esewa_live_merchant_code') : Options::get('esewa_live_merchant_code') }}" placeholder="Live Merchant Code" >
                                <small class="help-block text-danger">{{$errors->first('esewa_live_merchant_code')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="status" class="magic-radio" type="radio" name="esewa_mode" value="test" {{ Options::get('esewa_mode') == 'test' ? "checked" : "" }}>
                                    <label for="status" style="color:#000000;">TEST</label>
                                    <input id="status-2" class="magic-radio" type="radio" name="esewa_mode" value="live" {{ Options::get('esewa_mode') == 'live' ? "checked" : "" }}>
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
                            <label class="col-md-3 control-label" for="admin-first-name">eSewa Logo</label>
                            <div class="col-md-7">
                                <input type="file" name="esewa_logo" class="form-control" value="{{ old('esewa_logo') ? old('esewa_logo') : Options::get('esewa_logo') }}">
                                <small class="help-block" style="color: #000000;font-weight: 600;">eSewa Logo that will be displayed in payments page</small>
                                <small class="help-block text-danger">{{$errors->first('esewa_logo')}}</small>
                            </div>
                        </div>

                        @if ( Options::get('esewa_logo') && Options::get('esewa_logo') != "" )
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input"></label>
                            <div class="col-md-9">
                                <img src="{{ asset('uploads/paymentpartner/'.Options::get('esewa_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                            </div>
                            <label class="col-md-2 control-label" for="demo-contact-input"></label>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="esewa_status" class="magic-radio" type="radio" name="esewa_payment_status" value="active" {{ Options::get('esewa_payment_status') == 'active' ? "checked" : "" }}>
                                    <label for="esewa_status" style="color:#000000;">YES</label>
                                    <input id="esewa_status-2" class="magic-radio" type="radio" name="esewa_payment_status" value="inactive" {{ Options::get('esewa_payment_status') == 'inactive' ? "checked" : "" }}>
                                    <label for="esewa_status-2" style="color:#000000;">NO</label>
                                </div>
                                <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable eSewa from the system.</small>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
