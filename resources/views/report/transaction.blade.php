@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Contra Voucher
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Account No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Voucher Entry :</label>
                                <div class="col-sm-5">
                                    <select name="" id="" class="form-control">
                                        <option value="">Journal</option>
                                        <option value="">Payment</option>
                                        <option value="">Receipt</option>
                                        <option value="">Contra Voucher</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#accountModal"><i class="fa fa-plus"></i></button>
                                </div>
                                <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="accountModalLabel">Account Transaction</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-4">Name:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-4">Short Name:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-4">Group:</label>
                                                            <div class="col-sm-7">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Assests</option>
                                                                    <option value="">liabilities</option>
                                                                    <option value="">Expenses</option>
                                                                    <option value="">Income</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <button class="btn btn-primary" id="btngrp"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="grpDIV" class="col-sm-12 border-top" style="display: none;">
                                                        <div class="form-row mt-3">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-row">
                                                                    <label for="" class="col-sm-3">Name:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-row">
                                                                    <label for="" class="col-sm-3">Select Nature:</label>
                                                                    <div class="col-sm-7">
                                                                        <select name="" id="" class="form-control">
                                                                            <option value="">Assests</option>
                                                                            <option value="">liabilities</option>
                                                                            <option value="">Expenses</option>
                                                                            <option value="">Income</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-primary">Add</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row">
                                                            <label for="" class="col-sm-4">SubGroup:</label>
                                                            <div class="col-sm-7">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="">Assests</option>
                                                                    <option value="">liabilities</option>
                                                                    <option value="">Expenses</option>
                                                                    <option value="">Income</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-1">
                                                                <button id="subgrpbtn" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="subgrpDIV" class="col-sm-12 border-top" style="display: none;">
                                                        <div class="form-row mt-3">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-row">
                                                                    <label for="" class="col-sm-3">Name:</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-row">
                                                                    <label for="" class="col-sm-3">Select Nature:</label>
                                                                    <div class="col-sm-7">
                                                                        <select name="" id="" class="form-control">
                                                                            <option value="">Assests</option>
                                                                            <option value="">liabilities</option>
                                                                            <option value="">Expenses</option>
                                                                            <option value="">Income</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-primary">Add</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                            <label class="custom-control-label" for=""> Active </label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="" name="customRadio-1" class="custom-control-input">
                                                            <label class="custom-control-label" for=""> inactive </label>
                                                        </div>
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
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Branch:</label>
                                <div class="col-sm-7">
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
                                <label for="" class="col-sm-4">Debit Amt:</label>
                                <div class="col-sm-8">
                                    <input type="txet" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Credit Amt:</label>
                                <div class="col-sm-7">
                                    <input type="txet" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-5">Short Narration:</label>
                                <div class="col-sm-7">
                                    <input type="txet" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-primary">Add</button>
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
                                        <th class="text-center">Account Name</th>
                                        <th class="text-center">Debit Amt</th>
                                        <th class="text-center">Credit Amount</th>
                                        <th class="text-center">Short Narration</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">liabilities</td>
                                        <td class="text-center">1000</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Cash Billing</td>
                                        <td class="text-center">
                                            <a href="#!" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>
                                            <a href="#!" class="btn btn-danger"><i class="ri-delete-bin-fill"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">Total</td>
                                        <td class="text-center">1000</td>
                                        <td class="text-center">1000</td>
                                        <td class="text-center" colspan="2">Diffrencence Amt :1000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Cheque No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group form-row mt-2">
                                <label for="" class="col-sm-4">Transaction Date:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">Remarks:</label>
                                <textarea class="form-control col-sm-10" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-2">
                            <div class="form-group form-row float-right">
                                <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-sync"></i>&nbsp;Generate PDF</a>&nbsp;
                                <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-p-rint"></i>&nbsp;Print</a>&nbsp;
                                <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-plus"></i>&nbsp;Save</a>&nbsp;
                                <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-sync"></i>&nbsp;Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#subgrpbtn', function() {
        if ($(this).hasClass('show')) {
            $('#subgrpDIV').hide();
            $(this).removeClass('show');
        } else {
            $('#subgrpDIV').show();
            $(this).addClass('show');
        }
    });
    $(document).on('click', '#btngrp', function() {
        if ($(this).hasClass('show')) {
            $('#grpDIV').hide();
            $(this).removeClass('show');
        } else {
            $('#grpDIV').show();
            $(this).addClass('show');
        }
    });
</script>
@endsection
