<div class="modal fade" id="provisional-diagnosis-freetext-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">Provisional diagnosis</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-diagnosis-freetext">
                <input type="hidden" name="encounter" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" id="in_diag_enc">
                <input type="hidden" name="fldinput" value="Provisional Diagnosis" id="in_diag_fldinput">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="form-label col-md-9"> Enter Custom Provisional Diagnosis</label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-input width_input col-md-12" name="custom_diagnosis" id="custom_diagnosis">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" id="submitfreetextdiagnosis" class="btn btn-primary" value="Save">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#submitfreetextdiagnosis').on('click', function(){
            // alert('free allergic test');
            var postval = $('#custom_diagnosis').val();
            var encounter = $('#in_diag_enc').val();

            if (encounter == "") {
                alert('Please select encounter id.');
                return false;
            }

            var fldinput = $('#in_diag_fldinput').val();
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
                            $('#provisional_delete').append(data.html);
                            $('#provisional-diagnosis-freetext-modal').modal('hide');
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