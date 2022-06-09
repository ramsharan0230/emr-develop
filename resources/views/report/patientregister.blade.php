@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Treatment Book Center
                        </h3>
                    </div>
                    <div class="d-flex">
                        <a href="#" class="btn btn-primary"> <i class="fa fa-check"></i>&nbsp;Save </a>&nbsp;&nbsp; <a href="#" class="btn btn-primary"> <i class="fa fa-plus"></i>&nbsp;&nbsp;New </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-edit"></i>&nbsp;Edit </a>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">S.N:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>1234567</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Code No.:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" class="form-control" name="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Status:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>Completed</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Patta No.:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" class="form-control" name="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="" />
                                    <label class="custom-control-label" for="">Ratio Card</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Behalf
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Name:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>Mr Sanjook Rai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Gender:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Service:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>asdfgh</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Unit:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>1234567</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Rank:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>1234567</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Description:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>asdfghhj</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Patient Information
                        </h3>
                    </div>
                     <div class="d-flex">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;&nbsp;नेपालीमा परिवर्तन गर्नुहोस्</button>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5 col-lg-4">Patient Type::</label>
                                <div class="col-sm-6 col-lg-5">
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-6">CCN/BRN::</label>
                                <div class="col-sm-5 col-lg-6">
                                    <input type="text" class="form-control" name="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5 col-lg-4">CCN/BRN Date:</label>
                                <div class="col-sm-5 col-lg-6">
                                    <input type="date" class="form-control" name="" />
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-6">Regional Hospital:</label>
                                <div class="col-sm-5 col-lg-6">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Relation:</label>
                                <div class="col-sm-4 col-lg-5">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-sm-4 col-lg-4">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5 col-lg-4">District:</label>
                                <div class="col-sm-7 col-lg-8">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-6">Total Despensed Amt:</label>
                                <div class="col-sm-5 col-lg-6">
                                    <input type="text" class="form-control" name="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-2 col-lg-2">Name:</label>
                        <div class="col-sm-3 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-3 col-lg-2">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-2 col-lg-2">Date Of Birth:</label>
                        <div class="col-sm-2 col-lg-2">
                            <input type="date" name="" value="" class="form-control" />
                        </div>

                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-lg-1">Age:&nbsp;</label>&nbsp;
                        <div class="col-sm-1 col-lg-1">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        &nbsp;&nbsp; <label class="col-lg-1">Sex:&nbsp;</label>&nbsp;
                        <div class="col-sm-2 col-lg-2">
                            <select name="" class="form-control">
                                <option value="">Male</option>
                                <option value="">Female</option>
                            </select>
                        </div>
                        <label class="pl-2">Sticker No:&nbsp;11111</label>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-2 col-lg-2">End Date:</label>
                        <div class="col-sm-2 col-lg-2">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="">Blood Group:</label>
                        <div class="col-sm-2 col-lg-1">
                            <select name="" class="form-control">
                                <option value="">A+</option>
                                <option value="">O+</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-lg-1">Mobile No.:</label>
                        <div class="col-sm-2 col-lg-2">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Address
                        </h3>
                    </div>
                </div>
                 <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Province:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>Ms Sanjook Rai</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">District:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-4">Telephone No.:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <label>asdfgh</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Mu/VDC:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>1234567</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-3">Ward No.:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <label>1234567</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-4">House No.:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <label>asdfghhj</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class=" border-top">
                                <div class="form-group form-row mt-2">
                                    <label class="col-sm-4 col-lg-3">Behalf Family Description:</label>

                                    <div class="col-sm-8 col-lg-9 d-flex justify-content-between">
                                        <label>Discontinue</label>
                                        <p class="box-behalf bg-success text-center col-sm-1 mt-1"></p>
                                        <label>Death</label>
                                        <p class="box-behalf bg-danger text-center col-sm-1 mt-1"></p>
                                        <label>Upadan</label>
                                        <p class="box-behalf bg-warning text-center col-sm-1 mt-1"></p>
                                        <label>Barkashi</label>
                                        <p class="box-behalf bg-blue text-center col-sm-1 mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                     <div class="res-table table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>Code No.</th>
                                  <th>Reg. Date</th>
                                  <th>Relation</th>
                                  <th>DOB</th>
                                  <th>Blood</th>
                                  <th>Patta</th>
                                  <th>Status</th>
                              </tr>
                          </thead>
                        </table>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="d-flex justify-content-around">
                        <a href="#" onclick="" class="btn btn-primary">Delete</a>
                        <a href="#" onclick="" class="btn btn-primary">Update</a>
                        <a href="#" onclick="" class="btn btn-primary">Bill Entry</a>
                        <a href="#" onclick="" class="btn btn-primary">Patient Search</a>
                         <a href="#" onclick="" class="btn btn-primary">Photo Upload</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
