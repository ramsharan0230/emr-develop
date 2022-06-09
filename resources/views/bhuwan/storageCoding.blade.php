@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Storage Coding</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio1" class="custom-control-input">
                                    <label class="custom-control-label">Medicines</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio1" class="custom-control-input">
                                    <label class="custom-control-label">Surgicals</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio1" class="custom-control-input">
                                    <label class="custom-control-label">Extra Items</label>
                                </div>
                                <button class="btn btn-primary"><i class="ri-refresh-line"></i> Refresh</button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row align-items-center">
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-warning"><i class="ri-edit-2-fill"></i> Edit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="res-table">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>StockID</th>
                                            <th>Particulars</th>
                                            <th>Batch</th>
                                            <th>Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
