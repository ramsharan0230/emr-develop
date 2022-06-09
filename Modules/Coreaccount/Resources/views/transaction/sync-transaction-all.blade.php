@extends('frontend.layouts.master') @section('content')
 <style>
    .res-table {
        max-height: calc( 100vh - 120px);
    }
    .modal-body {
        padding: 0 1rem;
    }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="form-group rounded-top p-2 border-bottom">
                        <form action="javascript:;" id="sync-form-all" method="post">
                            @csrf
                            <div class="dept d-flex flex-row pt-2 pb-2">
                                <div class="col-md-1 text-right">Date:</div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control form-control-sm from_date" autocomplete="off" id="from-all" width="auto" >
                                    <input type="hidden" name="from_date" class="from_date_eng" id="from_eng-all" value="{{ $dateshow }}">
                                    <input type="hidden" name="today_date" class="today_date" id="today_date">
                                </div>
                                <!-- <div class="col-md-1 text-right">To:</div> -->
                                <!-- <div class="col-md-2"> -->
                                    <!-- <input type="text" class="form-control form-control-sm to_date" autocomplete="off" id="to-all" width="auto"> -->
                                    <input type="hidden" name="to_date" class="to_date_eng" value="{{ date('Y-m-d') }}" id="to_eng-all">
                                <!-- </div> -->
                                <div class="col-md-2 text-center">Department:</div>
                                <div class="col-md-4">
                                    <select class="form-control"  id="dept">
                                        @foreach (Session::get('user_hospital_departments') as $hosp_dept)
                                            <option value="{{ $hosp_dept->fldcomp }}" data-comp="{{ $hosp_dept->fldcomp }}">{{ $hosp_dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">
                                        <button type="button" onclick="transactionAccountAll()" class="btn btn-primary mr-1">View Transaction</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Transaction Entry
                            </h4>
                        </div>
                        <div class="accountsearchbox col-sm-4">

                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="javascript:;" id="transaction-form">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Account No:</label>
                                        <div class="col-sm-8">
                                            <select name="account_name" id="account_name" class="select2">
                                                <option value="">Select</option>
                                                @if($accounts)
                                                    @forelse($accounts as $account)
                                                        <option value="{{ $account->AccountNo }}">{{ $account->AccountName }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Voucher Entry:</label>
                                        <div class="col-sm-7">
                                            <select name="voucher_entry" id="voucher_entry" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Journal">Journal</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="debit_credit" class="col-sm-4">Dr/Cr:</label>
                                        <div class="col-sm-8">
                                            <select name="debit_credit" class="form-control" id="debit_credit">
                                                <option value="">Select</option>
                                                <option value="+">Dr</option>
                                                <option value="-">Cr</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Amount:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="amount" id="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Short Narration:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="narration" id="narration" class="form-control" maxlength="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <div class="">
                                    <button type="button" class="btn btn-primary" onclick="addVoucher()">Add</button>
                                </div>
                                <div class="">
                                    <button type="button" class="btn btn-primary ml-2" onclick="clearData()">Clear Data</button>
                                </div>
                                <div class="">
                                <a href="javascript:void(0);" type="button" class="btn btn-primary ml-2" onclick="exportCategorywiseReport()"><i class="fa fa-file"></i>&nbsp;
                                            Dept-Wise Report</a>
                                </div>
                            </div>
                        </form>
                    </div>
                                    </div>
            </div>
        </div>
        @php
                                        $countRows = 1;
                                        $accountIds = [];
                                        $grandTotal = 0;
                                        $sumTotalDebit = 0;
                                        $sumTotalDebitReturn = 0;
                                        $grandTotalReturn = 0;
                                        $sumTotalCredit = 0;

                                    @endphp
        @if($alreadysynced->account_sync == 0)
        <form action="{{ route('transaction.add.by.account') }}" id="final-save-data-form" class="row" method="post">
            <input type="hidden" name="today_date" id="today_date">
            <input type="hidden" name="fromdate" value="{{$fromdate}}" id="fromdate">
            <input type="hidden" name="department" value="{{$department}}" id="department">
            <input type="hidden"name="todate"  value="{{$todate}}" id="todate">
            <input type="hidden" name="engfromdate" value="{{\App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($fromdate)))->full_date}}" id="engfromdate">
            <input type="hidden"name="engtodate"  value="{{\App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($todate)))->full_date}}" id="engtodate">
            <div class="col-sm-12 p-0">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        @csrf
                        <div class="form-group">
                            <div class="table-responsive table-sticky-th">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Account Number</th>
                                        <th class="text-center">Account Name</th>
                                        <th class="text-center">Debit Amt</th>
                                        <th class="text-center">Credit Amt</th>
                                        <th class="text-center" style="width: 250px;">Short Narration</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="voucher-table-append">

                                    @if($ledgersCredit)
                                        @foreach($ledgersCredit as $key => $credit)

                                                <tr>
                                                        <input type="hidden" name="tempId" value="">
                                                    {{--because all transaction have same data except for amount--}}
                                                    <td class="text-center">{{ $countRows++ }}</td>
                                                    <td class="text-center">{{$credit->AccountNo}}</td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-{{ $credit->AccountNo  }}">{{ $credit->AccountName ? $credit->AccountName : '' }}</a>
                                                    </td>
                                                    <td class="text-center">0.00</td>
                                                    <td class="text-center">{{  \App\Utils\Helpers::numberFormat($credit->amount) }}</td>
                                                    <td class="text-center">Collection for {{$dateshow}}</td>
                                                    <td></td>
                                                </tr>
                                                @php
                                                    $grandTotal += abs($credit->amount);
                                                @endphp
                                        @endforeach
                                    @endif



                                    @if($ledgersReturn)

                                        @foreach($ledgersReturn as $key => $ledgersRet)

                                                <tr>


                                                    <input type="hidden" name="tempIdReturn" value="">
                                                    {{--because all transaction have same data except for amount--}}
                                                    <td class="text-center">{{ $countRows++ }}</td>
                                                    <td class="text-center">{{$credit->AccountNo}}</td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-Return-{{ $credit->AccountNo  }}">{{ $ledgersRet->AccountName }}</a>
                                                    </td>
                                                    <td class="text-center"> {{  \App\Utils\Helpers::numberFormat(abs($ledgersRet->amount)) }}</td>
                                                    <td class="text-center">0.00</td>
                                                    <td class="text-center">Refund Collection for  {{$dateshow}}</td>
                                                    <td></td>
                                                </tr>

                                                @php
                                                    $sumTotalDebit += abs($ledgersRet->amount);
                                                @endphp
                                        @endforeach
                                    @endif


                                    @if(($debitDisc))
                                        <tr>
                                                {{--because all transaction have same data except for amount--}}
                                                        <td class="text-center">{{ $countRows++ }}</td>
                                                        <td class="text-center">{{$debitDiscDetail[1] ? : '2000000005'}}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-debitdiscountdata">{{$debitDiscDetail[0] ? : 'DISCOUNT SCHEME EXP'}}</a>
                                                        </td>
                                                        <td class="text-center">{{   \App\Utils\Helpers::numberFormat(abs($debitDisc))  }}</td>

                                                        <td class="text-center">0.00 </td>
                                                        <td class="text-center">Discount for  {{$dateshow}}</td>
                                                        <td></td>
                                                    </tr>
                                                    @php
                                                    $sumTotalDebit += abs($debitDisc);
                                                    @endphp


                                    @endif

                                    @if(($creditDisc))
                                        <tr>
                                                        {{--because all transaction have same data except for amount--}}
                                                        <td class="text-center">{{ $countRows++ }}</td>
                                                        <td class="text-center">{{$creditDiscDetail[1] ? : '2000000005'}}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-creditdiscountdata">{{$creditDiscDetail[0] ? : 'DISCOUNT SCHEME EXP'}}</a>
                                                        </td>


                                                        <td class="text-center">0.00 </td>
                                                        <td class="text-center">{{   \App\Utils\Helpers::numberFormat(abs($creditDisc)) }}</td>
                                                        <td class="text-center">Discount for  {{$dateshow}}</td>
                                                        <td></td>
                                                    </tr>
                                                    @php
                                                    $grandTotal += abs($creditDisc) ;
                                                    @endphp


                                    @endif



                                    @if($PrevDeposit)
                                    @php
                                    $account = Helpers::getAccountLedger('Previous Deposit');
                                    @endphp
                                            @php
                                                $sumTotalDebit += abs($PrevDeposit);
                                            @endphp
                                            <tr>

                                                <td class="text-center">{{ $countRows++ }}</td>
                                                <td class="text-center">{{ $account[0] }}</td>
                                                <td class="text-center">
                                                    <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-advanceDetail">{{ $account[1] }}</a>
                                                </td>
                                                <td class="text-center">{{  \App\Utils\Helpers::numberFormat(abs($PrevDeposit)) }}</td>

                                                <td class="text-center">0.00   </td>
                                                <td class="text-center">Previous Adjusted On Discharge for  {{$dateshow}}</td>
                                                <td></td>
                                            </tr>


                                    @endif

                                    @if($Prevcr)
                                    @php
                                    $account = Helpers::getAccountLedger('Previous Credit');
                                    @endphp
                                            @php
                                                $grandTotal += abs($Prevcr);
                                            @endphp
                                            <tr>

                                                <td class="text-center">{{ $countRows++ }}</td>
                                                <td class="text-center">{{ $account[0] }}</td>
                                                <td class="text-center">
                                                    <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-advanceDetail">{{ $account[1] }}</a>
                                                </td>

                                                <td class="text-center">0.00   </td>
                                                <td class="text-center">{{  \App\Utils\Helpers::numberFormat(abs($Prevcr)) }}</td>

                                                <td class="text-center">Previous Adjusted On Credit for  {{$dateshow}}</td>
                                                <td></td>
                                            </tr>


                                    @endif


                                    @if($curdeposit)
                                    @php
                                    $account = Helpers::getAccountLedger('Deposit and collection');
                                    @endphp
                                            @php
                                                $grandTotal += abs($curdeposit);
                                            @endphp
                                            <tr>

                                                <td class="text-center">{{ $countRows++ }}</td>
                                                <td class="text-center">{{ $account[0] }}</td>
                                                <td class="text-center">
                                                    <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-advanceDetail">{{ $account[1] }}</a>
                                                </td>
                                                <td class="text-center">0.00   </td>
                                                <td class="text-center">{{  \App\Utils\Helpers::numberFormat(abs($curdeposit)) }}</td>


                                                <td class="text-center">Deposit Collection on  {{$dateshow}}</td>
                                                <td></td>
                                            </tr>


                                    @endif


                                    @if($patientcredit)
                                    @php
                                    $account = Helpers::getAccountLedger('Patient Credit');
                                    @endphp
                                            @php
                                                $sumTotalDebit += abs($patientcredit);
                                            @endphp
                                            <tr>

                                                <td class="text-center">{{ $countRows++ }}</td>
                                                <td class="text-center">{{ $account[0] }}</td>
                                                <td class="text-center">
                                                    <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-advanceDetailcredit">{{ $account[1] }}</a>
                                                </td>
                                                <td class="text-center">{{  \App\Utils\Helpers::numberFormat(abs($patientcredit)) }}</td>
                                                <td class="text-center">0.00   </td>



                                                <td class="text-center">Patient Credit bill on  {{$dateshow}}</td>
                                                <td></td>
                                            </tr>


                                    @endif



                                    @if($patbillingreceivedAmount)
                                    @php
                                    $account = Helpers::getAccountLedger('cash on hand');
                                    @endphp

                                                <tr>

                                                    <td class="text-center">{{ $countRows++ }}</td>
                                                    <td class="text-center">{{ $account[0] }}</td>

                                                    <input type="hidden" name="accountIdCash" value="{{$account[0]}}">
                                                    <input type="hidden" name="amountCash" value="{{  \App\Utils\Helpers::numberFormat($patbillingreceivedAmount)}}">
                                                    <input type="hidden" name="branchCash" value="">
                                                    <input type="hidden" name="narrationCash" value="CASH IN HAND ({{date('Y-m-d')}})">
                                                    <input type="hidden" name="remarksCash" value="CASH IN HAND ({{date('Y-m-d')}})">
                                                    <input type="hidden" name="voucher_entryCash" value="Journal">
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-patbillingreceivedAmountdata">{{ $account[1] }}</a>
                                                    </td>
                                                    @if($patbillingreceivedAmount > 0)
                                                    <td class="text-center">{{ \App\Utils\Helpers::numberFormat(abs($patbillingreceivedAmount)) }}</td>

                                                    <td class="text-center">0.00   </td>
                                                    @php
                                                $sumTotalDebit += abs($patbillingreceivedAmount);
                                                @endphp
                                                    @else
                                                    <td class="text-center">0.00</td>

                                                    <td class="text-center">{{ \App\Utils\Helpers::numberFormat(abs($patbillingreceivedAmount))}}</td>
                                                    @php
                                                $grandTotal += abs($patbillingreceivedAmount);
                                                @endphp
                                                    @endif

                                                    <td class="text-center">BILLING/CASH -  {{$dateshow}}</td>
                                                    <td></td>
                                                </tr>


                                    @endif



                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-center" colspan="2">Total</td>
                                        @php
                                        $sumdeposit = $sumTotalDebit;
                                        $grandtotals = $grandTotal;
                                        @endphp
                                        <td class="text-center" id="debit-total">{{\App\Utils\Helpers::numberFormat($sumTotalDebit) }}</td>

                                        <td class="text-center" id="credit-total">{{\App\Utils\Helpers::numberFormat($grandTotal)}}</td>
                                        <td class="text-center" colspan="2"> <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information-differences">Difference Amt: </a> <span id="difference-amount">{{$sumdeposit - $grandtotals }}</span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-12 p-0">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Cheque No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="cheque_number" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group form-row mt-2">
                                    <label for="" class="col-sm-4">Transaction Date:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="transaction_date_nepali" value="" class="form-control">
                                        <input type="hidden" name="transaction_date" id="transaction_date_eng" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label for="remarks-textarea" class="col-sm-2">Remarks:</label>
                                    <textarea name="remarks_textarea" class="form-control col-sm-10" rows="3" id="remarks-textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <div class="form-group form-row float-right">
                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-sync"></i>&nbsp;Generate PDF</a>&nbsp;
                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-p-rint"></i>&nbsp;Print</a>&nbsp;
                                    <a href="javascript:;" data-toggle="modal" data-target=".detailed-transaction" type="button" class="btn btn-primary btn-action"><i class="fas fa-p-rint"></i>&nbsp;Preview</a>
                                    <button type="button" class="btn btn-primary btn-action" id="save-button" onclick="saveFormData();"><i class="fas fa-plus"></i>&nbsp;Save</button>
                                    <a href="javascript:;" onclick="clearData()" type="button" class="btn btn-primary btn-action ml-1"><i class="fas fa-sync"></i>&nbsp;Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>

    @if($alreadysynced->account_sync == 0)
    @php
    $AccountNo='';
    $count = 0;
    @endphp

    @if($ledgerAll)

    @foreach($LedgerAccount as $led)
    @php
    $AccountNo=$led->AccountNo;
    $count = 0;
    $total = 0;
    @endphp

    <div class="modal fade detailed-account-information-{{$led->AccountNo}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total amount</th>
                            <th>Received amount</th>
                            <th>User</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ledgerAll as $ledger)

                       @if($AccountNo == $ledger->AccountNo and substr($ledger->fldbillno, 0, 3) != 'RET')

                                <tr>
                                    <td>{{ ++$count}}</td>
                                    <td>{{ $ledger->fldencounterval }}</td>
                                    <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                    <td>{{ $ledger->flditemname }}</td>

                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate) }}</td>
                                    <td>{{ $ledger->flditemqty }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>

                                    @php
                                   $total +=$ledger->flditemrate * $ledger->flditemqty
                                    @endphp

                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate * $ledger->flditemqty)  }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldditemamt) }}</td>
                                    <td>{{ $ledger->flduserid }}</td>

                                </tr>

                        @endif

                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif


    @if($ledgerAllR)
    @php
    $AccountNo='';
    @endphp
    @foreach($LedgerAccount as $led)
    @php
    $AccountNo=$led->AccountNo;
    $count = 0;
    $total = 0;
    @endphp
    <div class="modal fade detailed-account-information-Return-{{$led->AccountNo}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body table-responsive table-sticky-th res-table">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total amount</th>
                            <th>Received amount</th>
                            <th>User</th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($ledgerAllR as $ledger)


                       @if($AccountNo == $ledger->AccountNo)

                                <tr>
                                    <td>{{ ++$count}}</td>
                                    <td>{{ $ledger->fldencounterval }}</td>
                                    <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                    <td>{{ $ledger->flditemname }}</td>

                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate) }}</td>
                                    <td>{{ $ledger->flditemqty }}</td>

                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate * $ledger->flditemqty)  }}</td>
                                    @php
                                   $total +=$ledger->flditemrate * $ledger->flditemqty
                                    @endphp
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldditemamt) }}</td>
                                    <td>{{ $ledger->flduserid }}</td>

                                </tr>

                        @endif
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    @if($depositdata)
    @php

    $count = 0;
    $total = 0;
    @endphp

    <div class="modal fade detailed-account-information-depositdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body table-responsive table-sticky-th res-table">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>



                        </tr>
                        </thead>
                        <tbody>
                        @foreach($depositdata as $ledger)


                                <tr>
                                    <td>{{ ++$count}}</td>
                                    <td>{{ $ledger->fldencounterval }}</td>
                                    <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                    <td>{{ $ledger->fldpayitemname }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                                </tr>
                                @php
                                   $total +=$ledger->fldreceivedamt;
                                    @endphp



                        @endforeach
                        <tr>
                            <td colspan="8"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @endif

    @if($PrevDepositdata)

    @php

    $count = 0;
    $total = 0;
    @endphp
<div class="modal fade detailed-account-information-PrevDepositdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($PrevDepositdata as $ledger)


                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->fldpayitemname }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->fldprevdeposit;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="3"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif


