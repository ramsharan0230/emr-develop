@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Appointment Check-in
                        </h4>
                    </div>

                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV" style="display: none;">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form method="post" action="{{ route('eappointment-list') }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <div class="col-lg-5 col-sm-7">
                                        <label>Appointnment No:</label>
                                    </div>
                                    <div class="col-lg-7 col-sm-5">
                                        <input type="text" name="appointmentNumber" class="form-control" />
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
                                        <input type="Date" name="toDate" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label class="col-lg-5 col-sm-7">Types:</label>
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
                            <div class="col-sm-8">
                                <button class="btn btn-primary btn-action float-right mt-2" type="submit"> <i class="fa fa-search"></i>&nbsp;Search</button>
                                <a href="#" class="btn btn-outline-primary btn-action float-right mt-2 mr-2" type="button"> <i class="fa fa-sync-alt "></i>&nbsp;Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                
                    <div class="table-responsive res-table table-apptlist">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">S/N</th>

                                    <th class="text-center">App No.</th>
                                    <th class="text-center">Appt Date & Time</th>
                                    <th class="text-center">Patient Details</th>
                                    <th class="text-center">Reg No.</th>
                                    <th class="text-center">Hospital No</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Doctor(Department)</th>
                                    <th class="text-center">Txn Details</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($appointments))
                                @foreach($appointments as $k => $appointment)
                                <tr>
                                    <td class="text-center">{{$k+1}}</td>

                                    <td class="text-center">{{$appointment->appointmentNumber}}
                                    @if($appointment->followUp == 'Y')<i class="fa fa-tag"></i>
                                    Followup

                                            @endif
                                    </td>
                                    <td><?php $date=date_create($appointment->appointmentDate);
                                    echo date_format($date,"d M,Y"); ?><br> <i class="ri-time-line"></i>&nbsp;{{ $appointment->appointmentTime }}</td>
                                    <td>
                                        {{ $appointment->patientName }},{{ $appointment->age  }}/{{ $appointment->gender }}<br />
                                        <span class="form-row">
                                            <i class="ri-phone-fill"></i>&nbsp;{{$appointment->mobileNumber}}&nbsp;
                                            @if($appointment->isRegistered == 'N')
                                            <span class="badge badge-success mb-3">New</span>
                                            @else
                                            <span class="badge badge-success mb-3">Reg</span>
                                            @endif
                                        </span>
                                    </td>

                                    <td class="text-center">{{$appointment->registrationNumber}}</td>
                                    <td>{{$appointment->hospitalNumber ?? 'NA'}}</td>
                                    <td>{{$appointment->address ?? 'NA'}}</td>
                                    <td width="20%;">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar mr-3">
                                                <img src="{{$appointment->fileUri}}" alt="chatuserimage" class="avatar-50-app rounded" />
                                                <span class="avatar-status"></span>
                                            </div>
                                            <div class="chat-sidebar-name">
                                                <h6 class="mb-0">{{$appointment->doctorName}}({{$appointment->specializationName}})</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="form-row">{{ $appointment->appointmentNumber }}
                                           <br>
                                            <p class="m-0"><i class="ri-money-dollar-circle-line text-success" aria-hidden="true"></i>&nbsp;{{$appointment->appointmentAmount}}</p>
                                        </span>
                                        <!-- <button type="button" class="btn btn-sm-in btn-outline-success">&nbsp;Auto Cancel</button> -->
                                    </td>
                                    @if($appointment->isRegistered == 'N')
                                    <td class="text-center"><a href="javascript:;" class="checkedin  btn btn-primary " patientold ="{{$appointment->hospitalNumber}}" appointmentId="{{$appointment->appointmentId}}">Checkin</a></td>
                                    @else
                                    <td class="text-center"><a href="javascript:;" class="showcheckedin btn btn-primary " patientold ="{{$appointment->hospitalNumber}}" appointmentId="{{$appointment->appointmentId}}">Checkin show</a></td>

                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <!-- <div id="listingTable"></div>
                        <a href="javascript:prevPage()" id="btn_prev">Prev</a>
                        <a href="javascript:nextPage()" id="btn_next">Next</a>
                        page: <span id="page"></span> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- the modal -->
        <div class="modal fade" id="myconfirm" tabindex="-1" role="dialog" aria-labelledby="myconfirmLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <input type="hidden" id="complaintfldid" name="fldid" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myconfirmLabel" style="text-align: center;">Confirm</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h6>Are you new patient or old? If old please enter patient id</h6>
                        <div class="form-group mt-2"><label>Patient Id</label>
                        <input type="text" name="patient_id" id="patient_id" class="form-control" readonly></div>
                        <input type="hidden" name="checkedappointmentid" id="checkedappointmentid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                        <a href="javascript:;" type="button" class="checkedinshowing" oldpatientid="" class="btn btn-primary btn-action">Submit</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- The Modal -->


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

    $('.showcheckedin').click(function() {
       // alert('dd');
       var oldpatientid = $(this).attr('patientold');
        var appid = $(this).attr('appointmentid');
        $('#checkedappointmentid').val(appid);
        $('#patient_id').val(oldpatientid);
        $('#myconfirm').modal('show');
    });


    $(document).on('click', '.checkedinshowing', function () {
       // alert('test');
      var oldpatientid = $(this).attr('patientold');
        var appid = $('#checkedappointmentid').val();
        var patient_id = $('#patient_id').val();

        $('.checkedin').attr('disabled','disabled');
        $.ajax({
            url: '{{ route('eappointment.checkedin') }}',
            type: "POST",
            data: {
                appid: appid,
                patient_id: patient_id,
                oldpatientid:oldpatientid

            },
            success: function(response) {
                console.log(response);
                if(response.checkinstatus == 200){
                  //  alert('s')
                    console.log('200 success');
                    $('#myconfirm').modal('hide');
                    window.open(response.urlchange);
                    location.reload();
                }else{
                    alert('Something went wrong!!');
                }

                $('.checkedin').prop("disabled", false);


            },
            error: function(xhr, status, error) {
             //  alert('error');
                if(response.checkinstatus == 200){
                    console.log('200 e');
                    $('#myconfirm').modal('hide');
                    window.open(response.urlchange);
                    location.reload();
                }else{
                    alert('Something went wrong!!');
                }
                $('.checkedin').prop("disabled", false);
            }
        });

    })


