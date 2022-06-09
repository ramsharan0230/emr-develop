<div class="modal fade" id="past-diagnosis-freetext-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">Past diagnosis</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-diagnosis-freetext">
                <input type="hidden" name="encounter" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" id="in_past_diag_enc">
                <input type="hidden" name="fldinput" value="Past Diagnosis" id="in_past_diag_fldinput">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="form-label col-md-9"> Enter Custom Past Diagnosis</label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-input width_input col-md-12" name="past_custom_diagnosis" id="past_custom_diagnosis">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" id="submitpastfreetextdiagnosis" class="btn btn-primary" value="Save">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#submitpastfreetextdiagnosis').on('click', function(){
            // alert('free past diagnosis test');
            var postval = $('#past_custom_diagnosis').val();
            var encounter = $('#in_past_diag_enc').val();

            if (encounter == "") {
                alert('Please select encounter id.');
                return false;
            }

            var fldinput = $('#in_past_diag_fldinput').val();
            if(postval !=''){
                var url = "{{ route('physiotherapy.diagnosis.customfreetext.save') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    // dataType: "json",
                    data: { custom_diagnosis: postval, encounter: encounter, fldinput: fldinput },
                    success: function(data) {
                        // response.log()
                        // console.log(data)
                        if ($.isEmptyObject(data.error)) {
                            $('#past_delete').append(data);
                            $('#past-diagnosis-freetext-modal').modal('hide');
                            showAlert('Data Added !!');
                        } else
                            showAlert('Something went wrong!!');
                    }
                });
            }else{
                showAlert('Data Not Found');
            }

        });
    });

</script>