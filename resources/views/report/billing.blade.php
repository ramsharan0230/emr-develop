@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Billing report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">Form:</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" />
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">To:</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" />
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-3">
                            <div class="form-group form-row">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="" name="customRadio" class="custom-control-input" />
                                    <label class="custom-control-label" for=""> ENCID</label>
                                </div>
                                &nbsp;
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="" name="customRadio" class="custom-control-input" />
                                    <label class="custom-control-label" for="">User</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <input type="text" name="" class="form-control" id="" value="" />
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-3">
                            <div class="form-group form-row">
                                <div class="col-sm-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="" name="customRadio" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> Invoice</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <select name="" id="" class="form-control">
                                        <option value="%">%</option>
                                        <option value="Male"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <input type="text" name="" class="form-control" id="" value="" />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-3">
                            <div class="form-group form-row">
                                <select name="" id="" class="form-control">
                                    <option value="%">%</option>
                                    <option value="Male"></option>
                                </select>
                            </div>
                            <div class="form-group form-row">
                                <select name="" id="" class="form-control">
                                    <option value="%">%</option>
                                    <option value="Male"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <div class="col-sm-9">
                                     <select name="" id="" class="form-control">
                                    <option value="%">All Types</option>
                                    <option value="Male"></option>
                                </select>
                                </div>
                                <div class="col-sm-3">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="" name="customcheckbox" class="custom-control-input" />
                                        <label class="custom-control-label" for="">QTY</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-sm-9">
                                    <select name="" id="" class="form-control">
                                    <option value="%">%</option>
                                    <option value="Male"></option>
                                </select>
                                </div>
                                <div class="col-sm-3">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="" name="customcheckbox" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> AMT</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="col-sm-12">
                            <div class="d-flex justify-content-center">
                            <a href="#" type="button" class="btn btn-primary rounded-pill"><i class="fa fa-check"></i>&nbsp;
                            Save</a>&nbsp;
                             <a href="#" type="button" class="btn btn-primary rounded-pill"><i class="fa fa-code"></i>&nbsp;
                            Export</a>
                        </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                      <li class="nav-item">
                         <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                      </li>
                      <li class="nav-item">
                         <a class="nav-link" id="chart-tab-two" data-toggle="tab" href="#chart" role="tab" aria-controls="profile" aria-selected="false">Chart:QTY</a>
                      </li>
                      <li class="nav-item">
                         <a class="nav-link" id="amt-tab-two" data-toggle="tab" href="#amt-two" role="tab" aria-controls="contact" aria-selected="false">Chart:AMT</a>
                      </li>
                   </ul>
                   <div class="tab-content" id="myTabContent-1">
                              <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table">
                                        <table class="table table-striped table-hover table-bordered ">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Invoice</th>
                                                    <th>EnciD</th>
                                                    <th>Name</th>
                                                    <th>OldDepo</th>
                                                    <th>Total Amt</th>
                                                    <th>Disc Amt</th>
                                                    <th>Net Total</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                              </div>
                              <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                              </div>
                              <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                              </div>
                           </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
