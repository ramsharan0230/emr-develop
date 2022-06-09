@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Lab Category Wise Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form action="{{ route('report.lab-category-wise') }}" method="get" id="labCatReportForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-2">From:</label>
                                    <div class="col-sm-8">
                                        <input type="date" id="fromDate" name="fromDate" class="form-control" value="{{isset($fromDate) ? $fromDate : ''}}"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-2">To:</label>
                                    <div class="col-sm-8">
                                        <input type="date" id="toDate" name="toDate" class="form-control" value="{{isset($toDate) ? $toDate : ''}}"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <select name="testCategory" id="chooseReportCategory" class="form-control">
                                        <option value="">-- Select Report Category --</option>
                                        @foreach ($testCategories as $testCategory)
                                        <option @if($selectedTestCategory == $testCategory->fldcategory) selected @endif value="{{ $testCategory->fldcategory }}">{{ $testCategory->fldcategory }} Report</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="submitType" id="submitType">
                            <div class="col-lg-3 col-sm-3">
                                <button type="submit" name="submitButton" value="refresh" id="refreshReport" class="btn btn-primary rounded-pill"><i class="fa fa-check"></i> Refresh</button>
                                <button type="submit" name="submitButton" value="pdf" id="pdfReport" class="btn btn-primary rounded-pill"><i class="fa fa-file"></i> Pdf</button>
                                <button type="submit" name="submitButton" value="export" id="exportReport" class="btn btn-primary rounded-pill" id="exportLabData"><i class="fa fa-code"></i> Export</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="report-lists">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive res-table" style="max-height: none;">
                        <table class="table table-striped table-hover table-bordered " id="patientListTable">
                            <thead class="thead-light" id="table-head">
                                {!! $htmlHead !!}
                            </thead>
                            <tbody id="table-body">
                                {!! $htmlBody !!}
                            </tbody>
                        </table>
                    </div>
                    @if(isset($paginatedData))
                    {{ $paginatedData->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('after-script')
<script>
$("#exportReport").click(function(e) {
    e.preventDefault();
    $('#labCatReportForm').attr("action","{{ route('report.lab-category-wise.export') }}");
    $('#submitType').val("export");
    if($('#chooseReportCategory').val() != ""){
        $('#labCatReportForm').submit();
    }
});
$("#refreshReport").click(function(e) {
    e.preventDefault();
    $('#labCatReportForm').attr("action","{{ route('report.lab-category-wise') }}");
    $('#submitType').val("refresh");
    if($('#chooseReportCategory').val() != ""){
        $('#labCatReportForm').submit();
    }
});
$("#pdfReport").click(function(e) {
    e.preventDefault();
    $('#labCatReportForm').attr("action","{{ route('report.lab-category-wise') }}");
    $('#submitType').val("pdf");
    if($('#chooseReportCategory').val() != ""){
        $('#labCatReportForm').submit();
    }
});
</script>
@endpush
