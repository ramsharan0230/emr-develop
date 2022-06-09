@extends('frontend.layouts.master')

@section('content')
@php
$report_segment = Request::segment(2)
@endphp
<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                           Radio {{ ucwords($report_segment) }}
                       </h4>
                   </div>
                   <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
               </div>
           </div>
       </div>
       <div class="col-sm-12 col-lg-4 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <form id="js-printing-search-form">
                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-4"><label>Name</label></div>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="js-sampling-search-name-input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-4"><label>Encounter</label></div>
                        <div class="col-sm-8">
                            <input type="text" name="encounterId" id="js-sampling-search-encounter-input" class="form-control">
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
                            <button class="btn btn-primary" type="button" id="js-sampling-search-submit-btn"><i class="fa fa-sync"></i></button>
                        </div>
                    </div>

                    <div class="form-group form-row align-items-center form-row">
                        <div class="col-sm-12">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="status" value="Reported" class="custom-control-input" checked />
                                <label class="custom-control-label"> Reported </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="status" value="Verified" class="custom-control-input"/>
                                <label class="custom-control-label"> Verified </label>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive table-scroll-sampling table-sticky-th" style="height: 475px; min-height: 475px;">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>EncID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Userid</th>
                                <th>Datetime</th>
                            </tr>
                        </thead>
                        <tbody id="js-printing-patient-tbody">
                            @if(isset($patients))
                            @foreach($patients as $pat)
                            <tr data-encounterid="{{ $pat->fldencounterval }}">
                                <td>{{ $pat->fldencounterval }}</td>
                                <td>
                                    {{ ($pat->encounter && $pat->encounter->patientInfo) ? $pat->encounter->patientInfo->fldrankfullname : '' }}
                                </td>
                                <td>{{ ($pat->encounter && $pat->encounter->consultant) ? $pat->encounter->consultant->fldconsultname : '' }}</td>
                                <td>{{ $pat->flduserid_report }}</td>
                                <td>{{ $pat->fldtime_report }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                @if(FALSE)
                <div class="table-responsive table-scroll-sampling table-sticky-th mt-2">
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
    <div class="col-sm-12 col-lg-8 rightdiv">
        <form id="js-printing-hform" {{-- action="{{ route('laboratory.printing.print.report') }}"--}} method="post">
            @csrf
            <input type="hidden" name="encounter_id" id="js-printing-hform-encounter">
            <input type="hidden" name="sample_id" id="js-printing-hform-sample">
            <input type="hidden" name="category_id" id="js-printing-hform-category">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <div class="col-sm-4">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input  {{ (request('sample_id') == NULL || request('sample_id') == '') ? 'checked="checked' : '' }} type="radio" name="type" checked="checked" id="encounter" value="encounter" class="custom-control-input">
                                            <label class="custom-control-label" for="encounter"> Encounter </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="custom-control padding-none">
                                            <input type="text" id="js-printing-encounter-input" name="encounter_sample" value="{{ request('sample_id') ?: request('encounter_id') }}" class="form-control">
                                        </div>
                                    </div>
                                    <!-- <div class="custom-control custom-radio custom-control-inline">
                                        <input {{ request('sample_id') ? 'checked="checked' : '' }} type="radio" name="type" id="sample" value="sample" class="custom-control-input">
                                        <label class="custom-control-label" for="sample"> Sample </label>
                                    </div> -->
                                            
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Full Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly value='{{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ isset($encounter_data) ? "{$encounter_data->patientInfo->fldptnamefir} {$encounter_data->patientInfo->fldmidname} {$encounter_data->patientInfo->fldptnamelast}" : "" }}' class="form-control">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control" value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptadddist : "" }}'>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Section</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="js-printing-category-select">
                                            <option value="">%</option>
                                            @foreach ($categories as $category)
                                            <option {{ (request('category_id') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Age/Sex</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptsex : "" }}' class="form-control">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Location</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly value='{{ isset($encounter_data) ? $encounter_data->fldcurrlocat : "" }}' class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="button" class="btn btn-primary btn-action" id="js-reporting-pacs-btn">&nbsp;PACS</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-action" id="js-printing-show-btn"><i class="fas fa-play"></i>&nbsp;Show</button>&nbsp;
                            <button type="button" class="btn btn-primary btn-action" data-toggle="modal" data-target="#js-printing-patient-search-modal"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="col-sm-12">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="lab-table table-responsive mt-2 table-sticky-th">
                                    <table class="table table-hovered table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">Test Name</th>
                                                <th class="tittle-th">ReportDate</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                <th class="tittle-th">Observation</th>
                                                <th class="tittle-th">&nbsp;</th>
                                                @if($report_segment === 'verify')
                                                <th class="tittle-th">&nbsp;</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="js-printing-samples-tbody">
                                            @if(isset($samples))
                                            @foreach($samples as $key => $sample)
                                            @php $selects[] = $sample->fldtestid @endphp
                                            <tr data-subtest="{{ json_encode($sample->radioSubTest) }}">
                                                <td>{{ $key+1 }}</td>
                                                <td><input type="checkbox" name="test[]" checked="checked" value="{{ $sample->fldtestid }}"></td>
                                                <td>{{ $sample->fldtestid }}</td>
                                                <td>{{ $sample->fldtime_report }}</td>
                                                <td>
                                                    <button class="btn btn-sm {{ $sample->fldabnormal=='0' ? 'btn-success' : 'btn-danger' }}"></button>
                                                </td>
                                                <td>
                                                    @if($sample->fldreportquali !== NULL)
                                                    {!! $sample->fldreportquali !!}
                                                    @if($sample->testLimit && $sample->testLimit->isNotEmpty())
                                                    @foreach($sample->testLimit as $testLimit)
                                                    {{ $testLimit->fldsiunit }}
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                </td>
                                                <td><input type="checkbox" {{ $sample->fldstatus == 'Verified' ? 'checked="checked"' : '' }}></td>
                                                @if($report_segment === 'verify')
                                                <td>
                                                    <button class="btn btn-sm btn-default js-printing-verify-btn" data-fldid="{{ $sample->fldid }}">Verify</button>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6" style="float: left;">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="table-responsive major-table">
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
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-12" style="float: left;">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <!-- <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                        <input type="checkbox" class="custom-control-input bg-primary" id="input-check-new">
                                        <label class="custom-control-label" for="input-check-new"> New</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                        <input type="checkbox" class="custom-control-input bg-primary" id="input-check-printed">
                                        <label class="custom-control-label" for="input-check-printed"> Printed</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                        <input type="checkbox" class="custom-control-input bg-primary" id="input-check-mark-printed">
                                        <label class="custom-control-label" for="input-check-mark-printed"> Mark Printed</label>
                                    </div> -->
                                    <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                        <input type="checkbox" class="custom-control-input bg-primary" id="email_report" name="email_report">
                                        <label class="custom-control-label"> Email Report</label>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <select name="" class="form-control">
                                        <option>-- Select -</option>
                                        @foreach($selects as $select)
                                        <option>{{ $select }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Comment"></textarea>
                                </div> -->
                                <div class="form-group">
                                    <div class="diagnosis-btn float-right">
                                        <!-- <button class="btn rounded-pill btn-info" type="button" data-toggle="modal" data-target="#js-printing-save-report-modal">
                                            <i class="fas fa-plus"></i>&nbsp;Save
                                        </button> -->
                                        <button class="btn btn-action btn-warning" type="button">
                                            <i class="fas fa-file-download"></i>&nbsp;SMS
                                        </button>
                                        <button class="btn btn-action btn-primary" id="genereate-report" type="button">
                                            <i class="fas fa-code"></i>&nbsp; Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="js-printing-patient-search-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <div class="head-content">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                    </div>
                    <h6 class="modal-title">Search Patient</h6>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <form id="js-printing-search-patient-form" class="form-group">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>Name</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptnamefir" id="js-printing-modal-name-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>District</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptadddist" id="js-printing-modal-district-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>Contact</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptcontact" id="js-printing-modal-contact-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>NHSI No.</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptcode" id="js-printing-modal-nhsi-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>SurName</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptnamelast" id="js-printing-modal-surname-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>Address</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptaddvill" id="js-printing-modal-address-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box">
                                        <div class="box__label">
                                            <label>Gender</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <select name="fldptsex" id="js-printing-modal-gender-select" class="select-3 form-input">
                                                <option value="">-- Select --</option>
                                                <option value="">Male</option>
                                                <option value="">Female</option>
                                                <option value="">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="float: right;">
                                    <button type="button" id="js-printing-search-patient-btn-modal" class="btn btn-default btn-sm"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                                    <!-- <button type="button" id="js-printing-export-patient-btn-modal" class="btn btn-default btn-sm"><i class="fa fa-code"></i>&nbsp;&nbsp;Export</button> -->
                                </div>
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
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('after-script')
    <script src="{{asset('js/radiology_form.js')}}"></script>
    @endpush
