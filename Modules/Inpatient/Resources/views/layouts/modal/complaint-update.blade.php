<div class="modal fade" id="edit_complaint_inpatient" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('insert.complaint.detail') }}">
                @csrf
                <input type="hidden" class="complaintfldid" name="fldid" value="">
                <div class="modal-header">
                    <h5 class="inpatient__modal_title" id="edit_complaintLabel">Edit Complaint</h5>
                    <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <textarea name="flddetail" id="editor_present"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" id="submitflag" class="btn btn-primary" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>