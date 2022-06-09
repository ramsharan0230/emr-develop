<table>
    <thead>
    <tr><th></th></tr>
    @php
        $totalLiabilities = 0;
        $totalAssets = 0;
    @endphp
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Balance sheet report</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="10"><b>{{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateNepToEng($from_date)->full_date .")" : ''}} TO
                {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateNepToEng($to_date)->full_date . ")":'' }}</b></th>
    </tr>



    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed Date:</b>{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>

    <tr><th></th></tr>
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
