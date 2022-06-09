@extends('frontend.layouts.master')

@section('content')
@include('menu::common.nav-bar')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Stock Transfer</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-row justify-content-between">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-1">Target</label>
                            <a href="#" class="col-sm-1"><i class="ri-add-line"></i></a>
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control">
                                    <option value="">---select---</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <a href="#"><i class="ri-refresh-line"></i></a>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="" id="" class="form-control">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="ri-calendar-2-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="customRadio1" class="custom-control-input">
                                <label class="custom-control-label" >Generic</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="customRadio1" class="custom-control-input">
                                <label class="custom-control-label" >Brand</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-1">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="text" name="" id="" class="form-control">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                    </div>
                    <div class="res-table">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Stock No</th>
                                    <th>Category</th>
                                    <th>Paticulars</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>QTY</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
