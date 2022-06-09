<!-- <form action="{{ route('emergency.diagnosis.freetext.save.waiting') }}" id="diagnosis-request-submit" class="laboratory-form container" method="post"> -->
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}" id="emergency_diag_enc">
    <input type="hidden" name="fldinput" value="Provisional Diagnosis" id="emergency_diag_fldinput">  
    <div class="modal-body">      
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="form-label col-md-9"> Enter Provisional Diagnosis</label>
                </div>
                <div class="form-group">
                   <input type="text" class="form-input width_input col-md-12" id="custom_diagnosis" name="custom_diagnosis" value="">
                </div>
            </div>
        </div> 
    </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
       <button type="button" id="submitfreetextdiagnosis" class="btn btn-primary" >Save</button>
       <!-- <input type="submit" name="submit" id="submitfreetextdiagnosis" class="btn btn-primary" value="Save">      -->
    </div>
<!-- </form> -->
<script type="text/javascript">
    $('#submitfreetextdiagnosis').on('click', function(){
        // alert('free allergic test');
        var postval = $('#custom_diagnosis').val();
        var encounter = $('#emergency_diag_enc').val();
        var fldinput = $('#emergency_diag_fldinput').val();
        if(postval !=''){
            var url = "{{ route('emergency.diagnosis.freetext.save.waiting') }}";

            $.ajax({
                url: url,
                type: "POST",
                // dataType: "json",
                data: { custom_diagnosis: postval, encounter: encounter, fldinput: fldinput },
                success: function(data) {
                    // response.log()
                    // console.log(data)
                    $('#select-multiple-diagno').empty().append(data);
                    $('#diagnosis-freetext-modal-emergency').modal('hide');
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