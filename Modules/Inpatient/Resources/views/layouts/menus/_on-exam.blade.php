<div id="onexam" class="collapse " aria-labelledby="headingOne" data-parent="#accordion"  >
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-7">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-4">
                                <select id="js-examination-option" class="form-control select2"></select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-sm-in btn-primary {{ $disableClass }}" type="button" id="js-list-onexam" datatype="all"><i class="fa fa-list"></i></button>
                            </div>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="js-date-onexam">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control after-date-box" readonly>
                            </div>
                        </div>
                    </form>
                    <div class="res-table">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Symptoms</th>
                                    <th>Dura</th>
                                    <th>Side</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Time</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody class="js-examinations"></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-5">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-10">
                                <input type="number" placeholder="Body Weight" id="js-weight-text" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <button type="button" id="js-weight-add" class="btn btn-sm-in btn-primary {{ $disableClass }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="res-table">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Datetime</th>
                                    <th>WT</th>
                                </tr>
                            </thead>
                            <tbody class="js-weights"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-modal"></div>

<div class="modal" id="js-onexam-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Abnormal Status</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <input type="hidden" id="js-onexam-fldid-input">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="js-onexam-status-select">
                            <option value="0">Normal</option>
                            <option value="1">Abnormal</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-5">
                        <button type="button" id="js-onexam-status-save-modal" class="btn btn-success btn-sm {{ $disableClass }}">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