@if($patbillingreceivedAmountdata)

@php

    $count = 0;
    $total = 0;
    @endphp
<div class="modal fade detailed-account-information-patbillingreceivedAmountdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($patbillingreceivedAmountdata as $ledger)


                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->fldpayitemname }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->fldreceivedamt;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="8"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total) }}</td>
                            <td></td>


                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif


@if($curdepositdata)

@php

    $count = 0;
    $total = 0;
    @endphp
<div class="modal fade detailed-account-information-curdepositdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($curdepositdata as $ledger)


                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->fldpayitemname }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->fldcurdeposit;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="9"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>



                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif



@if($creditdiscountdata)
@php

    $count = 0;
    $total = 0;
    @endphp

    <div class="modal fade detailed-account-information-creditdiscountdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body table-responsive table-sticky-th res-table">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($creditdiscountdata as $ledger)



                                <tr>
                                    <td>{{ ++$count}}</td>
                                    <td>{{ $ledger->fldencounterval }}</td>
                                    <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                    <td>{{ $ledger->flditemname }}</td>

                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate) }}</td>
                                    <td>{{ $ledger->flditemqty }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                    <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldditemamt) }}</td>

                                </tr>

                                @php
                                   $total +=$ledger->flddiscamt;
                                    @endphp


                        @endforeach
                        <tr>
                            <td colspan="5"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>



                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @endif




