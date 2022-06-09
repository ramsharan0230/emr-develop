@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Demand Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-group form-row align-items-center">
                                {{--                                                            <div class="col-sm-4">--}}
                                {{--                                                                <label>Department</label>--}}
                                {{--                                                                <select name="fldpurtype" class="form-control" id="js-demandform-department-select">--}}
                                {{--                                                                    <option value="Outside">Outside</option>--}}
                                {{--                                                                    <option value="Inside">Inside</option>--}}
                                {{--                                                                </select>--}}
                                {{--                                                            </div>--}}
                                <div class="col-sm-2">
                                    <label>From Date</label>
                                    <input type="text" name="from_date" id="from_date"
                                           value="{{ $date }}" class="form-control nepaliDatePicker">
                                </div>
                                <div class="col-sm-2">
                                    <label>To Date</label>
                                    <input type="text" name="to_date" id="to_date"
                                           value="{{ $date }}"
                                           class="form-control nepaliDatePicker">
                                </div>
                                <div class="col-sm-2">
                                    <label>Department</label>
                                    <select name="department" class="form-control" id="department">
                                        <option value="">--Select--</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->fldcomp }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Supplier</label>
                                    <select name="fldsuppname" class="form-control" id="supplier">
                                        <option value="">--Select--</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->fldsuppname }}"
                                                    data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Demand No</label>
                                    <select name="fldbill" class="form-control" id="bill">
                                        <option value="">--Select--</option>
                                    </select>
                                    {{--                                                                <input type="text" name="fldbill" class="form-control" id="bill" placeholder="Bill no">--}}
                                </div>

                                <div class="col-sm-2 mt-3">
                                    <button type="button" class="btn btn-primary btn-action" id="refreshBtn"><i
                                            class="ri-refresh-line"></i></button>
                                    <button type="button" class="btn btn-primary btn-action" id="exportBtn"><i
                                            class="ri-code-s-slash-line "></i></button>
                                    <button type="button" class="btn btn-primary btn-action" id="excelBtn"><i
                                            class="ri-file-excel-2-fill "></i></button>
                                </div>
                                {{--                                                            <div class="col-sm-3">--}}
                                {{--                                                                <button class="btn btn-warning" id="js-demandform-export-btn"><i class="ri-code-s-slash-line h5"></i></button>--}}
                                {{--                                                            </div>--}}
                            </div>
                            {{--                            <div class="form-group form-row align-items-center">--}}
                            {{--                                <div class="col-sm-4">--}}
                            {{--                                    <label>Department</label>--}}
                            {{--                                    <select name="fldpurtype" class="form-control" id="js-demandform-department-select">--}}
                            {{--                                        <option value="Outside">Outside</option>--}}
                            {{--                                        <option value="Inside">Inside</option>--}}
                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-4">--}}
                            {{--                                    <label>Supplier/Department</label>--}}
                            {{--                                    <select name="fldsuppname" class="form-control" id="js-demandform-supplier-select">--}}
                            {{--                                        <option value="">--Select--</option>--}}
                            {{--                                        @foreach($suppliers as $supplier)--}}
                            {{--                                            <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>--}}
                            {{--                                        @endforeach--}}
                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-3">--}}
                            {{--                                    <button class="btn btn-warning" id="js-demandform-export-btn"><i class="ri-code-s-slash-line h5"></i></button>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                        </form>

                        <div class="row">
                            <div class="col-md-1">
                                <label>Search</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Type here to search...">
                            </div>
                        </div>
                        <br>

                        <div class="form-group">
                            <div class="res-table table-sticky-th">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Datetime</th>
                                        <th>Supplier/Department</th>
                                        <th>Particular</th>
                                        <th>Demand No</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>User</th>
                                        <th>Hospital Department</th>
                                        <th>Comp</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-demandform-order-tbody">
                                    <tr>
                                        <td colspan="10" align="center">Click refresh to generate</td>
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
@endsection

@push('after-script')
    <script type="text/javascript">

        $('#supplier').change(function () {
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (supplier != '') {
                $.ajax({
                    url: baseUrl + '/demand-report/getBillNo',
                    type: "GET",
                    data: {
                        supplier: supplier,
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        // $('#supplier').val('');
                        if (response) {
                            $('#bill').empty().append(response);

                        }
                        if (response.error) {
                            // $('#supplier').val('');
                            showAlert(response.error, 'error');

                        }
                    }
                });
            } else {
                showAlert('Something went wrong', 'error');
            }
        })


        $('#refreshBtn').click(function () {

            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var fldbill = $('#bill').val();
            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: baseUrl + '/demand-report/getMedicineList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        department: department,
                        supplier: supplier,
                        fldbill: fldbill,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        // return false;
                        if (response.status) {
                            $('#js-demandform-order-tbody').empty().append(response.html);
                        } else {
                            html += "<tr><td colspan='9' align='center'> No data available<td></tr>";
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
        //paginate
        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                SearchmoreData(page);
            });
        });

        function SearchmoreData(page) {

            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var fldbill = $('#bill').val();


            var url = "{{route('demand.report.getMedicineList')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    department: department,
                    supplier: supplier,
                    fldbill: fldbill,
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


        $('#exportBtn').click(function () {

            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var fldbill = $('#bill').val();

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/demand-report/report?department=' + department + '&fldbill=' + fldbill + '+&supplier=' + supplier + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }

        });
        $('#excelBtn').click(function () {
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var fldbill = $('#bill').val();

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/demand-report/report/excel?department=' + department + '&fldbill=' + fldbill + '&supplier=' + supplier + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

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
