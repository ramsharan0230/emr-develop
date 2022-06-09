@extends('frontend.layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Employee Lists</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-row">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label">Code</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 mb-2">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label">Name</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 mb-2">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="customRadio" class="custom-control-input">
                                        <label class="custom-control-label">SurName</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 mb-2">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                            <div class="res-table mt-3">
                                <table class="table table-hover table-bordered table-sth5 riped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Code</th>
                                            <th>Category</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Destination</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-book-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Address</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="0">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Gender</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="0">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Contact No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Citizenship</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Religion</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Rank</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Posting</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Join Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                        <div class="col-sm-1">
                                            <i class="h5 ri-calendar-2-fill"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Designation</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Patient No.</label>
                                        <div class="col-sm-7">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-download-cloud-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-ph5 rice-tag-3-fill"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Category</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Surname</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">District</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">DOB</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="ri-calendar-2-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Email</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Blood Group</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Marks</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Unit</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="h5 ri-add-box-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">UserID</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="ri-refresh-line"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">End Date</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#"><i class="ri-calendar-2-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Status</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Discount</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">---select---</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea name="" id="" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div>
                                        <button class="btn btn-primary"><i class="ri-add-line"></i> Save</button>
                                        <button class="btn btn-warning"><i class="ri-edit-2-fill"></i> Update</button>
                                        <button class="btn btn-danger"><i class="ri-delete-bin-5-fill"></i> Delete</button>
                                        <button class="btn btn-warning"><i class="ri-edit-2-fill"></i> Edit Discount</button>
                                        <button class="btn btn-danger"><i class="ri-close-line"></i> Blank</button>
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
