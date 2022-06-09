@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Edit Hospital Department</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(Session::get('success_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('success_message') }}
                        </div>
                    @endif

                    @if(Session::get('error_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('error_message') }}
                        </div>
                    @endif
                    <form action="{{ route('hospital.department.update',$department_id) }}" method="POST" id="edit-department-form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Name:</label>
                            <div class="col-sm-4">
                                <input type="text" id="department_name" name="department_name" class="form-control" value="{{ $department_data->name }}" placeholder="Enter Department Name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Parent Department:</label>
                            <div class="col-sm-4">
                                <select name="parent_department" id="parent_department" class="form-control">
                                    <option value="">--Root Department--</option>
                                    @foreach($parent_departments as $parent_department)
                                        <option @if($department_data->parent_department_id == $parent_department->id) selected @endif value="{{ $parent_department->id }}">{{ $parent_department->name }} ( {{ isset($parent_department->branchData) ? $parent_department->branchData->name : "Main Branch" }} )</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Branch:</label>
                            <div class="col-sm-4">
                                <select name="branch_id" id="branch_id" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach($branches as $branch)
                                        <option @if($department_data->branch_id == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Comp Id:</label>
                            <div class="col-sm-4">
{{--                                <input type="text" id="company_id" name="company_id" class="form-control" value="{{ $department_data->fldcomp }}" placeholder="Enter Company Id">--}}
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select Comp</option>
                                    @for($i = 1; $i<=200; $i++)
                                        <option value="comp{{ sprintf("%02d", $i) }}" {{ 'comp'. sprintf("%02d", $i) === $department_data->fldcomp ? "selected" : "" }}>comp{{ sprintf("%02d", $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Status:</label>
                            <div class="col-sm-4">
                                <select name="status" id="status" class="form-control">
                                    <option @if($department_data->status == "active") selected @endif value="active">Active</option>
                                    <option @if($department_data->status == "inactive") selected @endif value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('hospital.department') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <style>
        .error {
            color: red;
            font-size: 10px;
        }
    </style>
@endpush
