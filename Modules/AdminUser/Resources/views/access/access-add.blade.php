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

                    <div class="mt-1">
                        <div class="panel">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                    Create New Access
                                    <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                                </h6>
                            </div>

                            <form action="{{ route('admin.user.comp.access.store') }}" method="POST" class="panel-body form-horizontal form-padding">
                                <div class="accessadd-content mt-1">
                                    <div class="mt-2">
                                        {{ csrf_field() }}

                                        <div class="form-group form-row align-items-center">
                                            <label class="control-label col-md-2" for="admin-name">Name <span class="required_color">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Name">
                                                <small class="help-block text-danger">{{$errors->first('name')}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label class="control-label col-md-2" for="admin-name">Description <span class="required_color">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-description" name="description" class="form-control" value="{{ old('description') }}" placeholder="Description">
                                                <small class="help-block text-danger">{{$errors->first('description')}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label class="control-label col-md-2" for="admin-name">Map Computer</label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-description" name="map_comp" class="form-control" value="{{ old('map_comp') }}" placeholder="Map Computer">
                                            </div>
                                        </div>

                                        <div class="form-group form-row align-items-center">
                                            <label class="control-label col-md-2" for="demo-contact-input">Status</label>
                                            <div class="col-md-9">
                                                <div class="radio">
                                                    <input id="status" class="magic-radio" type="radio" name="status" value="active" checked>
                                                    <label for="status" style="color:#000000;">Active</label>
                                                    <input id="status-2" class="magic-radio" type="radio" name="status" value="inactive">
                                                    <label for="status-2" style="color:#000000;">Inactive</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group form-row align-items-center mt-4">
                                            <label class=" col-md-2 control-label"></label>
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
