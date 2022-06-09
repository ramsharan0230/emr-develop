<div class="form-group row">
	<div class="form-group">
		<div class="col-md-4">
			<label class="col-form-label col-form-label-sm">Group Name</label>
		</div>
		<div class="col-md-6">
			<select name="proc_group_name" id="proc_group_name" class="form-control form-control-sm">
				<option value=""></option>
				@if(isset($groups) and count($groups) >0)
					@foreach($groups as $g)
						<option value="{{$g->fldgroupname}}">{{$g->fldgroupname}}</option>
					@endforeach
				@endif
			</select>
		</div>
		<div class="col-md-2">
			<button class="default-btn" onclick="listByGroup()"><i class="fas fa-sync"></i><a href="javascript:void(0);" ></a></button>
		</div>
	</div>

</div>
<div class="form-group row">
	<div class="form-group">
		<div class="col-md-3">
			<label class="col-form-label col-form-label-sm">Procedures</label>
		</div>
		<div class="col-md-9">
			<input type="text" name="procedure_name" id="procedure_name" class="form-control form-control-sm">
        </div>
	</div>
</div>
<div class="form-group row">
	<div class="col-md-8">
		<button class="default-btn f-btn-icon-e" onclick="exportProcGroupToPdf()"><i class="fas fa-external-link-square-alt"></i><a href="javascript:void(0);" ></a>&nbsp;&nbsp;Export</button>
         <button class="default-btn f-btn-icon-b" onclick="addGroupProc()"><i class="fas fa-plus"></i><a href="javascript:void(0);" ></a>&nbsp;Add</button>
         <button class="default-btn f-btn-icon-s" onclick="deleteGroupProc()"><i class="far fa-trash-alt"></i><a href="javascript:void(0);" ></a>&nbsp;&nbsp;Delete</button>
	</div>
</div>
<div class="form-group row mt-2">
	<div class="col-md-12">
        <div class="table-responsive table-scroll-consultmodal">
            <table class="table table-sm table-bordered">
              <thead>
                <th class="tittle-th"></th>
                <th class="tittle-th">Group Name</th>
                <th class="tittle-th">Procedures</th>
              </thead>
                <tbody id="extra_proc_list" >
                   @if(isset($groups) and count($groups) > 0)
                      @foreach($groups as $g)
                        <tr>
                            <td><input type="checkbox" name="proc_group_name" class="proc_group_name" value="{{$g->fldid}}"> </td>
                            <td>{{$g->fldgroupname}}</td>
                            <td>{{$g->fldprocname}}</td>
                        </tr>
                      @endforeach
                   @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
	function addGroupProc(){
		$.ajax({
            url: '{{ route('add.group.proc.groups.consultant') }}',
            type: "POST",
            data: {group:$('#proc_group_name').val(),proc:$('#procedure_name').val(),"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#extra_proc_list').empty().html(response);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	}
	function deleteGroupProc(){
		if ($("input[name='proc_group_name']:checked").prop('checked')==true){
	        var favorite = [];
            $.each($("input[name='proc_group_name']:checked"), function(){
                favorite.push($(this).val());

            });
            // alert("My favourite sports are: " + favorite.join(", "));
            var flds = favorite.join(",");

        	$.ajax({
	            url: '{{ route('delete.procgroup.groups.consultant') }}',
	            type: "POST",
	            data: {fldids:flds,"_token": "{{ csrf_token() }}"},
	            success: function (response) {
	            	$('#extra_proc_list').empty().html(response);

	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
	    }else{
	    	alert('Please select procedure to delete');
	    	return false;
	    }
	}

	function listByGroup(){
		$.ajax({
            url: '{{ route('list.by.group.groups.consultant') }}',
            type: "POST",
            data: {group:$('#proc_group_name').val(),"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#extra_proc_list').empty().html(response);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	}


	function exportProcGroupToPdf(){

       $.ajax({
            url: '{{ route('export.proc.group.groups.consultant') }}',
            type: "POST",
            data: {group:$('#proc_group_name').val(),"_token": "{{ csrf_token() }}"},
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
</script>
