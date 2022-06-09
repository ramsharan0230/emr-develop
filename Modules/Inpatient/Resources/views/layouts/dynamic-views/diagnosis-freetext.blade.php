<!-- <form action="{{ route('inpatient.diagnosis.freetext.save.waiting') }}"  class="laboratory-form container" method="post"> -->
    <!-- @csrf -->
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}" id="in_diag_enc">
    <input type="hidden" name="fldinput" value="Provisional Diagnosis" id="in_diag_fldinput">
   
    <div class="modal-body">
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="form-label col-md-9"> Enter Custom Provisional Diagnosis</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-input width_input col-md-12 text" name="custom_diagnosis" id="custom_diagnosis"/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
       <input type="submit" id="submitfreetextdiagnosis" class="btn btn-primary" value="Save">    
    </div>
<!-- </form> -->

<script type="text/javascript">
    $(document).on('click','#submitfreetextdiagnosis', function(){
        // alert('free allergic test');
        var postval = $('#custom_diagnosis').val();
        var encounter = $('#in_diag_enc').val();
        var fldinput = $('#in_diag_fldinput').val();
        if(postval !=''){
            var url = "{{ route('inpatient.diagnosis.freetext.save.waiting') }}";

            $.ajax({
                url: url,
                type: "POST",
                // dataType: "json",
                data: { custom_diagnosis: postval, encounter: encounter, fldinput: fldinput },
                success: function(data) {
                    // response.log()
                    // console.log(data)
                    $('#provisional_delete').empty().append(data);
                    $('#diagnosis-freetext-modal').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!');
                }
            });
        }else{
            showAlert('Data Not Found');
        }
        
    });
</script>