@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
   @include('menu::toggleButton')
   <div class="row">
       <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Dietitian Planning</h4>
                </div>
                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="myDIV">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <form name="bedoccupany" method="post" id="bedOccupancyForm" action="{{route('dietitian.submit.bed.form')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group er-input">
                                <select name="door" id="" class="form-control">
                                    <option value="%" {{ isset($requestData['door']) && $requestData['door'] == "%"?"selected":"" }}>%</option>
                                   @foreach ($departments as $department)
                                        <option value="{{ $department->flddept }}" {{ isset($requestData['door']) && $requestData['door'] == $department->flddept?"selected":"" }}>{{ $department->flddept }}</option>
                                    @endforeach
                                </select>&nbsp;
                                <select name="color" id="" class="form-control">
                                    <option value="">Order</option>
                                    <option value="Red" {{ isset($requestData['color']) && $requestData['color'] == "Red"?"selected":"" }}>Red</option>
                                    <option value="Yellow" {{ isset($requestData['color']) && $requestData['color'] == "Yellow"?"selected":"" }}>Yellow</option>
                                    <option value="Green" {{ isset($requestData['color']) && $requestData['color'] == "Green"?"selected":"" }}>Green</option>
                                    <option value="Blue" {{ isset($requestData['color']) && $requestData['color'] == "Blue"?"selected":"" }}>Blue</option>
                                    <option value="Black" {{ isset($requestData['color']) && $requestData['color'] == "Black"?"selected":"" }}>Black</option>
                                    <option value="%" {{ isset($requestData['color']) && $requestData['color'] == "%"?"selected":"" }}>All</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 cpl-lg-5">
                            <div class="form-group">
                                <div class="er-input">
                                    <input type="text" value="{{ isset($requestData['encounter_id'])?$requestData['encounter_id']:'' }}" name="encounter_id" class="form-control" placeholder="Enter encounter Id">&nbsp;
                                    <input type="text" value="{{ isset($requestData['encounter_name'])?$requestData['encounter_name']:'' }}" name="encounter_name" class="form-control" placeholder="Enter Patient Name">&nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 col-lg-4 padding-none">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="action" value="Refresh"><i class="fas fa-sync-alt"></i>&nbsp;Refresh</button>
                                <button class="btn btn-primary" type="button" name="action" value="Report" onclick="submitReportBedOccupancy();"><i class="far fa-file-pdf"></i>&nbsp;Report</button>
                                <button class="btn btn-primary" type="button" name="action" value="Progress" onclick="submitReportBedOccupancyProgress();"><i class="far fa-file-pdf"></i>&nbsp;Progress</button>
                                <button class="btn btn-primary" type="button" name="action" value="All" onclick="submitReportBedOccupancyAll();"><i class="far fa-file-pdf"></i>&nbsp;All</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="col-sm-4">
                    <select name="act" class="act form-control">
                        <option url="" value="">--ACTIVITIES--</option>
                        <option url="" value="Essential Exam">Essential Exam</option>
                        <option url="" value="Drug Dosing">Drug Dosing</option>
                        <!-- <option url="" value="PO Intake">PO Intake*</option> -->
                        <option url="" value="Fluid Output">Fluid Output</option>
                        <option url="" value="">--FORMS--</option>
                        <option url="{{route('inpatient')}}" value="In-Patient">In-Patient</option>
                        <option url="{{route('delivery')}}" value="Delivery">Delivery</option>
                        <option url="{{route('emergency')}}" value="ER">ER Form</option>
                        <option url="{{route('majorprocedure')}}" value="Major Procedure">Major Procedure Form</option>
                        <!--  <option url="" value="ICU">ICU*</option> -->
                        <option url="" value="">--REQUEST--</option>
                        <option url="" value="Laboratory">Laboratory</option>
                        <option url="" value="Radiology">Radiology</option>
                        <option url="" value="Pharmacy">Pharmacy</option>
                    </select>


                </div>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="diet_plan_date">
                </div>
            </div>
            <div class="iq-card-body">

                <div id="table" class="table-responsive table-container">
                    <table class="table table-bordered table-hover table-striped text-center ">
                        <thead class="thead-light">
                            <tr>
                                <th>S.N</th>
                                <th>Bed</th>
                                <th>EncID</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Diet Plan</th>
                                <th>Diagnosis</th>
                                <th>Reason of Admission</th>
                                <th>Consult</th>
                                <th>Follow up Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($encounters))
                            @foreach($encounters as $k => $en)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$en['fldbed']}}</td>
                                <td>{{$en['encounter_id']}}</td>
                                <td style="width: 20%;">{{$en['name']}}</td>

                                <td style="width: 15%;">{{$en['agesex']}} {{ $en['birthday'] }}</td>
                                <td><a href="javascript:;" class="moveto" data-patientID="{{$en['patient_id']}}" data-patient_name="{{$en['name']}}" data-sex="{{$en['sex']}}" data-encounter_id="{{$en['encounter_id']}}"  ><i class="fas fa-book-medical"></i></a></td>
                                <td style="width: 25%;">{{$en['dignosis']}}</td>
                                <td>{{$en['reasonofadmission']}}</td>
                                <td>{{$en['consult']}}</td>
                                <td class="table-row-encounter">
                                    <input type="text" name="dietitian_followup_date" class="dietitian_followup_date" autocomplete="off" value="{{(isset($en['dietitian_followup_date']->flddietitianfollowupdate) && !empty($en['dietitian_followup_date']->flddietitianfollowupdate)) ? $en['dietitian_followup_date']->flddietitianfollowupdate : ''}}">
                                    <input type="hidden" name="" class="encounteval" value="{{$en['encounter_id']}}">
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                    </table>
                    <div id="bottom_anchor"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal" id="dietPlan">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="file-modal-title">Diet Planning</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3"> Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="patient_name" value="" id="patient_name" disabled="">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3"> Sex</label>
                            <div class="col-sm-9">
                                <input type="text" name="patient_sex" value="" id="patient_sex" disabled="">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-1">
                    <ul class="nav nav-tabs" id="yourTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#newdiet" role="tab" aria-controls="home" aria-selected="true">New Daily Plan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#dietsaved" role="tab" aria-controls="profile" aria-selected="false">Saved Daily Plan
                            </a>
                        </li>
                    </ul>
                    <div class=" col-lg-12 tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="newdiet" role="tabpanel" aria-labelledby="home-tab">
                            <div class="col-md-12 mt-3">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Start</label>
                                            <div class="col-sm-8">
                                                <input type="date" id="js-input-date-planned" class="form-control">&nbsp;
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="js-new-daily-plan-btn"><i class="ri-refresh-line"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <div class="input-group clockpicker">
                                                <input type="text" id="js-input-time-planned" class="form-control" value="09:30">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Type <br/><small>(To Be Filled By Doctors)</small></label>
                                            <div class="col-sm-9">
                                                <select name="" id="js-input-type-planned" class="form-control"></select>
                                            </div>
                                        </div>


                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Item</label>
                                            <div class="col-sm-10">
                                                <select name="" id="js-input-item-planned" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-5">Other Recommendation <br/><small>(To Be Filled By Doctors)</small></label>
                                            <div class="col-sm-7">

                                                <textarea name="" id="js-input-other-recommendation" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Recommended Diet</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-recommended-diet" class="form-control">

                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Prescribed Diet</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="" id="js-input-prescribed-diet" class="form-control">

                                            </div>
                                            <div class="col-sm-3">
                                                <select name="" id="js-input-prescribed-diet-unit" class="form-control">
                                                    <option value="Energy (kcal)">Energy (kcal)</option>
                                                        <option value="Protein (gm)">Protein (gm)</option>
                                                        <option value="Fat (gm)">Fat (gm)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Food Supplement</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-food-supplement" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Dose</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="" id="js-input-dose-planned" class="form-control">
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" name="" id="js-input-gram-planned" class="form-control" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group form-row align-items-center">
                                            <div class="col-sm-8">
                                                <input type="number" id="js-input-duration-planned" class="form-control" value="24" min="1" max="100" />
                                            </div>
                                            <label for="" class="col-sm-2">Hourly</label>
                                            <div class="col-sm-2">
                                                <input type="hidden" name="encounter_id" id="encounter_id">
                                                <button type="add" id="js-add-diet-planning-btn" class="btn btn-primary btn-sm-in"><i class="ri-add-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Feeding Route<br/><small>(To Be Filled By Doctors)</small></label>
                                            <div class="col-sm-8">
                                                <select name="" id="js-input-type-feeding-route" class="form-control">
                                                    <option value="Oral">Oral</option>
                                                    <option value="NG">NG</option>
                                                    <option value="OG">OG</option>
                                                    <option value="NJ">NJ</option>
                                                    <option value="PEG">PEG</option>
                                                    <option value="PEJ">PEJ</option>
                                                    <option value="Feeding Jejunostomy">Feeding Jejunostomy</option>
                                                    <option value="TPN">TPN</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Fluid Restriction<br/><small>(To Be Filled By Doctors)</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" name="fluid_restriction" id="fluid_restriction" class="form-control">

                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Therapeutic Need <br/><small>(To Be Filled By Doctors)</small></label>
                                            <div class="col-sm-8">
                                                <select name="" id="js-input-type-therapeutic-route" class="form-control">
                                                    <option value="High Calorie/Low calorie">High Calorie/Low calorie</option>
                                                    <option value="High protein">High protein</option>
                                                    <option value="High sodium/Low sodium">High sodium/Low sodium</option>
                                                    <option value="High potassium/Low potassium">High potassium/Low potassium</option>
                                                    <option value="Others">Others</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Any Restriction</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-restriction" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Extra Diet(If any)</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-extra-diet" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Energy</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-energy-planned" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-4">Fluid</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="" id="js-input-fluid-planned" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group-inner custom-diet">
                                            <button id="js-save-diet-planning-btn" class="btn btn-primary "><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;Save
                                            </button>
                                            <button id="js-diet-planning-export-btn-modal" class="btn btn-primary "><img src="{{ asset('assets/images/calculator.png') }}" width="18px" alt=""></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <div class="res-table">
                                    <table class="table-hover table-bordered table-striped table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="tittle-th">Route</th>
                                                <th class="tittle-th">Type</th>
                                                <th class="tittle-th">Therapeutic Need</th>
                                                <th class="tittle-th">Item</th>

                                                <th class="tittle-th">Dose</th>
                                                <th class="tittle-th">Energy</th>
                                                <th class="tittle-th">Fluid</th>
                                                <th class="tittle-th">Status</th>
                                                <th class="tittle-th">Fluid Restriction</th>
                                                <th class="tittle-th">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-diet-planning-planned-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade col-lg-12" id="dietsaved" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row mt-2">
                                <div class="col-lg-4">
                                    <div class="form-group form-row align-items-center">
                                        <div class="col-sm-10">
                                            <input type="date" id="js-input-date-saved" class="form-control">
                                        </div>
                                        <div class="col-sm-2">
                                            <button id="js-saved-daily-plan-btn" class="default-btn"><i class="ri-refresh-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Energy</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="" id="js-saved-daily-diet-plan-energy-input" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Fluid</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" id="js-saved-daily-diet-plan-fluid-input" class="form-control">
                                        </div>
                                        <div class="col-sm-1">
                                            <a><i class="ri-calculator-line"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="res-table savediet-table">
                                        <table class="table-striped table-hover table-bordered table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="tittle-th">Route</th>
                                                    <th class="tittle-th">Type</th>
                                                    <th class="tittle-th">Therapeutic Need</th>
                                                    <th class="tittle-th">Item</th>

                                                    <th class="tittle-th">Dose</th>
                                                    <th class="tittle-th">Energy</th>
                                                    <th class="tittle-th">Fluid</th>
                                                    <th class="tittle-th">Status</th>
                                                    <th class="tittle-th">Fluid Restriction</th>
                                                    <th class="tittle-th">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody class="js-diet-planning-continued-tbody"></tbody>
                                        </table>
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
<!-- Out Fluid modal -->
<div class="modal" id="js-inout-change-dose-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Dose</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Dose/Rate</label>
                        <input type="text" id="js-inout-change-dose-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-inout-change-dose-btn-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    function dietPlannings(){
      var patientid = $(this).attr('patientID');
      $('#dietPlan').modal('show');
  }

  $('.moveto').on('click', function(){
    var patientid = $(this).attr("data-patientID");
    var name = $(this).attr("data-patient_name");
    var sex = $(this).attr("data-sex");
    var encounter = $(this).attr("data-encounter_id");
    $('#patient_name').val(name);
    $('#patient_sex').val(sex);
    $('#encounter_id').val(encounter);
    $('#dietPlan').modal('show');
    get_diet_planning_type();
});
    /*
 * Diet planning
 */
 $(function () {
    var dateToday = new Date();
    $('.dietitian_followup_date').datepicker({
        minDate: dateToday,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "1600:2032",
        onClose: function (selectedDate) {
            var encounter = $(this).closest('.table-row-encounter').find('.encounteval').val();
            // alert(encounter);
            $.ajax({
                data: {date: selectedDate, encounter:encounter},
                url: baseUrl + '/dietitian/saveDietitianFollowupDate',
                type: "POST",
                success: function (response) {

                    showAlert(response.message);

                }

            })
        }
    });
});

