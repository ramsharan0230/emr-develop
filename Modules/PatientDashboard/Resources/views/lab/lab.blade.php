@extends('patient.layouts.master')

@section('content')
@php
$patLabDataGroupBy = $patLabData->groupBy('fldsampleid')->all();
$sampleids = $patLabData->pluck('fldsampleid')->unique('fldsampleid');
@endphp
<div class="main-container">
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
                                            @if($patLabDataGroupBy)
                                            @foreach($patLabDataGroupBy as $sample)
                                            <option value="{{ $sample[0]->fldsampleid }}">{{ $sample[0]->fldsampleid }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 lab-date">
                                        <div class="filter">

                                            <div id="date-range" class="input-daterange input-group input-group-sm ml-auto">

                                                <input type="text" class="input-sm form-control" name="start" placeholder="Start Date" id="date-range-1">
                                                <input type="text" class="input-sm form-control" name="end" placeholder="End Date" id="date-range-2">
                                                <span class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="ri-calendar-2-line"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table mb-0 table_styles">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col" class="border-0">Encounter</th>
                                                <th scope="col" class="border-0" style="width: 400px;">TestId</th>
                                                <th scope="col" class="border-0">Sample Id</th>
                                                <th scope="col" class="border-0">Sample Type</th>
                                                <th scope="col" class="border-0">Time</th>
                                                <th scope="col" class="border-0"></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if($patLabDataGroupBy)
                                            @foreach($patLabDataGroupBy as $test)
                                            <tr>
                                                <td>{{ $test[0]->fldencounterval }}</td>
                                                <td>{{ implode(", ", $test->pluck('fldtestid')->all()) }}</td>
                                                <td>{{ $test[0]->fldsampleid }}</td>
                                                <td>{{ implode(", ", array_unique ($test->pluck('fldsampletype')->all())) }}</td>
                                                <td>{{ $test[0]->fldtime_sample }}</td>
                                                <td>
                                                    <a href="{{ route('patient.portal.laboratory.sample.list', [$test[0]->fldencounterval, $test[0]->fldsampleid]) }}" class="pdf_btn">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection