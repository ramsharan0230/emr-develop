@extends('frontend.layouts.master')

@section('content')

    @php
        $report_segment = Request::segment(3)
    @endphp
    <div class="container-fluid">
        @include('menu::toggleButton')
        <form method="POST" id="js-printing-hform">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Testing {{ ucwords($report_segment) }}
                                </h4>
                            </div>
                            <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4 leftdiv">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body" id="js-print-search-div">
                                    <div class="form-group form-row align-items-center form-row">
                                        <div class="col-sm-4"><label>Name</label></div>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" id="js-printing-search-name-input" class="form-control js-lab-module-name-search-input">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center form-row">
                                        <div class="col-sm-4"><label>Encounter</label></div>
                                        <div class="col-sm-8">
                                            <input type="text" name="encounterId" id="js-printing-search-encounter-input" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center form-row">
                                        <div class="col-sm-4"><label>Category</label></div>
                                        <div class="col-sm-6">
                                            <select name="category" id="js-printing-category-search-select" class="form-control">
                                                <option value="">%</option>
                                                @foreach ($categories as $category)
                                                    <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button id="js-printing-test-report" type="button" class="btn btn-primary"><i class="fa fa-code"></i></button>
                                        </div>
                                    </div>
                                    @if($report_segment == 'printing')
                                        <div class="form-group form-row align-items-center form-row">
                                            <div class="col-sm-12">
                                                <div class="custom-control custom-radio custom-control-inline js-printing-status-radio">
                                                    <input type="radio" {{ (request('status') == 'reported') ? 'checked=checked' : '' }} name="status" id="reported" value="reported" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="reported"> Pending to verify </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" {{ (request('status') == NULL || request('status') == 'verified') ? 'checked="checked' : '' }} name="status" id="verified" value="verified" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="verified"> Verified </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group form-row align-items-center form-row">
                                            <div class="col-sm-12">
                                                <div class="custom-control custom-radio custom-control-inline js-printing-status-radio">
                                                    <input type="radio" {{ (request('status') == 'reported') ? 'checked="checked' : '' }} name="status" id="reported" value="reported" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="reported"> Pending to verify </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline js-printing-status-radio">
                                                    <input type="radio" {{ (request('status') == 'verified') ? 'checked="checked' : '' }} name="status" id="verified" value="verified" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="verified"> Verified </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group form-row align-items-center pl-2">
                                        <div class="col-sm-5">
                                            <input type="text" name="fromdate" value="{{ request()->get('fromdate') ?: $date }}" class="form-control nepaliDatePicker" id="js-fromdate-input-nepaliDatePicker">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="todate" value="{{ request()->get('todate') ?: $date }}" class="form-control nepaliDatePicker" id="js-todate-input-nepaliDatePicker">
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary" id="js-printing-search-submit-btn"><i class="fa fa-sync"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="table-responsive table-scroll-sampling table-sticky-th" style="height: 580px; min-height: 580px;">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                            <tr>
                                                <th>EncID</th>
                                                <th class="tittle-th" style="display: none;">Sample ID</th>
                                                <th>Name</th>
                                                <th>Department</th>
                                                <th>Userid</th>
                                                <th>Datetime</th>
                                            </tr>
                                            </thead>
                                            <tbody id="js-printing-patient-tbody" class="js-lab-module-name-search-tbody">
                                            @if(isset($patients))
                                                @foreach($patients as $pat)
                                                    <tr data-encounterid="{{ $pat->fldencounterval }}" data-sampleid="{{ $pat->fldsampleid }}">
                                                        <td>{{ $pat->fldencounterval }}</td>
                                                        <td style="display: none;">{{ $pat->fldsampleid }}</td>
                                                        <td class="js-patient-name">{{ ($pat->patientEncounter && $pat->patientEncounter->patientInfo) ? $pat->patientEncounter->patientInfo->fldrankfullname : '' }}</td>
                                                        <td>{{ ($pat->patientEncounter && $pat->patientEncounter->consultant) ? $pat->patientEncounter->consultant->fldconsultname : '' }}</td>
                                                        <td>{{ $pat->flduserid_report }}</td>
                                                        <td>{{ $pat->fldtime_report }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(FALSE)
                                        <div class="table-responsive table-scroll-sampling mt-2 table-sticky-th">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <select multiple style="width: 100%;height: 255px;" class="form-control" id="js-sampling-test-tbody"></select>
                                        </div>
                                        <div class="mt-2">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-3">Payable:</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" id="js-sampling-userid-select">
                                                        <option value="">-- Select user --</option>
                                                        @foreach($all_users as $user)
                                                            <option value="{{ $user->flduserid }}">{{ $user->fldusername }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <button class="btn btn-primary btn-sm-in" id="js-sampling-add-btn"><i class="ri-add-fill"></i>&nbsp;Add</button>&nbsp;
                                                <button class="btn btn-danger btn-sm-in" id="js-sampling-delete-btn"><i class="ri-delete-bin-5-fill"></i>&nbsp;Delete</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-8 rightdiv">
                    <div class="row">
                        @csrf
                        <input type="hidden" name="encounter_id" id="js-printing-hform-encounter" value="{{ isset($encounter_data) ? $encounter_data->fldencounterval : "" }}">
                        <input type="hidden" name="sample_id" id="js-printing-hform-sample">
                        <input type="hidden" name="category_id" id="js-printing-hform-category">
                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group er-input">
                                                <div class="custom-control custom-radio custom-control-inline js-printing-encsamp-radio-div">
                                                    <input {{ (request('sample_id') == NULL || request('sample_id') == '') ? 'checked="checked' : '' }} type="radio" name="type" checked="checked" id="encounter" value="encounter" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="encounter"> Encounter </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline js-printing-encsamp-radio-div">
                                                    <input {{ request('sample_id') ? 'checked="checked' : '' }} type="radio" name="type" id="sample" value="sample" class="custom-control-input"/>
                                                    <label class="custom-control-label" for="sample"> Sample </label>
                                                </div>
                                                <div class="custom-control col-4">
                                                    <input type="text" id="js-printing-encounter-input" name="encounter_sample" value="{{ request('encounter_sample') }}" class="form-control"/>
                                                </div>
                                                <div class="custom-control p-0">
                                                    <button type="button" id="js-printing-show-btn" class="btn btn-primary btn-sm"><i class="fas fa-play"></i>&nbsp;Show</button>
                                                </div>
                                            </div>
                                            <div class="form-group er-input">
                                                <label class="col-3">Full Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="js-printing-name-input" readonly value='{{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ isset($encounter_data) ? "{$encounter_data->patientInfo->fldfullname} " : "" }}'
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group er-input">
                                                <label class="col-3">Address</label>
                                                <div class="col-sm-9">
                                                    <input type="hidden" name="status" id="js-printing-status-hidden-input">
                                                    <input type="text" id="js-printing-address-input" readonly value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptadddist : "" }}' class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group er-input">
                                                <label class="col-3 padding-none">Section</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="report_category_id" id="js-printing-category-select">
                                                        <option value="">%</option>
                                                        @foreach ($categories as $category)
                                                            <option {{ (request('category_id') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group er-input">
                                                <label class="col-3 padding-none">Age/Sex</label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="js-printing-agesex-input" readonly value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldagestyle : "" }}/{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptsex : "" }}' class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group er-input">
                                                <label class="col-3 padding-none">Location</label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="js-printing-location-input" readonly value='{{ isset($encounter_data) ? $encounter_data->fldcurrlocat : "" }}' class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group form-row float-right">
                                                <button type="button" class="btn btn-primary btn-action" data-toggle="modal" data-target="#js-printing-patient-search-modal"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>&nbsp;
                                                <button type="button" class="btn btn-primary btn-action" id="genereate-report"><i class="fas fa-code"></i>&nbsp;Preview</button>&nbsp;
                                                <button type="button" class="btn btn-primary btn-action" id="js-reporting-reporting-btn"><i class="fas fa-play"></i>&nbsp;Reporting</button>&nbsp;
                                                @if($report_segment == 'printing')
                                                    <button type="button" class="btn btn-primary btn-action" id="js-reporting-verification-btn"><i class="fas fa-play"></i>&nbsp;Verification</button>
                                                @else
                                                    <button type="button" class="btn btn-primary btn-action" id="js-reporting-printing-btn"><i class="fas fa-print"></i>&nbsp;Printing</button>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    @if($report_segment === 'verify')
                                        <div class="er-input form-row float-right">
                                            <div class="custom-control custom-checkbox custom-control-inline" id="checkAll">
                                                <input type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label">Accept All</label>
                                            </div>&nbsp;
                                            <div class="custom-control custom-checkbox custom-control-inline" id="unCheckAll">
                                                <input type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label">Reject All</label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="lab-table table-responsive mt-2 table-sticky-th table-labprint">
                                        <table class="table table-hovered table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th><input type="checkbox" id="js-printing-select-all-checkbox"/></th>
                                                <th>Test Name</th>
                                                <th>Specimen</th>
                                                <th>Sample</th>
                                                <th>Sample Date</th>
                                                <th>&nbsp;</th>
                                                <th>Observation</th>
                                                <th>Reference</th>
                                                <th>ReportDate</th>

                                                @if($report_segment === 'verify')
                                                    <th>Verify</th>
                                                @endif

                                                <th>VerifiedBy</th>
                                                <th>Qua</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="js-printing-samples-tbody">
                                            @if(isset($samples))
                                                @foreach($samples as $key => $sample)
                                                    @php
                                                        $selects[] = $sample->fldtestid;
                                                    @endphp
                                                    <tr data-subtest="{{ json_encode($sample->subTest) }}" data-fldid="{{ $sample->fldid }}" data-fldoption="{{ $sample->subTest?isset($sample->test->fldoption)?$sample->test->fldoption:'' :''}}" data-fldtestid="{{ $sample->fldtestid }}">
                                                        <td>{{ $key+1 }}</td>
                                                        <td><input type="checkbox" name="test[]" value="{{ $sample->fldid }}" class="js-printing-labtest-checkbox" checked></td>
                                                        <td>{{ $sample->fldtestid }}</td>
                                                        <td>{{ $sample->fldsampletype }}</td>
                                                        <td>{{ $sample->fldsampleid }}</td>
                                                        <td>{{ $sample->fldtime_sample }}</td>
                                                        <td>
                                                            @if ($sample->fldtest_type == 'Quantitative')
                                                                <button type="button" class="btn btn-sm {{ $sample->fldabnormal=='0' ? 'btn-success' : 'btn-danger' }}"></button>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($sample->fldtestid == 'Culture & Sensitivity')
                                                                @if ($sample->subTest->isNotEmpty())
                                                                    <table style="width: 100%;" class="content-body test-content table-bordered">
                                                                        <tbody>
                                                                            @foreach ($sample->subTest as $subtest)
                                                                            <tr>
                                                                                <td>{{ $subtest->fldsubtest }}</td>
                                                                                <td>
                                                                                    <table style="width: 100%;" class="content-body test-content">
                                                                                        <tbody>
                                                                                            @foreach ($subtest->subtables as $subtable)
                                                                                            <tr>
                                                                                                <td class="td-width">{{ $subtable->fldvariable }}</td>
                                                                                                <td class="td-width">{{ $subtable->fldvalue }}</td>
                                                                                                <td class="td-width">{{ $subtable->fldcolm2 }}</td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @else
                                                                    {!! $sample->fldreportquali !!}
                                                                @endif
                                                            @elseif($sample->fldreportquali !== NULL)
                                                                <span class="quantity-{{ $sample->fldid }}">
                                                                    {!! $sample->fldreportquali !!}
                                                                </span>

                                                                @if($sample->testLimit->isNotEmpty())
                                                                    @foreach($sample->testLimit as $testLimit)
                                                                        {{ $testLimit->fldsiunit }}
                                                                    @endforeach
                                                                @endif
                                                            @elseif($sample->subTest)
                                                                @foreach($sample->subTest as $subTest)
                                                                    @if($subTest->fldreport)
                                                                        <strong>{{ $subTest->fldsubtest }}</strong>
                                                                        <br>
                                                                        {!! $subTest->fldreport !!} <br>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($sample->testLimit->isNotEmpty())
                                                                @foreach($sample->testLimit as $testLimit)
                                                                    {{ $testLimit->fldsilow }} - {{ $testLimit->fldsihigh }} {{ $testLimit->fldsiunit }}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $sample->fldtime_report }}</td>
                                                        @if($report_segment === 'verify')
                                                            <td>
                                                                <input type="checkbox" {{ $sample->fldstatus == 'Verified' ? 'checked="checked"' : '' }} class="js-printing-verify-btn" data-fldid="{{ $sample->fldid }}">
                                                            </td>
                                                        @endif
                                                        <td>{{ $sample->flduserid_verify }}</td>
                                                        <td>0</td>
                                                        <td>
                                                            <a href="javascript:;"><i class="fas fa-sticky-note text-primary" onclick="labPrintingNote('{{ $sample->fldid }}', '{{ $sample->fldcomment }}')" title="Note"></i></a>
                                                            @if($report_segment === 'verify')
                                                                |
                                                                @if($sample->fldtest_type == 'Quantitative')
                                                                    <a href="javascript:;" class="change-quantity" onclick="changeQuantityVerify({{ $sample->fldid }}, '{{ $sample->fldreportquali }}', '{{ $sample->fldtestid }}')" title="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="javascript:;" id="qualitative-{{ $sample->fldid }}" onclick="quantityObservation.displayQualitativeForm({{ $sample->fldid }})" testid="{{ $sample->fldtestid }}" fldid="{{ $sample->fldid }}" title="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" style="float: left;">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        @if($report_segment === 'printing')
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input name="new" value="new" type="checkbox" class="custom-control-input bg-primary" id="input-check-new" checked>
                                                <label class="custom-control-label"> New</label>
                                            </div>
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input name="printed" value="printed" {{ request()->get('printed') ? 'checked' : '' }} type="checkbox" class="custom-control-input bg-primary" id="input-check-printed">
                                                <label class="custom-control-label"> Printed</label>
                                            </div>
                                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                <input name="markprinted" value="markprinted" type="checkbox" class="custom-control-input bg-primary" id="input-check-mark-printed" checked>
                                                <label class="custom-control-label"> Mark Printed</label>
                                            </div>
                                        @endif
                                        <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-primary" id="email_report" name="email_report">
                                            <label class="custom-control-label"> Email Report</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Diagnosis:</label>
                                        <div class="col-sm-9">
                                            <select multiple id="js-lab-common-diagnosis-ul" class="form-control">
                                            @if(isset($encounter_data) && $encounter_data->PatFindings)
                                                @foreach($encounter_data->PatFindings as $patfinding)
                                                    <option>{{ $patfinding->fldcode }}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="table-responsive major-table">
                                        <table class="table table-hovered table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">Subtest</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">Observation</th>
                                            </tr>
                                            </thead>
                                            <tbody id="js-printing-samples-subtest-tbody"></tbody>
                                        </table>
                                    </div> -->
                                    <div class="form-group">
                                        <div class="diagnosis-btn">
                                            <!-- <button class="btn rounded-pill btn-info" type="button" data-toggle="modal" data-target="#js-printing-save-report-modal">
                                                <i class="fas fa-plus"></i>&nbsp;Save
                                            </button> -->
                                            <button class="btn rounded-pill btn-warning" type="button">
                                                <i class="fas fa-file-download"></i>&nbsp;SMS
                                            </button>
                                            <button class="btn rounded-pill btn-primary" id="js-genereate-report" type="button">
                                                <i class="fas fa-code"></i>&nbsp; Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form method="POST" target="_blank" id="js-reporting-popup-form">
        @csrf
        <input type="hidden" name="type" id="js-reporting-type-hidden-form-input">
        <input type="hidden" name="encounter_sample" id="js-reporting-encountersample-hidden-form-input">
    </form>

    <div class="modal fade body-modal" id="js-printing-patient-search-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Search Patient</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div>
                        <form id="js-printing-search-patient-form" class="row">
                            <div class="col-md-6">
                                <div class="form-row form-group">
                                    <div class="col-md-4">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptnamefir" id="js-printing-modal-name-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>District</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptadddist" id="js-printing-modal-district-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Contact</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptcontact" id="js-printing-modal-contact-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>NHSI No.</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptcode" id="js-printing-modal-nhsi-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>SurName</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptnamelast" id="js-printing-modal-surname-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Address</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptaddvill" id="js-printing-modal-address-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Gender</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="fldptsex" id="js-printing-modal-gender-select" class="select-3 form-input">
                                            <option value="">-- Select --</option>
                                            <option value="">Male</option>
                                            <option value="">Female</option>
                                            <option value="">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="justify-content: center;width: 100%;margin-top: 5px;">
                                <button type="button" id="js-printing-search-patient-btn-modal" class="btn btn-default btn-sm"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-scroll-test" style="height: 300px; min-height: 300px;">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">PatientNo</th>
                                        <th class="tittle-th">Name</th>
                                        <th class="tittle-th">SurName</th>
                                        <th class="tittle-th">Gender</th>
                                        <th class="tittle-th">Address</th>
                                        <th class="tittle-th">District</th>
                                        <th class="tittle-th">Contact</th>
                                        <th class="tittle-th">CurrAge</th>
                                        <th class="tittle-th">PatientCode</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-printing-modal-patient-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-printing-save-report-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Save Report</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label>Title</label>
                            <input type="text" id="js-printing-title-modal-input" class="form-control" value="%">
                        </div>
                        <div class="col-md-4">
                            <button style="width: 100%;margin-bottom: 5px;" class="btn" id="js-printing-add-btn-modal">Ok</button>
                            <button style="width: 100%;" type="button" class="btn onclose" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="js-printing-add-btn-modal">Ok</button>
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('laboratory::modal.observation')
    @include('laboratory::modal.modal')
    @include('laboratory::modal.comment')
    @include('laboratory::modal.specimen')
