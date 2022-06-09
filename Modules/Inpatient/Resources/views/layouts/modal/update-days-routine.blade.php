<div class="modal fade" id="update_flddays" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                <h5 class="inpatient__modal_title" style="text-align: center;">Select Numbers Of Days</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3 class="modal_items"></h3>
                            <input type="hidden" value="" class="modal_ids">
                            <input type="text" value="" class="modal_days">
                            <button type="button" class="f-btn f-btn-md f-btn-icon-g flex-basis save__flddays" url="{{ route('update.routine.days') }}"><i class="fa fa-check"></i>&nbsp;Save</button>
                        </div>
                        <div class="col-sm-5">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>