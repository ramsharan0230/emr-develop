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
                    <div class="d-flex">
                        <a href="#" class="btn btn-primary"> <i class="fa fa-check"></i>&nbsp;Save </a>&nbsp;&nbsp; <a href="#" class="btn btn-primary"> <i class="fa fa-plus"></i>&nbsp;&nbsp;New </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-edit"></i>&nbsp;Update </a>&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary"> <i class="fa fa-times"></i>&nbsp;Close </a>
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
                    <div class="table-responsive table-report">
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
                        <div class="col-sm-6">
                            <div class="custom-control custom-checkbox custom-checkbox-color-checked custom-control-inline">
                                <input type="checkbox" id="" name="" class="custom-control-input bg-primary" />
                                <label class="custom-control-label" for="">Update Computer Patta Number</label>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;&nbsp;नेपालीमा परिवर्तन गर्नुहोस्</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox custom-checkbox-color-checked custom-control-inline">
                            <input type="checkbox" id="" name="" class="custom-control-input bg-primary" />
                            <label class="custom-control-label" for=""> Block Profile </label>
                        </div>
                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                            <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                            <label class="custom-control-label" for="">Upadan</label>
                        </div>
                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                            <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                            <label class="custom-control-label" for="">Barkashi </label>
                        </div>
                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                            <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                            <label class="custom-control-label" for="">Unblock</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-checkbox-color-checked custom-control-inline">
                            <input type="checkbox" id="" name="" class="custom-control-input bg-primary" />
                            <label class="custom-control-label" for="">Birami</label>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Computer/Patta No.:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-lg-2">Working/Retired:</label>
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
                        <label class="col-sm-4 col-lg-3">Name:</label>
                        <div class="col-sm-8 col-lg-9">
                            <input type="text" name="" value="" class="form-control" />
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
                        <label class="col-sm-4 col-lg-3">Date Of Birth:</label>
                        <div class="col-sm-3 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>

                        <div class="col-sm-1 col-lg-1">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                        </div>
                        <label class="col-sm-2 col-lg-2">Age:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Sex:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value="">Male</option>
                                <option value="">Female</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-lg-2">Join Date:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="date" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Regional Hospital:</label>
                        <div class="col-sm-3 col-lg-3">
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
                        <label class="col-sm-4 col-lg-3">Service:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-lg-5">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Rank:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-lg-5">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Unit:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-lg-5">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Zone:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-lg-2">District:</label>
                        <div class="col-sm-2 col-lg-3">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">Minicipality/VDC:</label>
                        <div class="col-sm-4 col-lg-4">
                            <select name="" class="form-control">
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-lg-2">Ward No.:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" class="form-control" name="" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-sm-4 col-lg-3">House No.:</label>
                        <div class="col-sm-4 col-lg-4">
                            <input type="text" class="form-control" name="" />
                        </div>
                        <label class="col-sm-2 col-lg-2">Tell No.:</label>
                        <div class="col-sm-2 col-lg-3">
                            <input type="text" class="form-control" name="" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="#" type="button" class="btn btn-primary" url="" id="">Patient Search</a>&nbsp;
                        <a href="#" type="button" class="btn btn-primary" url="" id=""><i class="fa fa-upload"></i></a>
                    </div>
                    <div class="form-group form-row mt-2">
                        <label class="col-sm-5 col-lg-4">Behalf Family Description:</label>

                        <div class="col-sm-7 col-lg-8 d-flex justify-content-between">
                            <label>Discontinue</label>
                            <p class="box-behalf bg-success text-center col-sm-1 mt-1"></p>
                            <label>Blocked</label>
                            <p class="box-behalf bg-danger text-center col-sm-1 mt-1"></p>
                            <label>Upadan</label>
                            <p class="box-behalf bg-warning text-center col-sm-1 mt-1"></p>
                            <label>Barkashi</label>
                            <p class="box-behalf bg-purple text-center col-sm-1 mt-1"></p>
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
        </div>
    </div>
</div>
@endsection
