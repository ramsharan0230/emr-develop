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
                        Prabhu Bank Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.prabhu.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">



                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="prabhu_test_server_url">Test Server URL </label>
                            <div class="col-md-7">
                                <input type="text" name="prabhu_test_server_url" class="form-control" value="{{ old('prabhu_test_server_url') ? old('prabhu_test_server_url') : Options::get('prabhu_test_server_url') }}" placeholder="Test Server Url" >
                                <small class="help-block text-danger">{{$errors->first('prabhu_test_server_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="prabhu_test_pid">Test PID</label>
                            <div class="col-md-7">
                                <input type="text" name="prabhu_test_pid" class="form-control" value="{{ old('prabhu_test_pid') ? old('prabhu_test_pid') : Options::get('prabhu_test_pid') }}" placeholder="Test PID" >
                                <small class="help-block text-danger">{{$errors->first('prabhu_test_pid')}}</small>
                            </div>
                        </div>



                        <hr>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="prabhu_live_server_url">Live Server URL</label>
                            <div class="col-md-7">
                                <input type="text" name="prabhu_live_server_url" class="form-control" value="{{ old('prabhu_live_server_url') ? old('prabhu_live_server_url') : Options::get('prabhu_live_server_url') }}" placeholder="Live Server Url" >
                                <small class="help-block text-danger">{{$errors->first('prabhu_live_server_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="prabhu_live_pid">Live PID</label>
                            <div class="col-md-7">
                                <input type="text" name="prabhu_live_pid" class="form-control" value="{{ old('prabhu_live_pid') ? old('prabhu_live_pid') : Options::get('prabhu_live_pid') }}" placeholder="Live PID" >
                                <small class="help-block text-danger">{{$errors->first('prabhu_live_pid')}}</small>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-md-5 control-label" for="prabhu_mode">Current Mode</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="status" class="magic-radio" type="radio" name="prabhu_mode" value="test" {{ Options::get('prabhu_mode') == 'test' ? "checked" : "" }}>
                                    <label for="status" style="color:#000000;">TEST</label>
                                    <input id="status-2" class="magic-radio" type="radio" name="prabhu_mode" value="live" {{ Options::get('prabhu_mode') == 'live' ? "checked" : "" }}>
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
                            <label class="col-md-3 control-label" for="admin-first-name">Prabhu Bank Logo</label>
                            <div class="col-md-7">
                                <input type="file" name="prabhu_logo" class="form-control" value="{{ old('prabhu_logo') ? old('prabhu_logo') : Options::get('prabhu_logo') }}">
                                <small class="help-block" style="color: #000000;font-weight: 600;">Prabhu Logo that will be displayed in payments page</small>
                                <small class="help-block text-danger">{{$errors->first('prabhu_logo')}}</small>
                            </div>
                        </div>

                        @if ( Options::get('prabhu_logo') && Options::get('prabhu_logo') != "" )
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                <div class="col-md-9">
                                    <img src="{{ asset('uploads/paymentpartner/'.Options::get('prabhu_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                </div>
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="esewa_status" class="magic-radio" type="radio" name="prabhu_payment_status" value="active" {{ Options::get('prabhu_payment_status') == 'active' ? "checked" : "" }}>
                                    <label for="esewa_status" style="color:#000000;">YES</label>
                                    <input id="esewa_status-2" class="magic-radio" type="radio" name="prabhu_payment_status" value="inactive" {{ Options::get('prabhu_payment_status') == 'inactive' ? "checked" : "" }}>
                                    <label for="esewa_status-2" style="color:#000000;">NO</label>
                                </div>
                                <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable Prabhu Bank Payment from the system.</small>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
