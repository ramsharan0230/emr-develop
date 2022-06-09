@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        @if(Session::get('success_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('success_message') }}
                            </div>
                        @endif

                        @if(Session::get('error_message'))
                            <div class="alert alert-danger containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                {{ Session::get('error_message') }}
                            </div>
                        @endif

                        <table class="table  table-bordered adminMgmtTable">
                            <thead>
                            <tr>
                                <th class="text-center">S.N</th>
                                <th class="text-center">Partners Logo</th>
                                <th class="text-center">Partners Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Current Mode</th>
                                <th>Operation</th>
                            </tr>
                            </thead>

                            <tbody>

                            <!-- Esewa-->
                            {{--<tr>
                                <td align="center">1</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/esewa.jpg') }}">
                                </td>
                                <td align="center">
                                    <strong>eSewa Wallet</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('esewa_payment_status') && Options::get('esewa_payment_status') == 'active' )
                                        <strong style="color:green;">
                                            <span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active
                                        </strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('esewa_mode') && Options::get('esewa_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.esewa') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnEsewaBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}



                            <!-- PAYPAL -->
                            {{--<tr>
                                <td align="center">3</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/paypal_icon.png') }}">
                                </td>
                                <td align="center">
                                    <strong>PayPal Payment Gateway</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('paypal_payment_status') && Options::get('paypal_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('paypal_mode') && Options::get('paypal_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.paypal') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    <a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnPayPalBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>
                                </td>
                            </tr>--}}

                            <!-- CONVERGENT -->
                            <tr>
                                <td align="center">4</td>
                                <td align="center">
                                    <img src="{{ asset('images/fonepay.png') }}" class="w-50">
                                    <strong>Convergent</strong>
                                </td>
                                <td align="center">
                                    <strong>Convergent Payment System</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('convergent_payment_status') && Options::get('convergent_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('convergent_mode') && Options::get('convergent_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.convergent') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    {{--                                <a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnConverBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}
                                </td>
                            </tr>

                            <!-- CREDIT CARD -->
                            {{--<tr>
                                <td align="center">2</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/payment.png') }}">
                                    <strong>Credit Card</strong>
                                </td>
                                <td align="center">
                                    <strong>Credit Card Payment</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('nabil_payment_status') && Options::get('nabil_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('nabil_mode') && Options::get('nabil_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.nabil-credit') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnCCBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}
                            <!-- CREDIT CARD -->
                            {{--<tr>
                                <td align="center">2</td>
                                <td align="center">
                                    @if ( Options::get('hbl_logo') && Options::get('hbl_logo') != "" )
                                        <img src="{{ asset('uploads/paymentpartner/'.Options::get('hbl_logo')) }}" class="img-thumbnail" style="max-height: 40px;">
                                    @else
                                        <img src="{{ asset('backend/img/dashboard_icons/payment.png') }}">
                                    @endif
                                    <strong>HBL</strong>
                                </td>
                                <td align="center">
                                    <strong>HBL Payment</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('hbl_payment_status') && Options::get('hbl_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('hbl_mode') && Options::get('hbl_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.hbl') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnCCBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}

                            <!-- IMEPAY -->
                            {{--<tr>
                                <td align="center">2</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/imepay.png') }}">
                                </td>
                                <td align="center">
                                    <strong>IMEPay System</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('imepay_payment_status') && Options::get('imepay_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('imepay_mode') && Options::get('imepay_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.imepay') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                </td>
                            </tr>--}}

                            <!-- nPAY -->
                            {{--<tr>
                                <td align="center">6</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/npay.png') }}">
                                    <strong>nPay</strong>
                                </td>
                                <td align="center">
                                    <strong>nPay Payment System</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('npay_payment_status') && Options::get('npay_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('npay_mode') && Options::get('npay_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.npay') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnNPayBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}

                            <!-- NIBL -->
                            {{--<tr>
                                <td align="center">7</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/nibl.png') }}">
                                    --}}{{--<strong>NIBL</strong>--}}{{--
                                </td>
                                <td align="center">
                                    <strong>NIBL Payment</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('nibl_payment_status') && Options::get('nibl_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('nibl_mode') && Options::get('nibl_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.nibl') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnNiblBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}

                            <!-- Prabhu Bank -->
                            {{--<tr>
                                <td align="center">8</td>
                                <td align="center">
                                    <img src="{{ asset('backend/img/dashboard_icons/prabhu.png') }}">
                                    --}}{{--<strong>Prabhu Bank</strong>--}}{{--
                                </td>
                                <td align="center">
                                    <strong>Prabhu Bank Payment</strong>
                                </td>
                                <td align="center">
                                    @if ( Options::get('prabhu_payment_status') && Options::get('prabhu_payment_status') == 'active' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Active</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Disabled</strong>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ( Options::get('prabhu_mode') && Options::get('prabhu_mode') == 'live' )
                                        <strong style="color:green;"><span class="badge badge-success badge-icon badge-fw" style="margin: 0px;"></span> Live</strong>
                                    @else
                                        <strong style="color:#bf302f;"><span class="badge badge-danger badge-icon badge-fw" style="margin: 0px;"></span> Test</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.paymentgateway.prabhu') }}" class="btn btn-info adminMgmtTableBtn" title="Edit"><i class="fa fa-edit"></i> Update</a>
                                    --}}{{--<a href="javascript:void(0)" class="btn btn-primary adminMgmtTableBtn btnPrabhuBankCharge" title="Edit"><i class="fa fa-edit"></i> Bank Charge</a>--}}{{--
                                </td>
                            </tr>--}}

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.btnEsewaBankCharge').click(function () {
                $('#esewa-bank-charge').modal({show: true});
            });

            $('.btnIMEPayBankCharge').click(function () {
                $('#imepay-bank-charge').modal({show: true});
            });

            $('.btnPayPalBankCharge').click(function () {
                $('#paypal-bank-charge').modal({show: true});
            });

            $('.btnConverBankCharge').click(function () {
                $('#convergent-bank-charge').modal({show: true});
            });

            $('.btnCCBankCharge').click(function () {
                $('#credit-card-bank-charge').modal({show: true});
            });

            $('.btnNPayBankCharge').click(function () {
                $('#npay-bank-charge').modal({show: true});
            });
        });
    </script>

@stop