// $('[data-encouteriD]').each(function() {

//      var encounter = $(this).data("encouteriD");

//     $(this).datepicker({
//         minDate: dateToday,
//         changeMonth: true,
//         changeYear: true,
//         dateFormat: 'yy-mm-dd',
//         yearRange: "1600:2032",
//         onClose: function (selectedDate) {

//             alert(encounter);
//             $.ajax({
//                 data: {date: selectedDate, encounter:encounter},
//                 url: baseUrl + '/dietitian/saveDietitianFollowupDate',
//                 type: "POST",
//                 success: function (response) {

//                 showAlert(response.message);

//                 }

//             })
//         }
//     });
// });
// get select option for diet planning type
function get_diet_planning_type() {
    $.ajax({
        url: baseUrl + '/dietitian/getTypeData',
        type: "GET",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldfoodtype + '">' + option.fldfoodtype + '</option>';
            });
            $('#js-input-type-planned').empty().append(optionData);
            $('#js-intake-type-select').empty().append(optionData);
        }
    });
}

// list data for diet planning
function get_diet_planning_planned_data(date, status) {
    $.ajax({
        url: baseUrl + '/dietitian/getDiets',
        type: "GET",
        data: {date: date, status: status, encounter:$('#encounter_id').val()},
        dataType: "json",
        success: function (data) {
            var trData = '';
            var totalFluid = 0;
            var totalEnergy = 0;
            $.each(data, function(i, val) {
                totalFluid += val.fldfluid;
                totalEnergy += val.fldenergy;

                trData += '<tr data-id="' + val.fldid + '">';
                trData += '<td>' + val.feedingroute + '</td>';
                trData += '<td>' + val.type + '</td>';
                trData += '<td>' + val.therapeuticneed + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td>';
                trData += '<td>' + val.fldenergy + '</td>';
                trData += '<td>' + val.fldfluid + '</td>';
                trData += '<td>' + val.status + '</td>';
                trData += '<td>' + val.fluidrestriction + '</td>';
                // trData += '<td flddosetime="' + val.flddosetime + '">' + val.time + '</td>';
                trData += '<td class="js-diet-plan-delete" data-status="' + status + '" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td></tr>';
                // trData += '<td>' + val.status + '</td></tr>';
            });

            var classElem = (status === 'Planned') ? '#js-diet-planning-planned-tbody' : '.js-diet-planning-continued-tbody';
            if (status == 'Continue') {
                $('#js-saved-daily-diet-plan-energy-input').val(totalEnergy);
                $('#js-saved-daily-diet-plan-fluid-input').val(totalFluid);
            }
            $(classElem).empty().html(trData);
        }
    });
}

