<head>
    <title>StockRate Item Report</title>
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
                    <th colspan="7" style="text-align:center;">Stock Rate Item Report</th>
                </tr>

                <tr>
                    <th colspan ="3" style="text-align: left;">Billing Mode: {{$billingmode}}</th>
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
                    <th>Item Name</th>
                    <th>Medicine Name</th>
                    <th>Rate</th>
                    <th>User</th>
                </tr>
            </thead>
            
            <tbody>

                @isset($message)

                <tr height="50px">
                    <td colspan="5"><center>{{$message}}</center>  </td>
                    
                   
                </tr>

                @endisset

                @isset($result)
                @foreach($result as $results)

                <tr height="50px">
                    <td> {{$loop->iteration}} </td>
                    <td > {{$results->flditemname}} 
                    <td > {{$results->fldstockid}} 
                   
                    <td > {{$results->fldrate}}  

                    @php

                        $useridname = \App\Utils\Helpers::getNameByUsername($results->flduserid);

                    @endphp
                    
                    {{-- <td> {{$results->flduserid}}   --}}
                    <td>{{$useridname}}</td>
                   
                </tr>

                @endforeach
                @endisset
                
            </tbody>

        </table>
    </div>
</div>


            
     


 
