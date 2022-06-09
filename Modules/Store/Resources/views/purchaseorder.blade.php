@extends('frontend.layouts.master')

@section('content')
@php
$totalamount = 0;
$totaltax = 0;
@endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Purchase Order
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $date }}" class="form-control englishDatePicker"  id="js-purchaseorder-date-input" />
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $delivery_date }}" class="form-control englishDatePicker" id="js-purchaseorder-deliverydate-input"  />
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <select class="form-control" id="js-purchaseorder-supplier-select">
                                        <option value="">--Select--</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-row">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" />
                                        <label class="custom-control-label">Show All Entry</label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <input type="text" class="form-control col-sm-4" readonly id="js-purchaseorder-address-input" />
                                    <div class="col-sm-8">
                                        <select class="form-control" id="js-purchaseorder-refrence-select">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group form-row">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="generic" name="type" class="custom-control-input" checked />
                                        <label class="custom-control-label"> Generic </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="brand" name="type" class="custom-control-input" />
                                        <label class="custom-control-label"> Brand </label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <select class="form-control col-sm-8" id="js-purchaseorder-location-select">
                                        <option value="">--Select--</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->flditem }}">{{ $location->flditem }}</option>
                                        @endforeach
                                    </select>&nbsp;
                                    <button type="button" class="btn btn-primary btn-sm" id="js-purchaseorder-add-item-btn"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <div class="col-1">
                                        <input type="checkbox" name="" />
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="js-purchaseorder-route-select">
                                            <option value="">--Select--</option>
                                            @foreach($routes as $route)
                                            <option value="{{ $route }}">{{ $route }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="js-purchaseorder-medicine-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-1">Qty:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="js-purchaseorder-qty-input" onkeydown="if(event.key==='.'){event.preventDefault();}"/>
                                    </div>
                                    <label class="col-sm-1">Stock:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="js-purchaseorder-stock-input" />
                                    </div>
                                    <label class="col-sm-2">Old Rate:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="js-purchaseorder-rate-input" />
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" id="js-purchaseorder-add-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group form-row">
                            <div class="col-sm-2 text-center">
                                <button class="btn btn-primary" id="js-purchaseorder-finalupdate-btn"><i class="fa fa-check"></i>&nbsp;Save</button>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-row">
                                    <label class="col-sm-3">Tax:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="0" readonly value="{{ $totaltax }}" id="js-purchaseorder-taxtotal-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-row">
                                    <label class="col-sm-6">Total Amt:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" placeholder="0" id="js-purchaseorder-grandtotal-input" readonly value="{{ $totalamount }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-container tablefixedHeight">
                            <table class="table table-bordered table-hover table-striped ">
                                <thead class="thead-light">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Vendor</th>
                                        <th>Catogery</th>
                                        <th>Particulars</th>
                                        <th>QTY</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>User</th>
                                        <th>Date</th>
                                        <th>Comp</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="js-purchaseorder-order-tbody">
                                    @if(isset($orders))
                                        @foreach($orders as $order)
                                        @php
                                        $totalamount += $order->fldamt;
                                        @endphp
                                        <tr data-fldid="{{ $order->fldid }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->fldid }}</td>
                                            <td>{{ $order->fldsuppname }}</td>
                                            <td>{{ $order->fldroute }}</td>
                                            <td>{{ $order->flditemname }}</td>
                                            <td>{{ $order->fldqty }}</td>
                                            <td>{{ $order->fldrate }}</td>
                                            <td>{{ $order->fldamt }}</td>
                                            <td>{{ $order->flduserid }}</td>
                                            <td>{{ $order->fldorddate }}</td>
                                            <td>{{ $order->fldcomp }}</td>
                                            <td>
                                                <button class="btn btn-danger" onclick="deleteOrder({{ $order->fldid }})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-purchaseorder-medicine-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Select Particulars</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="text" class="form-control" id="js-purchaseorder-flditem-input-modal" style="width: 80%;float: left;">
                        <button style="float: left;" class="btn btn-sm-in btn-primary" type="button" id="js-purchaseorder-add-btn-modal">
                            <i class="fa fa-plus"></i>&nbsp; Save
                        </button>
                    </div>
                    <div style="overflow-y: auto;max-height: 400px;width: 100%;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                </tr>
                            </thead>
                            <tbody id="js-purchaseorder-table-modal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="js-purchaseorder-add-item-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="text" id="js-purchaseorder-flditem-input-item-modal" style="width: 100%;">
                    </div>
                    <div>
                        <button style="float: left;" class="btn btn-sm-in btn-primary" type="button" id="js-purchaseorder-add-btn-item-modal"><i class="fa fa-plus"></i></button>
                        <button style="float: right;" class="btn btn-sm-in btn-danger" type="button" id="js-purchaseorder-delete-btn-item-modal"><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="js-purchaseorder-table-item-modal"></table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="{{asset('js/purchaseorder_form.js')}}"></script>
@endpush
