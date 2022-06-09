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
                                <label for="" class="col-sm-6">Account Group Code:</label>
                                <div class="col-sm-6">
                                    <select name="" id="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Account No. :</label>
                                <div class="col-sm-7">
                                    <select name="" id="" class="form-control">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Account Name:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-6">Account Name:<br></label>
                                <div class="col-sm-6">
                                    <input type="txet" class="form-control" placeholder="In Native Language">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Is Active:</label>
                                <div class="col-sm-7">
                                    <select name="" id="" class="form-control">
                                        <option value="">Yes</option>
                                        <option value="">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary btn-action"><i class="fa fa-plus"></i>&nbsp;Add</button>
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
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Account group Code</th>
                                        <th class="text-center">Account No.</th>
                                        <th class="text-center"> Account Name</th>
                                        <th class="text-center">Account Name (in native language)</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center"> Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">name1</td>
                                        <td class="text-center">Nmae2</td>
                                        <td class="text-center"><button type="button" class="btn btn-outline-success btn-action">Active</button></td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editledgerModal"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>
                                        <div class="modal fade" id="editledgerModal" tabindex="-1" role="dialog" aria-labelledby="editledgerModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editledgerModalLabel">Add Account Ledger</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-5">Account Group Code:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-5">Account No.:<span class="text-danger">*</span></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-5">Account Name:<span class="text-danger">*</span></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-5">Account Name:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" placeholder="in native language">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-5">Is Active:</label>
                                                            <div class="col-sm-7">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Yes</option>
                                                                    <option value="">No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary">Add</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </tr>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">1234</td>
                                        <td class="text-center">name1</td>
                                        <td class="text-center">Nmae2</td>
                                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-action">Inctive</button></td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>

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
