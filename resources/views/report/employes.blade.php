@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Behalf Profile
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <div class="col-sm-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                <label class="custom-control-label" for=""> Code </label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-sm-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                <label class="custom-control-label" for=""> Name </label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-sm-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                <label class="custom-control-label" for=""> Surname </label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="table-responsive table-report" style="height: 815px; min-height: 815px;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Code/Patta No.:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-lg-2">Patient Type:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Name[In English]:</label>
                        <div class="col-sm-3 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-3 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Rank:</label>
                        <div class="col-sm-3 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>

                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-2 col-lg-2">Gender:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Unit:</label>
                        <div class="col-sm-7 col-lg-8">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Address:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-lg-2">District:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Contact No:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-lg-2">Blood Grp:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Citizenship:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-lg-2">Email:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Religion:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">User ID:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Service:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">Marks:</label>
                        <div class="col-sm-3 col-lg-4">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Join DAte:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">DOB:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Patient Status:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">End Date:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Patient No:</label>
                        <div class="col-sm-1 col-lg-2">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-link" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">Status:</label>
                        <div class="col-sm-3 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">OPD No:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-3 col-lg-2">Discount:</label>
                        <div class="col-sm-3 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-3 col-lg-2">Remarks:</label>
                        <div class="col-sm-9 col-lg-10">
                            <textarea class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="form-group">
                        <textarea class="form-control" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                     <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-primary"> <i class="fa fa-check"></i>&nbsp;Save </a>&nbsp;&nbsp; <a href="#" class="btn btn-primary"> <i class="fa fa-plus"></i>&nbsp;&nbsp;New </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-edit"></i>&nbsp;Update </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-times"></i>&nbsp;Delete </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-edit"></i>&nbsp;Edit Discount </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-times"></i>&nbsp;Clear</a>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
