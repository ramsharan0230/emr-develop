@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Department Wise Report Base on IP and OP
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="billing_filter_data">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">From:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" />
                                        <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">To:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" />
                                        <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-flex float-right">
                                    <button type="submit" class="btn btn-primary btn-action"><i class="fa fa-filter"></i>&nbsp;Filter</button>&nbsp;
                                    <a href="{{ route('departmentwise.report.pdf',Request::getQueryString()) }}" class="btn btn-primary btn-action" target="_blank"><i class="fa fa-file-pdf"></i>&nbsp;PDF</a>&nbsp;
                                    <a href="{{ route('departmentwise.report.excel',Request::getQueryString()) }}" class="btn btn-primary btn-action"><i class="fa fa-file"></i>&nbsp;Export</a>&nbsp;
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
                    <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive res-table table-sticky-th">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light" style="text-align: center;">
                                        <tr>
                                            <th rowspan="2">SNo.</th>
                                            <th rowspan="2">Department Name</th>
                                            <th colspan="2">Cash Item Amount</th>
                                            <th colspan="2">Free Con. Amount</th>
                                            <th colspan="2">SVR Tax</th>
                                            <th rowspan="2">Item Amount</th>
                                            <th colspan="2">Credit Amount</th>
                                            <th colspan="2">Refund Amount</th>
                                            <th colspan="2">RF SVR Tax</th>
                                            <th rowspan="2">Net Amount</th>
                                        </tr>
                                        <tr>
                                            <th>OP</th>
                                            <th>IP</th>
                                            <th>OP</th>
                                            <th>IP</th>
                                            <th>OP</th>
                                            <th>IP</th>
                                            <th>OP</th>
                                            <th>IP</th>
                                            <th>OP</th>
                                            <th>IP</th>
                                            <th>OP</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $item_amount = [];
                                        $net_amount = [];
                                        @endphp
                                    <tbody>
                                        @if(count($reports) > 0)
                                        @foreach($reports as $key => $report)
                                        @php
                                            $item_amount[$key] = $report->OP_Cash_Amount + $report->IP_Cash_Amount - $report->OP_Discount_Amount - $report->IP_Discount_Amount;
                                            $net_amount[$key] = $item_amount[$key] + $report->OP_Return_Amount + $report->IP_Return_Amount - ($report->OP_Return_Tax_Amount + $report->IP_Return_Tax_Amount);
                                            $net_amount[$key] = $report->OP_Credit_Amount + $report->IP_Credit_Amount +  $item_amount[$key] + $report->OP_Return_Amount + $report->IP_Return_Amount - ($report->OP_Return_Tax_Amount + $report->IP_Return_Tax_Amount);
                                            //$opcollection += $report->OP_Cash_Amount + $report->OP_Discount_Amount - $report->OP_Tax_Amount;
                                            //$ipcollection += $report->IP_Cash_Amount + $report->IP_Discount_Amount - $report->IP_Tax_Amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $report->dept }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Cash_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Cash_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Discount_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Discount_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Tax_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Tax_Amount) }}</td>
                                            <td><b>{{ \App\Utils\Helpers::numberFormat($item_amount[$key]) }}</b></td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Credit_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Credit_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Return_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Return_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Return_Tax_Amount) }}</td>
                                            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Return_Tax_Amount) }}</td>
                                            <td><b>{{ \App\Utils\Helpers::numberFormat($net_amount[$key]) }}</b></td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="17">No records found.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    @if(count($reports) > 0)
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total Amount:</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Cash_Amount'))}} </th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Cash_Amount'))  }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Discount_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Discount_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Tax_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Tax_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat(array_sum($item_amount)) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Credit_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Credit_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Return_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Return_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Return_Tax_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Return_Tax_Amount')) }}</th>
                                            <th>{{ \App\Utils\Helpers::numberFormat(array_sum($net_amount)) }}</th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>

                                <table class="table table-bordered" style="width:50%">
                                    <tr>
                                        <td>OP Collection</td>
                                        <td>{{\App\Utils\Helpers::numberFormat($OP_patbilling)}}</td>
                                    </tr>
                                    <tr>
                                        <td>IP Collection</td>
                                        <td>{{\App\Utils\Helpers::numberFormat($IP_patbilling)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Net Realized Revenue</td>
                                        <td>{{\App\Utils\Helpers::numberFormat(($OP_patbilling+$IP_patbilling))}}</td>
                                    </tr>
                                    <tr>
                                        <td>Deposit Only</td>
                                        <td>{{\App\Utils\Helpers::numberFormat($deposit)}}</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Deposit Refund</td>
                                        <td>{{\App\Utils\Helpers::numberFormat($deposit_refund)}}</td>
                                    </tr> -->
                                    <tr>
                                        <td>Adjustment from Previous Deposit</td>
                                        <td>{{\App\Utils\Helpers::numberFormat(($Previous_Deposit_of_Discharge_Clearence))}}</td>
                                    </tr>
                                    <tr>
                                        <td>Amount Received while Discharge Patient</td>
                                        <td>{{\App\Utils\Helpers::numberFormat(($Received_Deposit_of_Discharge_Clearence))}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Collection</td>
                                        <td>{{\App\Utils\Helpers::numberFormat($rev_amount_sum)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('after-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#from_date').val(AD2BS('{{ Request::get("eng_from_date") ?? date("Y-m-d") }}'));
        $('#to_date').val(AD2BS('{{ Request::get("eng_to_date") ?? date("Y-m-d") }}'));
        $('#eng_from_date').val('{{ Request::get("eng_from_date") ?? date("Y-m-d") }}');
        $('#eng_to_date').val('{{ Request::get("eng_to_date") ?? date("Y-m-d") }}');
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function() {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function() {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });
    });
</script>
@endpush
