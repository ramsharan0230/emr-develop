<div id="routine" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group form-row align-items-center">
                    <div class="col-sm-2 col-lg-3 er-input">
                        <input  type="date" id="js-routine-date-input" class="form-control col-8" onchange="onchangeDateRoutine(event);">&nbsp;
                        <input type="text" name="" class="form-control after-date-box" readonly>
                    </div>
                    <div class="col-sm-4 col-lg-3 p-0 text-center">
                        <button class="btn btn-sm-in btn-primary js-routine-labels-btn" type="button">
                            <i class="fa fa-question-circle"></i>&nbsp;Label
                        </button>
                        <button class="btn btn-sm-in btn-warning js-routine-druginfo-btn" type="button">
                            <i class="ri-information-fill"></i>&nbsp;Drug Info
                        </button>
                        <button class="btn btn-sm-in btn-danger js-routine-review-btn" type="button">
                            <i class="fa fa-exclamation-circle"></i>&nbsp;Review
                        </button>
                    </div>
                    <div class="col-sm-3 col-lg-3 ext-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="routine-value" name="radio-routine-top" class="custom-control-input js-routine-option-radio" checked="checked" value="value">
                            <label class="custom-control-label" for="routine-value"> Value </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="routine-label" name="radio-routine-top" class="custom-control-input js-routine-option-radio" value="label">
                            <label class="custom-control-label" for="routine-label"> Label </label>
                        </div>
                    </div>
                    <div class="col-sm-3 col-lg-3 text-center">
                        <button class="btn btn-sm-in btn-primary" type="button" datatype="all" id="list_all_routine">
                            <i class="fa fa-list"></i>&nbsp;Show All
                        </button>
                        <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal();">
                            <i class="ri-information-fill"></i>
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
                            <th>StartDate</th>
                            <th>Routine</th>
                            <th>Medicine</th>
                            <th>Dose</th>
                            <th>Freq</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>N</th>
                        </tr>
                    </thead>
                    <tbody class="show_all_routine" id="js-routine-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('inpatient::layouts.modal.edit-routine')
@include('inpatient::layouts.modal.update-status-routine')
@include('inpatient::layouts.modal.update-days-routine')
