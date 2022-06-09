<div class="modal fade" id="scale_box" tabindex="-1" role="dialog" aria-labelledby="scale_boxLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('scale_save') }}">
                @csrf


                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="text_boxLabel" style="text-align: center;">Edit Complaint</h5> -->
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-2 col-form-label">Examination</label>
                        <div class="col-sm-10">
                            <input type="text" name="flditem" class="form-control modal_flditem" value="" readonly/>
                            <input type="hidden" name="fldsysconst" class="modal_fldsysconst" value=""/>
                            <input type="hidden" name="fldtype" class="modal_fldtype" value=""/>
                            <input type="hidden" name="fldencounterval" class="modal_fldencounterval" value=""/>
                        </div>
                    </div>

                    <div id="ajax_response_scale_list">
                        <!-- <form onsubmit="">
                            <div class="form-group row">
                                <label class="col-sm-6 col-form-label">
                                    Are there previous conclusive reports on this reaction?
                                </label>
                                <div class="col-sm-4">
                                    <select class="form-control">
                                        <option value="1">Choose...</option>
                                        <option value="2">Yes</option>
                                        <option value="3">No</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control " placeholder="0">
                                </div>
                            </div>


                        </form> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" id="submitscale_box" class="btn btn-primary" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>
