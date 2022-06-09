@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Bed Occupancy</h4>
               </div>
            </div>
            <div class="iq-card-body">
               <form name="bedoccupany" method="post" id="bedOccupancyForm" action="{{route('submit.bed.form')}}">
                  @csrf
                  <div class="row">
                     <!-- <div class="col-sm-3">
                        <div class="form-group er-input">
                            <select name="door" id="dept-select" class="form-control">
                                <option value="%" {{ isset($requestData['door']) && $requestData['door'] == "%"?"selected":"" }}>%</option>
                                {{--<option value="Indoor" {{ isset($requestData['door']) && $requestData['door'] == "Indoor"?"selected":"" }}>Indoor</option>--}}

                                @foreach ($departments as $department)
                                    <option value="{{ $department->flddept }}" {{ isset($requestData['door']) && $requestData['door'] == $department->flddept?"selected":"" }}>{{ $department->flddept }}</option>
                                @endforeach
                            </select>
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
                        </div> -->
                     <div class="col-sm-3">
                        <div class="form-group form-row">
                           <input type="text" value="{{ isset($requestData['encounter_id'])?$requestData['encounter_id']:'' }}" name="encounter_id" id="encounter_id" class="form-control" placeholder="Enter encounter Id">
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div class="form-group form-row">
                           <input type="text" value="{{ isset($requestData['encounter_name'])?$requestData['encounter_name']:'' }}" name="encounter_name" id="encounter_name" class="form-control" placeholder="Enter Patient Name">
                        </div>
                     </div>
                     {{-- <div class="col-sm-3">
                        <div class="form-group">
                           <button class="btn btn-outline-primary" type="button" name="action" value="Refresh" onclick="refreshBedOccupancy()"><i class="fas fa-sync-alt"></i>&nbsp;Refresh</button> --}}
                           <!-- <button class="btn btn-primary" type="button" name="action" value="Report" onclick="submitReportBedOccupancy();"><i class="far fa-file-pdf"></i>&nbsp;Report</button>
                              <button class="btn btn-primary" type="button" name="action" value="Progress" onclick="submitReportBedOccupancyProgress();"><i class="far fa-file-pdf"></i>&nbsp;Progress</button>
                              <button class="btn btn-primary" type="button" name="action" value="All" onclick="submitReportBedOccupancyAll();"><i class="far fa-file-pdf"></i>&nbsp;All</button> -->
                           {{-- <button class="btn btn-primary" type="button" name="action" value="search" onclick=""><i class="fas fa-search"></i>&nbsp;Search</button>
                        </div>
                     </div> --}}
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         @if($details)
         @foreach($details as $k => $detail)
         @if(in_array($k,$user_department))
         <div id="accordion">
            <div class="iq-card">
               <div class="iq-card-header p-2" >
                  <h6 class="mb-0">
                     <button class="btn text-primary  btn-accordion font-bed" data-toggle="collapse" data-target="#collapseOne{{str_replace(' ', '', $k)}}" aria-expanded="true" aria-controls="collapseOne" >
                     {{  $k }} &nbsp;<small>(Total Bed: {{\App\Utils\Helpers::getBedCountByDepartment($k)}} | Avaiable Bed: {{\App\Utils\Helpers::getavailablebed($k)}})</small>
                     </button>
                  </h6>
               </div>
               <div id="collapseOne{{str_replace(' ', '', $k)}}" class="collapse" aria-labelledby="headingOne{{str_replace(' ', '', $k)}}" data-parent="#accordion">
                  <div class="form-group p-2">
                     <div id="table" class="bedoccupancy-table">
                        <table class="table table-bordered table-hover table-striped text-center">
                           <thead class="thead-light">
                              <tr>
                                 <th>S.N</th>
                                 <th>Bed</th>
                                 <th>EncID</th>
                                 <th>Patient Details</th>
                                 <th>Admission By</th>
                                 <th>Admission TIme</th>
                                 <th>Guardian</th>
                                 <th>Diagnosis</th>
                                 <th>Consult</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                            <tbody class="tablebody">
                              @if(isset($detail))
                              @php
                              $count = 1;
                              @endphp
                              @foreach($detail as $key => $en)
                              <tr data-encid="{{$en['encounter_id']}}" data-name="{{$en['name']}}">
                                 <td>{{$count++}}</td>
                                 <td>{{$en['fldbed']}}</td>
                                 <td>{{$en['encounter_id']}}</td>
                                 <td>
                                    <span> {{$en['name']}},</span><br>
                                    <span>
                                    {{$en['agesex']}} &nbsp;<i class="ri-phone-fill"></i>&nbsp;{{$en['fldptcontact']}}&nbsp;,
                                    </span><br>
                                    <span>
                                    <i class="ri-map-pin-line"></i>&nbsp;{{ $en['fldptaddvill'] }}, {{ $en['fldmunicipality']}}&nbsp;
                                    </span>
                                 </td>
                                 <td>{{$en['fldptguardian']}}</td>
                                 <td><i class="ri-time-line"></i>&nbsp;<em></em></td>
                                 <td>
                                    <span></span><br>
                                    <span>
                                    <i class="ri-phone-fill"></i>&nbsp;
                                    </span>
                                 </td>
                                 <td>{{$en['dignosis']}},</td>
                                 <td>{{$en['consult']}}</td>
                                 <td>
                                    <div class="btn-group">
                                       <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Action
                                       </button>
                                       <div class="dropdown-menu menu-bedaction">
                                          <a class="dropdown-item drugdosing" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Drug Dosing</a>
                                          <a class="dropdown-item fluidoutput" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Fluid Output</a>
                                          <a class="dropdown-item inpatient" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;In-Patient</a>
                                          <a class="dropdown-item delivery" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Delivery</a>
                                          <a class="dropdown-item erform" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;ER Form</a>
                                          <a class="dropdown-item majorprocedure" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Major Procedure Form</a>
                                          <a class="dropdown-item laboratory" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Laboratory</a>
                                          <a class="dropdown-item radiology" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Radiology</a>
                                          <a class="dropdown-item pharmacy" encounter_id="{{$en['encounter_id']}}" target="_blank">&nbsp;Pharmacy</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              @endforeach
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endif
         @endforeach
         @endif
      </div>
   </div>
