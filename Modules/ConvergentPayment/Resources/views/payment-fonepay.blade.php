@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Fonepay QR Payment</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="center">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(json_decode($response_qr)->qrMessage, 'QRCODE') }}" alt="barcode" class="center" style="width: 200px"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        var exampleSocket = new WebSocket("{{ json_decode($response_qr)->thirdpartyQrWebSocketUrl }}", "protocolOne");
        console.log(exampleSocket);
    </script>
@endpush
