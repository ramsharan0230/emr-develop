<div class="form-group row">
	<div class="col-md-12">
		<input type="text" name="variable_name" id="variable_name" class="form-control form-control-sm">
	</div>	

</div>
<div class="form-group row">
	<div class="col-md-4">
			<a href="javascript:void(0);" class="btn btn-primary" onclick="addVariable()"><i class="fas fa-plus-square"></i> Add</a>
		</div>
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<a href="javascript:void(0);" class="btn btn-primary" onclick="deleteProc()"><i class="fas fa-times"></i> Delete</a>
	</div>
</div>
<div class="form-group row">
	<div class="table-responsive table_height">
        <table class="table table-sm table-bordered">
          
            <tbody id="proc_list" >
               @if(isset($procname) and count($procname) > 0)
               	  @foreach($procname as $p)
               	  	<tr>
               	  		<td><input type="checkbox" name="procname" class="procname" value="{{$p->fldprocname}}"> {{$p->fldprocname}}</td>
               	  		
               	  	</tr>
               	  @endforeach
               @endif
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
	function addVariable(){
		var variable = $('#variable_name').val();
		if (variable.length == 0)
	      { 
	         alert("Nothing to add");  	
	         return false; 
	      }else{
	      	$.ajax({
	            url: '{{ route('add.variable.form.activity.consultant') }}',
	            type: "POST",
	            data: {variable:variable,"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	                $('#proc_list').empty().html(response.ahtml);
	                $('#proc_department').append(response.optionhtml);
	                
	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
	      }
		
	}
	function deleteProc(){
		if ($("input[name='procname']:checked").prop('checked')==true){ 
		        var favorite = [];
	            $.each($("input[name='procname']:checked"), function(){
	                favorite.push($(this).val());
	                // alert(favorite);
	                $("#proc_department option[value='"+favorite+"']").remove();
	            });
	            // alert("My favourite sports are: " + favorite.join(", "));
	            var procs = favorite.join(",");
	            
	        	$.ajax({
		            url: '{{ route('delete.procname.form.activity.consultant') }}',
		            type: "POST",
		            data: {procs:procs,"_token": "{{ csrf_token() }}"},
		            success: function (response) {
		            	$('#proc_list').empty().html(response.html);
                		// $('#proc_department').append(response.mainhtml);
		            },
		            error: function (xhr, status, error) {
		                var errorMessage = xhr.status + ': ' + xhr.statusText;
		                console.log(xhr);
		            }
		        });
		    }else{
		    	alert('Please select Proc Name to delete');
		    	return false;
		    }
	}
</script>