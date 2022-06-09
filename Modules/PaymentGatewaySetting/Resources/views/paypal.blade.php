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
                        Paypal Configurations
                        {{--<span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>--}}
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.paypal.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name"></label>
                            <div class="col-md-6">
                                <label class="control-label" for="admin-first-name">PAYPAL LIVE CREDENTIALS</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name">Live Client ID</label>
                            <div class="col-md-6">
                                <input type="text" name="paypal_live_client_id" class="form-control" value="{{ old('paypal_live_client_id') ? old('paypal_live_client_id') : Options::get('paypal_live_client_id') }}" placeholder="Live Paypal Client ID" >
                                <small class="help-block text-danger">{{$errors->first('paypal_live_client_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name">Live Secret Key</label>
                            <div class="col-md-6">
                                <input type="text" name="paypal_live_secret_id" class="form-control" value="{{ old('paypal_live_secret_id') ? old('paypal_live_secret_id') : Options::get('paypal_live_secret_id') }}" placeholder="Live Paypal Secret ID" >
                                <small class="help-block text-danger">{{$errors->first('paypal_live_secret_id')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name"></label>
                            <div class="col-md-6">
                                <label class="control-label" for="admin-first-name">PAYPAL TEST CREDENTIALS</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name">Test Client ID</label>
                            <div class="col-md-6">
                                <input type="text" name="paypal_test_client_id" class="form-control" value="{{ old('paypal_test_client_id') ? old('paypal_test_client_id') : Options::get('paypal_test_client_id') }}" placeholder="Test Paypal Client ID" >
                                <small class="help-block text-danger">{{$errors->first('paypal_test_client_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="admin-first-name">Test Secret Key</label>
                            <div class="col-md-6">
                                <input type="text" name="paypal_test_secret_id" class="form-control" value="{{ old('paypal_test_secret_id') ? old('paypal_test_secret_id') : Options::get('paypal_test_secret_id') }}" placeholder="Test Paypal Secret Key" >
                                <small class="help-block text-danger">{{$errors->first('paypal_test_secret_id')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-5">

                            <div class="form-group">
                                <label class="col-md-7 control-label" for="demo-contact-input">Current Mode</label>
                                <div class="col-md-5">
                                    <div class="radio">
                                        <input id="status" class="magic-radio" type="radio" name="paypal_mode" value="sandbox" {{ Options::get('paypal_mode') == 'sandbox' ? "checked" : "" }}>
                                        <label for="status" style="color:#000000;">TEST</label>
                                        <input id="status-2" class="magic-radio" type="radio" name="paypal_mode" value="live" {{ Options::get('paypal_mode') == 'live' ? "checked" : "" }}>
                                        <label for="status-2" style="color:#000000;">LIVE</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-7 control-label" for="demo-contact-input">Is Active</label>
                                <div class="col-md-5">
                                    <div class="radio">
                                        <input id="esewa_status" class="magic-radio" type="radio" name="paypal_payment_status" value="active" {{ Options::get('paypal_payment_status') == 'active' ? "checked" : "" }}>
                                        <label for="esewa_status" style="color:#000000;">YES</label>
                                        <input id="esewa_status-2" class="magic-radio" type="radio" name="paypal_payment_status" value="inactive" {{ Options::get('paypal_payment_status') == 'inactive' ? "checked" : "" }}>
                                        <label for="esewa_status-2" style="color:#000000;">NO</label>
                                    </div>
                                    <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable Paypal from the system.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label" for="admin-first-name">Paypal Logo</label>
                                <div class="col-md-6">
                                    <input type="file" name="paypal_logo" style="border: none !important;" class="form-control" value="{{ old('paypal_logo') ? old('paypal_logo') : Options::get('paypal_logo') }}">
                                    <small class="help-block" style="color: #000000;font-weight: 600;">Paypal Logo that will be displayed in payments page</small>
                                    <small class="help-block text-danger">{{$errors->first('paypal_logo')}}</small>
                                </div>
                            </div>

                            @if ( Options::get('paypal_logo') && Options::get('paypal_logo') != "" )
                                <div class="form-group">
                                    <label class="col-md-5 control-label" for="demo-contact-input"></label>
                                    <div class="col-md-6">
                                        <img src="{{ asset('uploads/paymentpartner/'.Options::get('paypal_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                    </div>
                                    <label class="col-md-2 control-label" for="demo-contact-input"></label>
                                </div>
                            @endif

                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-block btn-primary" name="submit" value="UPDATE">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
