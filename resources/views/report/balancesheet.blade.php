@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Balance Sheet
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
                        <div class="table-responsive res-account">
                            <table class="table table-striped table-hover table-bordered ">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Code No.</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Sub Group</th>
                                        <th class="text-center">Account</th>
                                        <th class="text-center">Liabilities</th>
                                        <th class="text-center">Assets</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Sub Total</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center" colspan="2"><strong>Group Total</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">690,410,827</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td class="text-center" colspan="2"><strong>Liabilities</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">690,410,827</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Liabilities</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">Profit and Loss Account</td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Sub Total</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Current Liabilities</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">NARULA EXPORTS</td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">TDS PAYABLE ON BUILDING REPAIR & MAINTAINANCE</td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Sub Total</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><strong>Tax Payables</strong></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">NARULA EXPORTS</td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">TDS PAYABLE ON BUILDING REPAIR & MAINTAINANCE</td>
                                        <td class="text-center">0.00</td>
                                        <td class="text-center">15,210.38</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="4"><strong>Grand Total</strong></td>
                                        <td class="text-center"><strong>15,210.38</strong></td>
                                        <td class="text-center"><strong>15,210.38</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
