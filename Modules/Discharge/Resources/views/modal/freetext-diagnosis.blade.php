<!-- <form action="{{ route('inpatient.final.diagnosis.freetext.save.waiting') }}"  class="laboratory-form container" method="post">
    @csrf -->
@php
    $encounterData = $encounter[0];
    $encounterDataPatientInfo = $encounter[0]->patientInfo;
@endphp
<input type="hidden" name="encounter" value="{{ $encounterId }}" id="in_final_diag_enc">
<input type="hidden" name="fldinput" value="Final Diagnosis" id="in_final_diag_fldinput">

<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="" class="form-label col-md-9"> Enter Custom Final Diagnosis</label>
            </div>
            <div class="form-group">
                <input type="text" class="form-input width_input col-md-12" name="final_custom_diagnosis" id="final_custom_diagnosis">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
    <input type="submit" name="submit" id="submitfinalfreetextdiagnosis" class="btn btn-primary" value="Save">
</div>
<!-- </form> -->
<script type="text/javascript">
    $('#submitfinalfreetextdiagnosis').on('click', function(){
        var postval = $('#final_custom_diagnosis').val();
        var encounter = $('#in_final_diag_enc').val();
        var fldinput = $('#in_final_diag_fldinput').val();
        if(postval !=''){
            var url = "{{ route('discharge.final.diagnosis.freetext.save.waiting') }}";
            // alert(url)
            $.ajax({
                url: url,
                type: "POST",
                data: { custom_diagnosis: postval, encounter: encounter, fldinput: fldinput },
                success: function(data) {
                    
                    $('#diagnosistext').html(data);
                    $('#diagnosis-freetext-modal-final').modal('hide');
                    showAlert('Data Added !!');
                    
                }
            });
        }else{
            showAlert('Data Not Found');
        }

    });
</script>
