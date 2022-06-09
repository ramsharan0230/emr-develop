<style type="text/css">
  .scroll-pain{
    max-height: 150px;
    overflow: scroll;
  }
</style>

@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
   <div class="row">
    <!-- Start Patient Profile -->
      @php
    $segment = Request::segment(1);

@endphp
@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
    @php
        $disableClass = 'disableInsertUpdate';
    @endphp
@else
    @php
        $disableClass = '';
    @endphp
@endif
@php
    $segment = Request::segment(1);
    if($segment == 'admin'){
    $segment2 = Request::segment(2);
    $segment3 = Request::segment(3);
    if(!empty($segment3))
    $route = 'admin/'.$segment2 . '/'.$segment3;
    else
    $route = 'admin/'.$segment2;

    }else{
    $route = $segment;
    }
@endphp
<style>
    input[name="department_bed"]:checked + label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .selected-department-bed + label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .traicolor {
        padding: 3px;
        border-radius: 50%;
    }
</style>
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
                    <input type="hidden" id="flduserid" class="current_user" value="{{Helpers::getCurrentUserName()}}"/>
                    <input type="hidden" id="fldcomp" value="{{ Helpers::getCompName() }}">
                    <input type="hidden" name="req_segment" id="req_segment" value="{{$segment}}">
                    <div class="form-group row mb-0 align-items-center">
                        <label for="" class="control-label col-sm-3 mb-0">Patient ID</label>
                        <div class="col-sm-6">
                            <input type="text" name="patient_id" id="patient_id_submit" class="form-control" placeholder="Enter patient ID"/>
                        </div>
                        <div class="col-sm-3">
                            @if(\App\Utils\Helpers::checkRedirectLastEncounter() == "Yes")
                                {{-- @if(Options::get('redirect_to_last_encounter') == "Yes") --}}
                                @php
                                    $segment = Request::segment(1);
                                    if($segment == 'admin'){
                                        $segment2 = Request::segment(2);
                                        $segment3 = Request::segment(3);
                                        if(!empty($segment3))
                                            $route = 'admin/'.$segment2 . '/'.$segment3;
                                        else
                                            $route = 'admin/'.$segment2;
                                    }else{
                                        $route = $segment;
                                    }
                                @endphp
                                <form method="post" action="{{$route}}" id="latest_encounter_form">
                                    @csrf
                                    <input type="hidden" name="encounter_id" id="latest_encounter_id">
                                </form>
                                <a href="javascript:;" id="patient_req" data-request-type="get_latest_encounter_number" url="{{ route('get_latest_encounter_number') }}" class="btn btn-primary btn-sm">
                                    Submit <i class="ri-arrow-right-line"></i>
                                </a>
                            @else
                                <a href="javascript:;" id="patient_req" data-request-type="get_encounter_number" url="{{ route('get_encounter_number') }}" class="btn btn-primary btn-sm">
                                    Submit <i class="ri-arrow-right-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="iq-card iq-card-block">
        <div class="iq-card-body">

            <div class="patient-profile">
                <div class="row">
                    <div class="col-lg-2 col-md-6 text-center">
                        <div class="profile-img traicolor mt-2" id="traicolor">

                            @php
                                $segment = Request::segment(1);
                                $image = \App\Utils\Helpers::getPatientImage($segment);
                            @endphp
                            @if(isset($image) and !empty($image))
                                <img src="{{ $image->fldpic }}" alt="">
                            @else
                                <img src="{{ asset('assets/images/dummy-img.jpg')}}" alt="">
                            @endif

                            <a href="javascript:void(0);" class="upload-profile {{ $disableClass }}" onclick="imagePop.displayModal()"><i class="ri-camera-2-fill"></i></a>
                        </div>

                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="profile-detail">
                            <h4 class="patient-name">{{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{ isset($patient) ? $patient->fldptnamefir . ' '. $patient->fldmidname . ' '. $patient->fldptnamelast:'' }}</h4>
                            <p>Pat ID: <span>@if(isset($enpatient)){{ $enpatient->fldpatientval }}@endif</span></p>
                            <input size="1" type="hidden" name="encounter_id" id="encounter_id" placeholder="" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" readonly>
                            <p>EncID: <span>@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif</span></p>
                            <p>@if(isset($patient)) {{$patient->fldagestyle}} @endif
                                {{-- @if(isset($years) && $years == 'Years')
                                    @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->age }} Years
                                    @endif
                                @endif
                                @if(isset($years) && $years == 'Months')
                                    @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%m') }}Months @endif
                                @endif
                                @if(isset($years) && $years == 'Days')
                                    @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%d') }} Days @endif
                                @endif
                                @if(isset($years) && $years == 'Hours')
                                    @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%H') }} Hours @endif
                                @endif --}}
                                / <span id="js-inpatient-gender-input">@if(isset($patient)){{ $patient->fldptsex }}@endif</span></p>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="profile-detail">

                            <div class="profile-form form-group form-row align-items-center">
                                <label for="" class="control-label col-sm-3 mb-0">Height:</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="heightvalue" value="@if(isset($body_height)){{ $height??'' }}@endif">

                                </div>
                                <div class="col-sm-4">
                                    <select name="heightrate" id="heightrate" class="form-control">
                                        <option value="1" @if(isset($heightrate) && $heightrate=='cm' ) selected=selected @endif>cm</option>
                                        <option value="2" @if(isset($heightrate) && $heightrate=='m' ) selected=selected @endif>m</option>
                                    </select>

                                </div>
                                <div class="col-sm-2">

                                    <a href="javascript:;" id="save_height" class="{{ $disableClass }}" url="{{ route('save_height') }}"><i class="ri-check-fill"></i></a>
                                </div>
                            </div>
                            <div class="profile-form form-group form-row align-items-center">
                                <label for="" class="control-label col-sm-5 mb-0">Weight(Kg):</label>
                                <div class="col-sm-5">
                                    <input type="text" name="weight" id="weight" class="form-control" value="@if(isset($body_weight)){{ $body_weight->fldrepquali??"" }}@endif">

                                </div>
                                <div class="col-sm-2">
                                    <a href="javascript:;" id="save_weight" class="{{ $disableClass }}" url="{{ route('save_weight') }}"><i class="ri-check-fill"></i></a>
                                </div>
                            </div>
                            <p>BMI: <span id="bmi">@if(isset($bmi)){{$bmi}}@endif</span></p>
                            <p>@if(isset($patient)){{ $patient->fldptaddvill }} , {{ $patient->fldptadddist }}@endif</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="profile-detail">

                            <div class="profile-form form-group form-row align-items-center">
                                <label for="" class="control-label col-sm-3 mb-0">Consult:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="consulting_aar" placeholder="" value="@if(isset($enpatient)){{ $enpatient->flduserid }} @elseif(Helpers::getCurrentRole($segment) == '1')@endif" readonly>

                                </div>
                                <div class="col-sm-2">
                                    <a href="javascript:;" class="{{ $disableClass }}" data-toggle="modal" data-target="#consultant_list"><i class="ri-stethoscope-fill"></i></a>
                                </div>
                            </div>

                            <p>DOReg: <span id="js-inpatient-dor-input">@if(isset($enpatient)){{ $enpatient->fldregdate }}@endif</span></p>
                            <p>Location:
                                <span id="get_related_fldcurrlocat">
                                    @if(isset($enpatient) && $enpatient->fldadmission == 'Discharged')
                                        Discharged
                                    @elseif(isset($enpatient))
                                        {{ $enpatient->fldcurrlocat }}
                                    @endif
                                    @if(isset($enbed)) / {{ $enbed->fldbed }}@endif
                                </span>
                            </p>


                            @if($route == 'admin/laboratory/addition' || $route == 'patient' || $route == 'eye' || $route == 'dental')

                            @else
                                <div class="form-group mt-3">
                                    @if(isset($enpatient))
                                        <a href="javascript:;" data-toggle="modal" class="btn btn-primary btn-sm" data-target="#assign-bed-emergency">Transfer</a>
                                    @endif
                                </div>
                            @endif
                            @if(isset($enpatient))
                                <div class="profile-form form-group form-row align-items-center">
                                    <label for="" class="control-label col-sm-3 mb-0">Refer:</label>
                                    <div class="col-sm-8">
                                        @php
                                            $referBy = \App\Utils\Helpers::getConsultReferBy($enpatient->fldencounterval);
                                        @endphp
                                        <p>{{ $referBy['fullname'] }} / {{ $referBy['consultname'] }}</p>
                                    </div>
                                </div>
                        @endif
                        <!-- this is for transfer bed modal start-->
                            <div class="modal fade" id="assign-bed-emergency">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Assign Bed</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <div class="form-group form-row">
                                                <select id="select-department-emergency" class="col-6 form-control" name="select-department-emergency">
                                                    <option value="">---Select Department---</option>
                                                    @if(isset($departments))
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->flddept }}" bed1="{{asset('assets/images/bed-2.png')}}" bed2="{{asset('assets/images/bed-1.png')}}">{{ $department->flddept }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group mt-2">
                                                <div class="container-fluid">
                                                    <div class="departments-bed-list row">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                                            <a href="javascript:;" id="save-department-bed" url="{{ route('save.department.bed') }}" class="btn btn-primary">Save changes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- transfer bed modal end -->

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="profile-detail">
                            <div class="profile-form form-group form-row align-items-center">
                                <input type="hidden" id="user_billing_mode" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif">

                                @if($route == 'admin/laboratory/addition')
                                    <label for="" class="control-label col-sm-4 mb-0">Billing:</label>
                                    <input type="text" name="billingmode" class="col-sm-8 yellow" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif" readonly>
                                @else
                                    @if(isset($billingset))
                                        <label for="" class="control-label col-sm-3 mb-0">Billing:</label>
                                        <div class="col-sm-9">
                                            <select name="billingmode" id="billingmode" class="form-control" url="{{route('save_billingmode')}}">
                                                <option value=""></option>
                                                @foreach($billingset as $b)

                                                    <option value="{{$b->fldsetname}}" @if(isset($enpatient) && ($enpatient->fldbillingmode ==$b->fldsetname) ) selected="selected" @endif >{{$b->fldsetname}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    @endif
                                @endif
                                <p>
                                    Status: <span id="admitedstatus">@if(isset($enpatient)){{$enpatient->fldadmission}}@endif</span>
                                </p>
                                @if($route == 'patient'  || $route == 'eye' || $route == 'dental' || $route == 'emergency')
                                    <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" value="Inside" class="custom-control-input" id="fldinside" @if(isset($enpatient) && ($enpatient->fldinside == '1')) checked @endif name="fldinside" url="{{route('inside')}}"/>
                                        <label class="custom-control-label">Patient Inside</label>
                                    </div>
                                @else

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- consulatnt modal start -->
<div class="modal fade" id="consultant_list" tabindex="-1" role="dialog" aria-labelledby="consultant_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            @csrf

            <div class="modal-header">
                <h5 class="modal-title" id="consultant_listLabel" style="text-align: center;">Choose Consultants</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body res-table">
                <div class="row">
                            <div class="col-4 mb-2">
                                <input type="text" class="form-control" id="searchConsultant" onkeyup="myFunctionSearchconsultant()" placeholder="Search for permission..">
                            </div>
                        </div>
                        <table id="consultantSearchtable" class="table table-bordered table-hover table-striped text-center">
                @php
                    $consultantList = Helpers::getConsultantList();
                @endphp
                @if(count($consultantList))
                    @foreach($consultantList as $con)
                    <tr>
                        <td style="text-align: left;">
                            <div class="custom-control custom-radio">
                            <input type="radio" name="consultant" value="{{ $con->username }}" class="custom-control-input" {{(isset($enpatient) && $enpatient->flduserid == $con->username)? "checked":"" }}>
                            <label class="custom-control-label">{{ $con->username }}</label>
                        </div>
                        </td>
                    </tr>

                    @endforeach
                @endif
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitconsultant_list" url="{{route('save_consultant')}}" data-dismiss="modal">Submit</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
        var fldencounterval = $("#fldencounterval").val();
        getPatientProfileColor();
    });


    $("#patient_id_submit").on('keyup', function (e) {
        if (e.keyCode === 13) {
            var patient_id = $("#patient_id_submit").val();
            var url = $('#patient_req').attr("url");
            if ($('#patient_req').attr("data-request-type") == "get_latest_encounter_number") {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        patient_id: patient_id
                    },
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            if (data.success.latest_encounter != 0) {
                                $('#latest_encounter_id').val(data.success.latest_encounter);
                                $('#latest_encounter_form').submit();
                            } else {
                                showAlert("Something went wrong!!");
                            }
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    }
                });
            } else {
                if (patient_id == '' || patient_id == 0) {
                    alert('Enter patient id');
                } else {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: {
                            patient_id: patient_id
                        },

                        success: function (data) {

                            if ($.isEmptyObject(data.error)) {
                                $("#ajax_response_encounter_list").empty();
                                $("#ajax_response_encounter_list").html(data.success.options);
                                $("#encounter_list").modal("show");
                            } else {
                                showAlert("Something went wrong!!");
                            }
                        }
                    });
                }
            }


        }
    });

    $("#patient_req").click(function () {

        var patient_id = $("#patient_id_submit").val();
        var url = $(this).attr("url");
        if ($('#patient_req').attr("data-request-type") == "get_latest_encounter_number") {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    patient_id: patient_id
                },
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        if (data.success.latest_encounter != 0) {
                            $('#latest_encounter_id').val(data.success.latest_encounter);
                            $('#latest_encounter_form').submit();
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        } else {
            if (patient_id == '' || patient_id == 0) {
                alert('Enter patient id');
            } else {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        patient_id: patient_id
                    },
                    success: function (data) {
                        console.log(data);
                        if ($.isEmptyObject(data.error)) {
                            $("#ajax_response_encounter_list").empty();
                            $("#ajax_response_encounter_list").html(data.success.options);
                            $("#encounter_list").modal("show");
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    }
                });
            }
        }
    });

    $("#fldinside").click(function () {
        var encounter_id = $("#encounter_id").val();
        if ($(this).prop("checked") == true) {
            var flag = 1;
            speechSynthesis.speak(message);
        } else if ($(this).prop("checked") == false) {
            var flag = 0;
        }
        var url = $(this).attr("url");
        if (encounter_id == '' || encounter_id == 0) {
            alert('Enter encounter id');
        } else {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    encounter_id: encounter_id,
                    flag: flag
                },
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        showAlert("Information saved  !!");
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        }
    });

    // Pop up modal for yes no register
    $('#yes_no_register').click(function () {
        var url = $(this).attr('url');
        var fldencounterval = $("#fldencounterval").val();
        if (fldencounterval == null) {
            showAlert('Please Enter Encounter Id');
            return false;
        }


        var html = '';
        html += '<input type="radio" id="yes_register" name="yes_no_register" value="1" style="margin-right:5px;>';
        html += '<label for="yes_register" style="margin-right: 10px;">Yes</label>';
        html += '<input type="radio" id="No_register" name="yes_no_register" value="0" style="margin-right:5px;>';
        html += '<label for="No_register">No</label>';
        $("#confirm_dialog_box_register").html(html);
        $("#confirm_dialog_box_register").dialog({
            resizable: false,
            modal: true,
            title: "",
            height: 250,
            width: 400,
            buttons: {
                "OK": function () {
                    var fldadmission = $("input[name='yes_no_register']:checked").val();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            fldencounterval: fldencounterval,
                            fldadmission: fldadmission
                        },
                        success: function (data) {
                            if ($.isEmptyObject(data.error)) {
                                $("#js-inpatient-status-input").val(null);
                                $.get('emergency/get/patient-admission?fldencounterval=' + fldencounterval, function (data) {
                                    $("#js-inpatient-status-input").val(data.fldadmission);
                                });

                                $('#admitedstatus').empty().text(data.success.data);
                                showAlert("Patient Admitted!");
                            } else {
                                showAlert("Something went wrong!!");
                            }
                        }
                    });
                    $(this).dialog('close');

                },
                "Cancel": function () {
                    $(this).dialog('close');
                    return false;
                }
            }
        });
    });


    $("#consultant_list").click(function () {

        $("#consultant_list").modal("show");

    });


    $(document).on("click", "#submitconsultant_list", function () {
        var fldencounterval = $("#fldencounterval").val();
        if (fldencounterval == null) {
            showAlert('Please Enter Encounter Id');
            return false;
        }
        // var favorite = [];
        //         $.each($("input[name='consultant']:checked"), function(){
        //             favorite.push($(this).val());
        //         });
        var user_consult = $("input[name='consultant']:checked").val();
        var encounter_id = $('#encounter_id').val();
        var url = $(this).attr("url");
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                user_consult: user_consult,
                encounter_id: encounter_id
            },
            success: function (data) {
                // console.log(data);
                if ($.isEmptyObject(data.error)) {
                    $('#consulting_aar').val(user_consult);
                    showAlert('Information saved!!');
                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });


    });

    $("#save_height").click(function () {
        var encounter_id = $('#encounter_id').val();
        var heightvalue = $("#heightvalue").val();
        var heightrate = $('#heightrate option:selected').text();

        // alert($.isNumeric(heightvalue));

        if ($.isNumeric(heightvalue) === true) {
            if (heightrate == 'm')
                var height = heightvalue * 100;
            else
                var height = heightvalue;


            //alert(height);
            var url = $(this).attr("url");
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    height: height,
                    encounter_id: encounter_id
                },
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        $('#bmi').text(data.success.bmi);
                        showAlert('Information saved!!');
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        } else {
            alert('Only numeric value allowed!!');
        }

    });


    $("#billingmode").change(function () {

        var encounter_id = $('#encounter_id').val();

        var billingmode = $('#billingmode option:selected').text();

        var url = $(this).attr("url");
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                billingmode: billingmode,
                encounter_id: encounter_id
            },
            success: function (data) {
                console.log(data);
                if ($.isEmptyObject(data.error)) {

                    showAlert('Information Saved !')
                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });
    });

    $("#save_weight").click(function () {
        var encounter_id = $('#encounter_id').val();
        var weight = $("#weight").val();

        var url = $(this).attr("url");
        if ($.isNumeric(weight) === true) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    weight: weight,
                    encounter_id: encounter_id
                },
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        $('#bmi').text(data.success.bmi);
                        showAlert('Information saved!!');
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        } else {
            alert('Only numeric value allowed!!');
        }

    });
    var dateToday = new Date();
    $('#datepicker').datetimepicker({
        maxDate: dateToday,
        changeMonth: true,
        changeYear: true,
        maxDate: 0,
        dateFormat: 'yy-mm-dd',
        yearRange: "-100:+0",
        onSelect: function (selected, evnt) {


            dob = new Date(selected);

            var today = new Date();
            var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            var encounter_id = $('#encounter_id').val();

            $('#datepicker').val(age);
            $('#dateselected').val(selected);
            $('#datepicker').datetimepicker("hide");
            $.ajax({
                url: "<?php echo route('getAgeurl'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    date: selected,
                    encounter_id: encounter_id
                },
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {

                        showAlert('Information saved!!');

                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        }
    });

    $(document).on("click", ".birthcalendra", function () {
        $('#datepicker').focus();

    });


    // Department Bed

    $(document).on('change', '#select-department-emergency', function (e) {
        var flddept = e.target.value;
        if (flddept === "") {
            showAlert('Select Department.', 'error');
            return false;
        }

        if ($("#encounter_id").val() === "") {
            showAlert('Select patient.', 'error');
            return false;
        }
        encounter_id
        // ajax
        var num = 1;
        $.get('emergency/department-bed/get-related-bed?flddept=' + flddept, function (data) {
            // console.log(data);
            $('.departments-bed-list').empty().html(data.html);
            // var disabled = 'disabled';
            // var img_path = $('#select-department-emergency option:selected').attr('bed1');
            // $.each(data, function (index, get_related_data) {
            //     if (get_related_data.fldencounterval == null) {
            //         disabled = '';
            //         img_path = $('#select-department-emergency option:selected').attr('bed2');
            //     }
            //     var transfer_bed = $('#transfer-bed-list').html();
            //     $('.departments-bed-list').empty().html(transfer_bed);
            //     num++;

            //     disabled = 'disabled';
            //     img_path = $('#select-department-emergency option:selected').attr('bed1');
            // });
        });
    });
    $('#save-department-bed').click(function () {
        var fldcurrlocat = $('#select-department-emergency option:selected').val();
        var fldbed = $("input[name='department_bed']:checked").val();
        var fldencounterval = $("#fldencounterval").val();
        var url = $(this).attr("url");
        var formData = {
            fldcurrlocat: fldcurrlocat,
            fldbed: fldbed,
            fldencounterval: fldencounterval
        };


        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    $('#assign-bed-emergency').modal('hide');
                    $("#get_related_fldcurrlocat").html(null);
                    // $("input[name='department_bed']:checked").parent('div').remove();
                    $.get('emergency/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                        $("#get_related_fldcurrlocat").html(data.fldcurrlocat + ' / ' + fldbed);
                    });
                    //location.reload();
                } else {
                    showAlert(data.error.message);
                }
            }
        });
    });

    function myFunctionSearchconsultant() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchConsultant");
            filter = input.value.toUpperCase();
            table = document.getElementById("consultantSearchtable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
</script>
  <!-- END Patient Profile -->
    <div class="col-sm-12">
       <div class="iq-card iq-card-block iq-card-stretch">
          <div class="iq-card-body">
            <div class="form-group form-row ">
                      <label class="pl-2">Pain Score:</label>&nbsp;&nbsp;
                      <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                            <label class="custom-control-label" for=""> V </label>
                         </div>
                         <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                            <label class="custom-control-label" for=""> A </label>
                         </div>
                         <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                            <label class="custom-control-label" for=""> S </label>
                         </div>
                      </div>

                   </div>
                   <hr class="mt-0">
             <div class="row">
                <div class="col-sm-6">
                   <div class="form-group">
                      <label>Digonosis:</label>
                         <textarea class="form-control scroll-pain" rows="10"></textarea>
                   </div>
                </div>
                <div class="col-sm-6">
                   <div class="form-group">
                      <label>Comorbidities: </label>
                         <textarea class="form-control scroll-pain" rows="10"></textarea>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="col-sm-12">
       <div class="iq-card iq-card-block iq-card-stretch">
          <div class="iq-card-body">
             <div class="row">
                <div class="col-sm-6">
                   <div class="form-group">
                      <label>Explain the modality:</label>
                          <textarea class="form-control" id="js-modality"></textarea>
                   </div>
                </div>
                 <div class="col-sm-6">
                   <div class="form-group">
                    <div class="form-row er-input">
                      <label class="col-sm-2 pl-0">Follow up:</label>
                      <input type="date" class="form-control col-sm-5" id="exampleInputdate" value="2019-12-18">
                    </div>
                      <textarea class="form-control" id="js-followup"></textarea>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="col-sm-12">
       <div class="iq-card iq-card-block iq-card-stretch">
           <div class="iq-card-header d-flex justify-content-between">
              <div class="iq-header-title">
                <h3 class="card-title">
                  Pain Management
                </h3>
              </div>
            </div>
          <div class="iq-card-body">
             <div class="row">

                 <div class="col-sm-4">
                   <div class="form-group">
                      <label>Counselling:</label>
                          <textarea class="form-control" id="js-counselling"></textarea>
                   </div>
                </div>
                 <div class="col-sm-4">
                   <div class="form-group">
                      <label >Intervention:</label>
                          <textarea class="form-control" id="js-intervention"></textarea>
                   </div>
                </div>
                 <div class="col-sm-4">
                   <div class="form-group">
                      <label >Drugs:</label>
                          <textarea class="form-control" id="js-drug"></textarea>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
<script type="text/javascript">
  CKEDITOR.replace('js-modality',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-intervention',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-drug',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-counselling',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-followup',
    {
    height: '100px',
    } );

</script>
@endsection

