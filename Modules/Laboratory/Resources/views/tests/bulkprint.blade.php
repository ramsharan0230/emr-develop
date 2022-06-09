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
                            <h4 class="card-title">Bulk Printing</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="js-bulkverify-form">
                            <input type="hidden" name="new" id="js-bulkprint-new-hidden-input">
                            <input type="hidden" name="printed" id="js-bulkprint-printed-hidden-input">
                            <input type="hidden" name="markprinted" id="js-bulkprint-markprinted-hidden-input">
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
                                    <div class="custom-control custom-radio custom-control-inline">
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
                                            <button type="button" class="btn btn-primary" id="js-bulkverify-print-btn"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>
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
                        <div class="row">
                            <div class="col-sm-2"><button class="btn" style="background-color: yellowgreen;">&nbsp;</button>&nbsp;Not Done</div>
                            <div class="col-sm-2"><button class="btn" style="background-color: #0080ff;">&nbsp;</button>&nbsp;Reported</div>
                            <div class="col-sm-2"><button class="btn" style="background-color: orange;">&nbsp;</button>&nbsp;Verified</div>
                        </div>
                        <div class="res-table table-sticky-th">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <th>&nbsp;</th>
                                    <th><input type="checkbox" id="js-printing-select-all-checkbox"/></th>
                                    <th>Enconter Id</th>
                                    <th>Sample Id</th>
                                    <th width="250px">Patient Detail</th>
                                    <th>Test Name</th>
                                    <th>Specimen</th>
                                    <th>Sample Date</th>
                                    <th>ReportDate</th>
                                    <th>VerifiedBy</th>
                                </tr>
                                </thead>
                                <tbody id="js-bulkverify-tbody">
                                    @if(isset($tests))
                                        @foreach($tests as $sample)
                                            <tr data-fldid="{{ implode(',', array_filter(array_column($sample['tests'], 'fldid'))) }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td><input type="checkbox" name="test[]" class="js-printing-labtest-checkbox" checked></td>
                                                <td>{{ $sample['fldencounterval'] }}</td>
                                                <td>{{ $sample['fldsampleid'] }}</td>
                                                <td>
                                                    {{ $sample['fldrankfullname'] }} <br>
                                                    {{ $sample['fldage'] }}/{{ $sample['fldptsex'] }} {{ $sample['fldptcontact'] }}<br>
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{ $sample['fldaddress'] }}
                                                </td>
                                                <td>
                                                    <ul>
                                                        @foreach ($sample['tests'] as $test)
                                                        @php
                                                            $color = '';
                                                            if ($test['fldstatus'] == 'Not Done')
                                                                $color = 'yellowgreen';
                                                            if ($test['fldstatus'] == 'Reported')
                                                                $color = '#0080ff';
                                                            if ($test['fldstatus'] == 'Verified')
                                                                $color = 'orange';                                                            
                                                        @endphp
                                                            <li style="color: {{ $color }}">{{ $test['fldtestid'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>{{ implode(', ', array_unique(array_filter($sample['fldsampletype']))) }}</td>
                                                <td>{{ max(array_filter($sample['fldtime_sample'])) }}</td>
                                                <td>{{ max(array_filter($sample['fldtime_report'])) }}</td>
                                                <td>{{ implode(', ', array_unique(array_filter($sample['flduserid_verify']))) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(isset($rawpatients))
                        {{ $rawpatients->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12" style="float: left;">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                <input name="new" value="new" type="checkbox" class="custom-control-input bg-primary" id="input-check-new" checked>
                                <label class="custom-control-label"> New</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                <input name="printed" value="printed" type="checkbox" {{ request()->get('printed') ? 'checked' : '' }} class="custom-control-input bg-primary" id="input-check-printed">
                                <label class="custom-control-label"> Printed</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                <input name="markprinted" value="markprinted" type="checkbox" class="custom-control-input bg-primary" id="input-check-mark-printed" checked>
                                <label class="custom-control-label"> Mark Printed</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
@endpush
