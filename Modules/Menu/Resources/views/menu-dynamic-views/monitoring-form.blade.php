<form class="">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="searchName" class="form-control" id="name" placeholder="Name" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{  $patient->fldptnamefir }} {{ $patient->fldmidname }} {{  $patient->fldptnamelast }}@endif" readonly>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="gender" class="col-sm-2">Gender</label>
                <div class="col-sm-10">
                    <input type="text" name="searchSurName" class="form-control" id="SurName" placeholder="Gender" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-2">
            <div class="form-group">
                <select name="type" class="form-control" id="type">
                    <option value=""></option>
                    <option value="Test">Test</option>
                    <option value="Exam">Exam</option>
                    <option value="Radio">Radio</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <select name="particulars" class="form-control" id="particulars">
                    <option value="0">---select---</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="text" name="freq" id="freq" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select name="unit" class="form-control" id="unit">
                    <option value="Unit">Unit</option>
                    <option value="Minute">Minute</option>
                    <option value="Hour">Hour</option>
                    <option value="Day">Day</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <a href="javascript:void(0);" onclick="addMonitor()" class="btn btn-primary"><i class="ri-add-fill"></i></a>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="res-table">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Type</th>
                            <th>Particulars</th>
                            <th>Freq</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="monitor_data">
                        @if(isset($monitordata) and count($monitordata) > 0)
                            @foreach($monitordata as $k=>$data)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$data->fldcategory}}</td>
                                <td>{{$data->flditem}}</td>
                                <td>{{$data->fldevery}}</td>
                                <td>{{$data->fldunit}}</td>
                                <td><a href="javascript:;" onclick="deleteMonitor('{{$data->fldid}}')" class="text-danger"><i class="ri-delete-bin-5-fill"></i></a></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</form>
<script type="text/javascript">
    function addMonitor() {
        $.ajax({
            url: '{{ route('patient.request.menu.addmonitor') }}',
            type: "POST",
            data: {encounterId: $('#encounter_id').val(), category: $('#type').val(), item: $('#particulars').val(), unit: $('#unit').val(), freq: $('#freq').val()},
            success: function (response) {
                // console.log(response);
                $('#monitor_data').html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function deleteMonitor(id) {
        // alert('Delete Diagnosis ?');
        var result = confirm("Delete Monitor?");
        if (result) {
            $.ajax({
                url: '{{ route('patient.request.menu.deletemonitor') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val(), monitorID: id},
                success: function (response) {
                    // console.log(response);
                    $('#monitor_data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            return false;
        }

    }

    $(document).on('change', '#type', function () {
        var type = $(this).val();
        if (type.length > 0) {

            $.get("{{ route('getMonitoringParticulars')}}", {term: type}).done(function (data) {
                // Display the returned data in browser
                console.log(data);
                $("#particulars").select2();
                $("#particulars").html(data);
            });
        } else {
            $("#particulars").html('');
        }

    });
</script>
