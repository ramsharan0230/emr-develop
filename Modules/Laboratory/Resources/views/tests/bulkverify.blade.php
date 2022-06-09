@extends('frontend.layouts.master')

@section('content')
    <style type="text/css">
        .fa-arrow-circle-right {
            font-size: 25px;
        }
        .modal-body p {
            margin-bottom: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Bulk Verification</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="js-bulkverify-form">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="name" class="col-sm-3">Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="name" class="form-control form-control-sm" id="name" autocomplete="off" value="{{ request()->get('name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="encounterId" class="col-sm-5">Encounter:</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="encounterId" class="form-control form-control-sm" id="encounterId" autocomplete="off" value="{{ request()->get('encounterId') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">From:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="from" class="form-control form-control-sm nepaliDatePicker" id="from_date" autocomplete="off" value="{{ $from }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-4">Category</label>
                                        <div class="col-lg-8 col-sm-8">
                                            <select name="category" class="form-control" id="js-bulkverify-category-select">
                                                <option value="">--Select--</option>
                                                @foreach ($categories as $category)
                                                <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="to" class="form-control form-control-sm nepaliDatePicker" id="to_date" autocomplete="off" value="{{ $to }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="custom-control custom-radio custom-control-inline js-printing-status-radio">
                                        <input type="radio" {{ (request('status') == 'reported') ? 'checked="checked' : '' }} name="status" id="reported" value="reported" class="custom-control-input"/>
                                        <label class="custom-control-label" for="reported"> Pending to verify </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline js-printing-status-radio">
                                        <input type="radio" {{ (request('status') == 'verified') ? 'checked="checked' : '' }} name="status" id="verified" value="verified" class="custom-control-input"/>
                                        <label class="custom-control-label" for="verified"> Verified </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-lg-3 col-sm-4">
                                            <button class="btn btn-primary" id="js-bulkverify-refresh-btn"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>
                                        </div>
                                        <div class="col-lg-3 col-sm-4">
                                            <button type="button" class="btn btn-primary" id="js-bulkverify-verify-btn"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Verify</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="js-bulkverify-tabledata">
                    <div class="iq-card-body">
                        <div class="res-table table-sticky-th table-bulk">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <th>&nbsp;</th>
                                    <th><input type="checkbox" id="js-printing-select-all-checkbox"/></th>
                                    <th>Enconter Id</th>
                                    <th width="250px">Patient Detail</th>
                                    <th>Test Name</th>
                                    <th>Specimen</th>
                                    <th>SampleId</th>
                                    <th>Sample Date</th>
                                    <th>&nbsp;</th>
                                    <th>Observation</th>
                                    <th>Reference</th>
                                    <th>ReportDate</th>
                                    <th>VerifiedBy</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="js-bulkverify-tbody">
                                    @if(isset($tests))
                                        @foreach($tests as $key => $sample)
                                            @php
                                                $selects[] = $sample->fldtestid;
                                            @endphp
                                            <tr data-fldid="{{ $sample->fldid }}">
                                                <td>{{ $key+1 }}</td>
                                                <td><input type="checkbox" name="test[]" value="{{ $sample->fldtestid }}" class="js-printing-labtest-checkbox" checked></td>
                                                <td>{{ $sample->fldencounterval }}</td>
                                                <td>
                                                    {{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldrankfullname : '' }} <br>
                                                    {{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldagestyle : '' }}/{{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldptsex : '' }} {{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldptcontact : '' }}<br>
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldptaddvill : '' }}, {{ ($sample->patientEncounter && $sample->patientEncounter->patientInfo) ? $sample->patientEncounter->patientInfo->fldptadddist : '' }}
                                                </td>
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
                                                            <table style="width: 100%;" class="content-body test-content">
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
                                                            <strong>{{ $subTest->fldsubtest }}</strong>
                                                            <br>
                                                            {!! $subTest->fldreport !!} <br>
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
                                                <td>{{ $sample->flduserid_verify }}</td>
                                                <td>
                                                    <a href="javascript:;"><i class="fas fa-sticky-note text-primary" onclick="labPrintingNote('{{ $sample->fldid }}', '{{ $sample->fldcomment }}')" title="Note"></i></a>
                                                    |
                                                    @if($sample->fldtest_type == 'Quantitative')
                                                        <a href="javascript:;" class="change-quantity" onclick="changeQuantityVerify({{ $sample->fldid }}, '{{ $sample->fldreportquali }}', '{{ $sample->fldtestid }}')" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @else
                                                        {{--subtest edit--}}
                                                        <a href="javascript:;" id="qualitative-{{ $sample->fldid }}" onclick="quantityObservation.displayQualitativeForm({{ $sample->fldid }})" testid="{{ $sample->fldtestid }}" fldid="{{ $sample->fldid }}" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(isset($tests))
                        {{ $tests->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('laboratory::modal.observation')
    @include('laboratory::modal.comment')
    @include('laboratory::modal.modal')
@endsection

@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
@endpush