// Delete diet plan data
$(document).on('click', '.js-diet-plan-delete', function() {
    var currentElem = $(this);
    $.ajax({
        url: baseUrl + '/dietitian/deleteDiet',
        type: "POST",
        data: {fldid: $(this).data('fldid'), status: $(this).data('status')},
        dataType: "json",
        success: function (response) {
            if (response.status) {
                $(currentElem).closest('tr').remove();
            }
            showAlert(response.message);
        }
    });
});

// Firsttab : On refresh click
$('#js-new-daily-plan-btn').click(function() {
    var date = $('#js-input-date-planned').val() || '';
    if (date == '')
        alert('Please select date ');
    else
        get_diet_planning_planned_data($('#js-input-date-planned').val(), 'Planned');
});

function getPlannedOptions(typeSelectorId, planedSelectorId) {
    $.ajax({
        url: baseUrl + '/dietitian/getTypeItems',
        type: "GET",
        data: {
            type: $(typeSelectorId).val(),
        },
        dataType: "json",
        success: function (response) {
            var optionData = '<option value="">-- Select --</option>';
            $.each(response, function(i, option) {
                optionData += '<option value="' + option.fldfoodid + '" data-fldfluid="' + option.fldfluid + '" data-fldenergy="' + option.fldenergy + '">' + option.fldfoodid + '</option>';
            });
            $(planedSelectorId).empty().append(optionData);
        }
    });
}

