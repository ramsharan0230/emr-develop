@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
       <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Reporting
                    </h4>
                </div>
                <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="form-group form-row align-items-center form-row">
                    <form id="js-reporting-search-form">
                        <div class="form-group form-row align-items-center form-row">
                            <div class="col-sm-4"><label>Name</label></div>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="js-reporting-search-name-input" class="form-control js-lab-module-name-search-input">
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center form-row">
                            <div class="col-sm-4"><label>Encounter</label></div>
                            <div class="col-sm-8">
                                <input type="text" name="encounterId" id="js-reporting-search-encounter-input" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center form-row">
                            <div class="col-sm-4"><label>Category</label></div>
                            <div class="col-sm-6">
                                <select name="category" id="js-reporting-category-select" class="form-control">
                                    <option value="">%</option>
                                    @foreach ($categories as $category)
                                    <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button id="js-reporting-pdf-btn" onclick="quantityObservation.generateFullPdf()" type="button" class="btn btn-primary"><i class="fa fa-code"></i></button>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center pl-2">
                            <div class="col-sm-5">
                                <input type="text" name="fromdate" value="{{ request()->get('fromdate') ?: $date }}" class="form-control nepaliDatePicker" id="js-fromdate-input-nepaliDatePicker">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="todate" value="{{ request()->get('todate') ?: $date }}" class="form-control nepaliDatePicker" id="js-todate-input-nepaliDatePicker">
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" id="js-reporting-refresh-btn"><i class="fa fa-sync"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="res-table reporting-table table-sticky-th border">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">EncID</th>
                                <th class="tittle-th" style="display: none;">Sample ID</th>
                                <th class="tittle-th">Name</th>
                                <th>Department</th>
                                <th>Userid</th>
                                <th>Datetime</th>
                            </tr>
                        </thead>
                        <tbody id="js-reporting-name-tbody" class="js-lab-module-name-search-tbody">
                            @foreach($labTestPatients as $labTestPatient)
                            @if($labTestPatient->fldencounterval)
                            <tr data-encounterid="{{ $labTestPatient->fldencounterval }}">
                                <td>{{ $labTestPatient->fldencounterval }}</td>
                                <td style="display: none;">{{ $labTestPatient->fldsampleid }}</td>
                                @if($labTestPatient->patientEncounter && $labTestPatient->patientEncounter->patientInfo)
                                <td class="js-patient-name">{{ $labTestPatient->patientEncounter->patientInfo->fldrankfullname }}</td>
                                @else
                                <td class="js-patient-name"></td>
                                @endif
                                <td>{{ ($labTestPatient->patientEncounter && $labTestPatient->patientEncounter->consultant) ? $labTestPatient->patientEncounter->consultant->fldconsultname : '' }}</td>
                                <td>{{ $labTestPatient->flduserid_sample }}</td>
                                <td>{{ $labTestPatient->fldtime_sample }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-12 rightdiv">
        <div class="iq-card iq-card-block">
            <div class="iq-card-body">
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="form-group er-input">
                            <div class="custom-control custom-radio custom-control-inline js-reporting-encsamp-radio-div">
                                <input type="radio" class="custom-control-input" id="js-reporting-encounter-radio" value="Encounter" name="type" checked="checked">
                                <label class="custom-control-label" for="js-reporting-encounter-radio"> Encounter </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline js-reporting-encsamp-radio-div">
                                <input type="radio" class="custom-control-input" id="js-reporting-sample-radio" value="Sample" name="type">
                                <label class="custom-control-label" for="js-reporting-sample-radio"> Sample </label>
                                <input type="hidden" name="encounter_id" id="js-reporting-encounter-id">
                            </div>
                            <div class="custom-control padding-none">
                                <input type="text" class="form-control col-11" id="js-reporting-encounter-input">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-4">Full Name</label>
                            <div class="col-sm-8">
                                <input type="text" id="js-reporting-fullname-input" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-4">Address</label>
                            <div class="col-sm-8">
                                <input type="text" id="js-reporting-address-input" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-row">
                            <label class="col-3 padding-none">Age/Sex</label>
                            <div class="col-sm-3">
                                <input type="text" id="js-reporting-agesex-input" class="form-control">
                            </div>
                            <div class="col-sm-6 padding-none">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="js-reporting-other-location-checkbox">
                                    <label class="custom-control-label">Other Location</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label class="col-3 padding-none">Location</label>
                            <div class="col-sm-4">
                                <input type="text" id="js-reporting-location-input" class="form-control">
                            </div>
                            <div class="col-sm-5 padding-none">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="js-reporting-show-all-checkbox" checked>
                                    <label class="custom-control-label">Show All</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group form-row">
                            <div class="custom-control custom-radio custom-control-inline js-reporting-encsamp-radio-div">
                                <input type="radio" class="custom-control-input" id="js-reporting-si-radio" value="SI" name="fldtestunit" checked="checked">
                                <label class="custom-control-label" for="js-reporting-si-radio"> SI </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline js-reporting-encsamp-radio-div">
                                <input type="radio" class="custom-control-input" id="js-reporting-metric-radio" value="Metric" name="fldtestunit">
                                <label class="custom-control-label" for="js-reporting-metric-radio"> Metric </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group form-row float-right">
                            <button id="js-reporting-show-btn" class="btn btn-primary" type="button"><i class="fas fa-play"></i>&nbsp;Show</button>&nbsp;
                            <button onclick="quantityObservation.printPage()" class="btn btn-primary" type="button"><i class="fas fa-code"></i>&nbsp;Report</button>&nbsp;
                            <button onclick="quantityObservation.printReport()" class="btn btn-primary" type="button"><i class="fas fa-code"></i>&nbsp;Preview</button>&nbsp;
                            <button onclick="quantityObservation.printPageHistory()" class="btn btn-primary" type="button"><i class="fas fa-code"></i>&nbsp;History</button>&nbsp;
                            <button class="btn btn-primary" id="js-reporting-verification-btn"><i class="fas fa-play"></i>&nbsp;Verification</button>&nbsp;
                            <button class="btn btn-primary" id="js-reporting-printing-btn"><i class="fas fa-print"></i>&nbsp;Printing</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="iq-card iq-card-block">
            <div class="iq-card-body">
                <div class="res-table table-sticky-th border" style="height: 435px;">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">SN</th>
                                <th class="tittle-th">SamID</th>
                                <th class="tittle-th">Test Name</th>
                                <th class="tittle-th">Flag</th>
                                <th class="tittle-th">Observation</th>
                                <th class="tittle-th">Unit</th>
                                <th class="tittle-th">Reference range</th>
                                <th class="tittle-th">Specimen</th>
                                {{-- <th class="tittle-th">Method</th> --}}
                                <th class="tittle-th">Sample Time</th>
                                <th class="tittle-th">Report Time</th>
                                <th class="tittle-th">Action</th>
                            </tr>
                        </thead>
                        <tbody id="js-reporting-samples-tbody"></tbody>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-3"><button class="btn btn-primary" id="js-reporting-component-btn">Component</button></div>
                    <div class="col-sm-9">
                        <div class="form-group er-input">
                            <label class="col-3">Diagnosis:</label>
                            <select multiple id="js-lab-common-diagnosis-ul" class="form-control"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<form method="POST" target="_blank" id="js-reporting-popup-form">
    @csrf
    <input type="hidden" name="type" id="js-reporting-type-hidden-form-input">
    <input type="hidden" name="encounter_sample" id="js-reporting-encountersample-hidden-form-input">
</form>

<div class="modal fade" id="js-reporting-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Abnormal Status</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <input type="hidden" id="js-reporting-fldid-input">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="js-reporting-status-select">
                            <option value="0">Normal</option>
                            <option value="1">Abnormal</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-5">
                        <button type="button" id="js-reporting-status-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="js-reporting-culture-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Select Component</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row" style="max-height: 300px;overflow-y: scroll;">
                    <div class="res-table reporting-table table-sticky-th">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="text" id="js-reporting-culture-modal-search-input" class="form-control">&nbsp;
                                        <button class="btn btn-success btn-sm" id="js-reporting-culture-modal-save-modal">Save</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="js-reporting-culture-modal-component-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('laboratory::modal.modal')
@include('laboratory::modal.patient-image')
@include('laboratory::modal.specimen')
@include('laboratory::modal.comment')

<div class="modal fade" id="js-reporting-culture-subtable-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Drug Sensitivity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
                <input type="text" id="js-reporting-culture-subtable-serach-input-modal" class="form-control col-3 mb-2 float-right" placeholder="search......">
                <input type="hidden" id="js-reporting-culture-subtable-modal-fldsubtestid-input">
                <br><br>
                <table>
                    <tbody id="js-reporting-culture-subtable-modal-tbody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="js-reporting-culture-subtable-modal-save-btn">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
<script>
    $('#js-reporting-encounter-input').on('keypress', function (e) {
        if (e.which === 13)
            $('#js-reporting-show-btn').click();
    });

    $(document).ready(function() {
        var encounterIdRequest = "{{ request('encounterId') }}";
        if (encounterIdRequest != '') {
            $('#js-reporting-encounter-input').val(encounterIdRequest);
            $('#js-reporting-show-btn').trigger('click');
        }
    });

    $('.js-answer-textarea').each(function(e){
        CKEDITOR.replace( this.id, { height: '100%;' });
    });

    $(document).on('click', '#js-reporting-samples-tbody tr td:nth-child(8)', function() {
        specimen.displayModal($(this));
    });
</script>
@endpush

