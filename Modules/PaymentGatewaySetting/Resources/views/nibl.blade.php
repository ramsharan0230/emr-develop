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
                        NIBL Configurations
                        <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                    </h3>
                </div>

                <form action="{{ route('admin.paymentgateway.nibl.store') }}" method="POST" class="panel-body form-horizontal form-padding" enctype="multipart/form-data">



                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_transaction_url">Test Transaction Url </label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_transaction_url" class="form-control" value="{{ old('nibl_test_transaction_url') ? old('nibl_test_transaction_url') : Options::get('nibl_test_transaction_url') }}" placeholder="Test Transaction Url" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_transaction_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_transaction_verify_url">Test Transaction Verify Url</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_transaction_verify_url" class="form-control" value="{{ old('nibl_test_transaction_verify_url') ? old('nibl_test_transaction_verify_url') : Options::get('nibl_test_transaction_verify_url') }}" placeholder="Test Transaction Verify Url" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_transaction_verify_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_bank_id">Test BankId</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_bank_id" class="form-control" value="{{ old('nibl_test_bank_id') ? old('nibl_test_bank_id') : Options::get('nibl_test_bank_id') }}" placeholder="Test BankId" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_bank_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_payee_id">Test Payee ID</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_payee_id" class="form-control" value="{{ old('nibl_test_payee_id') ? old('nibl_test_payee_id') : Options::get('nibl_test_payee_id') }}" placeholder="Test Payee ID" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_payee_id')}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_username">Test Username</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_username" class="form-control" value="{{ old('nibl_test_username') ? old('nibl_test_username') : Options::get('nibl_test_username') }}" placeholder="Test Username" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_username')}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_test_password">Test Password</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_test_password" class="form-control" value="{{ old('nibl_test_password') ? old('nibl_test_password') : Options::get('nibl_test_password') }}" placeholder="Test Password" >
                                <small class="help-block text-danger">{{$errors->first('nibl_test_password')}}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_live_transaction_url">Live Transaction Url</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_transaction_url" class="form-control" value="{{ old('nibl_live_transaction_url') ? old('nibl_live_transaction_url') : Options::get('nibl_live_transaction_url') }}" placeholder="Live Transaction Url" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_transaction_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_live_transaction_verify_url">Live Transaction Verify Url</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_transaction_verify_url" class="form-control" value="{{ old('nibl_live_transaction_verify_url') ? old('nibl_live_transaction_verify_url') : Options::get('nibl_live_transaction_verify_url') }}" placeholder="Live Transaction Verify Url" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_transaction_verify_url')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_live_bank_id">Live BankId </label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_bank_id" class="form-control" value="{{ old('nibl_live_bank_id') ? old('nibl_live_bank_id') : Options::get('nibl_live_bank_id') }}" placeholder="Live BankId" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_bank_id')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="admin-first-name">Live Payee ID</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_payee_id" class="form-control" value="{{ old('nibl_live_payee_id') ? old('nibl_live_payee_id') : Options::get('nibl_live_payee_id') }}" placeholder="Live Payee ID" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_payee_id')}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_live_username">Live Username</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_username" class="form-control" value="{{ old('nibl_live_username') ? old('nibl_live_username') : Options::get('nibl_live_username') }}" placeholder="Live Username" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_username')}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_live_password">Live Password</label>
                            <div class="col-md-7">
                                <input type="text" name="nibl_live_password" class="form-control" value="{{ old('nibl_live_password') ? old('nibl_live_password') : Options::get('nibl_live_password') }}" placeholder="Live Password" >
                                <small class="help-block text-danger">{{$errors->first('nibl_live_password')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label" for="nibl_mode">Current Mode</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="status" class="magic-radio" type="radio" name="nibl_mode" value="test" {{ Options::get('nibl_mode') == 'test' ? "checked" : "" }}>
                                    <label for="status" style="color:#000000;">TEST</label>
                                    <input id="status-2" class="magic-radio" type="radio" name="nibl_mode" value="live" {{ Options::get('nibl_mode') == 'live' ? "checked" : "" }}>
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
                            <label class="col-md-3 control-label" for="admin-first-name">NIBL Logo</label>
                            <div class="col-md-7">
                                <input type="file" name="nibl_logo" class="form-control" value="{{ old('nibl_logo') ? old('nibl_logo') : Options::get('nibl_logo') }}">
                                <small class="help-block" style="color: #000000;font-weight: 600;">NIBL Logo that will be displayed in payments page</small>
                                <small class="help-block text-danger">{{$errors->first('nibl_logo')}}</small>
                            </div>
                        </div>

                        @if ( Options::get('nibl_logo') && Options::get('nibl_logo') != "" )
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="demo-contact-input"></label>
                                <div class="col-md-9">
                                    <img src="{{ asset('uploads/paymentpartner/'.Options::get('nibl_logo')) }}" class="img-thumbnail" style="max-height: 70px;">
                                </div>
                                <label class="col-md-2 control-label" for="demo-contact-input"></label>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="demo-contact-input">Is Active</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input id="esewa_status" class="magic-radio" type="radio" name="nibl_payment_status" value="active" {{ Options::get('nibl_payment_status') == 'active' ? "checked" : "" }}>
                                    <label for="esewa_status" style="color:#000000;">YES</label>
                                    <input id="esewa_status-2" class="magic-radio" type="radio" name="nibl_payment_status" value="inactive" {{ Options::get('nibl_payment_status') == 'inactive' ? "checked" : "" }}>
                                    <label for="esewa_status-2" style="color:#000000;">NO</label>
                                </div>
                                <small class="help-block" style="color: #000000;font-weight: 600;">Enable/Disable NIBL Payment from the system.</small>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