// get item on type change
$('#js-input-type-planned').change(function() {
    getPlannedOptions('#js-input-type-planned', '#js-input-item-planned');
});

$('#js-intake-type-select').change(function() {
    getPlannedOptions('#js-intake-type-select', '#js-intake-item-select');
});

$('#js-intake-update-button').click(function() {
    $.ajax({
        url: baseUrl + '/dietitian/addDailyDietPlan',
        type: "POST",
        data: {
            type: $('#js-intake-type-select').val(),
            item: $('#js-intake-item-select').val(),
            dose: $('#js-input-dose-planned').val(),
            feedingroute: $('#js-input-type-feeding-route').val(),
            fluidrestriction: $('#fluid_restriction').val(),
            therapeuticneed: $('#js-input-type-therapeutic-route').val(),
            energy: $('#js-input-energy-planned').val(),
            fluid: $('#js-input-fluid-planned').val(),
            frequency: $('#js-input-duration-planned').val(),
            status: 'Completed',
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var trData = '<tr data-id="' + val.fldid + '">';

                trData += '<td>' + val.feedingroute + '</td>';
                trData += '<td>' + val.type + '</td>';
                trData += '<td>' + val.therapeuticneed + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td>';
                trData += '<td>' + val.energy + '</td>';
                trData += '<td>' + val.fluid + '</td>';
                trData += '<td>' + val.status + '</td>';
                trData += '<td>' + val.fluidrestriction + '</td>';
                trData += '<td class="js-diet-plan-delete" data-status="' + status + '" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td></tr>';
                $('#js-intake-table-tbody').append(trData);
            }
            alershowAlertt(response.message);
        }
    });
});

