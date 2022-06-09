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
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">From Date:<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group form-row">
                                <button type="button" class="btn btn-primary btn-action"><i class="fa fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </div>
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
                                        <th class="text-center" rowspan="2">S/N</th>
                                        <th class="text-center" rowspan="2">Particulars</th>
                                        <th class="text-center">For The period</th>
                                        <th class="text-center">Year To Date</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-left" colspan="3">Direct Income</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center">2000</td>
                                        <td class="text-center">2000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td class="text-left" colspan="3">Direct Expenses</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center">4000</td>
                                        <td class="text-center">4000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">Total</td>
                                        <td class="text-center">2000</td>
                                        <td class="text-center">2000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">Net Profit/Loss</td>
                                        <td class="text-center">2000</td>
                                        <td class="text-center">2000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <nav aria-label="Page navigation example">
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
                    </nav>
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
</script>
@endsection
