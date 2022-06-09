<div class="modal fade" id="show-discharge-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">{{ $enpatient->fldencounterval?? '' }}</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label col-md-12">Current Condition of Patient</label>
                            </div>
                            <div class="form-group">
                                <select class="form-input width_input col-md-12 fldhead">
                                    <option disabled="disabled">--- Patient Condition ---</option>
                                    <option value="Recovered">Recovered</option>
                                    <option value="Improved">Improved</option>
                                    <option value="Unchanged">Unchanged</option>
                                    <option value="Worse">Worse</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-discharge-modal" url="{{ route('outcome.discharge.save') }}" data-dismiss="modal">Done</button>
            </div>

        </div>
    </div>
</div>
