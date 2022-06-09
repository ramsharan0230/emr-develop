<div class="modal fade" id="edit_complaint_duration_inpatient" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" class="complaintfldid" name="fldid" value="">
            <div class="modal-header">
                <h5 class="inpatient__modal_title" id="edit_complaintLabel">Edit Complaint</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="number" class="inpatient__number duration" value="0" min="0" id="get_complaint_duration_inpatient_value">
                    <div class="dropdown-inpatient">
                        <select name="duration_type" class="dropdown-select-inpatient duration_type" id="get_complaint_duration_inpatient_type">
                            <option disabled="disabled">Duration</option>
                            <option value="Hours">Hours</option>
                            <option value="Days">Days</option>
                            <option value="Weeks">Weeks</option>
                            <option value="Months">Months</option>
                            <option value="Years">Years</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                <button id="insert_complaint_diration_inpatient" url="{{ route('insert.complaint.duration') }}" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>