$(document).on('click', '.checkedin', function () {
       // alert('test');

        var appid = $(this).attr('appointmentid');
        var patient_id = $('#patient_id').val();
        var oldpatientid = $(this).attr('patientold');
        $('.checkedin').attr('disabled','disabled');
        $.ajax({
            url: '{{ route('eappointment.checkedin') }}',
            type: "POST",
            data: {
                appid: appid,
                patient_id: patient_id,
                oldpatientid:oldpatientid

            },
            success: function(response) {
                console.log(response);
                if(response.checkinstatus == 200){
                  //  alert('s')
                    console.log('200 success');
                    $('#myconfirm').modal('hide');
                    window.open(response.urlchange);
                    location.reload();
                }else{
                    alert('Something went wrong!!');
                }
                $('.checkedin').prop("disabled", false);

            },
            error: function(xhr, status, error) {
             //  alert('error');
                if(response.checkinstatus == 200){
                    console.log('200 e');
                    $('#myconfirm').modal('hide');
                    window.open(response.urlchange);
                    location.reload();
                }else{
                    alert('Something went wrong!');
                }
                $('.checkedin').prop("disabled", false);
            }
        });

    })






    var current_page = 1;
    var records_per_page = 2;

    var objJson = [{
            adName: "AdName 1"
        },
        {
            adName: "AdName 2"
        },
        {
            adName: "AdName 3"
        },
        {
            adName: "AdName 4"
        },
        {
            adName: "AdName 5"
        },
        {
            adName: "AdName 6"
        },
        {
            adName: "AdName 7"
        },
        {
            adName: "AdName 8"
        },
        {
            adName: "AdName 9"
        },
        {
            adName: "AdName 10"
        }
    ]; // Can be obtained from another source, such as your objJson variable

    function prevPage() {
        if (current_page > 1) {
            current_page--;
            changePage(current_page);
        }
    }

    function nextPage() {
        if (current_page < numPages()) {
            current_page++;
            changePage(current_page);
        }
    }

    function changePage(page) {
        var btn_next = document.getElementById("btn_next");
        var btn_prev = document.getElementById("btn_prev");
        var listing_table = document.getElementById("listingTable");
        var page_span = document.getElementById("page");

        // Validate page
        if (page < 1) page = 1;
        if (page > numPages()) page = numPages();

        listing_table.innerHTML = "";

        for (var i = (page - 1) * records_per_page; i < (page * records_per_page); i++) {
            listing_table.innerHTML += objJson[i].adName + "<br>";
        }
        page_span.innerHTML = page;

        if (page == 1) {
            btn_prev.style.visibility = "hidden";
        } else {
            btn_prev.style.visibility = "visible";
        }

        if (page == numPages()) {
            btn_next.style.visibility = "hidden";
        } else {
            btn_next.style.visibility = "visible";
        }
    }

    function numPages() {
        return Math.ceil(objJson.length / records_per_page);
    }

    window.onload = function() {
        changePage(1);
    };
</script>
@endsection
