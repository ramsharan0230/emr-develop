@extends('inpatient::pdf.layout.main')

@section('title', 'Patient Discount')

@section('content')
    @include('frontend.common.account-header')
    <style>
        @page {
            margin: 20px;
        }
    </style>

    <table style="width: 100%">
        <tr>
            {{-- <td><b>From Date:</b> {{ date('Y-m-d', strtotime($from_date))}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($from_date)->format('Y-m-d'))->full_date .")" : ''}} </td> --}}
            {{-- <td> <b> From Date : </b> {{ "(". \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::now()->format('Y-m-d'))->full_date .")"}}  </td> --}}
            {{-- <td style="text-align: right;"><b>Printed At:</b> {{ date('Y-m-d H:i:s') }}</td> --}}
            <td></td>
            <td style="text-align: right;"><b>Printed At:</b> {{ date('Y-m-d H:i:s') }}</td>

        </tr>
        <tr>
            {{-- <td><b>To Date:</b> {{ date('Y-m-d', strtotime($to_date)) }} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($to_date)->format('Y-m-d'))->full_date . ")":'' }} </td> --}}
            {{-- <td><b>To Date:</b> {{ date('Y-m-d', strtotime($to_date)) }} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($to_date)->format('Y-m-d'))->full_date . ")":'' }} </td> --}}
            {{-- <td>  <b>To Date:</b>  {{ "(". \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::now()->format('Y-m-d'))->full_date .")"}}  </td> --}}
            <td></td>
            <td style="text-align: right;"><b>Printed By:</b> {{ \App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid) }}</td>
        </tr>
    </table>

    <div style="width: 100%; display: flex; justify-content: center;">
        <h3>Patient Discount</h3>
    </div>

    <table style="width: 100%;"  class="content-body">
                <thead>
                    {{-- <tr>
                        <td style="font-weight: 600;" colspan="13">Cash Refund Bill</td>
                    </tr> --}}
                    <tr>
                        <td></td>
                        <td class="tittle-th">DiscLabel</td>
                        <td class="tittle-th">DiscMode</td>
                        <td class="tittle-th">BillingMode</td>
                        <td class="tittle-th">StartDate</td>
                        {{-- <td class="tittle-th">DiscATM</td> --}}
                        <td class="tittle-th">DiscATM/Year</td>
                        <td class="tittle-th">CreditAmt</td>
                        <td class="tittle-th">Created By</td>
                        <td class="tittle-th">Updated By</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discountData as $dis)
                        <tr >

                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dis->fldtype }}</td>
                            <td>{{ $dis->fldmode }}</td>
                            <td>{{ $dis->fldbillingmode }}</td>
                            <td>{{ date('Y-m-d', strtotime($dis->fldyear)) }}</td>
                            <td>{{ $dis->fldamount }}</td>
                            <td>{{ $dis->fldcredit }}</td>
                            {{-- <td></td> --}}
                            <td>{{ $dis->flduserid }}</td>
                            <td>{{ !is_null($dis->cogentUser) ? $dis->cogentUser->firstname : null }}</td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
    </div>
        @php
            $signatures = Helpers::getSignature('discount-report');
        @endphp
        {{-- @include('frontend.common.footer-signature-pdf') --}}
    </main>
    </body>
    </html>
