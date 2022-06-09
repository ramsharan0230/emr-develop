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
                                Account Map For Aging Report
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('ageing.setting.map') }}" method="post">
                            @csrf

                            <div class="form-group form-row">
                                <label for="" class="col-2">Type</label>
                                <div class="col-4">
                                    <select name="type" id="type" class="form-control select2">
                                        <option value="">Select</option>
                                    <option value="Debtor" >Debtor</option>
                                    <option value="Creditor" >Creditor</option>
                                    <option value="Employee" >Employee</option>
                                    <option value="Intercompany" >Intercompany</option>
                                    <option value="Debtors-list-and-balance ">Debtors-list-and-balance</option>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-2">Account Ledger</label>
                                <div class="col-4">
                                    <select name="accountledger[]" id="accountledger" class="form-control select2" multiple="multiple">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountNo }}" >{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <label for="" class="col-2">DR OR CR</label>
                                <div class="col-4">
                                    <select name="accountType" id="accountType" class="form-control select2">
                                        <option value="">Select</option>
                                    <option value="Dr" >Debit</option>
                                    <option value="Cr" >Credit</option>



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
        $( document ).ready(function() {
            $(document.body).on("change","#type",function(){
              //  alert('sds');
              var page = $('#type option:selected').val();
            var url = "{{route('ageing.ledger.select')}}";
            $.ajax({
                url: url+"?page="+page,
                type: "GET",
                success: function(response) {
                    //.val(["1","6"]) alert(response.success.page);
                    selectedValues = response.success.selectledger;

                    $("#accountledger").select2().val(response.success.selectledger).trigger("change");
                    $("#accountType").select2().val(response.success.transaction);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }

            });
		// ...
});


    });
        </script>

@endpush