@endsection

@push('after-script')
    <script src="{{asset('js/laboratory_form.js')}}"></script>
    <script>
        $('#js-genereate-report').on('click', function() {
            $('#genereate-report').trigger('click');
        });
        $('#genereate-report').click(function (e) {
            e.preventDefault();
            var encounter_sample = $('#js-printing-encounter-input').val() || '';
            if (encounter_sample !== '') {
                var markPrinted = $('#input-check-mark-printed').prop('checked') ? 'markPrinted' : '';
                var url = baseUrl + '/admin/laboratory/printing/printReport';
                url = url + '?' + $('#js-printing-hform').serialize() + '&status=' + ($('input[type="radio"][name="status"]:checked').val() || '');

                if (markPrinted == 'markPrinted') {
                    $.each($('.js-printing-labtest-checkbox:checked'), function(i, elem) {
                        $(elem).closest('tr').remove();
                    });

                    var encounter = $('#js-printing-hform-encounter').val();
                    if ($('#js-printing-samples-tbody tr').length == 0)
                        $('#js-printing-patient-tbody tr[data-encounterid="' + encounter + '"]').remove();
                }

                window.open(url);
            } else
                showAlert('Please enter encouter number to view report.', 'fail');
        });

        $(document).ready(function () {
            $("#checkAll").click(function () {
                $(".js-printing-verify-btn").each(function () {
                    this.checked = true;
                    var verify = this.checked ? 1 : 0;
                    $.ajax({
                        url: baseUrl + '/admin/laboratory/verify/verifyReport',
                        type: "POST",
                        data: {fldid: $(this).data('fldid'), verify: verify},
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            showAlert(response.message);
                            $('#js-printing-samples-tbody tr[is_selected="yes"] td:nth-child(11)').find('input[type="checkbox"]').attr('checked', true);
                        }
                    });
                });
                $("#unCheckAll").prop('checked', false);
            });
            $("#unCheckAll").click(function () {
                $(".js-printing-verify-btn").each(function () {
                    this.checked = false;
                    var verify = this.checked ? 1 : 0;
                    $.ajax({
                        url: baseUrl + '/admin/laboratory/verify/verifyReport',
                        type: "POST",
                        data: {fldid: $(this).data('fldid'), verify: verify},
                        dataType: "json",
                        success: function (response) {
                            console.log(response)
                            showAlert(response.message);
                            $('#js-printing-samples-tbody tr[is_selected="yes"] td:nth-child(11)').find('input[type="checkbox"]').attr('checked', true);
                        }
                    });
                });
                $("#checkAll").prop('checked', false);
            });
        });

        $(document).on('click', '#js-printing-samples-tbody tr td:nth-child(4)', function() {
            specimen.displayModal($(this));
        });
    </script>
@endpush
