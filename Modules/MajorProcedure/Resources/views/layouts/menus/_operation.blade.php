<div class="tab-pane fade" id="operation" role="tabpanel" aria-labelledby="operation-tab">
 <!-- Collapse buttons -->
 <div class="form-group form-row" style="padding: 0px 0px 0px 5px;">
    <div class="box__collipsble1 pr-4">
        <label class=""> Examination</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#operationexam" aria-expanded="false" aria-controls="operationexam"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class=""> Clinical</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#operationclinic" aria-expanded="false" aria-controls="operationclinic"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class=""> Pharmacy</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#operationphar" aria-expanded="false" aria-controls="operationphar"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class="">Personnel</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#operationperson" aria-expanded="false" aria-controls="operationperson"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
    <div class="box__collipsble1 pr-4">
        <label class="">Other Item</label>
        <a class="btn-dental-form" data-toggle="collapse" href="#otheritem" aria-expanded="false" aria-controls="otheritem"><button type="button" class="btn btn-sm-f btn-primary ml-2 btn-sm mb-3"><i class="fa fa-plus pr-0"></i></button></a>
    </div>
</div>
<hr>
<!-- / Collapse buttons -->
<!-- Collapsible element -->
<div class="collapse" id="operationexam" data-parent="#operation">
    <div class="row">
        <div class="col-sm-6">
            <div class="res-table">
                <table class="table table-bordered table-hovered table-striped">
                    <tbody class="major-operation-examination-table"></tbody>
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
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Abnormal behavior
                            </td>
                            <td>3.67 Days</td>
                            <td>Left Side</td>
                            <td>2020-04-06</td>
                            <td>bajrabajra</td>
                            <td>Kathmandu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Collapsible element -->
<div class="collapse" id="operationclinic" data-parent="#operation">
    <div class="row">
        <div class="col-sm-12">
            <div class="er-input mt-2">
                <label>Indication</label>
                <div class="col-6">
                    <input type="text" id="clinical-indication-operation" name="" class="form-control"/>
                </div>
                <div class="col-1">
                    <a href="javascript:;" id="save-clinical-indication-operation" url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                </div>
                <div class="col-5">
                    <button type="button" id="save-clinical-note-operation" url="{{ route('insert.clinicalNote.textarea') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i>&nbsp;&nbsp;Save Note</button>
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
                    <tbody class="show-clinical-indication-operation"></tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group mt-2">
                <input type="hidden" id="current_id_o" value="">
                <input type="hidden" id="chapter_o" value="">
                <textarea name="clinical_note_operation_textarea" id="clinical_note_operation_textarea" class="form-control textarea-major"></textarea>
            </div>
        </div>
    </div>
</div>
<!-- Collapsible element -->
<div class="collapse" id="operationphar" data-parent="#operation">
   <div class="form-group float-right mt-2">
        <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
        <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
        <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyOperation"><i class="fa fa-list"></i>&nbsp;Show All</button>
        <a class="btn btn-sm-in btn-warning" href="{{ Session::has('major_procedure_encounter_id')?route('phramacy.pdfReport', Session::get('major_procedure_encounter_id')??0 ): '' }}" target="_blank"><i class="fa fa-code"></i>&nbsp;Export</a>
    </div>
    <div class="res-table">
        <table class="table table-hovered table-bordered table-striped">
            <thead class='thead-light'>
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
            <tbody class="show-all-phramacyOperation">

            </tbody>
        </table>
    </div>
</div>
<!-- Collapsible element -->
<div class="collapse" id="operationperson" data-parent="#operation">
    <div class="form-group-second form-row">
        <div class="col-sm-3 er-input">
            <select class="form-control" id="personnel-category">
                <option value="Anaesthetist">Anaesthetist</option>
                <option value="Assistant">Assistant</option>
                <option value="Count Nurse">Count Nurse</option>
                <option value="Scrub Nurse">Scrub Nurse</option>
                <option value="Surgeon">Surgeon</option>
            </select>
        </div>
        <div class="col-sm-3 er-input">
            <select class="form-control" id="personnel-username">
                @if(!empty($consultants))
                @foreach($consultants as $consultant)
                <option
                value="{{$consultant->fldusername}}">{{$consultant->fldusername}}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="col-5">
            <input type="text" class="form-control" id="personnel-description">
        </div>
        <div class="col-1">
            <a href="javascript:;" id="insert-personnel" url="{{ route('insert.personnel') }}" class="btn btn-sm-in btn-primary" type="button">
                <i class="fa fa-user"></i>
            </a>
        </div>
    </div>
    <div class="res-table">
        <table class="table table-hovered table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th class="tittle-th" scope="col">DateTime
                    </th>
                    <th class="tittle-th" scope="col">Category
                    </th>
                    <th class="tittle-th" scope="col">User Name
                    </th>
                    <th class="tittle-th" scope="col">Description
                    </th>
                </tr>
            </thead>
            <tbody class="show-personnel-table">

            </tbody>
        </table>
    </div>
