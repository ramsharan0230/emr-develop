@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                           Pharmacy Stock Live View With Batch
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="col-md-12">
                        <div class="d-flex flex-row">
                            <label class="col-sm-2">Fiscal Year:{{date('Y')}}</label>
                            <input type="text" class="form-control col-sm-4 " id="live-stock-search-medicine" placeholder="Search Medicines" >
                            <div class="col-md-4">
                                <select class="form-control col-sm-4 select select2" name="department" id="live-stock-department">
                                    <option value="">--Select Department--</option>
                                    @if($data['hospital_department'])
                                        @forelse($data['hospital_department'] as $dept)
                                            <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                        @empty
                                        @endforelse
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="live-stock-search-filter" class="btn btn-primary btn-action"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <marquee width="100%" direction="left" class="marquee-phar" id="marquee-row">
                        <div class="row">
                            @if(isset($data['highestQtyIssueItem']) && isset($data['highestQtyIssueItem']->fldbrand))
                            <div class="mr-5">
                                <label for="" class="mb-0" id="highestIssuedQty">{{$data['highestQtyIssueItem']->fldbrand}}({{$data['highestQtyIssueItem']->initialQtyIssue}})</label>&nbsp;&nbsp;
                                <span><i class="fa fa-caret-down text-danger" aria-hidden="true"></i></span>
                            </div>
                            @endif
                                @if(isset($data['highestPurQtyItem']) && isset($data['highestPurQtyItem']->fldbrand))
                            <div>
                                <label for="" class="mb-0" id="highestPurQty">{{$data['highestPurQtyItem']->fldbrand}}({{$data['highestPurQtyItem']->initialPurQty}})</label>&nbsp;&nbsp;
                                <span><i class="fa fa-caret-up text-success" aria-hidden="true"></i></span>
                            </div>
                                    @endif
                        </div>
                    </marquee>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive res-table" id="live-stock-table">
                        <table id="live-stock-tab"
                               data-show-columns="true"
                               data-search="true"
                               data-resizable="true"
                               data-show-toggle="true"
                               data-search-align="left"
                        >
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Batch</th>
                                <th>Opening Stock</th>
                                <th>Sales/Transaction &nbsp;&nbsp;&nbsp; <i class="fa fa-arrow-down text-danger" aria-hidden="true"></i></th>
                                <th>Purchase &nbsp;&nbsp;&nbsp; <i class="fa fa-arrow-up text-success" aria-hidden="true"></i></th>
                                <th>Expiry</th>
                                <th>Expiry Date</th>
                                <th>Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($data))
                            @foreach ($data['result'] as $list)
                                @if(isset($list->fldbrand))
                                <tr>
                                    <td>{{$list->fldbrand}} </td>
                                    <td>{{$list->fldbatch}} </td>
                                    <td>{{$list->initialBalQty}} </td>
                                    <td>{{$list->initialQtyIssue}} </td>
                                    <td>{{$list->initialPurQty}} </td>
                                    <td class="text-center"><i class="fa fa-window-minimize fa-rotate-90" style="color:{{$list->expiryStatus}}" aria-hidden="true" ></i></td>
                                    <td>{{\Carbon\Carbon::parse($list->fldexpiry)->format('d/m/Y')}} </td>
                                    <td>{{$list->fldqty}} </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('PUSHER_APP_KEY','2c681e719f7a99731e83')}}', {
            cluster: '{{env('PUSHER_APP_CLUSTER','ap2')}}'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('stock.live', function(res) {
            if(res){
               getChangeData();
            }

        });
        $(function() {
            $('#live-stock-tab').bootstrapTable()
        })
        $(document).on("click", "#live-stock-search-filter", function (e) {
                getChangeData();
        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [ day, month, year].join('/');
        }
        function getChangeData(){

                var data = {
                    'search':$('#live-stock-search-medicine').val(),
                    'department':$('#live-stock-department').val()
            };
            $.ajax({
                url: '{{ route('item.get-live-medicine-stock-change') }}',
                type: "GET",
                headers: {
                    "Content-Type": "application/json"
                },
                data: data,
                success: function (data) {
                    if(data) {
                        var responseHtml = "";
                        responseHtml += '<table data-show-columns="true" data-search="true" data-pagination="true"'
                        +'data-resizable="true" data-show-toggle="true" data-search-align="left" id="live-stock-tab">'
                            + '<thead>'
                            + ' <tr>'
                            +'<th> Item Name</th>'
                        + ' <th>Batch</th>'
                        + '  <th>Opening Stock</th>'
                        + ' <th>Sales/Transaction &nbsp;&nbsp;&nbsp; <i class="fa fa-arrow-down text-danger" aria-hidden="true"></i></th>'
                        + '  <th>Purchase &nbsp;&nbsp;&nbsp; <i class="fa fa-arrow-up text-success" aria-hidden="true"></i></th>'
                        + ' <th>Expiry Date</th>'
                        + ' <th>Expiry</th>'
                        + ' <th>Remaining</th>'
                        + '</tr></thead><tbody>';
                        $.each(data['result'], function (i, val) {
                            if(val.fldbrand){
                                responseHtml += '<tr>'
                                    +'<td>'+val.fldbrand+'</td>'
                                    +'<td>'+val.fldbatch+'</td>'
                                    + '<td>'+val.initialBalQty+'</td>'
                                    + '<td>'+val.initialQtyIssue+'</td>'
                                    + '<td>'+val.initialPurQty+'</td>'
                                    + '<td class="text-center"><i class="fa fa-window-minimize fa-rotate-90  aria-hidden="true" style="color:'+val.expiryStatus+'"></i></td>'
                                    + '<td>'+formatDate(val.fldexpiry)+'</td>'
                                    + '<td> ' + parseInt(val.fldqty )+ '</td>'
                                    + '</tr>';
                            }
                        });
                        responseHtml += ' </tbody></table>';
                        $('#live-stock-table').html(responseHtml);
                        console.log(data['highestQtyIssueItem'].fldbrand+'('+data['highestQtyIssueItem'].initialQtyIssue+')');
                        console.log(data['highestPurQtyItem'].fldbrand+'('+data['highestPurQtyItem'].initialQtyIssue+')');
                        var itemResponse = '';
                        if (data['highestQtyIssueItem'] && data['highestQtyIssueItem'].fldbrand){
                            itemResponse += ' <div class="row">'
                                +'  <div class="mr-5">'
                                +'<label for="" class="mb-0" id="highestIssuedQty">'+data['highestQtyIssueItem'].fldbrand+'('+data['highestQtyIssueItem'].initialQtyIssue+')</label>&nbsp;&nbsp;'
                                + '                        <span><i class="fa fa-caret-down text-danger" aria-hidden="true"></i></span> </div>'
                        }

                        if(data['highestPurQtyItem'] && data['highestPurQtyItem'].fldbrand){
                            itemResponse += ' <div class="row">'
                                +'  <div>'
                                +'<label for="" class="mb-0" id="highestPurQty">'+data['highestPurQtyItem'].fldbrand+'('+data['highestPurQtyItem'].initialPurQty+')</label>&nbsp;&nbsp;'
                                + '                        <span><i class="fa fa-caret-up text-success" aria-hidden="true"></i></span> </div>'
                        }



                        $('#marquee-row').html(itemResponse);
                        $('#live-stock-tab').bootstrapTable()
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    </script>
@endsection
