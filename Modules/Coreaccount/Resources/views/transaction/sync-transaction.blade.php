@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Transaction Entry
                            </h4>
                        </div>
                        <div class="accountsearchbox col-sm-4">
                            {{--                            <input type="text" class="form-control" placeholder="Search account group...">--}}
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
<!--                                                <option value="Payment">Payment</option>
                                                <option value="Receipt">Receipt</option>
                                                <option value="Contra">Contra</option>-->
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
                            <div class="d-flex justify-content-center mt-3 row">
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary" onclick="addVoucher()">Add</button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary" onclick="clearData()">Clear Data</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('transaction.add.by.account') }}" id="final-save-data-form" class="row" method="post">
            <input type="hidden" name="today_date" id="today_date">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        @csrf
                        <div class="form-group">
                            <div class="table-responsive res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Account Name</th>
                                        <th class="text-center">Debit Amt</th>
                                        <th class="text-center">Credit Amt</th>
                                        <th class="text-center" style="width: 250px;">Short Narration</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="voucher-table-append">
                                    @if($ledgers)
                                        <tr>
                                            @php
                                                $countRows = 1;
                                                $sumTotalCredit = 0;
                                                $sumTotalDebit = 0;
                                                $finalCount = 0;
                                                $ledgerDiscount = [];
                                            @endphp
                                            @foreach($ledgers as $key => $ledger)
                                                @php
                                                    if ($ledger->accountLedgerDiscount && !in_array($ledger->accountLedgerDiscount->AccountNo, $ledgerDiscount)){
$ledgerDiscount[$ledger->accountLedgerDiscount->AccountNo] = $ledger->accountLedgerDiscount->AccountName;
    }
                                                        $sumTotalCredit += $ledger->TranAmount;
                                                @endphp

                                                <input type="hidden" name="tempId[]" value="{{ $ledger->TranId }}">
                                            @endforeach
                                            {{--because all transaction have same data except for amount--}}
                                            <td class="text-center">{{ $countRows++ }}</td>
                                            <td class="text-center">
                                                <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information">{{ $AccountName }}</a>
                                            </td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">{{ $sumTotalCredit }}</td>
                                            <td class="text-center">Sync from Pat bill</td>
                                            <td></td>
                                        </tr>


                                        @if(count($ledgerDiscount))
                                            @foreach($ledgerDiscount as $key => $discount)
                                                @php
                                                    $sumTotalDebit += $ledgers->where('DisAccountNo', $key)->sum('DisAmount');
                                                @endphp
                                                <tr>
                                                    {{--because all transaction have same data except for amount--}}
                                                    <td class="text-center">{{ $countRows++ }}</td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" data-toggle="modal" data-target=".detailed-account-information">{{ $discount }}</a>
                                                    </td>
                                                    <td class="text-center">{{ $ledgers->where('DisAccountNo', $key)->sum('DisAmount') }}</td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center">Sync from Pat bill</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="text-center" colspan="2">Total</td>
                                        <td class="text-center" id="debit-total">{{ $sumTotalDebit }}</td>
                                        <td class="text-center" id="credit-total">{{ $sumTotalCredit }}</td>
                                        <td class="text-center" colspan="2">Difference Amt: <span id="difference-amount">0</span></td>
                                    </tr>
                                    </tfoot>
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
                                        <input type="text" name="cheque_number" class="form-control">
                                    </div>
                                </div>
                            <!--                                <div class="form-group form-row mt-2">
                                    <label for="" class="col-sm-4">Transaction Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                </div>-->
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label for="remarks-textarea" class="col-sm-2">Remarks:</label>
                                    <textarea class="form-control col-sm-10" rows="3" id="remarks-textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <div class="form-group form-row float-right">
                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-sync"></i>&nbsp;Generate PDF</a>&nbsp;
                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-p-rint"></i>&nbsp;Print</a>&nbsp;
                                    <button type="button" class="btn btn-primary btn-action" id="save-button" onclick="saveFormData();"><i class="fas fa-plus"></i>&nbsp;Save</button>
                                    <a href="javascript:;" onclick="clearData()" type="button" class="btn btn-primary btn-action bl-1"><i class="fas fa-sync"></i>&nbsp;Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade detailed-account-information" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ $AccountName }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body table-responsive res-table">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Bill No</th>
                            <th>Name</th>
                            <th>Amt</th>
                            <th>Dis</th>
                            <th>Total</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ledgers as $key => $ledger)
                            @if($ledger->patbill)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $ledger->patbill->billDetail->fldbillno }}</td>
                                    <td>{{ $ledger->patbill->flditemname }}</td>
                                    <td>{{ $ledger->patbill->flditemrate }}</td>
                                    <td>{{ $ledger->patbill->flddiscamt }}</td>
                                    <td>{{ $ledger->patbill->fldditemamt }}</td>
                                    <td>{{ $ledger->patbill->fldordtime }}</td>
                                </tr>
                            @endif
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
@endsection
@push('after-script')
    <script>
        var countInsertedData = {{$countRows}};
        var newNumber = {{$countRows}};
        var debitTotal = {{ $sumTotalDebit }};
        var creditTotal = {{ $sumTotalCredit }};
        var difference = 0;

        let todayDate =AD2BS('{{date('Y-m-d')}}');
        $(document).on('click', '#subgrpbtn', function () {
            if ($(this).hasClass('show')) {
                $('#subgrpDIV').hide();
                $(this).removeClass('show');
            } else {
                $('#subgrpDIV').show();
                $(this).addClass('show');
            }
        });

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
            $('#remarks-textarea').val($("#voucher_entry").val() + " Voucher ("+ AD2BS('{{date('Y-m-d')}}')+")");
        });

        $(document).on('click', '.delete-data', function () {
            // alert($(this).attr('deleteid'));
            if (confirm("Delete?")) {
                localStorage.removeItem($(this).attr('deleteid'));
                $(this).closest('tr').remove();
                calculate();
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
                debit = $("#amount").val();
                debitTotal = parseFloat(debitTotal) + parseFloat(debit);
                amount = debit;
            } else {
                credit = $("#amount").val();
                creditTotal = parseFloat(creditTotal) + parseFloat(credit);
                amount = credit * (-1);
            }
            difference = parseFloat(debitTotal) - parseFloat(creditTotal);
            deleteString = "IT-" + countInsertedData;

            voucher_entry = $("#voucher_entry").val();
            htmlTr = '<tr>' +
                '<td class="text-center">' + countInsertedData +
                '<input type="hidden" name="accountId[]" value="' + $("#account_name option:selected").val() + '">' +
                '<input type="hidden" name="amount[]" value="' + amount + '">' +
                '<input type="hidden" name="branch[]" value="' + $("#branch").val() + '">' +
                '<input type="hidden" name="narration[]" value="' + $("#narration").val() + '">' +
                '<input type="hidden" name="remarks[]" value="' + $("#remarks-textarea").val() + '">' +
                '<input type="hidden" name="voucher_entry[]" value="' + voucher_entry + '">' +
                '</td>' +
                '<td class="text-center">' + $("#account_name option:selected").text() + '</td>' +
                '<td class="text-center">' + debit + '</td>' +
                '<td class="text-center">' + credit + '</td>' +
                '<td class="text-center">' + $("#narration").val() + '</td>' +
                '<td class="text-center">' +
                // '    <a href="javascript:;" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>' +
                '    <a href="javascript:;" class="btn btn-danger delete-data" deleteid="' + deleteString + '"><i class="ri-delete-bin-fill"></i></a>' +
                '</td>' +
                '</tr>';

            saveToLocalStorage();

            $("#voucher-table-append").append(htmlTr);

            $("#debit-total").text(debitTotal.toFixed(2));
            $("#credit-total").text(creditTotal.toFixed(2));

            $("#difference-amount").text(parseFloat(difference).toFixed(2));
            $(".voucher_entry_for_old_data").val(voucher_entry)
            $('option', $("#voucher_entry")).not(':eq(0), :selected').remove();
            $("#transaction-form").trigger('reset');
            $("#voucher_entry").val(voucher_entry);
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
                        debit = $localStorageData.amount;
                        debitTotal = parseFloat(debitTotal) + parseFloat(debit);
                        amount = debit;
                    } else {
                        credit = $localStorageData.amount;
                        creditTotal = parseFloat(creditTotal) + parseFloat(credit);
                        amount = credit * (-1);
                    }
                    deleteString = 'IT-' + $countI;
                    htmlTr += '<tr>' +
                        '<td class="text-center">' + countInsertedData +
                        '<input type="hidden" name="accountId[]" value="' + $localStorageData.accountId + '">' +
                        '<input type="hidden" name="amount[]" value="' + amount + '">' +
                        '<input type="hidden" name="branch[]" value="' + $localStorageData.branch + '">' +
                        '<input type="hidden" name="narration[]" value="' + $localStorageData.narration + '">' +
                        '<input type="hidden" name="remarks[]" value="' + $localStorageData.remarks_textarea + '">' +
                        '<input type="hidden" name="voucher_entry[]" value="' + $localStorageData.voucher_entry + '">' +
                        '</td>' +
                        '<td class="text-center">' + $localStorageData.accountName + '</td>' +
                        '<td class="text-center">' + debit + '</td>' +
                        '<td class="text-center">' + credit + '</td>' +
                        '<td class="text-center">' + $localStorageData.narration + '</td>' +
                        '<td class="text-center">' +
                        // '    <a href="javascript:;" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>' +
                        '    <a href="javascript:;" class="btn btn-danger delete-data" deleteid="' + key + '"><i class="ri-delete-bin-fill"></i></a>' +
                        '</td>' +
                        '</tr>';
                    countInsertedData++;

                }

            }

            difference = parseFloat(debitTotal) - parseFloat(creditTotal);

            let displayDebitForCashHand = difference < 0 ? difference*(-1) : difference;
            debitTotal = displayDebitForCashHand+debitTotal;

            htmlTr += '<tr>' +
                '<td class="text-center">' + countInsertedData +
                '<input type="hidden" name="accountIdCash" value="{{ $accounts->where('AccountNo', Options::get('default_cash_in_hand'))->first()->AccountNo }}">' +
                '<input type="hidden" name="amountCash" value="' + parseFloat(displayDebitForCashHand).toFixed(2) + '">' +
                '<input type="hidden" name="branchCash" value="">' +
                '<input type="hidden" name="narrationCash" value="CASH IN HAND ('+todayDate+')">' +
                '<input type="hidden" name="remarksCash" value="CASH IN HAND ('+todayDate+')">' +
                '<input type="hidden" name="voucher_entryCash" value="Journal">' +
                '</td>' +
                '<td class="text-center">{{ $accounts->where('AccountNo', Options::get('default_cash_in_hand'))->first()->AccountName }}</td>' +
                '<td class="text-center">' + parseFloat(displayDebitForCashHand).toFixed(2) + '</td>' +
                '<td class="text-center">0</td>' +
                '<td class="text-center"></td>' +
                '<td class="text-center">' +
                // '    <a href="javascript:;" class="btn btn-primary"><i class="ri-edit-box-line"></i></a>' +
                '</td>' +
                '</tr>';

            difference = 0;

            $("#voucher-table-append").append(htmlTr);
            $("#debit-total").text(debitTotal.toFixed(2));
            $("#credit-total").text(creditTotal.toFixed(2));
            $("#difference-amount").text(parseFloat(difference).toFixed(2));
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

            $("#today_date").val(todayDate);
            total = debitTotal.toFixed(2) - creditTotal.toFixed(2);

            if ((total) !== 0) {
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
                        debit = $localStorageData.amount;
                        debitTotal = parseFloat(debitTotal) + parseFloat(debit);
                    }
                    if ($localStorageData.debit_credit && $localStorageData.debit_credit === "-") {
                        credit = $localStorageData.amount;
                        creditTotal = parseFloat(creditTotal) + parseFloat(credit);
                    }
                }
            }
            $("#account_name").val("").trigger('change');
            difference = parseFloat(debitTotal) - parseFloat(creditTotal);
            $("#debit-total").empty().text(debitTotal.toFixed(2));
            $("#credit-total").empty().text(creditTotal.toFixed(2));
            $("#difference-amount").empty().text(parseFloat(difference).toFixed(2));
        }
    </script>
@endpush
