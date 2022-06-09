{{-- Doctor Share Modal --}}
<div class="modal fade" id="department-doctor-modal" tabindex="-1" role="dialog" aria-labelledby="department-doctor" aria-hidden="false">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <form id="department-doctor-form" action="" method="POST">
                @csrf
                <input id="share-type" name="type" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Select Department Doctor -<span id="docdep-modal-title"></span></h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="false">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row">
                        <div class="col-md-6">
                            <label for="department">Department</label>
                            <select id="billing-department" name="billing_department">
                            
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="doctor">Doctor Name</label>
                            <select id="billing-doctors" name="billing_doctors">
                            
                            </select> 
                        </div>
                    </div>
                    <input type="hidden" name="pat_billing_id" id="pat_billing_id" value="">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary  onclose" data-dismiss="modal">Close</button>
                    <button type="button" name="submit" id="js-department-dcotor-submit-btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            $("#billing-department").select2();
            $("#billing-doctors").select2();
        }, 1000);
        
        $('#js-department-dcotor-submit-btn').on('click',function(){
            var billDepartment = $('#billing-department :selected').val();
            var billDoctor = $('#billing-doctors :selected').val();
            if(billDepartment == '' || billDepartment == undefined){
                showAlert('Choose Department.');
                return false;
            }

            if(billDoctor == '' || billDoctor == undefined){
                showAlert('Choose Doctor.');
                return false;
            }
            // var data = $('#department-doctor-form').serialize();
            $.ajax({
                url: "{{route('billing.userpay.save')}}",
                type: "post",
                data: $('#department-doctor-form').serialize(),
                success: function (response) {
                    console.log(response);
                    if(response.status == true){
                        showAlert('Information saved');
                        $('#payable-doctors').text(response.doctors);
                        $('#department-doctor-modal').modal('hide');
                    }else{
                        showAlert('Something went wrong')
                    }
                },
                error: function (xhr, status, error) {
                    var option = $('<option></option>').attr("value", "").text("--Package--");
                    $("#package").empty().append(option);
                }
            });
        });

        $('#billing-department').on('change',function(){
            var billDepartment = $('#billing-department :selected').val();
            
            if(billDepartment == '' || billDepartment == undefined){
                showAlert('Choose Department.');
                return false;
            }

            if (billDepartment !== '') {
                $.ajax({
                    method: "GET",
                    data: {department: billDepartment},
                    dataType: "json",
                    url: baseUrl + '/registrationform/getDepatrmentUser',
                }).done(function (data) {
                    var elems = '';
                    elems += "<option value=''>Select</option>";
                    $.each(data, function (i, d) {
                        var fldfullname = d.fldfullname.trim();
                        elems += '<option value="' + d.flduserid + '" data-consultantid="' + d.id + '">' + fldfullname + '(NMC: ' + d.nmc + ')</option>';
                    });
                    
                    $(document).find('#billing-doctors').empty().append(elems);
                });
            }
        });
    });

    $('.onclose').on('click',function(){
        if(confirm('Do you want to save without giving share ?')){
            $('#department-doctor-modal').modal('hide');
        }else{
            return false;
        }
    })
</script>
{{-- End of doctor share modal --}}
