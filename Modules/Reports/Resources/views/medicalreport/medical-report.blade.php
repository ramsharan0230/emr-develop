@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        #item-listing-table td {
            padding: 2px 5px;
        }

        .select_td {
            background: #f2f2f2;
            border-radius: 5px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Medical Report (Diagnosis Report)
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="medical_filter_data">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="category" id="category" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Patient Demographics">Patient Demographics</option>
                                                <option value="Clinical Demographics">Clinical Demographics</option>
                                                <option value="Presenting Symptoms">Presenting Complaints</option>
                                                <option value="Patient Symptoms">Patient Symptoms</option>
                                                <option value="Provisional Diagnosis">Provisional Diagnosis</option>
                                                <option value="Final Diagnosis">Final Diagnosis</option>
                                                <option value="Disease Surveillance">Disease Surveillance</option>
                                                <option value="Prov Diagnosis Groups">Prov Diagnosis Groups</option>
                                                <option value="Final Diagnosis Groups">Final Diagnosis Groups</option>
                                                <option value="Examination">Examination</option>
                                                <option value="Triage Examination">Triage Examination</option>
                                                <option value="Discharge Examination">Discharge Examination</option>
                                                <option value="Diagnostic Tests">Diagnostic Tests</option>
                                                <option value="Radio Diagnostics">Radio Diagnostics</option>
                                                <option value="Allergic Drugs">Allergic Drugs</option>
                                                <option value="Narcotic Drugs">Narcotic Drugs</option>
                                                <option value="Prescribed Drugs">Prescribed Drugs</option>
                                                <option value="Major Procedures">Major Procedures</option>
                                                <option value="Extra Procedures">Extra Procedures</option>
                                                <option value="Equipment">Equipment</option>
                                                <option value="Obstetrics">Obstetrics</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-center mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="loadData()"><i class="fas fa-dot-circle"></i>&nbsp;
                                        Load Data</a>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="iq-search-bar custom-search">
                                        <div class="searchbox">
                                            <input type="hidden" name="selectedItem" id="selectedItem">
                                            <input type="text" id="listing_data" name="" class="text search-input" placeholder="Type here to search..." />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 res-table mb-2" style="min-height: 300px;">
                                    <table id="list-table">
                                        <tbody id="listing-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="method" id="method" class="form-control" disabled>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="si" name="unitType" class="custom-control-input" checked/>
                                            <label class="custom-control-label" for=""> SI Unit </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="metric" name="unitType" class="custom-control-input" />
                                            <label class="custom-control-label" for=""> Metric </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-sm-4">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="getRefreshData()"><i class="fas fa-check"></i>&nbsp;
                                        Refresh</a>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="pdfExport()"><i class="fa fa-file"></i>&nbsp;
                                        Pdf</a>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="excelExport()"><i class="fa fa-code"></i>&nbsp;
                                        Export</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" />
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" />
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Time:</label>
                                        <div class="col-sm-8">
                                            <select name="time" id="time" class="form-control">
                                                <option value="Before">Before</option>
                                                <option value="After">After</option>
                                                <option value="AnyTime" selected>AnyTime</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Gender:</label>
                                        <div class="col-sm-8">
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Age(Yd):</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" min="0" value="0" name="minAge" id="minAge"/>
                                        </div>
                                        <label for="" class="col-sm-2">To:</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" min="0" value="0" name="maxAge" id="maxAge"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="proctype" id="proctype" class="form-control">
                                                <option value=""></option>
                                                <option value="Delivery" disabled>Delivery</option>
                                                <option value="Procedure">Procedure</option>
                                                <option value="Medicine">Medicine</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Diagnosis:</label>
                                        <div class="col-sm-8">
                                            <select name="diagnosis" id="diagnosis" class="form-control select2">
                                                <option value=""></option>
                                                @foreach($diagnosisDatas as $diagnosisData)
                                                <option value="{{$diagnosisData->fldcodeid}}">{{$diagnosisData->fldcode}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Range:</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" name="minRange" id="minRange"/>
                                        </div>
                                        <label for="" class="col-sm-2">To:</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" name="maxRange" id="maxRange"/>
                                        </div>
                                    </div> --}}
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">ProcName:</label>
                                        <div class="col-sm-8">
                                            <select name="procname" id="procname" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                        <div class="iq-card-body">
                                            <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                                              <li class="nav-item">
                                                 <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                                              </li>
                                           </ul>
                                           <div class="tab-content" id="myTabContent-1">
                                                <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                                    <div class="table-responsive res-table" style="max-height: none;">
                                                        <table class="table table-striped table-hover table-bordered ">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Index</th>
                                                                    <th>Date</th>
                                                                    <th>EncID</th>
                                                                    <th>Name</th>
                                                                    <th>Age</th>
                                                                    <th>Gender</th>
                                                                    <th>DOReg</th>
                                                                    <th>Patient No</th>
                                                                    <th>Observation</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="table_result">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('after-script')
@include('reports::medicalreport.medical-report-js')
@endpush