@if($debitdiscountdata)

@php

    $count = 0;
    $total = 0;
    @endphp
<div class="modal fade detailed-account-information-debitdiscountdata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>SNo</th>
                        <th>Encounter</th>
                        <th>Bill No.</th>
                        <th>Item Name</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Total amount</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debitdiscountdata as $ledger)



                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->flditemname }}</td>

                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemrate) }}</td>
                                <td>{{ $ledger->flditemqty }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldditemamt) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->flddiscamt;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="5"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>



                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif




@if($advanceDetail)
@php

    $count = 0;
    $total = 0;
    $totalcur =0;
    @endphp

<div class="modal fade detailed-account-information-advanceDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
                <p>
                    Deposit Only: {{\App\Utils\Helpers::numberFormat($depositonly)}}<br>
                    Remaining Amount: {{\App\Utils\Helpers::numberFormat($remainingdeposit)}}<br>

                </p>
            <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($advanceDetail as $ledger)


                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->fldpayitemname }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->fldprevdeposit;
                                   $totalcur +=$ledger->fldcurdeposit;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="3"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{\App\Utils\Helpers::numberFormat($totalcur)}}</td>



                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif


@if($patientcreditdetail)
@php

    $count = 0;
    $total = 0;
    $totalcur =0;
    @endphp

