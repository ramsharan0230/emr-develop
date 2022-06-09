@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Balance Sheet
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="balance-sheet-filter">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From Date:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}">
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}">
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="searchBalanceSheet()"><i class="fa fa-search"></i>&nbsp;Search</button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="exportBalanceSheet()"><i class="fa fa-download"></i>&nbsp;Export Excel</button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="exportBalanceSheetPdf()"><i class="fa fa-file-pdf"></i>&nbsp;PDF</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="table-responsive">
                                @php
                                    $totalLiabilities = 0;
                                    $totalAssets = 0;
                                @endphp
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Code No.</th>
                                        <th>Group</th>
                                        <th>Sub Group</th>
                                        <th>Account</th>
                                        <th>Liabilities</th>
                                        <th>Assets</th>
                                    </tr>
                                    </thead>
                                    <tbody id="balancesheet-search-result">

                                    @php
                                        $arrayLiabilityGroupName = [];
                                        $liabilityTotal = 0;
                                    @endphp
                                    @forelse($liabilities as $liability)
                                        <!--                                        loop of sub total-->
                                        @if (!in_array($liability->group_name, $arrayLiabilityGroupName) && $loop->iteration != 1)
                                            @php
                                                array_push($arrayLiabilityGroupName, $liability->group_name);
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <th>Sub Total</th>
                                                <th>{{ Helpers::numberFormat($liabilityTotal) }}</th>
                                                <td></td>
                                            </tr>
                                            @php
                                                $liabilityTotal = 0;
                                            @endphp
                                        @endif
                                        <!--                                        loop of sub total-->
                                        @php
                                            $totalLiabilities += ($liability->AMT);
                                        @endphp
                                        <tr>
                                            <td>{{ $liability->GroupTree }}</td>
                                            <td>{{ $liability->group_name }}</td>
                                            <td>{{ $liability->sub_name }}</td>
                                            <td>{{ $liability->AccountName }}</td>
                                            <td>{{ $liability->AMT < 0 ? Helpers::numberFormat($liability->AMT * -1) : Helpers::numberFormat($liability->AMT) }}</td>
                                            <td></td>
                                        </tr>

                                        @php
                                            $liabilityTotal += ($liability->AMT);
                                        @endphp
                                        <!--                                        loop of sub total-->
                                        @if ($loop->last)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <th>Sub Total</th>
                                                <th>{{ Helpers::numberFormat($liabilityTotal) }}</th>
                                                <td></td>
                                            </tr>
                                            @php
                                                $liabilityTotal = 0;
                                            @endphp
                                        @endif
                                        <!--                                        loop of sub total-->
                                    @empty
                                    @endforelse

                                    @php
                                        $arrayAssetGroupName = [];
                                        $assetSubTotal = 0;
                                    @endphp
                                    @forelse($assets as $asset)
                                        <!--                                        loop of sub total-->
                                        @if (!in_array($asset->group_name, $arrayAssetGroupName) && $loop->iteration != 1)
                                            @php
                                                array_push($arrayAssetGroupName, $asset->group_name);
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <th>Sub Total</th>
                                                <th></th>
                                                <th>{{ Helpers::numberFormat($assetSubTotal) }}</th>
                                            </tr>
                                            @php
                                                $assetSubTotal = 0;
                                            @endphp
                                        @endif
                                        <!--                                        loop of sub total-->
                                        @php
                                            $totalAssets += ($asset->AMT);
                                        @endphp
                                        <tr>
                                            <td>{{ $asset->GroupTree }}</td>
                                            <td>{{ $asset->group_name }}</td>
                                            <td>{{ $asset->sub_name }}</td>
                                            <td>{{ $asset->AccountName }}</td>
                                            <td></td>
                                            <td>{{ $asset->AMT < 0 ? Helpers::numberFormat($asset->AMT * -1) : Helpers::numberFormat($asset->AMT)}}</td>
                                        </tr>
                                        <!--                                        loop of sub total-->
                                        @php
                                            $assetSubTotal += $asset->AMT;
                                        @endphp
                                        @if ($loop->last)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <th>Sub Total</th>
                                                <td></td>
                                                <th>{{ Helpers::numberFormat($assetSubTotal) }}</th>
                                            </tr>
                                            @php
                                                $assetSubTotal = 0;
                                            @endphp
                                        @endif
                                        <!--                                        loop of sub total-->
                                    @empty
                                    @endforelse
                                    {{--
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                        <td>{{ $totalLiabilities < 0 ? Helpers::numberFormat($totalLiabilities * -1) : Helpers::numberFormat($totalLiabilities) }}</td>
                                        <td>{{ $totalAssets < 0 ? Helpers::numberFormat($totalAssets * -1) : Helpers::numberFormat($totalAssets) }}</td>
                                    </tr>--}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });

        function searchBalanceSheet() {
            // alert('Balance Sheet');
            $.ajax({
                url: baseUrl + '/account/balancesheet/searchBalanceSheet',
                type: "POST",
                data: $('#balance-sheet-filter').serialize(),
                success: function (response) {
                    $('#balancesheet-search-result').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportBalanceSheetPdf() {

            var data = $('#balance-sheet-filter').serialize();
            var urlReport = baseUrl + "/account/balancesheet/export-balancesheet-pdf?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');

        }

        function exportBalanceSheet() {

            var data = $('#balance-sheet-filter').serialize();
            var urlReport = baseUrl + "/account/balancesheet/export-balancesheet?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');

        }

    </script>
@endsection
