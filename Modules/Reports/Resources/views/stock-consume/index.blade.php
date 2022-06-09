@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Stock Consume Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-group form-row align-items-center">
                                {{--                                <div class="col-sm-4">--}}
                                {{--                                    <label>Target</label>--}}
                                {{--                                    <button type="button" id="add"> <i--}}
                                {{--                                            class="ri-add-circle-line"> </i></button>--}}
                                {{--                                    <select name="target" class="form-control" id="js-demandform-department-select">--}}
                                {{--                                        <option value="Outside">--Select--</option>--}}
                                {{--                                        @foreach($categories as $category)--}}
                                {{--                                        <option value="{{ $category->fldcategory }}">{{ $category->fldcategory }}</option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    </select>--}}
                                {{--                                </div>--}}
                                <div class="col-sm-3">
                                    <div class="form-group justify-content-between">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="from_date"
                                               value="{{ $date }}" class="form-control nepaliDatePicker">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="to_date"
                                               value="{{ $date }}"
                                               class="form-control nepaliDatePicker">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Item</label>
                                        <select name="item" class="form-control" id="item">
                                            <option value="">--Select--</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->flditem }}">{{ $item->flditem }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Consume Reference No</label>
                                        <select name="consume-reference" class="form-control" id="consume-reference">
                                            <option value="">--Select--</option>
                                            @foreach($consume_references as $reference)
                                                <option
                                                    value="{{ $reference->fldreference }}">{{ $reference->fldreference }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="refreshBtn"><i class="ri-refresh-line"></i>&nbsp;Refresh</button>
                                    <button type="button" class="btn btn-warning" id="exportBtn"><i class="fa fa-file-pdf"></i>&nbsp;Export</button>
                                    <button type="button" class="btn btn-primary" id="excelBtn"><i class="fa fa-file-excel"></i>&nbsp;
                                    Export To Excel</button>
                                </div>
                            </div>
                        </form>

                        <div class="form-group">
                            <div class="table-responsive table-container">
                                <table class="table table-bordered table-hover table-striped ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Target</th>
                                        <th>Category</th>
                                        <th>Particulars</th>
                                        <th>Batch</th>
                                        <th>Expiry</th>
                                        <th>Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-stock-consume-order-tbody">
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
@endsection

@push('after-script')
    <script type="text/javascript">
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $('#refreshBtn').click(function () {
            var reference = $('#consume-reference').val();
            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '' || reference != '') {
                $.ajax({
                    url: baseUrl + '/stock-consume/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        reference: reference,
                        item:item,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html ='';
                        if (response.status) {
                            $('#js-stock-consume-order-tbody').empty().append(response.html);
                        }else {
                            html +='<tr><td align="center" colspan="8">No data available</td></tr>'
                            $('#js-stock-consume-order-tbody').empty().append(html);
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
            var reference = $('#consume-reference').val();
            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-consume/report?item='+item+'&reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            var reference = $('#consume-reference').val();
            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-consume/report/excel?item='+item+'&reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $( document ).ready(function() {
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                SearchmoreData(page);
            });
        });


        function SearchmoreData(page){

            var reference = $('#consume-reference').val();
            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var url = "{{route('stock.consume.report.getList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    reference: reference,
                    item:item,
                },
                dataType: "json",
                success: function(response) {
                if(response.status){
                    $('#js-stock-consume-order-tbody').html(response.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
        }

        //sort refrences datewise
        $('#item').change(function () {

            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if(item!='' || from_date!='' || to_date!=''){


            }else{
               showAlert('Check Date and Item','error');
            }
            $.ajax({
                url: baseUrl + '/stock-consume/getReference',
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    item:item,
                },
                dataType: "json",
                success: function (response) {
                    if (response) {
                        $('#consume-reference').empty().append(response);
                    }
                    if (response.error) {
                        showAlert(response.error,'error');
                    }
                }
            });
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

            var reference = $('#consume-reference').val();
            var item = $('#item').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var url = "{{route('stock.consume.report.getList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    reference: reference,
                    item:item,
                },
                dataType: "json",
                success: function(response) {
                    if(response.status){
                        $('#js-stock-consume-order-tbody').html(response.html)
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