<div class="modal fade detailed-account-information-advanceDetailcredit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th res-table">
            <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>
                            <th>Encounter</th>
                            <th>Bill No.</th>
                            <th>Item Name</th>
                            <th>Prev Deposit</th>
                            <th>Amount</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Charged Amount</th>
                            <th>Received Amount</th>
                            <th>Current Deposit</th>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($patientcreditdetail as $ledger)


                            <tr>
                                <td>{{ ++$count}}</td>
                                <td>{{ $ledger->fldencounterval }}</td>
                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ $ledger->fldpayitemname }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldprevdeposit) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flditemamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->flddiscountamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldchargedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldreceivedamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->fldcurdeposit) }}</td>

                            </tr>
                            @php
                                   $total +=$ledger->fldprevdeposit;
                                   $totalcur +=$ledger->fldcurdeposit;
                                    @endphp


                    @endforeach
                    <tr>
                            <td colspan="3"></td>
                            <td>Total</td>
                            <td>{{\App\Utils\Helpers::numberFormat($total)}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{\App\Utils\Helpers::numberFormat($totalcur)}}</td>



                        </tr>
                    </tbody>
                </table>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif




@if($differences)
@php

    $count = 0;
    $total = 0;
    $css ='';
    @endphp

<div class="modal fade detailed-account-information-differences" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive table-sticky-th table-sticky-th res-table">

            <table class="table table-hover table-striped">
                    <thead class="thead-light">
                    <tr>
                    <th>SNo</th>

                            <th>Bill No.</th>
                            <th>Prev Deposit</th>
                            <th>Item Rate Sum</th>
                            <th>Discount</th>
                            <th>Detail discount</th>
                            <th>Rate*Qty</th>
                            <th>Gross</th>
                            <th>Receive Amt</th>
                            <th>Remaining</th>
                            <th></th>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($differences as $ledger)




                    @php
                    $gross =  $ledger->gross;
                    $final =  $ledger->final;
                    $PRVDep = $ledger->PRVDep;
                    $remain =  $ledger->remain;
                    @endphp
                            <tr class="{{$css}}">
                                <td>{{ ++$count}}</td>

                                <td><a href="javascript:void(0);" class="bill" data-bill="{{$ledger->fldbillno}}" >{{ $ledger->fldbillno }}</a></td>
                                <td>{{ \App\Utils\Helpers::numberFormat($PRVDep) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->rateQTY) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->DIS) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->detaildis) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($ledger->Detailrateqty) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($gross) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($final) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($remain) }}</td>
                                <td @if((($gross - $final - $PRVDep + $remain) != 0 )) class="red"@endif>{{\App\Utils\Helpers::numberFormat(($gross - $final - $PRVDep + $remain))}}</td>

                            </tr>


                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif





    @endif






