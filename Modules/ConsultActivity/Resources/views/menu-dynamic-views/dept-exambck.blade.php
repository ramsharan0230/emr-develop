<div class="form-group row">
	<div class="col-md-8">
		<div class="form-group">
			<label for="name" class="col-form-label col-form-label-sm">Department</label>
			<select name="proc_department" class="col-sm-6 form-control form-control-sm" id="proc_department">
				<option value=""></option>
					@if(isset($department) and count($department) > 0)
						@foreach($department as $d)
							<option value="{{$d->fldprocname}}">{{$d->fldprocname}}</option>
						@endforeach	
					@endif
				</select>
			<div class="col-sm-2">
				<a href="javascript:void(0);" class="btn btn-primary" onclick="deptexam.addProcModal()"><i class="fas fa-plus-square"></i></a>
			</div>
			<a href="javascript:void(0);" class="btn btn-primary" onclick="listExamByProcName()"><i class="fas fa-sync"></i></a>
		
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="form-group">
			<a href="javascript:void(0);" class="btn btn-primary" onclick="deptExamPdf()">Export</a>
		</div>
	</div>
</div>
<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
		<label for="name" class="col-form-label col-form-label-sm">Exam Label</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="exam_label" id="exam_label" class="form-control form-control-sm">
		</div>
	</div>
	
</div>
<div class="form-group row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="name" class="col-form-label col-form-label-sm">Data Type</label>
		
			<select name="data_type" id="data_type" class="form-control form-control-sm">
				<option value=""></option>
				<option value="Qualitative">Qualitative</option>
				<option value="Quantitative">Quantitative</option>
			</select>
		</div>
		
		
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="name" class="col-form-label col-form-label-sm">Sys Const</label>
		
			<select name="sys_const" id="sys_const" class="form-control form-control-sm">
				
			</select>
		</div>
		
		
	</div>
</div>
<div class="form-group row">

	<div class="col-md-6">
		<div class="form-group">
			<label for="name" class="col-form-label col-form-label-sm">Option Type</label>
		
			<select name="option" id="option" class="form-control form-control-sm">
				<option value="No Selection">No Selection</option>
				<option value="Single Selection">Single Selection</option>
				<option value="Dichotomous">Dichotomous</option>
				<option value="Multiple Selection">Multiple Selection</option>
				<option value="Left and Right">Left and Right</option>
				<option value="Date and Time">Date and Time</option>
				<option value="Text Table">Text Table</option>
				<option value="Qualitative">Qualitative</option>
				<option value="SysConst">SysConst</option>
				
				
			</select>
		</div>
		
		
	</div>
	<div class="col-md-6">
		<a href="javascript:void(0);" class="btn btn-primary" onclick="addDepExamination()"><i class="fas fa-plus-square"></i> Add</a>
		<a href="javascript:void(0);" class="btn btn-primary" onclick="deptexam.addOption()"><i class="fas fa-edit"></i> Options</a>
		<a href="javascript:void(0);" class="btn btn-primary" onclick="editExamination()"><i class="fas fa-edit"></i> Edit</a>
	</div>
</div>
<div class="form-group row">
	<div class="table-responsive table_height">
        <table class="table table-sm table-bordered">
            <thead>
            	<th></th>
            	<th>Variable</th>
            	<th>Examination</th>
            	<th>SysConstant</th>
            	<th>Option</th>
            	<th></th>
            </thead>
            <tbody id="dept_exam_list">
              
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
	$('#data_type').on('change', function(){
		var type = $(this).val();
		$.ajax({
            url: '{{ route('list.sysconst.deptexam.activity.consultant') }}',
            type: "POST",
            data: {type:type,"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#sys_const').html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	})

	function addDepExamination(){
		var dept = $('#proc_department').val();
		var label = $('#exam_label').val();
		var data_type = $('#data_type').val();
		var syscon = $('#sys_const').val();
		var option = $('#option').val();
		if(dept != '' && label !='' && data_type !='' && syscon !='' && option !=''){
			$.ajax({
	            url: '{{ route('add.deptexam.activity.consultant') }}',
	            type: "POST",
	            data: {dept:dept,label:label,data_type:data_type,syscon:syscon,option:option,"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	                $('#dept_exam_list').html(response);
	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
		}else{
			alert('Field Missing !!');
			return false
		}
	}

	function editExamination(){
		// alert('here');
		var dept = $('#proc_department').val();
		var label = $('#exam_label').val();
		var data_type = $('#data_type').val();
		var syscon = $('#sys_const').val();
		var option = $('#option').val();
		
		if ($('.procname_variable').is(":checked")){ 
			var fldid = $('.procname_variable').val();
			// alert(fldid);
			if(dept != '' && label !='' && data_type !='' && syscon !='' && option !=''){
				$.ajax({
		            url: '{{ route('edit.deptexam.activity.consultant') }}',
		            type: "POST",
		            data: {fldid:fldid,dept:dept,label:label,data_type:data_type,syscon:syscon,option:option,"_token": "{{ csrf_token() }}"},
		            success: function (response) {
		                $('#dept_exam_list').empty().html(response);
		            },
		            error: function (xhr, status, error) {
		                var errorMessage = xhr.status + ': ' + xhr.statusText;
		                console.log(xhr);
		            }
		        });
			}else{
				alert('Field Missing !!');
				
			}
		}else{
			alert('Please choose examination to update');
			
		}
		
	}

	function listExamByProcName(){
		var dept = $('#proc_department').val();
		if(dept !=''){
			$.ajax({
	            url: '{{ route('list.deptexam.activity.consultant') }}',
	            type: "POST",
	            data: {dept:dept,"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	                $('#dept_exam_list').empty().html(response);
	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
		}else{
			return false;
		}
	}

	function deptExamPdf(){
        if ($('#proc_department').val() == "") {
            alert('Please select department.');
            return false;
        }
       $.ajax({
            url: '{{ route('export.dept.exam.activity.consultant') }}',
            type: "POST",
            data: {dept:$('#proc_department').val(),"_token": "{{ csrf_token() }}"},
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
               
                var filename = "";                   
                var disposition = xhr.getResponseHeader('Content-Disposition');

                 if (disposition) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                } 
                var linkelem = document.createElement('a');
                try {
                                           var blob = new Blob([response], { type: 'application/octet-stream' });                        

                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) { 
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");

                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.target = "_blank";
                                a.click();
                            }
                        } else {
                            window.location = downloadUrl;
                        }
                    }   

                } catch (ex) {
                    console.log(ex);
                } 
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
    // deleteDeptExam()
    function deleteDeptExam(val){

    	if(val !=''){
			$.ajax({
	            url: '{{ route('delete.deptexam.activity.consultant') }}',
	            type: "POST",
	            data: {val:val,dept:$('#proc_department').val(),"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	                $('#dept_exam_list').empty().html(response);
	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
		}else{
			return false;
		}
    }
</script>