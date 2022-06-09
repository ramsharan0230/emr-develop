@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Purchase Entry
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" placeholder="0" name="" />
                                </div>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" placeholder="0" name="" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <select class="form-control" value="">
                                    <option value=""></option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="form-group form-row">
                                <button class="btn btn-primary col-4 btn-sm">Save</button>
                                <div class="col-sm-8">
                                    <select class="form-control" value="">
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" />
                                    <label class="custom-control-label">Show All Entry</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <input type="text" class="form-control col-sm-4" name="" />
                                <div class="col-sm-8">
                                    <select class="form-control" value="">
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <select class="form-control" value="">
                                    <option value=""></option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group form-row">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio-1" class="custom-control-input" />
                                    <label class="custom-control-label"> Generic </label>
                                </div>&nbsp;
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio-1" class="custom-control-input" />
                                    <label class="custom-control-label"> Brand </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <input type="text" class="form-control" name="" />
                            </div>
                            <div class="form-group form-row">
                                <input type="text" class="form-control col-2" name="" />
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="" />
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                     <div class="col-sm-3">
                        <div class="form-group form-row">
                            <label class="col-sm-6 col-lg-5">Total Cost:</label>
                            <div class="col-sm-6 col-lg-7">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-6 col-lg-5">Profit %:</label>
                            <div class="col-sm-6 col-lg-7">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-6 col-lg-5">Total Qty:</label>
                            <div class="col-sm-6 col-lg-7">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Max R Price:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Cash Disc:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Cash Bonus %:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">QTY Bonus:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Carry Cost:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Net Unit Cost:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Dish Unit Cost:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Curr Sell Price:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">New Sell Price:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="form-group form-row">
                    <div class="col-sm-2">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Sub-Total</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Discount:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Total Tax:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group form-row">
                            <label class="col-sm-8 col-lg-6">Total Amt:</label>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row">
                            <label class="col-sm-4 col-lg-3">Ref No.:</label>
                            <div class="col-sm-8 col-lg-9">
                                <input type="text" class="form-control" placeholder="0" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-primary btn-sm-in"><i class="fa fa-sync"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="table-responsive table-container">
                    <table class="table table-bordered table-hover table-striped ">
                        <tbody></tbody>
                    </table>
                    <div id="bottom_anchor"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
