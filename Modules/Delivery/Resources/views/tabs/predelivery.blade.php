<div class="tab-pane fade" id="predelivery" role="tabpanel" aria-labelledby="predelivery-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-5">
                <div class="res-table">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="3">Examination</th>
                            </tr>
                        </thead>
                        <tbody id="js-deliveryexamination-examination-tbody"></tbody>
                    </table>
                    <button class="btn btn-primary js-delivery-report-generate-btn" type="button"><i class="fa fa-code"></i>&nbsp;Report</button>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="res-table mt-2">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Examination</th>
                                <th>&nbsp;</th>
                                <th>Observation</th>
                                <th>Report Time</th>
                            </tr>
                        </thead>
                        <tbody id="js-deliveryexamination-patient-examination-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-deliveryexamiination-status-modal">
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
                        <label class="form-label">Status</label>
                        <select class="form-input" id="js-deliveryexamiination-status-select full-width">
                            <option value="0">Normal</option>
                            <option value="1">Abnormal</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-deliveryexamiination-status-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
