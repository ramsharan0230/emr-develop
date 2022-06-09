@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-7">
                <form id="department_one">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Department
                                </h4>
                            </div>
                        </div>
                        <input type="hidden" id="bed-search" value="{{route('bed-search')}}">
                        <input type="hidden" id="category-search" value="{{route('category-search')}}">

                        <div class="iq-card-body">
                            <div class="form-group form-row">
                                <label class="col-2">Category</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="category" id="category">
                                        <option value="0">Select Category</option>
                                        <option value="Consultation">Consultation</option>
                                        <option value="Patient Ward">Patient Ward</option>
                                        <option value="Emergency">Emergency</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="javascript:;" class="btn btn-primary search-category" type="button">Search</a>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Auto Billing</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="autobilling" id="autobilling">
                                        <option value="0"></option>
                                        @if($autobilling)
                                            @foreach($autobilling as $auto)
                                                <option value="{{$auto->flditemname}}">{{$auto->flditemname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Dept Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="department_name" id="department_name" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Dept Name in Nepali</label>
                                <div class="col-sm-10">
                                    <input type="text" name="department_name_nepali" id="department_name_nepali" class="form-control" required />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Dept code</label>
                                <div class="col-sm-10">
                                    <input type="text" name="department_code" id="department_code" class="form-control" required />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Dept description</label>
                                <div class="col-sm-10">
                                    <textarea  name="department_description" id="department_description" class="form-control" required >
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Service</label>
                                <div class="col-sm-10">
                                    <select class="form-control col-9 select2" name="service" id="service">
                                        <option value="">--Select--</option>
                                        @if($services)
                                            @foreach($services as $service)
                                                <option value="{{$service->value}}">{{$service->label}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Block <span style="color: red">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="fldblock" id="fldblock" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Floor</label>
                                <div class="col-sm-10">
                                    <select class="form-control col-9" name="flddeptfloor" id="flddeptfloor">
                                        <option value="">--Select--</option>
                                        @if($bedfloor)
                                            @foreach($bedfloor as $floor)
                                                <option value="{{$floor->name}}">{{$floor->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Room</label>
                                <div class="col-sm-10">
                                    <input type="text" name="room" id="room" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Incharge</label>
                                <div class="col-sm-10">
                                    <select name="incharge" id="incharge" class="form-control select2">
                                        <option value="">Select Incharge</option>
                                        @if($inchargeUser)
                                            @foreach($inchargeUser as $in)
                                                <option value="{{$in->username}}">{{$in->firstname}} {{$in->middlename}} {{$in->lastname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-2">Status</label>
                                <div class="col-sm-10">
                                    <select name="department_status" id="department_status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group text-right mt-2">
                                <a href="javascript:;" class="btn btn-primary" id="add-department" url="{{route('add-departement')}}"><i class="fas fa-plus"></i> Add</a>
                                <a href="javascript:;" class="btn btn-success" id="update-department" update-id="" url="{{route('update-department')}}"><i class="fa fa-pencil"></i> Update</a>
                            <!--                            <a href="javascript:;" class="btn btn-primary" id="delete-department" url="{{route('delete-departement')}}" delete-id=""> Delete</a>-->
                                <a href="{{ route('exportdepartment') }}" target="_blank" class="btn btn-warning"><i class="fa fa-code"></i>Export</a>
                            </div>

                            <div class="res-table">
                                <table class="table table-bordered table-striped mt-3 ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Department</th>
                                        <th>Category</th>
                                        <th>Block</th>
                                        <th>Floor</th>
                                        <th>Room No.</th>
                                        <th>Auto Billing</th>
                                        <th>Payable</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="department-list">
                                    @if($departments)
                                        @foreach($departments as $dept)
                                            <tr>
                                                <td> <a href="javascript:;" class="deptname" dept="{{$dept->flddept}}" > {{$dept->flddept}}</a></td>
                                                <td>{{$dept->fldcateg}}</td>
                                                <td>{{$dept->fldblock}}</td>
                                                <td>{{$dept->flddeptfloor}}</td>
                                                <td>{{$dept->fldroom}}</td>
                                                @if($dept->fldhead != "0") <td>{{$dept->fldhead}}</td> @else <td></td> @endif
                                                <td>{{$dept->fldactive}}</td>
                                                @if($dept->fldstatus == 1)
                                                    <td>Active</td>
                                                @else
                                                    <td>Inactive</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-5">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Bed
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="bed-one">
                            <div class="form-group form-row">
                                <label class="col-3">Department</label>
                                <input type="text" id="deptname" name="deptname" class="form-control col-9" value="" readonly/>
                            </div>
                            <div class="form-group form-row">
                                <label  class="col-3">Bed Name</label>
                                <input type="text" id="bedname" name="bedname" class="form-control col-9" value="" />
                            </div>
                            <div class="form-group form-row">
                                <label  class="col-3">Bed Type</label>
                                <select class="form-control col-9" name="bedtype" id="bedtype">
                                    <option value="">--Select Bed--</option>
                                    @if($bedtype)
                                        @foreach($bedtype as $type)
                                            <option value="{{$type->name}}">{{$type->name}}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        <!-- <div class="form-group form-row">
                            <label  class="col-3">Bed Group</label>
                            <select class="form-control col-9 " name="bedgroup" id="bedgroup">
                                    @if($bedgroup)
                            @foreach($bedgroup as $group)
                                <option value="{{$group->name}}">{{$group->name}}</option>
                                    @endforeach
                        @endif

                                </select>
                            </div> -->
                            <div class="form-group form-row">
                                <label  class="col-3">Bed Floor</label>
                                <select class="form-control col-9" name="floor" id="floor">
                                    <option value="">--Select Bed Floor--</option>
                                    @if($bedfloor)
                                        @foreach($bedfloor as $floor)
                                            <option value="{{$floor->name}}">{{$floor->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-3">Auto Billing</label>
                                <select class="form-control col-9" name="bed_autobilling" id="bed_autobilling">
                                    <option value="0"></option>
                                    @if($autobilling)
                                        @foreach($autobilling as $auto)
                                            <option value="{{$auto->flditemname}}">{{$auto->flditemname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group form-row">
                                <label  class="col-3">Oxygen</label>
                                <div class="er-input">
                                    <div class="col-sm-6">
                                        <input type="radio" class="is_oxygen" name="is_oxygen" value="0" checked>
                                        <label >No</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="radio" class="is_oxygen" name="is_oxygen" value="1">
                                        <label >Yes</label>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </form>
                        <div class="form-group mt-3 text-right">
                            <a href="javascript:;" class="btn btn-primary" id="addbed" url="{{route('addbed')}}"><i class="fas fa-plus"></i> Add</a>&nbsp;
                            <a href="javascript:;" class="btn btn-success" id="update-bed" update-id="" url="{{route('updatebed')}}"><i class="fa fa-pencil"></i> Update</a>
                        </div>
                        <div class="form-group mt-3">
                            <div class="res-table">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead class="thead-light">
                                    <tr>

                                        <th>Bed Name</th>
                                        <th>Bed Type</th>
                                        <th>Bed Group</th>
                                        <th>Floor</th>
                                        <th>Oxygen</th>
                                        <th>Autobilling</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody id="department-bed">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')

    <script>
        $(document).ready(function() {
            

            $('.search-category').on('click', function() {
                var url = $('#category-search').val();
                category = $("#category option:selected").val();

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        category: category
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#department-list').html(data.success.html);
                            showAlert("Information saved!!");
                            //location.reload();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });

            $('#department-list').on('click','.deptname', function() {
                var url = $('#bed-search').val();
                dept = $(this).attr('dept');
                $('#deptname').val(dept);
                $('#bedname').val('');
                $('#bedtype option').attr('selected', false);
                $('#floor option').attr('selected', false);
                $('#bed_autobilling option').attr('selected', false);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        dept: dept
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            $("#update-department").attr('update-id', data.success.departmentData.fldid);
                            $("#delete-department").attr('delete-id', data.success.departmentData.fldid);
                            $("#category").val(data.success.departmentData.fldcateg);
                            $("#autobilling").val(data.success.departmentData.fldhead).change();
                            $("#flddeptfloor").val(data.success.departmentData.flddeptfloor);
                            $("#fldblock").val(data.success.departmentData.fldblock);
                            $("#fldfloor").val(data.success.departmentData.flddeptfloor);
                            $("#department_name").val(data.success.departmentData.flddept);
                            $("#department_description").val(data.success.departmentData.department_description);
                            $("#department_code").val(data.success.departmentData.department_code);
                            $("#department_name_nepali").val(data.success.departmentData.department_name_nepali);
                            $("#service").val(data.success.departmentData.service).change();
                            $("#room").val(data.success.departmentData.fldroom);
                            $("#incharge").val(data.success.departmentData.fldactive).change();
                            $("#department_status").val(data.success.departmentData.fldstatus).change();
                            $('#department-bed').html(data.success.html);
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });

            $('#addbed').on('click', function() {
                var url = $(this).attr('url');
                var bedname = $("#bedname").val();
                var deptname = $("#deptname").val();
                var bedtype = $("#bedtype option:selected").val();
                var bedgroup = $("#bedgroup option:selected").val();
                var floor = $("#floor option:selected").val();
                var bed_autobilling = $("#bed_autobilling option:selected").val();
                var is_oxygen = $(".is_oxygen:checked").val();
                var formData = {
                    fldbed: bedname,
                    flddept: deptname,
                    bedtype: bedtype,
                    bedgroup: bedgroup,
                    floor: floor,
                    bed_autobilling: bed_autobilling,
                    is_oxygen:is_oxygen
                };
                if(deptname != ""){
                    if(bedname != "" && bedtype != "" && floor != "" && is_oxygen != ""){
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: formData,
                            success: function(data) {
                                if(data.error){
                                    showAlert(data.message,'error');
                                    return;
                                }
                                if ($.isEmptyObject(data.error)) {
                                    $('#department-bed').html(data.success.html);
                                    $('#bedname').val('');
                                    showAlert("Information saved!!");
                                    $('#bed-one')[0].reset();

                                } else {
                                    alert("Something went wrong!!");
                                }
                            }
                        });
                    }else{
                        alert("Please fill up all the data!");
                    }
                }else{
                    alert("Please select department first!");
                }
            });


            $('#add-department').on('click', function() {
                if($('#fldblock').val() ===''){
                    showAlert('Block is required','error')
                    return false;
                }
                var url = $(this).attr('url');
                var category = $("#category option:selected").val();
                var autobilling = $("#autobilling option:selected").val();
                var department_name = $("#department_name").val();
                var department_code = $("#department_code").val();
                var department_description = $("#department_description").val();
                var department_name_nepali = $("#department_name_nepali").val();



                var room = $("#room").val();
                var service = $("#service").val();
                var incharge = $("#incharge option:selected").val();
                var fldblock= $("#fldblock").val();
                var flddeptfloor= $("#flddeptfloor option:selected").val();
                var department_status = $("#department_status option:selected").val();

                var formData = {
                    category: category,
                    autobilling: autobilling,
                    service:service,
                    department_name: department_name,
                    department_code: department_code,
                    department_description: department_description,
                    department_name_nepali: department_name_nepali,
                    room: room,
                    fldblock: fldblock,
                    flddeptfloor: flddeptfloor,
                    incharge: incharge,
                    department_status: department_status
                };


                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        if(data.error){
                            showAlert(data.message,'error');
                            return;
                        }
                        if ($.isEmptyObject(data.error)) {
                            $('#department-list').html(data.success.html);
                            console.log(data.success.html);
                            showAlert("Information saved!!");
                            $('#department_one')[0].reset();

                            $('#incharge').val(null).trigger('change');
                            //location.reload();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });


            $('#update-department').on('click', function() {
                var url = $(this).attr('url');
                var service = $("#service").val();
                var fldid = $(this).attr('update-id');
                var category = $("#category option:selected").val();
                var autobilling = $("#autobilling option:selected").val();
                var department_name = $("#department_name").val();
                var department_code = $("#department_code").val();
                var department_description = $("#department_description").val();
                var department_name_nepali = $("#department_name_nepali").val();
                var room = $("#room").val();
                var incharge = $("#incharge option:selected").val();
                var fldstatus = $('#department_status option:selected').val();
                var fldblock= $("#fldblock").val();
                var flddeptfloor= $("#flddeptfloor option:selected").val();

                var formData = {
                    fldid: fldid,
                    category: category,
                    service:service,
                    autobilling: autobilling,
                    department_name: department_name,
                    department_code: department_code,
                    department_description: department_description,
                    department_name_nepali: department_name_nepali,
                    room: room,
                    incharge: incharge,
                    fldstatus: fldstatus,
                    fldblock: fldblock,
                    flddeptfloor: flddeptfloor,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#department-list').html(data.success.html);
                            console.log(data.success.html);
                            showAlert("Information saved!!");
                            $('#department_one')[0].reset();
                            $('#incharge').val(null).trigger('change');
                            //location.reload();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });


            $('#delete-department').on('click', function() {
                var url = $(this).attr('url');
                var fldid = $(this).attr('delete-id');
                var formData = {
                    fldid: fldid,
                };
                var result = confirm("Are you sure you want to delete?");
                if (result) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: formData,
                        success: function(data) {
                            if ($.isEmptyObject(data.error)) {
                                $('#department-list').html(data.success.html);
                                showAlert("Information deleted!!");
                                //location.reload();
                            } else {
                                alert("Something went wrong!!");
                            }
                        }
                    });
                }


            });

            $('#department-bed').on('click','.delete-bed', function() {
                var url = $(this).attr('url');
                var fldbed = $(this).attr('fldbed');
                var bedstatus = $(this).attr('bedstatus');
                var flddept = $('#deptname').val();
                var formData = {
                    fldbed: fldbed,
                    flddept: flddept,
                };
                let status = "InActive";
                if(bedstatus == "InActive"){
                    status = 'Active';
                }
                var result = confirm("Are you sure you want to " +status+"?");
                if (result) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: formData,
                        success: function(data) {
                            if ($.isEmptyObject(data.error)) {
                                $('#department-bed').html(data.success.html);
                                $('#bedname').val('');
                                showAlert("Status Changed");
                                //location.reload();
                            } else {
                                alert("Something went wrong!!");
                            }
                        }
                    });
                }
            });

            $('#department-bed').on('click','.edit-bed', function() {
                var url = $(this).attr('url');
                var fldbed = $(this).attr('fldbed');
                var formData = {
                    fldbed: fldbed,
                };
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#bedname').val(data.success.bedData.fldbed);
                            $('#deptname').val(data.success.bedData.flddept);
                            $("#update-bed").attr('update-id', data.success.bedData.fldbed);
                            $("#bedtype").val(data.success.bedData.fldbedtype);
                            $("#floor").val(data.success.bedData.fldfloor);
                            $('input[name=is_oxygen][value='+data.success.bedData.is_oxygen+']').attr('checked', true);
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });

            $('#update-bed').on('click', function() {
                var url = $(this).attr('url');
                var fldbed = $(this).attr('update-id');
                var deptname = $("#deptname").val();
                var bedname = $("#bedname").val();
                var bedtype = $("#bedtype option:selected").val();
                var floor = $("#floor option:selected").val();
                var is_oxygen = $('input[name=is_oxygen]:checked').val();
                var bed_autobilling = $("#bed_autobilling option:selected").val();

                var formData = {
                    fldbed: bedname,//   fldbed: bedname, yo huncha ki ke confussed
                    flddept: deptname,
                    is_oxygen: is_oxygen,
                    fldfloor: floor,
                    fldbedgroup: deptname,
                    fldbedtype: bedtype,
                    bed_autobilling: bed_autobilling
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        if(data.error){
                            showAlert(data.message,'error');
                            return;
                        }
                        if ($.isEmptyObject(data.error)) {
                            $('#department-bed').html(data.success.html);
                            showAlert("Information saved!!");
                            $('#bed-one')[0].reset();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });

            //Populate Bed Data

            $('#department-bed').on('click','.bedn', function() {
                var bedText = $(this).text();
                var dept = $(this).attr('dept');
                var floor = $(this).attr('floor');
                var type = $(this).attr('bedtype');
                var fldhead = $(this).attr('fldhead');
                $('#bedname').val(bedText).attr('readonly', true);
                $('#deptname').val(dept);
                $('#bedtype option').attr('selected', false);
                $('#bedtype option[value="' + type + '"]').attr('selected', true);
                $('#floor option').attr('selected', false);
                $('#floor option[value="' + floor + '"]').attr('selected', true);
                $('#bed_autobilling').val(0);
                $('#bed_autobilling').val(fldhead);
            });


        });
    </script>
@endpush
