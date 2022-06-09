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
                        IMEPay Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.imepay.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                        <div class="form-group" style="margin-left: 35px;text-transform: uppercase;">
                            <label class="col-md-5 col-md-offset-2 control-label" for="admin-first-name">Test Parameters</label>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Mechant Code</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_merchant_code" class="form-control" value="{{ old('imepay_test_merchant_code') ? old('imepay_test_merchant_code') : Options::get('imepay_test_merchant_code') }}" placeholder="Test Merchant Code" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_merchant_code')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Username</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_username" class="form-control" value="{{ old('imepay_test_username') ? old('imepay_test_username') : Options::get('imepay_test_username') }}" placeholder="Test Username" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_username')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Password</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_password" class="form-control" value="{{ old('imepay_test_password') ? old('imepay_test_password') : Options::get('imepay_test_password') }}" placeholder="Test Password" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Module</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_module" class="form-control" value="{{ old('imepay_test_module') ? old('imepay_test_module') : Options::get('imepay_test_module') }}" placeholder="Test Module Name" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_module')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Token Generate URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_token_url" class="form-control" value="{{ old('imepay_test_token_url') ? old('imepay_test_token_url') : Options::get('imepay_test_token_url') }}" placeholder="Test Token URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_token_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Checkout URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_checkout_url" class="form-control" value="{{ old('imepay_test_checkout_url') ? old('imepay_test_checkout_url') : Options::get('imepay_test_checkout_url') }}" placeholder="Test Checkout URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_checkout_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Payment Confirm URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_payment_confirm_url" class="form-control" value="{{ old('imepay_test_payment_confirm_url') ? old('imepay_test_payment_confirm_url') : Options::get('imepay_test_payment_confirm_url') }}" placeholder="Test Payment Confirm URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_payment_confirm_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Payment Recheck URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_test_payment_recheck_url" class="form-control" value="{{ old('imepay_test_payment_recheck_url') ? old('imepay_test_payment_recheck_url') : Options::get('imepay_test_payment_recheck_url') }}" placeholder="Test Payment Recheck URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_test_payment_recheck_url')}}</small>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                        <div class="form-group" style="margin-left: 35px;text-transform: uppercase;">
                            <label class="col-md-5 col-md-offset-2 control-label" for="admin-first-name">Live Parameters</label>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Mechant Code</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_merchant_code" class="form-control" value="{{ old('imepay_live_merchant_code') ? old('imepay_live_merchant_code') : Options::get('imepay_live_merchant_code') }}" placeholder="Live Merchant Code" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_merchant_code')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Username</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_username" class="form-control" value="{{ old('imepay_live_username') ? old('imepay_live_username') : Options::get('imepay_live_username') }}" placeholder="Live Username" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_username')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Password</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_password" class="form-control" value="{{ old('imepay_live_password') ? old('imepay_live_password') : Options::get('imepay_live_password') }}" placeholder="Live Password" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Module</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_module" class="form-control" value="{{ old('imepay_live_module') ? old('imepay_live_module') : Options::get('imepay_live_module') }}" placeholder="Live Module Name" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_module')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Token Generate URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_token_url" class="form-control" value="{{ old('imepay_live_token_url') ? old('imepay_live_token_url') : Options::get('imepay_live_token_url') }}" placeholder="Live Token URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_token_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Checkout URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_checkout_url" class="form-control" value="{{ old('imepay_live_checkout_url') ? old('imepay_live_checkout_url') : Options::get('imepay_live_checkout_url') }}" placeholder="Live Checkout URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_checkout_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Payment Confirm URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_payment_confirm_url" class="form-control" value="{{ old('imepay_live_payment_confirm_url') ? old('imepay_live_payment_confirm_url') : Options::get('imepay_live_payment_confirm_url') }}" placeholder="Live Payment Confirm URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_payment_confirm_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Payment Recheck URL</label>
                            <div class="col-md-7">
                                <input type="text" name="imepay_live_payment_recheck_url" class="form-control" value="{{ old('imepay_live_payment_recheck_url') ? old('imepay_live_payment_recheck_url') : Options::get('imepay_live_payment_recheck_url') }}" placeholder="Live Payment Recheck URL" >
                                <small class="help-block text-danger">{{$errors->first('imepay_live_payment_recheck_url')}}</small>
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                                <div class="col-md-7">
                                    <div class="radio">
                                        <input id="status" class="magic-radio" type="radio" name="imepay_mode" value="test" {{ Options::get('imepay_mode') == 'test' ? "checked" : "" }}>
                                        <label for="status" style="color:#000000;">TEST</label>
                                        <input id="status-2" class="magic-radio" type="radio" name="imepay_mode" value="live" {{ Options::get('imepay_mode') == 'live' ? "checked" : "" }}>
                                        <label for="status-2" style="color:#000000;">LIVE</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-5 control-label" for="demo-contact-input">Is Active</label>
                                <div class="col-md-7">
                                    <div class="radio">
                                        <input id="esewa_status" class="magic-radio" type="radio" name="imepay_payment_status" value="active" {{ Options::get('imepay_payment_status') == 'active' ? "checked" : "" }}>
                                        <label for="esewa_status" style="color:#000000;">YES</label>
                                        <input id="esewa_status-2" class="magic-radio" type="radio" name="imepay_payment_status" value="inactive" {{ Options::get('imepay_payment_status') == 'inactive' ? "checked" : "" }}>
                                        <label for="esewa_status-2" style="color:#000000;">NO</label>
                                    </div>
                                    <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable IMEPay from the system.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="admin-first-name">IMEPay Logo</label>
                                <div class="col-md-7">
                                    <input type="file" name="imepay_logo" class="form-control" value="{{ old('imepay_logo') ? old('imepay_logo') : Options::get('imepay_logo') }}">
                                    <small class="help-block" style="color: #000000;font-weight: 600;">IMEPay Logo that will be displayed in payments page</small>
                                    <small class="help-block text-danger">{{$errors->first('imepay_logo')}}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if ( Options::get('imepay_logo') && Options::get('imepay_logo') != "" )
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                    <div class="col-md-7">
                                        <img src="{{ asset('uploads/paymentpartner/'.Options::get('imepay_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                    </div>
                                    <label class="col-md-2 control-label" for="demo-contact-input"></label>
                                </div>
                            @endif
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label"></label>
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-block btn-primary" name="submit" value="UPDATE">
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
