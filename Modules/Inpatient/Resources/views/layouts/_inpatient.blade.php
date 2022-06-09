<div class="col-sm-4">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title tittle-resp">Provisional Diagnosis</h4>
            </div>
            <div class="allergy-add"> 

                @if(isset($enable_freetext) and $enable_freetext  == '1')
                <a href="javascript:void(0);" class="btn btn-primary btn-sm {{ $disableClass }} " data-toggle="modal" data-target="modal" onclick="inpatientdiagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                @else
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Tooltip on top">
                <a href="#javascript:void(0);" class="btn btn-warning btn-sm {{ $disableClass }} "><i class="ri-add-fill"></i></a>
                </div>
                @endif
                @if(isset($patient) and $patient->fldptsex == 'Female')
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="OBS">
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-success btn-sm" id="pro_obstetric" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="proobstetric.displayModal()"><i class="ri-add-fill"></i></a>
                </div>
                @endif
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="ICD">
                <a href="javascript:void(0);" class="btn btn-warning btn-sm {{ $disableClass }}" data-toggle="modal" data-target="#dliago_group">
                    <i class="ri-add-fill"></i>
                </a>
                </div>&nbsp;
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Delete">
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-danger btn-sm" id="delete__provisional_item"><i class="ri-delete-bin-5-fill"></i></a>
                </div>    
            </div>
        </div>
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group mb-0">
                    <select class="form-control" multiple id="provisional_delete">
                        @if(isset($pat_findings))
                        @foreach($pat_findings as $provisional)
                        @if($provisional->fldtype == 'Provisional Diagnosis')
                        <option value="{{ $provisional->fldid }}">{{ $provisional->fldcode }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title  tittle-resp">Final Diagnosis</h4>
            </div>
            <div class="allergy-add">
                @if(isset($enable_freetext) and $enable_freetext  == '1')
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-primary btn-sm" data-toggle="modal" onclick="finaldiagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                @else
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Free Writing">
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-primary btn-sm"><i class="ri-add-fill"></i></a>
                </div>
                @endif
                @if(isset($patient) and $patient->fldptsex == 'Female')
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="OBS">
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-warning btn-sm" id="final_obstetric" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Final Obstetric" data-target="#finalobstetricdiagnosis" onclick="finalobstetric.displayModal()"><i class="ri-add-fill"></i></a>
                </div>
                @endif
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="ICD">
                <a href="javascript:void(0);" class="btn btn-success btn-sm" id="final_diagnosis" data-toggle="modal" data-target="#final_dliago_group"><i class="ri-add-fill"></i></a>
                </div>
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Delete">
                <a href="javascript:void(0);" class="{{ $disableClass }} btn btn-danger btn-sm" id="delete__final_item"><i class="ri-delete-bin-5-fill"></i></a>
                </div>
            </div>
        </div>
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group mb-0">
                    <select name="" class="form-control" multiple id="final_delete">
                        @if(isset($pat_findings))
                        @foreach($pat_findings as $findings)
                        @if($findings->fldtype == 'Final Diagnosis')
                        <option value="{{ $findings->fldid }}">{{ $findings->fldcode }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title tittle-resp ">Allergy Drugs</h4>
            </div>
            <div class="allergy-add">
                @if(isset($enable_freetext) and $enable_freetext == '1')
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()"> Free </a>
                </div>
                @else
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Free writing">
                <a href="#" class="btn btn-secondary"><i class="ri-add-fill"></i></a>
                </div>
                 @endif
                 <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Drug">
                <a href="#" class="{{ $disableClass }} btn btn-success" data-toggle="modal" data-target="#allergic_modal">Drug</a>
                 </div>
                <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Delete">
                 <a href="#" class="{{ $disableClass }} btn btn-danger" id="delete__allergic_item"><i class="ri-delete-bin-5-fill"></i></a>
                </div>
            </div>
        </div>
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group mb-0">
                    <select name=""  class="form-control" multiple id="select-multiple-aldrug">
                        @if(isset($pat_findings))
                        @foreach($pat_findings as $findings)
                        @if($findings->fldtype == 'Allergic Drugs')
                        <option value="{{ $findings->fldid }}">{{ $findings->fldcode }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="iq-card">
        <div class="iq-card-body">
            <div id="accordion">
                <div class="accordion-nav">
                    <ul>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#patient_statistics" aria-expanded="true"
                            aria-controls="collapseOne">Patient Statistics</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#present" aria-expanded="false"
                            aria-controls="collapseOne">Present</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#onexam" aria-expanded="false" aria-controls="collapseOne">On Exam</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#inout" aria-expanded="false" aria-controls="collapseOne">In/Out</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#labs" aria-expanded="false" aria-controls="collapseOne">Labs</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#prog" aria-expanded="false" aria-controls="collapseOne">Prog</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#notes" aria-expanded="false" aria-controls="collapseOne">Notes</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#fluid" aria-expanded="false"
                            aria-controls="collapseOne">Fluids</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#routine" aria-expanded="false"
                            aria-controls="collapseOne">Routine</a>
                        </li>
                        <li>
                            <a href="#"  data-toggle="collapse" data-target="#stat" aria-expanded="false"
                            aria-controls="collapseOne">Stat/PRN</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#plan" aria-expanded="false" aria-controls="collapseOne">Plan</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#general" aria-expanded="false"
                            aria-controls="collapseOne">General</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#otchecklist" aria-expanded="false"
                            aria-controls="collapseOne">OT Checklist</a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse" data-target="#pre-anaesthesia-evaluation" aria-expanded="false"
                            aria-controls="collapseOne">Pre-Anaesthetic Evaluation</a>
                        </li>
                    </ul>
                </div>
                @include('inpatient::layouts.menus._patient-statistics')
                @include('inpatient::layouts.menus._present')
                @include('inpatient::layouts.menus._on-exam')
                @include('inpatient::layouts.menus._in-out')
                @include('inpatient::layouts.menus._labs')
                @include('inpatient::layouts.menus._prog')
                @include('inpatient::layouts.menus._notes')
                @include('inpatient::layouts.menus._fluids')
                @include('inpatient::layouts.menus._routine')
                @include('inpatient::layouts.menus._stat')
                @include('inpatient::layouts.menus._plan')
                @include('inpatient::layouts.menus._general')
                @include('inpatient::layouts.menus._ot-checklist')
                @include('inpatient::layouts.menus._pre-anaethesia')
            </div>
        </div>
    </div>
</div>

@include('inpatient::layouts.modal.diagnosis-obstetric-modal')
@include('inpatient::layouts.modal.diagnosis-freetext-modal')
@include('inpatient::layouts.modal.final-diagnosis-freetext-modal')
@include('inpatient::layouts.modal.allergic-drugs')
@include('outpatient::modal.allergy-freetext-modal')
@include('outpatient::modal.diagnosis-freetext-modal')
@include('inpatient::layouts.modal.inpatient-image-modal')
@include('inpatient::layouts.dynamic-views.diagnosis')
@include('inpatient::layouts.dynamic-views.final-diagnosis')
