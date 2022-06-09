<div class="form-row">
    @php
        $hospital_department = Helpers::getDepartmentAndComp();
    @endphp
	<div class="col-md-4">
		<div class="form-group">

			<select name="dept" class="form-control form-control-sm" id="dept">
				<option value="%">%</option>
                @if($hospital_department)
                    @forelse($hospital_department as $dept)
                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                    @empty
                    @endforelse
                @endif
			</select>

		</div>
	</div>
	<div class="col-md-1">
		<div class="form-group">
			<a href="javascript:void(0);" onclick="listReport('comp')"><i class="fas fa-sync"></i></a>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group" >
			<input type="text" name="" readonly="" value="Empty" class="form-control form-control-sm" style="float: right;">
		</div>

	</div>
	<div class="col-md-3">
		<div class="form-group">
			<button class="btn btn-primary fa-btn-icon-o" onclick="showAllPlan()"><i class="fa fa-list"></i><a href="javascript:void(0);" ></a>Show all</button>
		</div>
	</div>

</div>
<div class="form-group form-row">
	<div class="col-md-6">
		<div class="form-group form-row">
			<label for="name" class="col-sm-4 col-form-label col-form-label-sm">Date Type:</label>
			<div class="col-sm-7">
				<select name="date_type" class="form-control form-control-sm" id="date_type">

					<option value="AllDays">AllDays</option>
					<option value="Specific">Specific</option>
					<option value="Sunday">Sunday</option>
					<option value="Monday">Monday</option>
					<option value="Tuesday">Tuesday</option>
					<option value="Thursday">Thursday</option>
					<option value="Friday">Friday</option>
					<option value="Saturday">Saturday</option>
					<option value="%">%</option>
				</select>
			</div>
		</div>
		<div class="form-group form-row">
			<label for="name" class="col-sm-4 col-form-label col-form-label-sm">Method:</label>
			<div class="col-sm-7">
				<select name="method" class="form-control form-control-sm" id="method">
					<option value="AllParam">AllParam</option>
					<option value="Consultation+Mode">Consultation+Mode</option>
					<option value="ConsultOnly">ConsultOnly</option>
				</select>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group form-row">
			<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Date:</label>
			<div class="col-sm-10">
				<div class="input-group">
                    <input type="text" name="date" class="form-control form-control-sm" id="date" value="{{date('Y-m-d')}}" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="far fa-calendar-alt fa-1x"></i>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="form-group form-row">
			<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Mode:</label>
			<div class="col-sm-8">
				<select name="mode" class="form-control form-control-sm " id="modedata" >
                    <option value="%">%</option>
                    @if(isset($mode) and count($mode) > 0)
                        @foreach($mode as $m)
                            <option value="{{$m->fldsetname}}">{{$m->fldsetname}}</option>
                        @endforeach
                    @endif
                </select>
			</div>
			<div class="col-md-2">
				<a href="javascript:void(0);" class="" onclick="listReport('mode')"><i class="fas fa-sync"></i></a>
			</div>
		</div>
	</div>
</div>
<div class="form-group form-row">
	<div class="col-md-3">
		<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Department:</label>

	</div>
	<div class="col-md-9">
		<select name="department" class="form-control form-control-sm" id="user_dept">
			<option value=""></option>
			@if(isset($department) and count($department) > 0)
				@foreach($department as $d)
					<option value="{{$d->flddept}}">{{$d->flddept}}</option>
				@endforeach
			@endif
		</select>
	</div>
</div>
<div class="form-group form-row">
	<div class="col-md-3">
		<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Name:</label>

	</div>
	<div class="col-md-9">
		<input type="text" name="username" class="form-control form-control-sm" id="username">
	</div>
</div>
<div class="form-group form-row">
	<div class="col-md-3">
		<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Comment:</label>

	</div>
	<div class="col-md-9">
		<input type="text" name="commentuser" class="form-control form-control-sm" id="commentuser">

	</div>
</div>
<div class="form-group form-row">
	<div class="col-md-3">
		<label for="name" class="col-sm-2 col-form-label col-form-label-sm">Allocation:</label>

	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-3">
				<input type="text" name="allocation" id="allocation" class="col-sm-10 form-control form-control-sm">
			</div>
			<div class="col-sm-9">
				<button class="btn btn-primary"><a href="javascript:void(0);" id="addfunction"><i class="fas fa-plus"></i> Add</a></button>
				<button class="btn btn-warning"><a href="javascript:void(0);" id="editfunction"><i class="fas fa-edit"></i> Edit</a></button>
				<button class="btn btn-warning"><a href="javascript:void(0);" onclick="exportData()"><i class="fas fa-file-export"></i></a></button>
				<!-- <input type="checkbox" name=""> -->
			</div>
		</div>

	</div>
