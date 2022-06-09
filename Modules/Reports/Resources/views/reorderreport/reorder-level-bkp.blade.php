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
                            Reorder Level Report
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
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Category:</label>
                                        <div class="col-sm-8">
                                            <select name="category" id="category" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Medicines">Medicines</option>
                                                <option value="Surgicals">Surgicals</option>
                                                <option value="Extra Items">Extra Items</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Billing Mode:</label>
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
                                            <input type="text" id="medicine_listing" name="" class="text search-input" placeholder="Type here to search..." />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 res-table mb-2" style="min-height: 300px;">
                                    <table id="item-table">
                                        <tbody id="item-listing-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mb-2">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="getRefreshData()"><i class="fas fa-check"></i>&nbsp;
                                        Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
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

                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Comp:</label>
                                        <div class="col-sm-8">
                                            <select name="comp" id="comp" class="form-control department">
                                                <option value="%">%</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ isset($dept->departmentData->fldcomp) ? $dept->departmentData->fldcomp : "%" }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @empty
                                                    @endforelse
                                                @endif
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
                                                                    <th>Item</th>
                                                                    <th>Reorder Level</th>
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
                        </div>
                    </div>
                    </form>
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
@include('reports::reorderreport.reorder-report-js')
@endpush



