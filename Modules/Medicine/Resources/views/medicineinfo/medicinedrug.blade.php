@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
 <div class="row">
  <div class="col-sm-12">
   <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-header d-flex justify-content-between">
      <div class="iq-header-title">
        <h4 class="card-title">List group</h4>
      </div>
    </div>
    <div class="iq-card-body">
      <div class="row">
        <div class="col-lg-4 col-md-12">
          <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
              <div class="iq-search-bar custom-search">
                <input type="text" class="text search-input" placeholder="Type here to search..." name="searchkeyword" id="searchmedicine"/>
                <!-- <a class="search-link" href="#"><i class="ri-search-line"></i></a> -->
                <!-- </form> -->
              </div>
              @include('medicine::layouts.includes.medicinelisting')
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-md-12">
          <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
              <form action="{{ route('medicines.medicineinfo.adddrug') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group form-row align-items-center">
                  <label for="" class="col-sm-3">Generic Name:</label>
                  @php $codes = \App\Utils\Medicinehelpers::getAllCodes(); @endphp
                  <div class="col-sm-8">
                    <select name="fldcodename" class="form-select-dietary form-control-sm select2genericname" required>
                      <option value=""></option>
                      @forelse($codes as $code)
                      <option value="{{ $code->fldcodename }}" data-id="{{ $code->fldcodename }}" {{ (old('fldcodename') && old('fldcodename') == $code->fldcodename) ? 'selected' : ''}}>{{ $code->fldcodename }}</option>
                      @empty
                      @endforelse
                    </select>
                  </div>
                  <div class="col-sm-1">
                    <!-- <button class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></button> -->
                    <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#code_modal"><i class="ri-add-fill"></i></a>
                    @include('medicine::layouts.modal.code')
                  </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                  <label for="" class="col-sm-2">Dosage Form:</label>
                  <div class="col-sm-4">
                    <select name="fldroute" class="form-control">
                      <option value=""></option>
                      <option value="anal/vaginal" {{ old('fldroute') == 'anal/vaginal' ? 'selected' : '' }}>anal/vaginal</option>
                      <option value="eye/ear" {{ old('fldroute') == 'eye/ear' ? 'selected' : '' }}>eye/ear</option>
                      <option value="fluid" {{ old('fldroute') == 'fluid' ? 'selected' : '' }}>fluid</option>
                      <option value="injection" {{ old('fldroute') == 'injection' ? 'selected' : '' }}>injection</option>
                      <option value="liquid" {{ old('fldroute') == 'liquid' ? 'selected' : '' }}>liquid</option>
                      <option value="oral" {{ old('fldroute') == 'oral' ? 'selected' : '' }}>oral</option>
                      <option value="resp" {{ old('fldroute') == 'resp' ? 'selected' : '' }}>resp</option>
                      <option value="topical" {{ old('fldroute') == 'topical' ? 'selected' : '' }}>topical</option>
                    </select>
                  </div>
                  <label for="" class="col-sm-2">Strength:</label>
                  <div class="col-sm-1">
                    <input type="number" step="any" min="0" name="fldstrength" value="{{ old('fldstrength') }}" placeholder="0" class="form-control" required>
                    <!-- <input type="text" name="" id="" class="form-control" placeholder="0" /> -->
                  </div>
                  <div class="col-sm-3">
                    <input type="text" name="fldstrunit" value="{{ old('fldstrunit') }}" placeholder="" class="form-control" size="13" required>
                    <!-- <input type="text" name="" id="" class="form-control" placeholder="0" /> -->
                  </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                  <label for="" class="col-sm-2">Min Age (yrs):</label>
                  <div class="col-sm-4">
                    <input type="number" step="any" min="0" name="fldciyear" value="{{ old('fldciyear') }}" placeholder="0" class="form-control">
                  </div>
                  <label for="" class="col-sm-2">Reference:</label>
                  <div class="col-sm-4">
                    <input type="text" name="fldreference" value="{{ old('fldreference') }}" placeholder="" size="20" class="form-control">
                  </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                  <label for="" class="col-sm-2">Compatability:</label>
                  <div class="col-sm-4">
                    <!-- <button class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></button>  -->
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm-in" data-toggle="modal" data-target=""><i class="ri-add-fill"></i></a>
                  </div>
                  <label class="col-sm-2">Help:</label>
                  <div class="col-sm-4">
                    <input type="text" name="fldhelppage" class="form-control" value="{{ old('fldhelppage') }}" placeholder="" size="20">
                  </div>
                </div>
                <div class="form-group text-center mt-3">
                  <button class="btn btn-primary"><i class="ri-add-fill"></i>&nbsp;Add</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<form id="delete_form" method="POST">
 @csrf
 @method('delete')
