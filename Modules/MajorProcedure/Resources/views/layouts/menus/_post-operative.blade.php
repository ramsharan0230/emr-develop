<div class="tab-pane fade" id="postoperative" role="tabpanel" aria-labelledby="postoperative-tab">
   <!-- Collapse buttons -->
   <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
    <div class="box__collipsble1 pr-4">
        <label class=""> Examination</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#postexamination" aria-expanded="false" aria-controls="postexamination"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class=""> Clinical</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#postclinical" aria-expanded="false" aria-controls="postclinical"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class=""> Pharmacy</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#postpharmacy" aria-expanded="false" aria-controls="postpharmacy"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
</div>
<hr>
<!-- / Collapse buttons -->
<!-- Collapsible element -->
<div class="collapse" id="postexamination" data-parent="#postoperative">
 <div class="row">
    <div class="col-sm-6">
        <div class="res-table">
            <table class="post-operative-examination-list table table-hovered table-striped">
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
    <div class="col-sm-6">
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
                <tbody class="major-post-examination-table"></tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Collapsible element -->
<div class="collapse" id="postclinical" data-parent="#postoperative">
 <div class="row">
    <div class="col-sm-12">
        <div class="er-input mt-2">
            <label>Indication</label>
            <div class="col-6">
                <input type="text" name="" id="clinical-indication-postOp" class="form-control"/>
            </div>
            <div class="col-1">
                <a href="javascript:;" id="save-clinical-indication-postOp"
                url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
            </div>
            <div class="col-5">
                <a href="javascript:;" id="save-clinical-note-postOp"
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
                <tbody class="list-clinical-indication show-clinical-indication-postOp"></tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-2">
            <input type="hidden" id="current_id_postO" value="">
            <input type="hidden" id="chapter_postO" value="">
            <textarea name="clinical_note_postOp_textarea" id="clinical_note_postOp_textarea" class="form-control textarea-major"></textarea>
        </div>
    </div>
</div>
</div>
<!-- Collapsible element -->
<div class="collapse" id="postpharmacy" data-parent="#postoperative">
   <div class="form-group float-right mt-2">
        <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
        <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
        <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyPostOp"><i class="fa fa-list"></i>&nbsp;Show All</button>
        <a href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" class="btn btn-sm-in btn-warning" type="button" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
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
            <tbody class="show-all-phramacyPostOp">

            </tbody>
        </table>
    </div>
</div>

<!-- <div id="accordion">
    <div class="card">
        <div class="card-header">
            <a class="card-link" data-toggle="collapse" href="#collapseOne">
                Examination
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dietarytable">
                            <table class="post-operative-examination-list table table-hovered table-striped">
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
                    <div class="col-sm-6">
                        <div class="major-table table-responsive mt-2">
                            <table class="table table-hovered table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Examination</th>
                                        <th>&nbsp;</th>
                                        <th>Observation</th>
                                        <th>Report Time</th>
                                        <th>UserID</th>
                                    </tr>
                                </thead>
                                <tbody class="major-post-examination-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="card-link" data-toggle="collapse" href="#collapseTwo">
                Clinical Note
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseTwo" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="er-input mt-2">
                            <label>Indication</label>
                            <div class="col-6">
                                <input type="text" name="" id="clinical-indication-postOp" class="form-control"/>
                            </div>
                            <div class="col-1">
                                <a href="javascript:;" id="save-clinical-indication-postOp"
                                url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                            </div>
                            <div class="col-2">
                                <a href="javascript:;" id="save-clinical-note-postOp"
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
                                <tbody class="list-clinical-indication show-clinical-indication-postOp"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <input type="hidden" id="current_id_postO" value="">
                            <input type="hidden" id="chapter_postO" value="">
                            <textarea name="clinical_note_postOp_textarea" id="clinical_note_postOp_textarea" class="form-control textarea-major"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="card-link" data-toggle="collapse" href="#collapseThree">
                Pharmacy
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseThree" class="collapse" data-parent="#accordion">
            <div class="form-group float-right mt-2">
                <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
                <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
                <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyPostOp"><i class="fa fa-list"></i>&nbsp;Show All</button>
                <a href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" class="btn btn-sm-in btn-warning" type="button" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
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
                            <th>N</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="show-all-phramacyPostOp">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->
</div>
