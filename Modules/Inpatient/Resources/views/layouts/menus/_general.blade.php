<div id="general" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-5 col-lg-4">
                            <label class="form-label">Major Symptoms</label>
                        </div>
                        <div class="col-sm-7 col-lg-8 p-0">
                            <button class="btn btn-sm-in btn-danger" type="button">
                                <i class="fa fa-exclamation-circle"></i>&nbsp;Problem
                            </button>
                            <button id="js-general-freewriting-symptoms-add-btn"  class="btn btn-sm-in btn-primary {{ $disableClass }}"><i class="fa fa-plus"></i></button>
                            <button id="js-general-add-symptom-btn" class="btn btn-sm-in btn-warning {{ $disableClass }}"><i class="fa fa-plus"></i></button>
                            <button id="js-general-symptom-delete-btn" class="btn btn-sm-in btn-danger {{ $disableClass }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="res-table">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Startdate</th>
                                    <th>Symptoms</th>
                                    <th>Serverity</th>
                                    <th>Days</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="js-general-symptoms-tbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-horizontal">
                        <div class="row">
                            <label class="control-label col-sm-7 pr-0 col-lg-5 align-self-center mb-0" for="">Heptatic Status:</label>
                            <div class="col-sm-3 col-lg-5 p-0">
                                <select class="form-control mb-3" id="js-general-heptatic-status-select">
                                    <option value="Impaired">Impaired</option>
                                    <option value="Normal">Normal</option>
                                </select>
                            </div>
                            <div class="col-sm-2 col-lg-2">
                                <button class="btn btn-sm-in btn-primary js-general-status-save-bth {{ $disableClass }}" data-fldhead="Hepatic Status" type="button">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-7 pr-0 col-lg-5 align-self-center mb-0" for="">Pregnancy/Breast feeding:</label>
                            <div class="col-sm-3 col-lg-5 p-0">
                                <select class="form-control mb-3" id="js-general-pregnancy-status-select">
                                    <option value="1st Trimester">1st Trimester</option>
                                    <option value="2nd Trimester">2nd Trimester</option>
                                    <option value="3rd Trimester">3rd Trimester</option>
                                    <option value="Breast feeding">Breast feeding</option>
                                    <option value="Non Pregnant">Non Pregnant</option>
                                </select>
                            </div>
                            <div class="col-sm-2 col-lg-2">
                                <button class="btn btn-sm-in btn-primary js-general-status-save-bth {{ $disableClass }}" data-fldhead="Pregnancy/Breast feeding" type="button">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-7 col-lg-5 pr-0 align-self-center mb-0" for="">Ideal body weight (kg):</label>
                            <div class="col-sm-2 col-lg-4">
                                <input class="form-control mb-3" readonly="readonly" type="text" value="{{ (isset($data['body_weight']) && $data['body_weight']) ? $data['body_weight'] : '' }}">
                            </div>
                            <div class="col-sm-3 col-lg-3 p-0">
                                <button class="btn btn-sm-in btn-warning" type="button">
                                    <i class="fa fa-question-circle"></i>
                                </button>
                                <button class="btn btn-sm-in btn-primary" type="button">
                                    <i class="fa fa-flask"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-5   col-sm-7 col-lg-5 pr-0  align-self-center mb-0" for="">Body surface Area (Sq m):</label>
                            <div class="col-sm-2 col-lg-4">
                                <input class="form-control mb-3" for="" value="">
                            </div>
                            <div class="col-sm-3 col-lg-3 p-0">
                                <button class="btn btn-sm-in btn-warning" type="button">
                                    <i class="fa fa-question-circle"></i>
                                </button>
                                <button class="btn btn-sm-in btn-primary" type="button">
                                    <i class="fa fa-flask"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-5   col-sm-7 col-lg-5 pr-0 align-self-center mb-0" for="">Creat Cl(ml/min/1.73sqm):</label>
                            <div class="col-sm-2 col-lg-4">
                                <input class="form-control mb-3" for="" value="">
                            </div>
                            <div class="col-sm-3 col-lg-3 p-0">
                                <button class="btn btn-sm-in btn-warning" type="button">
                                    <i class="fa fa-question-circle"></i>
                                </button>
                                <button class="btn btn-sm-in btn-primary" type="button">
                                    <i class="fa fa-flask"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-5   col-sm-7 col-lg-5 pr-0 align-self-center mb-0" for="">Body Mass Index(kg/sqm):</label>
                            <div class="col-sm-2 col-lg-4">
                                <input class="form-control mb-3" readonly="readonly" type="text" value="{{ isset($data['bmi']) ? $data['bmi'] : '' }}">
                            </div>
                            <div class="col-sm-3 col-lg-3 p-0">
                                <button class="btn btn-sm-in btn-warning" type="button">
                                    <i class="fa fa-question-circle"></i>
                                </button>
                                <button class="btn btn-sm-in btn-primary" type="button">
                                    <i class="fa fa-flask"></i>
                                </button>
                            </div>
                            <label class="control-label col-sm-5   col-sm-7 col-lg-5 pr-0 padding-right align-self-center mb-0" for="">Serum Osmolality(mOsm/L):</label>
                            <div class="col-sm-2 col-lg-4">
                                <input class="form-control mb-3" for="" value="">
                            </div>
                            <div class="col-sm-3 col-lg-3 p-0">
                                <button class="btn btn-sm-in btn-warning" type="button">
                                    <i class="fa fa-question-circle"></i>
                                </button>
                                <button class="btn btn-sm-in btn-primary" type="button">
                                    <i class="fa fa-flask"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-general-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Alternate Sensation</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <p>Select severity of the symptom</p>
                    <div class="col-md-7">
                        <input type="hidden" id="js-general-fldid-input">
                        <select class="form-input" id="js-general-status-select" style="width: 100%;">
                            <option value="Subthreshold">Subthreshold</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                            <option value="Upper threshold">Upper threshold</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="button" id="js-general-status-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-general-symptoms-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Symptoms</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row" style="max-height: 300px;overflow-y: scroll;">
                    <input type="text" id="js-general-modal-search-input" class="form-input">&nbsp;
                    <button class="btn btn-success btn-sm" id="js-general-symptoms-save-modal">Save</button>
                    <table class="table-1 fluids">
                        <tbody id="js-general-symptoms-list-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-general-freewriting-symptoms-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Enter Symptoms</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <input type="text" class="form-input" id="js-general-freewriting-symptoms-input">&nbsp;
                    <button class="btn btn-success btn-sm" id="js-general-freewriting-symptoms-save-btn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-general-flddetail-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Enter Symptoms Report</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row" style="padding: 10px;">
                    <textarea id="js-general-flddetail-textarea" style="width: 100%;height: 100px;"></textarea>
                    <button class="btn btn-success btn-sm" id="js-general-flddetail-save-btn" style="margin-top: 10px;">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
