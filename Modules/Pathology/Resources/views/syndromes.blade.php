@extends('frontend.layouts.master')
@push('after-styles')
	<!-- style here -->
	<link rel="stylesheet" href="{{ asset('assets/css/pathology.css') }}">
	<style>
		.variables-box-syndromes{
			height: 300px;
			overflow-y: scroll;
			background-color: #fff;
			border: 1px solid #ccc;
		}
		.variables-box-list{
			list-style: none;
			padding: 0;
		}
		.variables-box-list > li {
			display: block;
			padding: 9px 3px;
		}
		.variables-box-list > li:hover,{
			cursor: pointer;
			background-color: #f3f3f3;
		}
		.variables-box-list input{
			display: none;
		}
		.form-group label{
			border: none;
			padding: 0;
		}
		.variables-box-list>li>label{
			margin-bottom: 0px;
			border: none;
			display: block;
			width: 100%;
			height: 100%;
		}
		.delete_this_syndrome>i:hover{
			color: red;
		}
		input[name="selected_variable_syndrome"]:checked+li{
	        background-color: #3f7cde;
	        color: #fff;
	    }
	    .variables-box-list>li:hover{
	    	background-color: #666;
	        color: #fff;
	    }
	    #reload_syndromes_list:hover{
			cursor: pointer;
	    }
	</style>
