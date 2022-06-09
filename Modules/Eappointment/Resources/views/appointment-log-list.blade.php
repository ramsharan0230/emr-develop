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
                                    <input type="text" name="appointmentNumber"  class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5 col-lg-4">Form Date:</label>
                                <div class="col-sm-7 col-lg-8">
                                    <input type="Date" name="fromDate" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-sm-5">To Date:</label>
                                <div class="col-sm-7">
                                    <input type="Date" name="toDate"  class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-lg-5 col-sm-7">Appointment Service Type:</label>
                                <div class="col-lg-7 col-sm-5">
                                    <select class=" form-control" name="appointmentServiceTypeCode">
                                        <option value="">All</option>
                                        @if($AppointmentServiceType)
                                            @foreach($AppointmentServiceType as $stype)
                                            <option value="{{$stype->value}}">{{$stype->label}}</option>
                                            @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-lg-5 col-sm-7">Specialization:</label>
                                <div class="col-lg-7 col-sm-5">
                                <select class=" form-control" name="specializationId">
                                            <option value="">All</option>
                                            @if($SpecializationActive)
                                            @foreach($SpecializationActive as $spec)
                                            <option value="{{$spec->value}}">{{$spec->label}}</option>
                                            @endforeach
                                            @endif

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
                                <select class="form-control" name="doctorId">
                                            <option value=""></option>
                                            @if($DoctorHospitalwise)
                                            @foreach($DoctorHospitalwise as $doc)
                                            <option value="{{$doc->value}}">{{$doc->label}}</option>
                                            @endforeach
                                            @endif
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
                                <select class="form-control" name="patientMetaInfoId">
                                            <option value=""></option>
                                            @if($PatientMetadatainfo)
                                            @foreach($PatientMetadatainfo as $pm)
                                            <option value="{{$pm->value}}">{{$pm->label}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-lg-5 col-sm-7">
                                    <label>Patient Type:</label>
                                </div>
                                <div class="col-sm-5 col-lg-7">
                                <select class="form-control" name="patientType">
                                            <option value=""></option>
                                            <option value="N">New</option>
                                            <option value="Y">Registered</option>

                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-lg-5 col-sm-7">
                                    <label>Appointment Category:</label>
                                </div>
                                <div class="col-sm-5 col-lg-7">
                                <select class="form-control" name="appointmentCategory">
                                            <option value=""></option>
                                            <option value="Y">Self</option>
                                            <option value="N">Others</option>

                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <div class="col-lg-5 col-sm-7">
                                    <label>Status:</label>
                                </div>
                                <div class="col-sm-5 col-lg-7">
                                <select class="form-control" name="status">
                                            <option value=""></option>
                                            <option value="PA">Booked</option>
                                            <option value="A">Checked-In</option>
                                            <option value="C">Cancelled</option>
                                            <option value="RE">Refunded</option>

                                        </select>
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
                    <div class="form-row form-group text-right">
                       <div class="col-sm-12 p-2">
                            <span class="btn btn-primary">PA</span>
                            <label class="mr-2">Check-in</label>
                            <span class="btn btn-success">A</span>
                            <label class="mr-2">Booked</label>
                            <span class="btn btn-danger">C</span>
                            <label class="mr-2">Cancelled</label>
                            <span class="btn btn-warning">RE</span>
                            <label>Refund</label>
                       </div>
                    </div>
                    <div class="table-responsive table-apptlog">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Status</th>
                                    <th>App No.</th>
                                    <th>Appt Date & Time</th>
                                    <th>Txn Date</th>
                                    <th>Txn Details</th>
                                    <th>Patient Details</th>
                                    <th>Doctor</th>
                                    <th>Reg No.</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($appointments)
                            @foreach($appointments as $k => $appointment)
                                <tr>
                                    <td class="text-center">{{$k+1}}</td>
                                    <td class="text-center"><a href="#" class=" @if($appointment->status == 'PA')
                                    badge badge-pill badge-primary
                                    @endif
                                    @if($appointment->status == 'A')

                                   badge badge-pill badge-success
                                    @endif
                                    @if($appointment->status == 'C')

                                 badge badge-pill badge-danger
                                    @endif
                                    @if($appointment->status == 'RE')

                                  badge badge-pill badge-warning
                                    @endif
                                    @if($appointment->status == 'SRE')

                                    @endif">
                                    @if($appointment->status == 'PA')
                                    PA
                                    @endif
                                    @if($appointment->status == 'A')
                                   A
                                    @endif
                                    @if($appointment->status == 'C')
                                  C
                                    @endif
                                    @if($appointment->status == 'RE')
                                RE
                                    @endif
                                    @if($appointment->status == 'SRE')
                                SRE
                                    @endif



                                    </a></td>
                                    <td class="text-center">{{$appointment->appointmentNumber}}</td>
                                    <td>{{ $appointment->appointmentDate }} <i class="ri-time-line"></i>&nbsp;<em>{{$appointment->appointmentTime}}</em></td>
                                    <td>{{ $appointment->transactionDate }} </td>
                                    <td>
                                        <span class="form-row">{{ $appointment->transactionNumber }},
                                            <br>
                                            <p class="m-0"><i class="ri-money-dollar-circle-line text-success" aria-hidden="true"></i>&nbsp;{{ $appointment->appointmentAmount }}</p>
                                        </span>

                                    </td>
                                    <td>
                                        {{ $appointment->patientName }}, {{$appointment->age}}/{{$appointment->patientGender}}<br />
                                        <span class="form-row">
                                            <i class="ri-phone-fill"></i>&nbsp;{{$appointment->mobileNumber}}&nbsp;,

                                            <button type="button" class="badge badge-success mb-3 ml-1">New</button>
                                        </span>
                                    </td>
                                    <td width="20%;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar mr-3">
                                                <img src="{{ asset('new/images/user/05.jpg')}}" alt="chatuserimage" class="avatar-50 rounded" />
                                                <span class="avatar-status"></span>
                                            </div>
                                            <div class="chat-sidebar-name">
                                                <h6 class="mb-0">{{$appointment->doctorName}}({{ $appointment->specializationName }})</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{$appointment->registrationNumber}}</td>
                                    <td>{{$appointment->patientAddress}}</td>
                                </tr>
                            @endforeach
                             @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block">
                        <div class="bak-icon bg-primary">B</div>
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block">
                        <div class="bak-icon bg-primary">B</div>
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                            <div class="followup-revenue">
                                <i class="fa fa-tag"></i>
                                <span class="fl-label"> Follow-up </span>
                                <span class="amt"> NPR 0</span>
                                 from<span class="apt"> 6 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block">
                        <div class="bak-icon bg-primary">B</div>
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block">
                        <div class="bak-icon bg-primary">B</div>
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block">
                        <div class="bak-icon bg-primary">B</div>
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body p-0">
                    <div class="revenue-block-total bg-warning">
                        <div class="revenue-content ">
                          <div class="label"> Booked</div>
                           <div>
                               <span class="amt"> NPR 0</span> from<span class="apt"> 0 </span>Appt.
                            </div>
                            <div class="followup-revenue">
                                <i class="fa fa-tag"></i>
                                <span class="fl-label"> Follow-up </span>
                                <span class="amt"> NPR 0</span>
                                 from<span class="apt"> 6 </span>Appt.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
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
