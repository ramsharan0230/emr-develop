@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center form-row">

                    </div>
                    <div class="form-group form-row align-items-center">
                        <label for="" class="col-sm-3">Plan For:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="js-planreport-date-left" value="{{ $date }} {{ $time}}" />
                        </div>
                        <div class="col-sm-4">
                            <button id="js-planreport-refresh-left" class="btn btn-primary full-width"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group form-row">
                        <div class="col-sm-5">
                            <input type="text" class="form-control englishDatePicker" id="js-planreport-date-right" value="{{ $date }}" />
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="js-planreport-category-right">
                                <option value="">%</option>
                                @if($categories)
                                @foreach($categories as $category)
                                <option value="{{ $category->item }}">{{ $category->item }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-3 text-right">
                            <button id="js-planreport-refresh-right" class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Refresh</button>&nbsp;
                            <button data-toggle="modal" data-target="#js-planreport-encounter-modal" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
                            <button id="js-planreport-export-right" class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group form-row text-right">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-sticky-th">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>EncID</th>
                                    <th>Particulars</th>
                                </tr>
                            </thead>
                            <tbody id="js-planreport-tbody-left"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-sticky-th">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Time</th>
                                    <th>Particulars</th>
                                    <th>EncID</th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Contact</th>
                                    <th>Consultant</th>
                                    <th>FileNo</th>
                                </tr>
                            </thead>
                            <tbody id="js-planreport-tbody-right"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="js-planreport-encounter-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Search EncounterId</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>EncounterId</label>
                        <input type="text" class="form-control" id="js-planreport-encounter-input">
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-5">
                        <button type="button" id="js-planreport-encounter-search-modal" class="btn btn-success btn-sm">Search</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-script')
<script>
    var moduleName = "{{ $moduleName }}";
</script>
<script src="{{asset('js/planreport_form.js')}}"></script>
@endpush
