@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Account Statement
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">From Date:<span class="text-danger">*</span></label>
                                    <div class="">
                                        <input type="text" name="from_date" id="from_date" class="form-control" value="{{isset($dateStart) ? $dateStart : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">To Date:<span class="text-danger">*</span></label>
                                    <div class="">
                                        <input type="text" name="to_date" id="to_date" class="form-control" value="{{isset($date) ? $date : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">Account No:</label>
                                    <div class="">
                                        <select name="account_num" id="account_num" class="select2" required>
                                            <option value="">Select</option>
                                            @if($accounts)
                                                @forelse($accounts as $account)
                                                    <option
                                                        value="{{ $account->AccountNo }}" {{ request()->get('account_num') == $account->AccountNo ? "selected" : "" }}>{{ $account->AccountName }}</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">Voucher: </label>
                                    <div class="">
                                        <select name="voucher_entry" id="voucher_entry" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Journal">Journal</option>
                                            <option value="Payment">Payment</option>
                                            <option value="Receipt">Receipt</option>
                                            <option value="Contra">Contra</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">Voucher Number:</label>
                                    <div class="">
                                        <input type="text" name="voucher_number" id="voucher_number" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="" class="">Voucher No: </label>
                                    <div class="">
                                        <input type="text" name="voucher_no" class="form-control" id="voucher_no" placeholder="Voucher Number">
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-sm-2 mt-4">
                                <div class="form-group">
                                    {{-- <label for="" class="">Ledger Name:</label>
                                    <div >
                                        <input type="text" id="ledger_name" class="form-control" placeholder="Enter Ledger Name">
                                    </div> --}}
                                    <div class="">
                                        <button type="button" class="btn btn-primary btn-action" onclick="filterStatement()"><i class="fa fa-search"></i>&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header justify-content-between mt-3">
                        <button type="button" class="btn btn-primary btn-action float-right ml-1" onclick="printStatement()"><i class="fa fa-print"></i>
                            Print
                        </button>
                        <button type="button" class="btn btn-primary btn-action float-right" onclick="exportStatement()"><i class="fa fa-arrow-circle-down"></i>
                            Export
                        </button>&nbsp;
                    </div>
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="table-responsive res-table">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Tran Date</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Voucher Type</th>
                                        <th class="text-center">Voucher No</th>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Balance</th>
                                        <!-- <th class="text-center">Type</th> -->
                                        <th class="text-center">Cheque No</th>
                                        <th class="text-center">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody id="statement-lists">
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
@push('after-script')
    <script>
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });

        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });

        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                filterStatement(page);
            });
        });

        $('#account_num').on('change',function(){
            // alert('')
            if($(this).val() !=''){
                $('#voucher_number').val('');
                $('#voucher_number').prop('disabled', true);
            }else{
               $('#voucher_number').prop('disabled', false); 
            }
        });
        $('#voucher_entry').on('change',function(){
            if($(this).val() !=''){
                $('#voucher_number').val('');
                $('#voucher_number').prop('disabled', true);
            }else{
                $('#voucher_number').prop('disabled', false);
            }
        });

        function filterStatement(page) {
            var url = "{{route('accounts.statement.filter')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "GET",
                data: {
                    'from_date': $('#from_date').val(),
                    'to_date': $('#to_date').val(),
                    'account_num': $('#account_num').val(),
                    'voucher_number': $('#voucher_number').val(),
                    'voucher_code': $('#voucher_entry').val()
                },
                success: function (response) {
                    if (response.data.status) {
                        $('#statement-lists').html(response.data.html);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportStatement() {

            var urlReport = baseUrl + "/account/statement/export?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&voucher_code=" + $('#voucher_entry').val()+"&voucher_number="+$('#voucher_number').val()+"&account_number=" + $('#account_num').val();
            window.open(urlReport);
        }

        function printStatement() {
            var urlReport = baseUrl + "/account/statement/print?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&voucher_code=" + $('#voucher_entry').val()+"&voucher_number=" + $('#voucher_number').val()+"&account_number="+$('#account_num').val();
            window.open(urlReport, '_blank');
        }

        $(document).on('click', '.voucher_details', function () {
            var urlReport = baseUrl + "/account/statement/voucher-details?voucher_no=" + $(this).html();
            window.open(urlReport, '_blank');
        });

    </script>
@endpush
