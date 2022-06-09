@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Order vs Receive Report</h4>
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
                                    <label>Search</label>
                                    <input type="text" name="search" id="search"
                                           value="" class="form-control" placeholder="search...">
                                </div>


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
                                    <label>Department</label>
                                    <select name="department" class="form-control" id="department">
                                        <option value="">--Select--</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->fldcomp }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Reference</label>
                                    <select name="reference" class="form-control" id="reference">
                                        <option value="">--Select--</option>
{{--                                        @foreach($references as $reference)--}}
{{--                                            <option--}}
{{--                                                value="{{ $reference->fldreference }}">{{ $reference->fldreference }}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </div>

                                <div class="col-sm-12 mt-3 text-right">
                                    <button type="button" class="btn btn-primary btn-action" id="refreshBtn"><i
                                            class="ri-refresh-line"></i></button>
                                    <button type="button" class="btn btn-primary btn-action" id="exportBtn"><i
                                            class="ri-code-s-slash-line "></i></button>
                                    <button type="button" class="btn btn-primary btn-action" id="excelBtn"><i
                                            class="ri-file-excel-2-fill "></i></button>
                                </div>
{{--                                <div class="col-sm-1">--}}
{{--                                    <button type="button" class="btn btn-warning" id="exportBtn"><i--}}
{{--                                            class="ri-code-s-slash-line h5"></i></button>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-1">--}}
{{--                                    <button type="button" class="btn btn-primary" id="excelBtn"><i--}}
{{--                                            class="ri-file-excel-2-fill h5"></i></button>--}}
{{--                                </div>--}}
                                {{--                                                            <div class="col-sm-3">--}}
                                {{--                                                                <button class="btn btn-warning" id="js-demandform-export-btn"><i class="ri-code-s-slash-line h5"></i></button>--}}
                                {{--                                                            </div>--}}
                            </div>

                        </form>

                        <div class="form-group">
                            <div class="table-sticky-th res-table">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Datetime</th>
                                        <th>Supplier</th>
                                        <th>Particular</th>
                                        <th>Order Qty</th>
                                        <th>Purchase Qty</th>
                                        <th>Route</th>
                                        <th>Ref No</th>
                                        <th>Hospital Department</th>
                                        <th>Comp</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-order-recieve-order-tbody">
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
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $('#refreshBtn').click(function () {
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var reference = $('#reference').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: baseUrl + '/order-vs-receive/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        department: department,
                        supplier: supplier,
                        reference: reference,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response.status) {
                            $('#js-order-recieve-order-tbody').empty().append(response.html);
                        } else {
                            html += "<tr><td colspan='8' align='center'> No data available<td></tr>";
                            $('#js-order-recieve-order-tbody').empty().append(html);
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

        $('#exportBtn').click(function () {
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var reference = $('#reference').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/order-vs-receive/report?reference='+reference+'&department=' + department + '&supplier=' + supplier + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var reference = $('#reference').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/order-vs-receive/report/excel?reference='+reference+'&department=' + department + '&supplier=' + supplier + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        //sort references by date
        $('#department').change( function () {
            if($('#department').val() == ''){
                return false;
            }
            $('#reference').val('');
            getReferences();
        });

        $('#supplier').change( function () {
            if($('#supplier').val() == ''){

                return false;
            }
            $('#reference').val('');
            getReferences();
        });

        function getReferences(){
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: baseUrl + '/order-vs-receive/getReferences',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        department: department,
                        supplier: supplier,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response) {
                            $('#reference').empty().append(response);
                        }
                        if (response.error) {
                            showAlert(response.error);
                        }
                    }
                });

            } else {
                showAlert('Please check date,supplier and departments', 'error');
            }
        }
        $( document ).ready(function() {
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                SearchmoreData(page);
            });
        });

        function SearchmoreData(page){

            var reference = $('#references').val();
            var category = $('#category').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var url = "{{route('order.vs.receive.getList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    reference: reference,
                    category: category,
                },
                dataType: "json",
                success: function(response) {
                    if(response.status){
                        $('#js-order-recieve-order-tbody').html(response.html)
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


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
