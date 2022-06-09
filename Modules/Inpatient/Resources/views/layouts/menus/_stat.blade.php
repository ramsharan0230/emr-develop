<div id="stat" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group form-row align-items-center">
                    <div class="col-sm-2 col-lg-3 er-input">
                        <input type="date" id="js-stat-date-input" onchange="onchangeDateStat(event);" class="form-control col-8">&nbsp;
                        <input type="text" class="form-control after-date-box">
                    </div>
                    <div class="col-sm-4 col-lg-3 p-0 text-center">
                        <button class="btn btn-sm-in btn-primary js-statprn-labels-btn" type="button">
                            <i class="fa fa-question-circle"></i>&nbsp;Label
                        </button>
                        <button class="btn btn-sm-in btn-warning js-statprn-druginfo-btn" type="button">
                            <i class="ri-information-fill"></i>&nbsp;Drug Info
                        </button>
                        <button class="btn btn-sm-in btn-danger js-statprn-review-btn" type="button">
                            <i class="fa fa-exclamation-circle"></i>&nbsp;Review
                        </button>
                    </div>
                    <div class="col-sm-3 col-lg-3 text-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="radio-stat-top" id="stat-value" class="custom-control-input js-stat-option-radio"  value="value" checked="checked">
                            <label class="custom-control-label" for="customRadio6"> Value </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="radio-stat-top" id="stat-label" class="custom-control-input js-stat-option-radio"  value="label">
                            <label class="custom-control-label" for="customRadio6"> Label </label>
                        </div>
                    </div>
                    <div class="col-sm-3 col-sm-3 text-center">
                        <button class="btn btn-sm-in btn-primary" type="button" id="list_all_stat">
                            <i class="fa fa-list"></i>&nbsp;Show All
                        </button>
                        <button class="btn btn-sm-in btn-info {{ $disableClass }}" onclick="dosingRecord.displayModal();" type="button">
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
                    <tbody class="show_all_stat" id="js-statprn-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('inpatient::layouts.modal.update-status-stat')
@include('inpatient::layouts.modal.update-days-stat')
