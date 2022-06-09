<table>
    <thead>
    <tr><th></th></tr>
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
        <th colspan="2"><b>Voucher No.:</b></th>
        <th colspan="2">{{ $voucher_no }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>S/N</th>
        <th>Branch</th>
        <th>Acc No.</th>
        <th>Name</th>
        <th>Description</th>
        <th>Dr Amount</th>
        <th>Cr Amount</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($voucherDatas as $key=>$voucherData)
            <tr>
                <td>{{++$key}}</td>
                @if(isset($voucherData->branch))
                    <td>{{$voucherData->branch->name}}</td>
                @else 
                    <td></td>
                @endif
                <td>{{$voucherData->AccountNo}}</td>
                <td>{{$voucherData->accountLedger->AccountName}}</td>
                <td>{{$voucherData->Remarks}}</td>
                @if ($voucherData->TranAmount > 0)
                    <td>{{$voucherData->TranAmount}}</td>
                    <td>0</td>
                @else
                    <td>0</td>
                    <td>{{$voucherData->TranAmount * (-1)}}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
    </table>
    