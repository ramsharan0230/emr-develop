@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Discount Mode
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">Discount label</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">Discount mode</label>
                                <div class="col-md-6">
                                    <select name="" id="" class="form-control">
                                        <option value="0">one</option>
                                        <option value="1">two</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">Disc Atm/Year</label>
                                <div class="col-md-6">
                                    <input type="text" value="0" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">Fix Disc %</label>
                                <div class="col-md-6">
                                    <input type="text" value="0" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">Credit AMT</label>
                                <div class="col-md-6">
                                    <input type="text" value="0" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-6 control-label">No Discount</label>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary full-width" data-toggle="modal" data-target=".bd-example-modal-lg">View List</button>
                                </div>
                                <div class="modal fade bd-example-modal-lg show" tabindex="-1" role="dialog" aria-modal="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">No Discount List</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <select class="form-control">
                                                                <option></option>
                                                            </select>
                                                        </div>

                                                        <div class="discounttable">
                                                            <table class="table table-striped table-hover table-vcenter">
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <button class="btn btn-primary mt-top"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="discounttable2">
                                                            <table class="table table-striped table-hover table-vcenter">
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="" /> &nbsp; ECG</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-row">
                                <label class="col-md-4 control-label">Year Start</label>
                                <div class="col-md-8 padding-none">
                                    <input type="date" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="4">Curr Disc %</label>&nbsp;
                                <div class="col-md-8">
                                    <button type="button" class="btn btn-primary full-width" data-toggle="modal" data-target=".bd-curr-modal-lg">View Items</button>
                                </div>
                            </div>
                            <div class="modal fade bd-curr-modal-lg show" tabindex="-1" role="dialog" aria-modal="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Discount Catogery</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group form-row col-12">
                                                    <label class="col-md-2 control-label">Discount label</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Laboratory</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Radiology</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Procedures</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Registration</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Equipment</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Gen Services</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Others</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <input type="number" class="form-control" placeholder="Exception" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Medical</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Surgical</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <label class="col-md-5">Extra Item</label>
                                                        <div class="col-md-6 padding-none">
                                                            <input type="number" class="form-control" placeholder="0" />
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label>%</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <button type="button" class="btn btn-primary"><i class="fa fa-edit"></i> update</button>
                                                    </div>
                                                </div>
                                                <div class="form-group form-row col-12">
                                                    <div class="col-sm-2">
                                                        <select name="" id="" class="form-control">
                                                            <option value="0">one</option>
                                                            <option value="1">two</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select name="" id="" class="form-control">
                                                            <option value="0">one</option>
                                                            <option value="1">two</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" class="form-control" placeholder="0" />
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="table-responsive table-scroll-fiscal mt-3">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Catogery</th>
                                                                    <th>Item Name</th>
                                                                    <th>Disc %</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group text-center">
                                <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                                <button class="btn btn-info"><i class="fa fa-edit"></i> Update</button>
                                <button class="btn btn-warning">
                                    <i class="fa fa-code"></i>
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
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>DiscLabel</th>
                                    <th>DiscMode</th>
                                    <th>StartDAte</th>
                                    <th>DiscATM/Year</th>
                                    <th>CreditAmt</th>
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