$(document).on('change', '#js-input-item-planned', function() {
    $('#js-input-fluid-planned').val($('#js-input-item-planned option:selected').data('fldfluid'));
    $('#js-input-energy-planned').val($('#js-input-item-planned option:selected').data('fldenergy'));
});

// add diet_planning [status: planned]
$('#js-add-diet-planning-btn').click(function() {
    var pdvalue = $('#js-input-prescribed-diet').val();
    var pdunit = $('#js-input-prescribed-diet-unit').val();
    $.ajax({
        url: baseUrl + '/dietitian/addDailyDietPlan',
        type: "POST",
        data: {
            date:$('#js-input-date-planned').val(),
            time: $('#js-input-time-planned').val(),
            type: $('#js-input-type-planned').val(),
            item: $('#js-input-item-planned').val(),
            dose: $('#js-input-dose-planned').val(),
            feedingroute: $('#js-input-type-feeding-route').val(),
            fluidrestriction: $('#fluid_restriction').val(),
            therapeuticneed: $('#js-input-type-therapeutic-route').val(),
            otherrecommendation: $('#js-input-other-recommendation').val(),
            recommendeddiet: $('#js-input-recommended-diet').val(),
            foodsupplement: $('#js-input-food-supplement').val(),
            anyrestriction: $('#js-input-restriction').val(),
            extradiet: $('#js-input-extra-diet').val(),
            prescribeddiet: pdvalue+' '+pdunit,
            energy: $('#js-input-energy-planned').val(),
            fluid: $('#js-input-fluid-planned').val(),
            frequency: $('#js-input-duration-planned').val(),
            encounter: $('#encounter_id').val(),
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                var val = response.data;
                var trData = '<tr data-id="' + val.fldid + '">';
                trData += '<td>' + val.feedingroute + '</td>';
                trData += '<td>' + val.type + '</td>';
                trData += '<td>' + val.therapeuticneed + '</td>';
                trData += '<td>' + val.particulars + '</td>';
                trData += '<td>' + val.dose + '</td>';
                trData += '<td>' + val.energy + '</td>';
                trData += '<td>' + val.fluid + '</td>';
                trData += '<td>' + val.status + '</td>';
                trData += '<td>' + val.fluidrestriction + '</td>';
                trData += '<td class="js-diet-plan-delete" data-status="' + status + '" data-fldid="' + val.fldid + '"><i class="ri-delete-bin-5-fill"></i></td></tr>';
                $('#js-diet-planning-planned-tbody').append(trData);
            }
            showAlert(response.message);
        }
    });
});

