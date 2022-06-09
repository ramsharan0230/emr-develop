@extends('frontend.layouts.master')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/laboratory-style.css')}}">
@endpush

@section('content')
    @php
        $report_segment = Request::segment(3)
    @endphp

<form style="display: none;" method="post" id="js-printing-hform">
    @csrf
    <input type="hidden" name="encounter_id" id="js-printing-hform-encounter">
    <input type="hidden" name="sample_id" id="js-printing-hform-sample">
    <input type="hidden" name="category_id" id="js-printing-hform-category">
</form>
<section class="cogent-nav">
    <ul class="nav nav-tabs" id="yourTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#testPrinting" role="tab">Test {{ ucwords($report_segment) }}</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="testPrinting" role="tabpanel">
            <div class="mt-3">
                <div class="row">
                   <div class="col-md-5">
                        <div class="form mt-2">
                            <div class="group__box half_box2">
                                <div class="radio-1 col-md-6">
                                    <input {{ (request('sample_id') == NULL || request('sample_id') == '') ? 'checked="checked' : '' }} type="radio" name="type" checked="checked" id="encounter" value="encounter">
                                    <label style="border: none;" for="encounter">Encounter</label>
                                    <input {{ request('sample_id') ? 'checked="checked' : '' }} type="radio" name="type" id="sample" value="sample">
                                    <label style="border: none;" for="sample">Sample</label>
                                </div>&nbsp;
                                <div class="box__input">
                                    <input type="text" id="js-printing-encounter-input" value="{{ request('sample_id') ?: request('encounter_id') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                           <div class="group__box half_box2">
                                <div class="box__label" style="flex: 0 0 31%;">
                                    <label>Full Name</label>
                                </div>&nbsp;
                                <div class="box__input" style="flex: 0 0 111%;">
                                    <input type="text" readonly="readonly" value='{{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ isset($encounter_data) ? "{$encounter_data->patientInfo->fldptnamefir} {$encounter_data->patientInfo->fldmidname} {$encounter_data->patientInfo->fldptnamelast}" : "" }}'>
                                </div>
                            </div>
                       </div>
                        <div class="form-group">
                           <div class="group__box half_box2">
                                <div class="box__label" style="flex: 0 0 31%;">
                                    <label>Address</label>
                                </div>&nbsp;
                                <div class="box__input" style="flex: 0 0 111%;">
                                    <input type="text" readonly="readonly" value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptadddist : "" }}'>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Full Name</label>
                                        </div>&nbsp;
                                        <div class="box__input" style="flex: 0 0 111%;">
                                            <input type="text" value='{{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ isset($encounter_data) ? "{$encounter_data->patientInfo->fldptnamefir} {$encounter_data->patientInfo->fldmidname} {$encounter_data->patientInfo->fldptnamelast}" : "" }}'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                       <div class="form-group">
                           <div class="group__box half_box2">
                                <div class="box__label" style="flex: 0 0 31%;">
                                    <label>Age/Sex</label>
                                </div>&nbsp;
                                <div class="box__input">
                                    <input type="text" readonly="readonly" value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptsex : "" }}'>
                                </div>
                            </div>
                       </div>
                        <div class="form-group">
                           <div class="group__box half_box2">
                                <div class="box__label" style="flex: 0 0 31%;">
                                    <label>Location</label>
                                </div>&nbsp;
                                <div class="box__input">
                                    <input type="text" readonly="readonly" value='{{ isset($encounter_data) ? $encounter_data->fldcurrlocat : "" }}'>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Age/Sex</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptsex : "" }}'>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Location</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" value='{{ isset($encounter_data) ? $encounter_data->fldcurrlocat : "" }}'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form mt-2">
                            <div class="group__box half_box2">
                                <div class="box__label" style="flex: 0 0 30%;">
                                    <button class="default-btn" data-toggle="modal" data-target="#js-printing-patient-search-modal"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                                </div>
                                <div class="radio-1-newdeli col-md-8">
                                    <input type="radio" name="t2" value="reported" id="reported" class="radio-custom" {{ $is_verified ? '' : 'checked="checked"' }}>
                                    <label style="border: none;" for="reported">Reported</label>
                                    <input type="radio" name="t2" value="verified" id="verified" class="radio-custom"{{ $is_verified ? 'checked="checked"' : '' }}>
                                    <label style="border: none;" for="verified">Verified</label>
                                </div>
                                <div class="form mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 30%;">
                                            <button class="default-btn" data-toggle="modal" data-target="#js-printing-patient-search-modal"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                                        </div>
                                        <div class="radio-1-newdeli col-md-8">
                                            <input type="radio" name="t2" value="reported" id="reported" class="radio-custom" checked="checked">
                                            <label style="border: none;" for="reported">Reported</label>
                                            <input type="radio" name="t2" value="verified" id="verified" class="radio-custom">
                                            <label style="border: none;" for="verified">Verified</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 30%;">
                                            <button class="default-btn"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Order</button>
                                        </div>
                                        <div class="radio-1-newdeli col-md-8">
                                            <input type="radio" name="t3" value="si_unit" id="si_unit" class="radio-custom" checked="checked">
                                            <label style="border: none;" for="si_unit">SI unit</label>
                                            <input type="radio" name="t3" value="metric" id="metric" class="radio-custom">
                                            <label style="border: none;" for="metric">Metric</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table-scroll-test" style="height: 300px; min-height: 300px;">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Test Name</th>
                                        <th class="tittle-th">Specimen</th>
                                        <th class="tittle-th">Sample</th>
                                        <th class="tittle-th">Sample Date</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Observation</th>
                                        <th class="tittle-th">Refrence</th>
                                        <th class="tittle-th">ReportDate</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Qua</th>
                                        @if($report_segment === 'verify')
                                        <th class="tittle-th">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="js-printing-samples-tbody">
                                @if(isset($samples))
                                    @foreach($samples as $key => $sample)
                                        @php $selects[] = $sample->fldtestid @endphp
                                        <tr data-subtest="{{ json_encode($sample->subTest) }}">
                                            <td>{{ $key+1 }}</td>
                                            <td><input type="checkbox"></td>
                                            <td>{{ $sample->fldtestid }}</td>
                                            <td>{{ $sample->fldsampletype }}</td>
                                            <td>{{ $sample->fldsampleid }}</td>
                                            <td>{{ $sample->fldtime_sample }}</td>
                                            <td>
                                                <button class="btn btn-sm {{ $sample->fldabnormal=='0' ? 'btn-success' : 'btn-danger' }}"></button>
                                            </td>
                                            <td>
                                                @if($sample->fldreportquali !== NULL)
                                                    {{ $sample->fldreportquali }}
                                                    @if($sample->testLimit->isNotEmpty())
                                                        @foreach($sample->testLimit as $testLimit)
                                                            {{ $testLimit->fldsiunit }}
                                                        @endforeach
                                                    @endif
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
                                            <td><input type="checkbox" {{ $sample->fldstatus == 'Verified' ? 'checked="checked"' : '' }}></td>
                                            <td>0</td>
                                            @if($report_segment === 'verify')
                                                <th class="tittle-th">Action</th>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="table-responsive table-scroll-test" style="height:200px; min-height: 200px;">
                            <table class="table table-bordered table-sm">
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
                    <div class="col-md-4 mt-2">
                        <div class="form">
                           <div class="group__box half_box2">
                                <div class="radio-1 col-md-12">
                                    <input type="checkbox" name="" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label style="border: none;">New</label>
                                    <input type="checkbox" name="" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label style="border: none;">Printed</label>
                                    <input type="checkbox" name="" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label style="border: none;">Mark Printed</label>
                                </div>
                            </div>
                            <div class="col-md-8 mt-2">
                                <div class="table-responsive table-scroll-test" style="height:200px; min-height: 200px;">
                                    <table class="table table-bordered table-sm">
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
                            <div class="col-md-4 mt-2">
                                <div class="form">
                                    <div class="group__box half_box2">
                                        <div class="radio-1 col-md-12">
                                            <input type="checkbox" name="">
                                            <label style="border: none;">New</label>
                                            <input type="checkbox" name="">
                                            <label style="border: none;">Printed</label>
                                            <input type="checkbox" name="">
                                            <label style="border: none;">Mark Printed</label>
                                            <br>
                                            <input type="checkbox" id="email_report" name="email_report">
                                            <label style="border: none;" for="email_report">Email Report</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="group__box half_box2">
                                        <div class="box__input" style="flex: 0 0 100%;">
                                            <select name="" class="select-3 form-input">
                                                <option>-- Select -</option>
                                                @foreach($selects as $select)
                                                    <option>{{ $select }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="group__box half_box2">
                                        <textarea style="height: 100px;"></textarea>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 60%;">
                                            <button class="default-btn fas fa-camera-retro">&nbsp;&nbsp;Save</button>
                                        </div>&nbsp;
                                        <div class="box__label" style="flex: 0 0 60%;">
                                            <button class="default-btn"><i class="fas fa-file-download"></i>&nbsp;&nbsp;SMS</button>
                                        </div>
                                        <div class="box__label">

                                            <button id="genereate-report" class="default-btn"><i class="fas fa-file-chart-line"></i>&nbsp;&nbsp;Report</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <label>Name</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptnamefir" id="js-printing-modal-name-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <label>District</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptadddist" id="js-printing-modal-district-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <label>Contact</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptcontact" id="js-printing-modal-contact-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
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
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <label>SurName</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptnamelast" id="js-printing-modal-surname-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <label>Address</label>
                                        </div>&nbsp;
                                        <div class="box__input">
                                            <input type="text" name="fldptaddvill" id="js-printing-modal-address-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
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
@stop

@push('after-script')

    <script src="{{asset('js/laboratory_form.js')}}"></script>

    <script>
        $('#genereate-report').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: baseUrl + '/admin/laboratory/printing/printReport',
                type: "POST",
                data: $('#js-printing-hform').serialize(),
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response, status, xhr) {
                    console.log(response);
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');

                    if (disposition) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }
                    var linkelem = document.createElement('a');
                    try {
                        var blob = new Blob([response], {type: 'application/octet-stream'});

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");

                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
                                    window.location = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.target = "_blank";
                                    a.click();
                                }
                            } else {
                                window.location = downloadUrl;
                            }
                        }

                    } catch (ex) {
                        console.log(ex);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });
    </script>
@endpush

