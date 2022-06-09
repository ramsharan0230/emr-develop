<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<style>
    table{
        width: 100%;
    }
    .text-center{
        text-align: center;
    }
    .th-record {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .td-record {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .pdf-container{
        width: 100%;
        margin: 0 auto;
    }

    table , td , th{
     padding: 4px;
     width: 20px;
    }

    .text-left{
        text-align: left;
    }

    .text-right{
        text-align: right;
    }
    .table-record{
        border: 1px solid black;
        border-collapse: collapse;
    }
    .border-none{
        border: none;
    }
    .bg-color{
        background-color: #ddd;
    }
</style>
<body>
@include('pdf-header-footer.header-footer')
<main>
    
    <div class="pdf-container">
        <table  style="width: 100%;" class="border-none">
            <tbody  class="border-none">
                <tr class="border-none">
                    <td rowspan="2" class="border-none text-right" style="width: 62%;"><strong>Department of Laboratory</strong></td>
                    <td class="text-right border-none" ><strong>Reception Number:</strong> {{ Options::get('reception_number')??'' }}</td>
                </tr>
                <tr class="border-none">
                    <td class="text-right border-none"><strong>Lab extension Number:</strong> {{ Options::get('lab_extension_number')??'' }}</td>
                </tr>
            </tbody>
        </table>
        <table class="head-table" style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-right  border-none " colspan="8"> Department Of Pathology Monthly Test Record</th>
                </tr>
                <tr>
                    <th class="text-left border-none" colspan="8">From : <?php echo $from_date; ?></th>
                    <th class="text-right border-none" colspan="8">To : <?php echo $to_date; ?></th>
                </tr>
            </thead>
        </table>
        <table class="table-record"  style="width: 100%;">
            <thead>
                <tr>
                    <th class="th-record">Section</th>
                    <th class="th-record">Test Name</th>
                    @if(!empty($billingmode))
                    @php
                    $sumofonebillmode = [];
                    $grandsumofonebillmode = [];
                    @endphp
                    @foreach($billingmode as $mode)
                    @php 
                    $sumofonebillmode[$mode] =0;
                    $grandsumofonebillmode[$mode] =0;
                    @endphp
                    <th class="th-record">{{$mode}}</th>
                    @endforeach
                    @endif
                    
                    <th class="th-record">Total</th>
                    <th class="th-record">Rem</th>
                </tr>
            </thead>
            <tbody>
                    @if($alltest)
                   
                    @foreach($alltest as $k => $test)
                    <?php $testcount = count($test);
                  
                     ?>
                    <tr>
                        <td class="td-record" rowspan="<?php echo $testcount+2; ?>" class="text-center"><strong>{{$k}}</strong></td>
                        @if($test)
                        @foreach($test as $key => $t)
                        <tr>
                      
                        <td class="td-record">{{$key}}</td>
                        @if(!empty($billingmode))
                        @php
                        $sumofbillmode =0;
                      
                        
                        @endphp
                       
                            @foreach($billingmode as $mode)
                            @php
                            $sumofbillmode +=$t[$mode];
                            $sumofonebillmode[$mode] += $t[$mode];
                            $grandsumofonebillmode[$mode] += $t[$mode];

                            @endphp
                            <td class="td-record">{{$t[$mode]}}</td>
                            @endforeach
                            @endif
                        
                          <td class="td-record">{{$sumofbillmode}}</td>
                        
                          <td class="td-record"></td>
                        </tr>
                        @endforeach
                        @endif
                    </tr>
                      
                        
                  
                    <tr>
                        <td class="td-record"><strong>Total</strong></td>
                        @if(!empty($billingmode))
                        @php
                        $totalsumofbillingmode = 0;
                        @endphp
                    @foreach($billingmode as $mode)
                    
                    <td class="td-record"><strong>{{$sumofonebillmode[$mode]}}</strong></td>
                    @php
                    $totalsumofbillingmode +=$sumofonebillmode[$mode];
                    $sumofonebillmode[$mode] =0;
                   
                    @endphp
                    @endforeach
                    @endif
                    
                       <td class="td-record">{{$totalsumofbillingmode}}</td>
                     <td class="td-record"></td>
                 
                  
                    </tr>
                   
                   
                    @endforeach
                    @php 
                    $grandtotalofbillmode =0;
                    @endphp
                   
                    @endif


                    
                    <tr>
                        <td class="td-record" colspan="2" class="text-center"><strong>Grand Total</strong></td>
                        @if(!empty($billingmode))
                        @php 
                        $grandsumfortotal = 0;
                        @endphp
                    @foreach($billingmode as $mode)
                    
                   
                    <td class="td-record"><strong>{{$grandsumofonebillmode[$mode]}}</strong></td>
                  @php
                  $grandsumfortotal += $grandsumofonebillmode[$mode];
                  @endphp
                    @endforeach
                    @endif
                    <td class="td-record">{{$grandsumfortotal}}</td>
                     <td class="td-record"></td> 
                    </tr>
                 
               
            </tbody>
        </table>
       
    </div>
</main>