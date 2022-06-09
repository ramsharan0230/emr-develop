<div class="modal fade" id="text_box" tabindex="-1" role="dialog" aria-labelledby="text_boxLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('text_save') }}">
                @csrf


                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="text_boxLabel" style="text-align: center;">Edit Complaint</h5> -->
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                <div class="form-group ">
                        <label>Examination</label>
                        <input type="text" name="flditem" class="modal_flditem" value="" readonly/>
                        <input type="hidden" name="fldsysconst" class="modal_fldsysconst" value=""/>
                        <input type="hidden" name="fldtype" class="modal_fldtype" value=""/>
                        <input type="hidden" name="fldencounterval" class="modal_fldencounterval" value=""/>
                    </div>

                    <div class="form-group ">
                        <textarea name="content" id="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input type="hidden" name="tab" value="clinical-finding">
                    <input type="submit" name="submit" id="submitlnr_box" class="btn btn-primary" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>
