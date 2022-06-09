<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                           Department Auto Billing
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Group Name</label>
                        <div class="col-lg-8 col-md-7">
                            <select name="" id="department" class="form-control">
                                <option value="">---select---</option>
                                @if(isset($department) and count($department) > 0)
                                @foreach($department as $d)
                                <option value="{{$d->flddept}}">{{$d->flddept}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="listAllAutobilling()">View list</a>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Billing Mode</label>
                        <div class="col-lg-8 col-md-9">
                            <select name="" id="billing_mode" class="form-control">
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
                            <input type="radio" name="regstration_type" class="regstration_type" value="New Registration" checked="">&nbsp;&nbsp;New Registration
                            <input type="radio" name="regstration_type" class="regstration_type" value="Follow Up">&nbsp;&nbsp;Follow Up
                            <input type="radio" name="regstration_type" class="regstration_type" value="Other Registration">&nbsp;&nbsp;Other Registration
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-lg-2 col-md-3">Enable Department</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="checkbox" name="enable_department" class="enable_department" id="enable_department" value="1" checked="">&nbsp
                            <button class="btn btn-primary" onclick="updateEnabledeptAutobilling()">Update</button>&nbsp;
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
                        <div class="col-sm-4" style="display: none;" id="testcat">
                            <select name="" id="test_type" class="form-control">
                                <option value="">---select---</option>
                                @foreach($test_type as $type)
                                <option value="{{$type->flditemtype}}">{{$type->flditemtype}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="" id="flditemname" class="form-control">
                                <!-- <option value="0">---select---</option> -->
                            </select>
                        </div>
                        <label class="col-sm-1">QTY</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="" placeholder="0" id="quantity">
                        </div>
                    </div>

                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-4 form-row ">
                            <label class="col-lg-2 col-md-3">Timing</label>
                            <div class="col-lg-10 col-md-9">
                                <select name="" id="timing" class="form-control">
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
                               <input type="time" name="" id="cutoff" class="form-control">
                           </div>
                       </div>
                       <input type="hidden" name="updateid" id="updateid">
                       <div class="col-sm-4 form-row form-group ">
                        <button class="btn btn-primary" onclick="saveAutobilling()">Save</button>&nbsp;
                        <button class="btn btn-primary" onclick="updateAutobilling()">Update</button>&nbsp;
                        {{-- <input type="checkbox" name=""> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="res-table" id="ajaxresult">
                    <table id="autobilling-table" >
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Billing Mode</th>
                            <th>Item Name</th>
                            <th>QTY</th>
                            <th>Timing</th>
                            <th>CuttOff</th>
                            <th>Registration Type</th>
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
        $('#billing_mode').on('change', function(){
            var billing_mode = this.value;
            $.ajax({
                url: '{{ route('checkEnableCheckbox') }}',
                type: "POST",
                data: {billing_mode: billing_mode,department:$('#department').val()},
                success: function (data) {
                    if(data == 1){
                        $('#enable_department').prop('checked',true);
                    }else{
                        $('#enable_department').prop('checked',false);
                    }

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });
            $('#test_type').on('change', function(){
            var testtype = $(this).val();

            $.ajax({
                url: '{{ route('getItemname') }}',
                type: "POST",
                data: {testtype: testtype,mode:$('#billing_mode').val()},
                success: function (data) {
                    $('#flditemname').html(data);
                    $('#flditemname').select2();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        })

        setTimeout(function () {
            $("#department").select2();

        }, 1500);
    });

    function saveAutobilling(){
     var testtype = $('#test_type').val();
     var itemname = $('#flditemname').val();
     var qty = $('#quantity').val();
     var timing = $('#timing').val();
     var cutoff = $('#cutoff').val();
     var groupname = $('#department').val();
     var mode = $('#billing_mode').val();
       // var reg_type = $("input[name='regstration_type']:checked").val();
       if($('.regstration_type').is(':checked')){
        var reg_type = $("input[name='regstration_type']:checked").val();
    }else{
       var reg_type = '';
   }

        if($('.enable_department').is(':checked')){
            var enable_dep = $("input[name='enable_department']:checked").val();
        }else{
            var enable_dep = 0;
        }
    if(testtype =='' || itemname=='' || groupname=='' || mode=='' || qty=='' || timing=='') {
        showAlert('Please check and enter all data','error');
        return false;
    }
       // alert(reg_type);
       $.ajax({
        url: '{{ route('saveAutobilling') }}',
        type: "POST",
        data: {testtype: testtype, itemname: itemname, qty: qty, timing: timing, cutoff:cutoff, groupname: groupname, mode: mode,reg_type: reg_type,enable_dep:enable_dep },
        success: function (data) {
            $('#ajaxresult').html(data);
            $('#autobilling-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
   }

   function updateAutobilling(){
        // $('.autobilling').bind('click',function() {
        //     $('.autobilling').not(this).prop("checked", false);
        // });

        // if ($(".autobilling").is(':checked')) {
            var fldid = $('#updateid').val();
            // var fldid = $('.autobilling:checked').val();
            var testtype = $('#test_type').val();
            var itemname = $('#flditemname').val();
            var qty = $('#quantity').val();
            var timing = $('#timing').val();
            var cutoff = $('#cutoff').val();
            if($('.regstration_type').is(':checked')){
                var reg_type = $("input[name='regstration_type']:checked").val();
            }else{
               var reg_type = '';
            }
       if($('.enable_department').is(':checked')){
           var enable_dep = $("input[name='enable_department']:checked").val();
       }else{
           var enable_dep = 0;
       }
           var mode = $('#billing_mode').val();
           $.ajax({
                url: '{{ route('updateAutobilling') }}',
                type: "POST",
                data: {department: $('#department').val(), fldid: fldid, testtype: testtype, itemname: itemname, qty: qty, timing: timing, cutoff: cutoff, mode: mode, reg_type: reg_type,enable_dep:enable_dep},
                success: function (data) {
                    $('#ajaxresult').html(data);
                    $('#autobilling-table').bootstrapTable()
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
    function updateEnabledeptAutobilling(){
            var testtype = $('#test_type').val();
            if($('.regstration_type').is(':checked')){
                var reg_type = $("input[name='regstration_type']:checked").val();
            }else{
               var reg_type = '';
            }
       if($('.enable_department').is(':checked')){
           var enable_dep = $("input[name='enable_department']:checked").val();
       }else{
           var enable_dep = 0;
       }
           var mode = $('#billing_mode').val();
           $.ajax({
                url: '{{ route('updateEnabledeptAutobilling') }}',
                type: "POST",
                data: {department: $('#department').val(), testtype: testtype, mode: mode, reg_type: reg_type,enable_dep:enable_dep},
                success: function (data) {
                    $('#ajaxresult').html(data);
                    $('#autobilling-table').bootstrapTable()
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

function listAllAutobilling(){
    if ($('#department').val() == "") {
        alert('Please select group.');
        return false;
    }

    $.ajax({
        url: '{{ route('listAllAutobilling') }}',
        type: "POST",
        data: {department: $('#department').val()},
        success: function (data) {
            $('#ajaxresult').html(data);
            $('#autobilling-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function deleteautobillingitem(id){
    if(!confirm("Delete?")){
        return false;
    }
    // var groupname = $('#department').val();
    // alert(id);
    $.ajax({
        url: '{{ route('deleteAutobilling') }}',
        type: "POST",
        data: {department: $('#department').val(),fldid: id},
        success: function (data) {
            $('#ajaxresult').html(data);
            $('#autobilling-table').bootstrapTable()
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function editautobillingitem(id){
    $.ajax({
        url: '{{ route('editAutobilling') }}',
        type: "POST",
        data: {fldid: id},
        success: function (data) {
            if(data.status){
                $('#billing_mode').val(data.autogroup.fldbillingmode);
                $('input[name="regstration_type"][value="'+data.autogroup.fldregtype+'"]').prop('checked', true);
                $('#testcat').show();
                $('#test_type').val(data.autogroup.flditemtype);
                $('#flditemname').empty().html(data.itemlists);
                // $('#flditemname').select2();
                $('#flditemname').val(data.autogroup.flditemname);
                $('#quantity').val(data.autogroup.flditemqty);
                $('#timing').val(data.autogroup.fldexitemtype);
                $('#cutoff').val(data.autogroup.fldcutoff);
                $('#updateid').val(data.autogroup.fldid);
                if(data.autogroup.fldenabledept == 1){
                    $("#enable_department").attr('checked', true);
                }else{
                    $("#enable_department").attr('checked', false);
                }
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

    $(function() {
        $('#autobilling-table').bootstrapTable()
    })
$('#billing_mode').on('change', function(){
    var mode = $(this).val();
    if(mode !=''){
        $('#testcat').show();
    }else{
        $('#testcat').hide();
    }
})

</script>
