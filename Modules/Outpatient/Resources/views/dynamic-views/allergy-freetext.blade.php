


    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}" id="all_enc">
    <input type="hidden" name="fldinput" value="Allergic Drugs" id="all_fldinput">
   
    <div class="modal-body">
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="form-label col-md-9"> Enter Custom Allergic Drugs</label>
                </div>
                <div class="form-group">
                   <input type="text" class="form-input width_input col-md-12" id="custom_allergy" name="custom_allergy" value="">
                </div>
            </div>
        </div>
        
        
       
        
    </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-secondary closediagnosisfreetext" data-dismiss="modal">Close</button>
       <button type="button" id="submitfreetextallergy" class="btn btn-primary">Save</button>
       <!-- <a href="javascript:void(0);" class="btn-primary" id="submitfreetextallergy">Save</a> -->
        
    </div>

<script type="text/javascript">
    $('#submitfreetextallergy').on('click', function(){
        // alert('free allergic test');
        var postval = $('#custom_allergy').val();
        var encounter = $('#all_enc').val();
        var fldinput = $('#all_fldinput').val();
        if(postval !=''){
            var url = "{{ route('patient.allergy.freetext.save.waiting') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: { custom_allergy: postval, encounter: encounter, fldinput: fldinput },
                success: function(response) {
                    // response.log()
                    // console.log(response);
                    $('#select-multiple-aldrug').empty().append(response);
                    $('#allergy-freetext-modal').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }else{
            showAlert('Data Not Found');
        }
        
    });
</script>


