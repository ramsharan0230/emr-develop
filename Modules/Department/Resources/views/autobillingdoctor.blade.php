<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Doctor Auto Billing
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Group Name</label>
                        <div class="col-lg-8 col-md-7">
                            <select name="" id="doctor_department" class="form-control js-registration-department">
                                <option value="">---select---</option>
                                @if(isset($department) and count($department) > 0)
                                @foreach($department as $d)
                                <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="listAllAutobillingdoctor()">View list</a>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Doctor Name</label>
                        <div class="col-lg-8 col-md-9">
                                <input type="hidden" name="consultantid[]"
                                       class="js-registration-consultantid">
                                <select name="consultant[]" id="consultant_id"
                                        class="form-control js-registration-consultant select2"
                                        required>
                                    <option value="">--Select--</option>
                                </select>

                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Billing Mode</label>
                        <div class="col-lg-8 col-md-9">
                            <select name="" id="billing_mode_doctor" class="form-control">
                                <option value="">---select---</option>
                                @if(isset($billingset) and count($billingset) > 0)
                                @foreach($billingset as $b)
                                <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Registration Type</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="radio" name="registration_type" class="registration_type" value="New Registration" checked="">&nbsp;&nbsp;New Registration
                            <input type="radio" name="registration_type" class="registration_type" value="Follow Up">&nbsp;&nbsp;Follow Up
                            <input type="radio" name="registration_type" class="registration_type" value="Other Registration">&nbsp;&nbsp;Other Registration
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Components
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-4" style="display: none;" id="testcatdoctor">
                            <select name="" id="test_type_doctor" class="form-control">
                                <option value="">---select---</option>
                                @foreach($test_type as $type)
                                <option value="{{$type->flditemtype}}">{{$type->flditemtype}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="" id="flditemnamedoctor" class="form-control">
                                <!-- <option value="0">---select---</option> -->
                            </select>
                        </div>
                        <label class="col-sm-1">QTY</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="" placeholder="0" id="quantity_doctor">
                        </div>
                    </div>

                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-4 form-row ">
                            <label class="col-lg-2 col-md-3">Timing</label>
                            <div class="col-lg-10 col-md-9">
                                <select name="" id="timing_doctor" class="form-control">
                                    <option value="">---select---</option>
                                    <option value="AllTime">AllTime</option>
                                    <option value="Before">Before</option>
                                    <option value="After">After</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 form-row ">
                            <label class="col-lg-2 col-md-3">Cutoff</label>
                            <div class="col-lg-10 col-md-9">
                               <input type="time" name="" id="cutoff_doctor" class="form-control">
                           </div>
                       </div>
                       <input type="hidden" name="updateid" id="updateiddoctor">
                       <div class="col-sm-4 form-row form-group ">
                        <button class="btn btn-primary" onclick="saveAutobillingdoctor()">Save</button>&nbsp;
                        <button class="btn btn-primary" onclick="updateAutobillingdoctor()">Update</button>&nbsp;
                        {{-- <input type="checkbox" name=""> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="res-table" id="ajaxresultdoctor">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
                            <th>Doctor Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody >

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#test_type_doctor').on('change', function(){
            var testtype = $(this).val();

            $.ajax({
                url: '{{ route('getItemname') }}',
                type: "POST",
                data: {testtype: testtype,mode:$('#billing_mode_doctor').val()},
                success: function (data) {
                    $('#flditemnamedoctor').html(data);
                    $('#flditemnamedoctor').select2();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        })

        setTimeout(function () {
            $("#doctor_department").select2();

        }, 1500);
    });

    function saveAutobillingdoctor(){
     var testtype = $('#test_type_doctor').val();
     var itemname = $('#flditemnamedoctor').val();
     var qty = $('#quantity_doctor').val();
     var timing = $('#timing_doctor').val();
     var cutoff = $('#cutoff_doctor').val();
     var groupname = $('#doctor_department').val();
     var mode = $('#billing_mode_doctor').val();
     var consultant_id = $('#consultant_id').val();
       // var reg_type = $("input[name='registration_type']:checked").val();
       if($('.registration_type').is(':checked')){
        var reg_type = $("input[name='registration_type']:checked").val();
    }else{
       var reg_type = '';
   }
    if(testtype =='' || itemname=='' || groupname=='' || mode=='' || qty=='' || timing=='') {
        showAlert('Please check and enter all data','error');
        return false;
    }
       // alert(reg_type);
       $.ajax({
        url: '{{ route('saveAutobillingDoctor') }}',
        type: "POST",
        data: {testtype: testtype, itemname: itemname, qty: qty, timing: timing, cutoff:cutoff, groupname: groupname, mode: mode,reg_type: reg_type,consultant_id:consultant_id },
        success: function (data) {
            $('#ajaxresultdoctor').html(data);
            $('#autobilling-doctor-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
   }

   function updateAutobillingdoctor(){
        // $('.autobilling').bind('click',function() {
        //     $('.autobilling').not(this).prop("checked", false);
        // });

        // if ($(".autobilling").is(':checked')) {
            var fldid = $('#updateiddoctor').val();
            // var fldid = $('.autobilling:checked').val();
            var testtype = $('#test_type_doctor').val();
            var itemname = $('#flditemnamedoctor').val();
            var qty = $('#quantity_doctor').val();
            var timing = $('#timing_doctor').val();
            var cutoff = $('#cutoff_doctor').val();
            if($('.registration_type').is(':checked')){
                var reg_type = $("input[name='registration_type']:checked").val();
            }else{
               var reg_type = '';
            }
           var mode = $('#billing_mode_doctor').val();
           $.ajax({
                url: '{{ route('updateAutobillingDoctor') }}',
                type: "POST",
                data: {department: $('#doctor_department').val(), fldid: fldid, testtype: testtype, itemname: itemname, qty: qty, timing: timing, cutoff: cutoff, mode: mode, reg_type: reg_type},
                success: function (data) {
                    $('#ajaxresultdoctor').html(data);
                    $('#autobilling-doctor-table').bootstrapTable()
                    showAlert("Successfully updated!");
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        // }else{
        //     alert('Please select item to update');
        // }
    }

function listAllAutobillingdoctor(){
    if ($('#doctor_department').val() == "") {
        alert('Please select group.');
        return false;
    }

    $.ajax({
        url: '{{ route('listAllAutobillingDoctor') }}',
        type: "POST",
        data: {department: $('#doctor_department').val()},
        success: function (data) {
            $('#ajaxresultdoctor').html(data);
            $('#autobilling-doctor-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function deleteautobillingitemdoctor(id){
    if(!confirm("Delete?")){
        return false;
    }
    // var groupname = $('#department').val();
    // alert(id);
    $.ajax({
        url: '{{ route('deleteAutobillingDoctor') }}',
        type: "POST",
        data: {department: $('#doctor_department').val(),fldid: id},
        success: function (data) {
            $('#ajaxresultdoctor').html(data);
            $('#autobilling-doctor-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function editautobillingitemdoctor(id){
    $.ajax({
        url: '{{ route('editAutobillingDoctor') }}',
        type: "POST",
        data: {fldid: id},
        success: function (data) {
            if(data.status){
                $('#billing_mode_doctor').val(data.autogroup.fldbillingmode);
                $('#doctor_department').val(data.autogroup.fldgroupname);

                $('input[name="registration_type"][value="'+data.autogroup.fldregtype+'"]').prop('checked', true);
                $('#testcatdoctor').show();
                $('#test_type_doctor').val(data.autogroup.flditemtype);
                $('#flditemnamedoctor').empty().html(data.itemlists);
                $('#flditemnamedoctor').val(data.autogroup.flditemname);
                $('#quantity_doctor').val(data.autogroup.flditemqty);
                $('#timing_doctor').val(data.autogroup.fldexitemtype);
                $('#cutoff_doctor').val(data.autogroup.fldcutoff);
                $('#updateiddoctor').val(data.autogroup.fldid);
                console.log(data.autogroup.doctor_id);
                $("#consultant_id").val(data.autogroup.doctor_id).change();;
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

$('#billing_mode_doctor').on('change', function(){
    var mode = $(this).val();
    if(mode !=''){
        $('#testcatdoctor').show();
    }else{
        $('#testcatdoctor').hide();
    }
})

    $(document).on('change', '.js-registration-department', function () {
        var department = $(this).val() || '';
        var currentDepartmentTd = $(this).closest('.js-registration-consultantid');
        $(currentDepartmentTd).next('.js-registration-consultantid').find('.js-registration-consultant').empty().append(selectOption.clone());
        if (department !== '') {
            $.ajax({
                method: "GET",
                data: {department: department},
                dataType: "json",
                url: baseUrl + '/registrationform/getDepatrmentUser',
            }).done(function (data) {
                var elems = '';
                elems += "<option value=''>Select</option>";
                $.each(data, function (i, d) {
                    var fldfullname = d.fldfullname.trim();
                    elems += '<option value="' + d.id + '" data-consultantid="' + d.id + '">' + fldfullname + '(NMC: ' + d.nmc + ')</option>';
                });
                $(currentDepartmentTd).next('.js-registration-consultantid').find('.js-registration-consultant').append(elems);
                // alert($(this).closest('.js-registration-consultant'));
                $(document).find('.js-registration-consultant').empty().append(elems);
            });
        }
    });

    $(function() {
        $('#autobilling-doctor-table').bootstrapTable()
    })

</script>
