@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Under Stock Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-sm-12">
                            <form id="js-demandform-form">
                                <div class="form-group form-row align-items-center">
                                    <div class="form-group col-sm-3">
                                        <div class=" justify-content-between">
                                            <label>Name</label>
                                            <input type="text" name="search" id="search"
                                                value="" class="form-control" placeholder="search...">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <div class=" justify-content-between">
                                            <label>From Date</label>
                                            <input type="text" name="from_date" id="from_date"
                                                value="{{ $date }}" class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <div class="justify-content-between">
                                            <label>To Date</label>
                                            <input type="text" name="to_date" id="to_date"
                                                value="{{ $date }}"
                                                class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mt-3">
                                        <button type="button" class="btn btn-primary btn-action" id="refreshBtn"><i
                                                class="ri-refresh-line"></i></button>
                                        <button type="button" class="btn btn-primary btn-action" id="exportBtn"><i
                                                class="ri-code-s-slash-line "></i></button>
                                        <button type="button" class="btn btn-primary btn-action" id="excelBtn"><i
                                                class="ri-file-excel-2-fill "></i></button>
                                    </div>
                                </div>


                            </form>
                            <div class="form-group">
                                <div class="res-table table-sticky-th">
                                    <table class="table table-bordered table-striped table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Manufacturer</th>
                                            <th>Standard</th>
                                            <th>MinQty</th>
                                            <th>CurrentQty</th>
                                            <th>Comment</th>
                                {{--  <th>Category</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody id="js-demandform-order-tbody" style="overflow: scroll;">
                                        <tr>
                                            <td colspan="9" align="center">Click refresh to generate</td>
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                            {{--                                        <td></td>--}}
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div id="bottom_anchor"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                {{--                            <div class="col-sm-3">--}}
                                {{--                                <div class="form-row form-group align-items-center">--}}
                                {{--                                    <label class="col-sm-6">Total Amt</label>--}}
                                {{--                                    <div class="col-sm-6">--}}
                                {{--                                        <input type="text" class="form-control" value="{{ $totalamount }}" id="js-demandform-grandtotal-input">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                                {{--                            @if($can_verify)--}}
                                {{--                                <div class="col-sm-2">--}}
                                {{--                                    <button class="btn btn-primary" id="js-demandform-verify-btn">Verify</button>--}}
                                {{--                                </div>--}}
                                {{--                            @endif--}}

                                {{--                            <div class="col-sm-2">--}}
                                {{--                                <button class="btn btn-primary" id="js-demandform-save-btn">Save</button>--}}
                                {{--                            </div>--}}
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
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $('#refreshBtn').click(function () {

            if (from_date != '' || to_date != '' || reference != '') {
                $.ajax({
                    url: baseUrl + '/under-stock/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),

                    },
                    dataType: "json",
                    success: function (response) {
                       var html ='';
                        if (response) {
                            $('#js-demandform-order-tbody').empty().append(response);
                        }else {
                            html +='<tr><td colspan="8" align="center"> No data available</td></tr>';
                            $('#js-demandform-order-tbody').empty().append(html);
                        }
                        if (response.error) {
                            showAlert(response.error);
                        }
                    }
                });

            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        })

        $('#exportBtn').click(function () {

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/under-stock/report?from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/under-stock/report/excel?from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });


        //paginate
        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                // var surg = $(this).attr('href').split('meds=')[1];
                // var extra = $(this).attr('href').split('meds=')[1];
                SearchmoreData(page);
                // SearchmoreSurgeries(surg);
                // SearchmoreExtras(extra);
            });
        });


        function SearchmoreData(page) {

            // var reference = $('#references').val();
            // var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var url = "{{route('under.stock.getList')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    // reference: reference,
                    // category: category,
                },
                dataType: "json",
                success: function (response) {

                    if (response) {
                        $('#js-demandform-order-tbody').html(response)
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        {{--function SearchmoreSurgeries(page) {--}}

        {{--    // var reference = $('#references').val();--}}
        {{--    // var category = $('#category').val();--}}
        {{--    var from_date = $('#from_date').val();--}}
        {{--    var to_date = $('#to_date').val();--}}
        {{--    var url = "{{route('under.stock.getList')}}";--}}
        {{--    $.ajax({--}}
        {{--        url: url + "?surgery=" + page,--}}
        {{--        type: "GET",--}}
        {{--        data: {--}}
        {{--            from_date: BS2AD(from_date),--}}
        {{--            to_date: BS2AD(to_date),--}}
        {{--            // reference: reference,--}}
        {{--            // category: category,--}}
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
        {{--function SearchmoreExtras(page) {--}}

        {{--    // var reference = $('#references').val();--}}
        {{--    // var category = $('#category').val();--}}
        {{--    var from_date = $('#from_date').val();--}}
        {{--    var to_date = $('#to_date').val();--}}
        {{--    var url = "{{route('under.stock.getList')}}";--}}
        {{--    $.ajax({--}}
        {{--        url: url + "?extra=" + page,--}}
        {{--        type: "GET",--}}
        {{--        data: {--}}
        {{--            from_date: BS2AD(from_date),--}}
        {{--            to_date: BS2AD(to_date),--}}
        {{--            // reference: reference,--}}
        {{--            // category: category,--}}
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

        // for search in table
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


    </script>
@endpush
