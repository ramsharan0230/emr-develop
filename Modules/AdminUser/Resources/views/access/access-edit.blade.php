@extends('frontend.layouts.master')
@section('content')

    <section class="cogent-nav">
        {{--navbar--}}
        @include('adminuser::common.nav-tab')
        {{--end navbar--}}
        <div class="patient-profile">
            <div class="container-fluid">

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

                    <div class="col-md-12 mt-3">
                        <div class="panel">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                    Edit Access
                                    <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
                                </h6>
                            </div>

                            <form action="{{ route('admin.user.comp.access.update') }}" method="POST" class="panel-body form-horizontal form-padding">
                                <div class="access-content mt-3" style="border: 1px solid #a5a2a2; height: 500px;">
                                    <div class="col-md-6 mt-2">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_id" value="{{ $compAccess->id }}">

                                        <div class="form-group">
                                            <label class="control-label" for="admin-name">Name <span class="required_color">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-name" name="name" class="form-control" value="{{ $compAccess->name??'' }}" placeholder="Name" readonly>
                                                <small class="help-block text-danger">{{$errors->first('name')}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="admin-name">Description <span class="required_color">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-description" name="description" class="form-control" value="{{ $compAccess->description??'' }}" placeholder="Description">
                                                <small class="help-block text-danger">{{$errors->first('description')}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="admin-name">Map Computer</label>
                                            <div class="col-md-8">
                                                <input type="text" id="admin-description" name="map_comp" class="form-control" value="{{ $compAccess->map_comp??'' }}" placeholder="Map Computer">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="demo-contact-input">Status</label>
                                            <div class="col-md-9">
                                                <div class="radio">
                                                    <input id="status" class="magic-radio" type="radio" name="status" value="active" {{ $compAccess->status == 'active' ? "checked" : "" }}>
                                                    <label for="status" style="color:#000000;">Active</label>
                                                    <input id="status-2" class="magic-radio" type="radio" name="status" value="inactive" {{ $compAccess->status == 'inactive' ? "checked" : "" }}>
                                                    <label for="status-2" style="color:#000000;">Inactive</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label"></label>
                                            <div class="col-md-3">
                                                <input type="submit" class="btn btn-block btn-primary" name="submit" value="UPDATE">
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
