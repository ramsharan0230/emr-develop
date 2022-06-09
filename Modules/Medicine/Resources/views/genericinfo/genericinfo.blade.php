@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Generic Info</h4>
               </div>
            </div>
            <div class="iq-card-body">
               @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(Session::get('success_message'))
                <div class="alert alert-success containerAlert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    {{ Session::get('success_message') }}
                </div>
                @endif

                @if(Session::get('error_message'))
                <div class="alert alert-success containerAlert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    {{ Session::get('error_message') }}
                </div>
               @endif
              <div class="row">
               <div class="col-lg-4 col-md-12">
                  @include('medicine::layouts.includes.genericlisting')
               </div>
               <div class="col-lg-8 col-md-12">
                  <form action="{{ route('medicines.generic.add') }}" class="form-horizontal" enctype="multipart/form-data" method="POST">
                     @csrf
                     <div class="form-group form-row align-items-center">
                        <label for="" class="col-sm-3">Generic Name:</label>
                        @php

                        $codes = \App\Utils\Medicinehelpers::getAllCodes();
                        @endphp
                        <div class="col-sm-8">
                           <select name="fldcodename" class="form-control select2genericname" required>
                              <option value=""></option>
                              @if(!empty($codes))
                              @forelse($codes as $code)
                              <option value="{{ $code->fldcodename }}" data-id="{{ $code->fldcodename }}" {{ (old('fldcodename') && old('fldcodename') == $code->fldcodename) ? 'selected' : ''}}>{{ $code->fldcodename }}</option>
                              @empty
                              @endforelse
                            @endif

                           </select>
                        </div>
                        <div class="col-sm-1">
                           <!-- <button class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></button> -->
                           <a href="javascript:void(0)" data-toggle="modal" data-target="#code_modal" class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></a>
                           @include('medicine::layouts.modal.code')
                        </div>
                     </div>
                     <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-2"><strong>Recommended:</strong></label>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center er-input">
                              <label for="" class="col-sm-6">Adult Dose</label>
                              <div class="col-sm-2">
                                 <input type="number" step="any" min="0" name="fldrecaddose" value="{{ old('fldrecaddose') }}" placeholder="0" class="form-control">
                              </div>
                              <div class="col-sm-4">
                                 <select name="fldrecaddoseunit" class="form-control">
                                    <option value=""></option>
                                    <option value="mg" {{ old('fldrecaddoseunit') == 'mg' ? 'selected' : '' }}>mg</option>
                                    <option value="mg/kg" {{ old('fldrecaddoseunit') == 'mg/kg' ? 'selected' : '' }}>mg/kg</option>
                                    <option value="mg/sqm" {{ old('fldrecaddoseunit') == 'mg/sqm' ? 'selected' : '' }}>mg/sqm</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center er-input">
                              <label for="" class="col-sm-6">Paed Dose </label>
                              <div class="col-sm-2">
                                 <input type="number" step="any" min="0" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" class="form-control">
                              </div>
                              <div class="col-sm-4">
                                 <select name="fldrecpeddoseunit" class="form-control">
                                    <option value=""></option>
                                    <option value="mg" {{ old('fldrecpeddoseunit') == 'mg' ? 'selected' : '' }}>mg</option>
                                    <option value="mg/kg" {{ old('fldrecpeddoseunit') == 'mg/kg' ? 'selected' : '' }}>mg/kg</option>
                                    <option value="mg/sqm" {{ old('fldrecpeddoseunit') == 'mg/sqm' ? 'selected' : '' }}>mg/sqm</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Adult Freq:</label>
                              <div class="col-sm-6">
                                 <input type="number" min="0" name="fldrecadfreq" id="" class="form-control" value="{{ old('fldrecadfreq') }}" placeholder="0"/>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Paed Freq:</label>
                              <div class="col-sm-6">
                                 <input type="number"  min="0" name="fldrecpedfreq" value="{{ old('fldrecpedfreq') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Allow PRN:</label>
                              <div class="col-sm-6">
                                 <select name="fldprn" class="form-control">
                                    <option value=""></option>
                                    <option value="No" {{ old('fldprn') == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Yes" {{ old('fldprn') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Risk Level:</label>
                              <div class="col-sm-6">
                                 <select name="fldrisklevel"  class="form-control">
                                    <option value=""></option>
                                    <option value="High Risk" {{ old('fldrisklevel') == 'High Risk' ? 'selected' : '' }}>High Risk</option>
                                    <option value="Low Risk" {{ old('fldrisklevel') == 'Low Risk' ? 'selected' : '' }}>Low Risk</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Elimination(%)Hepatic:</label>
                              <div class="col-sm-6">
                                 <input type="number" step="any" min="0" name="fldeliminhepatic" value="{{ old('fldeliminhepatic') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Renal</label>
                              <div class="col-sm-6">
                                 <input type="number" step="any" min="0" name="fldeliminrenal" value="{{ old('fldeliminrenal') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-8">Plasma Protein Binding (%):</label>
                              <div class="col-sm-4">
                                 <input type="number" step="any" min="0" name="fldplasmaprotein" value="{{ old('fldplasmaprotein') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-8">Elimination Half Life(Hour):</label>
                              <div class="col-sm-4">
                                 <input type="number" step="any" min="0" name="fldeliminhalflife" value="{{ old('fldeliminhalflife') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Category:</label>
                              <div class="col-sm-5">
                                 @php
                                 $medcategories = \App\Utils\Medicinehelpers::getMedCategory();
                                 @endphp
                                 <select name="fldcategory" class="select2Category form-control">
                                    <option value=""></option>
                                    @forelse($medcategories as $medcategory)
                                    <option value="{{ $medcategory->flclass }}" data-id="{{ $medcategory->fldid }}" {{ (old('fldcategory') == $medcategory->flclass) ? 'selected' : ''}}>{{ $medcategory->flclass }}</option>
                                    @empty
                                    @endforelse
                                 </select>
                              </div>
                              <div class="col-sm-1">
                                 <!-- <button class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></button> -->
                                 <a href="javascript:void(0)" class="btn btn-primary btn-sm-in"  data-toggle="modal" data-target="#med_category_modal"><i class="ri-add-fill"></i></a>
                                 @include('medicine::layouts.modal.medcategory')
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-8">Vol of Distribution(L/Kg):</label>
                              <div class="col-sm-4">
                                 <input type="number" step="any" name="fldvoldistribution" value="{{ old('fldvoldistribution') }}" placeholder="0" class="form-control">
                              </div>
                           </div>
                        </div>
                        {{-- <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Group(Allergy):</label>
                              <div class="col-sm-5"> --}}
                                 {{-- @php
                                 $chemicals = \App\Utils\Medicinehelpers::getChemicals();
                                 @endphp --}}
                                 {{-- <select name="fldchemclass" class="select2allergy form-control" required>
                                    <option value=""></option>
                                    @forelse($chemicals as $chemical)
                                    <option value="{{ $chemical->flclass }}" data-id="{{ $chemical->fldid }}" {{ (old('fldchemclass') == $chemical->flclass) ? 'selected' : ''}}>{{ $chemical->flclass }}</option>
                                    @empty
                                    @endforelse
                                 </select>
                              </div>
                              <div class="col-sm-1">
                                 <a href="javascript:void(0)" data-toggle="modal" data-target="#chemical_modal" class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></a>
                                 @include('medicine::layouts.modal.chemical')
                              </div>
                           </div>
                        </div> --}}
                        {{-- <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-5">Sensitivity Name:</label>
                              <div class="col-sm-5"> --}}
                                 {{-- @php
                                 $sensitivitydrugs = \App\Utils\Medicinehelpers::getSensitivityDrug();
                                 @endphp --}}
                                 {{-- <select name="fldsensname" class="select2sensitivity form-control" required>
                                    <option value=""></option>
                                    @forelse($sensitivitydrugs as $sensitivitydrug)
                                    <option value="{{ $sensitivitydrug->flclass }}" data-id="{{ $sensitivitydrug->fldid }}" {{ (old('fldsensname') == $sensitivitydrug->flclass) ? 'selected' : ''}}>{{ $sensitivitydrug->flclass }}</option>
                                    @empty
                                    @endforelse
                                 </select>
                              </div>
                              <div class="col-sm-1">
                                 <!-- <button class="btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></button> -->
                                 <a href="javascript:void(0)" class="btn btn-primary btn-sm-in" data-toggle="modal" data-target="#sensitivity_modal"><i class="ri-add-fill"></i></a>
                                 @include('medicine::layouts.modal.sensitivity')
                              </div>
                           </div>
                        </div> --}}
                        <div class="col-sm-6">
                           <div class="form-group form-row align-items-center">
                              <label for="" class="col-sm-6">Reference:</label>
                              <div class="col-sm-6">
                                 <input type="text" name="fldreference" value="{{ old('fldreference') }}" class="form-control"  placeholder="">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group form-row">
                              <label for="" class="col-sm-5">Help File:</label>
                              <div class="col-sm-7">
                                 <input type="text" name="fldhelppage" value="{{ old('fldhelppage') }}" class="form-control" placeholder="">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-6">Status:</label>
                                <div class="col-sm-6">
                                    <select name="fldstatus" class="form-control">
                                        @if(isset($code) && !empty($code))
                                        <option value="1" {{ $code->fldstatus == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $code->fldstatus == 0 ? 'selected' : '' }}>Inactive</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="form-group form-row">
                              <label for="" class="col-sm-3">MechofAction:</label>
                              <div class="col-sm-9">
                                 <textarea name="fldmechaction" rows="5" class="form-control">{!! old('fldmechaction') !!}</textarea>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="form-row">
                              <label for="" class="col-sm-3"></label>
                              <div class="col-sm-9">
                                 <textarea name="flddrugdetail" rows="5" class="form-control">{!! old('flddrugdetail') !!}</textarea>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12 mt-2">
                           <div class="form-group text-right">
                              <button class="btn btn-primary btn-action"><i class="ri-add-fill"></i>&nbsp;Add</button>
                           </div>
                        </div>
                     </div>
                  </form>
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
    var categories = <?php echo json_encode($medcategories); ?>;

    $('#med_category_modal').on('hidden.bs.modal', function () {
        $('#categorynamefield').val('');
        $('#categorylistingmodal').empty();
        $.each(categories, function(i, category) {
            var dynmeddeleteroutename = "{{ url('/medicines/deletecategory') }}/"+category.fldid;
            $('#categorylistingmodal').append('<li class="list-group-item"><a href="javascript:void(0)" class="category_item" data-href="'+dynmeddeleteroutename+'" data-id="'+category.fldid+'">'+category.flclass+'</a></li>');
        });
    })

    // $('#chemical_modal').on('hidden.bs.modal', function () {
    //     $('#chemicalnamefield').val('');
    //     $('#chemicallistingmodal').empty();
    //     $.each(chemicals, function(i, chemical) {
    //         var dynchemdeleteroutename = "{{ url('/medicines/deletechemicals') }}/"+chemical.fldid;
    //         $('#chemicallistingmodal').append('<li class="chemical-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="chemical_item" data-href="'+dynchemdeleteroutename+'" data-id="'+chemical.fldid+'">'+chemical.flclass+'</a></li>');
    //     });
    // })

    // $('#sensitivity_modal').on('hidden.bs.modal', function () {
    //     $('#sensitivitynamefield').val('');
    //     $('#sensitivitylistingmodal').empty();
    //     $.each(sensitivitynames, function(i, sensitivity) {
    //         var dynsendeleteroutename = "{{ url('/medicines/deletesensitivity') }}/"+sensitivity.fldid;
    //         $('#sensitivitylistingmodal').append('<li class="list-group-item"><a href="javascript:void(0)" class="sensitivity_item" data-href="'+dynsendeleteroutename+'" data-id="'+sensitivity.fldid+'">'+sensitivity.flclass+'</a></li>');
    //     });
    // })

    $(document).ready(function(){
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
    });
   $(function() {

        function select2loading() {
            setTimeout(function() {
                $('.select2genericname').select2({
                    placeholder : 'select generic name'
                });

                $('.select2Category').select2({
                    placeholder : 'select category'
                });

                $('.select2allergy').select2({
                    placeholder : 'select allergy'
                });

                $('.select2sensitivity').select2({
                   placeholder : 'select sensitivity'
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
                        var newOption = new Option(genericname, genericname, true, true);
                        $('.select2genericname').append(newOption).trigger('change');
                        }
                    }
                });
            } else {
                showAlert('Generic Name is required');
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
                            if(res.message == 'error'){
                                showAlert(res.errormessage);
                            } else if(res.message == 'success') {
                                showAlert(res.successmessage)
                                $("#genericnamelistingmodal").find(`[data-href='${deletegenericroute}']`).parent().remove();
                                $("#genericinfolistingtable").find(`[data-generic='${deletegenericid}']`).remove();
                                $('#genericnametobedeletedroute').val('');
                                $('#genericidtobedeleted').val('');
                             }
                        }
                    });
                }
            }
        });

        // adding category

        $('#categoryaddaddbutton').click(function() {
            var categoryname = $('#categorynamefield').val();

            if(categoryname != '') {
                $.ajax({
                    type : 'post',
                    url  : '{{ route('medicines.addmedcategory') }}',
                    dataType : 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                        'flclass': categoryname,
                    },
                    success: function (res) {
                        showAlert(res.message);
                        if(res.message == 'Category added successfully.') {
                            $('#categorynamefield').val('');
                            categories = res.medcategories;
                            $('#categorylistingmodal').empty();
                            $.each(categories, function(i, category) {
                                var dynmeddeleteroutename = "{{ url('/medicines/deletecategory') }}/"+category.fldid;
                                $('#categorylistingmodal').append('<li class="list-group-item"><a href="javascript:void(0)" class="category_item" data-href="'+dynmeddeleteroutename+'" data-id="'+category.fldid+'">'+category.flclass+'</a></li>');
                            });
                            $('.select2Category').append('<option value="'+res.flclass+'" data-id="'+res.fldid+'">'+res.flclass+'</option>');
                            select2loading();
                        }
                    }
                });
            }else {
                alert('Category Name is required');
            }
        });

        // selecting category item
        $('#categorylistingmodal').on('click', '.category_item', function() {
            $('#categorytobedeletedroute').val($(this).data('href'));
            $('#categoryidtobedeleted').val($(this).data('id'));
            selected_li('#categorylistingmodal li', $(this).closest('li'));
        });

        // deleting selected category item
        $('#categorydeletebutton').click(function() {
            var deletecategoryroute = $('#categorytobedeletedroute').val();
            var deletecategoryid = $('#categoryidtobedeleted').val();

            if(deletecategoryroute == '') {
                alert('no category selected, please select the category.');
            }

            if(deletecategoryroute != '') {
                var really = confirm("You really want to delete this category?");
                if(!really) {
                    return false
                } else {
                    $.ajax({
                        type : 'delete',
                        url : deletecategoryroute,
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function (res) {
                            if(res.message == 'error') {
                                showAlert(res.errormessage);
                            } else if(res.message == 'success') {
                                showAlert(res.successmessage);
                                categories = res.medcategories;
                                $("#categorylistingmodal").find(`[data-href='${deletecategoryroute}']`).parent().remove();
                                $(".select2category").find(`[data-id='${deletecategoryid}']`).remove();
                                $('#categorytobedeletedroute').val('');
                                $('#categoryidtobedeleted').val('');
                            }
                        }
                    });
                }
            }
        });

        // chemical adding
        // $('#chemicaladdaddbutton').click(function() {
        //     var chemical = $('#chemicalnamefield').val();
        //     if(chemical != '') {
        //         $.ajax({
        //             type : 'post',
        //             url  : '{{ route('medicines.addchemicals') }}',
        //             dataType : 'json',
        //             data : {
        //                 '_token': '{{ csrf_token() }}',
        //                 'flclass': chemical,
        //             },
        //             success: function (res) {
        //                 showAlert(res.message);
        //                 if(res.message == 'Chemical added successfully.') {
        //                     $('#chemicalnamefield').val('');
        //                     chemicals = res.chemicals;
        //                     $('#chemicallistingmodal').empty();
        //                     $.each(chemicals, function(i, chemical) {
        //                         var dynchemdeleteroutename = "{{ url('/medicines/deletechemicals') }}/"+chemical.fldid;
        //                         $('#chemicallistingmodal').append('<li class="chemical-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="chemical_item" data-href="'+dynchemdeleteroutename+'" data-id="'+chemical.fldid+'">'+chemical.flclass+'</a></li>');
        //                     });
        //                     $('.select2allergy').append('<option value="'+res.fldid+'" data-id="'+res.fldid+'">'+res.flclass+'</option>');
        //                     select2loading();
        //                 }
        //             }
        //         });
        //     } else {
        //         alert('Chemical Name is required');
        //     }
        // });

        // selecting chemical item
        // $('#chemicallistingmodal').on('click', '.chemical_item', function() {
        //     $('#chemicalstobedeletedroute').val($(this).data('href'));
        //     $('#chemicalidtobedeleted').val($(this).data('id'));
        //     selected_li('#chemicallistingmodal li', $(this).closest('li'));
        // });

        // deleting selected chemical item
        // $('#chemicaldeletebutton').click(function() {
        //     var deletechemicalroute = $('#chemicalstobedeletedroute').val();
        //     var deletechemicalid = $('#chemicalidtobedeleted').val();

        //     if(deletechemicalroute == '') {
        //         alert('no chemical selected, please select the chemical.');
        //     }

        //     if(deletechemicalroute != '') {
        //         var really = confirm("You really want to delete this chemical?");
        //         if(!really) {
        //             return false
        //         } else {
        //             $.ajax({
        //                 type : 'delete',
        //                 url : deletechemicalroute,
        //                 dataType : 'json',
        //                 data : {
        //                     '_token': '{{ csrf_token() }}',
        //                 },
        //                 success: function (res) {
        //                     if(res.message == 'error') {
        //                         showAlert(res.errormessage);
        //                     } else if(res.message == 'success') {
        //                         showAlert(res.successmessage);
        //                         chemicals = res.chemicals;
        //                         $("#chemicallistingmodal").find(`[data-href='${deletechemicalroute}']`).parent().remove();
        //                         $(".select2allergy").find(`[data-id='${deletechemicalid}']`).remove();
        //                         $('#chemicalstobedeletedroute').val('');
        //                         $('#chemicalidtobedeleted').val('');
        //                     }
        //                 }
        //             });
        //         }
        //     }
        // });

        // sensitivity adding
        // $('#sensitivityaddaddbutton').click(function() {
        //     var sensitivity = $('#sensitivitynamefield').val();
        //     if(sensitivity != '') {
        //         $.ajax({
        //             type : 'post',
        //             url  : '{{ route('medicines.addsensitivity') }}',
        //             dataType : 'json',
        //             data : {
        //                 '_token': '{{ csrf_token() }}',
        //                 'flclass': sensitivity,
        //             },
        //             success: function (res) {
        //                 showAlert(res.message);
        //                 if(res.message == 'Sensitivity added successfully.') {
        //                     $('#sensitivitynamefield').val('');
        //                     $('#sensitivitylistingmodal').empty();
        //                     sensitivitynames = res.sensitivitydrugs;
        //                     $.each(sensitivitynames, function(i, sensitivity) {
        //                         var dynsendeleteroutename = "{{ url('/medicines/deletesensitivity') }}/"+sensitivity.fldid;
        //                         $('#sensitivitylistingmodal').append('<li class="list-group-item"><a href="javascript:void(0)" class="sensitivity_item" data-href="'+dynsendeleteroutename+'" data-id="'+sensitivity.fldid+'">'+sensitivity.flclass+'</a></li>');
        //                     });
        //                     $('.select2sensitivity').append('<option value="'+res.fldid+'" data-id="'+res.fldid+'">'+res.flclass+'</option>');
        //                     select2loading();
        //                 }
        //             }
        //         });
        //     } else {
        //         alert('Sensitivity Name is required');
        //     }
        // });

        // selecting sensitivity item
        // $('#sensitivitylistingmodal').on('click', '.sensitivity_item', function() {
        //     $('#sensitivitytobedeletedroute').val($(this).data('href'));
        //     $('#sensitivityidtobedeleted').val($(this).data('id'));
        //     selected_li('#sensitivitylistingmodal li', $(this).closest('li'));
        // });

        // deleting selected sensitivity item
        // $('#sensitivitydeletebutton').click(function() {
        //     var deletesensitivityroute = $('#sensitivitytobedeletedroute').val();
        //     var deletesensitivityid = $('#sensitivityidtobedeleted').val();

        //     if(deletesensitivityroute == '') {
        //       alert('no sensitivity drug selected, please select the chemical.');
        //     }

        //     if(deletesensitivityroute != '') {
        //         var really = confirm("You really want to delete this sensitivity drug?");
        //         if(!really) {
        //             return false
        //         } else {
        //             $.ajax({
        //                 type : 'delete',
        //                 url : deletesensitivityroute,
        //                 dataType : 'json',
        //                 data : {
        //                     '_token': '{{ csrf_token() }}',
        //                 },
        //                 success: function (res) {
        //                     if(res.message == 'error') {
        //                         showAlert(res.errormessage);
        //                     } else if(res.message == 'success') {
        //                         showAlert(res.successmessage);
        //                         sensitivitynames = res.sensitivitydrugs;
        //                         $("#sensitivitylistingmodal").find(`[data-href='${deletesensitivityroute}']`).parent().remove();
        //                         $(".select2sensitivity").find(`[data-id='${deletesensitivityid}']`).remove();
        //                         $('#sensitivitytobedeletedroute').val('');
        //                         $('#chemicalidtobedeleted').val('');
        //                     }
        //                 }
        //             });
        //         }
        //     }
        // });

        $(document).on('click','.deletegenericinfo',function() {
            var really = confirm("You really want to delete this Generic Info?");
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
