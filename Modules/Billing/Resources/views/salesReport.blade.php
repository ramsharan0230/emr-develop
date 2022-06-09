@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Sales report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="sales_filter_data">
                            <div class="row">

                                <div class="col-lg-2 col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>

                                </div>
                                

                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        
                                        <div class="col-sm-7">
                                            <select name="department" id="" class="form-control department">
                                                <option value="">--Department--</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        @if($dept->departmentData)
                                                            <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @endif
                                                @empty

                                                @endforelse
                                            @endif
                                            <!-- <option value="Male"></option> -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                                               

                                <div class="col-sm-5">
                                    <div class="d-flex float-right">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchSalesDetail()"><i class="fa fa-filter"></i>&nbsp;
                                            Filter</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportSalesReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                            Export</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportSalesReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                        Export To Excel</a>&nbsp;

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
                                <div class="table-responsive res-table table-sticky-th">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>S.no</th>
                                            <th>Date (BS)</th>
                                            <th>Bill No</th>
                                            <th>PatientName</th>
                                            <th>PatientPAN</th>
                                            <th>Item Name</th>
                                            <th>ItemQty</th>
                                            <th>ItemTotal</th>
                                            <th>NonTaxableAmt</th>
                                            <th>TaxableAmt</th>
                                            <th>Tax</th>
                                            <th>ItemRate</th>
                                            <th>ExportCountry</th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody id="sales_result">
                                        
                                      
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
    @include('billing::modal.user-list')
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

        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchSalesDetail(page);
        });

        function searchSalesDetail(page) {

            var url = "{{route('searchSalesDetail')}}";
            
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            $.ajax({
                url: url + "?page=" + page,
                type: "get",
                data: $("#sales_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    $('#sales_result').empty().html(response.html)
                    
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportSalesReport() {
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            var data = $("#sales_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/sales/export-sales-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }

        function exportSalesReportToExcel() {
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            var data = $("#sales_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/sales/export-sales-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }
       
    </script>
@endpush


