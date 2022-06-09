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
                  Pharmacy Item Activation
                  </h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="form-group form-row align-items-center">
                        <div class="col-sm-4">
                          <input type="text" name="dynamicfilter" id="dynamicfilter" class="form-control">
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group form-row align-items-center mt-2">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="activation" class="custom-control-input"  id="medicineRadio" value="medicines" checked>
                                <label class="custom-control-label" for="medicineRadio">Medicines   </label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <!-- <input type="radio" id="customRadio7" name="customRadio-1" class="custom-control-input"> -->
                                <input type="radio" name="activation" class="custom-control-input"  id="surgicalRadio" value="surgical">
                                <label class="custom-control-label" for="surgicalRadio"> Surgical   </label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <!-- <input type="radio" id="customRadio8" name="customRadio-1" class="custom-control-input"> -->
                                <input type="radio" name="activation" class="custom-control-input" id="extraRadio" value="extra">
                                <label class="custom-control-label" for="extraRadio">Extra</label>
                              </div>
                               <div class="">
                                  <a href="javascript:void(0)" class="btn btn-primary btn-action" id="enable_all" type="button">&nbsp;&nbsp;Enable All</a>
                                  <!-- <a href="#" class="btn btn-info btn-action" type="button">Enable All</a>&nbsp; -->
                                  <!-- <a href="#" class="btn btn-warning btn-action" type="button">Disable All</a> -->
                                  <a href="javascript:void(0)" class="btn btn-warning btn-action" id="disable_all" type="button">&nbsp;&nbsp;Disable All</a>
                                  <a href="javascript:void(0)" class="btn btn-info btn-action" id="clear" type="button">&nbsp;&nbsp;Clear</a>
                                  <input type="hidden" name="activationof" id="activationof" value="medicines">
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <div id="table" class="table-responsive table-container" >
                  <table class="table table-bordered table-striped text-center">
                     <thead class="thead-light">
                        <tr>
                           <th class="tittle-th">&nbsp;</th>
                           <th class="tittle-th">Type</th>
                           <th class="tittle-th">Generic Type</th>
                           <th class="tittle-th">Brand Name</th>
                           <th class="tittle-th">Status</th>
                        </tr>
                     </thead>
                     <tbody id="tablebody"></tbody>
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
        $(document).on('click','#clear',function(){
            $('#dynamicfilter').val("");
        })

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var value = $("input[name='activation']:checked").val();
            if(value == 'medicines'){
                var href = '{{ route('pharmacist.activation.medicines') }}';
            } else if(value == 'surgical') {
                var href = '{{ route('pharmacist.activation.surgical') }}';
            } else if(value == 'extra') {
                var href = '{{ route('pharmacist.activation.extra') }}';
            }
            var page = $(this).attr('href').split('page=')[1];
            $.ajax({
               type: 'get',
               url: href+"?page="+page,
               dataType: 'json',
               success : function(res) {
                   if(res.message == 'error'){
                       showAlert(res.error);
                   } else if(res.message == 'success') {
                       $('#tablebody').html(res.html);
                   }
               }
           });
        });

       var activation = $('input[type=radio][name=activation]').val();
       function getTableDataFromRadio(value) {
           if(value == 'medicines'){
               var href = '{{ route('pharmacist.activation.medicines') }}';
           } else if(value == 'surgical') {
               var href = '{{ route('pharmacist.activation.surgical') }}';
           } else if(value == 'extra') {
               var href = '{{ route('pharmacist.activation.extra') }}';
           }

           $.ajax({
               type: 'get',
               url: href,
               dataType: 'json',
               success : function(res) {
                   if(res.message == 'error'){
                       showAlert(res.error);
                   } else if(res.message == 'success') {
                       $('#tablebody').html(res.html);
                   }
               }
           });
       }

       getTableDataFromRadio(activation);

       $(document).on('click','#medicineRadio',function(){
           $('#activationof').val('medicines');
            getTableDataFromRadio("medicines");
       });

       $(document).on('click','#surgicalRadio',function(){
            $('#activationof').val('surgical');
            getTableDataFromRadio("surgical");
       });

       $(document).on('click','#extraRadio',function(){
            $('#activationof').val('extra');
            getTableDataFromRadio("extra");
       });

    //    $(document).on('change','input[type=radio][name=activation]',function() {
    //        console.log('hit');
    //        var value = $(this).val();
    //        $('#activationof').val(value);
    //        getTableDataFromRadio(value);

    //    });

       $('#enable_all').click(function() {
           var enable = 'Active';

           enableDisableAll(enable);
       });

       $('#disable_all').click(function() {
           var disable = 'Inactive';
           enableDisableAll(disable);
       });



       function enableDisableAll(enabledisable) {
           var activationof = $('#activationof').val();
           $.ajax({
               type: 'post',
               url: '{{ route('pharmacist.activation.enabledisableall') }}',
               dataType: 'json',
               data: {
                   '_token' : '{{ csrf_token() }}',
                   'activationof' : activationof,
                   'enabledisable' : enabledisable
               },
               success : function(res) {
                   if(res.message == 'error'){
                       showAlert(res.error);
                   } else if(res.message == 'success') {
                       $('#tablebody').html(res.html);
                       // $('.pagination').html(res.pagination);
                   }
               }
           });
       }

       $('#tablebody').on('click', '.togglestatus', function() {
           var id = $(this).data('id');
           var brand = $(this).data('brand');
           var status = $(this).data('status');
           $.ajax({
               type: 'post',
               url: '{{ route('pharmacist.activation.togglestatus') }}',
               dataType: 'json',
               data : {
                   '_token' : '{{ csrf_token() }}',
                   'id' : id,
                   'brand' : brand,
                   'status' : status
               },
               success : function(res) {
                   if(res.message == 'error'){
                       showAlert(res.error);
                   } else if(res.message == 'success') {
                       $('#tablebody').find(`[data-id='${id}']`).children().html(res.status);
                       $('#tablebody').find(`[data-id='${id}']`).children().attr("class", res.class);
                       $('#tablebody').find(`[data-id='${id}']`).attr("title", res.title);
                       $('#tablebody').find(`[data-id='${id}']`).attr("data-status", res.status);
                   }
               }
           });
       });

       $('#dynamicfilter').keyup(function() {
           var keyword = $(this).val();
           var brandtype = $('#activationof').val();
           if(brandtype == 'medicines'){
               var href = '{{ route('pharmacist.activation.medicines') }}';

           } else if(brandtype == 'surgical') {
               var href = '{{ route('pharmacist.activation.surgical') }}';
           } else if(brandtype == 'extra') {
               var href = '{{ route('pharmacist.activation.extra') }}';
           }
           if(keyword.length > 0 &&  keyword != '') {
               $.ajax({
                   type : 'get',
                   url : href,
                   dataType : 'json',
                   data: {
                       'keyword' : keyword
                   },
                   success: function(res) {
                       if(res.message == 'error'){
                           showAlert(res.error);
                       } else if(res.message == 'success') {
                           $('#tablebody').html(res.html);
                       }
                   }
               });
           }
       });
   });
</script>
@stop
