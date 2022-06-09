@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Head Wise Ledger
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="" id="head-wise-ledger-form" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">Form:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="from_date" autocomplete="off">
                                        <input type="hidden" name="from_date" id="from_date_eng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">To:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="to_date" autocomplete="off">
                                        <input type="hidden" name="to_date" id="to_date_eng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Account No:</label>
                                    <div class="col-sm-8">
                                        <select name="account_num" id="account_num" class="select2" required>
                                            <option value="">Select</option>
                                            @if($accounts)
                                            @forelse($accounts as $account)
                                            <option value="{{ $account->AccountNo }}" {{ request()->get('account_num') == $account->AccountNo ? "selected" : "" }}>{{ $account->AccountName }}</option>
                                            @empty
                                            @endforelse
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary pull-left">Search</button>
                                <button class="btn btn-warning pull-left" type="button" onclick="exportHeadWiseReport()">Export</button>
                                <button class="btn btn-warning pull-left" type="button" onclick="exportHeadWiseReportToExcel()">Export To Excel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="res-table table-container">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                            <tr>
                                    <th class="tittle-th">SNo</th>

                                    <th class="tittle-th">DateBS</th>
                                    <th class="tittle-th">DateAD</th>
                                    <th class="tittle-th">V No.</th>
                                    <th class="tittle-th">Sub Ledger</th>
                                    <th class="tittle-th">Description</th>
                                    <th class="tittle-th">Chq No</th>
                                    <th class="tittle-th">Dr Amt</th>
                                    <th class="tittle-th">Cr Amt</th>
                                    <th class="tittle-th">Balance</th>
                                    <th class="tittle-th">DR/CR</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if($transactionData)
                                @php
                                $dr = 0;

                                $drAmount = 0;
                                $crAmount = 0;
                                $cr =0;
                                $cramount = 0;
                                $type = '';
                                $currenttype = '';
                                $balance = 0;
                                @endphp
                                @foreach($transactionData as $transaction)


                                <tr>
                                <td>{{ $loop->iteration }}</td>
                                    <td>{{ ((isset($transaction['TranDate']) ? \App\Utils\Helpers::dateToNepali($transaction['TranDate']) :'')) }}</td>

                                    <td>{{ $transaction['TranDate'] }}</td>
                                    <td class="voucher_details" style="cursor: pointer">{{ $transaction['VoucherNo'] }}</td>
                                    <td>{{ $transaction['AccountName'] }}</td>
                                    <td>{{ $transaction['Narration'] }}</td>
                                    <td>{{ $transaction['ChequeNo'] }}</td>


                                    @if($transaction['TranAmount'] > 0)
                                    @php
                                    $drAmount +=$transaction['TranAmount'];
                                    @endphp
                                    @php
                                    $currenttype = 'DR';
                                    @endphp
                                    <td>{{  \App\Utils\Helpers::numberFormat($transaction['TranAmount']) }}</td>
                                    <td></td>
                                    <td>
                                        <!-- balance debit aayecha bhane new bal = dr+dr-cr;
balance credit aayencha cr-dr+cr; -->

                                        @php
                                        if(abs($balance) > abs($transaction['TranAmount'])){


                                        if(($type == 'DR' && $type == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
                                        $balance = abs($balance)+abs($transaction['TranAmount']);
                                        }
                                        else{
                                        $balance = abs($balance)-abs($transaction['TranAmount']);
                                        }
                                        if($type == ''){
                                        $currenttype = 'DR';
                                        }else{
                                        $currenttype = $type;
                                        }

                                        }else{



                                        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
                                        $balance = abs($balance)+abs($transaction['TranAmount']);
                                        }
                                        else{
                                        $balance = abs($balance)-abs($transaction['TranAmount']);
                                        }
                                        }





                                        @endphp

                                        <!-- <td>DR</td> -->



                                        {{  \App\Utils\Helpers::numberFormat(abs($balance)) }}
                                    </td>
                                    @else
                                    @php
                                    $currenttype = 'CR';
                                    @endphp
                                    @php
                                    $crAmount +=$transaction['TranAmount'];
                                    @endphp
                                    <td></td>
                                    <td>{{  \App\Utils\Helpers::numberFormat(abs($transaction['TranAmount'])) }}</td>
                                    <td>

                                        @php
                                        if(abs($balance) > abs($transaction['TranAmount'])){

                                        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
                                        $balance = abs($balance)+abs($transaction['TranAmount']);
                                        }
                                        else{
                                        $balance = abs($balance)-abs($transaction['TranAmount']);
                                        }
                                        if($type == ''){
                                        $currenttype = 'CR';
                                        }else{
                                        $currenttype = $type;
                                        }
                                        }else{

                                        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
                                        $balance = abs($balance)+abs($transaction['TranAmount']);
                                        }
                                        else{
                                        $balance = abs($balance)-abs($transaction['TranAmount']);
                                        }
                                        }





                                        @endphp
                                        <!-- <td>CR</td> -->

                                        {{  \App\Utils\Helpers::numberFormat(abs($balance)) }}
                                    </td>
                                    @endif

                                    @php
                                    if($transaction['TranAmount'] > 0){
                                    $dr = abs($balance);
                                    $dramount = abs($transaction['TranAmount']);
                                    $cr = 0;
                                    $cramount = 0;
                                    $type = $currenttype;

                                    }else{
                                    $cr = abs($balance);
                                    $cramount = abs($transaction['TranAmount']);
                                    $dr = 0;
                                    $dramount = 0;
                                    $type = $currenttype;
                                    }
                                    @endphp



                                    <td>{{$currenttype}}</td>



                                </tr>

                                @endforeach
                                <tr>
                                <td colspan="7" style="text-align: right;"><strong>Grand Total</strong></td>
                                <td style="text-align: right;"><strong>{{  \App\Utils\Helpers::numberFormat(abs($drAmount)) }}</strong></td>
                                <td style="text-align: right;"><strong>{{  \App\Utils\Helpers::numberFormat(abs($crAmount)) }}</strong></td>

                                <td></td>
                                <td></td>
                            </tr>
                                @endif
                            </tbody>
                        </table>
                        <div>

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
    $(window).ready(function() {
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function() {
                $('#from_date_eng').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function() {
                $('#to_date_eng').val(BS2AD($('#to_date').val()));
            }
        });

        $('#to_date').val(AD2BS('{{request()->get('to_date')??date('Y-m-d')}}'));
        $('#from_date').val(AD2BS('{{request()->get('from_date')??date('Y-m-d')}}'));
        $('#to_date_eng').val(BS2AD($('#to_date').val()));
        $('#from_date_eng').val(BS2AD($('#from_date').val()));
    });

    function exportHeadWiseReport() {
        window.open("{{ route('accounts.head.wise.ledger.export') }}?" + $('#head-wise-ledger-form').serialize(), '_blank');
    }

    function exportHeadWiseReportToExcel() {
            var data = $("#head-wise-ledger-form").serialize();
            // alert(data);
            var urlReport = baseUrl + "/account/head-wise-ledger/exportToExcel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }


    $(document).on('click', '.voucher_details', function() {
        var urlReport = baseUrl + "/account/statement/voucher-details?voucher_no=" + $(this).html();
        window.open(urlReport, '_blank');
    });
</script>

@endpush