@endsection
@push('after-script')
    <script>
        var countInsertedData = {{ $countRows }};
        var newNumber = {{ $countRows }};
        var debitTotal =  numberFormat($('#debit-total').text());
        var creditTotal =  numberFormat($('#credit-total').text());
        var difference = 0;
        $countI = 0;

        let todayDate = AD2BS('{{date('Y-m-d')}}');
        $(document).on('click', '#subgrpbtn', function () {
            if ($(this).hasClass('show')) {
                $('#subgrpDIV').hide();
                $(this).removeClass('show');
            } else {
                $('#subgrpDIV').show();
                $(this).addClass('show');
            }
        });
        $(document).on('click', '.bill', function () {
            var data = $(this).data('bill');
            var urlReport = baseUrl + "/billing/service/billing-invoice?billno=" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            if (data.startsWith('RET'))
                urlReport = baseUrl + "/billing/service/displayReturnBilling?invoice_number=" + data;
            else if (data.startsWith('DEP'))
                urlReport = baseUrl + "/depositForm/printBill?fldbillno=" + data;

            window.open(urlReport, '_blank');
        });


        $(window).ready(function () {
            $('#transaction_date_nepali').val(todayDate);
            $('#transaction_date_eng').val(BS2AD($('#transaction_date_nepali').val()));

            $('#transaction_date_nepali').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#transaction_date_eng').val(BS2AD($('#transaction_date_nepali').val()));
                }
            });
            $(document).on('focusout', '#transaction_date_nepali', function () {
                $('#transaction_date_eng').val(BS2AD($('#transaction_date_nepali').val()));
            });

        })

        $(document).on('click', '#btngrp', function () {
            if ($(this).hasClass('show')) {
                $('#grpDIV').hide();
                $(this).removeClass('show');
            } else {
                $('#grpDIV').show();
                $(this).addClass('show');
            }
        });

        $("#voucher_entry").on('change', function () {
            $('#remarks-textarea').val($("#voucher_entry").val() + " Voucher (" + AD2BS('{{date('Y-m-d')}}') + ")");
        });

        $(document).on('click', '.delete-data', function () {
            // alert($(this).attr('deleteid'));
            if (confirm("Delete?")) {
                localStorage.removeItem($(this).attr('deleteid'));
                $d = $(this).closest('tr').attr('debited');
                $c = $(this).closest('tr').attr('credited');
                //alert($d)
                //alert($c)
                $(this).closest('tr').remove();
                debitamount = numberFormat($('#debit-total').text()) - numberFormat($d);
                creditamount =  numberFormat($('#credit-total').text()) - numberFormat($c);
              //  alert(creditamount)
                $('#debit-total').text(numberFormatDisplay(debitamount))
                $('#credit-total').text(numberFormatDisplay(creditamount))
                $('#difference-amount').text(numberFormatDisplay(parseFloat(debitamount-creditamount)));
                location.reload();

                //calculate();
            }
            return false;
        });

        /**add voucher*/
        function addVoucher() {
            debit = 0;
            credit = 0;
            amount = 0;

            if ($("#account_name option:selected").val() == "" || $("#voucher_entry option:selected").val() == "" || $("debit_credit option:selected").val() == "" || $("#amount").val() == "") {
                showAlert('Account number must be selected', 'error');
                return false;
            }

            if (isNaN($("#amount").val())) {
                showAlert('Amount must be number', 'error');
                return false;
            }

            /**
             * check if it is debit or credit
             */
            if ($("#debit_credit").val() === '+') {
                debit = numberFormat($("#amount").val());
                debitTotal = parseFloat(debitTotal) + parseFloat(debit);
                amount = debit;
            } else {
                credit = numberFormat($("#amount").val());
                creditTotal = parseFloat(creditTotal) + parseFloat(credit);
                amount = credit * (-1);
            }
            difference = parseFloat(debitTotal) - parseFloat(creditTotal);
            deleteString = "IT-" + countInsertedData;

            voucher_entry = $("#voucher_entry").val();
            htmlTr = '<tr debited="'+debit+'" credited="'+credit+'"> ' +
                '<td class="text-center">' + countInsertedData +
                '<input type="hidden" name="accountId[]" value="' + $("#account_name option:selected").val() + '">' +
                '<input type="hidden" name="amount[]" value="' + amount + '">' +
                '<input type="hidden" name="branch[]" value="' + $("#branch").val() + '">' +
                '<input type="hidden" name="narration[]" value="' + $("#narration").val() + '">' +
                '<input type="hidden" name="remarks[]" value="' + $("#remarks-textarea").val() + '">' +
                '<input type="hidden" name="voucher_entry[]" value="' + voucher_entry + '">' +
                '</td>' +
                '<td class="text-center">'+ $("#account_name option:selected").val() + '</td>' +
                '<td class="text-center">' + $("#account_name option:selected").text() + '</td>' +
                '<td class="text-center" >' + numberFormatDisplay(debit) + '</td>' +
                '<td class="text-center" >' + numberFormatDisplay(credit) + '</td>' +
                '<td class="text-center">' + $("#narration").val() + '</td>' +
                '<td class="text-center">' +
                // '    <a href="javascript:;" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>' +
                '    <a href="javascript:;" class="btn btn-danger delete-data" deleteid="' + deleteString + '"><i class="ri-delete-bin-fill"></i></a>' +
                '</td>' +
                '</tr>';

                debitamount = parseFloat(numberFormat($('#debit-total').text())) + parseFloat(numberFormat(debit));
                    creditamount =  parseFloat(numberFormat($('#credit-total').text())) + parseFloat(numberFormat(credit));
                    $('#debit-total').text(numberFormatDisplay(debitamount))
                    $('#credit-total').text(numberFormatDisplay(creditamount))

            saveToLocalStorage();

            $("#voucher-table-append").append(htmlTr);



            debitamount = parseFloat(numberFormat($('#debit-total').text()));
            creditamount =  parseFloat(($('#credit-total').text()));
              //  alert(creditamount)
              diff = parseFloat(debitamount)-parseFloat(creditamount);
                $('#debit-total').text(numberFormatDisplay(parseFloat(debitamount)))
                $('#credit-total').text(numberFormatDisplay(parseFloat(creditamount)))
                $('#difference-amount').text(numberFormatDisplay(parseFloat(diff)));


            $(".voucher_entry_for_old_data").val(voucher_entry)
            $('option', $("#voucher_entry")).not(':eq(0), :selected').remove();
            $('#account_name').select2("val", "");
            ;
            $("#transaction-form").trigger('reset');
            $("#voucher_entry").val(voucher_entry);
            showAlert('Transaction added successfully', 'success');
        }

        function saveToLocalStorage() {
            var obj = {
                'accountName': $("#account_name option:selected").text(),
                'accountId': $("#account_name option:selected").val(),
                'voucher_entry': $("#voucher_entry option:selected").val(),
                'branch': $("#branch option:selected").val(),
                'debit_credit': $("#debit_credit option:selected").val(),
                'amount': $("#amount").val(),
                'narration': $("#narration").val(),
                'remarks_textarea': $("#remarks-textarea").val()
            };
            $newSaveCount = getNewCount();
            // console.log($newSaveCount);
            localStorage.setItem("IT-" + $newSaveCount, JSON.stringify(obj));
            countInsertedData++;
        }

        $(document).ready(function () {
            /**
             * @todo
             * on first load check local storage and loop data to generate table,
             * v.v.i count must be increased so that
             * next insert will create new key in localstorage
             */


            htmlTr = "";
            $countI = 0;

            checkForEntryData = false;

            for (var i = 0; i < localStorage.length; i++) {
                debit = 0;
                credit = 0;

                $countI = i + 1;
                /**get key of saved data*/
                key = localStorage.key(i);
                var splitKey = key.split('-');

                if (splitKey[0].trim() === "IT") {
                    $localStorageData = JSON.parse(localStorage.getItem(key));
                } else {
                    $localStorageData = null;
                }

                if ($localStorageData !== null) {

                    voucher_entry = $localStorageData.voucher_entry;
                    checkForEntryData = true;
                    if ($localStorageData.debit_credit === "+") {
                        debit = numberFormat($localStorageData.amount);
                        debitTotal = parseFloat(numberFormat(debitTotal)) + parseFloat(numberFormat(debit));
                        amount = debit;
                    } else {
                        credit = numberFormat($localStorageData.amount);
                        creditTotal = parseFloat(numberFormat(creditTotal)) + parseFloat(numberFormat(credit));
                        amount = credit * (-1);
                    }
                    deleteString = 'IT-' + $countI;
                    htmlTr += '<tr debited="'+debit+'" credited="'+credit+'">' +
                        '<td class="text-center">' + countInsertedData +
                        '<input type="hidden" name="accountId[]" value="' + $localStorageData.accountId + '">' +
                        '<input type="hidden" name="amount[]" value="' + amount + '">' +
                        '<input type="hidden" name="branch[]" value="' + $localStorageData.branch + '">' +
                        '<input type="hidden" name="narration[]" value="' + $localStorageData.narration + '">' +
                        '<input type="hidden" name="remarks[]" value="' + $localStorageData.remarks_textarea + '">' +
                        '<input type="hidden" name="voucher_entry[]" value="' + $localStorageData.voucher_entry + '">' +
                        '</td>' +
                        '<td class="text-center">'+ $localStorageData.accountId + '</td>' +
                        '<td class="text-center">' + $localStorageData.accountName + '</td>' +
                        '<td class="text-center" >' + numberFormatDisplay(debit) + '</td>' +
                        '<td class="text-center" >' + numberFormatDisplay(credit) + '</td>' +
                        '<td class="text-center">' + $localStorageData.narration + '</td>' +
                        '<td class="text-center">' +
                        // '    <a href="javascript:;" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>' +
                        '    <a href="javascript:;" class="btn btn-danger delete-data" deleteid="' + key + '"><i class="ri-delete-bin-fill"></i></a>' +
                        '</td>' +
                        '</tr>';
                    countInsertedData++;

                    debitamount = parseFloat(numberFormat($('#debit-total').text())) + parseFloat(numberFormat(debit));
                    creditamount =  parseFloat(numberFormat($('#credit-total').text())) + parseFloat(numberFormatDisplay(credit));
                    $('#debit-total').text(numberFormatDisplay(debitamount))
                    $('#credit-total').text(numberFormatDisplay(creditamount))

                }

            }



            $("#voucher-table-append").append(htmlTr);

          debitamount = parseFloat(numberFormat($('#debit-total').text()));
            creditamount =  parseFloat(numberFormat($('#credit-total').text()));
              //  alert(creditamount)
              diff = parseFloat(debitamount)-parseFloat(creditamount);
                $('#debit-total').text(numberFormatDisplay(debitamount)))
                $('#credit-total').text(numberFormatDisplay(creditamount))
                $('#difference-amount').text(numberFormatDisplay(diff));
            if (checkForEntryData === true) {
                $("#voucher_entry").val(voucher_entry);
                $('option', $("#voucher_entry")).not(':eq(0), :selected').remove();
                $("#transaction-form").trigger('reset');
                $("#voucher_entry").val(voucher_entry);
            }

        });

        function clearData() {
            window.localStorage.clear();
            $("#voucher-table-append").empty();
            debitTotal = 0;
            creditTotal = 0;
            difference = 0;
            $('#debit-total').val(0);
            $('#credit-total').val(0);
            $('#difference-amount').val(0);
            showAlert('Data Cleared');
        }

        function getNewCount() {

            for (var i = 0; i < localStorage.length; i++) {
                key = localStorage.key(i);
                var splitKey = key.split('-');

                if (splitKey[1] !== undefined && newNumber < parseInt(splitKey[1].trim())) {
                    newNumber = splitKey[1];
                } else {

                }
            }
            return parseInt(newNumber) + 1;
        }

        function saveFormData() {
            $("#save-button").prop("disabled", true);
            $localStorageData = JSON.parse(localStorage.getItem('IT-' + $countI));

            total = debitTotal - creditTotal;
            $("#today_date").val(todayDate);
            if ((total) !== 0 && total > 0) {
                showAlert('Cannot submit form, debit and credit not balanced', 'error');
                $("#save-button").prop("disabled", false);
                return false;
            }
            window.localStorage.clear();

            $("#final-save-data-form").submit();
        }

        function calculate() {
            debitTotal = 0;
            creditTotal = 0;

            for (var i = 0; i < localStorage.length; i++) {
                key = localStorage.key(i);
                var splitKey = key.split('-');

                if (splitKey[0].trim() === "IT") {
                    $localStorageData = JSON.parse(localStorage.getItem(key));
                } else {
                    $localStorageData = null;
                }
                if ($localStorageData !== null) {
                    debit = 0;
                    credit = 0;
                    if ($localStorageData.debit_credit && $localStorageData.debit_credit === "+") {
                        debit = numberFormat($localStorageData.amount);
                        debitTotal = parseFloat(numberFormat(debitTotal)) + parseFloat(numberFormat(debit));
                    }
                    if ($localStorageData.debit_credit && $localStorageData.debit_credit === "-") {
                        credit = numberFormat($localStorageData.amount);
                        creditTotal = parseFloat(numberFormat(creditTotal)) + parseFloat(numberFormat(credit));
                    }
                }
            }

            $("#account_name").val("").trigger('change');
            difference = parseFloat(numberFormat(debitTotal)) - parseFloat(numberFormat(creditTotal));
            $("#debit-total").empty().text(numberFormatDisplay(debitTotal));
            $("#credit-total").empty().text(numberFormatDisplay(creditTotal));
            $("#difference-amount").empty().text(numberFormatDisplay(difference));
        }

          function exportCategorywiseReport() {
            var billingmode = '%';

            var from_date =$('#engfromdate').val();
            var to_date = $('#engtodate').val();


            var comp = '%';
            var selectedItem = "";
            var dateType = "entry_date";
            var itemRadio = "all_items";
            var urlReport = baseUrl + "/mainmenu/group-report/export-categorywise-report?billingmode=" + billingmode + "&from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&selectedItem=" + selectedItem + "&dateType=" + dateType + "&itemRadio=" + itemRadio;
            window.open(urlReport, '_blank');
        }


    </script>

