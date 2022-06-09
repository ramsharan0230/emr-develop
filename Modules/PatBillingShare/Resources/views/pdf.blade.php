<!DOCTYPE html>
<html>

<head>
    <title>Pat Billing Share Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }
    </style>
</head>

<body>
@include('pdf-header-footer.header-footer')
<main>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th>S.N</th>
            <th>Doctor</th>
            <th>Bill Number</th>
            <th>Item Name</th>

            <th>Billing Date</th>
            <th>Total Amount (Rs.)</th>
            <th>Hospital Share %</th>
            <th>Hospital Share Amount (Rs.)</th>
            <th>Share Type</th>
            <th>Doctor Share (Rs.)</th>
            <th>Tax %</th>

            <th>Is Return</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @if(!$billing_share_reports->isEmpty())
            @forelse($billing_share_reports as $k => $report)
                <tr>
                    <td>{{ $k + 1 }}</td>
                    <td>{{ ucfirst($report->firstname) . " " . ucfirst($report->middlename) . " " . ucfirst($report->lastname) }}</td>
                    <td>{{ $report->fldbillno }}</td>
                    <td>{{ $report->flditemname }}</td>

                    <td>{{ $report->fldordtime }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($report->fldditemamt) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($report->hospitalshare) }} %</td>
                    <td>{{ \App\Utils\Helpers::numberFormat((($report->hospital_share * $report->fldditemamt)/100)) }}</td>
                    <td>{{ ucfirst($report->type) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($report->share) }} ({{ \App\Utils\Helpers::numberFormat($report->usersharepercent) }})</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($report->tax_amt) }}</td>
                    @php
                        $tax_amt = ($report->tax_amt) ? $report->tax_amt : 0;
                        $payment = $report->share - $tax_amt;
                    @endphp

                    @php
                        $total += $payment;
                    @endphp
                    <td>{{($report->is_returned == 0) ? "No" : "Yes"}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="20" class="text-center">
                        <em>No data available in table ...</em>
                    </td>
                </tr>
            @endforelse
        @else
            <tr>
                <td colspan="20">No data to show.</td>
            </tr>
        @endif
        </tbody>
    </table>
    <p>admin, {{date('Y-m-d')}}
    </p>
</main>

</body>

</html>
