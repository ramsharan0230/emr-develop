<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>

    <tr>
        <th>Purchase Report</th>
    </tr>

    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th colspan="2">करदाता दर्ता नं (PAN) :</th>
        <th><b>{{ Options::get('hospital_pan')}}</b></th>
        <th></th>
        <th></th>
        <th>करदाताको नाम:</th>
        <th><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        <th></th>
        <th></th>
        <th>साल …</th>
        <th><b>{{$year}}</b></th>
        <th></th>
        <th>महिना …</th>
        <th><b>{{$month}}</b></th>
        <th></th>
        <th></th>

    </tr>
    <tr class="heading-2">
        <td>मिति</td>
        <td>बीजक नं.</td>
        <td>आपूर्तिकर्ताको नाम</td>
        <td>आपूर्तिकर्ताको स्थायी लेखा नम्बर</td>
        <td>कर छुट हुने वस्तु वा सेवाको खरिद / पैठारी मूल्य (रु)</td>
        <td>करयोग्य खरिद (पूंजीगत बाहेक) मूल्य (रु)</td>
        <td>करयोग्य खरिद (पूंजीगत बाहेक) मूल्य कर (रु)</td>
        <td class="right-align">जम्मा खरिद मूल्य (रु)</td>
    </tr>

    </thead>
    <tbody>
        @if(isset($purchasedata) and count($purchasedata) > 0)
                @php
                $taxable_amount = 0;
                $non_taxable_amount = 0;
                $tax = 0;
                $total = 0;
                @endphp
            @foreach($purchasedata as $report)
                @php
                $taxable_amount += $report->Taxable_Amount;
                $non_taxable_amount += $report->NonTaxable_Amount;
                $tax += $report->Tax;
                $total += $report->NonTaxable_Amount + $report->Taxable_Amount + $report->Tax;

                $nepalidate = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($report->Pur_Date)));
                $pan_no = Helpers::getSuppliersinfo($report->Supplier_Name);
                @endphp
                <tr>
                    <td>{{$nepalidate->year}}-{{$nepalidate->month}}-{{$nepalidate->date}}</td>
                    <td>{{$report->Purchase_Reference}}</td>
                    <td>{{$report->Supplier_Name}}</td>
                    @if(isset($pan_no->fldpanno) && isset($pan_no->fldvatno))
                        <td>{{$pan_no->fldpanno}}/{{$pan_no->fldvatno}}</td>
                    @elseif((isset($pan_no->fldpanno)))
                        <td>{{$pan_no->fldpanno}}</td>
                    @elseif((isset($pan_no->fldvatno)))
                        <td>{{$pan_no->fldvatno}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ \App\Utils\Helpers::numberFormat(($report->NonTaxable_Amount))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($report->Taxable_Amount))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($report->Tax))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($report->NonTaxable_Amount + $report->Taxable_Amount + $report->Tax))}}</td>
                </tr>
            @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($taxable_amount))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($non_taxable_amount))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($tax))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($total))}}</td>
                </tr>
        @endif
    </tbody>
</table>