<script>
        $(window).ready(function () {
            $('.from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('.from_date').val(AD2BS($('#from_eng-all').val()));

            $('.to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('.to_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#today_date').val(AD2BS('{{date('Y-m-d')}}'));
        });

        function syncAccount(AccountNo) {
            let route = "{!! route('map.sync.by.account', ':ACCOUNT_NUMBER') !!}";
            route = route.replace(':ACCOUNT_NUMBER', AccountNo);

            $("#from_eng" + AccountNo).val(BS2AD($("#from" + AccountNo).val()))
            $("#to_eng" + AccountNo).val(BS2AD($("#to" + AccountNo).val()))
            let todayDate = AD2BS('{{date('Y-m-d')}}');
            $.ajax({
                url: route,
                type: "POST",
                data: $('#sync-form-' + AccountNo).serialize() + '&today_date=' + todayDate,
                success: function (response) {
                    if (response.success) {
                        showAlert('Sync Successful');
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        }

        function syncAccountAll(AccountNo) {
            let route = "{!! route('map.sync.all') !!}";

            $("#from_eng-all").val(BS2AD($("#from-all").val()))
            $("#to_eng-all").val(BS2AD($("#from-all").val()))


            $.ajax({
                url: route,
                type: "POST",
                data: $('#sync-form-all').serialize(),
                success: function (response) {
                    if (response.success) {
                        showAlert('Sync Successful');
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        }


        function transactionAccountAll(AccountNo) {


            $("#from_eng-all").val(BS2AD($("#from-all").val()))
            $("#to_eng-all").val(BS2AD($("#from-all").val()))
            var from_date = $("#from_eng-all").val();
            var to_date = $("#to_eng-all").val();
            var department =$("#dept option:selected").val();
           // alert(department);



                let route = "{!! route('transaction.add.all') !!}"+"?fromdate="+from_date+"&todate="+to_date+"&department="+department;
                location.href =route;









        }


        $(document).on('focusout', '#from-all', function () {
                $('#from_eng-all').val(BS2AD($('#from-all').val()));
                $('#to_eng-all').val(BS2AD($('#from-all').val()));

            });





    </script>

@endpush
