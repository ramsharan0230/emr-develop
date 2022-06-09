@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Item Wise Profit</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-sm-12">
                            <form id="js-demandform-form">
                                <div class="form-group form-row align-items-center">
                                    <div class="form-group col-sm-2">
                                        <div class="justify-content-between">
                                            <label>From Date</label>
                                            <input type="text" name="from_date" id="from_date"
                                                   value="{{ $date }}" class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <div class="justify-content-between">
                                            <label>To Date</label>
                                            <input type="text" name="to_date" id="to_date" value="{{ $date }}"
                                                   class="form-control nepaliDatePicker">
                                        </div>
                                    </div>

                                    <div class="col-sm-2 form-group mt-3 ">
                                        <button type="button" class="btn btn-primary btn-action" id="refreshBtn"><i
                                                class="ri-refresh-line"></i></button>
                                        <button type="button" class="btn btn-primary btn-action" id="exportBtn"><i
                                                class="ri-code-s-slash-line "></i></button>
                                        <button type="button" class="btn btn-primary btn-action" id="excelBtn"><i
                                                class="ri-file-excel-2-fill "></i></button>
                                    </div>

                                </div>


                            </form>
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="search"> Search </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="search here">
                                </div>

                            </div>
                             <br>


                            <div class="form-group">
                                <div class="res-table table-sticky-th">
                                    <table class="table table-bordered table-striped table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>S.N</th>
{{--                                            <th>Code</th>--}}
                                            <th>Brand Name</th>
                                            <th>Generic Name</th>
                                            <th>Sales Qty</th>
                                            <th>Return Qty</th>
                                            <th>Value(SP)</th>
                                            <th>Value(CP)</th>
                                            <th>Discount</th>
                                            <th>Return(SP)</th>
                                            <th>Return(CP)</th>
                                            <th>Net Profit</th>
                                        </tr>
                                        </thead>
                                        <tbody id="pharmacy-sales-tbody">
                                        @php
                                            $returnsp =0;
                                            $returncp = 0;
                                            $valuesp =0;
                                            $valuecp =0;
                                            $netprofit =0;
                                        @endphp
                                        @forelse($pharmacy_results as $pharmacy)

                                            <tr>

                                                <td>{{ $loop->iteration }}</td>

{{--                                                <td></td>--}}

                                                @if($pharmacy->flditemtype=='Medicines')
                                                    <td>{{ (($pharmacy->brand) ? $pharmacy->brand->fldbrand :'' ) ?? null}}</td>
                                                @endif

                                                @if($pharmacy->flditemtype=='Surgicals')
                                                    <td>{{ (($pharmacy->surgicalBrand) ? $pharmacy->surgicalBrand->fldbrand :'' ) ?? null}}</td>
                                                @endif

                                                @if($pharmacy->flditemtype=='Extra Items')
                                                    <td>{{ (($pharmacy->extraBrand) ? $pharmacy->extraBrand->fldbrand :'' ) ?? null}}</td>
                                                @endif

                                                @if($pharmacy->flditemtype=='Medicines')
                                                    <td>{{ (($pharmacy->brand) ? $pharmacy->brand->flddrug :'' ) ?? null}}</td>
                                                @endif

                                                @if($pharmacy->flditemtype=='Surgicals')
                                                    <td>{{ (($pharmacy->surgicalBrand) ? $pharmacy->surgicalBrand->fldsurgid :'' ) ?? null}}</td>
                                                @endif

                                                @if($pharmacy->flditemtype=='Extra Items')
                                                    <td>{{ (($pharmacy->extraBrand) ? $pharmacy->extraBrand->fldextraid :'' ) ?? null}}</td>
                                                @endif
                                                @php
                                                    $returnsp = ( $pharmacy->flditemqty  * $pharmacy->fldditemamt );
                                                    $returncp = ( $pharmacy->flditemqty * $pharmacy->purchase->fldnetcost);
                                                    $valuesp = $pharmacy->flditemrate;
                                                    $valuecp = ($pharmacy->purchase ? $pharmacy->purchase->fldnetcost :'');
                                                    $netprofit = (($valuesp -$valuecp) - ( $returnsp + $returncp));
                                                @endphp


                                                <td>{{ $pharmacy->flditemqty ??'' }}</td>
                                                <td> {{ $pharmacy->fldretqty ?? '' }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat($valuesp) ?? 0 }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat($valuecp) ?? 0 }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat($pharmacy->flddiscamt) ?? 0 }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat($returnsp) ?? 0 }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat($returncp) ?? 0 }}</td>
                                                <td>{{   \App\Utils\Helpers::numberFormat(abs($netprofit)) ?? 0 }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9"> No Data available </td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div id="bottom_anchor">

                                    </div>
                                </div>

                            </div>
                            <div>
                                {{ $pharmacy_results->links() }}
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


        $('#refreshBtn').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: "{{ route('pharmacist.pharmacy-sales.search') }}",
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response) {
                            $('#pharmacy-sales-tbody').empty().append(response);
                        }
                        if(response.error){
                            showAlert(response.error,'error');
                        }

                    }
                });

            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        })

        $('#exportBtn').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/pharmacist/pharmacy-sales-book/export-pdf?from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/pharmacist/pharmacy-sales-book/export-excel?from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        //Search
        $("#search").on("keyup", function() {
            var value = $(this).val();

            $("table tr").each(function (index) {
                if (!index) return;
                $(this).find("td").each(function () {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });


        //paginate
        {{--$(document).ready(function () {--}}
        {{--    $(document).on('click', '.pagination a', function (event) {--}}
        {{--        event.preventDefault();--}}
        {{--        var page = $(this).attr('href').split('page=')[1];--}}
        {{--        SearchmoreData(page);--}}
        {{--    });--}}
        {{--});--}}


        {{--function SearchmoreData(page) {--}}

        {{--    var reference = $('#references').val();--}}
        {{--    var category = $('#category').val();--}}
        {{--    var from_date = $('#from_date').val();--}}
        {{--    var to_date = $('#to_date').val();--}}
        {{--    var url = "{{route('stock.return.report.getList')}}";--}}
        {{--    $.ajax({--}}
        {{--        url: url + "?page=" + page,--}}
        {{--        type: "GET",--}}
        {{--        data: {--}}
        {{--            from_date: BS2AD(from_date),--}}
        {{--            to_date: BS2AD(to_date),--}}
        {{--            reference: reference,--}}
        {{--            category: category,--}}
        {{--        },--}}
        {{--        dataType: "json",--}}
        {{--        success: function (response) {--}}
        {{--            if (response.status) {--}}
        {{--                $('#js-demandform-order-tbody').html(response.html)--}}
        {{--            }--}}
        {{--        },--}}
        {{--        error: function (xhr, status, error) {--}}
        {{--            var errorMessage = xhr.status + ': ' + xhr.statusText;--}}
        {{--            console.log(xhr);--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}


    </script>
@endpush
