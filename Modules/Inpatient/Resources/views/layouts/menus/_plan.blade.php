<div id="plan" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-4">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-8 col-lg-6">
                                <input type="date" id="js-plan-date" class="form-control">
                            </div>
                            <div class="col-sm-4 col-lg-6">
                                <input type="text" class="form-control after-date-box" readonly>
                            </div>
                        </div>
                    </form>
                    <div class="dietarytable plan-table">
                        <table class="table table-hovered table-striped">
                            <tbody id="js-plan-tbody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="plan-div">
                        <form action="" class="form-horizontal">
                            <div class="col-sm-8 er-input padding-none">
                                <h5 class="card-title col-md-8 col-lg-5 padding-none">Problem Statement:</h5>&nbsp;
                                <input type="text" class="form-control" id="js-plan-problem-statement-input">
                            </div>
                        </form>
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Subjective Parameters:</h4>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <textarea name="plan_subject" id="js-plan-subjective-parameter-textarea" class="form-control"></textarea>
                        </div><br>
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Objective Parameters:</h4>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <textarea class="form-control" name="plan_object" id="js-plan-objective-parameter-textarea"></textarea>
                        </div><br>
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Assestment:</h4>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <textarea name="plan_assess" id="js-plan-assessment-textarea" class="form-control"></textarea>
                        </div><br>
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Planning:</h4>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <textarea name="plan_planning" id="js-plan-planning-textarea" class="form-control"></textarea>
                        </div>
                        <div class="form-group form-row align-items-center mt-3">
                            <div class="col-sm-3">
                                <button type="add" id="js-plan-add-btn"  class="btn btn-primary {{ $disableClass }}" type="button">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                                <button type="edit" id="js-plan-edit-btn"  class="btn btn-warning {{ $disableClass }}" type="button">
                                    <i class="ri-edit-fill"></i>&nbsp;Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>