</form>
<script>
 $(function() {

   function select2loading() {
     setTimeout(function() {
       $('.select2genericname').select2({
        'placeholder' : 'select generic name'
      });
       $('.select2DosageForms').select2({
         placeholder : 'select dosage'
       });
     }, 4000);
   }

   select2loading();

   $('#genericnameaddaddbutton').click(function() {
     var genericname = $('#genericnamefield').val();


     if(genericname != '') {
       $.ajax({
         type : 'post',
         url  : '{{ route('medicines.addgeneric') }}',
         dataType : 'json',
         data : {
           '_token': '{{ csrf_token() }}',
           'fldcodename': genericname,
         },
         success: function (res) {

           showAlert(res.message);
           if(res.message == 'Generic Name added successfully.') {
             $('#genericnamefield').val('');
             var deleteroutename = "{{ url('/medicines/deletegeneric') }}/"+encodeURIComponent(genericname);
             $('#genericnamelistingmodal').append('<li class="generic-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="generic_item" data-href="'+deleteroutename+'" data-id="'+genericname+'">'+genericname+'</li>');
             $('.select2genericname').append('<option value="'+res.fldcodename+'" data-id="'+res.fldcodename+'">'+res.fldcodename+'</option>');
             select2loading();
           }

         }
       });
     } else {
       alert('Generic Name is required');
     }
   });

       // selecting category item
       $('#genericnamelistingmodal').on('click', '.generic_item', function() {
         $('#genericnametobedeletedroute').val($(this).data('href'));
         $('#genericidtobedeleted').val($(this).data('id'));
       });

       // deleting selected category item
       $('#genericnamedeletebutton').click(function() {
         var deletegenericroute = $('#genericnametobedeletedroute').val();
         var deletegenericid = $('#genericidtobedeleted').val();

         if(deletegenericroute == '') {
           alert('no generic info selected, please select the generic info.');
         }

         if(deletegenericroute != '') {
           var really = confirm("You really want to delete this Generic Info?");
           if(!really) {
             return false
           } else {
             $.ajax({
               type : 'delete',
               url : deletegenericroute,
               dataType : 'json',
               data : {
                 '_token': '{{ csrf_token() }}',
               },
               success: function (res) {
                 if(res.message == 'error') {
                   showAlert(res.errormessage);
                 } else if(res.message == 'success') {
                   showAlert(res.successmessage);
                   $("#genericnamelistingmodal").find(`[data-href='${deletegenericroute}']`).parent().remove();
                   $(".select2genericname").find(`[data-id='${deletegenericid}']`).remove();
                   $('#genericnametobedeletedroute').val('');
                   $('#genericidtobedeleted').val('');

                 }
               }
             });
           }
         }
       });

       // adding category

       $('#dosageaddbutton').click(function() {
         var dosagename = $('#dosageformfield').val();


         if(dosagename != '') {
           $.ajax({
             type : 'post',
             url  : '{{ route('medicines.adddosageform') }}',
             dataType : 'json',
             data : {
               '_token': '{{ csrf_token() }}',
               'flforms': dosagename,
             },
             success: function (res) {

               showAlert(res.message);
               if(res.message == 'Dosage Form added successfully.') {
                 $('#dosageformfield').val('');
                 var deleteroutename = "{{ url('/medicines/deletedosageform') }}/"+res.fldid;
                 $('#dosagelistingmodal').append('<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.flforms+'</li>');
                 $('.select2DosageForms').append('<option value="'+res.flforms+'" data-id="'+res.fldid+'">'+res.flforms+'</option>');
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
           alert('no Dosage selected, please select the Dosage.');
         }

         if(deletedosageroute != '') {
           var really = confirm("You really want to delete this Dosage?");
           if(!really) {
             return false
           } else {
             $.ajax({
               type : 'delete',
               url : deletedosageroute,
               data : {
                 '_token': '{{ csrf_token() }}',
               },
               success: function (res) {
                 showAlert(res);
                 if(res == 'Dosage deleted successfully.') {
                   $("#dosagelistingmodal").find(`[data-href='${deletedosageroute}']`).parent().remove();
                   $(".select2DosageForms").find(`[data-id='${dosageidtobedeleted}']`).remove();
                   $('#dosagetobedeletedroute').val('');
                   $('#categoryidtobedeleted').val('');
                   select2loading();
                 }
               }
             });
           }
         }
       });

       // validation error message

       @if($errors->any())
       var validation_error = '';

       @foreach($errors->all() as $error)
       validation_error += '{{ $error }} \n';
       @endforeach

       showAlert(validation_error);
       @endif


       @if(Session::has('success_message'))
       var successmessage = '{{ Session::get('success_message') }}';
       showAlert(successmessage);
       @endif

       @if(Session::has('error_message'))
       var errormessage = '{{ Session::get('error_message') }}';
       showAlert(errormessage);
       @endif

       $('.deletedrug').click(function() {
        var really = confirm("You really want to delete this Drug?");
        var href = $(this).data('href');
        if(!really) {
          return false
        } else {
          $('#delete_form').attr('action', href);
          $('#delete_form').submit();
        }
      });
     })
   </script>
   @endsection
