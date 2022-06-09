@extends('frontend.layouts.master')
@section('content')
{{--navbar--}} {{--@include('menu::common.nav-bar')--}} {{--end navbar--}}
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">
                     Dietitian Report
                  </h4>
               </div>
               <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
      </div>
      <div class="col-sm-12" id="myDIV">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <form action="" id="dietitianForm">
                  <div class="row">
                     <div class="col-sm-3">
                        <div class="form-group form-row align-items-center er-input">
                           <label for="" class="col-sm-3">Form:</label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" id="from_date" autocomplete="off">
                              <input type="hidden" name="from_date" id="from_date_eng">
                           </div>
                        </div>


                     </div>
                     <div class="col-sm-3">
                       <div class="form-group form-row align-items-center er-input">
                           <label for="" class="col-sm-3">To:</label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" id="to_date" autocomplete="off">
                              <input type="hidden" name="to_date" id="to_date_eng">
                           </div>
                        </div>
                     </div>
                   </div>
                   <div class="row">
                     <div class="col-sm-3">
                       <div class="form-group form-row align-items-center er-input">
                           <label for="" class="col-sm-3">Report Type:</label>
                           <div class="col-sm-9">
                              <select name="report_type" id="report_type" class="form-control form-control-sm">
                                 <option value="%">%</option>
                                 <option value="kitchen_report">Kitchen Attendance Report</option>
                                 <!-- <option value="extra_diet">Extra Diet</option> -->
                                 <option value="special_diet">Special Diet</option>
                                 <option value="diet_sheet">Diet Sheet</option>
                                 <option value="child_ward">Child Ward</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3" id="department_ward" style="display: none;">
                       <div class="form-group form-row align-items-center er-input">
                           <label for="" class="col-sm-3">Ward :</label>
                           <div class="col-sm-9">
                              <select name="ward" id="ward" class="form-control form-control-sm">
                                 <option value="%">%</option>
                                 @if(isset($departments) and count($departments) > 0)
                                    @foreach($departments as $d)
                                        <option value="{{$d->fldcateg}}">{{$d->fldcateg}}</option>
                                    @endforeach
                                 @endif
                              </select>
                           </div>
                        </div>
                     </div>

                     <div class="col-sm-3 ">

                       <!--  <a href="#" class="btn btn-primary  rounded-pill" type="button" onclick="showExaminationResult()"> <i class="fa fa-search"></i>&nbsp;Search</a> -->
                        <a href="javascript:void(0);" class="btn btn-warning rounded-pill" type="button" onclick="exportDietReport()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
                     </div>
                  </div>
               </form>
            </div>
        </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card">
            <div class="iq-card-body">
               <div class="tab-content" id="myTabContent-2">
                  <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                     <div class="table-responsive table-container">
                        <table class="table table-striped table-hover table-bordered ">
                           <thead class="thead-light">
                              <tr>
                                 <th>Index</th>
                                 <th>EncID</th>
                                 <th width="300">Name</th>
                                 <th>Age</th>
                                 <th>Gender</th>
                                 <th>PatientNo</th>
                                 <th>DateTime</th>
                                 <th>Location</th>
                                 <th>Observation</th>
                                 <th>Flag</th>
                              </tr>
                           </thead>
                           <tbody id="diagnostic_examination_data"></tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@push('after-script')
<script type="text/javascript">

   $('#to_date').nepaliDatePicker({
       npdMonth: true,
       npdYear: true,
       // npdYearCount: 10, // Options | Number of years to show
       onChange: function () {
        var nepalitd = $('#to_date').val();
         $('#to_date_eng').val(BS2AD(nepalitd));
      }
   });


   $('#from_date').nepaliDatePicker({
       npdMonth: true,
       npdYear: true,
       // npdYearCount: 10, // Options | Number of years to show
       onChange: function () {
        var nepalifd = $('#from_date').val();
         $('#from_date_eng').val(BS2AD(nepalifd));
      }
   });

  function exportDietReport() {
       // alert(baseUrl);
       $('form').submit(false);
       data = $('#dietitianForm').serialize();
       // alert(data);
       var urlReport = baseUrl + "/dietitian/export-dietitian-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


       window.open(urlReport, '_blank');
   }

   $('#report_type').on('change', function(){
      var report = $(this).val();
      if(report == 'diet_sheet'){
        $('#department_ward').show();
        $('#ward').prop('required', true);
      }else{
        $('#department_ward').hide();
        $('#ward').prop('required', false);
      }
   })
</script>
@endpush
