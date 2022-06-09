<head>
    <title>HI Total Vs Consumed Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @media print {
            .page {
                margin: 5px;
            }           
        }
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
        .content-body table { page-break-inside:auto; }
        .content-body tr    { page-break-inside:avoid; page-break-after:auto }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>

<div class="page">
    <div class="row">
            <table style="width: 100%;" >
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</th>
                </tr>

                <tr>
                    <th colspan="7" style="text-align:center;">HI Patient Total Vs Consumed Report</th>
                </tr>

                <tr>
                    <th colspan ="3" style="text-align: left;">Date: {{$fromdateeng}} To {{$todateeng}}</th>
                    <th colspan ="1" style="text-align: left;"></th>
                    <th colspan ="3" style="text-align: right;">Printed By: {{$userid}}<br>Printed Time: {{ \Carbon\Carbon::now() }}</th>

                </tr>
            </table>
    </div>
    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead>
                <tr>    
                    <th>S.N.</th>
                    <th>Patient ID</th>
                    <th>NHSI ID</th>
                    <th>Patient Details</th>
                    <th>Allowed Amount</th>
                    <th>Consumed Amount</th>
                    <th>Bills</th>
                </tr>
            </thead>
            
            <tbody>

                @isset($result)
                @foreach($result as $results)

                <tr>
                    <td> {{$loop->iteration}} </td>
                    <td > {{$results->fldpatientval}} </td>
                    
                    <td > {{$results->fldnhsiid}} </td>
                    
                    <td> {{$results->fldptnamefir}} </td>
                    <td> {{$results->fldallowedamt}}  </td>
                    <td> {{$results->total}}  </td>
                    <td> {{$results->bills}}  </td>
                </tr>

                @endforeach
                @endisset
                
            </tbody>

        </table>
    </div>
</div>


            
     


 