</div>
<!-- Out Fluid modal -->
<div class="modal" id="outFluid">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="file-modal-title">Enter Value</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <div class="form-row">
               <div class="col-md-6">
                  <input type="" name="" value="Output Fluid" disabled class="input-output-fluid" style="border:none;">
               </div>
               <div class="col-md-3">
                  <input type="hidden" id="passed_encounter_id" class="form-control" name="passed_encounter_id" value=""/>
                  <input type="text" id="js-quantative" value="0" class="form-control" style="width: 100%;">
               </div>
               <div class="col-md-3">
                  <button class="btn btn-primary btn-sm btn-action" id="js-out-fluid-save" style="width: 100%"><i class="fa fa-check"></i>&nbsp;Save</button>
               </div>
               <div class="col-md-12 mt-2">
                  <div class="pulse-nxt-full">
                     <div class="form-group">
                        <div class="form-group-inner custom-9 border">
                           <select id="js-out-fluid-option" class="form-control" multiple="" style="height: 203px;overflow: auto; border-none">
                           </select>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(".drugdosing").click(function () {
      var encounter_id = $(this).attr('encounter_id');
              globalEncounter = encounter_id;


              dosingRecord.displayModal(encounter_id);
              $('.patient_name_e').val($(this).attr('patient_name'));
              $('.patient_sex_e').val($(this).attr('sex'));
              $('#patientID').val($(this).attr('patientID'));

   });
   $(".fluidoutput").click(function () {
      var encounter_id = $(this).attr('encounter_id');
              globalEncounter = encounter_id;


              $('#passed_encounter_id').val(encounter_id);
              $('#outFluid').modal('show');
              $('.patient_name_e').val($(this).attr('patient_name'));
              $('.patient_sex_e').val($(this).attr('sex'));

   });
      $(".inpatient").click(function () {
          var encounter_id = $(this).attr('encounter_id');
              globalEncounter = encounter_id;
          url = '{{ route('inpatient') }}';
                      $.ajax({
                          url: '{{ route('setsessionbed') }}',
                          type: "POST",
                          data: {
                              encounter_id: encounter_id,
                              type: 'inpatient'
                          },
                          success: function (test) {
                              window.open(url);
                          }
                      });

      });
          $(".delivery").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          url = '{{ route('delivery') }}';
                          $.ajax({
                              url: '{{ route('setsessionbed') }}',
                              type: "POST",

                              data: {
                                  encounter_id: encounter_id,
                                  type: 'delivery'
                              },
                              success: function (test) {
                                  window.open(url);
                              }
                          });

          });
          $(".erform").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          url = '{{ route('emergency') }}';
                          $.ajax({
                              url: '{{ route('setsessionbed') }}',
                              type: "POST",

                              data: {
                                  encounter_id: encounter_id,
                                  type: 'emergency'
                              },
                              success: function (test) {
                                  window.open(url);
                              }
                          });

          });
          $(".majorprocedure").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          url = '{{ route('majorprocedure') }}';
                          $.ajax({
                              url: '{{ route('setsessionbed') }}',
                              type: "POST",

                              data: {
                                  encounter_id: encounter_id,
                                  type: 'major_procedure'
                              },
                              success: function (test) {
                                  window.open(url);
                              }
                          });

          });
          $(".laboratory").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          laboratory.displayModal(encounter_id);
          });
          $(".radiology").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          radiology.displayModal(encounter_id)

          });
          $(".pharmacy").click(function () {
              var encounter_id = $(this).attr('encounter_id');
                          pharmacy.displayModal(encounter_id)
          });
      // $(".moveto").click(function () {
      //     // alert('asdf')
      //     var act = $(".act option:selected").val();
      //     var encounter_id = $(this).attr('encounter_id');

      //     if (act == 'In-Patient') {

      //     }

      //     if (act == 'Essential Exam') {
      //         var encounter_id = $(this).attr('encounter_id');
      //         globalEncounter = encounter_id;

      //         essenseExam.displayModal(encounter_id)
      //         $('.patient_name_e').val($(this).attr('patient_name'));
      //         $('.patient_sex_e').val($(this).attr('sex'));

      //         //$("#foo").trigger( "click" );
      //     }

      //     if (act == 'Drug Dosing') {



      //         //$("#foo").trigger( "click" );
      //     }

      //     if (act == 'PO Intake') {
      //         //$("#foo").trigger( "click" );
      //     }

      //     if (act == 'Fluid Output') {



      //     }


      //     if (act == 'Delivery') {

      //     }

      //     if (act == 'ER') {

      //     }

      //     if (act == 'Major Procedure') {

      //     }


      //     if (act == 'ICU') {

      //     }


      //     if (act == 'Laboratory') {

      //     }


      //     if (act == 'Radiology') {

      //     }

      //     if (act == 'Pharmacy') {

      //     }


      // });


      // save data for out fluid
      $('#js-out-fluid-save').click(function () {
          var encounter_id = $(this).attr('encounter_id');
          $.ajax({
              url: baseUrl + '/inpatient/inout/saveOutFluid',
              type: "POST",
              data: {
                  encounter_id: encounter_id,
                  item: $('#js-out-fluid-option').val(),
                  quantative: $('#js-quantative').val()
              },
              dataType: "json",
              success: function (response) {
                  if (response.status) {

                      $('#outFluid').modal('hide');

                  }
                  showAlert(response.message);
              }
          });
      });

      function submitReportBedOccupancy() {
          if (checkSearchVariables() === false) {
              return false;
          }
          data = $('#bedOccupancyForm').serialize();
          // console.log(data);
          var urlReport = baseUrl + "/bedoccupancy?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
          window.open(urlReport, '_blank');
      }

      function submitReportBedOccupancyProgress() {
          if (checkSearchVariables() === false) {
              return false;
          }
          data = $('#bedOccupancyForm').serialize();
          // console.log(data);
          var urlReport = baseUrl + "/bedoccupancy?" + data + "&action=" + "Progress" + "&_token=" + "{{ csrf_token() }}";
          window.open(urlReport, '_blank');
      }

      function submitReportBedOccupancyAll() {
          if (checkSearchVariables() === false) {
              return false;
          }
          data = $('#bedOccupancyForm').serialize();
          // console.log(data);
          var urlReport = baseUrl + "/bedoccupancy?" + data + "&action=" + "All" + "&_token=" + "{{ csrf_token() }}";
          window.open(urlReport, '_blank');
      }

      function checkSearchVariables() {
          if ($('#dept-select').val() == "" && $('#dept-select').val() == "") {
              // if ($('#encounter_name').val() == "" && $('#encounter_id').val() == "") {
              showAlert('Department must not be empty', 'Error');
              return false;
          }
      }

      function refreshBedOccupancy() {
          if (checkSearchVariables() === false) {
              return false;
          }
          $('#bedOccupancyForm').submit();
      }

    $(document).on('keyup','#encounter_id',function(){
        var encid = $(this).val().toLowerCase();
        $.each($('.tablebody'), function(i, option) {
            var hasResult = false;
            $.each($(option).find('tr'), function(j, opt) {
                if ($(opt).data('encid').toLowerCase().indexOf(encid) >= 0){
                    $(opt).show();
                    hasResult = true;
                }else{
                    $(opt).hide();
                }
            });
            if(hasResult){
                // $(option).closest('.iq-card').find('.font-bed').click();
                $(option).closest('.iq-card').find('.font-bed').removeClass('collapsed');
                $(option).closest('.iq-card').find('.collapse').addClass('show');
            }else{
                $(option).closest('.iq-card').find('.font-bed').addClass('collapsed');
                $(option).closest('.iq-card').find('.collapse').removeClass('show');
            }
        });
    });

    $(document).on('keyup','#encounter_name',function(){
        var encname = $(this).val().toLowerCase();
        $.each($('.tablebody'), function(i, option) {
            var hasResult = false;
            $.each($(option).find('tr'), function(j, opt) {
                if ($(opt).data('name').toLowerCase().indexOf(encname) >= 0){
                    $(opt).show();
                    hasResult = true;
                }else{
                    $(opt).hide();
                }
            });
            if(hasResult){
                // $(option).closest('.iq-card').find('.font-bed').click();
                $(option).closest('.iq-card').find('.font-bed').removeClass('collapsed');
                $(option).closest('.iq-card').find('.collapse').addClass('show');
            }else{
                $(option).closest('.iq-card').find('.font-bed').addClass('collapsed');
                $(option).closest('.iq-card').find('.collapse').removeClass('show');
            }
        });
    });
</script>
@endsection
