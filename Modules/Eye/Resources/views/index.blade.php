@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        .img-eye img {
            position: absolute;
            width: 290px;
        }

        .canvas__eye {
            border: 2px solid;
            position: absolute;
        }

        #history-modal.modal .modal-dialog {
            width: 100%;
            max-width: none;
            height: 100%;
        }

        #history-modal.modal .modal-content {
            height: 100%;
        }

        #history-modal.modal .modal-body {
            overflow-y: auto;
        }

        #history-modal.modal .iq-card-body{
            max-height: 500px;
            overflow: auto;
        }

    </style>
@endpush
@section('content')
    @php
        $disableClass = (isset($patient_status_disabled) && $patient_status_disabled == 1) ? 'disableInsertUpdate' : '';

        $pgp = ['Spherical', 'Cylindrical', 'Axis'];
        $auto_reaction = ['Spherical', 'Cylindrical', 'Axis'];
        $add = ['Spherical', 'Vision'];
        $acceptance = ['Spherical', 'Cylindrical', 'Axis', 'Vision'];
        $schicmers_test = ['Type I', 'Type II', 'Type III'];
        $k_reading = ['K1', 'K2', 'AXL'];
        $location = ['RE', 'LE'];
    @endphp
    @include('menu::common.eye-nav-bar')

    <div class="container-fluid">
        <div class="row">
            @include('frontend.common.patientProfile')
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div id="accordion">
                            <div class="accordion-nav">
                                <ul>
                                    <li><a href="#" data-toggle="collapse" data-target="#chief-complaint" aria-expanded="true"
                                           aria-controls="collapseOne">Chief Complaints</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#systemic-illness" aria-expanded="false"
                                           aria-controls="collapseOne">Systemic Illness</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#allergy" aria-expanded="false"
                                           aria-controls="collapseOne">Allergy</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#current-medication" aria-expanded="false"
                                           aria-controls="collapseOne">Current Medication</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#history" aria-expanded="false"
                                           aria-controls="collapseOne">History</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#on-examination" aria-expanded="false"
                                           aria-controls="collapseOne">On Examination</a></li>
                                    <li><a href="#" data-toggle="collapse" data-target="#procedure" aria-expanded="false"
                                           aria-controls="collapseOne">Procedure</a></li>
                                </ul>
                            </div>

                            @include('eye::modal.chiefComplaints')
                            <div id="systemic-illness" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Systemic Illness</h4>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <form method="post" class="js-eye-ajax-form" action="{{ route('eye.examgeneral') }}">
                                        <div class="form-group">
                                            <textarea id="js-systematic-illness-ck-textarea" name="Systemic_Illiness" class="ck-eye">{{ isset($exam['otherData']['systemic_illiness']) ? $exam['otherData']['systemic_illiness'] : ''}}</textarea>
                                            <div class="col-md-12 mt-2 mb-3 text-center">
                                                <button class="js-eye-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @include('eye::modal.allergy')
                            <div id="current-medication" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Current Medication</h4>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <form method="post" class="js-eye-ajax-form" action="{{ route('eye.examgeneral') }}">
                                        <div class="form-group">
                                            <textarea id="js-current-medication-ck-textarea" name="Current_Medication" class="ck-eye">{{ isset($exam['otherData']['current_medication']) ? $exam['otherData']['current_medication'] : ''}}</textarea>
                                        </div>
                                        <div class="col-md-12 text-center eyebtn">
                                            <button class="js-eye-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="history" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">History</h4>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <form method="post" class="js-eye-ajax-form form-row" action="{{ route('eye.examgeneral') }}">
                                        <div class="col-sm-6">
                                            <label for="">PAST</label>
                                            <textarea name="History_Past" id="js-history-past" class="td-input">{{ isset($exam['otherData']['history_past']) ? $exam['otherData']['history_past'] : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">FAMILY</label>
                                            <textarea name="History_Family" id="js-history-family" class="td-input">{{ isset($exam['otherData']['history_family']) ? $exam['otherData']['history_family'] : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <button class="js-eye-ajax-save-btn btn btn-primary  mt-3"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="on-examination" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">On Examination</h4>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <form method="post" class="js-eye-ajax-form form-row" action="{{ route('eye.examgeneral') }}">
                                        <div class="col-sm-6">
                                            <label for="">RIGHT</label>
                                            <textarea name="On_Examination_Right" id="js-onexam-right" class="td-input">{{ isset($exam['otherData']['on_examination_right']) ? $exam['otherData']['on_examination_right'] : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">LEFT</label>
                                            <textarea name="On_Examination_Left" id="js-onexam-left" class="td-input">{{ isset($exam['otherData']['on_examination_left']) ? $exam['otherData']['on_examination_left'] : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <button class="js-eye-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="procedure" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Procedure</h4>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <form method="post" class="js-eye-ajax-form" action="{{ route('eye.examgeneral') }}">
                                        <div class="form-group">
                                            <textarea id="js-procedure-ck-textarea" name="Procedure" class="ck-eye">{{ isset($exam['otherData']['procedure']) ? $exam['otherData']['procedure'] : ''}}</textarea>
                                            <div class="col-md-12 mt-2 mb-3 text-center">
                                                <button class="js-eye-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('eye.store') }}">
                @csrf
                @include("eye::common.eye-draw")
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="4">Previous Glass Prescription (PGP)</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($pgp as $p)
                                                        <th>{{ $p }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($pgp as $p)
                                                                <td><input type="text" name="exam[Previous_Glass_Precribtion_(PGP)][{{ $p }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['Previous_Glass_Precribtion_(PGP)'][$p][$loca])) ? $exam['eyeExamData']['Previous_Glass_Precribtion_(PGP)'][$p][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan=3>Add</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($add as $a)
                                                        <th>{{ $a }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($add as $a)
                                                                <td><input type="text" name="exam[Add][{{ $a }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['Add'][$a][$loca])) ? $exam['eyeExamData']['Add'][$a][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="4">Auto Refraction</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($auto_reaction as $ar)
                                                        <th>{{ $ar }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($auto_reaction as $ar)
                                                                <td><input type="text" name="exam[Auto_Reaction][{{ $ar }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['Auto_Reaction'][$ar][$loca])) ? $exam['eyeExamData']['Auto_Reaction'][$ar][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="5">Acceptance</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($acceptance as $ap)
                                                        <th>{{ $ap }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($acceptance as $ap)
                                                                <td><input type="text" name="exam[Acceptance][{{ $ap }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['Acceptance'][$ap][$loca])) ? $exam['eyeExamData']['Acceptance'][$ap][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="7">Intraocular Pressure</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" class="align-middle">Vision</th>
                                                    <th colspan="2">AT</th>
                                                    <th colspan="2">NCT</th>
                                                    <th colspan="2">SA</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>mmHg</th>
                                                    <th>&nbsp;</th>
                                                    <th>mmHg</th>
                                                    <th>&nbsp;</th>
                                                    <th>mmHg</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>RE</td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][AT][RE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['AT']['RE']['readingprefix'])) ? $exam['intracularPressureData']['AT']['RE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][AT][RE][reading]" value="{{ (isset($exam['intracularPressureData']['AT']['RE']['reading'])) ? $exam['intracularPressureData']['AT']['RE']['reading'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][NCT][RE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['NCT']['RE']['readingprefix'])) ? $exam['intracularPressureData']['NCT']['RE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][NCT][RE][reading]" value="{{ (isset($exam['intracularPressureData']['NCT']['RE']['reading'])) ? $exam['intracularPressureData']['NCT']['RE']['reading'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][SA][RE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['SA']['RE']['readingprefix'])) ? $exam['intracularPressureData']['SA']['RE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][SA][RE][reading]" value="{{ (isset($exam['intracularPressureData']['SA']['RE']['reading'])) ? $exam['intracularPressureData']['SA']['RE']['reading'] : '' }}" class="td-input"></td>
                                                </tr>
                                                <tr>
                                                    <td>LE</td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][AT][LE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['AT']['LE']['readingprefix'])) ? $exam['intracularPressureData']['AT']['LE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][AT][LE][reading]" value="{{ (isset($exam['intracularPressureData']['AT']['LE']['reading'])) ? $exam['intracularPressureData']['AT']['LE']['reading'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][NCT][LE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['NCT']['LE']['readingprefix'])) ? $exam['intracularPressureData']['NCT']['LE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][NCT][LE][reading]" value="{{ (isset($exam['intracularPressureData']['NCT']['LE']['reading'])) ? $exam['intracularPressureData']['NCT']['LE']['reading'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][SA][LE][readingprefix]" value="{{ (isset($exam['intracularPressureData']['SA']['LE']['readingprefix'])) ? $exam['intracularPressureData']['SA']['LE']['readingprefix'] : '' }}" class="td-input"></td>
                                                    <td><input type="text" name="exam[Intracular_Pressure][SA][LE][reading]" value="{{ (isset($exam['intracularPressureData']['SA']['LE']['reading'])) ? $exam['intracularPressureData']['SA']['LE']['reading'] : '' }}" class="td-input"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="2">Color Vision</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>RE</td>
                                                    <td><input type="text" name="exam[Color_Vision][No][RE]" value="{{ isset($exam['eyeExamData']['Color_Vision']['No']['RE']) ? $exam['eyeExamData']['Color_Vision']['No']['RE'] : '' }}" class="td-input"></td>
                                                </tr>
                                                <tr>
                                                    <td>LE</td>
                                                    <td><input type="text" name="exam[Color_Vision][No][LE]" value="{{ isset($exam['eyeExamData']['Color_Vision']['No']['LE']) ? $exam['eyeExamData']['Color_Vision']['No']['LE'] : '' }}" class="td-input"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="4">Schirmer’s Test</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($schicmers_test as $st)
                                                        <th>{{ $st }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($schicmers_test as $st)
                                                                <td><input type="text" name="exam[Schicmers_Test][{{ $st }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['Schicmers_Test'][$st][$loca])) ? $exam['eyeExamData']['Schicmers_Test'][$st][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="4">K-Reading</th>
                                                </tr>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    @foreach($k_reading as $kr)
                                                        <th>{{ $kr }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($location) && $location)
                                                    @foreach($location as $loca)
                                                        <tr>
                                                            <td>{{ $loca }}</td>
                                                            @foreach($k_reading as $kr)
                                                                <td><input type="text" name="exam[K-Reading][{{ $kr }}][{{ $loca }}]" value="{{ (isset($exam['eyeExamData']['K-Reading'][$kr][$loca])) ? $exam['eyeExamData']['K-Reading'][$kr][$loca] : '' }}" class="td-input"></td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Note</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        <textarea id="js-note-ck-textarea" name="examgeneral[Note]" class="form-control ck-eye">{{ isset($exam['otherData']['note']) ? $exam['otherData']['note'] : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Advice</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        <textarea id="js-advice-ck-textarea" name="examgeneral[Advice]" class="form-control ck-eye">{{ isset($exam['otherData']['advice']) ? $exam['otherData']['advice'] : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex justify-content-around">
                                <button type="button" onclick="laboratory.displayModal()" class="btn btn-primary">Laboratory</button>
                                <button type="button" onclick="radiology.displayModal()" class="btn btn-primary">Radiology</button>
                                <button type="button" onclick="pharmacy.displayModal()" class="btn btn-primary">Pharmacy</button>
                                @if(isset($enpatient) && $enpatient->fldpatientval)
                                    <a href="javascript:;" class="btn btn-primary btn-action {{ $disableClass }}" onclick="showHistoryPopup('{{ $enpatient->fldencounterval }}')">History</a>
                                @endif
                                {{-- <a href="{{ route('eye.histry.pdf', $patient->fldpatientval ?? 0) }}?eye" target="_blank">
                                    <button type="button" class="btn btn-primary">History</button>
                                </a> --}}
                                <a href="{{ Session::has('eye_encounter_id')?route('eye.opd.sheet.pdf', Session::get('eye_encounter_id')??0 ): '' }}?eye" type="button" class="btn-custom-opd" target="_blank">
                                    <button type="button" class="btn btn-primary">OPD Sheet</button>
                                </a>
                                <button class="btn btn-primary">Save</button>
                                <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish">
                                    <button class="btn btn-primary">Finish</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('outpatient::modal.history')
    @include('eye::diagnosisstoremodal')
    @include('outpatient::modal.diagnosis-freetext-modal')
    @include('inpatient::layouts.modal.patient-image')
    @include('inpatient::layouts.modal.triage')
    @include('outpatient::modal.diagnosis-obstetric-modal')
    @include('inpatient::layouts.modal.demographics', ['module' => 'eye'])

    <script src="{{ asset('js/eye_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $(".flditem").select2();
                $(".find_fldhead").select2();
            }, 1500);

            $(document).on("keydown", ".select2-search__field", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    //alert('You pressed a "enter" key in textbox');
                    $('.flditem').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                }
            });
        });
        $(window).ready(function () {
            init();
            initRight();
        })
        var canvas, ctx, flag = false,
            prevX = 0,
            currX = 0,
            prevY = 0,
            currY = 0,
            dot_flag = false;

        var x = "red",
            y = 2;

        function init() {
            canvas = document.getElementById('canvas-draw');
            ctx = canvas.getContext("2d");
            w = canvas.width;
            h = canvas.height;

            canvas.addEventListener("mousemove", function (e) {
                findxy('move', e)
            }, false);
            canvas.addEventListener("mousedown", function (e) {
                findxy('down', e)
            }, false);
            canvas.addEventListener("mouseup", function (e) {
                findxy('up', e)
            }, false);
            canvas.addEventListener("mouseout", function (e) {
                findxy('out', e)
            }, false);
        }

        function color(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    x = "green";
                    break;
                case "blue":
                    x = "blue";
                    break;
                case "red":
                    x = "red";
                    break;
                case "yellow":
                    x = "yellow";
                    break;
                case "orange":
                    x = "orange";
                    break;
                case "black":
                    x = "black";
                    break;
                case "white":
                    x = "white";
                    break;
            }
            if (x == "white") y = 14;
            else y = 2;

        }

        function draw() {
            ctx.beginPath();
            ctx.moveTo(prevX, prevY);
            ctx.lineTo(currX, currY);
            ctx.strokeStyle = x;
            ctx.lineWidth = y;
            ctx.stroke();
            ctx.closePath();
        }

        function erase() {
            var m = confirm("Want to clear");
            if (m) {
                ctx.clearRect(0, 0, w, h);
                document.getElementById("canvasimg").style.display = "none";
            }
        }

        function save() {
            /*document.getElementById("canvasimg").style.border = "2px solid";
            var dataURL = canvas.toDataURL();
            document.getElementById("canvasimg").src = dataURL;
            document.getElementById("canvasimg").style.display = "inline";*/
            var dataURL = canvas.toDataURL();
            $('.left-image').val(dataURL);
            // console.log(dataURL);
        }

        function findxy(res, e) {
            if (res == 'down') {
                prevX = currX;
                prevY = currY;
                currX = e.clientX - canvas.getBoundingClientRect().left;
                currY = e.clientY - canvas.getBoundingClientRect().top;

                flag = true;
                dot_flag = true;
                if (dot_flag) {
                    ctx.beginPath();
                    ctx.fillStyle = x;
                    ctx.fillRect(currX, currY, 2, 2);
                    ctx.closePath();
                    dot_flag = false;
                }
            }
            if (res == 'up' || res == "out") {
                flag = false;
            }
            if (res == 'move') {
                if (flag) {
                    prevX = currX;
                    prevY = currY;
                    currX = e.clientX - canvas.getBoundingClientRect().left;
                    currY = e.clientY - canvas.getBoundingClientRect().top;
                    draw();
                }
            }
        }

        var canvasRight, ctxRight, flagRight = false,
            prevXRight = 0,
            currXRight = 0,
            prevYRight = 0,
            currYRight = 0,
            dot_flagRight = false;

        var xRight = "red",
            yRight = 2;

        function initRight() {
            canvasRight = document.getElementById('canvasRight-draw');
            ctxRight = canvasRight.getContext("2d");
            w = canvasRight.width;
            h = canvasRight.height;

            canvasRight.addEventListener("mousemove", function (e) {
                findxyRight('move', e)
            }, false);
            canvasRight.addEventListener("mousedown", function (e) {
                findxyRight('down', e)
            }, false);
            canvasRight.addEventListener("mouseup", function (e) {
                findxyRight('up', e)
            }, false);
            canvasRight.addEventListener("mouseout", function (e) {
                findxyRight('out', e)
            }, false);
        }

        function colorRight(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    xRight = "green";
                    break;
                case "blue":
                    xRight = "blue";
                    break;
                case "red":
                    xRight = "red";
                    break;
                case "yellow":
                    xRight = "yellow";
                    break;
                case "orange":
                    xRight = "orange";
                    break;
                case "black":
                    xRight = "black";
                    break;
                case "white":
                    xRight = "white";
                    break;
            }
            if (xRight == "white") yRight = 14;
            else yRight = 2;

        }

        function drawRight() {
            ctxRight.beginPath();
            ctxRight.moveTo(prevXRight, prevYRight);
            ctxRight.lineTo(currXRight, currYRight);
            ctxRight.strokeStyle = xRight;
            ctxRight.lineWidth = yRight;
            ctxRight.stroke();
            ctxRight.closePath();
        }

        function eraseRight() {
            var m = confirm("Want to clear");
            if (m) {
                ctxRight.clearRect(0, 0, w, h);
            }
        }

        function saveRight() {
            /*document.getElementById("canvasRightimg").style.border = "2px solid";
            var dataURL = canvasRight.toDataURL();
            document.getElementById("canvasRightimg").src = dataURL;
            document.getElementById("canvasRightimg").style.display = "inline";*/
            var dataURL = canvasRight.toDataURL();
            $('.right-image').val(dataURL);
            // console.log(dataURL);
        }

        function findxyRight(res, e) {
            if (res == 'down') {
                prevXRight = currXRight;
                prevYRight = currYRight;
                currXRight = e.clientX - canvasRight.getBoundingClientRect().left;
                currYRight = e.clientY - canvasRight.getBoundingClientRect().top;

                flagRight = true;
                dot_flagRight = true;
                if (dot_flagRight) {
                    ctxRight.beginPath();
                    ctxRight.fillStyle = x;
                    ctxRight.fillRect(currXRight, currYRight, 2, 2);
                    ctxRight.closePath();
                    dot_flagRight = false;
                }
            }
            if (res == 'up' || res == "out") {
                flagRight = false;
            }
            if (res == 'move') {
                if (flagRight) {
                    prevXRight = currXRight;
                    prevYRight = currYRight;
                    currXRight = e.clientX - canvasRight.getBoundingClientRect().left;
                    currYRight = e.clientY - canvasRight.getBoundingClientRect().top;
                    drawRight();
                }
            }
        }

        function showHistoryPopup($encounter) {
            if ($encounter == "") {
                showAlert('No patient selected.', 'error');
                return false;
            }
            let route = "{!! route('history.by.patient', ['encounter' => ':ENCOUNTER_ID']) !!}";
            route = route.replace(':ENCOUNTER_ID', $encounter);
            $.ajax({
                url: route,
                type: "POST",
                success: function (response) {
                    // console.log(response);
                    $('.history-modal-content').empty();
                    $('.history-modal-content').html(response);
                    $('#history-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    </script>
@stop
