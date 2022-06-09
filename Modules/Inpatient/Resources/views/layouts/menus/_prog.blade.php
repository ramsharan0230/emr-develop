<div id="prog" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="js-prog-date-input">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control after-date-box" readonly>
                        </div>
                    </div>
                    <div class="dietarytable">
                        <table class="table table-hovered table-striped">
                            <tbody id="js-prog-time-tbody"></tbody>
                        </table>
                    </div>
                    <div class="dietarytable">
                        <button type="button" class="btn btn-info float-righ mb-3 {{ $disableClass }}" id="js-prog-add-btn"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                        <button type="button" class="btn btn-warning float-righ mb-3 {{ $disableClass }}" onclick="essenseExam.displayModal()"><i class="ri-edit-2-fill"></i>&nbsp;&nbsp;Essential</button>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
                        <div class="box__collipsble1 pr-1">
                            <label class="">Problems</label>
                            <a class="btn-dental-form collapsed" data-toggle="collapse" href="#problem" aria-expanded="false" aria-controls="problem">
                                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
                            </a>
                        </div>
                        <div class="box__collipsble1 pr-1">
                            <label class="">On Examination</label>
                            <a class="btn-dental-form collapsed" data-toggle="collapse" href="#onexamination" aria-expanded="false" aria-controls="onexamination">
                                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
                            </a>
                        </div>
                        <div class="box__collipsble1 pr-1">
                            <label class=""> Treatment</label>
                            <a class="btn-dental-form collapsed" data-toggle="collapse" href="#treatment" aria-expanded="false" aria-controls="treatment">
                                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
                            </a>
                        </div>
                        <div class="box__collipsble1 pr-1">
                            <label class=""> Input/Output</label>
                            <a class="btn-dental-form collapsed" data-toggle="collapse" href="#inputoutput" aria-expanded="false" aria-controls="inputoutput">
                                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
                            </a>
                        </div>
                        <div class="box__collipsble1">
                            <label class=""> Planning</label>
                            <a class="btn-dental-form collapsed" data-toggle="collapse" href="#planning" aria-expanded="false" aria-controls="planning">
                                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
                            </a>
                        </div>
                    </div>
                    <div class="collapse prog-accordion" id="problem">
                       <div class="form-group form-row">
                        <h6 class="card-title pr-5">Problem Assesment</h6>
                        <button id="js-prog-problem-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                            <i class="fas fa-check pr-0"></i>
                        </button>
                    </div>
                    <div class="form-group mb-0">
                        <textarea class="form-control present-textarea" name="prog_problem" id="js-prog-problem-input"></textarea>
                    </div>
                </div>
                <div class="collapse prog-accordion" id="onexamination">
                    <div class="form-group form-row align-items-center mt-3">
                        <div class="col-sm-4">
                            <select id="js-prog-exam-select" class="form-control"></select>
                        </div>
                        <div class="col-sm-4" id="js-prog-exam-span">
                            <select id="js-prog-exam-input" class="form-control"></select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="js-prog-exam-input-quantity">
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-sm-in btn-sm-in btn-primary" id="js-prog-exam-add-btn" type="button">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="inpatient-table table-responsive">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Examination</th>
                                    <th>&nbsp;</th>
                                    <th>Observation</th>
                                    <th>&nbsp;</th>
                                    <th>Report Time</th>
                                    <th>UserID</th>
                                </tr>
                            </thead>
                            <tbody id="js-prog-exam-tbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="collapse prog-accordion" id="treatment">
                    <div class="form-group form-row ">
                        <h6 class="card-title pr-5">Treatment Assesment</h6>
                        <button id="js-prog-treatment-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                            <i class="fas fa-check pr-0"></i>
                        </button>
                    </div>
                    <div class="form-group mb-0">
                        <textarea class="form-control present-textarea" name="prog_treatment" id="js-prog-treatment-input"></textarea>
                    </div>
                </div>
                <div class="collapse prog-accordion" id="inputoutput">
                  <div class="form-group form-row ">
                    <h6 class="card-title pr-5">Input and Out Assesment</h6>
                    <button id="js-prog-in-output-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                        <i class="fas fa-check pr-0"></i>
                    </button>
                </div>
                <div class="form-group mb-0">
                    <textarea class="form-control present-textarea" name="prog_input" id="js-prog-in-output-input"></textarea>
                </div>
            </div>
            <div class="collapse prog-accordion" id="planning">
                <form action="" class="form-horizontal">
                    <div class="form-group form-row align-items-center mt-3">
                        <div class="col-sm-8 er-input padding-none">
                            <h6 class="card-title padding-none">Impression:</h6>&nbsp;
                            <input type="text" class="form-control" id="js-prog-planning-drop-input">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-sm-in btn-warning" type="button" id="js-prog-planning-drop-save-btn">
                                <i class="fa fa-check"></i>
                            </button>
                            <button class="btn btn-sm-in btn-primary" type="button">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="form-group form-row ">
                    <h6 class="card-title pr-5">Nurse
                    </h6>
                    <button id="js-prog-planning-text-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                        <i class="fas fa-check pr-0"></i>
                    </button>
                </div>
                <div class="form-group mb-0">
                    <textarea class="form-control prog-textarea" name="prog_plan" id="js-prog-planning-text-input"></textarea>
                </div>
            </div>
          <!--   <div id="accordion">
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#problem">
                                Problems <button type="button" class="btn btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
                            </a>
                        </div>
                        <div id="problem" class="collapse" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between mt-3">
                                <h6 class="card-title">Problem Assesment</h6>
                                <button id="js-prog-problem-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fas fa-check pr-0"></i>
                                </button>
                            </div>
                            <div class="form-group mb-0">
                                <textarea class="form-control present-textarea" name="prog_problem" id="js-prog-problem-input"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="collapsed card-link" data-toggle="collapse" href="#onexamination">
                                On Examination <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
                            </a>
                        </div>
                        <div id="onexamination" class="collapse" data-parent="#accordion">
                            <div class="form-group form-row align-items-center mt-3">
                                <div class="col-sm-4">
                                    <select id="js-prog-exam-select" class="form-control"></select>
                                </div>
                                <div class="col-sm-4" id="js-prog-exam-span">
                                    <select id="js-prog-exam-input" class="form-control"></select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="js-prog-exam-input-quantity">
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-sm-in btn-sm-in btn-primary" id="js-prog-exam-add-btn" type="button">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="inpatient-table table-responsive">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Examination</th>
                                            <th>&nbsp;</th>
                                            <th>Observation</th>
                                            <th>&nbsp;</th>
                                            <th>Report Time</th>
                                            <th>UserID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-prog-exam-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
                                Treatment <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
                            </a>
                        </div>
                        <div id="collapseThree" class="collapse" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between mt-3">
                                <h6 class="card-title">Treatment Assesment</h6>
                                <button id="js-prog-treatment-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fas fa-check pr-0"></i>
                                </button>
                            </div>
                            <div class="form-group mb-0">
                                <textarea class="form-control present-textarea" name="prog_treatment" id="js-prog-treatment-input"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="collapsed card-link" data-toggle="collapse" href="#collapsefour">
                                Input/Output <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
                            </a>
                        </div>
                        <div id="collapsefour" class="collapse" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between mt-3">
                                <h6 class="card-title">Input and Out Assesment</h6>
                                <button id="js-prog-in-output-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fas fa-check pr-0"></i>
                                </button>
                            </div>
                            <div class="form-group mb-0">
                                <textarea class="form-control present-textarea" name="prog_input" id="js-prog-in-output-input"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="collapsed card-link" data-toggle="collapse" href="#collapseFive">
                                Planing <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
                            </a>
                        </div>
                        <div id="collapseFive" class="collapse" data-parent="#accordion">
                            <form action="" class="form-horizontal">
                                <div class="form-group form-row align-items-center mt-3">
                                    <div class="col-sm-8 er-input padding-none">
                                        <h6 class="card-title padding-none">Impression:</h6>&nbsp;
                                        <input type="text" class="form-control" id="js-prog-planning-drop-input">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-sm-in btn-warning" type="button" id="js-prog-planning-drop-save-btn">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm-in btn-primary" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="iq-card-header d-flex justify-content-between mt-3">
                                <h6 class="card-title">Nurse
                                </h6>
                                <<button id="js-prog-planning-text-save-btn" class="btn btn-sm-in btn-sm btn-primary mb-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                    <i class="fas fa-check pr-0"></i>
                                </button>
                            </div>
                            <div class="form-group mb-0">
                                <textarea class="form-control prog-textarea" name="prog_plan" id="js-prog-planning-text-input"></textarea>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
</div>