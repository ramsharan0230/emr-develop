@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<style>
    .list {
        height: 400px;
        border: 1px solid #e3e3e3;
        padding 5px;
        border-radius: 5px;
        overflow: auto;
    }

    .list ul {
        list-style: none;
    }

    .list ul li {
        padding: 5px 10px;
    }

    .list ul li:hover {
        background-color: #f6f6ff;
        cursor: pointer;
    }

    .list ul li.active {
        border-left: 3px solid var(--main-bg-color);
        background-color: #f6f6ff;
    }

    .child-box {
        display: flex;
        flex-wrap: wrap;
        margin-left: 50px;
    }

    .inputs {
        display: flex;
        flex-direction: row;
        padding: 2px;
        align-items: center;
    }

    .roles-header {
        padding: 3px 20px;
    }
    
    input[type="checkbox"], input[type="checkbox"] + label {
        cursor: pointer;
    }

    .nav-tabs .nav-link.active {
        background-color: unset;
    }
</style>

<!-- Old Design  -->
<div class="container-fluid" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Notification Settings</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#low_order" role="tab"
                                aria-controls="profile" aria-selected="false">Low Order</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#expiry_item" role="tab"
                                aria-controls="contact" aria-selected="false">Expiry Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pending" role="tab"
                                aria-controls="contact" aria-selected="false">Pending lab/Radio</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#reporting" role="tab"
                                aria-controls="contact" aria-selected="false">Reporting Lab/Radio</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#verification" role="tab"
                                aria-controls="contact" aria-selected="false">Verification Lab/Radio</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#dosing" role="tab"
                                aria-controls="contact" aria-selected="false">Dosing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#opd" role="tab"
                                aria-controls="contact" aria-selected="false">OPD</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#ipd" role="tab"
                                aria-controls="contact" aria-selected="false">IPD</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent-3">

                        <div class="tab-pane fade show active" id="low_order" role="tabpanel"
                                aria-labelledby="low_order">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Low Order</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8 col-md-12">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Low Order Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="low_order_notification_message"
                                                        id="low_order_notification_message"
                                                        class="form-control">{{ Options::get('low_order_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('low_order_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                        <li>{$item-name}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="expiry_item" role="tabpanel" aria-labelledby="not-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Expiry Item</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-12">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Expiry Item Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="expiry_items_notification_message"
                                                        id="expiry_items_notification_message"
                                                        class="form-control">{{ Options::get('expiry_items_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('expiry_items_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                    {{--   <li>{$no}</li>--}}
                                        <li>{$item-name}</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <!-- Pending-->
                        <div class="tab-pane fade" id="pending" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Pending Lab/Radio</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Pending Lab/Radio Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="pending_lab_notification_message"
                                                        id="pending_lab_notification_message"
                                                        class="form-control">{{ Options::get('pending_lab_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('pending_lab_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                    {{--         <li>{$no}</li>--}}
                                        <li>{$no}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Reporting-->
                        <div class="tab-pane fade" id="reporting" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Reporting Lab/Radio</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Reporting Lab/Radio Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="lab_reporting_notification_message"
                                                        id="lab_reporting_notification_message"
                                                        class="form-control">{{ Options::get('lab_reporting_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('lab_reporting_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                {{--   <li>{$no}</li>--}}
                                        <li>{$test}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Verification-->
                        <div class="tab-pane fade" id="verification" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Verification Lab/Radio</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Verification Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="lab_verification_notification_message"
                                                        id="lab_verification_notification_message"
                                                        class="form-control">{{ Options::get('lab_verification_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('lab_verification_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                    {{--    <li>{$no}</li>--}}
                                        <li>{$test}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Dosing-->
                        <div class="tab-pane fade" id="dosing" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Dosing</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">Dosing Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="dosing_notification_message"
                                                        id="dosing_notification_message"
                                                        class="form-control">{{ Options::get('dosing_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('dosing_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                        <li>{$no}</li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <!-- OPD-->
                        <div class="tab-pane fade" id="opd" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">OPD</h4>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">OPD Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="opd_notification_message"
                                                        id="opd_notification_message"
                                                        class="form-control">{{ Options::get('opd_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('opd_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                        <li>{$user_name}</li>
                                        <li>{$patient_name}</li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <!-- IPD-->
                        <div class="tab-pane fade" id="ipd" role="tabpanel"
                                aria-labelledby="hospital-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">IPD</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-5 col-lg-3">IPD Message:</label>
                                        <div class="col-sm-5 col-lg-7">
                                            <textarea name="ipd_notification_message"
                                                        id="ipd_notification_message"
                                                        class="form-control">{{ Options::get('ipd_notification_message') }} </textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="javascript:;" class="btn btn-primary"
                                                onclick="labRadioSettings.save('ipd_notification_message')"><i
                                                    class="fa fa-check"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <h4>List of variables</h4>
                                    <ul>
                                        <li>{$user_name}</li>
                                        <li>{$patient_name}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- New Design  -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Notification Settings
                        </h3>
                    </div>
                    <div>
                        <a type="button" class="btn btn-outline-primary" href="" id="reset_not"><i class="fa fa-sync"></i>&nbsp;Reset</a>
                    </div>
                    
                </div>
                <form>
                    <div class="iq-card-body">
                        <div class="form-row">
                            <div class="col-sm-3">
                                <label for="not_type_search">Notification Type</label>
                                <div class="">
                                    <select name="not_type_search" id="not_type_search" class="form-control select2">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="not_name_search">Notification Name</label>
                                <div class="">
                                    <select name="not_name_search" id="not_name_search" class="form-control select2">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="d-flex flex-column justify-content-start">
                                    <label for="">Status </label>
                                    <div class="d-flex flex-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="" id="status_active_search"
                                                value="Active" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="status_active"> Active </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="" id="status_inactive_search" class="custom-control-input" value="Inactive">
                                            <label class="custom-control-label" for="status_inactive"> Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-4">
                                <button class="btn btn-primary btn-action float-right mr-2" type="submit"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs justify-content-start" id="myTab-two" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#notlist" role="tab" aria-controls="sample" aria-selected="true">Notification list</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#notpermission" role="tab" aria-controls="sampling_checkbox" aria-selected="true">Notification Permissions</a>
        </li>
    </ul>
    <div class="tab-content" id="myTableContent-1">
        <div class="tab-pane fade show active" id="notlist" role="tabpanel" aria-labelledby="sample">
            <div class="">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Notification List
                            </h3>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-not"><i class="fa fa-plus"></i>&nbsp;Add</button>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="table-responsive" id="not_result">
                                <table id="notTable" 
                                    data-show-columns="true"
                                    data-search="true"
                                    data-show-toggle="true"
                                    data-pagination="true"
                                    data-resizable="true"
                                >
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.N</th>
                                            <th class="text-center">Notification Type</th>
                                            <th class="text-center">Notification Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Notification Message</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notlist">
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td><i class="fa fa-circle" style="color: green"></i>&nbsp;Active</td>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex flex-row justify-content-center">
                                                    <a class="btn btn-primary m-1" data-toggle="modal" data-target="#editmodal" href="">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="" onclick=""
                                                        class="btn btn-danger m-1"
                                                        data-toggle="modal" data-target="#deletemodal">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td><i class="fa fa-circle" style="color: lightgrey"></i>&nbsp;Inactive</td>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex flex-row justify-content-center">
                                                    <a class="btn btn-primary m-1" data-toggle="modal" data-target="#editmodal" href="">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="" onclick=""
                                                        class="btn btn-danger m-1"
                                                        data-toggle="modal" data-target="#deletemodal">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="notpermission" role="tabpanel" aria-labelledby="sampling_checkbox">
            <div class="">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Notification Permissions
                            </h3>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                        <h6>Notification Type</h6>
                                        <div class="inputs">
                                            <input type="checkbox" id="" value="">
                                            <label class="ml-1" for="">All</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0 mb-1">
                                        <input type="text" class="form-control" id="" placeholder="Search">
                                    </div>
                                    <div class="list">
                                        <ul>
                                            <li class="active">Alert</li>
                                            <li class="">Reminders</li>
                                            <li class="">Low Stocks</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                        <h6>Notification Name</h6>
                                        <div class="inputs">
                                            <input type="checkbox" id="" value="">
                                            <label class="ml-1" for="">All</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0 mb-1">
                                        <input type="text" class="form-control" id="" placeholder="Search">
                                    </div>
                                    <div class="list">
                                        <ul>
                                            <li class="">Insufficient Beds</li>
                                            <li class="">New Patient Admission</li>
                                            <li class="">Patient Discharged</li>
                                            <li class="">Patient Missing</li>
                                            <li class="">Stocks Arrived</li>
                                            <li class="active">Inventory Cleaned</li>
                                            <li class="">Patient Deceased</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                        <h6>Users</h6>
                                        <div class="inputs">
                                            <input type="checkbox" id="" value="">
                                            <label class="ml-1" for="">All</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0 mb-1">
                                        <input type="text" class="form-control" id="" placeholder="Search">
                                    </div>
                                    <div class="list">
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Sudin Shakya</label>
                                            </div>
                                        </div>
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Bivek Bashyal</label>
                                            </div>
                                        </div>
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Aasara Shrestha</label>
                                            </div>
                                        </div>
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Alisha Shakya</label>
                                            </div>
                                        </div>
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Aavish Bajracharya</label>
                                            </div>
                                        </div>
                                        <div class="check-box">
                                            <div class="inputs roles-header">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Dhanusha Roka</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-right mt-3">
                            <button type="button" class="btn btn-primary btn-action"><i class="fa fa-save"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--Add Notification Settings Modal-->

<div class="modal fade" id="add-not">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Notification Setting</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-row">
                                <label for="not-type">Notification Type </label>
                                <select name="not_type_view" id="" class="select2 form-control">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="not_name_view">Notification Name </label>
                                <input type="text" id="not_name_view" name="not_name_view" value="" class="form-control" placeholder="notification name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex flex-column">
                                    <label for="status_view">Status</label>
                                    <div class="d-flex flex-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="checkbox" name="status_view" id="" value="Active" class="custom-control-input">
                                            <label class="custom-control-label" for="status_active_view"> Active </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="checkbox" name="status_view" id="" class="custom-control-input" value="Inactive">
                                            <label class="custom-control-label" for=""> Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="not Text Details">Notification Message</label>
                                <textarea id="not-details-textarea-view" name="not_details_view" class="form-control" rows="2" placeholder="message"></textarea>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose btn-action" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-action"><i class="fa fa-save"></i>&nbsp;Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Edit Modal  -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Edit 'notification-name' Setting</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="">
               <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-row">
                                <label for="not-type">Notification Type </label>
                                <select name="not_type_view" id="" class="select2 form-control">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="not_name_view">Notification Name </label>
                                <input type="text" id="not_name_view" name="not_name_view" value="" class="form-control" placeholder="notification name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex flex-column">
                                    <label for="status_view">Status</label>
                                    <div class="d-flex flex-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="checkbox" name="status_view" id="" value="Active" class="custom-control-input">
                                            <label class="custom-control-label" for="status_active_view"> Active </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="checkbox" name="status_view" id="" class="custom-control-input" value="Inactive">
                                            <label class="custom-control-label" for=""> Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="not Text Details">Notification Message</label>
                                <textarea id="not-details-textarea-view" name="not_details_view" class="form-control" rows="2" placeholder="message"></textarea>
                            </div>
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
                <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Delete Notification Setting</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12 pt-2">
                        <div class="form-group">
                            <label for="reamrks">Are you sure You want to delete this Notification Setting? Remarks required<span class="text-danger">*</span></label>
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
@endsection

@push('after-script')
<script>
    $(function() {
        $('#notTable').bootstrapTable()
    })
</script>
    <!-- <script>

        var labRadioSettings = {
            save: function (settingTitle) {
                settingValue = $('#' + settingTitle).val();
                if (settingValue === "") {
                    alert('Selected field is empty.')
                }

                $.ajax({
                    url: '{{ route('notification.setting.store') }}',
                    type: "POST",
                    data: {settingTitle: settingTitle, settingValue: settingValue},
                    success: function (response) {
                        showAlert(response.message)
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }
    </script> -->
@endpush

