@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Purchase Order
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
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group form-row">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio-1" class="custom-control-input" />
                                    <label class="custom-control-label"> Generic </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="customRadio-1" class="custom-control-input" />
                                    <label class="custom-control-label"> Brand </label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <input type="text" class="form-control col-8" name="" />&nbsp;
                                <button type="" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="form-group form-row">
                            <div class="col-1">
                                <input type="checkbox" name=""/>
                            </div>
                            <div class="col-sm-7">
                                <select class="form-control" value="">
                                    <option value=""></option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group form-row align-items-center">
                            <label for="" >Qty:</label>
                            <div class="col-sm-1">
                              <input type="text" class="form-control" name="" />
                          </div>
                          <label for="" class="col-sm-1">Stock:</label>
                          <div class="col-sm-2">
                              <input type="text" class="form-control" name="" />
                          </div>
                          <label for="" >Old Rate:</label>
                          <div class="col-sm-2">
                              <input type="text" class="form-control" name="" />
                          </div>
                          <label for="">VAT Rate:</label>
                          <div class="col-sm-2">
                              <input type="text" class="form-control" name="" />
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
            <div class="table-responsive table-dispensing">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Vendor</th>
                            <th>Catogery</th>
                            <th>Particulars</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Comp</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="form-group form-row">
                <div class="col-sm-2 text-center">
                    <button class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;Save</button>
                </div>
                <div class="col-sm-2">
                    <div class="form-group form-row">
                        <label class="col-sm-3">VAT:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="0" name="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group form-row">
                        <label class="col-sm-3">Tax:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="0" name="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group form-row">
                        <label class="col-sm-6">Total Amt:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" placeholder="0" name="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group form-row">
                        <label>Total VAT Amt:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="0" name="" />
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
