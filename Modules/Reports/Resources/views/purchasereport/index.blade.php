@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Purchase Order Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            {{--                            <div class="form-row ">--}}
                            {{--                                <div class="form-group col-sm-3">--}}
                            {{--                                    <div class="form-row justify-content-between">--}}
                            {{--                                        <div class="col-sm-5"><label>From Date</label></div>--}}
                            {{--                                        <div class="col-sm-7"><input type="text" name="from_date" id="from_date"--}}
                            {{--                                                                     value="{{ $date }}" class="form-control nepaliDatePicker"></div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="form-group col-sm-3">--}}
                            {{--                                    <div class="form-row">--}}
                            {{--                                        <div class="col-sm-5"><label>To Date</label></div>--}}
                            {{--                                        <div class="col-sm-7"><input type="text" name="to_date" id="to_date"--}}
                            {{--                                                                     value="{{ $date }}"--}}
                            {{--                                                                     class="form-control nepaliDatePicker"></div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-3">--}}
                            {{--                                    <button type="button" class="btn btn-primary" id="refreshBtn"><i--}}
                            {{--                                            class="ri-refresh-line"></i></button>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-3">--}}
                            {{--                                    <button type="button" class="btn btn-warning" id="exportBtn"><i--}}
                            {{--                                            class="ri-code-s-slash-line h5"></i></button>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-1">--}}
                            {{--                                    <button type="button" class="btn btn-primary" id="excelBtn"><i--}}
                            {{--                                            class="ri-file-excel-2-fill h5"></i></button>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
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
                                            <option value="{{ $supplier->fldsuppname }}" data-fldsuppaddress="{{ $supplier->fldsuppaddress }}">{{ $supplier->fldsuppname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>PO Ref No</label>
                                    <select name="fldbill" class="form-control" id="bill">
                                        <option value="">--Select--</option>
                                    </select>
                                    {{--                                                                <input type="text" name="fldbill" class="form-control" id="bill" placeholder="Bill no">--}}
                                </div>


                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary" id="refreshBtn"><i
                                            class="ri-refresh-line"></i></button>
                                    <button type="button" class="btn btn-primary" id="exportBtn"><i
                                            class="ri-code-s-slash-line "></i></button>
                                    <button type="button" class="btn btn-primary" id="excelBtn"><i
                                            class="ri-file-excel-2-fill "></i></button>
                                </div>
                                {{--                                                            <div class="col-sm-3">--}}
                                {{--                                                                <button class="btn btn-warning" id="js-demandform-export-btn"><i class="ri-code-s-slash-line h5"></i></button>--}}
                                {{--                                                            </div>--}}
                            </div>

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
                            <div class="table-responsive table-container">
                                <table class="table table-bordered table-hover table-striped ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Datetime</th>
                                        <th>Supplier/Department</th>
                                        <th>Particular</th>
                                        <th>PO Ref No</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>User</th>
                                        <th>Hospital Department</th>
                                        <th>Comp</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-purchase-order-tbody">
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
            if(supplier !=''){
                $.ajax({
                    url: baseUrl + '/purchase-report/getBillNo',
                    type: "GET",
                    data: {
                        supplier: supplier,
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                    },
                    dataType: "json",
                    success: function (response) {
                        var html ='';
                        if (response) {
                            $('#bill').empty().append(response);
                        }
                        if (response.error) {
                            showAlert(response.error ,'error');
                        }
                    }
                });
            }else{
                showAlert('Something went wrong','error');
            }
        })

        $('#refreshBtn').click(function () {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var fldbill = $('#bill').val();

            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: baseUrl + '/purchase-report/getMedicineList',
                    type: "GET",
                    data: {
                        from_date:  BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        department: department,
                        supplier: supplier,
                        fldbill: fldbill,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html ='';
                        if (response.status) {
                            $('#js-purchase-order-tbody').empty().append(response.html);
                        }else {
                            html +="<tr><td colspan='9' align='center'> No data available<td></tr>";
                            $('#js-purchase-order-tbody').empty().append(html);
                        }
                        if (response.error) {
                            showAlert(response.error);
                        }
                    }
                });

            } else {
                showAlert('Please check date,supplier and departments', 'error');
            }
        })

        //paginate
        $( document ).ready(function() {
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                SearchmoreData(page);
            });
        });

        function SearchmoreData(page){

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var fldbill = $('#bill').val();

            var url = "{{route('purchase.report.getMedicineList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date:  BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    department: department,
                    supplier: supplier,
                    fldbill: fldbill,
                },
                dataType: "json",
                success: function(response) {
                    if(response.status){
                        $('#js-purchase-order-tbody').html(response.html)
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
            var fldbill = $('#bill').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/purchase-report/report?department='+department+'&fldbill='+fldbill+'&supplier='+supplier+'&from_date=' + BS2AD(from_date)+'&to_date='+BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });


        $('#excelBtn').click(function () {
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var fldbill = $('#bill').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/purchase-report/report/excel?department='+department+'&fldbill='+fldbill+'&supplier='+supplier+'&from_date=' +  BS2AD(from_date)+'&to_date='+BS2AD(to_date)
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
