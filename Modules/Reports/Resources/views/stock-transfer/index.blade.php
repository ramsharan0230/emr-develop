@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Stock Transfer Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-group form-row">
                                <div class="col-sm-2">
                                    <div class="">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="from_date"
                                               value="{{ $date }}" class="form-control nepaliDatePicker">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="to_date"
                                               value="{{ $date }}"
                                               class="form-control nepaliDatePicker">
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label>Target Comp</label>
                                    <select name="category" class="form-control" id="target">
                                        <option value="Outside">--Select--</option>
                                        @foreach($comps as $comp)
                                            <option value="{{ $comp->fldfromcomp }}">{{ $comp->fldfromcomp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label> Transfer Reference</label>
                                    <select name="reference" class="form-control" id="references">
                                        <option value="">--Select--</option>
                                        @foreach($references as $reference)
                                            <option
                                                value="{{ ((isset($reference->fldreference) && $reference->fldreference != null) ? $reference->fldreference :'' ) }}">{{ ((isset($reference->fldreference) && $reference->fldreference != null) ? $reference->fldreference :'' ) }} </option>
                                        @endforeach
                                    </select>
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
                            <div class="table-responsive table-container">
                                <table class="table table-bordered table-hover table-striped ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Batch</th>
                                        <th>Expiry</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>Comp</th>
                                        <th>RefNo</th>
                                        <th>Remark</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-stock-transfer-order-tbody">
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


        $('#refreshBtn').click(function () {
            var reference = $('#references').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            if (from_date != '' || to_date != '' || reference != '') {
                $.ajax({
                    url: baseUrl + '/stock-transfer/getList',
                    type: "GET",
                    data: {
                        from_date: BS2AD(from_date),
                        to_date: BS2AD(to_date),
                        reference: reference,
                    },
                    dataType: "json",
                    success: function (response) {
                        var html ='';
                        if (response.status) {
                            $('#js-stock-transfer-order-tbody').empty().append(response.html);
                        }else {
                            html +='<tr><td align="center" colspan="8"> No data available</td></tr>';
                            $('#js-stock-transfer-order-tbody').empty().append(html);
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
            var reference = $('#references').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-transfer/report?reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

        $('#excelBtn').click(function () {
            var reference = $('#references').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (typeof reference === undefined || reference === null || reference === '') {
                showAlert('Reference cannot be empty', 'error');
                return false;
            }
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/stock-transfer/report/excel?reference=' + reference + '&from_date=' + BS2AD(from_date) + '&to_date=' + BS2AD(to_date)
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

            var reference = $('#references').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var url = "{{route('stock.transfer.report.getList')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                data: {
                    from_date: BS2AD(from_date),
                    to_date: BS2AD(to_date),
                    reference: reference,
                },
                dataType: "json",
                success: function(response) {
                    if(response.status){
                        $('#js-stock-transfer-order-tbody').html(response.html)
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
