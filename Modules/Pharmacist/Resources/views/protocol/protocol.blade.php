@extends('frontend.layouts.master')
@push('after-styles')
@endpush
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">
                     Medicine Grouping
                  </h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="form-group form-row align-items-center er-input">
                  <label for="">Group Name:</label>
                  <div class="col-sm-4">
                     @php
                     $medgroups = \App\Utils\Pharmacisthelpers::getAllMedGroups();
                     @endphp
                     <select name="type" class="form-control select2medgroup" id="fldmedgroup">
                        <option value=""></option>
                        @foreach($medgroups as $medgroup)
                        <option value="{{ $medgroup->fldmedgroup }}" data-id="{{ $medgroup->fldid }}" {{ (old('flddosageform') == $medgroup->fldmedgroup) ? 'selected' : ''}}>{{ $medgroup->fldmedgroup }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-sm-6">
                     <a href="javascript:void(0)" id="loadmedicinegrouping" class="btn btn-action btn-primary"><i class="fas fa-sync"></i></a>&nbsp;
                     <a href="javascript:void(0)"  class="btn btn-action btn-primary" data-toggle="modal" data-target="#med_group_modal"><i class="fa fa-plus"></i></a>&nbsp;
                     @include('pharmacist::layouts.modal.medicalgroup')
                      <a href="{{ route('pharmacist.protocols.list') }}" class="btn btn-action btn-primary" target="_blank"><i class="fa fa-list"></i>&nbsp; List</a>&nbsp;
                     <a href="javascript:void(0)" id="exportmedicineMedgroup" class="btn btn-action btn-warning"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;&nbsp;Export</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">
                     Medicines
                  </h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="row">
                  <div class="col-lg-5 col-md-6">
                     <div class="form-group form-row">
                        <label class="col-4">Components:</label>
                        <div class="col-sm-8">
                           <select class="form-control fldroute" required id="fldroute">
                              <option value=""></option>
                              <option value="anal/vaginal" >anal/vaginal</option>
                              <option value="extra" >extra</option>
                              <option value="eye/ear" >eye/ear</option>
                              <option value="fluid" >fluid</option>
                              <option value="injection" >injection</option>
                              <option value="liquid" >liquid</option>
                              <option value="msurg" >msurg</option>
                              <option value="oral" >oral</option>
                              <option value="ortho" >ortho</option>
                              <option value="resp" >resp</option>
                              <option value="suture" >suture</option>
                              <option value="topical" >topical</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group form-row">
                        <label class="col-4">Dose</label>
                        <div class="col-sm-4">
                           <input type="number" step="any" min="0" name="flddose" id="flddose" placeholder="0" class="form-control">
                        </div>
                        <div class="col-sm-4">
                           <select class="form-control" name="flddoseunit" id="flddoseunit">
                              <option value=""></option>
                              <option value="mg" >mg</option>
                              <option value="mg/kg" >mg/kg</option>
                              <option value="mg/sqm" >mg/sqm</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="form-group form-row">
                        <label class="col-3">Medicine:</label>
                        <div class="col-sm-9">
                           <select class="form-control select2medicinelist" name="fldbrandid" id="flditem">
                           </select>
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-3">Days:</label>
                        <div class="col-sm-3">
                           <input type="number" min="0" name="fldday" id="fldday" placeholder="0" class="form-control">
                        </div>
                         <label for="" class="col-sm-2">Qty:</label>
                        <div class="col-sm-4">
                           <input type="number" step="any" min="0" name="fldqty" id="fldqty" placeholder="0" class="form-control">
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-3 col-md-6">
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-5 col-lg-4">Start Hour:</label>
                        <div class="col-sm-7 col-lg-8">
                           <input type="number" step="any" min="0" name="fldstart" id="fldstart" class="form-control">
                        </div>
                     </div>
                      <div class="form-group form-row">
                        <label class="col-sm-5 col-lg-4">Frequency:</label>
                        <div class="col-sm-7 col-lg-8">
                           <select name="fldfreq" id="fldfreq" class="form-control">
                              <option value=""></option>
                              <option value="1" >1</option>
                              <option value="2" >2</option>
                              <option value="3" >3</option>
                              <option value="4" >4</option>
                              <option value="6" >6</option>
                              <option value="PRN" >PRN</option>
                              <option value="SOS" >SOS</option>
                              <option value="stat" >stat</option>
                              <option value="AM" >AM</option>
                              <option value="HS" >HS</option>
                              <option value="pre" >pre</option>
                              <option value="Post">Post</option>
                              <option value="Hourly">Hourly</option>
                              <option value="Alt day">Alt day</option>
                              <option value="Weekly">Weekly</option>
                              <option value="Biweekly">Biweekly</option>
                              <option value="Triweekly">Triweekly</option>
                              <option value="Monthly">Monthly</option>
                              <option value="Yearly">Yearly</option>
                              <option value="Tapering">Tapering</option>
                           </select>
                        </div>
                     </div>
                     <!-- <a href="#" class="btn btn-info rounded-pill" type="button"> <i class="fa fa-check"></i>&nbsp;Save</a> -->

                  </div>
               </div>
                <div class="d-flex justify-content-center mt-3">
                   <a href="javascript:void(0)" class="btn btn-primary btn-action float-right rounded-pill " id="savebuton"><i class="fa fa-check"></i>&nbsp; Save</a>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <div class="table-responsive table-container">
                  <table class="table table-sm table-bordered " id="medicinetable">
                  </table>
                  <div id="bottom_anchor"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(function() {

       function select2loading() {
           setTimeout(function() {
               $('.select2medgroup').select2({
                   placeholder : 'select group'
               });

               $('.select2medicinelist').select2({
                   placeholder : 'select medicine'
               });
           }, 4000);
       }

       select2loading();

       $('#dosageaddbutton').click(function() {
           var dosagename = $('#dosageformfield').val();

           if(dosagename != '') {
               $.ajax({
                   type : 'post',
                   url  : '{{ route('pharmacist.protocols.addmedgroup') }}',
                   dataType : 'json',
                   data : {
                       '_token': '{{ csrf_token() }}',
                       'fldmedgroup': dosagename,
                   },
                   success: function (res) {
                       showAlert(res.message);
                       if(res.message == 'Med Group added successfully.') {
                           $('#dosageformfield').val('');
                           var deleteroutename = "{{ url('/pharmacist/protocols/deletemedgroup') }}/"+res.fldid;
                           $('#dosagelistingmodal').append('<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.fldmedgroup+'</li>');
                           $('.select2medgroup').append('<option value="'+res.fldmedgroup+'" data-id="'+res.fldid+'">'+res.fldmedgroup+'</option>');
                           select2loading();
                       }

                   }
               });
           } else {
               alert('Dosage Form Name is required');
           }
       });

       // selecting category item
       $('#dosagelistingmodal').on('click', '.dosage_item', function() {
           $('#dosagetobedeletedroute').val($(this).data('href'));
           $('#dosageidtobedeleted').val($(this).data('id'));
       });

       // deleting selected category item
       $('#dosagedeletebutton').click(function() {
           var deletedosageroute = $('#dosagetobedeletedroute').val();
           var dosageidtobedeleted = $('#dosageidtobedeleted').val();

           if(deletedosageroute == '') {
               alert('no Med Group selected, please select the Group.');
           }

           if(deletedosageroute != '') {
               var really = confirm("You really want to delete this Group?");
               if(!really) {
                   return false
               } else {
                   $.ajax({
                       type : 'delete',
                       url : deletedosageroute,
                       dataType: 'json',
                       data : {
                           '_token': '{{ csrf_token() }}',
                       },
                       success: function (res) {
                           showAlert(res.message);
                           if(res.message == 'Med Group Deleted Successfully.') {
                               $("#dosagelistingmodal").find(`[data-href='${deletedosageroute}']`).parent().remove();
                               $(".select2medgroup").find(`[data-id='${dosageidtobedeleted}']`).remove();
                               $('#dosagetobedeletedroute').val('');
                               $('#categoryidtobedeleted').val('');
                               select2loading();
                           }
                       }
                   });
               }
           }
       });

       $('#fldroute').change(function() {
           var fldroute = $(this).val();

           $.ajax({
               type: 'post',
               url: '{{ route('pharmacist.protocols.getmedicinesfromfldroute') }}',
               dataType: 'json',
               data: {
                   '_token': '{{ csrf_token() }}',
                   'fldroute': fldroute,
               },
               success: function(res) {
                   if(res.message == 'error'){
                       showAlert(res.messagedetail);
                   } else if(res.message == 'success') {
                       $('.select2medicinelist').html(res.html);

                       select2loading();
                   }
               }
           });
       });

       $('#savebuton').click(function() {
           var fldmedgroup = $("#fldmedgroup").val();
           var fldroute = $("#fldroute").val();
           var flditem = $("#flditem").val();
           var flddose = $("#flddose").val();
           var flddoseunit = $("#flddoseunit").val();
           var fldfreq = $("#fldfreq").val();
           var fldqty = $('#fldqty').val();
           var fldday = $('#fldday').val();
           var fldstart = $('#fldstart').val();

           if(fldmedgroup == '') {
               alert('Group Name is required');
               return false;
           }

           if(fldroute == '') {
               alert('Component is required');
               return false;

           }

           if(flditem == '') {
               alert('Medicine is required');
               return false;
           }

           if(flddose == '') {
               alert('Dose is required');
               return false;
           }

           if(flddoseunit == '') {
               alert('Dose unit is required');
               return false;
           }

           if(fldday == '') {
               alert('Day is required');
               return false;
           }

           if(fldfreq == '') {
               alert('frequency is required');
               return false;
           }
           fldday
           if(fldqty == '') {
               alert('Days is required');
               return false;
           }

           if(fldstart == '') {
               alert('Start is required');
               return false;
           }

           $.ajax({
               type : 'post',
               url: '{{ route('pharmacist.protocols.addproductgroup') }}',
               dataType: 'json',
               data : {
                   '_token': '{{ csrf_token() }}',
                   'fldmedgroup': fldmedgroup,
                   'fldroute': fldroute,
                   'flditem': flditem,
                   'flddose': flddose,
                   'flddoseunit': flddoseunit,
                   'fldfreq': fldfreq,
                   'fldqty': fldqty,
                   'fldday': fldday,
                   'fldstart': fldstart
               },
               success: function(res) {
                   if(res.message == 'error'){
                       showAlert(res.errormessage);
                   } else if(res.message == 'success') {
                       showAlert(res.successmessage);
                       $('#medicinetable').html(res.html);
                       $("#fldroute").val('');
                       $("#flditem").val('');
                       $("#flddose").val('');
                       $("#flddoseunit").val('');
                       $("#fldfreq").val('');
                       $('#fldqty').val('');
                       $('#fldday').val('');
                       $('#fldstart').val('');
                       $(".select2medicinelist").html('<option value=""></option>');
                       select2loading();
                   }
               }
           });

       });

       $('#loadmedicinegrouping').click(function() {
           var fldmedgroup = $('#fldmedgroup').val();
           $.ajax({
               type : 'post',
               url: '{{ route('pharmacist.protocols.loadmedicinegrouping') }}',
               dataType: 'json',
               data : {
                   '_token': '{{ csrf_token() }}',
                   'fldmedgroup': fldmedgroup
               },
               success: function(res) {
                   if(res.message == 'error'){
                       showAlert(res.errormessage);
                   } else if(res.message == 'success') {
                       $('#medicinetable').html(res.html);
                       $("#fldroute").val('');
                       $("#flditem").val('');
                       $("#flddose").val('');
                       $("#flddoseunit").val('');
                       $("#fldfreq").val('');
                       $('#fldqty').val('');
                       $('#fldday').val('');
                       $('#fldstart').val('');
                       $(".select2medicinelist").html('<option value=""></option>');
                       select2loading();
                   }
               }
           });
       });

       $('#exportmedicineMedgroup').click(function() {
           var fldmedgroup = $('#fldmedgroup').val();

           if(fldmedgroup == '') {
               alert('please select Group Name.');

               return false;
           } else if(fldmedgroup != '') {
               var linkURL = "{{ url('/pharmacist/protocols/exportmedicinemedgroup') }}/"+fldmedgroup;
               window.location.href = linkURL;
           }
       })

       // deleting selected category item
       $('#medicinetable').on('click', '.deleteproductgroup', function() {
           var deleteproductgrouproute = $(this).data('href');
           console.log(deleteproductgrouproute);
           if(deleteproductgrouproute != '') {
               var really = confirm("You really want to delete this particular?");
               if(!really) {
                   return false
               } else {
                   $.ajax({
                       type : 'delete',
                       url : deleteproductgrouproute,
                       dataType: 'json',
                       data : {
                           '_token': '{{ csrf_token() }}',
                       },
                       success: function (res) {
                           if(res.message == 'error'){
                               showAlert(res.errormessage);
                           } else if(res.message == 'success') {
                               showAlert(res.successmessage);
                               $("#medicinetable").find(`[data-href='${deleteproductgrouproute}']`).parent().parent().remove();

                           }
                       }
                   });
               }
           }
       });

   })
</script>
@stop
