<div class="modal fade" id="final-diagnosis-freetext-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">Final diagnosis</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-diagnosis-freetext-final">
                <input type="hidden" name="encounter" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" id="in_final_diag_enc">
                <input type="hidden" name="fldinput" value="Final Diagnosis" id="in_final_diag_fldinput">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="form-label col-md-9"> Enter Custom Final Diagnosis</label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-input width_input col-md-12"
                                       name="final_custom_diagnosis" id="final_custom_diagnosis">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close
                    </button>
                    <input type="submit" name="submit" id="submitfinalfreetextdiagnosis" class="btn btn-primary"
                           value="Save">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function() {
        $('#submitfinalfreetextdiagnosis').on('click', function(){
            // alert('free diagnosis test');
            var postval = $('#final_custom_diagnosis').val();
            var encounter = $('#in_final_diag_enc').val();
            var fldinput = $('#in_final_diag_fldinput').val();
            if(postval !=''){
                var url = "{{ route('physiotherapy.diagnosis.customfreetext.save') }}";
                // alert(url)
                $.ajax({
                    url: url,
                    type: "POST",
                    data: { custom_diagnosis: postval, encounter: encounter, fldinput: fldinput },
                    success: function(data) {
                        // response.log()
                        // console.log(data)
                        if ($.isEmptyObject(data.error)) {
                            $('#final_delete').append(data.html);
                            $('#final-diagnosis-freetext-modal').modal('hide');
                            showAlert('Data Added !!');
                        } else {
                            showAlert('Something went wrong!!');
                        }
                    }
                });
            }else{
                showAlert('Data Not Found');
            }

        });
    });

</script>