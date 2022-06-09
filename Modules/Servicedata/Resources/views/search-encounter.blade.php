<style type="text/css">
	.modal-content {
		width: 70%
	}
	.form-group-inner.custom-9 {
		width: 100%;
		height: 125px;
	}
</style>
<div class="form-group row">
	<div class="col-md-12">
		<input type="text" name="file_no" id="file_no" placeholder="File No." class="form-control form-control-sm">
		
	</div>
</div>
<div class="form-group row">
	<div class="col-md-12 mt-2">
		<input type="text" name="enc_id" id="enc_id" placeholder="EncID" class="form-control form-control-sm">
		
	</div>
</div>
<div class="form-group row">
	<div class="col-md-6 mt-2">
		<input type="text" name="" class="form-control form-control-sm" placeholder="English Date" id="reg_date">
	</div>
	<div class="col-md-6 mt-2">
		<input type="text" name="" class="form-control form-control-sm" placeholder="Nepali Date" id="nep_reg_date">
	</div>
</div>
<div class="form-group row">
	<div class="col-md-12 mt-2">
		<input type="text" name="pat_no" id="pat_no" placeholder="Pat No." class="form-control form-control-sm">
		
	</div>
</div>
<div class="form-group row">
	<div class="col-md-12 mt-3">
		<a href="javascript:void(0);" class="btn btn-primary btn-block" onclick="searchpatient()" id="showlist">Show List</a>
	</div>
	
</div>
<div class="form-group row">
	<div class="form-group-inner custom-9" id="list_patient">

	</div>
</div>
<script type="text/javascript">
	function searchpatient(){
		// $("#showlist").removeAttr('onclick');
		// $("#showlist").removeClass('btn btn-primary btn-block');
		$("#showlist").addClass('btn btn-block');
		var fileno = $('#file_no').val();
		var encounter = $('#enc_id').val();
		var patientno = $('#pat_no').val();
		$.ajax({
			url: '{{ route('search.patient.list.consultant') }}',
			type: "POST",
			data: {fileno: fileno, encounterID:encounter, patientno:patientno,"_token": "{{ csrf_token() }}"},
			success: function (data) {

				$('#list_patient').html(data.html);
				$('#pat_no').val(data.patientval);
				$('#enc_id').val(data.encounterval);
				$('#reg_date').val(data.regdate);
				$('#nep_reg_date').val(data.nepaliregdate);
			},
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText;
				console.log(xhr);
			}
		});
	}
	$('#reg_date').datetimepicker({

		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "1600:2032",

	});
	var encounterDetail = function(val){
		
		$.ajax({
			url: '{{ route('search.encounter.detail.consultant') }}',
			type: "POST",
			data: {encounterID:val, "_token": "{{ csrf_token() }}"},
			success: function (data) {

				$('#reg_date').val(data.regdate);
				$('#nep_reg_date').val(data.nepaliregdate);
			},
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText;
				console.log(xhr);
			}
		});
	}

	// $('#showlist').on('click', function(){
	// 	e.preventDefault();
	// });
</script>