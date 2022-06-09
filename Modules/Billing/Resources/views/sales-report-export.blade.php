<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
    <main>
        <div>
            <h3 style="text-align: center;">Sales Book</h3>
            <h4>Name of Firm: {{ isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '' }}</h4>
            <h4>PAN: {{ Options::get('hospital_pan') }}</h4>
            <h4>Duration of Sales: Month {{ $monthdiff }} Year {{ $yeardiff }}</h4>
        </div>
        <table>
            <thead style="text-align: center;">
                <tr>
                    <th colspan="4">Invoice</th>
                    <th rowspan="2">Total Sales</th>
                    <th rowspan="2">Non TaxableSales</th>
                    <th rowspan="2">ExportSales</th>
                    <th rowspan="2">Discount</th>
                    <th colspan="2">Taxable Sales</th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Bill no</th>
                    <th>Buyer's Name</th>
                    <th>Buyer's  PAN Number</th>
                    <th>Amount</th>
                    <th>Tax(Rs)</th>
                </tr>
            </thead>
            <tbody id="js-sales-report-tbody">
                @php
                    $grosstotalsales = 0;
                    $grossnontaxablesales = 0;
                    $grossexportsales = 0;
                    $grossdiscount = 0;
                    $grosstaxableamount = 0;
                    $grosstax = 0;
                @endphp
                @foreach ($data as $d)
                @php
                    $grosstotalsales += $d['totalsales'];
                    $grossnontaxablesales += $d['nontaxablesales'];
                    $grossexportsales += $d['exportsales'];
                    $grossdiscount += $d['discount'];
                    $grosstaxableamount += $d['taxableamount'];
                    $grosstax += $d['tax'];
                @endphp
                    <tr>
                        <td> {{ $d['fldtime'] }}</td>
                        <td> {{ $d['fldbillno'] }}</td>
                        <td> {{ $d['fldfullname'] }}</td>
                        <td> {{ $d['fldpannumber'] }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['totalsales']) }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['nontaxablesales']) }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['exportsales']) }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['discount']) }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['taxableamount']) }}</td>
                        <td> {{ \App\Utils\Helpers::numberFormat($d['tax']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total Amount:</th>
                    <th id="js-grosstotalsales-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grosstotalsales) }}</th>
                    <th id="js-grossnontaxablesales-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grossnontaxablesales) }}</th>
                    <th id="js-grossexportsales-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grossexportsales) }}</th>
                    <th id="js-grossdiscount-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grossdiscount) }}</th>
                    <th id="js-grosstaxableamount-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grosstaxableamount) }}</th>
                    <th id="js-grosstax-tfoot-th">{{ \App\Utils\Helpers::numberFormat($grosstax) }}</th>
                </tr>
            </tfoot>
        </table>
    </main>
</body>
</html>
