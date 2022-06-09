<div id="fluid" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group form-row align-items-center">
                    <div class="col-sm-4 col-lg-4  er-input">
                        <input type="date" class="form-control" id="js-fluids-date">&nbsp;
                        <input type="text" class="form-control after-date-box" readonly>
                    </div>
                    <div class="col-sm-5 col-lg-4 text-center">
                        <button class="btn btn-sm-in btn-primary" type="button" id="js-fluids-info-top-btn">
                            <i class="fa fa-question-circle"></i>&nbsp;Info
                        </button>
                        <button class="btn btn-sm-in btn-warning" type="button" id="js-fluids-bluecompact-top-btn">
                            <i class="ri-information-fill"></i>&nbsp;Compact
                        </button>
                        <button class="btn btn-sm-in btn-danger" type="button">
                            <i class="fa fa-exclamation-circle"></i>&nbsp;Compact
                        </button>

                        <button type="button" data-target="#js-intake-modal" class="btn btn-sm btn-primary btn-sm-in {{ $disableClass }}" data-toggle="modal" data-placement="top" data-original-title="In"><i class="fa fa-plus pr-0"></i>&nbsp;&nbsp;In</button>&nbsp;
                        <button type="button" data-target="#outFluid" class="btn btn-sm btn-primary btn-sm-in {{ $disableClass }}" data-toggle="modal" data-placement="top" data-original-title="Out"><i class="fas fa-minus pr-0"></i>&nbsp;&nbsp;Out</button>
                    </div>
                    <div class="col-sm-3 col-lg-4 text-center">
                        <button class="btn btn-sm-in btn-primary" type="button" id="js-fluids-showall-btn" datatype="all">
                            <i class="fa fa-list"></i>&nbsp;Show All
                        </button>
                        <button class="btn btn-sm-in btn-warning" type="button">
                            <i class="fa fa-code"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="res-table">
                <table class="table table-hovered table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Start Date</th>
                            <th>Medicine</th>
                            <th>Dose</th>
                            <th>Freq</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="js-fluids-medicines-tbody"></tbody>
                </table>
            </div><br>
            <div class="inpatient-table  table-responsive">
                <table class="table table-hovered table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Particulars</th>
                            <th>Rate</th>
                            <th>Unit</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="js-fluids-particulars-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-fluids-play-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title"></h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row col-md-12">
                            <textarea class="form-input" style="width: 100%;height: 100%" disabled>Enter Rate of Administration in mL/Hour</textarea>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" id="js-fluid-play-fldid-input">
                                    <input type="text" name="" id="js-fluids-dosevalue-input" class="form-input">
                                </div>
                            </div>
                            <button type="button" id="js-fluid-play-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-fluids-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Current Status</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div id="js-fluids-status-text-display"></div>
                        <input type="hidden" id="js-fluid-fldid-input">
                        <select class="form-input" id="js-fluid-status-select" style="width: 100%;">
                            <option value="Continue">Continue</option>
                            <option value="Discontinue">Discontinue</option>
                            <option value="Hold">Hold</option>
                            <option value="Change">Change</option>
                            <option value="ReWrite">ReWrite</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="button" id="js-fluid-status-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
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

<div class="modal" id="js-fluids-change-date-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Date Time</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Date</label>
                        <input type="date" id="js-fluids-change-date-input" class="form-input remove-indicator">
                        <label>Time</label>
                        <input type="text" id="js-fluids-change-time-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-fluid-change-date-btn" class="btn btn-success btn-sm">Save</button>&nbsp;
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

<div class="modal" id="js-fluids-change-dose-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Dose</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Dose</label>
                        <input type="text" id="js-fluids-change-dose-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-fluid-change-dose-btn" class="btn btn-success btn-sm">Save</button>&nbsp;
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

<div class="modal" id="js-fluids-bluecompact-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Compatibility Information</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" id="js-fluids-bluecompact-title"></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Compatible Drugs</label>
                        </div>
                        <div class="css-fluids-bluecompact-md-3" id="js-compatible-drugs-div"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Incompatible Drugs</label>
                        </div>
                        <div class="css-fluids-bluecompact-md-3" id="js-incompatible-drugs-div"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Compatible Fluids</label>
                        </div>
                        <div class="css-fluids-bluecompact-md-3" id="js-compatible-fluids-div"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Incompatible Fluids</label>
                        </div>
                        <div class="css-fluids-bluecompact-md-3" id="js-incompatible-fluids-div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