</div>
<!-- Collapsible element -->
<div class="collapse" id="otheritem" data-parent="#operation">
   <div class="row">
    <div class="col-sm-12">
        <div class="er-input mt-2">
            <div class="col-3 padding-none">
                <select class="form-control" id="other-items-select" class="form-control">
                    @if(!empty($other_items))
                    <option value="">---Select---</option>
                    @foreach($other_items as $other_item)
                    <option value="{{ $other_item->fldid }}"
                        rel="{{ $other_item->flditemname }}">{{ $other_item->flditemname }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-2 er-input">
                    <label>Rate:</label> &nbsp;
                    <input type="text" name="" class="form-control" id="other-items-rate" value="0" disabled="disabled"/>
                </div>
                <div class="col-3 er-input">
                    <label>Dis/Tax:</label> &nbsp;
                    <input type="text" name="" class="form-control" id="other-items-dis" value="0" disabled="disabled"/>&nbsp;
                    <input type="text" name="" class="form-control" id="other-items-tax" value="0" disabled="disabled"/>
                </div>
                <div class="col-2 er-input">
                    <label>QTY:</label> &nbsp;
                    <input type="text" name="" class="form-control" id="other-items-qty" value="0"/>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-primary btn-sm mb-3" id="insert-other-items"
                    url="{{ route('insert.otherItems') }}"><i class="fa fa-check pr-0"></i>&nbsp;Save
                </button>
                <input type="hidden" class="flditemno" value="">
                <input type="hidden" class="flddiscper" value="">
                <input type="hidden" class="fldorduserid" value="">
                <input type="hidden" class="fldorduserid" value="">
                <input type="hidden" class="fldordcomp" value="">
                <input type="hidden" class="fldordtime" value="">
                <input type="hidden" class="fldalert" value="">
                <input type="hidden" class="fldtarget" value="">
                <input type="hidden" class="fldtaxamt" value="">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="newitem-tab" data-toggle="tab" href="#newitem" role="tab" aria-controls="newitem" aria-selected="true">New Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="saveditem-tab" data-toggle="tab" href="#saveditem" role="tab" aria-controls="saveditem" aria-selected="false">Saved Item</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent-2">
            <div class="tab-pane fade show active" id="newitem" role="tabpanel" aria-labelledby="newitem-tab">
                <div class="res-table">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>&nbsp;</th>
                                <th>S/N</th>
                                <th>DateTime</th>
                                <th>Catogery</th>
                                <th>Code</th>
                                <th>Particulars</th>
                                <th>Rate</th>
                                <th>QTY</th>
                                <th>Tax%</th>
                                <th>Disc</th>
                            </tr>
                        </thead>
                        <tbody class="show-other-items-table">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="saveditem" role="tabpanel" aria-labelledby="saveditem-tab">
                <div class="major-table table-responsive">
                    <div class="res-table">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light"></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- <div id="accordion">
    <div class="card">
        <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseOne">
                Examination
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dietarytable">
                            <table class="table table-hovered table-striped">
                                <tbody class="major-operation-examination-table"></tbody>
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
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Abnormal behavior
                                        </td>
                                        <td>3.67 Days</td>
                                        <td>Left Side</td>
                                        <td>2020-04-06</td>
                                        <td>bajrabajra</td>
                                        <td>Kathmandu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
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
                                <input type="text" id="clinical-indication-operation" name="" class="form-control"/>
                            </div>
                            <div class="col-1">
                                <a href="javascript:;" id="save-clinical-indication-operation" url="{{ route('insert.clinicalIndication.clinicalNote') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i></a>
                            </div>
                            <div class="col-2">
                                <button type="button" id="save-clinical-note-operation" url="{{ route('insert.clinicalNote.textarea') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-check pr-0"></i>&nbsp;&nbsp;Save Note</button>
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
                                <tbody class="show-clinical-indication-operation"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mt-2">
                            <input type="hidden" id="current_id_o" value="">
                            <input type="hidden" id="chapter_o" value="">
                            <textarea name="clinical_note_operation_textarea" id="clinical_note_operation_textarea" class="form-control textarea-major"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
                Pharmacy
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseThree" class="collapse" data-parent="#accordion">
            <div class="form-group float-right mt-2">
                <button class="btn btn-sm-in btn-primary" type="button" onclick="pharmacy.displayModal()"><i class="fa fa-plus"></i>&nbsp;Request</button>
                <button class="btn btn-sm-in btn-info" type="button" onclick="dosingRecord.displayModal()"><i class="fa fa-edit"></i>&nbsp;Dosing</button>
                <button class="btn btn-sm-in btn-primary" type="button" id="getAllPhramacyOperation"><i class="fa fa-list"></i>&nbsp;Show All</button>
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
                            <th>N</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="show-all-phramacyOperation">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapsePersonnel">
                Personnel
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapsePersonnel" class="collapse" data-parent="#accordion">
            <div class="form-group-second form-row">
                <div class="col-sm-3 er-input">
                    <select class="form-control" id="personnel-category">
                        <option value="Anaesthetist">Anaesthetist</option>
                        <option value="Assistant">Assistant</option>
                        <option value="Count Nurse">Count Nurse</option>
                        <option value="Scrub Nurse">Scrub Nurse</option>
                        <option value="Surgeon">Surgeon</option>
                    </select>
                </div>
                <div class="col-sm-3 er-input">
                    <select class="form-control" id="personnel-username">
                        @if(!empty($consultants))
                        @foreach($consultants as $consultant)
                        <option
                        value="{{$consultant->fldusername}}">{{$consultant->fldusername}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-5">
                    <input type="text" class="form-control" id="personnel-description">
                </div>
                <div class="col-1">
                    <a href="javascript:;" id="insert-personnel" url="{{ route('insert.personnel') }}" class="btn btn-sm-in btn-primary" type="button">
                        <i class="fa fa-user"></i>
                    </a>
                </div>
            </div>
            <div class="major-table table-responsive">
                <table class="table table-hovered table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="tittle-th" scope="col">DateTime
                            </th>
                            <th class="tittle-th" scope="col">Category
                            </th>
                            <th class="tittle-th" scope="col">User Name
                            </th>
                            <th class="tittle-th" scope="col">Description
                            </th>
                        </tr>
                    </thead>
                    <tbody class="show-personnel-table">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseFive">
                Other Item
                <button type="button" class="btn btn-sm-f btn-sm-f btn-primary float-right btn-sm mb-3"><i class="fa fa-chevron-down pr-0"></i></button>
            </a>
        </div>
        <div id="collapseFive" class="collapse" data-parent="#accordion">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="er-input mt-2">
                            <div class="col-3 padding-none">
                                <select class="form-control" id="other-items-select" class="form-control">
                                    @if(!empty($other_items))
                                    <option value="">---Select---</option>
                                    @foreach($other_items as $other_item)
                                    <option value="{{ $other_item->fldid }}"
                                        rel="{{ $other_item->flditemname }}">{{ $other_item->flditemname }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-2 er-input">
                                    <label>Rate:</label> &nbsp;
                                    <input type="text" name="" class="form-control" id="other-items-rate" value="0" disabled="disabled"/>
                                </div>
                                <div class="col-3 er-input">
                                    <label>Dis/Tax:</label> &nbsp;
                                    <input type="text" name="" class="form-control" id="other-items-dis" value="0" disabled="disabled"/>&nbsp;
                                    <input type="text" name="" class="form-control" id="other-items-tax" value="0" disabled="disabled"/>
                                </div>
                                <div class="col-2 er-input">
                                    <label>QTY:</label> &nbsp;
                                    <input type="text" name="" class="form-control" id="other-items-qty" value="0"/>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary btn-sm mb-3" id="insert-other-items"
                                    url="{{ route('insert.otherItems') }}"><i class="fa fa-check pr-0"></i>&nbsp;Save
                                </button>
                                <input type="hidden" class="flditemno" value="">
                                <input type="hidden" class="flddiscper" value="">
                                <input type="hidden" class="fldorduserid" value="">
                                <input type="hidden" class="fldorduserid" value="">
                                <input type="hidden" class="fldordcomp" value="">
                                <input type="hidden" class="fldordtime" value="">
                                <input type="hidden" class="fldalert" value="">
                                <input type="hidden" class="fldtarget" value="">
                                <input type="hidden" class="fldtaxamt" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="newitem-tab" data-toggle="tab" href="#newitem" role="tab" aria-controls="newitem" aria-selected="true">New Item</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="saveditem-tab" data-toggle="tab" href="#saveditem" role="tab" aria-controls="saveditem" aria-selected="false">Saved Item</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade show active" id="newitem" role="tabpanel" aria-labelledby="newitem-tab">
                                <div class="major-table table-responsive">
                                    <table class="table table-hovered table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>S/N</th>
                                                <th>DateTime</th>
                                                <th>Catogery</th>
                                                <th>Code</th>
                                                <th>Particulars</th>
                                                <th>Rate</th>
                                                <th>QTY</th>
                                                <th>Tax%</th>
                                                <th>Disc</th>
                                            </tr>
                                        </thead>
                                        <tbody class="show-other-items-table">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="saveditem" role="tabpanel" aria-labelledby="saveditem-tab">
                                <div class="major-table table-responsive">
                                    <div class="saveitem-table">
                                        <table class="table table-hovered table-bordered table-striped">
                                            <thead></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
</div>
