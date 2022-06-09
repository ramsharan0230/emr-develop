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
                                                <option value="Payment">Payment</option>
                                                <option value="Receipt">Receipt</option>
                                                <option value="Contra">Contra</option>
                                            </select>
                                        </div>
                                        <!--                                        <div class="col-sm-2">
                                                                                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#accountModal"><i class="fa fa-plus"></i></button>
                                                                                </div>-->
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
                                                                                <button type="button" class="btn btn-primary" onclick="addVoucher()">Add</button>

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
        <form action="{{ route('transaction.store') }}" id="final-save-data-form" class="row" method="post">
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
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="text-center" colspan="2">Total</td>
                                        <td class="text-center" id="debit-total">0</td>
                                        <td class="text-center" id="credit-total">0</td>
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
<!--                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-sync"></i>&nbsp;Generate PDF</a>&nbsp;
                                    <a href="#" type="button" class="btn btn-primary btn-action"><i class="fas fa-p-rint"></i>&nbsp;Print</a>&nbsp;-->
                                    <button type="button" class="btn btn-primary btn-action mr-1" id="save-button" onclick="saveFormData();"><i class="fas fa-plus"></i>&nbsp;Save</button>
<!--                                    <a href="javascript:;" onclick="clearData()" type="button" class="btn btn-primary btn-action bl-1"><i class="fas fa-sync"></i>&nbsp;Reset</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('after-script')
    <script>
        var countInsertedData = 1;
        var newNumber = 1;
        var debitTotal = 0;
        var creditTotal = 0;
        var difference = 0;
        let todayDate = AD2BS('{{date('Y-m-d')}}');

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
        })

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
            $('#remarks-textarea').val($("#voucher_entry").val() + " Voucher (" + AD2BS('{{date('Y-m-d')}}') + ")");
        });

        $(document).on('click', '.delete-data', function () {
            if (confirm("Delete?")) {
                if (window.localStorage.removeItem($(this).attr('deleteid'))) {
                    $(this).closest('tr').remove();
                } else {
                    // showAlert('Something went wrong.', 'error');
                    window.location.href = window.location.href;
                }

                calculate();
            }
            return false;
        });

        function calculate() {
            debitTotal = 0;
            creditTotal = 0;
            let $localStorageData = null;
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
                        debitTotal = (parseFloat(debitTotal) + parseFloat(debit)).toFixed(2);
                    }
                    if ($localStorageData.debit_credit && $localStorageData.debit_credit === "-") {
                        credit = $localStorageData.amount;
                        creditTotal = (parseFloat(creditTotal) + parseFloat(credit)).toFixed(2);
                    }
                }
            }
            $("#account_name").val("").trigger('change');
            difference = parseFloat(debitTotal) - parseFloat(creditTotal);
            $("#debit-total").empty().text(debitTotal);
            $("#credit-total").empty().text(creditTotal);
            $("#difference-amount").empty().text(parseFloat(difference));
        }

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
                debitTotal = (parseFloat(debitTotal) + parseFloat(debit)).toFixed(2);
                amount = parseFloat(debit).toFixed(2);
            } else {
                credit = $("#amount").val();
                creditTotal = (parseFloat(creditTotal) + parseFloat(credit)).toFixed(2);
                amount = credit * (-1);
                amount = parseFloat(amount).toFixed(2);
            }
            difference = (parseFloat(debitTotal) - parseFloat(creditTotal)).toFixed(2);
            countInsertedData++;
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
                '<input type="hidden" name="asdf[]" value="' + countInsertedData + '">' +
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

            $("#debit-total").text(debitTotal);
            $("#credit-total").text(creditTotal);
            $("#account_name").val("").trigger('change');
            $("#difference-amount").text(parseFloat(difference));
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
                        debitTotal = (parseFloat(debitTotal) + parseFloat(debit)).toFixed(2);
                        amount = parseFloat(debit).toFixed(2);
                    } else {
                        credit = $localStorageData.amount;
                        creditTotal = (parseFloat(creditTotal) + parseFloat(credit)).toFixed(2);
                        amount = credit * (-1);
                        amount = parseFloat(amount).toFixed(2);
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
                        '<input type="hidden" name="asdf[]" value="' + key + '">' +
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
            $("#voucher-table-append").append(htmlTr);
            $("#debit-total").text(debitTotal);
            $("#credit-total").text(creditTotal);
            $("#difference-amount").text(parseFloat(difference));
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
            $('#debit-total').text(0);
            $('#credit-total').text(0);
            $('#difference-amount').text(0);
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
            setTimeout(function(){ $("#save-button").prop("disabled", false); }, 3000);

            // $localStorageData = JSON.parse(localStorage.getItem('IT-' + $countI));

            total = debitTotal - creditTotal;
            $("#today_date").val(todayDate);
            if ((total) !== 0) {
                showAlert('Cannot submit form, debit and credit not balanced', 'error');
                $("#save-button").prop("disabled", false);
                return false;
            }
            window.localStorage.clear();
            $("#final-save-data-form").submit();
        }
    </script>
    @if(Session::has('voucher_number'))
        <script>
            var urlReport = baseUrl + "/account/statement/voucher-details?voucher_no={{ Session::get('voucher_number')}}";
            window.open(urlReport, '_blank');
        </script>
    @endif
@endpush
