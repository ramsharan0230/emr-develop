@extends('frontend.layouts.master')

@push('after-styles')
<style>
.loader-ajax-start-stop2 {
    position: absolute;
    left: 45%;
    top: 35%;
}

.loader-ajax-start-stop-container2 {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    background: black;
    opacity: .5;
    z-index: 999999;
}
</style>
@endpush

@section('content')
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Purchase Entry Edit
                            </h3>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-3">Supplier</label>
                                    <div class="col-sm-9">
                                        <select id="supplier" class="form-control" name="supplier">
                                            <option value="">--Select--</option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier->fldsuppname }}">{{ $supplier->fldsuppname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Ref Order No</label>
                                    <div class="col-sm-8">
                                        <select id="reference" class="form-control" name="reference">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <button class="btn btn-primary btn-action" id="js-purchaseentry-export-btn"><i class="ri-file-pdf-line"></i>&nbsp;Export PDF</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-bordered table-hover table-striped" id="return-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Items</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>MRP</th>
                                    <th>TotCost</th>
                                    <th>VAT AMT</th>
                                    <th>TotQTY</th>
                                    <th>CasDisc</th>
                                    <th>CasBon</th>
                                    <th>QTYBon</th>
                                    <th>CCost</th>
                                    <th>NetCost</th>
                                    <th>SellPr</th>
                                    <th>TotalPr</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="purchaseEntryGrid"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection

@push('after-script')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content")
        }
    });
});

$('#supplier').change( function () {
    var supplier = $('#supplier').val();
    $('#purchaseEntryGrid').empty();
    if (supplier != '' ) {
        $.ajax({
            url: baseUrl + '/purchase-return/reference',
            type: "GET",
            data: {
                supplier: supplier,
            },
            dataType: "json",
            success: function (response) {
                if(response){
                    $('#reference').empty().append(response);
                    $('#route').val("");
                    $('#medicine').html("<option value=''>--Select--</option>");
                    $('#batch').html("<option value=''>--Select--</option>");
                    $('#expiry').val("");
                    $('#carcost').val(0);
                    $('#qty').val(0);
                    $('#retqty').val(0);
                }else {
                    $('#reference').empty().append('<option>Not availlable</option>');
                }

            }
        });
    }
})

