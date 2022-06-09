@extends('frontend.layouts.master')

@section('content')
@include('menu::common.nav-bar')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Purchase Entry</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-row justify-content-between">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="" id="" class="form-control">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="ri-calendar-2-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-grup">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" >
                                <label class="custom-control-label">Purchase Restriction</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" >
                                <label class="custom-control-label">Show all Entry</label>
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
                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-2">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-2">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-2">
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
                    <div class="form-row">
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Total Cost</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" name="" id="" class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="ri-calculator-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Max R price</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">QTY Bonus</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Dist Unit Cost</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Profit %</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Cash Disc</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Carry Cost %</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Curr Sell Price</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Total QTY</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" name="" id="" class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="ri-calculator-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Cash Bonus %</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">Net Unit Cost</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">New Sell Price</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="" id="" class="form-control"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-2">
                            <div class="form-row form-group align-items-center">
                                <label class="col-sm-5">Sub Total</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-row form-group align-items-center">
                                <label class="col-sm-5">Discount</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-row form-group align-items-center">
                                <label class="col-sm-5">Total Tax</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-row form-group align-items-center">
                                <label class="col-sm-5">Total Amt</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-row form-group align-items-center">
                                <label class="col-sm-5">Ref No</label>
                                <div class="col-sm-7">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-warning"><i class="ri-code-s-slash-line h5"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