@endpush
@section('content')
	<!-- content here -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Syndromes</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <!-- <div class="col-lg-8 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2 form-label-syndrome">Category</label>
                                    <div class="col-sm-6">
                                        <select name="syndromes_categories" class="form-control getAllCategories">
                                            @if(count($syndromesCategories))
                                            @foreach($syndromesCategories as $category)
                                            <option value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                    	<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#insert_category_modal_syndromes"><i class="ri-add-line"></i></a>
                                    </div>
                                    <div class="col-sm-1">
                                    	<a href="javascript:;" class="btn btn-primary" id="reload_syndromes_list"><i class="ri-refresh-line"></i></a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2 form-label-syndrome">Syndromes</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="" id="syndromes_name" class="form-control">
                                    </div>
                                </div>
                                <div class="from-group form-row align-items-center">
                                    <label class="col-sm-2">ICD Group</label>
                                    <div class="col-sm-3">
                                        <input type="text" name=""  class="form-control" id="icd_group_particular">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name=""  class="form-control"  id="icd_group_code">
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#icd_group_modal"><i class="ri-edit-2-fill h6"></i></button>
                                        @include('pathology::modal.icd_group')
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" id="insert_syndromes" url="{{ route('insert.syndrome') }}"><i class="ri-add-line h6"></i> Add</button>
                                    </div>
                                </div>
                            </div> -->
							<div class="col-sm-4">
								<div class="form-group form-row align-items-center">
                                    <label class="col-sm-3 form-label-syndrome">Category</label>
                                    <div class="col-sm-6">
                                        <select name="syndromes_categories" class="form-control getAllCategories">
                                            @if(count($syndromesCategories))
                                            @foreach($syndromesCategories as $category)
                                            <option value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                    	<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#insert_category_modal_syndromes"><i class="ri-add-line"></i></a>&nbsp;
                                    	<a href="javascript:;" class="btn btn-primary" id="reload_syndromes_list"><i class="ri-refresh-line"></i></a>
                                    </div>
                                </div>
							</div>
							<div class="col-sm-3">
								<div class="form-group form-row align-items-center">
                                    <label class="col-sm-5 form-label-syndrome">Syndromes</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="" id="syndromes_name" class="form-control">
                                    </div>
                                </div>
							</div>
							<div class="col-sm-5">
								<div class="from-group form-row align-items-center">
                                    <label class="col-sm-3">ICD Group</label>
                                    <div class="col-sm-3">
                                        <input type="text" name=""  class="form-control" id="icd_group_particular">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name=""  class="form-control"  id="icd_group_code">
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#icd_group_modal"><i class="ri-edit-2-fill h6"></i></button>
                                        @include('pathology::modal.icd_group')
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" id="insert_syndromes" url="{{ route('insert.syndrome') }}"><i class="ri-add-line h6"></i> Add</button>
                                    </div>
                                </div>
							</div>
                        </div>
                        <div class="table-responsive table-container mt-2">
                            <table class="table table-bordered table-hover table-striped ">
                                <thead class="thead-light">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Syndromes</th>
                                        <th>ICD Group</th>
                                    </tr>
                                </thead>
								<tbody class="append_related_data"></tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="insert_category_modal_syndromes">
        <div class="modal-dialog ">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Variables</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Syndromes</label>
                            <div class="col-sm-8">
                                <input type="text" id="syndromes_variable" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <i class="h5 ri-refresh-line" id="reload_syndromes_variable_list"></i>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="btn btn-primary" id="syndromes_variable_insert" url="{{ route('insert.variable.syndrome') }}"><i class="ri-add-line h5"></i> Add</a>
                    <a href="javascript:;" class="btn btn-danger" id="syndromes_variable_delete" url="{{ route('delete.variable.syndrome') }}"><i class="ri-delete-bin-5-fill"></i> Delete</a>
                    <div class="variables-box-syndromes mt-2">
                        <ul class="variables-box-list list-group">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
	<!-- script here -->
	<script>
		// global app configuration object
	    var config = {
	        routes: {
	            syndromesDelete: "{{ route('delete.syndrome') }}"
	        }
	    };

		// Add Syndromes Variables
		$('#syndromes_variable_insert').click(function(){
		    var flclass = $("#syndromes_variable").val();
		    var url = $(this).attr("url");
		    var formData = {
		        flclass: flclass
		    };

		    if(flclass == '')
		    {
		    	alert('Please Insert Variable');
		    	return false;
		    }

		    $.ajax({
		        url: url,
		        type: "POST",
		        dataType: "json",
		        data: formData,
		        success: function (data) {
		            if ($.isEmptyObject(data.status)) {
		                alert(data.message);
		                getSyndromesCategories();
		                //location.reload();
		            } else {
		                alert(data.message);
		            }
		        }
		    });
		});

		function getSyndromesCategories(){
			$('.getAllCategories').empty();
			$('.variables-box-list').empty();
			$.get('syndromes/variables/list', function(data) {
	            $.each(data, function(index, getVariables) {
	                $('.getAllCategories').append('<option value="'+ getVariables.flclass +'">'+ getVariables.flclass +'</option>');
	                $('.variables-box-list').append('<input type="radio" name="selected_variable_syndrome" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'" rel="'+getVariables.flclass+'"><li><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.flclass +'</label></li>');
	            });
	        });
		}

		// Reload Function
		$('#reload_syndromes_variable_list').click(function(){
			getSyndromesCategories();
		});

		// Delete Syndromes Variables
		$('#syndromes_variable_delete').click(function(){
			var fldid = $("input[name='selected_variable_syndrome']:checked").val();
			var value = $("input[name='selected_variable_syndrome']:checked").attr('rel');
			var url = $(this).attr("url");
		    var formData = {
		        fldid: fldid
		    };

		    if(fldid == '')
		    {
		    	alert('Please Select Variable To Delete');
		    	return false;
		    }

		    $.ajax({
		        url: url,
		        type: "POST",
		        dataType: "json",
		        data: formData,
		        success: function (data) {
		            if ($.isEmptyObject(data.status)) {
		                alert(data.message);
		                $("input[name='selected_variable_syndrome']:checked + li").remove();
		                $('select[name="syndromes_categories"]').find('option[value="'+value+'"]').remove();
		                // getSyndromesCategories();
		                //location.reload();
		            } else {
		                alert(data.message);
		            }
		        }
		    });
		});

		// on click icd_group data
		$(document).on("click", "#icd_group_pathalogy tr", function () {
		    var code = $(this).attr('rel');
		    var particular = $(this).attr('rel1');
		    $('#icd_group_particular').val(null);
            $('#icd_group_code').val(null);
            $('#icd_group_particular').val(particular);
            $('#icd_group_code').val(code);
            $('.icd_group_modal').modal('toggle');
	    });

	    // Add Syndromes Variables
		$('#insert_syndromes').click(function(){
		    var fldcategory = $(".getAllCategories option:selected").val();
		    var fldsyndrome = $("#syndromes_name").val();
		    var fldsymcode = $("#icd_group_code").val();
		    var url = $(this).attr("url");
		    var formData = {
		        fldcategory: fldcategory,
		        fldsyndrome: fldsyndrome,
		        fldsymcode: fldsymcode
		    };

		    if(fldcategory == '' || fldsyndrome == '')
		    {
		    	alert('Insuffecient Data');
		    	return false;
		    }

		    $.ajax({
		        url: url,
		        type: "POST",
		        dataType: "json",
		        data: formData,
		        success: function (data) {
		            if ($.isEmptyObject(data.status)) {
		                alert(data.message);
		                getSyndromes();
						$('#syndromes_name').val('');
						$('#icd_group_particular').val('');
						$('#icd_group_code').val('');
		                //location.reload();
		            } else {
		                alert(data.message);
		            }
		        }
		    });
		});

		function getSyndromes(){
			var fldcategory = $(".getAllCategories option:selected").val();
			$('.append_related_data').empty();
			$.get('syndromes/list?fldcategory=' + fldcategory, function(data) {
	            $.each(data, function(index, getSyndromes) {
	                $('.append_related_data').append('<tr rel="'+getSyndromes.fldsyndrome+'"><td>'+getSyndromes.fldsyndrome+'</td><td>'+getSyndromes.fldsymcode+'</td><td class="delete_this_syndrome"><i class="far fa-trash-alt"></i></tr>');
	            });
	        });
		}

		// Delete Syndromes Variables
		$(document).on("click", ".delete_this_syndrome", function () {
		    var fldsyndrome = $(this).closest("tr").attr('rel');
            var url = config.routes.syndromesDelete;
            var formData = {
		        fldsyndrome: fldsyndrome
		    };
            if (confirm("Are you sure? You Want To Delete " + fldsyndrome)) {
            	$(this).closest("tr").remove();
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.status)) {
							alert(data.message);
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            }
	    });

        $('.getAllCategories').on('change', function(e) {
            var fldcategory = e.target.value;
            // ajax
            $('.append_related_data').empty();
            $.get('syndromes/list?fldcategory=' + fldcategory, function(data) {
	            $.each(data, function(index, getSyndromes) {
	                $('.append_related_data').append('<tr rel="'+getSyndromes.fldsyndrome+'"><td>'+getSyndromes.fldsyndrome+'</td><td>'+getSyndromes.fldsymcode+'</td><td class="delete_this_syndrome"><i class="far fa-trash-alt"></i></tr>');
	            });
	        });
        });

        $(document).on("click", "#reload_syndromes_list", function(){
        	var fldcategory = $(".getAllCategories option:selected").val();
        	// ajax
            $('.append_related_data').empty();
            $.get('syndromes/list?fldcategory=' + fldcategory, function(data) {
	            $.each(data, function(index, getSyndromes) {
	                $('.append_related_data').append('<tr rel="'+getSyndromes.fldsyndrome+'"><td>'+getSyndromes.fldsyndrome+'</td><td>'+getSyndromes.fldsymcode+'</td><td class="delete_this_syndrome"><i class="far fa-trash-alt"></i></tr>');
	            });
	        });
        });
	</script>
@endpush