// save diet_planning [status: continued]
$('#js-save-diet-planning-btn').click(function() {
    var fldids = $.map($('#js-diet-planning-planned-tbody tr'), function(e) {
        return $(e).data('id');
    });

    $.ajax({
        url: baseUrl + '/dietitian/saveDailyDietPlan',
        type: "POST",
        data: {fldids: fldids},
        dataType: "json",
        success: function (response) {
            if (response.status)
                $('#js-diet-planning-planned-tbody').empty();

            showAlert(response.message);
        }
    });
});

var currentElem = '';
$(document).on('click', '#js-diet-planning-planned-tbody tr', function () {
    selected_td('#js-diet-planning-planned-tbody tr', this);
});

$(document).on('click', '#js-diet-planning-planned-tbody tr td:nth-child(4)', function() {
    $('#js-diet-change-date-modal').modal('show');
    var dateTIme = $(this).attr('flddosetime').split(' ');
    $('#js-diet-change-date-input').val(dateTIme[0]);
    $('#js-diet-change-time-input').val(dateTIme[1]);

    currentElem = this;
});

$('#js-diet-change-date-btn').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    var date = $('#js-diet-change-date-input').val();
    var time = $('#js-diet-change-time-input').val();

    if (fldid != '') {
        var value = date + ' ' + time;
        $.ajax({
            url: baseUrl + '/dietitian/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'flddosetime',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $(currentElem).text(time);
                    $(currentElem).attr('flddosetime', value);
                }
                showAlert(response.message);
                $('#js-diet-change-date-modal').modal('hide');
            }
        });
    }
});

$(document).on('click', '#js-diet-planning-planned-tbody td:nth-child(3)', function() {
    $('#js-inout-change-dose-modal').modal('show');
    $('#js-inout-change-dose-input').val($(this).text().trim());

    currentElem = this;
});

$('#js-inout-change-dose-btn-modal').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    if (fldid != '') {
        var value = $('#js-inout-change-dose-input').val();
        $.ajax({
            url: baseUrl + '/dietitian/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'flddose',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status)
                    $(currentElem).text(value);

                showAlert(response.message);
                $('#js-inout-change-dose-modal').modal('hide');
            }
        });
    }
});

$(document).on('click', '#js-diet-planning-planned-tbody td:nth-child(6)', function() {
    $('#js-inout-status-modal').modal('show');
    $('#js-inout-status-select option').attr('selected', false);
    $('#js-inout-status-select option[value="' + $(this).text().trim() + '"]').attr('selected', false);

    currentElem = this;
});

$('#js-inout-status-save-modal').click(function() {
    var fldid = $('#js-diet-planning-planned-tbody tr[is_selected="yes"]').data('id') || '';
    if (fldid != '') {
        var value = $('#js-inout-status-select').val();
        $.ajax({
            url: baseUrl + '/dietitian/updateExtraDosing',
            type: "POST",
            data: {
                fldid: fldid,
                field: 'fldstatus',
                value : value,
            },
            dataType: "json",
            success: function (response) {
                if (response.status)
                    $(currentElem).text(value);

                showAlert(response.message);
                $('#js-inout-status-modal').modal('hide');
            }
        });
    }
});


// Second tab : On refresh click
$('#js-saved-daily-plan-btn').click(function() {
    get_diet_planning_planned_data($('#js-input-date-saved').val(), 'Continue');
});
$('a[href="#dietsaved"]').click(function() {
    get_diet_planning_planned_data($('#js-input-date-saved').val(), 'Continue');
});

$('div.accordion-nav ul li a[data-target="#inout"]').click(function() {
    get_out_fluid_select_data();
    get_in_out_list_data();

    get_diet_planning_type();
});
</script>

@endsection
