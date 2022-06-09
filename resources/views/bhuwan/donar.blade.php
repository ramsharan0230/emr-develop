@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Donarv Master</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                <div class="form-row justify-content-between">
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-3">Target Comp</label>
                        <div class="col-sm-4">
                            <select name="" id="" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
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
                                <th>Category</th>
                                <th>QTYy</th>
                                <th>Cost</th>
                                <th>Comp</th>
                                <th>Ref No</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Donarv Master</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="sendTo-tab" data-toggle="tab" href="#sendTo" role="tab" aria-controls="sendTo" aria-selected="true">Sent To</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="receiveTo-tab" data-toggle="tab" href="#receiveTo" role="tab" aria-controls="receiveTo" aria-selected="false">Receive</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-2">
                        <div class="tab-pane fade show active" id="sendTo" role="tabpanel" aria-labelledby="sendTo-tab">
                            <div class="form-row justify-content-between">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-3">Target Comp</label>
                                    <div class="col-sm-4">
                                        <select name="" id="" class="form-control">
                                            <option value="">---select---</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
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
                                            <th>Category</th>
                                            <th>QTYy</th>
                                            <th>Cost</th>
                                            <th>Comp</th>
                                            <th>Ref No</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="button-block">
                                    <button class="btn btn-primary"><i class="ri-check-line"></i> Save</button>
                                    <button class="btn btn-warning"><i class="ri-code-s-slash-line"></i> Export</button>
                                </div>
                                <div class="form-group form-row align-items-center justify-content-end">
                                    <label class="col-sm-2">Total</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="receiveTo" role="tabpanel" aria-labelledby="receiveTo-tab">
                            <div class="res-table">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Particulars</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Category</th>
                                            <th>QTY</th>
                                            <th>Cost</th>
                                            <th>Comp</th>
                                            <th>Ref No</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="button-block">
                                    <button class="btn btn-primary"><i class="ri-check-line"></i> Save</button>
                                    <button class="btn btn-warning"><i class="ri-code-s-slash-line"></i> Export</button>
                                </div>
                                <div class="form-group form-row align-items-center justify-content-end">
                                    <label class="col-sm-2">Total</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
