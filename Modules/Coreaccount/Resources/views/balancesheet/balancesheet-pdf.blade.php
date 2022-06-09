@extends('inpatient::pdf.layout.main')

@section('title', 'Balance Sheet')

@section('content')
    <style>
        .head-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .alignright p {
            text-align: right;
        }
        .date, .alignright {
            width: 40%;
        }
        .title {
            display: flex;
            justify-content: center;
            width: 20%;
        }

    </style>
    <main>
        @include('frontend.common.account-header')

        <div class="head-row">
            <div class="date">
                <p><b>From Date:</b> {{ isset($from_date) ? $from_date :'' }} {{ isset($eng_from_date) ? "(" .$eng_from_date .")" :'' }}</p>
                <p><b>To Date:</b> {{ isset($to_date) ? $to_date :'' }} {{ isset($eng_to_date) ? "(" .$eng_to_date .")" :'' }}</p>
            </div>
            <div class="title">
                <h3>Balance Sheet</h3>
            </div>
            <div class="alignright">
                <p><b>Printed Date:</b> {{ date('Y-m-d H:i:s') }}</p>
                <p><b>Printed By:</b>{{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</p>
            </div>
        </div>


@php
    $totalLiabilities = 0;
    $totalAssets = 0;
@endphp



        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            @php
                                $totalLiabilities = 0;
                                $totalAssets = 0;
                            @endphp
                            <table class="table table-bordered content-body" style="width: 100%;" border="1px" >
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
                                <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>Liabilities</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <tr>
                                    <td>1</td>
                                    <td>Assets</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                    <td>{{ $totalLiabilities < 0 ? Helpers::numberFormat($totalLiabilities * -1) : Helpers::numberFormat($totalLiabilities) }}</td>
                                    <td>{{ $totalAssets < 0 ? Helpers::numberFormat($totalAssets * -1) : Helpers::numberFormat($totalAssets) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    @php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
@endsection

