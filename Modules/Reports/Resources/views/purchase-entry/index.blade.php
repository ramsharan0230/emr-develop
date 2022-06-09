@extends('frontend.layouts.master')

@section('content')

    <style>
        .table th:nth-child(8),.table td:nth-child(8){
            min-width:120px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Purchase Entry Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-group form-row align-items-center">
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
                                    <label>P.E Ref No</label>
                                    <select name="fldbill" class="form-control" id="bill">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <label>Opening</label>&nbsp;
                                    <input type="checkbox" name="opening" id="opening" value="yes">
                                    <div>
                                    <button type="button" class="btn btn-primary" id="refreshBtn"><i
                                                class="ri-refresh-line"></i></button>
                                        <button type="button" class="btn btn-primary" id="exportBtn"><i
                                                class="ri-code-s-slash-line "></i></button>
                                        <button type="button" class="btn btn-primary" id="excelBtn"><i
                                                class="ri-file-excel-2-fill "></i></button>
                                    </div>
                                </div>
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
                                        <th>SNo.</th>
                                        <th>Supplier Name</th>
                                        <th>Purchase Date</th>
                                        <th>Category</th>
                                        <th>Item Name</th>
                                        <th>Batch</th>
                                        <th>Expiry</th>
                                        <th>P.E Ref No</th>
                                        <th>GRN No.</th>
                                        {{-- <th>Quantity</th> --}}
                                        <th>Purchased Qty</th>
                                        <th>Qty Bon</th>
                                        <th>Total Qty</th>
                                        <th>NetCost</th>
                                        <th>VAT AMT</th>
                                        <th>CCost</th>
                                        <th>Sub Total</th>
                                        <th>Total Amount</th>
                                        {{-- <th>Sell Rate</th> --}}
                                        <th>Department</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-purchase-entry-tbody">
                                    <tr>
                                        <td colspan="18" align="center">Click refresh to generate</td>
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
            $('#bill').empty();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(supplier !=''){
                $.ajax({
                    url: baseUrl + '/purchase-entry-report/getBillNo',
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
                $('#bill').empty().append("<option value=''>-- Select --</option>");
            }
        })


        $('#refreshBtn').click(function () {

            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var bill = $('#bill').val();

            if($('#opening').is(':checked')){
                var opening = $('#opening').val();
            }
            if (from_date != '' || to_date != '') {
                $.ajax({
                    url: baseUrl + '/purchase-entry-report/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        department: department,
                        supplier: supplier,
                        fldbill: bill,
                        opening: opening ? opening :'',
                    },
                    dataType: "json",
                    success: function (response) {
                        var html ='';
                        if (response.status) {
                            $('#js-purchase-entry-tbody').empty().append(response.html);
                        }else {
                            html +="<tr><td colspan='8' align='center'> No data available<td></tr>";
                            $('#js-purchase-entry-tbody').empty().append(html);
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
            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var bill = $('#bill').val();
            var opening = "";
            if($('#opening').is(':checked')){
                var opening = '&opening='+$('#opening').val();
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/purchase-entry-report/report?department='+department+'&fldbill='+bill+opening+'&supplier='+supplier+'&from_date=' + BS2AD(from_date)+'&to_date='+BS2AD(to_date)
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
            var bill = $('#bill').val();
            var opening = "";
            if($('#opening').is(':checked')){
                var opening = '&opening='+$('#opening').val();
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/purchase-entry-report/report/excel?department='+department+'&fldbill='+bill+opening+'&supplier='+supplier+'&from_date=' + BS2AD(from_date)+'&to_date='+BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        //paginate
        $( document ).ready(function() {
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                SearchmoreData(page);
            });
        });


        function SearchmoreData(page){

            var department = $('#department').val();
            var supplier = $('#supplier').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var bill = $('#bill').val();
            if($('#opening').is(':checked')){
                var opening = $('#opening').val();
            }

            var url = "{{route('purchase.entry.report.getList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    department: department,
                    supplier: supplier,
                    fldbill: bill,
                    opening: opening ? opening :'',
                },
                dataType: "json",
                success: function(response) {
                if(response.status){
                    $('#js-purchase-entry-tbody').html(response.html)
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
