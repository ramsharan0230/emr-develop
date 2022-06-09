@extends('frontend.layouts.master')
@push('after-styles')
<!-- style here -->
@endpush
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/pathology.css') }}">
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="iq-card iq-card-block iq-card-stretch iq-card-height">
				<div class="iq-card-header d-flex justify-content-between">
					<div class="iq-header-title">
						<h4 class="card-title">Symptoms</h4>
					</div>
				</div>
				<div class="iq-card-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="res-table">
								<table class="dietary-table datatable-symptoms table-striped table-hover table table-bordered">
									<thead class="thead-light">
										<tr>
											<th>Symptoms</th>
										</tr>
									</thead>
									<tbody id="getSelectedList">
										@if(isset($symptoms))
										@foreach($symptoms as $sym)
										<tr rel="{{ $sym->fldsymptom }}" rel1="{{ $sym->fldcategory }}" rel2="{{ $sym->fldsymdetail }}">
											<td class="dietary-td border-none">{{ $sym->fldsymptom }}</td>
										</tr>
										@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group form-row">
								<div class="col-sm-6">
									<div class="form-row">
										<label class="col-sm-3">Symptoms:</label>
										<div class="col-md-9">
											<input type="text" class="form-control" id="getSymptom" symptomsName="">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-row">
										<label class="col-sm-3">Catogery:</label>
										<div class="col-sm-7">
											<select class="getAllCategoriesSymptoms form-control" name="symptoms_category">
												<option disabled="">---Select Category---</option>
												@if(isset($symptomsCategories))
												@foreach($symptomsCategories as $category)
												<option value="{{ $category->flclass }}">{{ $category->flclass }}</option>
												@endforeach
												@endif
											</select>
										</div>
										<div class="col-sm-2">
											<a href="javascript:;" data-toggle="modal" class="btn btn-primary btn-sm-in" data-target="#insert_category_modal_symptoms"><i class="ri-add-box-fill"></i></a>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" rows="10" id="getSymDetail"></textarea>
							</div>
							<div class="form-row float-right">
								<a href="javascript:;" class="btn btn-action btn-primary mr-2" id="insert_symptoms" url="{{ route('insert.symptoms') }}"><i class="ri-add-line"></i> Add</a>

								<a href="javascript:;" class="btn btn-action btn-warning mr-2" id="update_symptoms" url="{{ route('update.symptoms') }}"><i class="ri-edit-2-fill"></i> Edit</a>

								<a href="javascript:;" class="btn btn-action btn-danger" id="delete_symptoms" url="{{ route('delete.symptoms') }}"><i class="ri-delete-bin-5-fill"></i> Delete</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="insert_category_modal_symptoms">
	<div class="modal-dialog ">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Variables</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group form-row align-items-center">
					<label class="col-sm-2">Symptoms</label>
					<div class="col-sm-8">
						<input type="text" id="symptoms_variable" class="form-control">
					</div>
					<div class="col-sm-2">
						<i class="ri-refresh-fill" id="reload_symptoms_variable_list"></i>
					</div>
				</div>
				<div class="d-flex">
					<a href="javascript:;" class="btn btn-primary mr-2" id="symptoms_variable_insert" url="{{ route('insert.variable.symptom') }}"><i class="ri-add-line"></i> Add</a>
					<a href="javascript:;" class="btn btn-danger" id="symptoms_variable_delete" url="{{ route('delete.variable.symptom') }}"><i class="ri-delete-bin-5-fill"></i> Delete</a>
				</div>
				<div class="res-table mt-3">
					<ul class="symptom-variables-box-list">
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
		// DataTable Search
		var table = $('table.datatable-symptoms').DataTable({
			"paging":   false
		});
		// Add Symptoms Variables
		$('#symptoms_variable_insert').click(function(){
			var flclass = $("#symptoms_variable").val();
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
						getSymptomsCategories();
		                //location.reload();
		            } else {
		            	alert(data.message);
		            }
		        }
		    });
		});

		function getSymptomsCategories(){
			$('.getAllCategoriesSymptoms').empty();
			$('.symptom-variables-box-list').empty();
			$.get('symptoms/variables/list', function(data) {
				$.each(data, function(index, getVariables) {
					$('.getAllCategoriesSymptoms').append('<option value="'+ getVariables.flclass +'">'+ getVariables.flclass +'</option>');
					$('.symptom-variables-box-list').append('<input type="radio" name="selected_variable_symptoms" id="radio-variable-'+getVariables.fldid+'" value="'+getVariables.fldid+'" rel="'+getVariables.flclass+'"><li><label for="radio-variable-'+getVariables.fldid+'">'+ getVariables.flclass +'</label></li>');
				});
			});
		}

		// Reload Function
		$('#reload_symptoms_variable_list').click(function(){
			getSymptomsCategories();
		});

		// Delete Symptoms Variables
		$('#symptoms_variable_delete').click(function(){
			var fldid = $("input[name='selected_variable_symptoms']:checked").val();
			var value = $("input[name='selected_variable_symptoms']:checked").attr('rel');
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
						$("input[name='selected_variable_symptoms']:checked + li").remove();
						$('select[name="symptoms_category"]').find('option[value="'+value+'"]').remove();
					} else {
						alert(data.message);
					}
				}
			});
		});

		// on click icd_group data
		$(document).on("click", "#getSelectedList tr", function () {
			var symptom = $(this).attr('rel');
			var category = $(this).attr('rel1');
			var symdetail = $(this).attr('rel2');
			$('#getSymptom').val(null);
			$('#getSymptom').val(symptom);
			$('#getSymptom').attr('symptomsName', symptom);
			$('#getSymDetail').text(null);
			$('#getSymDetail').text(symdetail);
			$('option:selected', 'select[name="symptoms_category"]').removeAttr('selected');
			$('select[name="symptoms_category"]').find('option[value="'+category+'"]').attr("selected",true);
		});

	    // Delete Symptoms
	    $('#delete_symptoms').click(function(){
	    	var fldsymptom = $("#getSymptom").val();
	    	var url = $(this).attr("url");
	    	var formData = {
	    		fldsymptom: fldsymptom
	    	};

	    	if(fldsymptom == '')
	    	{
	    		alert('Please Select Symptoms To Delete');
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
		                // reloadAllSymptoms();
		                location.reload();
		            } else {
		            	alert(data.message);
		            }
		        }
		    });
	    });

	    function reloadAllSymptoms()
	    {
	    	$('#getSelectedList').empty();
	    	$('#getSymptom').val(null);
	    	$('#getSymDetail').val(null);
	    	$('option:selected', 'select[name="symptoms_category"]').removeAttr('selected');
	    	$.get('symptoms/list', function(data) {
	    		$.each(data, function(index, getSymptoms) {
	    			$('#getSelectedList').append('<tr rel="'+ getSymptoms.fldsymptom +'" rel1="'+ getSymptoms.fldcategory +'" rel2="'+ getSymptoms.fldsymdetail +'"><td class="dietary-td">'+ getSymptoms.fldsymptom +'</td></tr>');
	    		});
	    	});
	    }

		// Insert Symptoms
		$('#insert_symptoms').click(function(){
			var symptom = $("#getSymptom").val();
			var category = $(".getAllCategoriesSymptoms option:selected").val();
			var detail = $("#getSymDetail").val();
			var url = $(this).attr("url");
			var formData = {
				symptom: symptom,
				category: category,
				detail: detail
			};

			if(symptom == '' || category == '')
			{
				alert('Insuffecient Values');
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
		                // reloadAllSymptoms();
		                location.reload();
		            } else {
		            	alert(data.message);
		            }
		        }
		    });
		});

		// Update Symptoms
		$('#update_symptoms').click(function(){
			var symptomid = $("#getSymptom").attr('symptomsName');
			var symptom = $("#getSymptom").val();
			var category = $(".getAllCategoriesSymptoms option:selected").val();
			var detail = $("#getSymDetail").val();
			var url = $(this).attr("url");
			var formData = {
				symptomid: symptomid,
				symptom: symptom,
				category: category,
				detail: detail
			};

			if(symptom == '' || category == '')
			{
				alert('Insuffecient Values');
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
		                // reloadAllSymptoms();
		                location.reload();
		            } else {
		            	alert(data.message);
		            }
		        }
		    });
		});

	</script>
	@endpush
