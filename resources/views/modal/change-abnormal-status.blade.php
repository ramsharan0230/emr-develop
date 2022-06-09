<div class="modal" id="js-global-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Abnormal Status</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <input type="hidden" id="js-global-fldid-input">
                        <input type="hidden" id="js-global-tbodyId-input">
                        <input type="hidden" id="js-global-tdCount-input">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="js-global-status-select">
                            <option value="0">Normal</option>
                            <option value="1">Abnormal</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-5">
                        <button type="button" onclick="changeAbnormalStatus.save()" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>