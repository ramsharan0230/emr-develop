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
    input[name="department_bed"]:checked+label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .selected-department-bed+label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .traicolor {
        padding: 3px;
        border-radius: 50%;
    }
</style>

<div class="patient-profile">
    <div class="row">
        <div class="col-lg-4 col-md-6 text-center">
            <div class="profile-img img-discharge traicolor mt-0  mb-3" id="traicolor">

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
        <div class="col-lg-4 col-md-6">
            <div class="profile-detail">
                <input type="hidden" id="fldencounterval" value="" />
                <input type="hidden" name="selectedEncounter" id="selectedEncounter" value="">
                <h4 class="patient-name" id="patientName">
                    {{-- {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{ isset($patient) ? $patient->fldptnamefir . ' '. $patient->fldmidname . ' '. $patient->fldptnamelast:'' }}--}}
                </h4>
                <p>Pat ID: <span id="patID"></span></p>
                <input size="1" type="hidden" name="encounter_id" id="encounter_id" placeholder="" value="" readonly>
                <p>EncID: <span id="EncID"></span></p>
                <p id="age"> </p>

            </div>
            <div class="profile-detail">

                <div class="profile-form form-group form-row align-items-center">
                    <label for="" class="control-label col-sm-4 mb-0">Consult:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="consulting_aar" placeholder="" value="" readonly>

                    </div>
                </div>

                <p>DOReg: <span id="DOReg"></span></p>
                <p>Location:
                    <span id="location">

                    </span>
                </p>




            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="profile-detail">

                <div class="profile-form form-group form-row align-items-center">
                    <label for="" class="control-label col-sm-3 mb-0">Height:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="heightvalue" value="">

                    </div>
                    <div class="col-sm-5">
                        <select name="heightrate" id="heightrate" class="form-control">

                        </select>

                    </div>
                </div>
                <div class="profile-form form-group form-row align-items-center">
                    <label for="" class="control-label col-sm-5 mb-0">Weight(Kg):</label>
                    <div class="col-sm-7">
                        <input type="text" name="weight" id="weight" class="form-control" value="">

                    </div>
                </div>
                <p>BMI: <span id="bmi"></span></p>
                <p id="address"></p>
            </div>
            <div class="profile-detail">
                <div class="profile-form form-group form-row align-items-center">
                    <input type="hidden" id="user_billing_mode" value="">


                    <label for="" class="control-label col-sm-3 mb-0">Billing: </label>

                    <div class="col-sm-9">
                        <select name="" id="billingmode" class="form-control" url="">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="profile-detail">
                <div class="profile-form form-group form-row align-items-center">
                    <label class="control-label col-sm-3 mb-0">
                        Status: <b><span id="admitedstatus"></span></b>
                    </label>


                </div>
                <!-- <div class="col-lg-4"></div> -->
                <!-- <div class="col-lg-7 col-md-6">
                        <div class="profile-detail">

                            <div class="profile-form form-group form-row align-items-center">
                                <label for="" class="control-label col-sm-3 mb-0">Consult:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="consulting_aar" placeholder="" value="" readonly>

                                </div>
                            </div>

                            <p>DOReg: <span id="DOReg"></span></p>
                            <p>Location:
                                <span id="location">

                                </span>
                            </p>




                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="profile-detail">
                            <div class="profile-form form-group form-row align-items-center">
                                <input type="hidden" id="user_billing_mode" value="">


                                <label for="" class="control-label col-sm-3 mb-0">Billing: </label>

                                <div class="col-sm-9">
                                    <select name="" id="billingmode" class="form-control" url="">
                                        <option value=""></option>
                                    </select>
                                </div>



                                <p>
                                    Status: <span id="admitedstatus"></span>
                                </p>

                            </div>
                        </div>
                    </div> -->
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

<!--consultant modal end -->
@include('outpatient::modal.patient-encounter-modal')


<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
        var fldencounterval = $("#fldencounterval").val();
        getPatientProfileColor();
    });


    $("#patient_id_submit").on('keyup', function(e) {
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
                    success: function(data) {
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

                        success: function(data) {

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

    $("#patient_req").click(function() {

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
                success: function(data) {
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
                    success: function(data) {
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

    $("#fldinside").click(function() {
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
                success: function(data) {
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
    $('#yes_no_register').click(function() {
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
                "OK": function() {
                    var fldadmission = $("input[name='yes_no_register']:checked").val();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            fldencounterval: fldencounterval,
                            fldadmission: fldadmission
                        },
                        success: function(data) {
                            if ($.isEmptyObject(data.error)) {
                                $("#js-inpatient-status-input").val(null);
                                $.get('emergency/get/patient-admission?fldencounterval=' + fldencounterval, function(data) {
                                    $("#js-inpatient-status-input").val(data.fldadmission);
                                });

                                $('#admitedstatus').empty().text(data.success.data);
                                if (data.success.data == "Registered") {
                                    $('#patientActionButton').prop("disabled", true);
                                } else if (data.success.data == "Admitted") {
                                    $('#patientActionButton').removeAttr("disabled");
                                    $('#patientActionButton').html("Assign Bed");
                                } else {
                                    $('#patientActionButton').removeAttr("disabled");
                                    $('#patientActionButton').html("Transfer");
                                }
                                showAlert("Patient Admitted!");
                            } else {
                                showAlert("Something went wrong!!");
                            }
                        }
                    });
                    $(this).dialog('close');

                },
                "Cancel": function() {
                    $(this).dialog('close');
                    return false;
                }
            }
        });
    });


    $("#consultant_list").click(function() {

        $("#consultant_list").modal("show");

    });


    $(document).on("click", "#submitconsultant_list", function() {
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
            success: function(data) {
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

    $("#save_height").click(function() {
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
                success: function(data) {
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


    $("#billingmode").change(function() {

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
            success: function(data) {
                console.log(data);
                if ($.isEmptyObject(data.error)) {

                    showAlert('Information Saved !')
                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });
    });

    $("#save_weight").click(function() {
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
                success: function(data) {
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
        onSelect: function(selected, evnt) {


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
                success: function(data) {
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

    $(document).on("click", ".birthcalendra", function() {
        $('#datepicker').focus();

    });


    // Department Bed

    $(document).on('change', '#select-department-emergency', function(e) {
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
        $.get('emergency/department-bed/get-related-bed?flddept=' + flddept, function(data) {
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
    $('#save-department-bed').click(function() {
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
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    $('#assign-bed-emergency').modal('hide');
                    $("#get_related_fldcurrlocat").html(null);
                    // $("input[name='department_bed']:checked").parent('div').remove();
                    $.get('emergency/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function(data) {
                        $("#get_related_fldcurrlocat").html(data.fldcurrlocat + ' / ' + fldbed);
                    });
                    $('#patientActionButton').html("Transfer");
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
