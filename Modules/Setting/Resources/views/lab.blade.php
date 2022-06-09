@extends('frontend.layouts.master')
@push('after-styles')
    <style type="text/css">
        img.tick {
            width: 30%;
        }
        .upload-btn {
            display: flex;
            justify-content: center;
        }

        .preview-img {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 60px;
            background-color: #e6e6e6;
        }

        .preview-img img {
            border: 1px solid #999999;
            height: 60px;
            width: auto;
            border-radius: 5px;
            object-fit: contain;
        }

        #cke_1_top, #cke_2_top, #cke_3_top, #cke_4_top, #cke_5_top  {
            display: none;
        }

        #cke_1_contents, #cke_2_contents, #cke_3_contents, #cke_4_contents, #cke_5_contents {
            height: 200px !important;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-start" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#sample" role="tab" aria-controls="sample" aria-selected="true">Lab Sample</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#sampling_checkbox" role="tab" aria-controls="sampling_checkbox" aria-selected="true">Sampling Checkbox</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#imu_link_credentials" role="tab" aria-controls="imu_link_credentials" aria-selected="true">IMU Credentials</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#test_received" role="tab" aria-controls="test_received" aria-selected="true">Test Received</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#unsample_list" role="tab" aria-controls="unsample_list" aria-selected="true">Unsample List</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#worksheet" role="tab" aria-controls="worksheet" aria-selected="false">Work Sheet</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#barcode" role="tab" aria-controls="barcode" aria-selected="false">Barcode</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#report" role="tab" aria-controls="report" aria-selected="false">Report</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#printsetting" role="tab" aria-controls="printsetting" aria-selected="false">Print Setting</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="sample" role="tabpanel" aria-labelledby="sample">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Sample No Autoincrement:</label>
                                                <div class="col-sm-6">
                                                    <select name="sample_no_increment" id="sample_no_increment" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('sample_no_increment') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('sample_no_increment') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('sample_no_increment')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Sample Type:</label>
                                                <div class="col-sm-6">
                                                    <select name="sample_type" id="sample_type" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Default" {{ Options::get('sample_type') == 'Default'?'selected':'' }}>Default</option>
                                                        <option value="Daily" {{ Options::get('sample_type') == 'Daily'?'selected':'' }}>Daily</option>
                                                        <option value="Monthly" {{ Options::get('sample_type') == 'Monthly'?'selected':'' }}>Monthly</option>
                                                        <option value="Yearly" {{ Options::get('sample_type') == 'Yearly'?'selected':'' }}>Yearly</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('sample_type')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Prefix Text for Sample ID:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="prefix_text_for_sample_id" id="prefix_text_for_sample_id" class="form-control" placeholder="Prefix Text for Sample ID" value="{{ Options::get('prefix_text_for_sample_id')??'' }}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('prefix_text_for_sample_id')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Check Barcode:</label>
                                                <div class="col-sm-6">
                                                    <select name="check_barcode" id="check_barcode" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('check_barcode') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('check_barcode') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('check_barcode')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Check worksheet:</label>
                                                <div class="col-sm-6">
                                                    <select name="check_worksheet" id="check_worksheet" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('check_worksheet') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('check_worksheet') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('check_worksheet')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Print Mode:</label>
                                                <div class="col-sm-8">
                                                    <select name="worksheet_print_mode" id="worksheet_print_mode" class="form-control">
                                                        <option value="0">---select---</option>
                                                        <option value="1" {{ Options::get('worksheet_print_mode') == 1?'selected':'' }}>Continuous</option>
                                                        <option value="2" {{ Options::get('worksheet_print_mode') == 2?'selected':'' }}>Categorical</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('worksheet_print_mode')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Content:</label>
                                                <div class="col-sm-8">
                                                    <select name="bar_code_content" id="bar_code_content" class="form-control">
                                                        <option value="0">---select---</option>
                                                        <option value="EncounterID" {{ Options::get(
                                                        'bar_code_content') == 'EncounterID'?'selected':'' }}>EncounterID</option>
                                                        <option value="SampleNo" {{Options::get(
                                                        'bar_code_content') == 'SampleNo'?'selected':'' }}>SampleNo</option>
                                                        <option value="SampleNo@EncID" {{ Options::get('bar_code_content') == 'SampleNo@EncID'?'selected':'' }}>SampleNo@EncID</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('bar_code_content')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Seperation:</label>
                                                <div class="col-sm-8">
                                                    <select name="bar_code_seperation" id="bar_code_seperation" class="form-control">
                                                        <option value="0">---select---</option>
                                                        <option value="TestName" {{ Options::get('bar_code_seperation') == 'TestName'?'selected':'' }}>TestName</option>
                                                        <option value="Section" {{ Options::get('bar_code_seperation') == 'Section'?'selected':'' }}>Section</option>
                                                        <option value="None" {{ Options::get('bar_code_seperation') == 'None'?'selected':'' }}>None</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('bar_code_seperation')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Template:</label>
                                                <div class="col-sm-8">
                                                    <input name="bar_code_template" value="{{ Options::get('bar_code_template')??'' }}" id="bar_code_template" placeholder="Template" class="form-control">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('bar_code_template')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Format:</label>
                                                <div class="col-sm-8">
                                                    <select name="barcode_format" id="barcode_format" class="form-control">
                                                        <option value="0">---select---</option>
                                                        <option value="C39" {{ Options::get('barcode_format') == "C39"? "selected":"" }}>C39</option>
                                                        <option value="C39+" {{ Options::get('barcode_format') == "C39+"? "selected":"" }}>C39+</option>
                                                        <option value="C39E" {{ Options::get('barcode_format') == "C39E"? "selected":"" }}>C39E</option>
                                                        <option value="C39E+ " {{ Options::get('barcode_format') == "C39E+"? "selected":"" }}">C39E+</option>
                                                        <option value="C93" {{ Options::get('barcode_format') == "C93"? "selected":"" }}>C93</option>
                                                        <option value="S25" {{ Options::get('barcode_format') == "S25"? "selected":"" }}>S25</option>
                                                        <option value="S25+ " {{ Options::get('barcode_format') == "S25+"? "selected":"" }}">S25+</option>
                                                        <option value="I25" {{ Options::get('barcode_format') == "I25"? "selected":"" }}>I25</option>
                                                        <option value="I25+ " {{ Options::get('barcode_format') == "I25"? "selected":"" }}">I25+</option>
                                                        <option value="C128" {{ Options::get('barcode_format') == "C128"? "selected":"" }}>C128</option>
                                                        <option value="C128A" {{ Options::get('barcode_format') == "C128A"? "selected":"" }}>C128A</option>
                                                        <option value="C128B" {{ Options::get('barcode_format') == "C128B"? "selected":"" }}>C128B</option>
                                                        <option value="C128C" {{ Options::get('barcode_format') == "C128C"? "selected":"" }}>C128C</option>
                                                        <option value="EAN2" {{ Options::get('barcode_format') == "EAN2"? "selected":"" }}>EAN2</option>
                                                        <option value="EAN5" {{ Options::get('barcode_format') == "EAN5"? "selected":"" }}>EAN5</option>
                                                        <option value="EAN8" {{ Options::get('barcode_format') == "EAN8"? "selected":"" }}>EAN8</option>
                                                        <option value="EAN13" {{ Options::get('barcode_format') == "EAN13"? "selected":"" }}>EAN13</option>
                                                        <option value="UPCA" {{ Options::get('barcode_format') == "UPCA"? "selected":"" }}>UPCA</option>
                                                        <option value="UPCE" {{ Options::get('barcode_format') == "UPCE"? "selected":"" }}>UPCE</option>
                                                        <option value="MSI" {{ Options::get('barcode_format') == "MSI"? "selected":"" }}>MSI</option>
                                                        <option value="MSI+ " {{ Options::get('barcode_format') == "MSI"? "selected":"" }}">MSI+</option>
                                                        <option value="POSTNET" {{ Options::get('barcode_format') == "POSTNET"? "selected":"" }}>POSTNET</option>
                                                        <option value="PLANET" {{ Options::get('barcode_format') == "PLANET"? "selected":"" }}>PLANET</option>
                                                        <option value="RMS4CC" {{ Options::get('barcode_format') == "RMS4CC"? "selected":"" }}>RMS4CC</option>
                                                        <option value="KIX" {{ Options::get('barcode_format') == "KIX"? "selected":"" }}>KIX</option>
                                                        <option value="IMB" {{ Options::get('barcode_format') == "IMB"? "selected":"" }}>IMB</option>
                                                        <option value="CODABAR" {{ Options::get('barcode_format') == "CODABAR"? "selected":"" }}>CODABAR</option>
                                                        <option value="CODE11" {{ Options::get('barcode_format') == "CODE11"? "selected":"" }}>CODE11</option>
                                                        <option value="PHARMA" {{ Options::get('barcode_format') == "PHARMA"? "selected":"" }}>PHARMA</option>
                                                        <option value="PHARMA2T" {{ Options::get('barcode_format') == "PHARMA2T"? "selected":"" }}>PHARMA2T</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('barcode_format')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            {{--barcode size--}}
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Height:</label>
                                                <div class="col-sm-8">
                                                    <input name="bar_code_height" value="{{ Options::get('bar_code_height')??'' }}" id="bar_code_height" placeholder="Height" class="form-control" type="number">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('bar_code_height')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Width:</label>
                                                <div class="col-sm-8">
                                                    <input name="bar_code_width" value="{{ Options::get('bar_code_width')??'' }}" id="bar_code_width" placeholder="Width" class="form-control">
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('bar_code_width')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>

                                            {{-- barcode size end--}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="tab-pane fade" id="sampling_checkbox" role="tabpanel" aria-labelledby="sampling_checkbox">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Check Barcode:</label>
                                                <div class="col-sm-6">
                                                    <select name="check_barcode" id="check_barcode" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('check_barcode') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('check_barcode') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('check_barcode')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Check worksheet:</label>
                                                <div class="col-sm-6">
                                                    <select name="check_worksheet" id="check_worksheet" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('check_worksheet') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('check_worksheet') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('check_worksheet')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="tab-pane fade" id="imu_link_credentials" role="tabpanel" aria-labelledby="imu_link_credentials">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">IMU Link:</label>
                                                <div class="col-sm-7">
                                                    <input name="imu_link" value="{{ Options::get('imu_link')??'' }}" id="imu_link" placeholder="Lab Extension Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('imu_link')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">IMU Username:</label>
                                                <div class="col-sm-7">
                                                    <input name="imu_username" value="{{ Options::get('imu_username')??'' }}" id="imu_username" placeholder="Lab Extension Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('imu_username')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">IMU Password:</label>
                                                <div class="col-sm-7">
                                                    <input name="imu_password" value="{{ Options::get('imu_password')??'' }}" id="imu_password" placeholder="Lab Extension Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('imu_password')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="test_received" role="tabpanel" aria-labelledby="test_received">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Test Receiving:</label>
                                                <div class="col-sm-6">
                                                    <select name="test_receiving" id="test_receiving" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('test_receiving') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('test_receiving') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('test_receiving')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="unsample_list" role="tabpanel" aria-labelledby="unsample_list">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Unsample List:</label>
                                                <div class="col-sm-6">
                                                    <select name="unsample_lists" id="unsample_lists" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('unsample_lists') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('unsample_lists') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('unsample_lists')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="tab-pane fade" id="worksheet" role="tabpanel" aria-labelledby="worksheet">
                                
                            </div> --}}
                            {{-- <div class="tab-pane fade" id="barcode" role="tabpanel" aria-labelledby="barcode">
                                
                            </div> --}}
                            {{-- <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="barcode">
                                
                            </div> --}}
                            <div class="tab-pane fade" id="printsetting" role="tabpanel" aria-labelledby="printsetting">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">Show Verified:</label>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="show_verified" value="1" @if(Options::get('show_verified') === '1') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Yes</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="show_verified" value="0" @if(Options::get('show_verified') === '0') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.saveRadio('show_verified')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">Page Break:</label>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="lab_page_break" value="1" @if(Options::get('lab_page_break') === '1') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">Yes</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="lab_page_break" value="0" @if(Options::get('lab_page_break') === '0') checked @endif class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadio1">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.saveRadio('lab_page_break')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Reception Number:</label>
                                                <div class="col-sm-8">
                                                    <input name="reception_number" value="{{ Options::get('reception_number')??'' }}" id="reception_number" placeholder="Reception Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('reception_number')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-2">Lab Extension Number:</label>
                                                <div class="col-sm-8">
                                                    <input name="lab_extension_number" value="{{ Options::get('lab_extension_number')??'' }}" id="lab_extension_number" placeholder="Lab Extension Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_extension_number')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                @include('setting::lab_printing_setting')
                                    {{-- <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Logo Image Height:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_logo_image_height" value="{{ Options::get('lab_print_logo_image_height')??'' }}" id="lab_print_logo_image_height" placeholder="Logo Image Height" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_logo_image_height')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Logo Image Width:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_logo_image_width" value="{{ Options::get('lab_print_logo_image_width')??'' }}" id="lab_print_logo_image_width" placeholder="Logo Image Width" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_logo_image_width')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Hospital Name:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_hospital_name" value="{{ Options::get('lab_print_hospital_name')??'' }}" id="lab_print_hospital_name" placeholder="Hospital Name" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_hospital_name')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Hospital Address:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_hospital_address" value="{{ Options::get('lab_print_hospital_address')??'' }}" id="lab_print_hospital_address" placeholder="Hospital Address" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_hospital_address')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Phone Number:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_phone_number" value="{{ Options::get('lab_print_phone_number')??'' }}" id="lab_print_phone_number" placeholder="Phone Number" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_phone_number')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Patient Info:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_patient_info" value="{{ Options::get('lab_print_patient_info')??'' }}" id="lab_print_patient_info" placeholder="Patient Info" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_patient_info')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Test Detail:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_test_detail" value="{{ Options::get('lab_print_test_detail')??'' }}" id="lab_print_test_detail" placeholder="Test Detail" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_test_detail')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Signature:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_signature" value="{{ Options::get('lab_print_signature')??'' }}" id="lab_print_signature" placeholder="Signature" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_signature')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Signature Image Height:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_signature_height" value="{{ Options::get('lab_print_signature_height')??'' }}" id="lab_print_signature_height" placeholder="Signature Image Height" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_signature_height')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Siignature Image Width:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_signature_image_width" value="{{ Options::get('lab_print_signature_image_width')??'' }}" id="lab_print_signature_image_width" placeholder="Siignature Image Width" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_signature_image_width')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Footer Comment:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" name="lab_print_footer_comment" value="{{ Options::get('lab_print_footer_comment')??'' }}" id="lab_print_footer_comment" placeholder="Footer Comment" class="form-control" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labReportSettings.save('lab_print_footer_comment')" class="btn btn-primary"><i class="fa fa-check"></i> </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div> --}}
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script>
        var labReportSettings = {
            save: function (settingTitle) {
                console.log(settingTitle);
                settingValue = $('#' + settingTitle).val();
                console.log(settingValue);

                if (settingValue === "") {
                    alert('Selected field is empty.')
                }

                $.ajax({
                    url: '{{ route('setting.lab.save') }}',
                    type: "POST",
                    data: {settingTitle: settingTitle, settingValue: settingValue},
                    success: function (response) {
                        showAlert(response.message)
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            saveRadio: function (settingTitle) {
                settingValue = $('input[type="radio"][name="' + settingTitle + '"]:checked').val();
                if (settingValue === "") {
                    alert('Selected field is empty.')
                }

                $.ajax({
                    url: '{{ route('setting.lab.save') }}',
                    type: "POST",
                    data: {settingTitle: settingTitle, settingValue: settingValue},
                    success: function (response) {
                        showAlert(response.message)
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }
    </script>
@endpush
