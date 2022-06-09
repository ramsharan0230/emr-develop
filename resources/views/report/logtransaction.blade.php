@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Appointment log
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-lg-5 col-sm-7">
                                    <label>Appoitnment No:</label>
                                </div>
                                <div class="col-lg-7 col-sm-5">
                                    <input type="text" name="" id="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5 col-lg-4">Form Date:</label>
                                <div class="col-sm-7 col-lg-8">
                                    <input type="Date" name="" id="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5">To Date:</label>
                                <div class="col-sm-7">
                                    <input type="Date" name="" id="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-lg-5 col-sm-7">Types:</label>
                                <div class="col-lg-7 col-sm-5">
                                    <select class=" form-control" name="Types">
                                        <option value="">All</option>
                                        <option value="">Department</option>
                                        <option value="">Doctors</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-sm-4">
                                    <label>Doctor:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control" name="Types">
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-sm-7 col-lg-5">
                                    <label>Patient Details:</label>
                                </div>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" id="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-lg-5 col-sm-7">
                                    <label>Patient Type:</label>
                                </div>
                                <div class="col-sm-5 col-lg-7">
                                    <input type="text" name="" id="" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <a href="#" class="btn btn-primary btn-action float-right mt-2" type="button"> <i class="fa fa-search"></i>&nbsp;Search</a>
                            <a href="#" class="btn btn-outline-primary btn-action float-right mt-2 mr-2" type="button"> <i class="fa fa-sync-alt "></i>&nbsp;Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive res-table">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Status</th>
                                    <th>App No.</th>
                                    <th>Appt Date and Time</th>
                                    <th>Txn Date</th>
                                    <th width="12%">Txn Details</th>
                                    <th width="15%">Patient Details</th>
                                    <th>Client</th>
                                    <th>Reg No.</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><a href="#" class="badge border border-success text-success">Booked</a></td>
                                    <td class="text-center">123</td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>
                                        <span class="form-row">00004HN
                                            &nbsp;&nbsp;
                                            <p class="m-0"><i class="ri-money-dollar-circle-line text-success" aria-hidden="true"></i>&nbsp;,550</p>
                                        </span>
                                        <button type="button" class="btn btn-sm-in btn-outline-success">&nbsp;Auto Cancel</button>
                                    </td>
                                    <td>
                                        Archana Rai, 23y/F<br />
                                        <span class="form-row">
                                            <i class="ri-phone-fill"></i>&nbsp;9856788799&nbsp;
                                            <button type="button" class="btn btn-sm-in btn-outline-success mb-3">New</button>
                                        </span>
                                    </td>
                                    <td width="20%;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar mr-3">
                                                <img src="new/images/user/05.jpg" alt="chatuserimage" class="avatar-50 rounded" />
                                                <span class="avatar-status"></span>
                                            </div>
                                            <div class="chat-sidebar-name">
                                                <h6 class="mb-0">Chirayu Hopsital</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">1234</td>
                                    <td>Kapan, KTM</td>
                                </tr>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><a href="#" class="badge border border-success text-success">Booked</a></td>
                                    <td class="text-center">123</td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>
                                        <span class="form-row">00004HN
                                            &nbsp;&nbsp;
                                            <p class="m-0"><i class="ri-money-dollar-circle-line text-success" aria-hidden="true"></i>&nbsp;,550</p>
                                        </span>
                                        <button type="button" class="btn btn-sm-in btn-outline-success">&nbsp;Auto Cancel</button>
                                    </td>
                                    <td>
                                        Archana Rai, 23y/F<br />
                                        <span class="form-row">
                                            <i class="ri-phone-fill"></i>&nbsp;9856788799&nbsp;
                                            <button type="button" class="btn btn-sm-in btn-outline-success mb-3">New</button>
                                        </span>
                                    </td>
                                    <td width="20%;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar mr-3">
                                                <img src="new/images/user/05.jpg" alt="chatuserimage" class="avatar-50 rounded" />
                                                <span class="avatar-status"></span>
                                            </div>
                                            <div class="chat-sidebar-name">
                                                <h6 class="mb-0">Chirayu Hopsital</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">1234</td>
                                    <td>Kapan, KTM</td>
                                </tr>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><a href="#" class="badge border border-success text-success">Booked</a></td>
                                    <td class="text-center">123</td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>March 23,2021 <i class="ri-time-line"></i>&nbsp;<em>11:30 AM</em></td>
                                    <td>
                                        <span class="form-row">00004HN
                                            &nbsp;&nbsp;
                                            <p class="m-0"><i class="ri-money-dollar-circle-line text-success" aria-hidden="true"></i>&nbsp;,550</p>
                                        </span>
                                        <button type="button" class="btn btn-sm-in btn-outline-success">&nbsp;Auto Cancel</button>
                                    </td>
                                    <td>
                                        Archana Rai, 23y/F<br />
                                        <span class="form-row">
                                            <i class="ri-phone-fill"></i>&nbsp;9856788799&nbsp;
                                            <button type="button" class="btn btn-sm-in btn-outline-success mb-3">New</button>
                                        </span>
                                    </td>
                                    <td width="20%;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar mr-3">
                                                <img src="new/images/user/05.jpg" alt="chatuserimage" class="avatar-50 rounded" />
                                                <span class="avatar-status"></span>
                                            </div>
                                            <div class="chat-sidebar-name">
                                                <h6 class="mb-0">Chirayu Hopsital</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">1234</td>
                                    <td>Kapan, KTM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // hide/show -->
<script>
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
@endsection
