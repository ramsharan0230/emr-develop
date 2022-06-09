 @extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account Map List
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 p-0">
                <div class="row flex-row">
                    <div class="col-sm-9">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <small>Note: Must insert cash in hand, default ledger for discount and tax <a href="{{ route('account.setting') }}" target="_blank" class="btn btn-primary">HERE</a> before sync.</small>
                                </div>
                                <div class="form-group">
                                    <form action="javascript:;" id="sync-form-all" method="post">

                                        <div class="d-flex flex-column bg-light rounded mt-3 p-2">
                                            <div class="dept d-flex flex-row pt-2 pb-2">
                                                <div class="col-3">Account Name</div>
                                                <div class="col-6">
                                                    @if($ledgers->pluck('AccountNo'))
                                                    @foreach($ledgers->pluck('AccountNo') as $account)
                                                        <input type="hidden" name="accountNum[]" value="{{ $account }}">
                                                    @endforeach
                                                    @endif
                                                    @csrf
                                                </div>
                                            </div>

                                            <div class="dept d-flex flex-row pt-2 pb-2">
                                                <div class="col-2">Department</div>
                                                <div class="col-6">
                                                    <select class="form-control"  id="dept">
                                                        @foreach (Session::get('user_hospital_departments') as $hosp_dept)
                                                            <option value="{{ $hosp_dept->fldcomp }}" data-comp="{{ $hosp_dept->fldcomp }}">{{ $hosp_dept->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row pt-2 pb-2">
                                                <div class="col-2">Date</div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control form-control-sm from_date" autocomplete="off" id="from-all" width="auto">
                                                    <input type="hidden" name="from_date" class="from_date_eng" id="from_eng-all" value="{{ date('Y-m-d') }}">
                                                    <input type="hidden" name="today_date" class="today_date" id="today_date">
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row  pt-2 pb-2">
                                                <!-- <div class="col-2">To</div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control form-control-sm" autocomplete="off" id="to-all" width="auto"> -->
                                                    <input type="hidden" name="to_date" class="to_date_eng" value="{{ date('Y-m-d') }}" id="to_eng-all">
                                                <!-- </div> -->
                                            </div>
                                            <div class="col-sm-8 pt-2">
                                                <div class="float-right">
                                                    <button type="button" onclick="transactionAccountAll()" class="btn btn-primary mr-1">View Transaction</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="d-flex flex-column">
                            <div>
                                <div class="iq-card iq-card-block iq-card-stretch">
                                    <div class="iq-card-body">
                                        <div class="table-responsive res-table">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Miscellaneous</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Miscellaneous</td>
                                                    <td style="text-align: right">
                                                        <a href="{{ route('transaction.view.miscellaneous') }}" class="btn btn-primary">View</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="iq-card iq-card-block iq-card-stretch">
                                    <div class="iq-card-body">
                                        <div class="form-group">
                                            <div class="table-responsive res-table">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2">Doctor Fraction</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">Doctor Fraction</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right">
                                                            <a href="{{ route('transaction.view.doctor') }}" class="btn btn-primary">View</a>
                                                            <a href="{{ route('transaction.map.doctor') }}" class="btn btn-primary">Map</a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="iq-card iq-card-block iq-card-stretch">
                                    <div class="iq-card-body">
                                        <div class="form-group">
                                            <div class="table-responsive res-table">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Discount</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Discount</td>
                                                        <td style="text-align: right">
                                                            <!-- {{--                                            <a href="{{ route('transaction.view.doctor') }}" class="btn btn-primary">View</a>--}} -->
                                                            <a href="{{ route('discount.map') }}" class="btn btn-primary">Map</a>
                                                        </td>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        $(window).ready(function () {
            $('.from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 10 // Options | Number of years to show
            });

            $('.from_date').val(AD2BS('{{date('Y-m-d')}}'));

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
                window.open(route);








        }


        $(document).on('focusout', '#from-all', function () {
                $('#from_eng-all').val(BS2AD($('#from-all').val()));
                $('#to_eng-all').val(BS2AD($('#from-all').val()));

            });





    </script>
@endpush
