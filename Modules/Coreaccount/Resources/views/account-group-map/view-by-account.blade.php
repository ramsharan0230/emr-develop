@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account Map List for {{ $AccountName->AccountName??'' }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        @csrf
                        <div class="form-group">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Group Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($items)
                                        @foreach($items as $ledger)
                                            <tr>
                                                <td>{{ $ledger->flditemname }}</td>
                                                <td>{{ $ledger->GroupName }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                {!! $items->render() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        $('.from_date').datepicker({
            dateFormat: 'yy-mm-dd',
            maxDate: 'today',
        });
        $('.to_date').datepicker({
            dateFormat: 'yy-mm-dd',
            maxDate: 'today',
        });
    </script>
@endpush
