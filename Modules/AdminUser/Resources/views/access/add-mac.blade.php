@extends('frontend.layouts.master')
@section('content')

    <section class="cogent-nav">
        {{--navbar--}}
        @include('adminuser::common.nav-tab')
        {{--end navbar--}}
        <div class="patient-profile">
            <div class="container">

                <div class="adminMgmtPageContent">
                    @if(Session::get('success_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {{ Session::get('success_message') }}
                        </div>
                    @endif

                    @if(Session::get('success_message_special'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {!! Session::get('success_message_special') !!}
                        </div>
                    @endif

                    @if(Session::get('error_message'))
                        <div class="alert alert-danger containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {{ Session::get('error_message') }}
                        </div>
                    @endif

                    <div class="mt-3">
                        <div class="panel">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                    Add Mac Address
                                    <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                                </h6>
                            </div>

                            <form action="{{ route('admin.user.mac.access.store') }}" method="POST" class="panel-body form-horizontal form-padding">
                                <div class="mac-content mt-3"style="border: 1px solid #a5a2a2; height: 500px;">
                                    <div class="col-md-6 mt-2">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_access_name" value="{{ $comp }}">

                                        <div class="">
                                            <p>This computer's mac is: {{ $getmac??'' }}</p>
                                            <p>This computer's host name is: {{ gethostname() ??'' }}</p>
                                        </div>

                                        <div class="form-group mt-4">
                                            <label class="control-label" for="fldhostmac">Mac Address <span class="required_color">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="fldhostmac" name="fldhostmac" class="form-control" value="{{ old('fldhostmac') }}" placeholder="Mac Address">
                                                <small class="help-block text-danger">{{$errors->first('fldhostmac')}}</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="fldhostname">Host Name </label>
                                            <div class="col-md-8">
                                                <input type="text" id="fldhostname" name="fldhostname" class="form-control" value="{{ old('fldhostmac') }}" placeholder="Host Name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="demo-contact-input">Status</label>
                                            <div class="col-md-9">
                                                <div class="radio">
                                                    <input id="status" class="magic-radio" type="radio" name="status" value="Active" checked>
                                                    <label for="status" style="color:#000000;">Active</label>
                                                    <input id="status-2" class="magic-radio" type="radio" name="status" value="Inactive">
                                                    <label for="status-2" style="color:#000000;">Inactive</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label"></label>
                                            <div class="col-md-4">
                                                <input type="submit" class="btn btn-block btn-primary" name="submit" value="CREATE">
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
    </section>

@stop
