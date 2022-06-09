<!DOCTYPE html>
<html>

<head>
    <title>Department Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

        .content-body td,
        .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }

        body {
            margin-top: 3.5cm;
            margin-bottom: 1cm;
        }

        @page {
            margin: 0.5cm 0.5cm;
        }

        table tr td h2,
        h4 {
            line-height: 0.5rem;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2rem;

        }
    </style>
</head>

<body>

    @include('pdf-header-footer.header-footer')
    <main>
        <table style="width: 100%;" border="1px" rules="all" class="content-body">
            <tbody>
                <tr>
                    <th style="width: 96px; text-align: center;">Department</th>
                    <th style="width: 96px; text-align: center;">Category</th>
                    <th style="width: 96px; text-align: center;">Block</th>
                    <th style="width: 96px; text-align: center;">Floor</th>
                    <th style="width: 96px; text-align: center;">Room No.</th>
                    <th style="width: 96px; text-align: center;">Auto Billing</th>
                    <th style="width: 96px; text-align: center;">Payable</th>
                    <th style="width: 96px; text-align: center;">Status</th>
                </tr>
                @if($departments)
                    @foreach($departments as $dept)
                        <tr>
                            <td class="deptname" dept="{{$dept->flddept}}">{{$dept->flddept}}</td>
                            <td>{{$dept->fldcateg}}</td>
                            <td>{{$dept->fldblock}}</td>
                            <td>{{$dept->flddeptfloor}}</td>
                            <td>{{$dept->fldroom}}</td>
                            @if($dept->fldhead != "0") <td>{{$dept->fldhead}}</td> @else <td></td> @endif
                            <td>{{$dept->fldactive}}</td>
                            @if($dept->fldstatus == 1) <td>Active</td> @else <td>Inactive</td> @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @php
        $signatures = Helpers::getSignature('opd');
        @endphp
        @include('frontend.common.footer-signature-pdf')
    </main>
</body>

</html>