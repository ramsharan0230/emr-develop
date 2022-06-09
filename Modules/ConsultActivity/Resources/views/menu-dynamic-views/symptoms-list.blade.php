<div class="row">
	<div class="col-md-5">
		<select name="symptom_category" class="form-control form-control-sm" id="symptom_category">
			@if(isset($category) and count($category) > 0)
				@foreach($category as $c)
					<option value="{{$c->fldcategory}}">{{$c->fldcategory}}</option>
				@endforeach
			@endif
			
		</select>
		<div class="table-responsive table_height">
            <table class="table table-sm table-bordered">
                
                <tbody id="cat_symptom_list">
                   
                </tbody>
            </table>
        </div>
	</div>
	<div class="col-md-2">
		<button class="btn btn-primary" onclick="addSymptom()"><i class="fas fa-arrow-right"></i></button>
	</div>
	<div class="col-md-5">
		<div class="table-responsive table_height">
            <table class="table table-sm table-bordered">
                
                <tbody id="symptom_list" style="height: 30px; overflow-y: scroll;">
                   @if(isset($complaints) and count($complaints) > 0)
                   		@foreach($complaints as $c)
                   			<tr><td>{{$c->fldsymptom}}</td></tr>
                   		@endforeach
                   @endif
                </tbody>
            </table>
        </div>
	</div>
</div>
<script type="text/javascript">
	$('#symptom_category').on('change',function(){
		var cat = $(this).val();
		var comp = $('#target').val();
		// alert(comp);
		$.ajax({
            url: '{{ route('list.symptoms.form.activity.consultant') }}',
            type: "POST",
            data: {category:cat,target:comp,"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#cat_symptom_list').append().html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	})

	function addSymptom(){
			if ($("input[name='symptom_value']:checked").prop('checked')==true){ 
		        var favorite = [];
	            $.each($("input[name='symptom_value']:checked"), function(){
	                favorite.push($(this).val());
	                var whichtr = $(this).closest("tr");
	                whichtr.remove();
	                
	            });
	            // alert("My favourite sports are: " + favorite.join(", "));
	            var symp = favorite.join(",");
	            var comp = $('#target').val();
	            var cat = $('#symptom_category').val();
	        	$.ajax({
		            url: '{{ route('add.symptoms.form.activity.consultant') }}',
		            type: "POST",
		            dataType : 'json',
		            data: {symptoms:symp,cat:cat,comp:comp,"_token": "{{ csrf_token() }}"},
		            success: function (response) {
		                $('#symptom_list').append(response.html);
		                $('#complaint_list').empty().append(response.mhtml);
		            },
		            error: function (xhr, status, error) {
		                var errorMessage = xhr.status + ': ' + xhr.statusText;
		                console.log(xhr);
		            }
		        });
		    }else{
		    	alert('Please select symptom to add');
		    	return false;
		    }
            
	}
</script>