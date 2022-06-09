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
                            <h4 class="card-title">TAT Reporting</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="js-tat-form">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-4">Category</label>
                                        <div class="col-lg-8 col-sm-8">
                                            <select name="category" class="form-control" id="js-tat-report-select">
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
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="from" class="form-control form-control-sm nepaliDatePicker" id="from_date" autocomplete="off" value="{{ $from }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-lg-3 col-sm-4">
                                            <button type="button" class="btn btn-primary" id="js-tat-report-excel-btn"><i class="fa fa-code"></i>&nbsp;Excel</button>&nbsp;
                                        </div>
                                        <div class="col-lg-3 col-sm-4">
                                            <button type="button" class="btn btn-primary" id="js-tat-report-export-btn"><i class="fa fa-code"></i>&nbsp;Pdf</button>&nbsp;
                                        </div>
                                        <div class="col-lg-3 col-sm-4">
                                            <button type="button" class="btn btn-primary" id="js-tat-report-refresh-btn"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>&nbsp;
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="js-tat-report-tabledata">
                    @include('laboratory::tests.tatdata')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
@endpush
