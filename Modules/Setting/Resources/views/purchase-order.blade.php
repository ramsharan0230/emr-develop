@extends('frontend.layouts.master')
@push('after-styles')
    <style type="text/css">
        img.tick {
            width: 30%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('new/minicolor/jquery.minicolors.css') }}"/>
    <style>
        .minicolors-theme-default .minicolors-input {
            height: auto !important;
        }
    </style>
@endpush

@section('content')
    @php
    $patient_credential_setting = Options::get('patient_credential_setting');
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-center" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#purchase_order" role="tab" aria-controls="purchase_order" aria-selected="true">Purchase Order</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#stock_color" role="tab" aria-controls="stock_color" aria-selected="true">Stock Color</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#purchase_entry_format" role="tab" aria-controls="purchase_entry_format" aria-selected="false">Purchase Entry Format</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="purchase_order" role="tabpanel" aria-labelledby="purchase_order">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form method="POST" class="form-horizontal" action="{{ route('setting.purchaseOrder') }}">
                                            @csrf
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Order Lead Time
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="stock_lead_time" value="{{ (Options::get('stock_lead_time') != false) ? Options::get('stock_lead_time') : 30 }}" class="form-control">
                                                    <small class="help-block text-danger">{{$errors->first('stock_lead_time')}}</small>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Safety Stock
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="safety_stock" value="{{ (Options::get('safety_stock') != false) ? Options::get('safety_stock') : 60 }}" class="form-control">
                                                    <small class="help-block text-danger">{{$errors->first('safety_stock')}}</small>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Stock available color code:</label>
                                                <div class="col-sm-8">
                                                    <input autocomplete="off" type="text" name="stock_available_color_code" value="{{ (Options::get('stock_available_color_code') != false) ? Options::get('stock_available_color_code') : "#28a745" }}" id="stock_available_color_code" placeholder="Stock available color code" class="form-control colorpicker">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Stock near empty color code:</label>
                                                <div class="col-sm-8">
                                                    <input autocomplete="off" type="text" name="stock_near_empty_color_code" value="{{ (Options::get('stock_near_empty_color_code') != false) ? Options::get('stock_near_empty_color_code') : "#ffc107" }}" id="stock_near_empty_color_code" placeholder="Stock near empty color code" class="form-control colorpicker">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4"></label>
                                                <div class="col-sm-8">
                                                    <button class="btn btn-primary btn-action">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="stock_color" role="tabpanel" aria-labelledby="stock_color">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="{{ route('setting.medicine.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                            @csrf
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Expired color code:</label>
                                                <div class="col-sm-3">
                                                    <input autocomplete="off" type="text" name="expire_color_code" value="{{ Options::get('expire_color_code') }}" id="expire_color_code" placeholder="Expired color code" class="form-control colorpicker">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Near Expiry color code:</label>
                                                <div class="col-sm-3">
                                                    <input autocomplete="off" type="text" name="near_expire_color_code" value="{{ Options::get('near_expire_color_code') }}" id="near_expire_color_code" placeholder="Near Expiry color code" class="form-control colorpicker">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Near Expiry Duration (in days):</label>
                                                <div class="col-sm-3">
                                                    <input autocomplete="off" type="text" name="near_expire_duration" value="{{ Options::get('near_expire_duration') }}" id="near_expire_duration" placeholder="Near Expiry Duration (in days)" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4">Non exipry color code:</label>
                                                <div class="col-sm-3">
                                                    <input autocomplete="off" type="text" name="non_expire_color_code" value="{{ Options::get('non_expire_color_code') }}" id="non_expire_color_code" placeholder="Non exipry color code" class="form-control colorpicker">
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4"></label>
                                                <div class="col-sm-3">
                                                    <button class="btn btn-primary btn-action">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="purchase_entry_format" role="tabpanel" aria-labelledby="purchase_entry_format">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="{{ route('setting.purchaseentry.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                            @csrf
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">Report Format:</label>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="report_format" value="Default" @if(Options::get('report_format') === 'Default') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Default</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="report_format" value="Government" @if(Options::get('report_format') === 'Government') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Government</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-4"></label>
                                                <div class="col-sm-3">
                                                    <button class="btn btn-primary btn-action">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script src="{{asset('new/minicolor/jquery.minicolors.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $('.colorpicker').minicolors();
        });
    </script>
@endpush
