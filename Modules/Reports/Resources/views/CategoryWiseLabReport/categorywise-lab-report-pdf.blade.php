<!DOCTYPE html>
<html>
<head>
    <title>{{ Str::upper($testCategory) }} REPORT</title>
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
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Category Wise lab Report</h4>
{{--@include('pdf-header-footer.header-footer')--}}
<main>
    <table style="width: 100%;"  class="content-body">
        <thead class="thead-light" id="table-head">
            {!! $htmlHead !!}
        </thead>
        <tbody id="table-body">
            {!! $htmlBody !!}
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('laboratory');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
