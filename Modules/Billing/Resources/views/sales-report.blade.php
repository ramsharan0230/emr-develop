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
                        <form id="billing_filter_data">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="d-flex float-right">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchSalesDetail()"><i class="fa fa-filter"></i>&nbsp;Filter</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportSalesReport()"><i class="fa fa-file-pdf"></i>&nbsp;Export</a>&nbsp;
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
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table table-sticky-th">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light" style="text-align: center;">
                                            <tr>
                                                <th colspan="4">Invoice</th>
                                                <th rowspan="2">Total Sales</th>
                                                <th rowspan="2">Non TaxableSales</th>
                                                <th rowspan="2">ExportSales</th>
                                                <th rowspan="2">Discount</th>
                                                <th colspan="2">Taxable Sales</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Bill no</th>
                                                <th>Buyer's Name</th>
                                                <th>Buyer's  PAN Number</th>
                                                <th>Amount</th>
                                                <th>Tax(Rs)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-sales-report-tbody"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align: right;">Total Amount:</th>
                                                <th id="js-grosstotalsales-tfoot-th"></th>
                                                <th id="js-grossnontaxablesales-tfoot-th"></th>
                                                <th id="js-grossexportsales-tfoot-th"></th>
                                                <th id="js-grossdiscount-tfoot-th"></th>
                                                <th id="js-grosstaxableamount-tfoot-th"></th>
                                                <th id="js-grosstax-tfoot-th"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <script type="text/javascript">
        function searchSalesDetail() {
            $.ajax({
                url: "{{ route('salesReport') }}",
                type: "GET",
                data: $('#billing_filter_data').serializeArray(),
                success: function (response) {
                    var trElem = "";
                    var grosstotalsales = 0;
                    var grossnontaxablesales = 0;
                    var grossexportsales = 0;
                    var grossdiscount = 0;
                    var grosstaxableamount = 0;
                    var grosstax = 0;
                    $.each(response, function(i, data) {
                        grosstotalsales += data.totalsales;
                        grossnontaxablesales += data.nontaxablesales;
                        grossexportsales += data.exportsales;
                        grossdiscount += data.discount;
                        grosstaxableamount += data.taxableamount;
                        grosstax += data.tax;

                        trElem += "<tr>";
                        trElem += "<td>" + data.fldtime + "</td>";
                        trElem += "<td>" + data.fldbillno + "</td>";
                        trElem += "<td>" + data.fldfullname + "</td>";
                        trElem += "<td>" + data.fldpannumber + "</td>";
                        trElem += "<td>" + data.totalsales.toFixed(2) + "</td>";
                        trElem += "<td>" + data.nontaxablesales.toFixed(2) + "</td>";
                        trElem += "<td>" + data.exportsales.toFixed(2) + "</td>";
                        trElem += "<td>" + data.discount.toFixed(2) + "</td>";
                        trElem += "<td>" + data.taxableamount.toFixed(2) + "</td>";
                        trElem += "<td>" + data.tax.toFixed(2) + "</td>";
                        trElem += "</tr>";
                    });

                    $('#js-sales-report-tbody').html(trElem);
                    $('#js-grosstotalsales-tfoot-th').text(grosstotalsales.toFixed(2));
                    $('#js-grossnontaxablesales-tfoot-th').text(grossnontaxablesales.toFixed(2));
                    $('#js-grossexportsales-tfoot-th').text(grossexportsales.toFixed(2));
                    $('#js-grossdiscount-tfoot-th').text(grossdiscount.toFixed(2));
                    $('#js-grosstaxableamount-tfoot-th').text(grosstaxableamount.toFixed(2));
                    $('#js-grosstax-tfoot-th').text(grosstax.toFixed(2));
                }
            });
        }

        function exportSalesReport() {
            var url = "{{ route('salesReportExport') }}?" + $('#billing_filter_data').serialize();
            window.open(url, '_blank');
        }

        $(document).ready(function () {
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
        });
    </script>
@endpush
