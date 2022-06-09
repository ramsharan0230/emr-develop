@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Account Ledger
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
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Ledger Name:</label>
                                <div class="col-sm-5">
                                    <input type="txet" class="form-control" placeholder="Enter Ledger Name">
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-primary btn-action"><i class="fa fa-search"></i>&nbsp;Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header justify-content-between mt-3">
                    <button type="button" class="btn btn-primary btn-action float-right ml-1"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <button type="button" class="btn btn-primary btn-action float-right"><i class="fa fa-arrow-circle-down"></i>
                        Export
                    </button>&nbsp;
                </div>
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive res-table">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Tran Date</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Voucher Code</th>
                                        <th class="text-center">Voucher No</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Balance</th>
                                        <th class="text-center">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">branch1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">description1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Voucher</td>
                                        <td class="text-center">Dr</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">branch1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">description1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Voucher</td>
                                        <td class="text-center">Dr</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">branch1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">description1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Voucher</td>
                                        <td class="text-center">Dr</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">branch1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">description1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Voucher</td>
                                        <td class="text-center">Dr</td>
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
