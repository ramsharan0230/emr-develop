@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                User Collection report
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
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date">
                                        </div>
                                        <!--  <div class="col-sm-3">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Department:</label>
                                        <div class="col-sm-6">
                                            <select name="department" class="form-control">
                                                <option value=""></option>
                                                @if(count($hospital_departments) > 0 )
                                            @foreach($hospital_departments as $hospital_department)

                                            <option value="{{$hospital_department->fldcomp}}">{{$hospital_department->name}}</option>

                                            @endforeach
                                        @endif

                                            </select>

                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex flex-row justify-content-end">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action mr-1" onclick="searchCollectionBillingDetail()"><i class="fa fa-sync"></i>&nbsp;
                                            Refresh</a>
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action mr-1" onclick="exportUserBillingReport()"><i class="fas fa-file-pdf"></i>&nbsp;
                                            pdf</a>
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportUserCollectionExcelReport()"><i class="fa fa-code"></i>&nbsp;
                                            Export</a>
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
                            <!-- <li class="nav-item">
                               <a class="nav-link" id="chart-tab-two" data-toggle="tab" href="#chart" role="tab" aria-controls="profile" aria-selected="false">Chart:QTY</a>
                            </li>
                            <li class="nav-item">
                               <a class="nav-link" id="amt-tab-two" data-toggle="tab" href="#amt-two" role="tab" aria-controls="contact" aria-selected="false">Chart:AMT</a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table">
                                    <table class="table table-striped table-hover table-bordered bilingresult">
                                        <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2">User Name</th>
                                            <th rowspan="2">Department</th>
                                            <th colspan="6" style="text-align: center;">OP Collection</th>
                                            <th colspan="6" style="text-align: center;">IP Collection</th>
                                            <th rowspan="2">Deposit </th>
                                            <th rowspan="2">Deposit Return </th>
                                            <th rowspan="2"> Total Collection</th>
                                            {{-- <th rowspan="2"> Miscellaneous</th> --}}
                                            <th rowspan="2"> Grand Total Collection</th>
                                        </tr>
                                        <tr>
                                            <th>Cash Bill(+)</th>
                                            <th>Cash Refund(-)</th>
                                            <th>Net Cash Total</th>

                                            <th>Credit Bill(+)</th>
                                            <th>Credit Refund(-)</th>
                                            <th>Net Credit Total</th>

                                            <th>Cash Bill(+)</th>
                                            <th>Cash Refund(-)</th>
                                            <th>Net Cash Total</th>

                                            <th>Credit Bill(+)</th>
                                            <th>Credit Refund(-)</th>
                                            <th>Net Credit Total</th>
                                        </tr>
                                        </thead>
                                        <tbody id="user_billing_result">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--  <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                                   <div id="qty-chart"></div>
                             </div>
                             <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                             </div> -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <!-- am core JavaScript -->
    <script src="{{ asset('new/js/core.js') }}"></script>
    <!-- am charts JavaScript -->
    <script src="{{ asset('new/js/charts.js') }}"></script>
    {{-- Apex Charts --}}
    <script src="{{ asset('js/apex-chart.min.js') }}"></script>
    <!-- am animated JavaScript -->
    <script src="{{ asset('new/js/animated.js') }}"></script>
    <!-- am kelly JavaScript -->
    <script src="{{ asset('new/js/kelly.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $(".department").select2();
            }, 1500);
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                searchCollectionBillingDetail(page);
            });

            $('#eng_from_date').val(BS2AD($('#from_date').val()));
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });


        function searchCollectionBillingDetail(page) {
            var url = "{{route('searchCollectionBillingDetail')}}";

            if (page !== undefined) {
                url = url + "?page=" + page
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    // $("#bilingresult").dataTable().fnDestroy()
                    // $("#bilingresult").dataTable({
                    //     // ... skipped ...
                    // });
                    $('#user_billing_result').empty().html(response);

                    // $(".bilingresult").DataTable().fnDestroy();
                    // $('.bilingresult').DataTable( {
                    //     "columnDefs": [ {
                    //         "visible": false,
                    //         "targets": -1
                    //     } ]
                    // } );

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportUserBillingReport() {
            // alert('export');
            var data = $("#billing_filter_data").serialize();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/export-collection-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');
        }
        function exportUserCollectionExcelReport() {
            // alert('export');
            var data = $("#billing_filter_data").serialize();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/export-collection-excel-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');
        }


    </script>
@endpush


