@extends('inpatient::pdf.layout.main')

@section('title', 'Department Wise Report')

@section('content')
<style>
    h4, h5 {
        margin: 10px 0;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h4 style="text-align: center;">Chirayu National Hospital & Medical Institute</h4>
            <h5 style="text-align: center;">Basundhara Kathmanudu, Nepal</h5>
            <h5 style="text-align: center;">Contact No:</h5>
            <h5 style="text-align: center;">Department Wise Total Collection</h5>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%">Date: {{ $eng_from_date }}({{ $nep_from_date }}) To {{ $eng_to_date }}({{ $nep_to_date }})</td>
                    <td style="text-align: right; width: 50%">Printed On: {{Carbon\Carbon::now()->format('Y-m-d')}}</td>                    
                </tr>
            </table>
            
            <h5></h5>
            
            <h5></h5>
           
            <h5></h5>
            <div class="table-responsive res-table table-sticky-th">
                <table style="width: 100%;"  class="content-body">
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
                <table class="content-body" style="width:50%">
                    <tr>
                        <td>OP Collection</td>
                        <td>{{\App\Utils\Helpers::numberFormat(($reports->sum('OP_Cash_Amount') + $reports->sum('OP_Return_Amount') - $reports->sum('OP_Return_Tax_Amount')- $reports->sum('OP_Discount_Amount')))}}</td>
                    </tr>
                    <tr>
                        <td>IP Collection</td>
                        <td>{{\App\Utils\Helpers::numberFormat(($reports->sum('IP_Cash_Amount') + $reports->sum('IP_Return_Amount') - $reports->sum('IP_Return_Tax_Amount') - $reports->sum('IP_Discount_Amount')))}}</td>
                    </tr>
                    <tr>
                        <td>Net Realized Revenue</td>
                        <td>{{\App\Utils\Helpers::numberFormat(array_sum($net_amount) - $reports->sum('OP_Credit_Amount') - $reports->sum('IP_Credit_Amount'))}}</td>
                    </tr>
                    <tr>
                        <td>Deposit Only</td>
                        <td>{{\App\Utils\Helpers::numberFormat($deposit)}}</td>
                    </tr>
                    <tr>
                        <td>Deposit Refund</td>
                        <td>{{\App\Utils\Helpers::numberFormat($deposit_refund)}}</td>
                    </tr>
                    <tr>
                        <td>Adjustment from Previous Deposit</td>
                        <td>{{\App\Utils\Helpers::numberFormat($Previous_Deposit_of_Discharge_Clearence)}}</td>
                    </tr>
                    <tr>
                        <td>Amount Received while Discharge Patient</td>
                        <td>{{\App\Utils\Helpers::numberFormat($Received_Deposit_of_Discharge_Clearence)}}</td>
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
@endsection