$(document).on('change','#reference',function(){
    var supplier = $('#supplier').val();
    var reference = $('#reference').val();
    $.ajax({
        url: baseUrl + '/purchase-entry-edit/get-purchase-entries',
        type: "GET",
        data: {
            supplier: supplier,
            reference: reference,
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                $('#purchaseEntryGrid').empty();
                $.each(response.purchaseEntries, function(i, entry) {
                    var trData = '';
                    trData += '<tr data-fldid="' + entry.fldid + '">';
                    trData += '<td>' + ($('#purchaseEntryGrid tr').length+1) + '</td>';
                    trData += '<input type="hidden" value="' + entry.fldid + '" name="purchaseid" class="purchaseid">';
                    trData += '<input type="hidden" value="' + entry.fldid + '" name="gridpurchaseid">';
                    trData += '<input type="hidden" value="' + (entry.fldtotalqty ? entry.fldtotalqty : '0') + '" name="ordqty" class="ordqty">';
                    trData += '<input type="hidden" value="' + entry.fldrate + '" name="ordrate" class="ordrate">';
                    trData += '<input type="hidden" value="' + entry.fldvatper + '" class="ordvatper">';
                    trData += '<input type="hidden" value="' + entry.fldprofitper + '" class="ordprofitper">';
                    trData += '<td class="stockid">' + entry.fldstockid + '</td>';
                    trData += '<td><input type="text" name="gridbatch" class="form-control grid-batch-input mark-readonly" value="'+ (entry.fldbatch ? entry.fldbatch : '') +'"></input></td>';
                    trData += '<td><input type="date" name="gridexpiry" class="form-control grid-expiry-input mark-readonly" value="'+ (entry.fldexpiry ? entry.fldexpiry.slice(0,10) : '') +'"></input></td>';
                    trData += '<td style="width:auto;"><input type="number" name="gridnetcost" class="form-control grid-netcost-input mark-readonly" value="'+ (entry.fldrate ? entry.fldrate : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridsuppcost" class="form-control grid-suppcost-input mark-readonly" value="'+ (entry.flsuppcost ? entry.flsuppcost : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridvatamt" class="form-control grid-vatamt-input mark-readonly" value="'+ (entry.fldvatamt ? entry.fldvatamt : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridtotalqty" class="form-control grid-totalqty-input mark-readonly" value="'+ (entry.fldtotalqty ? entry.fldtotalqty : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridcashdisc" class="form-control grid-cashdisc-input mark-readonly" value="'+ (entry.fldcasdisc ? entry.fldcasdisc : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridcashbonus" class="form-control grid-cashbonus-input mark-readonly" value="'+ (entry.fldcasbonus ? entry.fldcasbonus : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridqtybonus" class="form-control grid-qtybonus-input mark-readonly" value="'+ (entry.fldqtybonus ? entry.fldqtybonus : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridcarcost" class="form-control grid-carcost-input mark-readonly" value="'+ (entry.fldcarcost ? entry.fldcarcost : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridcurrcost" class="form-control grid-currcost-input mark-readonly" value="'+ (entry.fldnetcost ? entry.fldnetcost : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridsellprice" class="form-control grid-sellprice-input mark-readonly" value="'+ (entry.fldsellprice ? entry.fldsellprice : '0') +'"></input></td>';
                    trData += '<td><input type="number" name="gridtotalprice" class="form-control grid-totalprice-input mark-readonly" value="'+ (entry.fldtotalcost ? entry.fldtotalcost : '0') +'"></input></td>';
                    trData += '<td class="action-td">'+
                                '<button class="btn btn-success" onclick="editentry('+entry.fldid+','+entry.entryqty+','+entry.fldqtybonus+','+entry.fldtotalqty+',this)"><i class="fa fa-edit" aria-hidden="true"></i></button>'+
                                '<button class="btn btn-danger" onclick="deleteentry(' + entry.fldid + ','+entry.entryqty+','+entry.fldqtybonus+','+entry.fldtotalqty+',this)"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
                            '</td>';
                    trData += '</tr>';
                    $('#purchaseEntryGrid').append(trData);
                });
                $('.mark-readonly').prop('readonly',true);
            }
        }
    });
});

function editentry(fldid,entryqty,bonusqty,purchaseqty,currelem){
    $.ajax({
        url: baseUrl + '/purchase-entry-edit/edit',
        type: "GET",
        data: {
            fldid: fldid
        },
        dataType: "json",
        success: function (response) {
            if (response.status) {
                if(!$(currelem).hasClass('editshow')){
                    if(response.isDispensed == "Yes"){
                        showAlert("Warning!!! This Item cannot be edited.","error");
                    }else{
                        $(currelem).closest('.action-td').append('<button class="btn btn-warning updateButton" onclick="updateentry('+fldid+',this)">Update</button>')
                        $(currelem).closest('tr').find('.mark-readonly').prop("readonly",false);
                        $(currelem).addClass('editshow');
                    }
                }
            }
        }
    });
}

function updateentry(fldid,currelem){
    var batch = $(currelem).closest('tr').find('.grid-batch-input').val();
    var expiry = $(currelem).closest('tr').find('.grid-expiry-input').val();
    var netcost = $(currelem).closest('tr').find('.grid-currcost-input').val();
    var suppcost = $(currelem).closest('tr').find('.grid-suppcost-input').val();
    var vatamt = $(currelem).closest('tr').find('.grid-vatamt-input').val();
    var totalqty = $(currelem).closest('tr').find('.grid-totalqty-input').val();
    var cashdisc = $(currelem).closest('tr').find('.grid-cashdisc-input').val();
    var cashbonus = $(currelem).closest('tr').find('.grid-cashbonus-input').val();
    var qtybonus = $(currelem).closest('tr').find('.grid-qtybonus-input').val();
    var carcost = $(currelem).closest('tr').find('.grid-carcost-input').val();
    var sellprice = $(currelem).closest('tr').find('.grid-sellprice-input').val();
    var totalprice = $(currelem).closest('tr').find('.grid-totalprice-input').val();
    $.ajax({
        url: baseUrl + '/purchase-entry-edit/update',
        type: "POST",
        data: {
            fldid: fldid,
            batch: batch,
            expiry: expiry,
            netcost: netcost,
            suppcost: suppcost,
            vatamt: vatamt,
            totalqty: totalqty,
            cashdisc: cashdisc,
            cashbonus: cashbonus,
            qtybonus: qtybonus,
            carcost: carcost,
            sellprice: sellprice,
            totalprice: totalprice,
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                $(currelem).closest('.action-td').find('.btn-warning').remove();
                $('#reference').change();
            }
        }
    });
}

function deleteentry(fldid,entryqty,bonusqty,purchaseqty,currelem){

    $.ajax({
        url: baseUrl + '/purchase-entry-edit/delete',
        type: "GET",
        data: {
            fldid: fldid,
        },
        dataType: "json",
        success: function (response) {

            if(response.isDispensed == "Yes"){
                showAlert("Warning!!! This Item cannot be deleted.","error");
                return false;
            }
            var really = confirm("You really want to delete this Entry?");
            if(!really) {
                return false
            } else {
                destoryentry(fldid);
            }

        }
    });
}
function destoryentry(fldid){
    $.ajax({
        url: baseUrl + '/purchase-entry-edit/destory',
        type: "POST",
        data: {
            fldid: fldid,
        },
        dataType: "json",
        success: function (response) {
            if(response.status){
                $('#reference').change();
                }
            }
        });
}
$(document).on('click','#js-purchaseentry-export-btn',function(){
    var refno = $('#reference').val();
    if (refno != '')
        window.open(baseUrl + '/purchaseentry/export?fldreference=' + refno, '_blank');
});

</script>
@endpush
