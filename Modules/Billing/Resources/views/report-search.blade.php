@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Billing report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
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
                            <li class="nav-item">
                                <a class="nav-link" id="chart-tab-two" data-toggle="tab" href="#chart" role="tab" aria-controls="profile" aria-selected="false">Chart:QTY</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="amt-tab-two" data-toggle="tab" href="#amt-two" role="tab" aria-controls="contact" aria-selected="false">Chart:AMT</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Invoice</th>
                                            <th>EnciD</th>
                                            <th>Name</th>
                                            <th>OldDepo</th>
                                            <th>TotAmt</th>
                                            <th>TaxAmt</th>
                                            <th>DiscAmt</th>
                                            <th>NetTot</th>
                                            <th>RecAMT</th>
                                            <th>NewDepo</th>
                                            <th>User</th>
                                            <th>InvType</th>
                                            <th>BankName</th>
                                            <th>ChequeNo</th>
                                            <th>TaxGroup</th>
                                            <th>DiscGroup</th>
                                        </tr>
                                        </thead>
                                        <tbody id="billing_result">
                                        @if(isset($results) and count($results) > 0)
                                            @forelse($results as $k=>$r)
                                                @php
                                                    $datetime = explode(' ', $r->fldtime);
                                                    $enpatient = \App\Encounter::where('fldencounterval',$r->fldencounterval)->with('patientInfo')->first();
                                                     $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
                                                     $sn = $k+1;
                                                @endphp
                                                <tr>
                                                    <td>{{$sn}}</td>
                                                    <td><a href="javascript:void(0);" class="btn btn-primary bill" data-bill="'.$r->fldbillno.'"><i class="fas fa-print"></i></a></td>
                                                    <td>{{$datetime[0]}}</td>
                                                    <td>{{$datetime[1]}}</td>
                                                    <td>{{$r->fldbillno}}</td>
                                                    <td>{{$r->fldencounterval}}</td>
                                                    <td>{{$fullname}}</td>
                                                    <td>{{$r->fldprevdeposit}}</td>
                                                    <td>{{$r->flditemamt}}</td>
                                                    <td>{{$r->fldtaxamt}}</td>
                                                    <td>{{$r->flddiscountamt}}</td>
                                                    <td>{{$r->fldchargedamt}}</td>
                                                    <td>{{$r->fldreceivedamt}}</td>
                                                    <td>{{$r->fldcurdeposit}}</td>
                                                    <td>{{$r->flduserid}}</td>
                                                    <td>{{$r->fldbilltype}}</td>
                                                    <td>{{$r->fldbankname}}</td>
                                                    <td>{{$r->fldchequeno}}</td>
                                                    <td>{{$r->fldtaxgroup}}</td>
                                                    <td>{{$r->flddiscountgroup}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="20" class="text-center">
                                                        <em>No data available in table ...</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @endif

                                        </tbody>
                                        @if(isset($results) and count($results) > 0)
                                            <tfoot>
                                            <tr>
                                                <td colspan="20">{!! $results->appends(request()->all())->links() !!}</td>
                                            </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                                <div id="qty-chart"></div>
                            </div>
                            <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                            </div>
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
                searchBillingDetail(page);
            });
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

        function searchBillingDetail(page) {
            alert('adsf')
            var params = {
                page: page,
                eng_from_date:'{{$finalfrom}}',
                eng_to_date:'{{$finalto}}',
                search_type:'{{$search_type}}',
                search_type_text:'{{$search_text}}',
                department:'{{$department}}',
                search_name:'{{$search_name}}',
                cash_credit:'{{$cash_credit}}',
                billing_mode:'{{$billingmode}}',
                report_type:'{{$report_type}}',
                item_type:'{{$item_type}}',

            };
            var queryString = $.param(params);

            var url = "{{route('searchBillingDetail')}}?"+queryString;

            $.ajax({
                url: url,
                type: "get",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    $('#billing_result').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


    </script>
@endpush


