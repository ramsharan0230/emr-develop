@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Account Daybook
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group>
                                <label for="" class="">From Date:<span class="text-danger">*</span></label>
                                <div class="">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group>
                                <label for="" class="">To Date:<span class="text-danger">*</span></label>
                                <div class="">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="" class="">Voucher Type:</label>
                                <div class="">
                                    <select name="" id="" class="form-control">
                                        <option value="">All</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="" class="col-sm-2">User:</label>
                                <div class="col-sm-3">
                                    <select name="" id="" class="form-control">
                                        <option value="">All</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-2">
                        <button type="button" class="btn btn-primary btn-action"><i class="fa fa-search"></i>&nbsp;Search</button>
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
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Form Date</th>
                                        <th class="text-center">To Date</th>
                                        <th class="text-center">Voucher Type</th>
                                        <th class="text-center"> User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Type1</td>
                                        <td class="text-center">User</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Type1</td>
                                        <td class="text-center">User</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">Type1</td>
                                        <td class="text-center">User</td>
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
