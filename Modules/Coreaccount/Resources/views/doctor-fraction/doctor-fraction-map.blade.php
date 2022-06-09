@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account List for Not Mapped
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('transaction.create.doctor.map') }}">
                            @csrf
                            <div class="form-group form-row">
                                <label for="" class="col-1">Ledger</label>
                                <div class="col-4">
                                    <select name="ledger" id="ledger" class="form-control select2">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}">{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-1">Doctor</label>
                                <div class="col-4">
                                    <select name="doctor" id="doctor" class="form-control select2">
                                        <option value="">Select</option>
                                        @if($doctors)
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        function toggle(source) {
            checkboxes = document.getElementsByClassName('doctors-toggle');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
@endpush
