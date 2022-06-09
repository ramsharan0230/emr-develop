<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <td>ItemNo</td>
        <td>Brand</td>
        <td>Generic</td>
        <td>Unit</td>
        <td>UnitCost</td>
        <td>OpenQTY</td>
        <td>SaleQTY</td>
        <td>TotSaleCost</td>
        <td>EndQTY</td>
    </tr>
    </thead>
    <tbody>
    @if($resultData)
        @foreach($resultData as $r)
            <?php
            $purx = 0;
            $purqty = 0;
            $recvqx = 0;
            $purchaseTotal = 0;
            $recvTotal = 0;
            $salqx = 0;
            $salesTotal = 0;
            $fldtotalqty = 0;
            $fldreturnqty = 0;
            $fldnetcost = 0;
            $recvNetCost = 0;
            $bulqxDisplay = 0;
            $bulkQty = 0;
            $bulqx = 0;
            $sentqx = 0;
            $adjCompQtx = 0;
            $bulkCurrentQty = 0;
            $adjqtx = 0;
            $xvax = 0;
            $openQuantity = 0;

            if ($r->multiplePurchase) {
                $fldtotalqty = $r->multiplePurchase->where('fldstockno', $r->fldstockno)->sum('fldtotalqty');
                $fldreturnqty = $r->multiplePurchase->where('fldstockno', $r->fldstockno)->sum('fldreturnqty');
                $fldnetcost = $r->multiplePurchase->where('fldstockno', $r->fldstockno)->avg('fldnetcost');

                $purx = $fldtotalqty - $fldreturnqty;

                $purqty = $purx;

                $purchaseTotal = $fldnetcost * $purx;
            }

            /*recqx*/
            if ($r->transfer) {
                $recvqx = $r->transfer->where('fldstockno', $r->fldstockno)->sum('fldqty');
                $recvNetCost = $r->transfer->where('fldstockno', $r->fldstockno)->avg('fldnetcost');
                $recvTotal = $recvqx * $recvNetCost;
            }

            if ($r->patBillingByName) {
                $salqx = $r->patBillingByName->where('fldstockno', $r->fldstockno)->sum('flditemqty');
                $salesTotal = $r->patBillingByName->where('fldstockno', $r->fldstockno)->sum('fldditemamt');
            }

            if ($r->bulkSale) {
                $bulqxDisplay = $r->bulkSale->where('fldstockno', $r->fldstockno)->sum('fldqtydisp');
                $bulkQty = $r->bulkSale->where('fldstockno', $r->fldstockno)->sum('fldqtyret');
                $bulqx = $bulqxDisplay - $bulkQty;
            }

            if ($r->transfer) {
                $sentqx = $r->transfer->where('fldstockno', $r->fldstockno)->sum('fldqty');
            }

            if ($r->adjustment) {
                $adjCompQtx = $r->adjustment->where('fldstockno', $r->fldstockno)->sum('fldcompqty');
                $bulkCurrentQty = $r->adjustment->where('fldstockno', $r->fldstockno)->sum('fldcurrqty');
                $adjqtx = $adjCompQtx - $bulkCurrentQty;
            }

            $xvax = $r->where('fldstockno', $r->fldstockno)->sum('fldqty') + ($salqx + $bulqx + $sentqx + $adjqtx) - ($purx + $recvqx);

            $openQuantity = $xvax + ($salqx + $bulqx + $sentqx + $adjqtx) - ($purqty + $recvqx) ;
            ?>
            <tr>
                <td>{{$r->fldstockno}}</td>
                <td>{{$r->medbrand? $r->medbrand->fldbrand :''}}</td>
                <td>{{$r->fldstockid}}</td>
                <td>{{$r->medbrand? $r->medbrand->fldvolunit :''}}</td>
                <td>{{$r->fldsellpr}}</td>
                <td>{{$xvax}}</td>
                <td>{{$salqx}}</td>
                <td>{{$salesTotal}}</td>
                <td>{{$r->where('fldstockno', $r->fldstockno)->sum('fldqty')}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
