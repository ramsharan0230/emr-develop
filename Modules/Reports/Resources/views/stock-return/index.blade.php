@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Stock Return Report</h4>
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

                                    <div class="col-sm-2 form-group ">
                                        <div class=justify-content-between>
                                            <label>Category</label>
                                            <select name="category" class="form-control" id="category">
                                                <option value="">--Select--</option>
                                                @foreach($categories as $category)
                                                    <option
                                                        value="{{ $category->fldcategory }}">{{ $category->fldcategory }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 form-group ">
                                        <label>Reference</label>
                                        <select name="reference" class="form-control" id="references">
                                            <option value="">--Select--</option>
                                            {{--                                        @foreach($references as $reference)--}}
                                            {{--                                            <option--}}
                                            {{--                                                value="{{ $reference->fldnewreference }}">{{ $reference->fldnewreference }} </option>--}}
                                            {{--                                        @endforeach--}}
                                        </select>
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

                            <div class="form-group">
                                <div class="res-table table-sticky-th">
                                    <table class="table table-bordered table-striped table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Particulars</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Qty</th>
                                            <th>Cost</th>
                                            <th>Vendor</th>
                                            <th>RefNo</th>
                                        </tr>
                                        </thead>
                                        <tbody id="js-demandform-order-tbody">
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
                                    <div id="bottom_anchor">
                                        {{--                                    {{ $user_shares->render() }}--}}
                                    </div>
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

        $('#category').change(function () {
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if(category ==''){

                return false;
            }

            if (from_date != '' || to_date != '' || category != '') {
                $.ajax({
                    url: baseUrl + '/stock-return/getReference',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        category: category,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response) {
                            $('#references').empty().append(response);
                        }
                        if (response.error) {
                            showAlert(response.error, 'error');
                        }
                    }
                });

            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        })

        $('#refreshBtn').click(function () {
            var reference = $('#references').val();
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '' || reference != '') {
                $.ajax({
                    url: baseUrl + '/stock-return/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        reference: reference,
                        category: category,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response.status) {
                            $('#js-demandform-order-tbody').empty().append(response.html);
                        } else {
                            html += '<tr><td align="center" colspan="8"></td></tr>';
                            $('#js-demandform-order-tbody').empty().append(html);
                        }
                        if (response.error) {
                            showAlert(response.error,'error');
                        }
                    }
                });

            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        })

        $('#exportBtn').click(function () {
            var reference = $('#references').val();
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-return/report?category=' + category + '&reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            var reference = $('#references').val();
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-return/report/excel?category=' + category + '&reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
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
                SearchmoreData(page);
            });
        });


        function SearchmoreData(page) {

            var reference = $('#references').val();
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var url = "{{route('stock.return.report.getList')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    reference: reference,
                    category: category,
                },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        $('#js-demandform-order-tbody').html(response.html)
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


    </script>
@endpush
