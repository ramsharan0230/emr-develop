<div class="row">
	<div class="col-md-5">
		<select name="examination_type" class="form-control form-control-sm" id="examination_type">
			<option value=""></option>
			<option value="Qualitative">Qualitative</option>
			<option value="Quantitative">Quantitative</option>
			
		</select>
		<div class="table-responsive table_height">
            <table class="table table-sm table-bordered">
                
                <tbody id="examination_type_list">
                   
                </tbody>
            </table>
        </div>
	</div>
	<div class="col-md-2">
		<button class="btn btn-primary" onclick="addExamination()"><i class="fas fa-arrow-right"></i></button>
	</div>
	<div class="col-md-5">
		<div class="table-responsive table_height">
            <table class="table table-sm table-bordered">
                
                <tbody id="examination_added_list" style="height: 30px; overflow-y: scroll;">
                   @if(isset($examinations) and count($examinations) > 0)
                   		@foreach($examinations as $e)
                   			<tr><td>{{$e->fldexamid}}</td></tr>
                   		@endforeach
                   @endif
                </tbody>
            </table>
        </div>
	</div>
</div>
<script type="text/javascript">
	$('#examination_type').on('change',function(){
		var type = $(this).val();
		var cat = $('#examination_category').val();
		var comp = $('#target').val();
		// alert(comp);
		$.ajax({
            url: '{{ route('list.examination.bytype.form.activity.consultant') }}',
            type: "POST",
            data: {type:type,category:cat,comp:comp,"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#examination_type_list').empty().html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	})

	function addExamination(){
			if ($("input[name='exam_value']:checked").prop('checked')==true){ 
		        var favorite = [];
	            $.each($("input[name='exam_value']:checked"), function(){
	                favorite.push($(this).val());
	                var whichtr = $(this).closest("tr");
	                whichtr.remove();
	            });
	            // alert("My favourite sports are: " + favorite.join(", "));
	            var exams = favorite.join(",");
	            var comp = $('#target').val();
	            var cat = $('#examination_category').val();
	            var rowCount = $('#examination_list tr').length;
		        
	        	$.ajax({
		            url: '{{ route('add.examination.form.activity.consultant') }}',
		            type: "POST",
		            data: {exams:exams,category:cat,comp:comp,count:rowCount,"_token": "{{ csrf_token() }}"},
		            success: function (response) {
		                $('#examination_added_list').append(response.html);
		                $('.empty_exam').remove();
		                
		                $('#examination_list').append(response.mainhtml);
		            },
		            error: function (xhr, status, error) {
		                var errorMessage = xhr.status + ': ' + xhr.statusText;
		                console.log(xhr);
		            }
		        });
		    }else{
		    	alert('Please select examination to add');
		    	return false;
		    }
            
	}
</script>