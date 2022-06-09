@extends('frontend.layouts.master')

@section('content')
@php
    $totalamount = 0;
    $can_verify = \App\Utils\Permission::checkPermissionFrontendAdmin('can-verify-demand');
@endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Demand form</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <input type="hidden" name="isQuotationFiltered" id="isQuotationFiltered">
                            <div class="form-row justify-content-between">
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-sm-4">
                                            <input type="checkbox" value="1" name="fldisgenericdemand" id="js-demandform-make-generic-demand">
                                            <label>Make Generic Demand</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Quotation No:</label>
                                        <div class="form-group form-row">
                                            <div class="col-sm-4">
                                                <input type="text" name="fldquotationno" id="js-demandform-quotation-input" value="{{ request('fldquotationno') }}" class="form-control">
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" name="fldorderdate" value="{{ $date }}" class="form-control nepaliDatePicker" id="js-demandform-date-input">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
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
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <div class="col-sm-2">
                                    <label>Department:</label>
                                    <select name="fldpurtype" class="form-control" id="js-demandform-department-select">
                                        <option value="Outside">Outside</option>
                                        <option value="Inside">Inside</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Supplier/Department:</label>
                                    <select name="fldsuppname" class="form-control" id="js-demandform-supplier-select">
                                        <option value="">--Select--</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Address/Category:</label>
                                    <input type="text" readonly class="form-control" id="js-demandform-address-input">
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="js-demandform-isstock-checkbox">
                                        <label class="custom-control-label">Is Stock</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-end" id="js-demandform-item-add-div">
                                <div class="col-sm-2">
                                    <label>Route</label>
                                    <select class="form-control" id="js-demandform-route-select" name="fldroute">
                                        <option value="">--Select--</option>
                                        @foreach($routes as $route)
                                        <option value="{{ $route }}">{{ $route }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Particular</label>
                                    <input type="text"  class="form-control" name="fldstockid" id="js-demandform-medicine-input">
                                </div>
                                <div class="col-sm-1" style="display: none;">
                                    <label>Stock</label>
                                    <input type="text" name="fldstock" readonly class="form-control" id="js-demandform-fldstock-input">
                                    <input type="hidden" name="fldbatch" id="js-demandform-fldbatch-input">
                                </div>
                                <div class="col-sm-1">
                                    <label>Quantity</label>
                                    <input type="number" name="fldquantity" class="form-control" id="js-demandform-fldquantity-input" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                </div>
                                <div class="col-sm-1">
                                    <label>Rate</label>
                                    <input type="number" name="fldrate" class="form-control" id="js-demandform-fldrate-input">
                                </div>
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
                                    {{-- @if(isset($orders))
                                        @foreach($orders as $order)
                                        @php
                                        $date = explode(' ', $order->fldtime_order);
                                        $time = $date[1];
                                        $date = $date[0];
                                        $totalamount += $order->fldtotal;
                                        @endphp
                                        <tr data-fldid="{{ $order->fldid }}">
                                            <td>{{ $loop->iteration }}</td>
                                            @if($can_verify)
                                            <td><input type="checkbox" class="js-demandform-selectall-class-checkbox"></td>
                                            @endif
                                            <td>{{ \App\Utils\Helpers::dateEngToNepdash($date)->full_date }} {{ $time }}</td>
                                            <td>{{ $order->fldsuppname }}</td>
                                            <td>{{ $order->fldstockid }}</td>
                                            <td>{{ $order->fldquantity }}</td>
                                            <td>{{ $order->fldrate }}</td>
                                            <td>{{ $order->fldtotal }}</td>
                                            <td>{{ $order->flduserid_order }}</td>
                                            <td>
                                                <button class="btn btn-danger" onclick="deleteOrder({{ $order->fldid }})">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif --}}
                                    </tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-row form-group align-items-center">
                                    <label class="">Total Amt</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value="{{ $totalamount }}" id="js-demandform-grandtotal-input" readonly>
                                    </div>
                                </div>
                            </div>
                            {{-- @if($can_verify)
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="js-demandform-verify-btn">Verify</button>
                            </div>
                            @endif --}}
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-action btn-warning" id="js-demandform-export-btn"><i class="ri-code-s-slash-line"></i>&nbsp;&nbsp;Export</button>&nbsp;
                                <button class="btn btn-action btn-primary" id="js-demandform-save-btn"><i class="fa fa-check"></i>&nbsp;&nbsp;Save</button>
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
    @if($can_verify)
    <div class="modal fade" id="js-demandform-changequantity-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Change Quantity</h6>
                    <div class="head-content">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <label class="col-sm-4">Quantity</label>
                        <div class="col-sm-8">
                            <input type="hidden" id="js-demandform-fldid-input-modal">
                            <input type="text" class="form-control"  id="js-demandform-fldquantity-input-modal">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" id="js-demandform-updatequantity-btn" class="btn btn-success btn-sm">Save</button>&nbsp;
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="js-demandform-changerate-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h6 class="modal-title">Change Rate</h6>
                    <div class="head-content">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <label class="col-sm-4">Rate</label>
                        <div class="col-sm-8">
                            <input type="hidden" id="js-demandform-fldid-input-modal">
                            <input type="text" class="form-control"  id="js-demandform-fldrate-input-modal">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" id="js-demandform-updaterate-btn" class="btn btn-success btn-sm">Save</button>&nbsp;
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('after-script')
<script type="text/javascript">
    var can_verify = "{{ $can_verify }}";
</script>
<script src="{{asset('js/demand_form.js')}}"></script>
@endpush
