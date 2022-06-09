<div class="modal fade" id="number_box" tabindex="-1" role="dialog" aria-labelledby="number_boxLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('number_save') }}">
                @csrf


                <div class="modal-header">
                    <h4 class="modal-title">Examination</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                <div class="form-group ">
                        <label></label>
                        <input type="text" name="flditem" class="form-control modal_flditem" value="" readonly/>

                        <input type="hidden" name="fldsysconst" class="modal_fldsysconst" value=""/>
                        <input type="hidden" name="fldtype" class="modal_fldtype" value=""/>
                        <input type="hidden" name="fldencounterval" class="modal_fldencounterval" value=""/>


                    </div>

                    <div class="form-group ">
                        <input type="number" name="content" id="content" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input type="hidden" name="tab" value="clinical-finding">
                    <input type="submit" name="submit" id="submitnumber_box" class="btn btn-primary disable-on-first-click" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>
