@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Donar Master</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-4">Branch</label>
                                <div class="col-sm-8">
                                    <select name="" id="" class="form-control">
                                        <option value="">---select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-3">Reg Date</label>
                                <div class="col-sm-9">
                                    <input type="date" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-4">Search Donar</label>
                                <div class="col-sm-8">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="iq-search-bar p-0">
                                <div class="searchbox full-width">
                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="search..." style="background:none;">
                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group mt-4">
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <div class="col-sm-4">
                                    <label class="">Donar</label>&nbsp;
                                    <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                 </div>
                                <div class="col-sm-8">
                                    <select name="" id="" class="form-control">
                                        <option value="">---Tittle---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-3">Blood Grp</label>
                                <div class="col-sm-9">
                                    <select name="" id="" class="form-control">
                                        <option value="">---select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 pr-0">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-5">RH Type</label>
                                <div class="col-sm-7 p-0">
                                    <select name="" id="" class="form-control">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-4">Gender</label>
                                <div class="col-sm-8">
                                    <select name="" id="" class="form-control">
                                        <option value="">--select--</option>
                                        <option value="">--Male--</option>
                                        <option value="">--Female--</option>
                                        <option value="">--Others--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-2">DOB</label>
                                <div class="col-sm-10">
                                    <select name="" id="" class="form-control">
                                        <option value="">---select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-3">Age</label>
                                <div class="col-sm-4">
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" name="" id="" class="form-control">
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
                    <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="communication-tab" data-toggle="tab" href="#communication" role="tab" aria-controls="communication" aria-selected="true">Communication Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="permenant-tab" data-toggle="tab" href="#permenant" role="tab" aria-controls="permenant" aria-selected="false">Permenant Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="others-tab" data-toggle="tab" href="#others" role="tab" aria-controls="others" aria-selected="false">Others Details</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-2">
                        <div class="tab-pane fade show active" id="communication" role="tabpanel" aria-labelledby="communication-tab">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Country</label>
                                        <div class="col-sm-7">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">State</label>
                                        <div class="col-sm-8">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">City</label>
                                        <div class="col-sm-8">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Place</label>
                                        <div class="col-sm-7">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Street</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Pin</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Mobile</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="permenant" role="tabpanel" aria-labelledby="permenant-tab">
                        <div class="row form-group">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Country</label>
                                        <div class="col-sm-7">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">State</label>
                                        <div class="col-sm-8">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">City</label>
                                        <div class="col-sm-8">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Place</label>
                                        <div class="col-sm-7">
                                           <div class="iq-search-bar p-0">
                                                <div class="searchbox full-width">
                                                    <input type="text" class="text search-input search-input-donar" id="header-search-input" placeholder="" style="background:none;">
                                                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Street</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Pin</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Mobile</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="others" role="tabpanel" aria-labelledby="others-tab">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Type|:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Marital Status:</label>
                                        <div class="col-sm-8">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                <label class="custom-control-label" for=""> Married </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                <label class="custom-control-label" for=""> Unmarried </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-4">Food Type:</label>
                                        <div class="col-sm-8">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                <label class="custom-control-label" for=""> Veg </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                <label class="custom-control-label" for=""> Non-Veg </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-5">Last Donated On</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group form-row mt-2">
                        <label class="col-sm-1">Remarks</label>
                        <div class="col-sm-6">
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary"><i class="ri-save-3-line"></i></button>
                            <button class="btn btn-primary"><i class="fa fa-check"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
