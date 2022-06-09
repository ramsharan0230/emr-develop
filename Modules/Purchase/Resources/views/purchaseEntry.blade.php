@extends('frontend.layouts.master')

@section('content')

<style>
    .total-detail tr td:first-child{
        text-align: right;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Purchase Entry</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="{{ route('import.purchase.entry') }}" method="post" enctype="multipart/form-data" id="importPurchaseEntryForm">
                            @csrf
                            <input type="file" name="import-purchase-entry" style="display: none" id="purchaseEntryFile">
                            <button type="submit" id="importSubmit" style="display: none"></button>
                        </form>
                        <form id="js-purchaseentry-form">
                            <div class="form-row">
                                <div class="form-group mr-3">
                                    <div class="input-group">
                                        <input type="text" value="{{ $date }}" name="fldpurdate" class="form-control markreadonly englishDatePicker"  id="js-purchaseentry-date-input" />
                                    </div>
                                </div>
                                <div class="form-group mr-3">
                                    <input type="checkbox" name="isOpeningStock" value="1" id="isOpeningStock">
                                    <label for="isOpeningStock">Is Opening Stock</label>
                                    <a href="{{ route('download.excel.format') }}" class="btn btn-primary text-white" style="display: none" id="download-purchaseentry-format">Download Excel Format</a>
                                    <a class="btn btn-primary text-white" style="display: none" id="import-purchaseentry">Import</a>
                                </div>
                                <div class="form-group mr-3">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" >
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
                                <div class="col-sm-2">
                                    <label>Payment</label>
                                    <select id="js-purchaseentry-payment-type-select" name="fldpurtype" class="form-control markreadonly">
                                        <option value="">--Select--</option>
                                        <option value="Cash Payment">Cash Payment</option>
                                        <option value="Credit Payment">Credit Payment</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Billno</label>
                                    <input type="text" id="js-purchaseentry-billno-input" name="fldbillno" class="form-control markreadonly">
                                </div>
                                <div class="col-sm-3">
                                    <label>Supplier</label>
                                    <select class="form-control markreadonly" name="fldsuppname" id="js-purchaseentry-supplier-select">
                                        <option value="">--Select--</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Ref Order No</label>
                                    <select class="form-control markreadonly" name="fldreference" id="js-purchaseentry-reforderno-select">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Address</label>
                                    <input type="text" id="js-purchaseentry-address-input" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center">
                                <!-- <div class="col-sm-2">
                                    <label>Route</label>
                                    <select class="form-control markreset" name="route" id="js-purchaseentry-route-select">
                                        <option value="">--Select--</option>
                                        @foreach($routes as $route)
                                        <option value="{{ $route }}">{{ $route }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-sm-3">
                                    <label>Items <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control markreset" name="fldstockid" id="js-purchaseentry-medicine-input" />
                                </div>
                                <div class="col-sm-3">
                                    <label>Batch<span class="text-danger">*</span></label>
                                    <input type="text" name="fldbatch" id="js-purchaseentry-batch-input" class="form-control markreset">
                                </div>
                                <div class="col-sm-2">
                                    <label>Expiry</label>
                                    <input type="date" name="fldexpiry" class="form-control markreset" min="{{$minexpirydate}}" value="{{ $delivery_date }}" id="js-purchaseentry-expiry-input" />
                                </div>
                                <input type="hidden" name="ordfldid" id="ordfldid">
                                <div class="col-sm-2">
                                    <label>Vat</label>
                                    <select class="form-control markreset" id="js-purchaseentry-tax-inex-select">

                                        <option value="Exclusive" selected="selected">Exclusive</option>
                                        <option value="Inclusive">Inclusive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Total Cost<span class="text-danger">*</span></label>
                                        <div class="col-sm-5">
                                            <input type="hidden" class="markreset" id="js-purchaseentry-rate-input">
                                            <input type="text" placeholder="0" class="form-control markreset" id="js-purchaseentry-totalcost-input" name="fldsubtotal">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Vat</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fldvatamt" placeholder="0" class="form-control markreset" id="js-purchaseentry-vat-input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Max R price</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" id="js-purchaseentry-maxrprice-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">QTY Bonus</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" name="fldqtybonus" class="form-control markreset js-number-validation" id="js-purchaseentry-qtybonus-input" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Dist Unit Cost</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" name="flsuppcost" class="form-control markreset js-number-validation" id="js-purchaseentry-distunitcost-input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Profit %</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" id="js-purchaseentry-profitpercentage-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label>Total Amt. Inc VAT</label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Cash Disc</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldcasdisc" id="js-purchaseentry-cashdisc-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Carry Cost Amt</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldcarcost" id="js-purchaseentry-carrycostpercentage-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Curr Sell Price</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldcurrcost" id="js-purchaseentry-currsellprice-input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Purchase QTY<span class="text-danger">*</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldtotalqty" id="js-purchaseentry-totalqty-input" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Total QTY</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldtotalentryqty" id="js-purchaseentry-totalentryqty-input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <input type="text" placeholder="0" class="form-control markreset js-number-validation" id="js-purchaseentry-amtaftervat-input" name="fldtotalcost" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Bonus %</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldcashbonus" id="js-purchaseentry-bonuspercentage-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Net Unit Cost</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldnetcost" id="js-purchaseentry-netunitcost-input" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">New Sell Price<span class="text-danger">*</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset js-number-validation" name="fldsellprice" id="js-purchaseentry-newsellprice-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-7">Bar Code</label>
                                        <div class="col-sm-5">
                                            <input type="text" placeholder="0" class="form-control markreset" name="fldbarcode" id="js-purchaseentry-barcode-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-primary btn-sm" id="js-purchaseentry-add-btn"><i class="ri-add-line"></i>&nbsp;Add</button>
                                </div>
                            </div>
                        </form>

                        <div class="form-group mb-3">
                            <div class="table-responsive table-container tablefixedHeight">
                                <table class="table table-bordered table-hover table-striped ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Items</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Net Cost</th>
                                            <th>Unit Cost</th>
                                            <th>VAT AMT</th>
                                            <th>TotQTY</th>
                                            <th>CasDisc</th>
                                            <th>CasBon</th>
                                            <th>QTYBon</th>
                                            <th>CCost</th>
                                            <th>MRP</th>
                                            <th>DistCost</th>
                                            <th>SellPr</th>
                                            <th>Sub Total</th>
                                            <th>Total Cost</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-purchaseentry-entry-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-2">
                        &nbsp;
                        </div>

                        <div class="col-sm-5">
                        <table class="table table-borderless total-detail">
                        <tr>
                            <td>
                            <label class="pl-2">Vatable Amount

                            </label>
                            </td>
                            <td>
                            <input type="text" class="form-control text-right" id="js-purchaseentry-vatableamt-input" value="0" readonly>
                            <input type="hidden" class="form-control text-right" id="js-purchaseentry-vatableamt-input-vtt" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <label class="pl-2">Non Vatable Amount</label>

                            </td>
                            <td>
                            <input type="text" class="form-control text-right" id="js-purchaseentry-nonvatableamt-input" value="0" readonly>
                            <input type="hidden" class="form-control text-right" id="js-purchaseentry-nonvatableamt-input-vtt" value="0" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <label class="pl-2">Ref No</label>
                            </td>
                            <td>
                            <input type="text" class="form-control text-right" id="js-purchaseentry-refno-input" placeholder="0">
                            </td>
                        </tr>

                        <tr>
                        <td colspan="2" style="text-align:right;">
                            <button class="btn btn-primary btn-action" id="js-purchaseentry-export-btn"><i class="ri-file-pdf-line"></i>&nbsp;Export PDF</button>
                            <button class="btn btn-primary btn-action" id="js-purchaseentry-export-excel-btn"><i class="ri-code-s-slash-line"></i>&nbsp;Export Excel</button>
                        </td>
                        </tr>
                        </table>
                        </div>
                        <div class="col-sm-5">
                            <table class="table table-borderless total-detail">
                            <tr>
                            <td> <label class="pl-2">Sub Total</label></td>
                            <td><input type="text" class="form-control text-right" id="js-purchaseentry-subtotal-input" placeholder="0" disabled></td>
                            </tr>
                            <tr>
                            <td> <label class="pl-2">Discount</label></td>
                            <td>
                            <input type="number" class="form-control text-right" id="js-purchaseentry-discount-input" placeholder="0" name="groupdiscount">
                            </td>
                            </tr>
                            <tr>
                            <td> <label class="pl-2">Individual Tax</label></td>
                            <td>
                            <input type="number" class="form-control text-right" id="js-purchaseentry-totaltax-input" placeholder="0" name="individualtax" disabled>
                            </td>
                            </tr>
                            <tr>
                            <td> <label class="pl-2">Group Tax</label></td>

                            <td>

                            <div class="row">
                            <div class="col-sm-3 pt-1">
                            <input  type="checkbox" id="grouptaxon" onclick="grouptaxon()" name="grouptaxon" value="">
                            <span>13%</span>
                            </div>
                            <div class="col-sm-9">
                            <!-- <input type="number" class="form-control text-right" id="js-purchaseentry-grouptax-percent" placeholder="13" name="grouptax" value="13" readonly> -->
                            <input type="number" class="form-control text-right" id="js-purchaseentry-grouptax-input" placeholder="0" name="grouptax" value="" disabled>
                            </div>
                            </div>
                            </td>
                            </tr>

                            <tr>
                            <td>
                            <label class="pl-2">Carry Cost</label>
                            </td>
                            <td>
                            <input type="number" class="form-control text-right" id="js-purchaseentry-ccost-input" placeholder="0" disabled>
                            </td>
                            </tr>
                            <tr>
                            <td>
                            <label class="pl-2">Amount</label>
                            </td>
                            <td>
                            <input type="text" class="form-control text-right" id="js-purchaseentry-totalamt-input" placeholder="0" disabled>
                            </td>
                            </tr>
                            <tr>
                            <td>
                            <label class="pl-2">Total Amt</label>
                            </td>
                            <td>
                            <input type="text" class="form-control text-right" id="js-purchaseentry-amt-input" placeholder="0" disabled>
                            </td>
                            </tr>

                            </table>
                        </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary btn-action" id="js-purchaseentry-finalsave-btn" title="Final Save"><i class="fa fa-check"></i>&nbsp;Final Save</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade show" id="js-purchaseentry-medicine-modal">
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
                            <input type="text" class="form-control" id="js-purchaseentry-flditem-input-modal" autofocus>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-primary" type="button" id="js-purchaseentry-add-btn-modal">
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
                            <tbody id="js-purchaseentry-table-modal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script>

function grouptaxon() {
    var checkBox = document.getElementById("grouptaxon");
    var totalamt = $('#js-purchaseentry-totalamt-input').val() || 0;

    if (checkBox.checked == true){
        amt = (13/100)*parseFloat(totalamt);
        $('#js-purchaseentry-grouptax-input').val(amt);
        $('#grouptaxon').val(1);
        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');

    } else {
        $('#grouptaxon').val(0);
        $('#js-purchaseentry-grouptax-input').val(0);
        $('#js-purchaseentry-grouptax-input').attr('readonly','readonly');
        $('#js-purchaseentry-totaltax-input').attr('readonly','readonly');
    }


    var vatamt = $('#js-purchaseentry-grouptax-input').val() || 0;

    var discountedamt = Number(totalamt) + Number(vatamt);
    $('#js-purchaseentry-amt-input').val(discountedamt);


    changeTaxAmt();


  }


    var directPurchaseEntry = "{{Options::get('direct_purchase_entry')}}";
    var expiry = "{{$delivery_date}}";
</script>
<script src="{{asset('js/purchaseentry_form.js')}}"></script>
@endpush
