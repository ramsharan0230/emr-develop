@extends('frontend.layouts.master')
@push('after-styles')
    <style>
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Moving Type Analysis Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="item_filter_data">
                            <div class="form-row">
                                <div class="col-lg-2 col-sm-2">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">From:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-2">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Analysis Type:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="analysis_type" id="analysis_type">
                                                <option value="quantity">Quantity</option>
                                                <option value="amount">Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Depart:</label>
                                        <div class="col-sm-7">
                                            <select name="comp" id="comp" class="form-control department">
                                                <option value="%">%</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Billing Mode:</label>
                                        <div class="col-sm-7">
                                            <select name="billing_mode" id="billing_mode" class="form-control">
                                                <option value="%">%</option>
                                                @if(isset($billingset))
                                                    @foreach($billingset as $b)
                                                        <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getSearchData()">&nbsp;
                                        Search</a>
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getPdfData()"><i class="fa fa-file-pdf"></i>&nbsp;
                                        PDF</a>
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="getExportData()"><i class="fa fa-code"></i>&nbsp;
                                        Export</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="quantity_block">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                            <tr>
                                <th>S.No.</th>
                                <th>Generic Name</th>
                                <th>Brand Name</th>
                                <th>Category</th>
                                <th>Sold Qty</th>
                                {{-- <th>Total Qty</th> --}}
                                <th>Unit Price</th>
                                <th>Moving Type</th>
                                <th>Total Amt</th>
                            </tr>
                            </thead>
                            <tbody id="table_result">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="amount_block" style="display: none;">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                            <tr>
                                <th>S.No.</th>
                                <th>Generic Name</th>
                                <th>Brand Name</th>
                                <th>Category</th>
                                <th>Sold Qty</th>
                                <th>Unit Price</th>
                                <th>Value Type</th>
                                <th>Total Amt</th>
                            </tr>
                            </thead>
                            <tbody id="table_amt_result">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
<script>
    $(window).ready(function () {
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
    })

    $(document).on('change','#analysis_type',function(){
        var value = $(this).val();
        if(value == "quantity"){
            $('#amount_block').hide();
            $('#quantity_block').show();
            $('#table_result').html("");
        }else{
            $('#amount_block').show();
            $('#quantity_block').hide();
            $('#table_amt_result').html("");
        }
    });

    $(document).ready(function () {
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getSearchData(page);
        });
    });

    function getSearchData(page) {
        var url = "{{route('abcanalysis.getMovingTypeReport')}}";
        $.ajax({
            url: url + "?page=" + page,
            type: "POST",
            data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                analysis_type: $('#analysis_type').val(),
                comp: $('#comp').val(),
                billing_mode: $('#billing_mode').val(),
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.status) {
                    if($('#analysis_type').val() == "quantity"){
                        $('#table_result').html(response.html);
                    }else{
                        $('#table_amt_result').html(response.html);
                    }
                } else {
                    showAlert("Something went wrong...", "error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function getPdfData(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var analysis_type = $('#analysis_type').val();
        var comp = $('#comp').val();
        var billing_mode = $('#billing_mode').val();
        var urlReport = "{{ route('abcanalysis.getMovingTypeReport') }}" + "?typePdf=pdf&from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&billing_mode=" + billing_mode + "&analysis_type=" + analysis_type + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }

    function getExportData(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var analysis_type = $('#analysis_type').val();
        var comp = $('#comp').val();
        var billing_mode = $('#billing_mode').val();
        var urlReport = "{{ route('abcanalysis.exportMovingTypeReportCsv') }}" + "?from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&billing_mode=" + billing_mode + "&analysis_type=" + analysis_type + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }
</script>
@endpush



