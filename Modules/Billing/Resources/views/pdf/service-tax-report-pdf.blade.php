<head>
    <title>Service TAX Report</title>
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
        .content-body table {
            page-break-inside:auto; 
        }
        .content-body tr { 
            page-break-inside:avoid; 
            page-break-after:auto; 
        }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>

<div class="a4">
    <div class="row">
        <table style="width: 100%;" >
            <tr>
                <th colspan="10" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</th>
            </tr>
            <tr>
                <th colspan="10" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</th>
            </tr>

            <tr>
                <th colspan="10" style="text-align:center;">TAX REPORT</th>
            </tr>

            <tr>
                <th colspan="4" style="text-align: left;">From: {{$fromdatenep}} BS ({{$fromdateeng}} AD) </th> 
                <th colspan="2" style="text-align: right;"></th>
                <th colspan="4" style="text-align: right;">Printed By: {{$userid}}</th>

            </tr>

            <tr>
                <th colspan="4" style="text-align: left;">To: {{$todatenep}} BS ({{$todateeng}} AD)</th>
                <th colspan="2" style="text-align: right;"></th>
                <th colspan="4" style="text-align: right;">Printed Time: {{ \Carbon\Carbon::now() }} </th>
            </tr>

            <tr>
                <th colspan="4" style="text-align: left;">Department: {{$department}}</th>
                <th colspan="2" style="text-align: right;"></th>
                <th colspan="4" style="text-align: right;"></th>
            </tr>
        </table>
    </div>
    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead>
                <tr>    
                    <th>Bill Date</th>
                    <th>Start Bill No.</th>
                    <th>End Bill No.</th>
                    <th>Gross</th>
                    <th>Discount</th>
                    <th>Sub Total</th>
                    <th>Taxable Total</th>
                    <th>Non Taxable Total</th>
                    <th>TAX AMT</th>
                    <th>Net Total</th>
                </tr>
            </thead>
            
            <tbody>


                {!!$html!!}

        
                
            </tbody>

        </table>
    </div>
  
     
</div>


 
