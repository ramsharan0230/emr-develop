@extends('frontend.layouts.master')

@section('content')
@php
    $totalamount = 0;
@endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Purchase Order</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-row justify-content-between">
                                <div class="form-group col-sm-4">
                                    <div class="form-row">
                                        <div class="col-sm-5"><label>Quotation No</label></div>
                                        <div class="col-sm-7"><input type="text" name="fldquotationno" id="js-demandform-quotation-input" value="{{ request('fldquotationno') }}" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <input type="text" name="fldorderdate" value="{{ $date }}" class="form-control nepaliDatePicker" id="js-purchaseorder-nepalidate-input">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="ri-calendar-2-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" checked id="js-demandform-showall-checkbox">
                                        <label class="custom-control-label">Show all Entry</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" class="custom-control-input" checked value="generic">
                                        <label class="custom-control-label">Generic</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" class="custom-control-input" value="brand">
                                        <label class="custom-control-label">Brand</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <input type="hidden" name="fldpurtype" id="js-demandform-department-select" value="Outside">
                                <div class="col-sm-6">
                                    <label>Supplier/Department</label>
                                    <select name="fldsuppname" class="form-control" id="js-demandform-supplier-select">
                                        <option value="">--Select--</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Address/Category</label>
                                    <input type="text" readonly class="form-control" id="js-demandform-address-input">
                                </div>
                            </div>
                            <div class="form-group form-row align-items-end" id="js-demandform-medicine-div">
                                <div class="col-sm-2">
                                    <label>Route</label>
                                    <select class="form-control" id="js-demandform-route-select" name="fldroute">
                                        <option value="">--Select--</option>
                                        @foreach($routes as $route)
                                        <option value="{{ $route }}">{{ $route }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <label>Particular</label>
                                    <input type="text"  class="form-control" name="fldstockid" id="js-demandform-medicine-input">
                                </div>
                                <div class="col-sm-1">
                                    <label>Quantity</label>
                                    <input type="number" name="fldquantity" class="form-control" id="js-demandform-fldquantity-input" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                </div>
                                <div class="col-sm-1">
                                    <label>Rate</label>
                                    <input type="number" name="fldrate" class="form-control" id="js-demandform-fldrate-input">
                                </div>
                                <input type="hidden" name="isPurchaseOrder" id="isPurchaseOrder" value="1">
                                <div class="col-sm-2">
                                    <label>Total</label>
                                    <input type="text" name="fldtotal" class="form-control" id="js-demandform-fldtotal-input" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="js-demandform-add-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>

                        <div class="form-group">
                            <div class="table-responsive table-container tablefixedHeight">
                                <table class="table table-bordered table-hover table-striped ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th><input type="checkbox" id="js-demandform-selectall-id-checkbox"></th>
                                            <th>Datetime</th>
                                            <th>Supplier/Department</th>
                                            <th>Particular</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>User</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-demandform-order-tbody">
                                    </tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-2">
                                <div class="form-row form-group align-items-center">
                                    <label class="">Total Amt</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" value="{{ $totalamount }}" id="js-demandform-grandtotal-input" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-row form-group align-items-center">
                                    <label class="">Purchase Order No</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="js-demandform-purchaseno-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7 text-right">
                                <button class="btn btn-warning" id="js-purchaseorderform-export-btn"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
                                <button class="btn btn-primary" id="js-demandform-save-btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-demandform-medicine-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Select Particulars</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="js-demandform-flditem-input-modal">
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-primary" type="button" id="js-demandform-add-btn-modal">
                            <i class="fa fa-plus"></i>&nbsp; Save
                            </button>
                        </div>
                    </div>
                    <div class="res-table mt-2">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2">Particulars</th>
                                </tr>
                            </thead>
                            <tbody id="js-demandform-table-modal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="{{asset('js/billing_purchaseorder_form.js')}}"></script>
@endpush
