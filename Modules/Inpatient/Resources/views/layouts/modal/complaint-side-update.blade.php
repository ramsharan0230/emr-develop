<div class="modal fade" id="edit_complaint_side_inpatient" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
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
                    <div class="dropdown-inpatient">
                        <select name="fldreportquali_side" class="dropdown-select-inpatient fldreportquali" id="get_complaint_side">
                            <option disabled="disabled">Sides</option>
                            <option value="Left Side">Left Side</option>
                            <option value="Right Side">Right Side</option>
                            <option value="Both Side">Both Side</option>
                            <option value="Episodes">Episodes</option>
                            <option value="On/Off">On/Off</option>
                            <option value="Present">Present</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                <button id="insert_complaint_side_inpatient" url="{{ route('insert.complaint.side') }}" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>