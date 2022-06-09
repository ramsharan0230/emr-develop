@extends('frontend.layouts.master')
@push('after-styles')
<link rel="stylesheet" href="{{ asset('assets/css/laboratory-style.css')}}">
@endpush


@section('content')
<section class="cogent-nav">
    <ul class="nav nav-tabs" id="yourTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#testReporting" role="tab">Radio Reporting</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="testReporting" role="tabpanel">
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="form">
                            <div class="group__box half_box2">
                                <div class="box__input" style="flex: 0 0 82%;">
                                    <select id="js-reporting-category-select" class="select-3 form-input">
                                        <option value="">%</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="box__icon">
                                    <button class="default-btn" id="js-reporting-refresh-btn"><i class="fa fa-sync"></i></button>
                                </div>
                                <div class="box__icon">
                                    <button class="default-btn f-btn-icon-o"><i class="fa fa-code"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-8">
                        <div class="form">
                            <div class="group__box half_box2">
                                <div class="color-box"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="res-table table-sticky-th" style="max-height: 609px;">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="tittle-th">EncID</th>
                                        <th class="tittle-th">Name</th>
                                    </tr>
                                </thead>
                                <tbody id="js-reporting-name-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form">
                                    <div class="group__box half_box2">
                                        <div class="radio-1 col-md-4">
                                            <input id="js-reporting-encounter-radio" type="radio" value="Encounter" name="type" checked="checked">
                                            <label for="js-reporting-encounter-radio" style="border: none;">Encounter</label>
                                            <input id="js-reporting-sample-radio" type="radio" value="Sample" name="type">
                                            <label for="js-reporting-sample-radio" style="border: none;">Sample</label>
                                            <input type="hidden" name="encounter_id" id="js-reporting-encounter-id">
                                        </div>
                                        <div class="box__input" style="flex: 0 0 23%;">
                                            <input type="text" id="js-reporting-encounter-input">
                                        </div>
                                        <div class="box__icon" style="flex: 0 0 2%;">
                                            <button class="default-btn"><i class="fas fa-camera-retro"></i></button>
                                        </div>
                                        <div class="radio-1 col-md-2">
                                            <input type="checkbox" name="">&nbsp;
                                            <label style="border: none;">Show All</label>
                                        </div>&nbsp;
                                        <div class="box__icon">
                                            <button class="default-btn" id="js-reporting-show-btn"><i class="fas fa-play"></i>&nbsp;Show</button>
                                        </div>
                                        <div class="box__input">
                                            <button class="default-btn"><i class="fas fa-print"></i>&nbsp;Report</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Full Name</label>
                                        </div>&nbsp;
                                        <div class="box__input" style="flex: 0 0 68%;">
                                            <input type="text" id="js-reporting-fullname-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Address</label>
                                        </div>&nbsp;
                                        <div class="box__input" style="flex: 0 0 68%;">
                                            <input type="text" id="js-reporting-address-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Age/Sex</label>
                                        </div>&nbsp;
                                        <div class="box__input" style="flex: 0 0 68%;">
                                            <input type="text" id="js-reporting-agesex-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 31%;">
                                            <label>Location</label>
                                        </div>&nbsp;
                                        <div class="box__input" style="flex: 0 0 68%;">
                                            <input type="text" id="js-reporting-location-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <button class="default-btn"><i class="fas fa-file-medical-alt"></i>&nbsp;History</button>&nbsp;&nbsp;
                                <button class="default-btn"><i class="fas fa-long-arrow-alt-down"></i>&nbsp;Info</button>
                            </div>
                            <div class="col-md-5">
                                <div class="group__box half_box2">
                                    <div class="radio-1 col-md-10">
                                        <input type="checkbox" name="">
                                        <label style="border: none;">SI Unit</label>&nbsp;&nbsp;
                                        <input type="checkbox" name="">
                                        <label style="border: none;">Metric</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="default-btn f-btn-icon-o" onclick="quantityObservation.printPage()"><i class="fas fa-code"></i>&nbsp;Report</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="res-table table-sticky-th">
                                    <table class="table-striped table-hover table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="tittle-th">SN</th>
                                                <th class="tittle-th">SamID</th>
                                                <th class="tittle-th">Test Name</th>
                                                <th class="tittle-th">Flag</th>
                                                <th class="tittle-th">Observation</th>
                                                <th class="tittle-th">Unit</th>
                                                <th class="tittle-th">Low/High</th>
                                                <th class="tittle-th">Visibility</th>
                                                <th class="tittle-th">Specimen</th>
                                                <th class="tittle-th">Method</th>
                                                <th class="tittle-th">Sample Time</th>
                                                <th class="tittle-th">Report Time</th>
                                                <th class="tittle-th">Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-reporting-samples-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 45%;">
                                            <button class="default-btn f-btn-icon-g">&nbsp;&nbsp;Components</button>
                                        </div>&nbsp;
                                        <div class="box__label" style="flex: 0 0 45%;">
                                            <button class="default-btn f-btn-icon-b">&nbsp;&nbsp;Conditions</button>
                                        </div>
                                        <div class="box__label">
                                            <button class="default-btn"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Comment</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label" style="flex: 0 0 15%;">
                                            <label>signal</label>
                                        </div>
                                        <div class="radio-1 col-md-12">
                                            <input type="radio" value="red" id="red" name="color" class="js-color-radio">
                                            <label for="red" style="border: none;">Red</label>
                                            <input type="radio" value="yellow" id="yellow" name="color" class="js-color-radio">
                                            <label for="yellow" style="border: none;">Yellow</label>
                                            <input type="radio" value="green" id="green" name="color" class="js-color-radio">
                                            <label for="green" style="border: none;">Green</label>
                                            <input type="radio" value="blue" id="blue" name="color" class="js-color-radio">
                                            <label for="blue" style="border: none;">Blue</label>
                                            <input type="radio" value="black" id="black" name="color" class="js-color-radio">
                                            <label for="black" style="border: none;">Black</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" style="float: right;">
                                <div class="form-group mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__label">
                                            <button class="default-btn fas fa-camera-retro">&nbsp;&nbsp;image</button>
                                        </div>&nbsp;
                                        <div class="box__label" style="flex: 0 0 60%;">
                                            <button class="default-btn"><i class="fas fa-sms"></i>&nbsp;&nbsp;SMS</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <div class="group__box half_box2">
                                        <div class="box__input" style="flex: 0 0 83%;">
                                            <input type="" name="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="js-reporting-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Abnormal Status</h6>
            </div>
            <!-- Modal body -->
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
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@include('laboratory::modal.modal')
@stop

@push('after-script')
<script src="{{asset('js/laboratory_form.js')}}"></script>
@endpush
