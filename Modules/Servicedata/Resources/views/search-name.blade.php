
<h6 style="margin-bottom: 12px;">Search Patient Name:</h6>
<div class="form-group row">
	<div class="col-md-2">
		<label for="name" class="col-form-label col-form-label-sm">First Name:</label>
	</div>
	<div class="col-md-10">
		<input type="text" name="firstname" id="firstname" class="form-control form-control-sm" value="%">
		
	</div>
</div>
<div class="form-group row">
	<div class="col-md-2">
		<label for="name" class=" col-form-label col-form-label-sm">Last Name:</label>
	</div>
	<div class="col-md-10">
		<input type="text" name="lastname" id="lastname" class="form-control form-control-sm" value="%">
		
	</div>
</div>
<div class="form-group row">
	<div class="col-md-8">
		<label for="name" class=" col-form-label col-form-label-sm"></label>
	</div>
	<div class="col-md-4">
		<a href="javascript:void(0);" onclick="searchbyname()" class="btn btn-primary " id="searchbyname" >Ok</a>&nbsp;
		<a href="javascript:void(0);" class="btn btn-secondary onclose" data-dismiss="modal" aria-hidden="true">Close</a>
		
	</div>
</div>

<script type="text/javascript">
	function searchbyname(){
		
		var firstname = $('#firstname').val();
		var lastname = $('#lastname').val();
		// var patientno = $('#pat_no').val();
		$.ajax({
            url: '{{ route('search.patient.name.consultant') }}',
            type: "POST",
            data: {firstname: firstname, lastname:lastname,"_token": "{{ csrf_token() }}"},
            success: function (response) {
            	$('#consultation_data').empty();
                $('#consultation_data').html(response);
                
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