@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Purchase/Sales Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">

                    <div class="row">
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">From:</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" name="from_date" value="{{isset($date) ? $date : ''}}" id="from_date" class="form-control nepaliDatePicker" />
                                </div>


                            </div>


                        </div>



                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">To:</label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" class="form-control nepaliDatePicker" />
                                </div>

                            </div>
                        </div>




                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary rounded-pill refresh" id="refresh"><i class="ri-refresh-line"></i>&nbsp;Refresh</button>

                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportPurchaseReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                Export</a>&nbsp;
                        </div>

                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reporttype" id="reporttype" value="0" onclick="refresh()" checked>
                                <label class="form-check-label">
                                    Purchase Report with VAT
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reporttype" id="reporttype" value="1" onclick="refresh()">
                                <label class="form-check-label">
                                    Purchase Summary Report
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reporttype" id="reporttype" value="3" onclick="refresh()">
                                <label class="form-check-label">
                                    Sales Summary Report
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reporttype" id="reporttype" value="2" onclick="refresh()">
                                <label class="form-check-label">
                                    VAT Difference
                                </label>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">

            <div class="iq-card-body">
                <!-- <div class="res-table table-sticky-th">
                    <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light"> -->

                <div class="table-responsive table-sticky-th">
                    <table class="table table-bordered table-hover table-striped text-center">
                        <thead class="thead-light" id="purchase_vat_table">
                        </thead>

                        <tbody id="item_result">

                         <tr>
                             
                            <th>S.N.</th>
                            <th>Date</th>
                            <th>Purchase Ref</th>
                            <th>Bill No.</th>
                            <th>Supp Name</th>
                            <th>PAN/VAT</th>
                            <th>Non Taxable (Exc. Dis)</th>
                            <th>Discount</th>
                            <th>Taxable</th>
                            <th>Sub Total</th>
                            <th>VAT Amt</th>
                            <th>Net Total</th>
                            <th>Remarks</th>
            
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection


<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(document).ready(function() {

        $('.refresh').on('click', function(e) {

            var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            var reporttype = document.querySelector('input[name="reporttype"]:checked').value;

            if (fromdate != '' && todate != '') {


                $.ajax({
                    url: '{{route("purchase-vat-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        reporttype: reporttype
                    },
                    success: function(response) {
                        $('#item_result').html(response.data.html);
                        $('#purchase_vat_table').html(response.data.header);

                        showAlert('Data Retrieved');

                    },

                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            } else {
                alert('From and To Date Required!!');
            }

        });


    
    });

 

    function exportPurchaseReport() {

        var data = $("#purchase_data").serialize();
        var fromdate = $("#from_date").val();
        var todate = $("#to_date").val();
        var reporttype = document.querySelector('input[name="reporttype"]:checked').value;

        if (fromdate != '' && todate != '') {
            var urlReport = baseUrl + "/inventory/purchase-vat-report-pdf?fromdate=" + fromdate + "&todate=" + todate + "&reporttype=" + reporttype + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport);
        } else {
            alert('From and To Date Required!!');
        }
    }

    function refresh(){
        var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            var reporttype = document.querySelector('input[name="reporttype"]:checked').value;

            if (fromdate != '' && todate != '') {


                $.ajax({
                    url: '{{route("purchase-vat-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        reporttype: reporttype
                    },
                    success: function(response) {
                        $('#item_result').html(response.data.html);
                        $('#purchase_vat_table').html(response.data.header);

                        showAlert('Data Retrieved');

                    },

                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            } else {
                alert('From and To Date Required!!');
            }
    }
</script>