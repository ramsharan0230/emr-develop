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
                        HBL Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.hbl.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        {{ csrf_field() }}


                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant ID</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_merchant_test_id" class="form-control" value="{{ old('hbl_merchant_test_id') ? old('hbl_merchant_test_id') : Options::get('hbl_merchant_test_id') }}" placeholder="Test Merchant ID" >
                                <small class="help-block text-danger">{{$errors->first('hbl_merchant_test_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Merchant Name</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_merchant_test_name" class="form-control" value="{{ old('hbl_merchant_test_name') ? old('hbl_merchant_test_name') : Options::get('hbl_merchant_test_name') }}" placeholder="Test Merchant Name" >
                                <small class="help-block text-danger">{{$errors->first('hbl_merchant_test_name')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Secret Code</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_test_secret" class="form-control" value="{{ old('hbl_test_secret') ? old('hbl_test_secret') : Options::get('hbl_test_secret') }}" placeholder="Test Secret Code" >
                                <small class="help-block text-danger">{{$errors->first('hbl_test_secret')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Test Server URL</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_test_server_url" class="form-control" value="{{ old('hbl_test_server_url') ? old('hbl_test_server_url') : Options::get('hbl_test_server_url') }}" placeholder="Test Server URL" >
                                <small class="help-block text-danger">{{$errors->first('hbl_test_server_url')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant ID</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_merchant_live_id" class="form-control" value="{{ old('hbl_merchant_live_id') ? old('hbl_merchant_live_id') : Options::get('hbl_merchant_live_id') }}" placeholder="Live Merchant ID" >
                                <small class="help-block text-danger">{{$errors->first('hbl_merchant_live_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Merchant Name</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_merchant_live_name" class="form-control" value="{{ old('hbl_merchant_live_name') ? old('hbl_merchant_live_name') : Options::get('hbl_merchant_live_name') }}" placeholder="Live Merchant Name" >
                                <small class="help-block text-danger">{{$errors->first('hbl_merchant_live_name')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Secret Code</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_live_secret" class="form-control" value="{{ old('hbl_live_secret') ? old('hbl_live_secret') : Options::get('hbl_live_secret') }}" placeholder="Live Secret Code" >
                                <small class="help-block text-danger">{{$errors->first('hbl_live_secret')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Server URL</label>
                            <div class="col-md-7">
                                <input type="text" name="hbl_live_server_url" class="form-control" value="{{ old('hbl_live_server_url') ? old('hbl_live_server_url') : Options::get('hbl_live_server_url') }}" placeholder="Live Server URL" >
                                <small class="help-block text-danger">{{$errors->first('hbl_live_server_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="demo-contact-input">Current Mode</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="status" class="magic-radio" type="radio" name="hbl_mode" value="test" {{ Options::get('hbl_mode') == 'test' ? "checked" : "" }}>
                                    <label for="status" style="color:#000000;">TEST</label>
                                    <input id="status-2" class="magic-radio" type="radio" name="hbl_mode" value="live" {{ Options::get('hbl_mode') == 'live' ? "checked" : "" }}>
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
                            <label class="col-md-3 control-label" for="admin-first-name">HBL Logo</label>
                            <div class="col-md-7">
                                <input type="file" name="hbl_logo" class="form-control" value="{{ old('hbl_logo') ? old('hbl_logo') : Options::get('hbl_logo') }}">
                                <small class="help-block" style="color: #000000;font-weight: 600;">HBL Logo that will be displayed in payments page</small>
                                <small class="help-block text-danger">{{$errors->first('hbl_logo')}}</small>
                            </div>
                        </div>

                        @if ( Options::get('hbl_logo') && Options::get('hbl_logo') != "" )
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                <div class="col-md-9">
                                    <img src="{{ asset('uploads/paymentpartner/'.Options::get('hbl_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                </div>
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="hbl_status" class="magic-radio" type="radio" name="hbl_payment_status" value="active" {{ Options::get('hbl_payment_status') == 'active' ? "checked" : "" }}>
                                    <label for="hbl_status" style="color:#000000;">YES</label>
                                    <input id="hbl_status-2" class="magic-radio" type="radio" name="hbl_payment_status" value="inactive" {{ Options::get('hbl_payment_status') == 'inactive' ? "checked" : "" }}>
                                    <label for="hbl_status-2" style="color:#000000;">NO</label>
                                </div>
                                <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable HBL from the system.</small>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
