<div class="modal fade" id="update_fldcurval" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                <h5 class="inpatient__modal_title" style="text-align: center;">Select Current Status</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-9">
                            <h3 class="modal_item"></h3>
                            <input type="hidden" value="" class="modal_id">
                            <select class="modal_curval">
                                <option disabled="disabled">Select</option>
                                <option value="Continue">Continue</option>
                                <option value="Discontinue">Discontinue</option>
                                <option value="Hold">Hold</option>
                                <option value="Changed">Changed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="f-btn f-btn-md f-btn-icon-g flex-basis save__fldcurval" url="{{ route('update.routine.status') }}"><i class="fa fa-check"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>