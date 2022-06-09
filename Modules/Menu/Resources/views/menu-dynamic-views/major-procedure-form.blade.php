<form>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="searchName" class="form-control" id="name" placeholder="Name" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{  $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{  $patient->fldptnamelast }}@endif" readonly>
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="address" class="col-sm-2">Address</label>
                <div class="col-sm-10">
                    <input type="text" name="searchName" class="form-control" id="Address" placeholder="Address" value="@if(isset($patient)){{ $patient->fldptaddvill }} , {{ $patient->fldptadddist }}@endif" readonly>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="gender" class="col-sm-3">Gender</label>
                <div class="col-sm-9">
                    <input type="text" name="searchSurName" class=" form-control" id="SurName" placeholder="Gender" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" readonly>
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="bedno" class="col-sm-3">Bed No</label>
                <div class="col-sm-9">
                    <input type="text" name="searchDistrict" class="form-control" id="District" placeholder="Bed No" value="@if(isset($enpatient)){{ Helpers::getBedNumber($enpatient->fldencounterval) }}@endif" readonly>
                </div>
            </div>

        </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <nav>
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="requested-tab" data-toggle="tab" href="#requested" role="tab" aria-controls="requested" aria-selected="true">Requested</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
                        </li>
                    </ul>
                </nav>

                <div class="tab-content" id="nav-tabContent" style="margin-top: 20px;">
                    <div class="tab-pane fade show active" id="requested" role="tabpanel" aria-labelledby="requested-tab">
                        <div class="row"style="margin-top: 0;">
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                    <label for="name" class="col-sm-3">Billing</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="billing" id="billing" disabled="">
                                            <option value=""></option>
                                            @if(isset($billing) and count($billing) > 0)
                                                @foreach($billing as $b)
                                                <option value="{{$b->fldsetname}}" @if($enpatient->fldbillingmode == $b->fldsetname) selected="selected" @endif>{{$b->fldsetname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="name" class="col-sm-3">Date</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="text" name="billing_date" id="billing_date" class="form-control" value="{{date('Y-m-d H:i:s')}}">&nbsp;
                                            <div class="input-group-append">
                                                <a href="javascript:;"  id="billing_date_bs" class="text-primary" style="font-size: 18px;"><i class="ri-calendar-2-fill"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center">
                                    <label for="name" class="col-sm-3">Proced</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="proced" id="proced">
                                            <option value=""></option>
                                            @if(isset($procedures) and count($procedures) > 0)
                                                @foreach($procedures as $p)
                                                    <option value="{{$p->flditemname}}">{{$p->flditemname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="name" class="col-sm-3">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="status" id="prstatus">
                                            <option value=""></option>
                                            <option value="Planned">Planned</option>
                                            <option value="Referred">Referred</option>
                                            <option value="On Hold">On Hold</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                        <!-- <div class="modal" id="myModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">


                                                <div class="modal-header">
                                                    <h4 class="modal-title">Nepali Date</h4>
                                                    <button type="button" class="close removecurrent" data-target="#file-modal" billing_date_bs>&times;</button>
                                                </div>


                                                <div class="modal-body">
                                                    <input type="text" name="billing_date_bs" id="billing_date_bs" class="form-control">

                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary removecurrent" data-target="#file-modal">OK</button>
                                                    <button type="button" class="btn btn-danger removecurrent" data-target="#file-modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div> -->
                            <div class="col-sm-12">
                                <div class="box__input mt-2">
                                    <a href="javascript:;" class="btn btn-primary" id="addprocedure"><i class="ri-add-fill"></i>&nbsp;Add</a>
                                    <a href="javascript:;" class="btn btn-primary" id="editprocedure"><i class="ri-edit-2-fill"></i>&nbsp;Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="res-table mt-2">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th></th>
                                                <th>S.No</th>
                                                <th>Target Date</th>
                                                <th>Procedure</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="procedure_data">
                                        @if(isset($patdata) and count($patdata) > 0)
                                            @foreach($patdata as $k=>$data)
                                                <tr>
                                                    <td><input type="checkbox" name="procedureId" class="procedureId" value="{{$data->fldid}}"></td>
                                                    <td>{{$k+1}}</td>
                                                    <td>{{$data->fldnewdate}}</td>
                                                    <td>{{$data->flditem}}</td>
                                                    <td>{{$data->fldreportquali}}</td>
                                                    <td><a href="javascript:;" onclick="deletePro('{{$data->fldid}}')" class="text-danger"><i class="ri-delete-bin-5-fill"></i></a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--=======

                                                                <a href="javascript:;" class="btn btn-primary btn-sm" id="addprocedure"><i class="fas fa-plus-square"></i>&nbsp;Add</a>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:;" class="btn btn-secondary btn-sm" id="editprocedure"><i class="fas fa-edit"></i>&nbsp;Edit</a>

                            >>>>>>> e1a2796e3eb4a09c0d26a9d22998be6bad8f86c5--}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                        <div class="res-table">
                            <table class="table table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Target Date</th>
                                        <th>Procedure</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($completed_patdata) and count($completed_patdata) > 0)
                                    @foreach($completed_patdata as $cdata)
                                        <tr>

                                            <td>{{$cdata->fldnewdate}}</td>
                                            <td>{{$cdata->flditem}}</td>
                                            <td>{{$cdata->fldreportquali}}</td>
                                            <td><a href="javascript:;" onclick="deletePro('{{$cdata->fldid}}')" class="text-danger"><i class="ri-delete-bin-5-fill"></i></a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</form>
<script type="text/javascript" src="{{asset('js/nepali.datepicker.v2.2.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/nepali.datepicker.v2.2.min.css')}}"/>
<script type="text/javascript">

    function deletePro(id) {
        // alert('Delete Diagnosis ?');
        var result = confirm("Delete Procedure?");
        if (result) {
            $.ajax({
                url: '{{ route('patient.request.menu.deleteprocedure') }}',
                type: "POST",
                data: {encounterId: $('#encounter_id').val(), proID: id},
                success: function (response) {
                    // console.log(response);
                    $('#procedure_data').html(response);
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

    $(".removecurrent").on("click", function () {

        $("#myModal").modal("hide");
        $("#myModal").on("hidden.bs.modal", function () {
            $("#file-modal").modal("show");
        });
    });

    $("#pickdate").on("click", function () {
        var engdate = $('#billing_date').val();
        var res = engdate.split(" ");
        $.ajax({
            type: 'post',
            url: '{{ route("patient.request.menu.englishtonepali") }}',
            data: {date: res[0],},
            success: function (response) {
                $('#billing_date_bs').val(response);
            }
        });

    });
    $('#billing_date').datetimepicker({

        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: "1600:2032",

    });

    $('#billing_date_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
        // disableDaysAfter: '1',
        onChange: function () {
            var datebs = $('#billing_date_bs').val();
            $.ajax({
                type: 'post',
                url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                data: {date: datebs,},
                success: function (response) {
                    $('#billing_date').val(response);
                }
            });
        }

    });
    $(document).on('change', '#billing', function () {
        var billing = $(this).val();
        if (billing.length > 0) {

            $.get("{{ route('getProcedureByBilling')}}", {term: billing}).done(function (data) {
                // Display the returned data in browser
                console.log(data);
                $("#proced").select2();
                $("#proced").html(data);
            });
        } else {
            $("#proced").html('');
        }

    });

    $(document).on('click', '#addprocedure', function () {

        $.ajax({
            url: '{{ route('patient.request.menu.addprocedure') }}',
            type: "POST",
            data: {encounterId: $('#encounter_id').val(), procedure: $('#proced').val(), date: $('#billing_date').val(), billing: $('#billing').val(), status: $('#prstatus').val()},
            success: function (response) {
                if(response.html === "available"){
                    alert('Selected procedure has already been added');
                }else{
                     $('#procedure_data').html(response.html);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });

    $(document).on('click', '#editprocedure', function () {
        if ($('.procedureId').is(":checked")) {
            var procedure = $('#proced').val();
            var status = $('#prstatus').val();
            if(procedure ==''){
                alert('Choose Procedure');
                return false;
            }else if(status == ''){
                alert('Choose Status for the Procedure');
                return false;
            }else{
                $.ajax({
                    url: '{{ route('patient.request.menu.editprocedure') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val(), 
                        procedure: $('#proced').val(), 
                        date: $('#billing_date').val(), 
                        billing: $('#billing').val(), 
                        status: $('#prstatus').val(), 
                        proID: $('.procedureId:checked').val()
                    },
                    success: function (response) {
                        $('#procedure_data').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }

        } else {
            alert('Please select procedure.');
            return false;

        }


    });

    $(document).on('click','.procedureId', function () {
        $('input[name="procedureId"]').bind('click',function() {
          $('input[name="procedureId"]').not(this).prop("checked", false);
        });
         var procedureID = $(this).val();
         // alert(proID);
        $.ajax({
            url: '{{ route('patient.request.menu.pupulate') }}',
            type: "POST",
            data: {encounterId: $('#encounter_id').val(),proID: procedureID},
            success: function (response) {
                // console.log(response);
                $('#prstatus').val(response.fldstatus);
                $('#proced').val(response.flditem);
                $('#billing_date').val(response.fldtime);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });


</script>
