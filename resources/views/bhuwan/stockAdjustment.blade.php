@extends('frontend.layouts.master')

@section('content')
@include('menu::common.nav-bar')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Stock Adjustment</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="from-group form-row align-items-center">
                        <label class="col-sm-1">Reason</label>
                        <div class="col-sm-7">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="customRadio1" class="custom-control-input">
                            <label class="custom-control-label">Generic</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="customRadio1" class="custom-control-input">
                            <label class="custom-control-label">Brand</label>
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
                        <div class="col-sm-1">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                    </div>
                    <div class="res-table">
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Particulars</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Categ</th>
                                    <th>SellPr</th>
                                    <th>CompQTY</th>
                                    <th>CurrQTY</th>
                                    <th>&nbsp;</th>
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
