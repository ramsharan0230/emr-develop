<div id="notes" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-6">
                            <input type="date" class="form-control" onchange="onchangeDate(event);">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="" class="form-control after-date-box">
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-sm-in btn-primary" type="button"  id="js-list-notes" datatype="all">
                                <i class="fa fa-list"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dietarytable notes-table">
                        <table class="table table-hovered table-striped notes__table_list"></table>
                    </div>
                </div>
                <div class="col-sm-8">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-3">
                                <label class="border mb-0 col-12"> Category</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="duration_type" id="note_list_select" class="form-control presentType note__field_item">
                                    <option disabled="disabled">Select...</option>
                                    <option value="Progress Note">Progress Note</option>
                                    <option value="Clinicians Note">Clinicians Note</option>
                                    <option value="Nurses Note">Nurses Note</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-sm-in btn-warning {{ $disableClass }}" type="button" data-toggle="modal" data-target="#refere__to" title="Refere Patient">
                                    <i class="fa fa-retweet"></i>
                                </button>
                                <button class="btn btn-sm-in btn-info {{ $disableClass }}" id="insert__notes" url="{{ route('inpatient.insert.note') }}" type="button">
                                    <i class="fa fa-plus"></i>&nbsp;Add
                                </button>
                                <button class="btn btn-sm-in btn-primary {{ $disableClass }}"  id="update__notes" url="{{ route('inpatient.update.note') }}" type="button">
                                    <i class="ri-edit-fill pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="form-group mb-0">
                        <textarea name="notes_field" id="notes_field" class="form-control textarea-notes"></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Impression</label>
                        <input type="text" class="form-control label-notes note__fldreportquali" value="">
                        <input type="hidden" class="notes_fldtime" value="">
                        <input type="hidden" class="note__field_id" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refere__to" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                <h5 class="inpatient__modal_title" style="text-align: center;">Refere To</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="refere__input_caption">Refere Location</h6>
                            <input type="text" class="refere_input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="f-btn f-btn-md f-btn-icon-o adonclose" data-dismiss="modal">Cancel</button>
                <button type="button" id="notes__refer_patient" url="{{ route('inpatient.refere.patient') }}" class="f-btn f-btn-md f-btn-icon-g {{ $disableClass }}" title="Refere Patient">Ok</button>
            </div>
        </div>
    </div>
</div>
