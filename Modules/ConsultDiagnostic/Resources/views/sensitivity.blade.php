@extends('frontend.layouts.master') @section('content')
{{--navbar--}}
@include('menu::common.nav-bar')
{{--end navbar--}}
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">
                     Diagnostic Report/Radiology Report
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
                  <div class="col-sm-3">
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-4 col-lg-3">To:</label>
                        <div class="col-sm-8 col-lg-9">
                           <input type="text" class="form-control" name="sensitivity_to_date" id="sensitivity_to_date" value="{{isset($date) ? $date : ''}}" autocomplete="off"/>
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-4 col-lg-3">From:</label>
                        <div class="col-sm-8 col-lg-9">
                           <input type="text" class="form-control" id="sensitivity_from_date" name="sensitivity_from_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-4 col-lg-3">Status:</label>
                        <div class="col-sm-8 col-lg-9">
                           <select name="sensitivity_status" class="form-control form-control-sm" id="sensitivity_status">
                              <option value="Sampled">Sampled</option>
                              <option value="Reported" selected="">Reported</option>
                              <option value="Verified">Verified</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-5 col-lg-4">Specimen:</label>
                        <div class="col-sm-7 col-lg-8">
                           <select name="sensitivity_specimen" id="sensitivity_specimen" class="form-control form-control-sm">
                              <option value="%">%</option>
                              @if(isset($specimen) and count($specimen) > 0)
                              @foreach($specimen as $s)
                              <option value="{{$s->fldsampletype}}">{{$s->fldsampletype}}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-5">Organism</label>
                        <div class="col-sm-7">
                           <select name="sensitivity_organism" id="sensitivity_organism" class="form-control form-control-sm">
                              <option value="%">%</option>
                              @if(isset($organism) and count($organism) > 0)
                              @foreach($organism as $o)
                              <option value="{{$o->fldsubtest}}">{{$o->fldsubtest}}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-5">Drug:</label>
                        <div class="col-sm-7">
                           <select name="sensitivity_drug" id="sensitivity_drug" class="form-control form-control-sm">
                              <option value="%">%</option>
                              @if(isset($drugs) and count($drugs) > 0)
                              @foreach($drugs as $d)
                              <option value="{{$d->flclass}}">{{$d->flclass}}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-3 p-0">
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-4 col-lg-4">Result:</label>
                        <div class="col-sm-7 col-lg-7">
                           <select name="sensitivity_result" id="sensitivity_result" class="form-control form-control-sm">
                              <option value="%">%</option>
                              <option value="Intermediate">Intermediate</option>
                              <option value="Resistant">Resistant</option>
                              <option value="Sensitive">Sensitive</option>
                              <option value="ValidValue">ValidValue</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <button class="btn btn-primary  rounded-pill" type="button" onclick="showSensitivityResult()"><i class="fa fa-sync"></i>&nbsp;Refresh</button>
                        <button class="btn btn-warning rounded-pill" type="button"><i class="fas fa-external-link-square-alt"></i>&nbsp;&nbsp;Export</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card">
            <div class="iq-card-body">
               <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gridview</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Chartview</a>
                  </li>
               </ul>
               <div class="tab-content" id="myTabContent-2">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                     <div class="table-responsive table-container">
                        <table class="table table-striped table-hover table-bordered ">
                           <thead class="thead-light">
                              <tr>
                                 <th class="tittle-th">DateTime</th>
                                 <th class="tittle-th">EncID</th>
                                 <th class="tittle-th" width="300">Name</th>
                                 <th class="tittle-th">Gender</th>
                                 <th class="tittle-th">Age</th>
                                 <th class="tittle-th">Specimen</th>
                                 <th class="tittle-th">Method</th>
                                 <th class="tittle-th">Growth</th>
                                 <th class="tittle-th">Antibiotics</th>
                                 <th class="tittle-th">Sensitivity</th>
                              </tr>
                           </thead>
                           <tbody></tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@push('after-script')
<script type="text/javascript">
   // $('#sensitivity_to_date').datetimepicker({

   //     changeMonth: true,
   //     changeYear: true,
   //     dateFormat: 'yy-mm-dd',
   //     yearRange: "1600:2032",

   // });
   // $('#sensitivity_from_date').datetimepicker({

   //     changeMonth: true,
   //     changeYear: true,
   //     dateFormat: 'yy-mm-dd',
   //     yearRange: "1600:2032",

   // });

   $('#sensitivity_from_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });
    $('#sensitivity_to_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });

   function showSensitivityResult(){
           // alert('here');
           var fdate = $('#from_date').val();
           var tdate = $('#to_date').val();

           var specimen = $('#sensitivity_specimen').val();
           var status = $('#sensitivity_status').val();
           var organism = $('#sensitivity_organism').val();
           var drug = $('#sensitivity_drug').val();
           var result = $('#sensitivity_result').val();

           $.ajax({
               url: '{{ route('search.sensitivity.form.diagnostic.consultant') }}',
               type: "POST",
               data: {fdate:fdate,tdate:tdate,specimen:specimen,status:status,organism:organism,drug:drug,result:result,"_token": "{{ csrf_token() }}"},
               success: function (response) {
                 $('#laboratory_data').html(response);
             },
             error: function (xhr, status, error) {
               var errorMessage = xhr.status + ': ' + xhr.statusText;
               console.log(xhr);
           }
       });
       }
</script>
@endpush
