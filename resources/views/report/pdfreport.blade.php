@extends('frontend.layouts.master')
<style>
    .text-haemo {
        color: #279faf;
    }
    .Haemodialysis-texarea {
        display: none;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Patient Profile
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title d-flex align-items-center">
                        <h4 class="card-title">Id No:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group mb-0">
                        <textarea class="form-control" rows="7"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title d-flex align-items-center">
                        <h4 class="card-title">Patient Refrerred Form:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group mb-0 past-patdiagno">
                        <div class="form-group mb-0">
                            <textarea class="form-control" rows="7"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Haemodialysis
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Started date:</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="date" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Blood Group:</label>
                                <div class="col-sm-6 col-lg-7">
                                    <select name="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-2">Need a radio button of</label>
                                <div class="col-sm-7 col-lg-7">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> Right </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> Left </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="" name="customRadio-1" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> Both </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-2">Schedule:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">3/7</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">2/7</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">2/7</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Irregular</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-2">Vascular Access:</label>
                                <div class="col-sm-10 col-lg-10">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">A-V fistula</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Subclavian</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">femoral</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Intra – jugular</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">AV Graft </label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Perm cath</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-2">Screening Test:</label>
                                <div class="col-sm-8 col-lg-8 form-row">
                                    <label>HBSAG:</label>
                                    <div class="col-sm-2">
                                        <select name="" class="form-control">
                                            <option value="">Positive</option>
                                            <option value="">Negative</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label>HCV: </label>
                                    <div class="col-sm-2">
                                        <select name="" class="form-control">
                                            <option value="">Positive</option>
                                            <option value="">Negative</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <label>HIV: </label>
                                    <div class="col-sm-2">
                                        <select name="" class="form-control">
                                            <option value="">Positive</option>
                                            <option value="">Negative</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-2">Vaccination schedule:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Hepatitis-B</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Pneumococcal</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="" />
                                        <label class="custom-control-label" for="">Influenza</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-row">
                                <label class="col-sm-4 col-lg-2">Plan:</label>
                                <div class="col-sm-8 col-lg-8">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="haemodialysis" />
                                        <label class="custom-control-label" for="haemodialysis">Haemodialysis</label>
                                    </div>
                                    <div class="form-group" id="haemodialysis_texarea" style="display: none;">
                                        <textarea class="form-control" rows="7"></textarea>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="Renal" />
                                        <label class="custom-control-label" for="Renal">Renal Transplantation</label>
                                    </div>
                                    <div class="form-group" id="renal_textarea" style="display: none;">
                                        <textarea class="form-control" rows="7"></textarea>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="CAPD" />
                                        <label class="custom-control-label" for="CAPD">CAPD</label>
                                    </div>
                                    <div class="form-group" id="capd_textarea" style="display: none;">
                                        <textarea class="form-control" rows="7"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title d-flex align-items-center">
                        <h4 class="card-title">Comorbid conditions:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group mb-0">
                        <textarea class="form-control" rows="7"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header p-1 d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Final Diagnosis</h4>
                    </div>
                    <div class="allergy-add">
                        <a href="#" class="iq-bg-secondary mr-1"><i class="ri-add-fill"></i></a>

                        <a href="#" class="iq-bg-primary mr-1" onclick="obstetric.displayModal()"><i class="ri-add-fill"></i></a>
                        <a href="#" class="iq-bg-primary mr-1" data-toggle="modal" data-target="#diagnosis-emergency"><i class="ri-add-fill"></i></a>
                        <a href="#" class="iq-bg-warning mr-1"><i class="ri-information-fill"></i></a>
                        <a href="#" class="iq-bg-danger mr-1" id="deletealdiagno-emergency"><i class="ri-delete-bin-5-fill"></i></a>
                        <!-- <a href="#" class="iq-bg-primary"><i class="ri-add-fill"></i></a>
                <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
                <a href="#" class="iq-bg-warning"><i class="ri-information-fill"></i></a>
                <a href="#" class="iq-bg-danger"><i class="ri-delete-bin-5-fill"></i></a> -->
                    </div>
                </div>
                <div class="iq-card-body">
                    <form action="" class="form-horizontal">
                        <div class="form-group mb-0">
                            <select name="" id="select-multiple-diagno" class="form-control" multiple="">
                                <option value="">No Diagnosis Found</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title d-flex align-items-center">
                        <h4 class="card-title">Past Digonosis</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group mb-0 past-patdiagno">
                        <ul>
                            <li class="list-group-item">No Diagnosis Found</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                        Emergency Hemodialysis and maintenance Hemodialysis
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- Collapse buttons -->
                    <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
                        <div class="col-sm-6">
                            <div class="form-row">
                                <label class="col-sm-7 pl-0">Vascular access:</label>
                                <div class="col-sm-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-row">
                                <label class="col-sm-3">Hour:</label>
                                <div class="col-sm-9">
                                    <input type="date" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="box__collipsble1 pr-4">
                            <label class=""> Attended center</label>
                            <a class="text-haemo" data-toggle="collapse" href="#attended" aria-expanded="false" aria-controls="attended"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-4">
                            <label class=""> Indication</label>
                            <a class="text-haemo" data-toggle="collapse" href="#indication" aria-expanded="false" aria-controls="indication"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <hr />
                    <!-- Collapsible element -->
                    <div class="collapse" id="attended" data-parent="#attended">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="vascular" data-parent="#vascular">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="indication" data-parent="#indication">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="hour" data-parent="#hour">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Type of acccess:
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- Collapse buttons -->
                    <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
                        <div class="col-sm-12">
                            <div class="form-row">
                                <label class="col-sm-4 pl-0">Date of creation</label>
                                <div class="col-sm-5">
                                    <input type="date" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="box__collipsble1 pr-5">
                            <label class="">Institute/ Doctor </label>
                            <a class="text-haemo" data-toggle="collapse" href="#doctor" aria-expanded="false" aria-controls="doctor"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1">
                            <label class="">Cause</label>
                            <a class="text-haemo" data-toggle="collapse" href="#cause" aria-expanded="false" aria-controls="cause"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <hr />
                    <!-- Collapsible element -->
                    <div class="collapse" id="creation" data-parent="#creation">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="doctor" data-parent="#doctor">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="cause" data-parent="#cause">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Body Composition Monitoring (BCM):
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- Collapse buttons -->
                    <div class="form-group d-flex justify-content-between" style="padding: 0px 0px 0px 5px;">
                        <div class="box__collipsble1 pr-3">
                            <label class="">Weight</label>
                            <a class="text-haemo" data-toggle="collapse" href="#weight" aria-expanded="false" aria-controls="weight"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Over hydration(OH) </label>
                            <a class="text-haemo" data-toggle="collapse" href="#oh" aria-expanded="false" aria-controls="oh"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Dry Wt.</label>
                            <a class="text-haemo" data-toggle="collapse" href="#dry" aria-expanded="false" aria-controls="dry"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1">
                            <label class="">Remarks</label>
                            <a class="text-haemo" data-toggle="collapse" href="#remarks" aria-expanded="false" aria-controls="remarks"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <hr />
                    <!-- Collapsible element -->
                    <div class="collapse" id="weight" data-parent="#weight">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="oh" data-parent="#oh">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="dry" data-parent="#dry">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="remarks" data-parent="#remarks">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Haemodialysis Treatment Record:
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- Collapse buttons -->
                    <div class="form-group d-flex justify-content-between" style="padding: 0px 0px 0px 5px;">
                        <div class="box__collipsble1 pr-3">
                            <label class="">Dialysis No.</label>
                            <a class="text-haemo" data-toggle="collapse" href="#dialysis" aria-expanded="false" aria-controls="dialysis"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Ideal Weight </label>
                            <a class="text-haemo" data-toggle="collapse" href="#ideal" aria-expanded="false" aria-controls="ideal"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Weight Gain</label>
                            <a class="text-haemo" data-toggle="collapse" href="#weightgain" aria-expanded="false" aria-controls="weightgain"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1">
                            <label class=""> Loss</label>
                            <a class="text-haemo" data-toggle="collapse" href="#loss" aria-expanded="false" aria-controls="loss"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <hr />
                    <!-- Collapsible element -->
                    <div class="collapse" id="dialysis" data-parent="#dialysis">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="ideal" data-parent="#ideal">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="weightgain" data-parent="#weightgain">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="loss" data-parent="#loss">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
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
                            Blood Analysis:
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <!-- Collapse buttons -->
                    <div class="form-group d-flex" style="padding: 0px 0px 0px 5px;">
                        <div class="box__collipsble1 pr-4">
                            <label class="">Pre dialysis</label>
                            <a class="text-haemo" data-toggle="collapse" href="#predialysis" aria-expanded="false" aria-controls="predialysis"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Post dialysis </label>
                            <a class="text-haemo" data-toggle="collapse" href="#postdialysis" aria-expanded="false" aria-controls="postdialysis"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class="">Other investigatipons</label>
                            <a class="text-haemo" data-toggle="collapse" href="#investigation" aria-expanded="false" aria-controls="investigation"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1 pr-3">
                            <label class=""> Doctor examinations</label>
                            <a class="text-haemo" data-toggle="collapse" href="#examination" aria-expanded="false" aria-controls="examination"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                        <div class="box__collipsble1">
                            <label class="">Notes</label>
                            <a class="text-haemo" data-toggle="collapse" href="#note" aria-expanded="false" aria-controls="note"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <hr />
                    <!-- Collapsible element -->
                    <div class="collapse" id="predialysis" data-parent="#predialysis">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="postdialysis" data-parent="#postdialysis">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="investigation" data-parent="#investigation">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="examination" data-parent="#examination">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="collapse" id="note" data-parent="#note">
                        <div class="form-group">
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            HD session
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Pre dialysis</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Weight</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Temperature</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Pulse</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Rest BP</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Stand BP</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Dry weight
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-8 col-lg-5">Post dialysis</label>
                                <div class="col-sm-4 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Weight</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Temperature</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Pulse</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Rest BP</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Stand BP</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
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
                        <h3 class="card-title"></h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-7">VA</label>
                                <div class="col-sm-5 col-lg-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Difference</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Heparin</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-7">Circulation</label>
                                <div class="col-sm-5 col-lg-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Bolus</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Continuous</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-10 col-lg-7">UF Goal/ Fluid removed</label>
                                <div class="col-sm-2 col-lg-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Dialyzer Type</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-5">Net weight loss</label>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-7">Blood transfusion</label>
                                <div class="col-sm-5 col-lg-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">Next HD</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="date" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-6 col-lg-5">PCV/Hb</label>
                                <div class="col-sm-6 col-lg-7">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-7 col-lg-7">Complications</label>
                                <div class="col-sm-5 col-lg-5">
                                    <input type="text" name="" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#haemodialysis").on("click", function () {
        if ($("#haemodialysis_texarea").is(":hidden")) {
            $("#haemodialysis_texarea").show();
        } else {
            $("#haemodialysis_texarea").hide();
        }
    });
    $("#Renal").on("click", function () {
        if ($("#this").is(":checked")) {
            $("#renal_textarea").show();
        } else {
            $("#renal_textarea").hide();
        }
    });
    $("#CAPD").on("click", function () {
        if ($("#capd_textarea").is(":hidden")) {
            $("#capd_textarea").show();
        } else {
            $("#capd_textarea").hide();
        }
    });
</script>
@endsection
