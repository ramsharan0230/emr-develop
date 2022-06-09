@extends('frontend.layouts.master')

@push('after-styles')
    {{-- <style>
        #myTable_filter {
            width: 100%;
            padding: 14px 0;
            background: #f8f9fa;
        }

        #myTable_filter label {
            width: 100%;
            text-align: left;
        }

        #myTable_filter label input {
            width: 74%;
            margin-left: 55px;
        }

        #myTable_paginate {
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
        }

        #myTable_paginate span {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* td.scrollable {
            white-space: nowrap;
            overflow-x: auto;
            max-width: 150px;
            height: 20px;
        }

        td.scrollable::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        td.scrollable::-webkit-scrollbar-track {
            border-radius: 10px;
            background: #e5e5e5;
        }

        td.scrollable::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: #c4c4c4;
        } */

        .title {
            width: 15%;
        }

        .desc {
            width: 85%;
            font-weight: 600;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            border: 2px solid #35fc74;
        }

    </style> --}}
@endpush

@section('content')
    @php $grandtotal = 0; @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header form-row">
                        <div class="col-sm-7">
                            <div class="iq-header-title">
                                <h4 class="card-title">NearExpiry Report</h4>
                            </div>
                        </div>
                         <div class="col-sm-5 text-right p-2">
                             <form method="get">
                                  <div class="form-row">
                                    <div class="col-sm-5">
                                        <select id="js-near-expiry-range-select" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="30">30Days</option>
                                            <option value="60">60Days</option>
                                            <option value="90">90Days</option>
                                            <option value="180">180Days</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" id="js-near-expiry-date-input" autocomplete="off" class="form-control">
                                        <input type="hidden" name="date" id="js-near-expiry-englishdate-input" class="form-control">
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <button class="btn btn-primary btn-action"><i class="fa fa-search"></i>&nbsp;Search</button>
                                    </div>
                                  </div>
                            </form>
                         </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="iq-card iq-card-stretch iq-card-height">
                            {{-- <div class="col-sm-2 mb-3">
                                <input type="text" class="form-control" id="js-search">
                            </div> --}}
                            <div class="iq-card-body">
                                <table id="myTable1" data-show-columns="true"
                                data-search="true"
                                data-show-toggle="true"
                                data-pagination="true"
                                data-resizable="true">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Stock Id</th>
                                            <th>Supplier</th>
                                            <th>Purchase Number</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Total</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expenses-table">
                                        @if ($medicines)
                                            @foreach ($medicines as $medicine)
                                                @php
                                                    $total = $medicine->fldqty*$medicine->fldsellpr;
                                                    $grandtotal += $total;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $medicine->fldstockid }}</td>
                                                    @if(isset($medicine->hasTransfer))
                                                    @php $suppliername = Helpers::getSuppName($medicine->hasTransfer->fldoldstockno);
                                                    @endphp
                                                    @else
                                                    @php $suppliername = Helpers::getSuppName($medicine->fldstockno);
                                                    @endphp
                                                    @endif
                                                    <td>{{ isset($suppliername) ? $suppliername->fldsuppname : '' }}</td>
                                                    <td>{{ isset($suppliername) ? $suppliername->fldreference : '' }}</td>
                                                    <td>{{ $medicine->fldbatch }}</td>
                                                    <td>{{ explode(' ', $medicine->fldexpiry)[0] }}</td>
                                                    <td>{{ $medicine->fldqty }}</td>
                                                    <td>{{ $medicine->fldsellpr }}</td>
                                                    <td>{{ $total }}</td>
                                                    <td>{{ $medicine->fldcategory }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    {{-- <tfoot>
                                        <tr>
                                            <td colspan="7">Total</td>
                                            <td colspan="2">{{ $grandtotal }}</td>
                                        </tr>
                                    </tfoot> --}}
                                </table>
                            </div>
                            <div class="col-sm-12 mt-2 text-right">
                                <a href="{{ route('reports.nearexpiryExcel', Request::query()) }}" target="_blank" class="btn btn-primary btn-action">Export Excel</a> &nbsp;
                                <a href="{{ route('reports.nearexpiryPdf', Request::query()) }}" target="_blank" class="btn btn-primary btn-action">Export Pdf</a>  
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
    $(document).ready(function() {
        var nepaliDateConverter = new NepaliDateConverter();
        $('#js-near-expiry-range-select').change(function() {
            var max = 2;
            var date = new Date();
            var numberOfDaysToAdd = Number($('#js-near-expiry-range-select').val()) || 0;
            date.setDate(date.getDate() + numberOfDaysToAdd);
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            date = month + '/'+ day + '/'+ year;
            date = nepaliDateConverter.ad2bs(date);

            $('#js-near-expiry-date-input').val(date);
            $('#js-near-expiry-englishdate-input').val(year + '-' + month + '-' + day);
        });

        $('#js-near-expiry-date-input').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10,
            onChange: function() {
                var date = $('#js-near-expiry-date-input').val().split('-');
                date = date[1] + '/' + date[2] + '/' + date[0];
                date = nepaliDateConverter.bs2ad(date);
            
                $('#js-near-expiry-englishdate-input').val(date);
            }
        });
        // $('#myTable').DataTable({
        //         "ordering": false,
        //         "info": false,
        //         "lengthChange": false,
        //         // "scrollY": "260px",
        //         // "scrollCollapse": true,
        //         "pageLength": 25,
        //         "language": {
        //             // "search": '',
        //             "searchPlaceholder": "Type here to search...",
        //             "paginate": {
        //                 "previous": "<<",
        //                 "next": ">>"
        //             }
        //         },
        //         "drawCallback": function(settings) {
        //             var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
        //             pagination.toggle(this.api().page.info().pages > 1);
        //         }
        //     });
        // $('#js-search').on('keyup', function() {
        //     var searchText = $(this).val().toUpperCase();
        //     $.each($('#expenses-table tr td:nth-child(2)'), function(i, e) {
        //         var tdText = $(e).text().trim().toUpperCase();

        //         if (tdText.search(searchText) >= 0)
        //             $(e).closest('tr').show();
        //         else
        //             $(e).closest('tr').hide();
        //     });
        // });
    });
    $(function() {
    $('#myTable1').bootstrapTable()
  })
</script>
@endpush
