@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Profit Loss Account
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form id="profit-loss-filter">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">From Date:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}">
                                        <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}">
                                        <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-row">
                                    <button type="button" class="btn btn-primary btn-action" onclick="searchProfitLoss()"><i class="fa fa-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-row">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="exportProfitLoss()"><i class="fa fa-download"></i>&nbsp;Export Excel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-row">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="exportProfitLossPdf()"><i class="fa fa-file-pdf"></i>&nbsp;PDF</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive res-table">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center"  colspan="2">Particulars</th>
                                        <th class="text-center">For The period</th>
                                        <th class="text-center">Year To Date</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" ></th>
                                        <th class="text-center" >Group</th>
                                        <th class="text-center">Subgroup</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="profit-loss-data">
                                    <!-- <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center" >Direct Income</td>
                                        <td class="text-center" >Direct Income</td>
                                        <td class="text-center" >Direct Income</td>
                                        <td class="text-center" >Direct Income</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center">2000</td>
                                        <td class="text-center">2000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center" >Direct Expense</td>
                                        <td class="text-center" >Direct Expense</td>
                                        <td class="text-center" >Direct Expense</td>
                                        <td class="text-center" >Direct Expense</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center">2000</td>
                                        <td class="text-center">2000</td>
                                    </tr>

                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Net Profit/Loss</td>
                                        <td class="text-center">Income Expense</td>
                                        <td class="text-center">Income Expense</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- <nav aria-label="Page navigation example">
                        <ul class="pagination mb-0">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">«</span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                </a>
                            </li>
                        </ul>
                    </nav> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // hide/show
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "none";
        } else {
            x.style.display = "none";
        }
    }
    $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
        }
    });

    function searchProfitLoss(){
        // alert('Profit Loss');
        $.ajax({
            url: baseUrl + '/account/profitloss/searchProfitLoss',
            type: "POST",
            data: $('#profit-loss-filter').serialize(),
            success: function (response) {
                $('#profit-loss-data').html(response);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function exportProfitLoss() {

        var data = $('#profit-loss-filter').serialize();
        var urlReport = baseUrl + "/account/profitloss/export-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport, '_blank');

    }

    function exportProfitLossPdf() {

        var data = $('#profit-loss-filter').serialize();
        var urlReport = baseUrl + "/account/profitloss/export-pdf?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport, '_blank');

    }

</script>
@endsection
