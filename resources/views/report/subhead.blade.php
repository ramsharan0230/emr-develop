@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex ">
                    <div class="iq-header-title col-sm-7 p-0">
                        <h4 class="card-title">
                            Account Group
                        </h4>
                    </div>
                    <div class="accountsearchbox col-sm-4">
                        <input type="text" class="form-control" placeholder="Search account group...">
                        <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#accountModal"><i class="fa fa-plus"></i>
                            Add
                        </button>
                    </div>
                    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="accountModalLabel">Add Account SubHead</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Short Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Select Nature:</label>
                                        <div class="col-sm-9">
                                            <select name="" id="" class="form-control">
                                                <option value="">Assests</option>
                                                <option value="">liabilities</option>
                                                <option value="">Expenses</option>
                                                <option value="">Income</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-primary">Add & New</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive res-table">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Account Nature</th>
                                        <th class="text-center">Group Name</th>
                                        <th class="text-center"> Account Sub Group</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">liabilities</td>
                                        <td class="text-center">Cash in hand</td>
                                        <td class="text-center">Cash Billing</td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>
                                        <div class="modal fade" id="editaccountModal" tabindex="-1" role="dialog" aria-labelledby="editaccountModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editaccountModalLabel">Edit Account SubHead</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Name:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Short Name:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Select Nature:</label>
                                                            <div class="col-sm-9">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Assests</option>
                                                                    <option value="">liabilities</option>
                                                                    <option value="">Expenses</option>
                                                                    <option value="">Income</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary">Add</button>
                                                        <button type="button" class="btn btn-primary">Add & New</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center">liabilities</td>
                                        <td class="text-center">Cash in hand</td>
                                        <td class="text-center">Cash Billing</td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>
                                        <div class="modal fade" id="editaccountModal" tabindex="-1" role="dialog" aria-labelledby="editaccountModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editaccountModalLabel">Add Account SubHead</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Name:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Short Name:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Select Nature:</label>
                                                            <div class="col-sm-9">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Assests</option>
                                                                    <option value="">liabilities</option>
                                                                    <option value="">Expenses</option>
                                                                    <option value="">Income</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary">Add</button>
                                                        <button type="button" class="btn btn-primary">Add & New</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td class="text-center">liabilities</td>
                                        <td class="text-center">Cash in hand</td>
                                        <td class="text-center">Cash Billing</td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#editaccountModal"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>
                                        <div class="modal fade" id="editaccountModal" tabindex="-1" role="dialog" aria-labelledby="editaccountModalLabel" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editaccountModalLabel">Add Account SubHead</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Name</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Short Name:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-3">Select Nature:</label>
                                                            <div class="col-sm-9">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Assests</option>
                                                                    <option value="">liabilities</option>
                                                                    <option value="">Expenses</option>
                                                                    <option value="">Income</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary">Add</button>
                                                        <button type="button" class="btn btn-primary">Add & New</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
@endsection
