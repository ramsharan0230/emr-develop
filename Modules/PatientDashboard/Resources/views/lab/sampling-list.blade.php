@extends('patient.layouts.master')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="topspce">
                {{--<div class="row">
                        <div class="col-md-4">
                            <h4 class="pages_title">Laboratory Reports</h4>
                        </div>

                    </div>--}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="lab-bar">
                            <div class="header_card">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Laboratory Reports</h4>
                                </div>
                            </div>
                            <div class="row lab-tab-sec">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <select class="custom-select custom-select-sm">
                                        <option selected="">Select Laboratory sample no.</option>
                                        <option value="1">sample</option>
                                        <option value="2">sample</option>
                                        <option value="3">sample</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12 lab-date">
                                    <div class="filter">

                                        {{--<div id="date-range"
                                                 class="input-daterange input-group input-group-sm ml-auto">

                                                <input type="text" class="input-sm form-control" name="start"
                                                       placeholder="Start Date" id="date-range-1">
                                                <input type="text" class="input-sm form-control" name="end"
                                                       placeholder="End Date" id="date-range-2">
                                                <span class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="ri-calendar-2-line"></i>
                                                            </span>
                                                        </span>
                                            </div>--}}
                                        <a class="export_btn" href="{{ route('patient.portal.laboratory.report', [$encounter, $sampleId]) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf"></i> Export
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0 table_styles">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="border-0">SampleId</th>
                                            <th scope="col" class="border-0" style="width: 400px;">Test Name</th>
                                            <th scope="col" class="border-0">Flag</th>
                                            <th scope="col" class="border-0">Observation</th>
                                            <th scope="col" class="border-0">Unit</th>
                                            <th scope="col" class="border-0">Low/High</th>
                                            <th scope="col" class="border-0">Specimen</th>
                                            <th scope="col" class="border-0">Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($patLabData)
                                        @foreach($patLabData as $test)
                                        <tr>
                                            <td>{{ $test->fldsampleid }}</td>
                                            <td>{{ $test->fldtestid }}</td>
                                            <td>
                                                {!! ($test->fldabnormal == '0') ? '<div class="green_box"></div>' : '<div class="red_box"></div>' !!}
                                            </td>
                                            @if($test->fldtest_type == 'Quantitative')
                                            <td>{{ $test->fldreportquanti }}</td>
                                            <td>{{ $test->test_limit ? $test->test_limit->first()->fldmetunit:'' }}</td>
                                            <td>{{ $test->test_limit ? $test->test_limit->first()->fldsilow .'/'. $test->test_limit->first()->fldsihigh:'' }}</td>
                                            @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @endif
                                            <td>{{ $test->fldsampletype }}</td>
                                            <td>{{ $test->fldmethod }}</td>
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
</div>
@endsection