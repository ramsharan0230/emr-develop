<head>
    <title>Purchase/Sales VAT Report</title>
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

<div class="row" style="margin: 0 auto;">
        <table style="width: 100%;" >
            <tr>
                <th colspan="3">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</th>
            </tr>
            <tr>
                <th colspan="3">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</th>
            </tr>
            @if( $reporttype == '0')
            <tr>
                <th colspan="3">Purchase Report with VAT</th>
            </tr>
            @elseif( $reporttype == '1')
            <tr>
                <th colspan="3">Purchase Summary Report</th>
            </tr>
            @elseif( $reporttype == '2')
            <tr>
                <th colspan="3">VAT Difference</th>
            </tr>
            @elseif( $reporttype == '3')
            <tr>
                <th colspan="3">Sales Summary Report</th>
            </tr>
            @endif
            <tr>

                <th style="width: 30%; text-align: left;">Date: {{$fromdateeng}} To {{$todateeng}}</th>
                <th style="width: 30%; text-align: left;"></th>
                <th style="width: 40%; text-align: right;">Printed By: {{$userid}}</th>

            </tr>

            <tr>

                <th style="width: 30%; text-align: left;"></th>
                <th style="width: 30%; text-align: left;"></th>
                <th style="width: 40%; text-align: right;">Printed Time: {{ \Carbon\Carbon::now() }}</th>


            </tr>
        </table>
    </div>


    @if( $reporttype == '0')
        <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>

                            <th>S.N.</th>
                            <th>Date</th>
                            <th>Purchase Ref</th>
                            <th>Bill No.</th>
                            <th>Supp Name</th>
                            <th>PAN/VAT</th>
                            <th>Non Taxable (Exc. Dis)</th>
                            <th>Discount</th>
                            <th>Taxable</th>
                            <th>Sub Total</th>
                            <th>VAT Amt</th>
                            <th>Net Total</th>
                            <th>Remarks</th>

                        </tr>

                        <tbody>

                            @php $totalamt = 0 @endphp

                            @isset($result)
                            @foreach($result as $results)

                            @php $subtotal = ($results->NonTaxableAmount) + ($results->TaxableAmount) - ($results->flddiscount) @endphp
                            @php $nettotalinv = ($results->NonTaxableAmount) + ($results->TaxableAmount) - ($results->flddiscount) + ($results->VATAMT) @endphp
                            @php
                            $totalamt += $subtotal @endphp


                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$results->fldpurdate}}</td>
                                    <td>{{$results->fldreference}}</td>
                                    <td>{{$results->fldbillno}}</td>
                                    <td>{{$results->fldsuppname}}</td>
                                    <td>{{$results->fldvatpan}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($results->NonTaxableAmount)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($results->flddiscount)}}</td>

                                    <td>{{\App\Utils\Helpers::numberFormat($results->TaxableAmount)}}</td>

                                    <td>{{\App\Utils\Helpers::numberFormat($subtotal)}}</td>

                                    <!-- <td>{{($results->Total_Amount)}}</td> -->
                                    <td>{{\App\Utils\Helpers::numberFormat($results->VATAMT)}}</td>
                                    <!-- <td>{{($results->NetAmt)}}</td> -->
                                    <td>{{\App\Utils\Helpers::numberFormat($nettotalinv)}}</td>
                                    <td>{{$results->itemreturn}}</td>
                                </tr>

                            @endforeach
                            @endisset

                        </tbody>
                        <tr>
                        <td colspan="6"  style="text-align:center;"><b>Total</b></td>
                                <td><b>{{\App\Utils\Helpers::numberFormat($totaldata['nontax'])}}</b></td>
                                <td><b>{{\App\Utils\Helpers::numberFormat($totaldata['discount'])}}</b></td>
                                <td><b>{{\App\Utils\Helpers::numberFormat($totaldata['tax'])}}</b></td>

                                <td><b>{{\App\Utils\Helpers::numberFormat($totalamt)}}</b></td>
                                <td><b>{{\App\Utils\Helpers::numberFormat($totaldata['vat'])}}</b></td>
                                <td><b>{{\App\Utils\Helpers::numberFormat($totaldata['nettotal'])}}</b></td>
                                <td></td>
                        </tr>



                    </table>
                </div>
            </div>

        </div>
        <br>




    @elseif( $reporttype == '1')

    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>


                        <th> Billing Range </th>
                        <th> Non-Taxable Amount (After Discount) </th>
                        <th> Taxable Amount (After Discount) </th>
                        <th> VAT Amount </th>
                        <th> CC Charge </th>
                        <th> Net Total </th>

                        </tr>
                        <tbody>

                            @isset($result)

                                <tr>

                                    <td>{{$totaldata['billnofirst']}} - {{$totaldata['billnolast']}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nontax'])}} </td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['tax'])}} </td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['vat'])}} </td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['cccharge'])}} </td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nettotal'])}} </td>
                                </tr>

                            @endisset

                        </tbody>

                    </table>
                </div>
            </div>

        </div>
        <br>





    @elseif( $reporttype == '2')

    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>

                        <th> Total Purchase VAT </th>
                        <th> Total Sales VAT </th>
                        <th> VAT Difference </th>
                        </tr>
                        <tbody>

                            @isset($result)

                                <tr>

                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['purvat'])}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['salesvat'])}} </td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['vatdiff'])}} </td>

                                </tr>

                            @endisset

                        </tbody>

                    </table>
                </div>
            </div>

        </div>
        <br>






    @elseif( $reporttype == '3')
    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                        <tr>

                            <th> Bill Type </th>
                            <th> NonTaxable Amount (After Discount) </th>
                            <th> Taxable Amount (After Discount) </th>
                            <th> VAT </th>
                            <th> Net Total </th>
                        </tr>
                        <tbody>

                            @isset($result)


                                <tr>
                                    <td>CASH</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nontaxcash']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['taxcash']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['vatcash']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nettotalcash']) }}</td>
                                </tr>

                                <tr>
                                    <td>CASH RETURN</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nontaxret']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['taxret']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['vatret']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nettotalret']) }}</td>
                                </tr>

                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nontaxtotal']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['taxtotal']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['vattotal']) }}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($totaldata['nettotaltotal']) }}</td>
                                </tr>

                            @endisset

                        </tbody>

                    </table>
                </div>
            </div>

        </div>
        <br>



    @endif

