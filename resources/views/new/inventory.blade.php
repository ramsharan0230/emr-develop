@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                        CogentEMR
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                 <div class="form-group form-row align-items-center">
                    <label class="col-sm-2">Item Code</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="">
                    </div>
                    <div class="col-sm-1">
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-sync"></i></a>
                        </div>
                        <label class="col-sm-1">Search</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-2">Particulars</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="">
                        </div>
                        <label class="col-sm-1">Rate</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <select class="form-control col-2 ">
                            <option>--Fluid--</option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                        <div class="col-sm-6">
                            <select class="form-control ">
                                <option>---Select---</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-2">Brand Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                             <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                             <button class="btn btn-info"><i class="fa fa-edit"></i> Edit</button>
                             <button class="btn btn-danger">
                                <i class="fa fa-trash"> Delete</i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="table-responsive table-scroll-costing">
                    <table class="table table-bordered table-sm">
                     <tbody></tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>
</div>
</div>
@endsection
