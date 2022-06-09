@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Day Book
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">From Date:<span class="text-danger">*</span></label>
                                    <div class="">
                                        <input type="text" name="from_date" id="from_date" class="form-control"
                                               value="{{isset($date) ? $date : ''}}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">To Date:<span class="text-danger">*</span></label>
                                    <div class="">
                                        <input type="text" name="to_date" id="to_date" class="form-control"
                                               value="{{isset($date) ? $date : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">Voucher Type:</label>
                                    <div class="">
                                        <select name="voucher_type" id="voucher_type" class="form-control select2">
                                            <option value="%">All</option>
                                            @foreach ($voucher_types as $voucher_type)
                                                <option value="{{$voucher_type}}">{{$voucher_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">Voucher No:</label>
                                    <div class="">
                                        <select name="voucher_number" id="voucher_number" class="form-control select2">
                                            <option value="%">All</option>
                                            @foreach ($voucher_number as $voucher_no)
                                                <option value="{{$voucher_no}}">{{$voucher_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="" class="">User:</label>
                                    <div class="">
                                        <select name="user" id="username" class="form-control select2">
                                            <option value="%">All</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user}}">{{$user}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="btn btn-primary btn-action" onclick="filterDaybook()"><i
                                    class="fa fa-search"></i>&nbsp;Search
                            </button>&nbsp;
                            <a href="{{ route('accounts.close.day') }}" class="btn btn-primary btn-action "><i
                                    class="far fa-stop-circle"></i>&nbsp;Day Close</a>&nbsp;
                            <button type="button" class="btn btn-primary btn-action " onclick="exportExcel()"><i
                                    class="fa fa-download"></i>&nbsp;Export Excel
                            </button>&nbsp;

                            <button type="button" class="btn btn-primary btn-action " onclick="exportPdf()"><i
                                    class="fa fa-file-pdf"></i>&nbsp;PDF
                            </button>

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
                                        <th class="text-center">Voucher No</th>
                                        <th class="text-center">Voucher Type</th>
                                        <th class="text-center">Voucher Date</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">User</th>
                                        <th class="text-center">Branch</th>
                                    </tr>
                                    </thead>
                                    <tbody id="daybookLists">
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
            @if(Session::has('message'))
            showAlert("{{Session::get('message')}}");
            @endif

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                filterDaybook(page);
            });
        });

        function filterDaybook(page) {
            var url = "{{route('accounts.daybook.filter')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "GET",
                data: {
                    'from_date': $('#from_date').val(),
                    'to_date': $('#to_date').val(),
                    'voucher_type': $('#voucher_type').val(),
                    'voucher_number': $('#voucher_number').val(),
                    'user': $('#username').val()
                },
                success: function (response) {
                    if (response.data.status) {
                        $('#daybookLists').html(response.data.html);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportExcel() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var voucher_type = $('#voucher_type').val();
            var voucher_number = $('#voucher_number').val();
            var user = $('#username').val();

            var urlReport = baseUrl + "/account/daybook/export-excel?from_date=" + from_date +'&to_date='+to_date+'&voucher_type='+voucher_type+'&voucher_number='+voucher_number+'&user='+user;
            window.open(urlReport, '_blank');


        }

        function exportPdf() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var voucher_type = $('#voucher_type').val();
            var voucher_number = $('#voucher_number').val();
            var user = $('#username').val();

            var urlReport = baseUrl + "/account/daybook/export-pdf?from_date=" + from_date +'&to_date='+to_date+'&voucher_type='+voucher_type+'&voucher_number='+voucher_number+'&user='+user;
            window.open(urlReport, '_blank');


        }

        $(document).on('click', '.voucher_details', function () {
            var urlReport = baseUrl + "/account/daybook/voucher-details?voucher_no=" + $(this).html();
            window.open(urlReport, '_blank');
        });

        $('#voucher_type').on('change', function(){
            var vouchertype = $(this).val();
            $.ajax({
                url: baseUrl + '/account/daybook/getVoucherNumber',
                type: "POST",
                data: {vouchertype:vouchertype},
                success: function (response) {
                    $('#voucher_number').empty().html(response);
                    setTimeout(function () {
                        $('#voucher_number').select2();
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        })
    </script>
@endpush
