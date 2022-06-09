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

        .select-editable {position:relative; background-color:white; border:solid grey 1px;  width:125px; height:25px;}
        .select-editable select {position:absolute; top:0px; left:0px; font-size:14px; border:none; width:120px; margin:0;}
        .select-editable input {position:absolute; top:0px; left:0px; width:100px; padding:1px; font-size:12px; border:none;}
        .select-editable select:focus, .select-editable input:focus {outline:none;}

        .grey-box {
            min-height: 435px !important;
            border: 1px solid #d3d3d3;
            margin: auto;
        }
        .btn-outline-primary:active, .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: #144069;
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
                            Group Report
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-row">
                                    {{-- <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="invoice_date" name="selectDate" class="custom-control-input" checked/>
                                        <label class="custom-control-label" for=""> Invoice Date </label>
                                    </div> --}}
                                     <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="entry_date" name="selectDate" class="custom-control-input" checked style="display: hidden;"/>
                                        {{-- <label class="custom-control-label" for=""> Entry Date </label> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">From:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Comp:</label>
                                    <div class="col-sm-8">
                                        <select name="comp" id="comp" class="form-control department">
                                            <option value="%">%</option>
                                            @if($hospital_department)
                                                @forelse($hospital_department as $dept)
                                                    <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="select_item" name="itemRadio" class="custom-control-input" checked/>
                                    <label class="custom-control-label" for=""> Select Item </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="all_items" name="itemRadio" class="custom-control-input" />
                                    <label class="custom-control-label" for=""> All Items </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">To:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Mode:</label>
                                    <div class="col-sm-8">
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
                            <div class="col-sm-2">
                               <div class="form-row">
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="loadData()"><i class="fas fa-dot-circle"></i>&nbsp;
                                    Load Data</a>
                               </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-row float-right">
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Category
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateCategoryExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportCatWiseItemReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportCatWiseItemReport()"><i class="fa fa-code"></i>&nbsp;
                                    Category</a>&nbsp; -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Items
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateItemsExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportItemParticularReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportItemParticularReport()"><i class="fa fa-code"></i>&nbsp;
                                    Items</a>&nbsp; -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Details
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateDetailsExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportDetailReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportDetailReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                    Details</a>&nbsp; -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Dates
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateDatewiseExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportDatesReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportDatesReport()"><i class="fa fa-file"></i>&nbsp;
                                    Dates</a>&nbsp; -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Patient
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generatePatientsExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportPatientReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportPatientReport()"><i class="fa fa-venus"></i>&nbsp;
                                    Patient</a>&nbsp; -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Visits
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateVisitsExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>
                                            <a href="javascript:void(0);" type="button" class="dropdown-item"  onclick="exportVisitsReport()"> <i class="fa fa-file-pdf"></i>&nbsp; Pdf </a>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <!-- <a href="javascript:void(0);" type="button" class="btn btn-outline-primary btn-action" onclick="exportVisitsReport()"><i class="fa fa-code"></i>&nbsp;
                                    Visits</a> -->
                                    <div class="dropdown" >
                                        <button class="btn btn-outline-primary dropdown-toggle dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Summary
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"  type="button"  onclick="generateSummaryExcel()"> <i class="fas fa-file-excel"></i>&nbsp;Excel  </a>                                            
                                        </div>
                                    </div>
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group text-right">
                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="createGroup()"><i class="fa fa-plus"></i>&nbsp;
                        Create Group</a>
                    </div>
                    <div class="form-group">
                        <div class="iq-search-bar custom-search">
                            <div class="searchbox">
                                <input type="hidden" name="selectedItem" id="selectedItem">
                                <input type="text" id="listing" name="" class="text search-input" placeholder="Type here to search..." />
                            </div>
                        </div>
                        <div class="res-table mb-2" style="min-height: 300px;">
                            <table id="group-table">
                                <tbody id="listing-table">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <div class="form-group">
                                 <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getRefreshData()"><i class="fas fa-sync"></i>&nbsp;Refresh</a>
                                  <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportItemReport()"><i class="fa fa-file-pdf"></i>&nbsp;Export</a>
                                  <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportSummaryReport()"><i class="fa fa-file"></i>&nbsp; Summary</a>
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDatewiseReport()"><i class="fa fa-calendar"></i>&nbsp;Datewise</a>

                              </div>
                        </div>
                         <div class="d-flex justify-content-between">
                            <div class="form-group">
                                 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                      <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                      <li class="nav-item">
                         <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                      </li>
                   </ul>
                   <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="res-table table-responsive">
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
                                            <th>Payable</th>
                                            <th>Referral</th>
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
       <!--  <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="item_filter_data">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="invoice_date" name="selectDate" class="custom-control-input" checked/>
                                            <label class="custom-control-label" for=""> Invoice Date </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="entry_date" name="selectDate" class="custom-control-input" />
                                            <label class="custom-control-label" for=""> Entry Date </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-center mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="createGroup()"><i class="fa fa-plus"></i>&nbsp;
                                        Create Group</a>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-center mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="loadData()"><i class="fas fa-dot-circle"></i>&nbsp;
                                        Load Data</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="select_item" name="itemRadio" class="custom-control-input" checked/>
                                            <label class="custom-control-label" for=""> Select Item </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="all_items" name="itemRadio" class="custom-control-input" />
                                            <label class="custom-control-label" for=""> All Items </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="iq-search-bar custom-search">
                                        <div class="searchbox">
                                            <input type="hidden" name="selectedItem" id="selectedItem">
                                            <input type="text" id="listing" name="" class="text search-input" placeholder="Type here to search..." />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 res-table mb-2" style="min-height: 300px;">
                                    <table id="group-table">
                                        <tbody id="listing-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="getRefreshData()"><i class="fas fa-check"></i>&nbsp;
                                        Refresh</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportItemReport()"><i class="fa fa-code"></i>&nbsp;
                                        Export</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportSummaryReport()"><i class="fa fa-code"></i>&nbsp;
                                        Summary</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDatewiseReport()"><i class="fa fa-code"></i>&nbsp;
                                        Datewise</a>
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
                                </div>

                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Comp:</label>
                                        <div class="col-sm-8">
                                            <select name="comp" id="comp" class="form-control department">
                                                <option value="%">%</option>
                                                {{-- @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ isset($dept->departmentData->fldcomp) ? $dept->departmentData->fldcomp : "%" }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @empty
                                                    @endforelse
                                                @endif --}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Mode:</label>
                                        <div class="col-sm-8">
                                            <select name="billing_mode" id="billing_mode" class="form-control">
                                                <option value="%">%</option>
                                                {{-- @if(isset($billingset))
                                                    @foreach($billingset as $b)
                                                        <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                    @endforeach
                                                @endif --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportCatWiseItemReport()"><i class="fa fa-code"></i>&nbsp;
                                                Category</a>
                                            </div>
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportItemParticularReport()"><i class="fa fa-code"></i>&nbsp;
                                                Items</a>
                                            </div>
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDetailReport()"><i class="fa fa-code"></i>&nbsp;
                                                Details</a>&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDatesReport()"><i class="fa fa-code"></i>&nbsp;
                                                Dates</a>&nbsp;
                                            </div>
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportPatientReport()"><i class="fa fa-code"></i>&nbsp;
                                                Patient</a>&nbsp;
                                            </div>
                                            <div class="d-flex mb-1">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportVisitsReport()"><i class="fa fa-code"></i>&nbsp;
                                                Visits</a>&nbsp;
                                            </div>
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
                                                                    <th>Payable</th>
                                                                    <th>Referral</th>
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
        </div> -->
    </div>

     <div class="modal fade" id="groupModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Create Group</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group form-row">
                        <label for="" class="col-sm-2">Group Name:</label>
                        <div class="col-sm-5">
                            <div class="select-editable full-width">
                                <select onchange="this.nextElementSibling.value=this.value" id="groupOptions" class="full-width" style="background: none;">
                                </select>
                                <input style="padding: 0; width:calc(100% - 40px);" type="text" name="group_name" id="group_name" class="" value=""/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="loadGroup()"><i class="fa fa-search"></i></a>
                                <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="addGroup()"><i class="fa fa-plus"></i></a>
                                <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="groupReport()"><i class="fas fa-bars"></i> Lists</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group res-table">
                        <table id="group-table-list" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Particulars</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="group-listing-table">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addGroupModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <div class="col-sm-12">
                                    <input type="hidden" name="groupName" id="groupName" class="form-control full-width">
                                    <select name="groupcategory" id="groupcategory" class="form-control full-width">
                                        <option value=""></option>
                                        <option value="Diagnostic Tests">Diagnostic Tests</option>
                                        <option value="General Services">General Services</option>
                                        <option value="Procedures">Procedures</option>
                                        <option value="Equipment">Equipment</option>
                                        <option value="Radio Diagnostics">Radio Diagnostics</option>
                                        <option value="Other Items">Other Items</option>
                                        <option value="Medicines">Medicines</option>
                                        <option value="Surgicals">Surgicals</option>
                                        <option value="Extra Items">Extra Items</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <div class="col-sm-12">
                                    <input type="checkbox" id="selectAllItemLists"> Select All
                                </div>
                            </div>
                            <div class="iq-search-bar custom-search mb-2">
                                <div class="searchbox">
                                    <input type="text" id="searchItemListing" class="text form-control" placeholder="Type here to search..." />
                                </div>
                            </div>
                            <div class="row grey-box res-table">
                                <div class="col-sm-12">
                                    <table id="item-list" class="table">
                                        <tbody id="item-listing-table">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <a href="javascript:void(0);" class="btn btn-primary selectItemName"><i class="fas fa-angle-right"></i></a>
                                </div>
                            </div>
                            <div class="row grey-box res-table">
                                <div class="col-sm-12">
                                    <table id="item-selected-list" class="table">
                                        <tbody id="item-selected-listing-table">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok
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
@include('reports::groupreport.group-report-js')
@endpush



