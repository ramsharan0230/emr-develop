@extends('frontend.layouts.master')
@section('content')
<div class="iq-top-navbar second-nav">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
        <!-- <div class="iq-menu-bt align-self-center">
            <div class="wrapper-menu">
                <div class="main-circle"><i class="ri-more-fill"></i></div>
                <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
            </div>
        </div> -->
        {{--<div class="navbar-collapse">
            <ul class="navbar-nav navbar-list">
                <li class="nav-item">
                    <a
                    class="search-toggle iq-waves-effect language-title"
                    href="#"
                    >User <i class="ri-arrow-down-s-line"></i
                        ></a>
                        <div class="iq-sub-dropdown">
                            <a class="iq-sub-card" href="#">Blank Form</a>
                            <a class="iq-sub-card" href="#">Waiting</a>
                            <a class="iq-sub-card" href="#">Search</a>
                            <a class="iq-sub-card" href="#">Last EncID</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a
                        class="search-toggle iq-waves-effect language-title"
                        href="#"
                        >Group <i class="ri-arrow-down-s-line"></i
                            ></a>
                            <div class="iq-sub-dropdown">
                                <a class="iq-sub-card" href="#">Blank Form</a>
                                <a class="iq-sub-card" href="#">Waiting</a>
                                <a class="iq-sub-card" href="#">Search</a>
                                <a class="iq-sub-card" href="#">Last EncID</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a
                            class="search-toggle iq-waves-effect language-title"
                            href="#"
                            >Group Mac <i class="ri-arrow-down-s-line"></i
                                ></a>
                                <div class="iq-sub-dropdown">
                                    <a class="iq-sub-card" href="#">Blank Form</a>
                                    <a class="iq-sub-card" href="#">Waiting</a>
                                    <a class="iq-sub-card" href="#">Search</a>
                                    <a class="iq-sub-card" href="#">Last EncID</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a
                                class="search-toggle iq-waves-effect language-title"
                                href="#"
                                >Mac Request <i class="ri-arrow-down-s-line"></i
                                    ></a>
                                    <div class="iq-sub-dropdown">
                                        <a class="iq-sub-card" href="#">Blank Form</a>
                                        <a class="iq-sub-card" href="#">Waiting</a>
                                        <a class="iq-sub-card" href="#">Search</a>
                                        <a class="iq-sub-card" href="#">Last EncID</a>
                                    </div>
                                </li>
                            </ul>
                        </div>--}}
                    </nav>
                </div>
            </div>
            <!-- TOP Nav Bar END -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Edit Group</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <form action="{{ route('admin.user.groups.update') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="col-md-8 mt-3">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_id" value="{{ $group_details->id }}">
                                        <div class="form-group row">
                                            <label class="control-label col-sm-4 align-self-center mb-0" for="">Group Name <span class="required_color">*:</span></label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $group_details->name }}">
                                                <small class="help-block text-danger">{{$errors->first('name')}}</small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-4 align-self-center mb-0" for="">Computer access:</label>
                                            <div class="col-md-8">
                                                @if(count($computer_access))
                                                @foreach($computer_access as $ca)
                                                <input type="checkbox" name="computer_access[]" value="{{ $ca->id }}" id="{{ $ca->id }}" {{ $group_details->group_computer_access->where('computer_access_id', $ca->id)->first()?"checked":"" }}>
                                                <label for="{{ $ca->id }}" class="mr-2">{{ $ca->name }}</label>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-4 align-self-center mb-0" for="">Status:</label>
                                            <div class="col-md-8">
                                                <div class="radio">
                                                    <input id="status" class="" type="radio" name="status" value="active" {{ $group_details->status == "active" ? "checked" : "" }}>
                                                    <label for="status" class="mr-2">Active</label>
                                                    <input id="status-2" class="" type="radio" name="status" value="inactive" {{ $group_details->status == "inactive" ? "checked" : "" }}>
                                                    <label for="status-2">Inactive</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group mt-4">
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
                </div>
            </div>
            @endsection

