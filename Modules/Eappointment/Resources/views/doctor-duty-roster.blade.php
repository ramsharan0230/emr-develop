@extends('frontend.layouts.master') @section('content')
<link rel="stylesheet" href="{{ asset('css/jquery.timepicker.min.css') }}"><style>
    .table td {
        padding: 10px 7px;
        border-top: unset;
    }

    .modal.fade {
        background: rgba(0,0,0,0.25);
    }

</style>

<div class="container-fluid">
   <div class="row">
       <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="" style="background-color: unset;" data-toggle="tab" href="#addroster"  onClick="window.location.reload();" role="tab" aria-controls="Add Roster" aria-selected="true">Add Roster</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link get-ddr" id="get-ddr" style="background-color: unset;" data-toggle="tab" href="#manageroster" role="tab" aria-controls="Manage Roster" aria-selected="false">Manage Roster</a>
                </li>
            </ul>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="addroster" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form action="">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="myDIV">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">
                                        Doctor Information
                                    </h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="d-flex flex-wrap">
                                    <div class="col-sm-4 col-md-4 col-lg-2">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-sm-12">Form Date</label>
                                            <div class="col-sm-12">
                                                <input type="Date" name="fromDate" id=fromDate class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-2">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-sm-12">To Date</label>
                                            <div class="col-sm-12">
                                                <input type="Date" name="toDate" id="toDate"  class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-lg-12">Specailization</label>
                                            <div class="col-lg-12">
                                                <select class="form-control select2" name="appointmentServiceTypeCode" id="ddr-specialization">
                                                    <option value="">please select specialization</option>
                                                    @foreach($specializations as $s)
                                                        <option value="{{$s->value}}">{{$s->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-md-12">Doctor</label>
                                            <div class="col-lg-12">
                                                <select class="form-control select2" name="specializationId" id="ddr-doctor">
                                                    <option value="">All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <div class="col-lg-12">
                                                <label>Duration in minutes</label>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="number" id="ddr-duration" name="appointmentNumber"  class="form-control" value="1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <div class="col-lg-12">
                                                <label>Status</label>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="d-flex flex-row">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="ddr-active" value="" name="" checked="checked" class="custom-control-input">
                                                        <label class="custom-control-label" for="ddr-active"> Active</label>
                                                    </div>
                                                    <!-- <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                        <label class="custom-control-label" for=""> Inactive</label>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 text-right align-items-end">
                                        <div class="">
                                            <button class="btn btn-link btn-action"> <i class="fa fa-check"></i>&nbsp;Check Availability</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">
                                        Doctor Availability
                                    </h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <table class="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">
                                                <span>Days</span>
                                            </th>
                                            <th>
                                                <div>
                                                    <input type="checkbox" name="all-dayoff" class="all-dayoff">
                                                    <span>Day off</span>
                                                </div>
                                            </th>
                                            <th>
                                                <span>Start Time</span>
                                            </th>
                                            <th>
                                                <span>End Time</span>
                                            </th>
                                            <th>
                                                <span>Time Slot</span>
                                            </th>
                                            <th>
                                                <span>Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Sun</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="sunday-dayoff dayoff" data-dayoff="sunday" name="sunday-dayoff" data-dayindex="0">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 sunday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control sunday-starttime starttime">
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 sunday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control sunday-endtime endtime">
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary sunday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('sunday',0)" ><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone sunday-clone all-clone" data-day="sunday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Mon</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="monday-dayoff dayoff" data-dayoff="monday" name="monday-dayoff" data-dayindex="1">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 monday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control monday-starttime starttime">
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 monday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control monday-endtime endtime">
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary monday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('monday',1)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone monday-clone all-clone" data-day="monday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Tue</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="tuesday-dayoff dayoff" data-dayoff="tuesday" name="tuesday-dayoff" data-dayindex="2">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 tuesday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control tuesday-starttime starttime">
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 tuesday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control tuesday-endtime endtime">
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary tuesday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('tuesday',2)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone tuesday-clone all-clone" data-day="tuesday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Wed</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="wednesday-dayoff dayoff" data-dayoff="wednesday" name="wednesday-dayoff" data-dayindex="3">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 wednesday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control wednesday-starttime starttime">
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 wednesday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control wednesday-endtime endtime">

                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary wednesday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('wednesday',3)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone wednesday-clone all-clone" data-day="wednesday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Thu</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="thursday-dayoff dayoff" data-dayoff="thursday" name="thursday-dayoff" data-dayindex="4">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 thursday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control thursday-starttime starttime">

                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 thursday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control thursday-endtime endtime">

                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary thursday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('thursday',4)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone thursday-clone all-clone" data-day="thursday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Fri</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="friday-dayoff dayoff"  data-dayoff="friday" name="friday-dayoff" data-dayindex="5">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 friday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control friday-starttime starttime">

                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 friday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control friday-endtime endtime">
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary friday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('friday',5)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone friday-clone all-clone" data-day="friday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Sat</td>
                                            <td>
                                                <div>
                                                    <input type="checkbox" class="saturday-dayoff dayoff" data-dayoff="saturday" name="saturday-dayoff" data-dayindex="6">
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 saturday-starttime starttime" name="starttime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control saturday-starttime starttime">
                                            </td>
                                            <td>
                                                <!-- <select class="form-control select2 saturday-endtime endtime" name="endtime" placeholder="00:00">
                                                    <option value="12:45 PM">12:45 PM</option>
                                                    <option value="12:50 PM">12:50 PM</option>
                                                    <option value="12:55 PM">12:55 PM</option>
                                                    <option value="1:00 PM">1:00 PM</option>
                                                    <option value="1:05 PM">1:05 PM</option>
                                                    <option value="1:10 PM">1:10 PM</option>
                                                </select> -->
                                                <input class="timepickers form-control saturday-endtime endtime">
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-primary saturday-setmode all-setmode" data-toggle="modal" data-target="#setmodemodal" onclick="getTime('saturday',6)"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-clone saturday-clone all-clone" data-day="saturday"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!-- <tfoot>
                                        <tr class="border-top">
                                            <td>
                                                <div class="checkbox pt-2">
                                                    <input id="select-all" class="magic-checkbox" type="checkbox" data-toggle="modal" data-target="#overridemodal">
                                                    <label for="select-all">Override (optional)</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot> -->
                                </table>
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-action" id="add-ddr" type="button"><i class="fa fa-save"></i>&nbsp;Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade " id="manageroster" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="myDIV">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Roster Details
                                </h4>
                            </div>
                            <div>
                                <button onclick="" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="myDIV">
                        <div class="iq-card-body">
                            <form action="" id="">
                                <div class="d-flex flex-wrap">
                                    <div class="col-sm-4 col-md-4 col-lg-2">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-sm-12">Form Date</label>
                                            <div class="col-sm-12">
                                                <input type="Date" name="fromDate" id="from_date" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-2">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-sm-12">To Date</label>
                                            <div class="col-sm-12">
                                                <input type="Date" name="toDate" id="to_date"  class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-lg-12">Specailization</label>
                                            <div class="col-lg-12">
                                            <select class="form-control select2" name="appointmentServiceTypeCode" id="ddr-specialization-manage">
                                                    <option value="">please select specialization</option>
                                                    @foreach($specializations as $s)
                                                        <option value="{{$s->value}}">{{$s->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <label class="col-md-12">Doctor</label>
                                            <div class="col-lg-12">
                                            <select class="form-control select2" name="specializationId" id="ddr-doctor-manage">
                                                    <option value="">All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group form-row flex-column">
                                            <div class="col-lg-12">
                                                <label>Status</label>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="d-flex flex-row">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                        <label class="custom-control-label" for="">Active</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                        <label class="custom-control-label" for="">Inactive</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" id="" class="btn btn-primary btn-action get-ddr"><i class="fa fa-filter"></i> &nbsp;Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Roster List
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body" id="tableAjax">
                            <table id="myTable1"
                                data-show-columns="true"
                                data-search="true"
                                data-show-toggle="true"
                                data-pagination="true"
                                data-resizable="true"
                            >
                                <thead class="thead-light">
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Specialization</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Time Duration (minutes)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
       </div>
   </div>
</div>

<!-- Edit Modal  -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Edit Duty Roster</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="box-shadow: unset">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Information
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="d-flex flex-wrap">
                                <div class="col-sm-4 col-md-4 col-lg-2">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-sm-12">Form Date</label>
                                        <div class="col-sm-12">
                                            <input type="Date" name="fromDate" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-2">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-sm-12">To Date</label>
                                        <div class="col-sm-12">
                                            <input type="Date" name="toDate"  class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-lg-12">Specailization</label>
                                        <div class="col-lg-12">
                                            <select class="form-control select2" name="appointmentServiceTypeCode" disabled>
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-md-12">Doctor</label>
                                        <div class="col-lg-12">
                                            <select class="form-control select2" name="specializationId" disabled>
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <div class="col-lg-12">
                                            <label>Duration in minutes</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="number" name="appointmentNumber"  class="form-control" disabled/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <div class="col-lg-12">
                                            <label>Status</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-flex flex-row">
                                                <div class="custom-control custom-radio mr-3">
                                                    <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                    <label class="custom-control-label" for=""> Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-sm-12">Remarks</label>
                                        <div class="col-sm-12">
                                            <textarea name="remarks" placeholder="Remarks" style="width:100%" id="" rows="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="box-shadow: unset">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Availability
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">
                                            <span>Days</span>
                                        </th>
                                        <th>
                                            <div>
                                                <input type="checkbox">
                                                <span>Day off</span>
                                            </div>
                                        </th>
                                        <th>
                                            <span>Start Time</span>
                                        </th>
                                        <th>
                                            <span>End Time</span>
                                        </th>
                                        <th>
                                            <span>Time Slot</span>
                                        </th>
                                        <th>
                                            <span>Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sun</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal" disabled><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Mon</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tue</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Wed</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Thu</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Fri</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Sat</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-top">
                                        <td>
                                            <div class="checkbox pt-2">
                                                <input id="select-all" class="magic-checkbox" type="checkbox" data-toggle="modal" data-target="#overridemodal">
                                                <label for="select-all">Override (optional)</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-secondary btn-action mr-2">Cancel</button>
                                    <button class="btn btn-primary btn-action">Update</button>
                                </div>
                            </div> -->
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-action onclose mr-2" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-action" data-dismiss="modal">Update</button>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal  -->
<div class="modal fade" id="deletemodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Delete Doctor Duty Roster</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12 pt-2">
                        <div class="form-group">
                            <label for="reamrks">Are you sure You want to delete this Doctor Duty Roster? Remarks required<span class="text-danger">*</span></label>
                            <textarea name="remarks" placeholder="Remarks" style="width:100%" id="" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-action onclose mr-2" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-action" data-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Clone and Add New Modal  -->
<div class="modal fade" id="cloneandnewmodal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Clone and Add Doctor Roster</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="box-shadow: unset">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Information
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="d-flex flex-wrap">
                                <div class="col-sm-4 col-md-4 col-lg-2">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-sm-12">Form Date</label>
                                        <div class="col-sm-12">
                                            <input type="Date" name="fromDate" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-2">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-sm-12">To Date</label>
                                        <div class="col-sm-12">
                                            <input type="Date" name="toDate"  class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-lg-12">Specailization</label>
                                        <div class="col-lg-12">
                                            <select class="form-control select2" name="appointmentServiceTypeCode" disabled>
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <label class="col-md-12">Doctor</label>
                                        <div class="col-lg-12">
                                            <select class="form-control select2" name="specializationId">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <div class="col-lg-12">
                                            <label>Duration in minutes</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="number" name="appointmentNumber"  class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group form-row flex-column">
                                        <div class="col-lg-12">
                                            <label>Status</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-flex flex-row">
                                                <div class="custom-control custom-radio mr-3">
                                                    <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                    <label class="custom-control-label" for=""> Active</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-3">
                                                    <input type="radio" id="" value="" name=""  class="custom-control-input">
                                                    <label class="custom-control-label" for=""> Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="box-shadow: unset">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Doctor Availability
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">
                                            <span>Days</span>
                                        </th>
                                        <th>
                                            <div>
                                                <input type="checkbox">
                                                <span>Day off</span>
                                            </div>
                                        </th>
                                        <th>
                                            <span>Start Time</span>
                                        </th>
                                        <th>
                                            <span>End Time</span>
                                        </th>
                                        <th>
                                            <span>Time Slot</span>
                                        </th>
                                        <th>
                                            <span>Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sun</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal" disabled><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Mon</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tue</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Wed</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Thu</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Fri</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Sat</td>
                                        <td>
                                            <div>
                                                <input type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="starttime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="endtime" placeholder="00:00">
                                                <option value="">12:45 PM</option>
                                                <option value="">12:50 PM</option>
                                                <option value="">12:55 PM</option>
                                                <option value="">1:00 PM</option>
                                                <option value="">1:05 PM</option>
                                                <option value="">1:10 PM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#setmodemodal"><i class="fa fa-clock"></i>&nbsp;Set Mode</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-outline-primary"><i class="fa fa-copy"></i>&nbsp;Clone</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="border-top">
                                        <td>
                                            <div class="checkbox pt-2">
                                                <input id="select-all" class="magic-checkbox" type="checkbox" data-toggle="modal" data-target="#overridemodal">
                                                <label for="select-all">Override (optional)</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-action" data-dismiss="modal">Clone and Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Override Modal  -->
<div class="modal fade" id="overridemodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Add Override</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-row flex-column">
                            <div class="col-lg-12">
                                <label>Doctor Duty Date</label>
                            </div>
                            <div class="col-lg-12">
                                <input type="number" name="appointmentNumber"  class="form-control" disabled/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-row flex-column">
                            <label class="col-sm-12">Override Form Date</label>
                            <div class="col-sm-12">
                                <input type="Date" name="fromDate" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-row flex-column">
                            <label class="col-sm-12">Override To Date</label>
                            <div class="col-sm-12">
                                <input type="Date" name="toDate"  class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-row flex-column">
                            <label class="col-sm-12">Start Time</label>
                            <div class="col-md-12">
                                <select class="form-control select2" name="starttime" placeholder="00:00">
                                    <option value="">12:45 PM</option>
                                    <option value="">12:50 PM</option>
                                    <option value="">12:55 PM</option>
                                    <option value="">1:00 PM</option>
                                    <option value="">1:05 PM</option>
                                    <option value="">1:10 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-row flex-column">
                            <label class="col-sm-12">End Time</label>
                            <div class="col-md-12">
                                <select class="form-control select2" name="starttime" placeholder="00:00">
                                    <option value="">12:45 PM</option>
                                    <option value="">12:50 PM</option>
                                    <option value="">12:55 PM</option>
                                    <option value="">1:00 PM</option>
                                    <option value="">1:05 PM</option>
                                    <option value="">1:10 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox pt-2">
                            <input id="select-all" class="magic-checkbox" type="checkbox">
                            <label for="select-all">Off</label>
                        </div>
                    </div>
                    <div class="col-md-6 pt-2">
                        <div class="form-group">
                            <textarea name="remarks" placeholder="Remarks" style="width:100%" id="" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-action onclose" data-dismiss="modal">Save and Exit</button>
                <button type="button" class="btn btn-primary btn-action" data-dismiss="modal">Save and Add Another</button>
            </div>
        </div>
    </div>
</div>

<!-- Set Mode Modal  -->
<div class="modal fade" id="setmodemodal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Manage Time Slots</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span id="day-slot">Sunday</span> (<span id="day-range-slot"></span>)</div>
                        <div class="d-flex flex-row">
                            <!-- <div class="d-flex flex-row">
                                <span>Online (<span class="slot-online-length">0</span>/<span class="slot-length">0</span>)</span>
                                <div class="checkbox ml-2">
                                    <input id="select-all" class="magic-checkbox select-all-online" type="checkbox">
                                    <label for="select-all">All</label>
                                </div>
                            </div> -->
                            <div class="d-flex flex-row ml-5">
                                <span>Offline (<span class="slot-offline-length">0</span>/<span class="slot-length">0</span>)</span>
                                <div class="checkbox ml-2">
                                    <input id="select-all" class="magic-checkbox select-all-offline" type="checkbox">
                                    <label for="select-all">All</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pt-2 border-top">

                        <div class="row" id="range-slots">
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-1">
                                    <div class="d-flex align-items-center justify-content-around border rounded p-2">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-3">
                                <div class="">
                                    <div class="d-flex align-items-center justify-content-around">
                                        <button class="btn btn-primary btn-action">12:30 AM</button>
                                        <div class="on-check">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                        <div class="off-check">
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>

                    <!-- <table class="table table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    <span>Time</span>
                                </th>
                                <th>
                                    <div>
                                        <input type="checkbox">
                                        <span>Online (1/2)</span>
                                    </div>
                                </th>
                                <th>
                                    <div>
                                        <input type="checkbox">
                                        <span>Offline (1/2)</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>12:20 AM</td>
                                <td>
                                    <div>
                                        <input type="checkbox">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <input type="checkbox">
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-action" data-dismiss="modal"><i class="fa fa-save"></i>&nbsp;Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('after-script')
<script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
<script>

var doctorWeekDaysDutyRosterDTOSDATA={
    0:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:1,
    weekDaysName:"Sunday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    1:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:2,
    weekDaysName:"Monday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    2:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:3,
    weekDaysName:"Tuesday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    3:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:4,
    weekDaysName:"Wednesday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    4:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:5,
    weekDaysName:"thursday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    5:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:6,
    weekDaysName:"Friday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
    6:{
    dayOffStatus:"N",
    doctorList :[],
    endTime:"2022-04-10T18:23:00.000Z",
    errorMessage:"",
    isSettingCloned:false,
    isUpdate:true,
    offlineCount:0,
    onlineCount:2,
    startTime:"2022-04-10T18:15:00.000Z",
    weekDaysDoctorInfo:[],
    weekDaysId:7,
    weekDaysName:"Saturday",
    weekDaysTimeInfo:{
       0: {
            isOffline:"N",
            isOnilne:"N",
            status:"Y",
            time:"12:00 AM"
        },
        1: {
             isOffline:"N",
             isOnilne:"N",
             status:"Y",
             time:"12:02 AM"
        },
    }
    },
   
}
   
    var ddrRequest={
        doctorDutyRosterOverrideRequestDTOS:{},
        doctorId:220,
        doctorWeekDaysDutyRosterRequestDTOS:doctorWeekDaysDutyRosterDTOSDATA,
        fromDate:"2022-04-11T09:24:13.823Z",
        hasOverrideDutyRoster:"N",
        hospitalId:"",
        rosterGapDuration:"1",
        specializationId:110,
        status:"Y",
        toDate:"2022-04-17T09:24:13.823Z"
    }
   ;

   var currentDayIndex = 0;
    var sun_range=[],
    mon_range=[],
    tue_range=[],
    wed_range=[],
    thu_range=[],
    fri_range=[],
    sat_range=[];
   function getTime(day,index)
    {
        $('.select-all-offline').prop('checked',false);
        $('.slot-offline-length').html(0);

        currentDayIndex = index;
        let start_time = $('.'+day+'-starttime').val();
        let end_time = $('.'+day+'-endtime').val();
        console.log('2022-04-13 T'+start_time);
       

        var start_time_array = start_time.split(":");
        var end_time_array = end_time.split(":");
        let start_time_hour = start_time_array[0];
        let start_time_minute = start_time_array[1];
        let end_time_hour = end_time_array[0];
        let end_time_minute = end_time_array[1];

        if(start_time_minute.includes('am')){
            start_time_minute = start_time_minute.replace('am','');
            start_time_type = 'AM';
        }else{
            start_time_minute = start_time_minute.replace('pm','');
            start_time_type = 'PM';
        }
        if(end_time_minute.includes('am')){
            end_time_minute = end_time_minute.replace('am','');
            end_time_type = 'AM';
        }else{
            end_time_minute = end_time_minute.replace('pm','');
            end_time_type = 'PM';
        }
        

        var startTime = new Date('2022-04-13 '+start_time_hour + ':'+start_time_minute).toISOString();
        var endTime = new Date('2022-04-13 '+end_time_hour + ':'+end_time_minute).toISOString();
        doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].startTime=startTime ;

        doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].endTime = endTime;
        console.log('start_time_hour',start_time_hour)
        console.log('start_time_minute',start_time_minute)
        
        console.log('end_time_hour',end_time_hour)
        console.log('end_time_minute',end_time_minute)
        var interval = 1;
        if($('#ddr-duration').val() != ''){
            var interval = $('#ddr-duration').val();
            interval = parseInt(interval);
        }
       
        start_time_minute = parseInt(start_time_minute);
        start_time_hour = parseInt(start_time_hour);
        end_time_minute = parseInt(end_time_minute);
        end_time_hour = parseInt(end_time_hour);
        var time_range = [];
        var time_array = [];
        if (/^\d$/.test(start_time_minute))  {
            time_array = {
                isOffline:"N",
                isOnilne:"Y",
                status:"Y",
                time: start_time_hour + ':0'+start_time_minute+' '+start_time_type
            }
            time_range.push( time_array);
        }else{
            time_array = {
                isOffline:"N",
                isOnilne:"Y",
                status:"Y",
                time: start_time_hour + ':'+start_time_minute+' '+start_time_type
            }
            time_range.push( time_array);
        }
        while ( start_time_minute != end_time_minute ||  start_time_hour != end_time_hour) {
            console.log(start_time_hour + ':'+start_time_minute);
        start_time_minute = start_time_minute + parseInt(interval);
        if (/^\d$/.test(start_time_minute))  {
            time_array = {
                isOffline:"N",
                isOnilne:"Y",
                status:"Y",
                time: start_time_hour + ':0'+start_time_minute+' '+start_time_type
            }
            time_range.push( time_array);
        }else{
            time_array = {
                isOffline:"N",
                isOnilne:"Y",
                status:"Y",
                time: start_time_hour + ':'+start_time_minute+' '+start_time_type
            }
            time_range.push( time_array);
        }
        if(start_time_minute == 60){
            start_time_minute = 0;
            if(start_time_hour == 12){
                start_time_hour =  1;

            }else{
                if(start_time_hour == 11){
                    if(start_time_type == 'AM'){
                        start_time_type = 'PM';
                    }else{
                        start_time_type = 'AM';
                    }
                }
                start_time_hour = start_time_hour + 1;

            }
        }
        }
        console.log('time_range', time_range);
        if(day == 'sun'){
            sun_range = time_range;
        }else if(day == 'mon'){
            mon_range = time_range;
        }else if(day == 'tue'){
            tue_range = time_range;
        }else if(day == 'wed'){
            wed_range = time_range;
        }else if(day == 'thu'){
            thu_range = time_range;
        }else if(day == 'fri'){
            fri_range = time_range;
        }else{
            sat_range = time_range;
        }
        html = '';
        doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo = [];
        $.each(time_range, function (i, elem) {
        html+= ' <div class="col-md-3">'+
                               ' <div class="p-1">'+
                                    '<div class="d-flex align-items-center justify-content-around border rounded p-2">'+
                                        '<button class="btn btn-primary btn-action">'+elem.time+'</button>'+
                                        '<div class="slot-checkbox-offline"><input data-timeindex='+i+' type="checkbox" name="" id=""> </div>'+
                                    '</div></div></div>';
                                   
                                    doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo.push(elem) ;

                                    $('#range-slots').html(html);
                                    $('#day-slot').html(day);
                                    $('#day-range-slot').html(time_range[0].time+'-'+time_range[time_range.length - 1].time);
                                    $('.slot-length').html(time_range.length);


$('.slot-checkbox-offline input').click(function(){
    let length = $('.slot-checkbox-offline input:checkbox:checked').length;
    let timeindex = $(this).data('timeindex');
    if($(this).is(":checked")){
    doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo[timeindex].isOffline = 'N'
    } else{
        doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo[timeindex].isOffline = 'Y'
    }
       $('.slot-offline-length').html(length);
console.log(doctorWeekDaysDutyRosterDTOSDATA);


});
});
console.log(doctorWeekDaysDutyRosterDTOSDATA);


    }

    $('.timepickers').timepicker({
        'step':1,
    });

$('#ddr-duration').change(function(){
    let value = this.value;
   
        ddrRequest['rosterGapDuration'] = value;
    $('.timepickers').timepicker({
        'step': this.value,
    });
});

$('#fromDate').change(function(){
    let value = this.value;
        ddrRequest['fromDate'] = value;

});

$('#toDate').change(function(){
    let value = this.value;
        ddrRequest['toDate'] = value;
});

$('#ddr-specialization').change(function(){
    let value = this.value;
        ddrRequest['specializationId'] = value;

});

$('#ddr-doctor').change(function(){
    let value = this.value;
        ddrRequest['doctorId'] = value;

console.log(ddrRequest);

});

function deleteDDR(id){
    Swal.fire({  
        title: 'Do you want to delete?',  
        showDenyButton: true,  
        confirmButtonText: `Yes`,  
        denyButtonText: `No`,
        }).then((result) => { 
            if (result.isConfirmed) {  
                $.ajax({
            url: baseUrl + "/eappointment/delete-doctor-duty-roster",
            type: "GET",
            data:{id:id},
            success: function (response) {
                showAlert("Successfully deleted!");
                $('#tableAjax').html(response);
                $('#myTable1').bootstrapTable();
            },
            error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log('ddr fail',error);

                    console.log(xhr);
                }
        });


            } else if (result.isDenied) {    
                Swal.fire('Changes are not saved', '', 'info')  
            }
        });
   
}

$('.select-all-offline').click(function(){
    if($('.select-all-offline').is(":checked")){
           $('.slot-checkbox-offline input').prop('checked',true);
       }else{
           $('.slot-checkbox-offline input').prop('checked',false);
       }
       $.each(doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo,function(i,value){
        doctorWeekDaysDutyRosterDTOSDATA[currentDayIndex].weekDaysTimeInfo[i].isOffline = 'Y'
       });
       console.log(doctorWeekDaysDutyRosterDTOSDATA);
       
       let length = $('.slot-checkbox-offline input:checkbox:checked').length;
       $('.slot-offline-length').html(length);
});
    $('.all-dayoff').click(function(){
       if($('.all-dayoff').is(":checked")){
           $('.dayoff').prop('checked',true);
           $('.starttime').prop('disabled',true);
           $('.endtime').prop('disabled',true);
           $('.all-clone').prop('disabled',true);
           $('.all-setmode').prop('disabled',true);
           $.each(doctorWeekDaysDutyRosterDTOSDATA,function(i,value){
        doctorWeekDaysDutyRosterDTOSDATA[i].dayOffStatus = 'Y';
       });

       }else{
           $('.dayoff').prop('checked',false);
           $('.starttime').prop('disabled',false);
           $('.endtime').prop('disabled',false);
           $('.all-clone').prop('disabled',false);
           $('.all-setmode').prop('disabled',false);
           $.each(doctorWeekDaysDutyRosterDTOSDATA,function(i,value){
        doctorWeekDaysDutyRosterDTOSDATA[i].dayOffStatus = 'N';
       });
       }

       console.log(doctorWeekDaysDutyRosterDTOSDATA);
    });

    $('.dayoff').click(function(){
        let day = $(this).data('dayoff');
        let dayIndex = $(this).data('dayindex');
       if($(this).is(":checked")){
        $('.'+day+'-starttime').prop('disabled',true);
           $('.'+day+'-endtime').prop('disabled',true);
           $('.'+day+'-clone').prop('disabled',true);
           $('.'+day+'-setmode').prop('disabled',true);
           doctorWeekDaysDutyRosterDTOSDATA[dayIndex].dayOffStatus = 'Y';
       }else{
           $('.'+day+'-starttime').prop('disabled',false);
           $('.'+day+'-endtime').prop('disabled',false);
           $('.'+day+'-clone').prop('disabled',false);
           $('.'+day+'-setmode').prop('disabled',false);
           doctorWeekDaysDutyRosterDTOSDATA[dayIndex].dayOffStatus = 'N';
       }
       console.log(doctorWeekDaysDutyRosterDTOSDATA);
    });
    $('.btn-clone').click(function(){
        let day = $(this).data('day');
        let clone_start_time = $('.'+day+'-starttime').val();
        let clone_end_time = $('.'+day+'-endtime').val();
        $('.starttime').val(clone_start_time);
        $('.starttime').trigger('change');
        $('.endtime').val(clone_end_time);
        $('.endtime').trigger('change');
        console.log('starttime',clone_start_time);
        console.log($('.sat-starttime').val());
        console.log('endtime',clone_end_time);
    });
    $(document).on('change', '#ddr-specialization', function() {
        console.log(this.value);
        $.ajax({
            url: baseUrl + "/eappointment/get-doctor-specialization",
            type: "GET",
            data: {
                specialization_id: this.value,
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                var optionData = '';
                optionData += '<option value="">-- Select --</option>';
                $.each(response, function(i, option) {
                    optionData += '<option value="' + option.value + '">' + option.label + '</option>';
                });
                $('#ddr-doctor').empty().html(optionData);
            }
        });
    });

    $(document).on('change', '#ddr-specialization-manage', function() {
        console.log(this.value);
        $.ajax({
            url: baseUrl + "/eappointment/get-doctor-specialization",
            type: "GET",
            data: {
                specialization_id: this.value,
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                var optionData = '';
                optionData += '<option value="">-- Select --</option>';
                $.each(response, function(i, option) {
                    optionData += '<option value="' + option.value + '">' + option.label + '</option>';
                });
                $('#ddr-doctor-manage').empty().html(optionData);
            }
        });
    });

    $(document).on('click', '#add-ddr', function() {
        $.ajax({
            url: baseUrl + "/eappointment/add-doctor-duty-roster",
            type: "POST",
            data: ddrRequest,
            success: function (response) {
                console.log('here asdasd');
                showAlert("Successfully added!");
                $('.get-ddr').click().trigger('change');
            },error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log('ddr fail',error);

                    console.log(xhr);
                }
        });
    });

    $(document).on('click', '.get-ddr', function() {
        $.ajax({
            url: baseUrl + "/eappointment/get-doctor-duty-roster",
            type: "GET",
            data:{
                from_date:$('#from_date').val(),
                to_date:$('#to_date').val(),
                doctorId:$('#ddr-doctor-manage').val(),
                specializationId:$('#ddr-specialization-manage').val(),
            },
            success: function (response) {
                $('#tableAjax').html(response);
                $('#myTable1').bootstrapTable();
            },
            error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log('ddr fail',error);

                    console.log(xhr);
                }
        });
    });

    

    $(function() {
        $('#myTable1').bootstrapTable();
    })
</script>
@endpush
