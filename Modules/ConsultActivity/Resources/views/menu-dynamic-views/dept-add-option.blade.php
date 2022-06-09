<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
			<label class="col-form-label col-form-label-sm">Test</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="test" id="test" value="{{$exam_label}}" readonly="">
		</div>
	</div>
	
</div>
<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
			<label class="col-form-label col-form-label-sm">Sub Test</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="sub_test" id="sub_test" value="{{$department}}" readonly="">
		</div>
	</div>
	
</div>
<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
			<label class="col-form-label col-form-label-sm">Option Type</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="option_type" id="option_type" value="{{$option}}" readonly="">
		</div>
	</div>
	
</div>
<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
			<label class="col-form-label col-form-label-sm">Options</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="options" id="options" value="" >
		</div>
	</div>
	
</div>
<div class="form-group row">
	
		<div class="col-md-4">
			<!-- <label class="col-form-label col-form-label-sm">Options</label> -->
		</div>
		<div class="col-md-4">
			<a href="javascript:void(0);" class="btn btn-primary" id="addoption" onclick="addQualitativeOptions()"><i class="fas fa-plus-square"></i> Add</a>
			<a href="javascript:void(0);" class="btn btn-primary" id="deleteoption" onclick="deleteQualitativeOptions()"><i class="fas fa-times"></i> Delete</a>
		</div>
	
	
</div>
<div class="table-responsive table_height">
    <table class="table table-sm table-bordered">
        
        <tbody id="qualitative_option_list">
          	@if(isset($result) and count($result) > 0)
          		@foreach($result as $r)
          			<tr><td><input type="checkbox" name="dept_exam_option" class="dept_exam_option" value="{{$r->fldid}}">{{$r->fldanswer}}</td></tr>
          		@endforeach
          	@endif
        </tbody>
    </table>
</div>
<script type="text/javascript">
	function addQualitativeOptions(){
		var opts = $('#options').val();
		if(opts === ''){
			alert('Data Not Found');
			return false;
		}
		$.ajax({
            url: '{{ route('add.options.deptexam.activity.consultant') }}',
            type: "POST",
            data: {test:$('#test').val(),sub_test:$('#sub_test').val(),option_type:$('#option_type').val(),option:$('#options').val(),"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#qualitative_option_list').empty().html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	}
	function deleteQualitativeOptions(){
		if ($('.dept_exam_option').is(":checked")){

			var favorite = [];
            $.each($("input[name='dept_exam_option']:checked"), function(){
                favorite.push($(this).val());
                var whichtr = $(this).closest("tr");
                whichtr.remove();
            });
            // alert("My favourite sports are: " + favorite.join(", "));
            var fldid = favorite.join(",");
			// alert(fldid);
			// var fldid = $('.dept_exam_option').val();
			$.ajax({
	            url: '{{ route('delete.options.deptexam.activity.consultant') }}',
	            type: "POST",
	            data: {fldid:fldid,"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	                
	                showAlert("Information saved!!");
	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
		}else{
			alert('Choose option to delete');
		}
		
	}
</script>