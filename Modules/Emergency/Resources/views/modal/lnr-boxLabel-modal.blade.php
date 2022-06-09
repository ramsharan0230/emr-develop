<div class="modal fade" id="lnr_box" tabindex="-1" role="dialog" aria-labelledby="lnr_boxLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
{{--            <form method="post" action="{{ route('lnrsave_emergency') }}">--}}
{{--                @csrf--}}


                <div class="modal-header">
                    <h4 class="modal-title">Examination</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group ">
                        <div class="col-sm-10">
                            <input type="text" name="flditem" class="form-control modal_flditem" value="" readonly/>
                            <input type="hidden" name="fldsysconst" class="modal_fldsysconst" value=""/>
                            <input type="hidden" name="fldtype" class="modal_fldtype" value=""/>
                            <input type="hidden" name="fldencounterval" class="modal_fldencounterval" value=""/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Left</label>
                                <textarea rows="8" class="form-control" name="left" id="lnr_left"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Right</label>
                                <textarea rows="8" class="form-control" name="right" id="lnr_right"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <input type="button" name="submit" id="submitlnr_box" class="btn btn-primary" value="Save changes">
                </div>
{{--            </form>--}}
        </div>
    </div>
</div>
