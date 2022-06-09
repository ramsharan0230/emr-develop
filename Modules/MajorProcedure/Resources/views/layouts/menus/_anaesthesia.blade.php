<div class="tab-pane fade" id="anasthesia" role="tabpanel" aria-labelledby="anasthesia-tab">
    <!-- Collapse buttons -->
    <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
        <div class="box__collipsble1 pr-4">
            <label class=""> Examination</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#anaesexamination" aria-expanded="false" aria-controls="anaesexamination">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
        <div class="box__collipsble1 pr-4">
            <label class=""> Clinical</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#anaesclinical" aria-expanded="false" aria-controls="anaesclinical">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
        <div class="box__collipsble1 pr-4">
            <label class=""> Pharmacy</label>
            <a class="btn-dental-form" data-toggle="collapse" href="#anaespharmacy" aria-expanded="false" aria-controls="anaespharmacy">
                <button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button>
            </a>
        </div>
    </div>
    <hr>
    <!-- / Collapse buttons -->
    <!-- Collapsible element -->
    <div class="collapse" id="anaesexamination" data-parent="#anasthesia">
        <div class="row">
            <div class="col-sm-5">
                <div class="major-table dietarytable">
                    <table class="table table-hovered table-striped anaesthesia-examination-list">

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
            <div class="col-sm-7">
                <div class="res-table mt-2">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Examination</th>
                                <th>&nbsp;</th>
                                <th>Observation</th>
                                <th>Report Time</th>
                                <th>UserID</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody class="major-anaesthesia-examination-table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Collapsible element -->
    <div class="collapse" id="anaesclinical" data-parent="#anasthesia">
        <div class="row">
            <div class="col-sm-12">
                <div class="er-input mt-2">
                    <label>Tecnique</label>
                    <div class="col-1">
                        <a href="javascript:;" data-toggle="modal" data-target="#anaesthesia_variables"
                        onclick="getProcedureVariables()" class="btn btn-primary btn-sm mb-3"><i class="fa fa-plus pr-0"></i></a>

                    </div>
                    <div class="col-5">
                        <select id="clinical-indication-ana" name="clinical-indication-ana" class="form-control">
                            <option value=""></option>
                        </select>

                    </div>
                    <div class="col-1">
                        <a href="javascript:;" id="save-clinical-indication-ana"
                        url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                    </div>
                    <div class="col-4">
                        <a href="javascript:;" id="save-clinical-note-ana"
                        url="{{ route('insert.clinicalNote.textarea') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i>&nbsp;&nbsp;Save Note</a>
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
                        <tbody class="list-clinical-indication show-clinical-indication-ana"></tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-2">
                    <input type="hidden" id="current_id_ana" value="">
                    <input type="hidden" id="chapter_ana" value="">
                    <input type="hidden" id="report_quali_ana" value="">
                    <textarea class="form-control textarea-major" name="clinical_note_ana_textarea" id="clinical_note_ana_textarea"></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- Collapsible element -->
    <div class="collapse" id="anaespharmacy" data-parent="#anasthesia">
        <div class="form-group float-right mt-2">
            <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
            <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
            <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyAnaesthesia"><i class="fa fa-list"></i>&nbsp;Show All</button>
            <a class="btn btn-sm-in btn-warning" href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
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
                        <th>Status</th>
                        <th>N</th>

                    </tr>
                </thead>
                <tbody class="show-all-phramacyAnaesthesia">

                </tbody>
            </table>
        </div>
    </div>
<!-- <div id="accordion">
    <div class="card">
        <div class="card-header">
            <a class="collapsed" data-toggle="collapse" href="#collapseExam">
                Examination
                <button type="button" class="btn btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseExam" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dietarytable">
                            <table class="table table-hovered table-striped anaesthesia-examination-list">

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
<div class="col-sm-7">
    <div class="major-table table-responsive mt-2">
        <table class="table table-hovered table-bordered table-striped">
            <thead>
                <tr>
                    <th>Examination</th>
                    <th>&nbsp;</th>
                                        <th>Observation</th>
                                        <th>Report Time</th>
                                        <th>UserID</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody class="major-anaesthesia-examination-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed" data-toggle="collapse" href="#collapseClinic">
                Clinical Note
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseClinic" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="er-input mt-2">
                            <label>Tecnique</label>
                            <div class="col-1">
                                <a href="javascript:;" data-toggle="modal" data-target="#anaesthesia_variables"
                                onclick="getProcedureVariables()" class="btn btn-primary btn-sm mb-3"><i class="fa fa-plus pr-0"></i></a>

                            </div>
                            <div class="col-5">
                                <select id="clinical-indication-ana" name="clinical-indication-ana" class="form-control">
                                    <option value=""></option>
                                </select>

                            </div>
                            <div class="col-1">
                                <a href="javascript:;" id="save-clinical-indication-ana"
                                url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                            </div>
                            <div class="col-2">
                                <a href="javascript:;" id="save-clinical-note-ana"
                                url="{{ route('insert.clinicalNote.textarea') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i>&nbsp;&nbsp;Save Note</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="major-table table-responsive mt-2">
                            <table class="table table-hovered table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Date Time</th>
                                        <th>Indication</th>
                                    </tr>
                                </thead>
                                <tbody class="list-clinical-indication show-clinical-indication-ana"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <input type="hidden" id="current_id_ana" value="">
                            <input type="hidden" id="chapter_ana" value="">
                            <input type="hidden" id="report_quali_ana" value="">
                            <textarea class="form-control textarea-major" name="clinical_note_ana_textarea" id="clinical_note_ana_textarea"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed" data-toggle="collapse" href="#collapsePhar">
                Pharmacy
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapsePhar" class="collapse" data-parent="#accordion">
            <div class="form-group float-right mt-2">
                <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
                <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
                <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyAnaesthesia"><i class="fa fa-list"></i>&nbsp;Show All</button>
                <a class="btn btn-sm-in btn-warning" href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
            </div>
            <div class="major-table table-responsive">
                <table class="table table-hovered table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Start Date</th>
                            <th>Routine</th>
                            <th>Medicine</th>
                            <th>Dose</th>
                            <th>Freq</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>N</th>

                        </tr>
                    </thead>
                    <tbody class="show-all-phramacyAnaesthesia">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->
</div>
@include('majorprocedure::layouts.modal.anaesthesia_variables')