</div>
	<div class="row">
        <div class="res-table">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-light">
                    <th class="tittle-th" width="20"></th>
                    <th class="tittle-th" width="40">Method</th>
                    <th class="tittle-th" width="200">Timing</th>
                    <th class="tittle-th" width="40">Date</th>
                    <th class="tittle-th" width="40">Mode</th>
                    <th class="tittle-th" width="40">Department</th>
                    <th class="tittle-th" width="40">UserName</th>
                    <th class="tittle-th" width="40">Limit</th>
                    <th class="tittle-th" width="40">Reason</th>
                </thead>
                <tbody id="user_posting">
                    @if(isset($result) and count($result) > 0)
                        @foreach($result as $k=>$data)
                        <tr>
                            <td><input type="checkbox" value="{{$data->fldid}}" class="user_fldid"></td>
                            <td>{{$data->fldmethod}}</td>
                            <td>{{$data->fldselect}}</td>
                            <td>{{$data->flddate}}</td>
                            <td>{{$data->fldbillingmode}}</td>
                            <td>{{$data->flddept}}</td>
                            <td>{{$data->flduserid}}</td>
                            <td>{{$data->fldquota}}</td>
                            <td>{{$data->fldreason}}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

<script type="text/javascript">
	$('#date_type').on('change', function(){
		var type = $(this).val();
		if(type == 'Specific'){
			$('#date').prop('readonly', false);
			$("#date").datepicker();
		}else{
			$('#date').prop('readonly', true);
			$("#date").datepicker("destroy");
		}
	});
	$('#date').datetimepicker({
		changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "1600:2032",
	});
	$('#method').on('change',function(){
		// alert('method chagnes');
		var methodtype = $(this).val();
		// alert(methotype);
		if(methodtype == 'Consultation+Mode'){
			$('#user_dept').prop('disabled', 'disabled');

		}else if(methodtype == 'ConsultOnly'){
			// alert('elseif');
			$('#modedata').prop('disabled', 'disabled');
			$('#user_dept').prop('disabled', 'disabled');
		}else{
			$('#modedata').prop('disabled',false);
			$('#user_dept').prop('disabled',false);
		}
	});



	$('#addfunction').on('click', function(){
		// alert($("#commentuser").val());
		$.ajax({
            url: '{{ route('add.userposting.form.activity.consultant') }}',
            type: "POST",
            data: {comp: $('#dept').val(), dateType:$('#date_type').val(), method:$('#method').val(), mode:$('#modedata').val(), department:$('#user_dept').val(),username:$('#username').val(),comment:$('#commentuser').val(),allocation:$('#allocation').val(),date:$('#date').val(),"_token": "{{ csrf_token() }}"},
            success: function (data) {
            	$('#user_posting').empty();
                $('#user_posting').html(data);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
	});
	$(document).on('click','#editfunction', function(){

      if ($('.user_fldid').is(":checked"))
        {
            // var proID = $('.procedureId:checked').val()
            $.ajax({
	            url: '{{ route('update.userposting.form.activity.consultant') }}',
	            type: "POST",
	            data: {fldid: $('.user_fldid:checked').val(),comp: $('#dept').val(), dateType:$('#date_type').val(), method:$('#method').val(), mode:$('#modedata').val(), department:$('#user_dept').val(),username:$('#username').val(),comment:$('#commentuser').val(),allocation:$('#allocation').val(),date:$('#date').val(),"_token": "{{ csrf_token() }}"},
	            success: function (data) {
	            	$('#user_posting').empty();
	                $('#user_posting').html(data);

	            },
	            error: function (xhr, status, error) {
	                var errorMessage = xhr.status + ': ' + xhr.statusText;
	                console.log(xhr);
	            }
	        });
        }else{
            alert('Please select consultation plan.');
            return false;

        }


  });

	function listReport(val){
		var compdept = $('#dept').val();
		var modeval = $('#modedata').val();

		$.ajax({
            url: '{{ route('list.data.userposting.form.activity.consultant') }}',
            type: "POST",
            data: {type: val, mode:modeval, deart:compdept,"_token": "{{ csrf_token() }}"},
            success: function (data) {
            	$('#user_posting').empty();
                $('#user_posting').html(data);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


	}

	function exportData(){

       $.ajax({
            url: '{{ route('export.data.userposting.form.activity.consultant') }}',
            type: "POST",
            data: {"_token": "{{ csrf_token() }}"},
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

    function showAllPlan(){
    	$.ajax({
            url: '{{ route('showall.data.userposting.form.activity.consultant') }}',
            type: "POST",
            data: {"_token": "{{ csrf_token() }}"},
            success: function (data) {
            	$('#user_posting').empty();
                $('#user_posting').html(data);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>
