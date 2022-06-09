@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Voucher Details
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header justify-content-between mt-3">
                        <a href="{{route('accounts.voucher.print-details',["voucher_no" => $voucher_no])}}" class="btn btn-primary btn-action float-right ml-1" target="_blank"><i class="fa fa-print"></i>
                            Print
                        </a>
                        <button type="button" class="btn btn-primary btn-action float-right" onclick="exportVoucherDetails()"><i class="fa fa-arrow-circle-down"></i>
                            Export
                        </button>&nbsp;
                    </div>
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Voucher No. : <b>{{$voucher_no}}</b></p>
                                </div>
                                <div class="col-sm-6">
                                    <p>Date: <b>{{ $date }}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="table-responsive res-table">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Acc No.</th>
                                        <th class="text-center">Account Head</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Dr Amount</th>
                                        <th class="text-center">Cr Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody id="voucher-details">
                                    @php
                                        $drAmount =0;
                                        $crAmount =0;
                                    @endphp
                                    @foreach ($voucherDatas as $key=>$voucherData)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$voucherData->AccountNo}}</td>
                                            <td>{{$voucherData->accountLedger ? $voucherData->accountLedger->AccountName : ''}}</td>
                                            <td>{{$voucherData->Narration}}</td>
                                            @if ($voucherData->TranAmount > 0)
                                                @php
                                                    $drAmount += abs($voucherData->TranAmount);
                                                @endphp
                                                <td>{{abs($voucherData->TranAmount)}}</td>
                                                <td></td>
                                            @else
                                                @php
                                                    $crAmount += abs($voucherData->TranAmount);
                                                @endphp
                                                <td></td>
                                                <td>{{abs($voucherData->TranAmount)}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @if($voucherDatas)
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <th>{{ $drAmount }}</th>
                                            <th>{{ abs($crAmount) }}</th>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                            <div>
                                <p>
                                    <b>Particulars:</b> {{ $voucherDatas[0]->Remarks }}
                                </p>
                                <p>
                                    <b>In Words: Rs.</b> {{ ucwords(\App\Utils\Helpers::numberToNepaliWords($drAmount)) }}
                                </p>
                            </div>

                        </div>
                    </div>
                    {{-- <section>
                        <div>
                            <div style="width: 30%; margin-left: 2rem; float: left">
                                <p>{{$voucherDatas[0]->CreatedBy}}</p>
                                <p style="margin-top: 5px">_________________________</p>
                                <p>Entered By : </p>
                            </div>
                            <div style="width: 30%; margin-left: 2rem; float: left">
                                <p>{{\Auth::guard('admin_frontend')->user()->username}}</p>
                                <p style="margin-top: 5px">_________________________</p>
                                <p>Generated By : </p>
                            </div>
                            <div style="width: 30%; margin-left: 2rem; float: left">
                                <p style="margin-top: 40px">_________________________</p>
                                <p>Approved By : </p>
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </section>        --}}
                </div>
            </div>
        </div>
        @endsection
        @push('after-script')
            <script>
                function exportVoucherDetails() {
                    var voucherNo = "{{$voucher_no}}";
                    var urlReport = baseUrl + "/account/statement/export-voucher-details?voucher_no=" + voucherNo;
                    window.open(urlReport);
                }
            </script>
    @endpush
