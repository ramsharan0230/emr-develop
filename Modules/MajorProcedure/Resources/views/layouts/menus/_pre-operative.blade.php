<div class="tab-pane fade" id="preoperative" role="tabpanel" aria-labelledby="preoperative-tab">

    <!-- Collapse buttons -->
    <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
        <div class="box__collipsble1 pr-4">
            <label class="">Discussion</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#discussion" aria-expanded="false" aria-controls="discussion">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
        <div class="box__collipsble1 pr-4">
            <label class=""> Examination</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#examination" aria-expanded="false" aria-controls="examination">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
        <div class="box__collipsble1 pr-4">
            <label class=""> Clinical</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#clinical" aria-expanded="false" aria-controls="clinical">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
        <div class="box__collipsble1 pr-4">
            <label class=""> Pharmacy</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#pharmacy" aria-expanded="false" aria-controls="pharmacy">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
    </div>
    <hr>
    <!-- / Collapse buttons -->
    <!-- Collapsible element -->
    <div class="collapse" id="discussion" data-parent="#preoperative">
        <div class="row mt-2">
            <div class="col-sm-6">
                <div class="er-input">
                    <label>Personal</label>
                    <div class="col-6">
                        <input type="text" class="form-control" id="pre-operative-discussion-freetext">
                    </div>
                    <div class="col-4">
                        <a href="javascript:;" id="save-pre-operative-discussion-freetext" url="{{ route('insert.preOperativeDiscussion.freetext') }}" class="btn btn-primary float-right btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                    </div>
                </div>
                <div class="major-table table-responsive tablepreoperative">
                    <table class="table table-hovered table-bordered table-striped preoperative-table dislpay-pre-operative-fldreportquali">
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="major-table table-responsive tablepreoperative2">
                    <table class="table table-hovered table-bordered table-striped preoperative-table2 display-pat-finding">
                    </table>
                </div>
                <a href="javascript:;" id="save-pre-operative-discussion-textarea" url="{{ route('insert.preOperativeDiscussion.textarea') }}" class="btn btn-primary float-right btn-sm mt-2"><i class="fa fa-check pr-0"></i></a>
            </div>
            <div class="col-sm-12 mt-2">
                <div class="form-group mb-0">
                    <textarea name="pre_operative_discussion_textarea" class="form-control" id="pre_operative_discussion_textarea"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Collapsible element -->
    <div class="collapse" id="examination" data-parent="#preoperative">
        <div class="row">
            <div class="col-sm-4">
                <div class="major-table dietarytable table-examination-right examination-listed">
                    <table class="pre-operative-examination-list">
                    </table>
                    {{--<nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>--}}
                </div>
            </div>
            <div class="col-sm-8">
                <div class="res-table mt-2">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Examination</th>
                                <th>&nbsp;</th>
                                <th>Observation</th>
                                <th>Report Time</th>
                                <th>UserID</th>
                            </tr>
                        </thead>
                        <tbody class="major-pre-examination-table">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Collapsible element -->
    <div class="collapse" id="clinical" data-parent="#preoperative">
        <div class="row">
            <div class="col-sm-12">
                <div class="er-input mt-2">
                    <label>Indication</label>
                    <div class="col-6">
                        <input type="text" class="form-control" id="clinical-indication">
                    </div>
                    <div class="col-1">
                        <a href="javascript:;" id="save-clinical-indication" url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                    </div>
                    <div class="col-5">
                        <button type="button" id="save-clinical-note" url="{{ route('insert.clinicalNote.textarea') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i>&nbsp;&nbsp;Save Note</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="res-table mt-2">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S/N</th>
                                <th>Date Time</th>
                                <th>Indication</th>
                            </tr>
                        </thead>
                        <tbody class="show-clinical-indication"></tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <textarea name="clinical_note_textarea" id="clinical_note_textarea" class="form-control textarea-major"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Collapsible element -->
    <div class="collapse" id="pharmacy" data-parent="#preoperative">
        <div class="form-group float-right mt-2">
            <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
            <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
            <button class="btn btn-sm-in btn-primary btn-pharmacy" type="button" id="getAllPhramacy"><i class="fa fa-list"></i>&nbsp;Show All</button>
            <a href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" class="btn btn-sm-in btn-warning" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
        </div>
        <div class="res-table">
            <table class="table table-hovered table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Start Date</th>
                        <th>Routine</th>
                        <th>Medicine</th>
                        <th>Dose</th>
                        <th>Freq</th>
                        <th>Days</th>
                        <th>N</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="show-all-phramacy">

                </tbody>
            </table>
        </div>
    </div>
</div>
