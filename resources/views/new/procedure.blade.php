@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-5">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                 <div class="form-group form-row align-items-center">
                    <label class="col-sm-3">Item Code</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="">
                    </div>
                    <div class="col-sm-3">
                        <a href="#" class="btn btn-primary">
                            <i class="ri-add-fill"></i> Add</a>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-3">Price</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="" >
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-3">Bill Mode</label>
                        <div class="col-sm-9">
                            <select class="form-control ">
                                <option>---Select---</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-3">Status</label>
                        <div class="col-sm-7">
                            <select class="form-control ">
                                <option>---Select---</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" class="btn btn-primary">
                                <i class="fa fa-sync"></i></a>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Type</label>
                            <div class="col-sm-9">
                                <select class="form-control ">
                                    <option>---Select---</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Section</label>
                            <div class="col-sm-9">
                                <select class="form-control ">
                                    <option>---Select---</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="" >
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Item Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="" >
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                          <input type="text" class="form-control" name="" >
                      </div>
                      <div class="form-group text-right">
                         <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                         <button class="btn btn-info"><i class="fa fa-edit"></i> Update</button>
                         <button class="btn btn-warning">
                            <i class="fa fa-code"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-scroll-costing">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Paticuars</th>
                                    <th>Fee</th>
                                    <th>Target</th>
                                    <th>bill Mode</th>
                                    <th>Unit</th>
                                    <th>Low/High</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="form-group form-row mt-3">
                        <label class="col-md-2">CSV File:</label>
                        <div class="col-md-4">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;import</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
