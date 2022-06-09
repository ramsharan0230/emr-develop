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
                                Items Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="item_filter_data">
                            <div class="form-row">
                                {{-- <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="invoice_date" name="selectDate" class="custom-control-input" checked/>
                                            <label class="custom-control-label" for=""> Invoice Date </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">From:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="from_date" id="from_date_eng">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Depart:</label>
                                        <div class="col-sm-7">
                                            <select name="comp" id="comp" class="form-control department">
                                                <option value="%">%</option>
                                                @if(\Illuminate\Support\Facades\Session::get('user_hospital_departments'))
                                                    @forelse(\Illuminate\Support\Facades\Session::get('user_hospital_departments') as $dept)
                                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}}</option>
                                                    @empty
                                                    @endforelse
                                                @endif

{{--                                                @if($hospital_department)--}}
{{--                                                    @forelse($hospital_department as $dept)--}}
{{--                                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>--}}
{{--                                                    @empty--}}
{{--                                                    @endforelse--}}
{{--                                                @endif--}}
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Departments:</label>
                                        <div class="col-sm-8">
                                            <select name="departments" id="departments" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($departments))
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->flddept}}">{{$department->flddept}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Category:</label>
                                        <div class="col-sm-8">
                                            <select name="category" id="category" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Diagnostic Tests">Diagnostic Tests</option>
                                                <option value="Equipment">Equipment</option>
                                                <option value="Extra Items">Extra Items</option>
                                                <option value="General Services">General Services</option>
                                                <option value="Medicines">Medicines</option>
                                                <option value="Other Items">Other Items</option>
                                                <option value="Radio Diagnostics">Radio Diagnostics</option>
                                                <option value="Surgicals">Surgicals</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline"> --}}
                                            <input type="radio" value="entry_date" name="selectDate" class="custom-control-input" checked/>
                                            {{-- <label class="custom-control-label" for=""> Entry Date </label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="to_date" id="to_date_eng">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Billing Mode:</label>
                                        <div class="col-sm-7">
                                            <select name="billing_mode" id="billing_mode" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($billingset))
                                                    @foreach($billingset as $b)
                                                        <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                <div class="d-flex justify-content-end">

                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Category
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"  type="button"  onclick="exportCatWiseItemExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                                <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportCatWiseItemReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                            </div>
                                        </div>
                                    &nbsp;
                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Items
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" type="button"  onclick="exportItemParticularExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>

                                            <a  class="dropdown-item" href="javascript:void(0);" type="button"  onclick="exportItemParticularReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>

                                            </div>
                                        </div>
                                        &nbsp;
                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Details
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item"  type="button"  onclick="exportDetailExcel()"><i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a  class="dropdown-item" href="javascript:void(0);" type="button" onclick="exportDetailReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>

                                            </div>
                                        </div>
                                        &nbsp;
                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Dates
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" type="button"  onclick="exportDatesExcel()"><i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a class="dropdown-item" href="javascript:void(0);" type="button"   onclick="exportDatesReport()"><i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>

                                            </div>
                                        </div>
                                        &nbsp;
                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Visits
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" type="button"  onclick="exportVisitsExcel()"><i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a class="dropdown-item" href="javascript:void(0);" type="button" onclick="exportVisitsReport()"  ><i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>

                                            </div>
                                        </div>
                                        &nbsp;
                                        <div class="dropdown" >
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                   Patients
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" type="button"  onclick="exportDatewiseExcel()"><i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a class="dropdown-item" href="javascript:void(0);" type="button" onclick="openPatientModal()"  ><i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>

                                            </div>
                                        </div>
                                    </div>
                                    </div>

                                <!-- <div class="col-sm-12">
                                    <div class="form-group text-right">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportCatWiseItemReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                            Category</a>
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportItemParticularReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                            Items</a>
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDetailReport()"><i class="fa fa-file"></i>&nbsp;
                                            Details</a>&nbsp;
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDatesReport()"><i class="fa fa-calendar"></i>&nbsp;
                                            Dates</a>&nbsp;
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="openPatientModal()"><i class="fa fa-venus"></i>&nbsp;
                                            Patient</a>&nbsp;
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportVisitsReport()"><i class="fa fa-code"></i>&nbsp;
                                            Visits</a>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group" style="text-align: center;">
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="loadData()"><i class="fas fa-dot-circle"></i>&nbsp;
                                Load Data</a>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="itemRadio" id="inlineRadio1" value="select_item" checked>
                                <label class="form-check-label" for="inlineRadio1"> Select Item </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="itemRadio" id="inlineRadio2" value="all_items">
                                <label class="form-check-label" for="inlineRadio2"> All Items </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="itemRadio" id="inlineRadio3" value="packages">
                                <label class="form-check-label" for="inlineRadio3"> Packages </label>
                            </div>
                            <!-- <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="select_item" name="itemRadio" class="custom-control-input" checked/>
                                <label class="custom-control-label"> Select Item </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="all_items" name="itemRadio" class="custom-control-input"/>
                                <label class="custom-control-label"> All Items </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="packages" name="itemRadio" class="custom-control-input"/>
                                <label class="custom-control-label"> Packages </label>
                            </div> -->
                        </div>
                        <div class="form-group">
                            <div class="iq-search-bar custom-search">
                                <div class="searchbox">
                                    <input type="hidden" name="selectedItem" id="selectedItem">
                                    <input type="text" id="medicine_listing" name="" class="text search-input" placeholder="Type here to search..."/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="res-table mb-2" style="max-height: 300px; min-height: 300px;">
                                <table id="item-table" class="table-bordered" style="width:100%"php>
                                    <tbody id="item-listing-table">
                                    </tbody>
                                    <tbody id="package-listing-table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex float-right">
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getRefreshData()"><i class="fas fa-sync"></i>&nbsp;
                                    Refresh</a>&nbsp;
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportItemReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                    Export</a>&nbsp;
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="excelItemReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                    Excel</a>&nbsp;
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDatewiseReport()"><i class="fa fa-calendar"></i>&nbsp;
                                    Datewise</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active res-table" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive" style="max-height: none;">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Encounter</th>
                                            <th>Patient Name</th>
                                            <th>Particulars</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>Disc</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Entry Date</th>
                                            <th>Invoice</th>
                                            {{-- <th>TP Bill</th> --}}
                                            {{-- <th>Payable</th>
                                            <th>Referral</th> --}}
                                        </tr>
                                        </thead>
                                        <tbody id="item_result">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="patientModal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Cut Off Amount:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="number" name="cut_off_amount" id="cut_off_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submitModal">Ok
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    @include('reports::itemreport.item-report-js')
@endpush



