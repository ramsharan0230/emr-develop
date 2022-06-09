<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Sales Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table td, .table th {
            border: 1px solid #a79c9c;
            padding: 4px;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        p, h3 {
            margin-bottom: 0; margin-top: 2px;
        }
        main{
            width: 90%;
            margin: 0 auto;;
        }
        .content-body table { page-break-inside:auto }
        .content-body tr    { page-break-inside:avoid; page-break-after:auto }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>
<body>
<main>
    <div class="row" style="margin: 0 auto;">
        <table style="width: 100%;" >
            <tr>
                <th colspan="3">Chirayu National Hospital & Medical Institue Pvt. ltd.</th>
            </tr>
            <tr>
                <th colspan="3">Pharmacy Unit</th>
            </tr>
            <tr>
                <th style="width: 20%; text-align: left;">Date : {{ $finalfrom }} - {{$finalto}}</th>
                <th style="width: 60%;">Basundhara</th>
                <th style="width: 20%; text-align: right;"></th>
            </tr>
        </table>
    </div>
    <div class="row" style="margin: 0 auto;  margin-top: 10px;">
        <table class="table content-body">
            <thead>
                <th >Description</th>
                <th class="text-center">Values</th>
            </thead>
            <tbody>
            @php
                    $netcashcollection = 0;
                    $vat =0;
                    $returnvat =0;
                    $depositcollection = 0;
                    $patientclerance = 0;
                    $cashin = 0;
                    $netcreditcollection=0;
                    @endphp
                <tr>
                    <td  rowspan="2" class="border-none">Cash Sale  <br><br> <span>Cash Discount </span>

                    </td>
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($cash[0]->flditemamt)}}</td>
                </tr>
                <tr>
                    <td class="text-center" >Less-({{ \App\Utils\Helpers::numberFormat($cash[0]->flddiscountamt)}} </td>
                </tr>
                <tr>
                    <td  rowspan="2" class="border-none">Total Cash Sales<br><br> <span>Cash Sales Refund </span>

                    </td>
                    @php
                    $totalcashin = $cash[0]->flditemamt - $cash[0]->flddiscountamt ;
                    @endphp
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($totalcashin)}}</td>
                </tr>
                <tr>
                    <td class="text-center" >Less({{ \App\Utils\Helpers::numberFormat($refund[0]->fldreceivedamt)}}) </td>
                </tr>

                <tr>
                    <td  rowspan="5" class="border-none">Net Cash Sales<br><br>
                        <span>Vat Amount Cash </span><br> <br>
                        <span>Return Vat Amount Cash </span><br> <br>
                        <span>Deposit Collection </span><br> <br>
                        <span>Patients Clearance </span>
                    </td>
                    @php
                    $Net_Cash_Sales = $cash[0]->flditemamt - $cash[0]->flddiscountamt + $refund[0]->fldreceivedamt;
                    @endphp
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($Net_Cash_Sales)}}</td>
                </tr>
                <tr>
                    <td class="text-center" >Add+ ({{ \App\Utils\Helpers::numberFormat($cash[0]->fldtaxamt)}}) </td>
                </tr>
                <tr>
                    <td class="text-center" >Less({{ \App\Utils\Helpers::numberFormat($refund[0]->fldtaxamt)}}) </td>
                </tr>
                <tr>
                    @php
                    $dp = $depositcredit[0]->fldreceivedamt + $depositrefundcredit[0]->fldreceivedamt;
                    @endphp
                    <td class="text-center" >Add+ ({{  \App\Utils\Helpers::numberFormat($dp) }}) </td>
                </tr>
                <tr>
                    <td class="text-center" >Add+ ({{ \App\Utils\Helpers::numberFormat($tobepaidbypatientcash[0]->fldreceivedamt) }}) </td>
                </tr>
                <tr>
                    <td >Cash In Hand </td>
                    @php
                    $netcashcollection =$cash[0]->flditemamt - $cash[0]->flddiscountamt + $refund[0]->fldreceivedamt;
                    $vat =$cash[0]->fldtaxamt;
                    $returnvat =$refund[0]->fldtaxamt;
                    $depositcollection = $depositcredit[0]->fldreceivedamt + $depositrefundcredit[0]->fldreceivedamt;
                    $patientclerance = $tobepaidbypatientcash[0]->fldreceivedamt;
                    $cashin = $netcashcollection + $vat +$returnvat+ $depositcollection+$patientclerance;
                    @endphp

                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($cashin)}}</td>
                </tr>
                <tr>
                    <td  rowspan="2" class="border-none">Credit sale <br><br> <span>Credit Discount </span>

                    </td>
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($credit[0]->flditemamt)}}</td>
                </tr>
                <tr>
                    <td class="text-center" >Less-({{ \App\Utils\Helpers::numberFormat($credit[0]->flddiscountamt)}}) </td>
                </tr>
                <tr>
                    <td  rowspan="2" class="border-none">Total credit sales<br><br> <span>Credit Sales Refund </span>

                    </td>
                    @php
                    $cp = $credit[0]->flditemamt -$credit[0]->flddiscountamt
                    @endphp
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($cp) }}</td>
                </tr>
                <tr>
                    <td class="text-center" >Less({{ \App\Utils\Helpers::numberFormat($refundcredit[0]->flditemamt)}}) </td>
                </tr>
                <tr>
                    <td >Net Credit Sales </td>
                    @php
                    $netcreditcollection = $credit[0]->flditemamt- $credit[0]->flddiscountamt + $refundcredit[0]->fldreceivedamt;
                    @endphp
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($netcreditcollection)}}</td>
                </tr>
                <tr>
                    <td  rowspan="4" class="border-none">Vat Amount Credit<br><br>
                        <span>Return Vat Amount Credit</span><br> <br>
                        <span>After Sales Discount</span><br> <br>
                        <span>TDS on Discount</span>
                    </td>
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($credit[0]->fldtaxamt)}}</td>
                </tr>
                <tr>
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($refundcredit[0]->fldtaxamt)}} </td>
                </tr>
                <tr>
                    <td class="text-center" >xxxx</td>
                </tr>
                <tr>
                    <td class="text-center" >xxxx </td>
                </tr>
                <tr>
                    <td  rowspan="3" class="border-none">Total sales: <br><br>
                        <span>Net Taxable Amount</span><br> <br>
                        <span>Net Non Taxable Amount</span><br> <br>
                    </td>
                 @php
                 $tc = $netcashcollection+$netcreditcollection;
                 @endphp
                    <td class="text-center" >{{  \App\Utils\Helpers::numberFormat( $tc) }}</td>
                </tr>
                <tr>
                    @php
                    $vtc = $vatableamount[0]->flditemamt -  $vatableamount[0]->flddiscountamt;
                    $nvtc = $nonvatableamount[0]->flditemamt -  $vatableamount[0]->flddiscountamt;
                    @endphp
                    <td class="text-center" >{{ \App\Utils\Helpers::numberFormat($vtc)}} </td>
                </tr>
                <tr>
                    <td class="text-center" >{{  \App\Utils\Helpers::numberFormat($nvtc) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
