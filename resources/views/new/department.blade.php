@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-7">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Department
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <label class="col-2">Catogery</label>
                        <div class="col-sm-9">
                            <input type="text" name="" class="form-control" />
                        </div>
                        <div class="col-sm-1">
                            <a href="#" class="btn btn-primary" type="button"><i class="fas fa-sync"></i></a>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Auto Billing</label>
                        <div class="col-sm-10">
                            <input type="text" name="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Dept Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Room</label>
                        <div class="col-sm-10">
                            <input type="text" name="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <label class="col-2">Incharge</label>
                        <div class="col-sm-10">
                            <input type="text" name="" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group float-right mt-2">
                        <button class="btn btn-info"><i class="fas fa-plus"></i> Bed No.</button>
                        <button class="btn btn-success"><i class="fa fa-pencil"></i> Update</button>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                        <button class="btn btn-warning"><i class="fa fa-code"></i>Export</button>
                    </div>
                    <table class="table table-bordered mt-3 table-scroll-consult">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Department</th>
                                <th>Catogery</th>
                                <th>Room No.</th>
                                <th>Auto Billing</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Add Bed
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <input type="text" name="" class="form-control" />
                    </div>
                    <div class="form-group form-row">
                        <input type="text" name="" class="form-control" />
                    </div>
                    <div class="form-group mt-3 text-right">
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>&nbsp;
                        <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    <div class="form-group mt-3">
                        <div class="departtable form-row">
                            <table class="table table-hovered table-striped">
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
