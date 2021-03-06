@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Voucher Reports
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Voucher No. :<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="voucherNo" id="voucherNo">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary btn-action" id="searchVoucher"><i class="fa fa-search"></i>&nbsp;Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="voucherReportDiv" style="display: none;">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header justify-content-between mt-3">
                    <button class="btn btn-primary btn-action float-right ml-1" onclick="printVoucherDetails()"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button type="button" class="btn btn-primary btn-action float-right" onclick="exportVoucherDetails()"><i class="fa fa-arrow-circle-down"></i>
                        Export
                    </button>&nbsp;
                </div>
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Voucher No. : <b id="voucherNumber"></b></p>
                            </div>
                            <div class="col-sm-6">
                                <p>Date: <b>{{$date}}</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="table-responsive res-table">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Acc No.</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Dr Amount</th>
                                        <th class="text-center">Cr Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="voucher-details">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <section>
                    <div>
                        <div style="width: 30%; margin-left: 2rem; float: left">
                            <p id="enteredUser"></p>
                            <p>_________________________</p>
                            <p>Entered By : </p>
                        </div>
                        <div style="width: 30%; margin-left: 2rem; float: left">
                            <p id="generatedUser"></p>
                            <p>_________________________</p>
                            <p>Generated By : </p>
                        </div>
                        <div style="width: 30%; margin-left: 2rem; float: left">
                            <p style="margin-top: 35px">_________________________</p>
                            <p>Approved By : </p>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </section>        --}}
            </div>
        </div>
    </div>
@endsection
@push('after-script')
<script>
$(document).on('click','#searchVoucher',function(){
    if($('#voucherNo').val() != ""){
        var url = "{{route('voucher.report.filter')}}";
        $.ajax({
            url: url,
            type: "POST",
            data : {
                        'voucherNo': $('#voucherNo').val(),
                        '_token': '{{ csrf_token() }}'
                    },
            success: function(response) {
                if(response.data.status){
                    $('#voucher-details').html(response.data.html);
                    $('#enteredUser').html(response.data.enteredByUser);
                    $('#generatedUser').html(response.data.loggedInUser);
                    $('#voucherNumber').html(response.data.voucher_no);
                    $('#voucherReportDiv').css('display','block');
                }else{
                    showAlert(response.data.msg,'error');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert("Please enter voucher number!");
    }
});

function printVoucherDetails(){
    if($('#voucherNo').val() != ""){
        var url = "{{route('check.voucher')}}";
        $.ajax({
            url: url,
            type: "get",
            data : {
                        'voucherNo': $('#voucherNo').val()
                    },
            success: function(response) {
                if(response.data.status){
                    var urlReport = baseUrl + "/account/statement/print-voucher-details?voucher_no=" + $('#voucherNo').val();
                    window.open(urlReport);
                }else{
                    showAlert("Invalid Voucher number",'error');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert("Please enter voucher number!");
    }
}

function exportVoucherDetails(){
    if($('#voucherNo').val() != ""){
        var url = "{{route('check.voucher')}}";
        $.ajax({
            url: url,
            type: "get",
            data : {
                        'voucherNo': $('#voucherNo').val()
                    },
            success: function(response) {
                if(response.data.status){
                    var urlReport = baseUrl + "/account/statement/export-voucher-details?voucher_no=" + $('#voucherNo').val();
                    window.open(urlReport);
                }else{
                    showAlert("Invalid Voucher number",'error');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert("Please enter voucher number!");
    }
}
</script>
@endpush
