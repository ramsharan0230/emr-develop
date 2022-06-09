@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                               Ageing Report
                            </h3>
                        </div>
                    </div>
                    <form action="{{ route('ageing.report.export') }}" method="POST" id="ageing-form">
                        @csrf
                        <div class="iq-card-body">
                            <div class="form-row">
                            <div class="form-group form-row">
                                        <label for="" class="col-sm-3">From:</label>
                                        <div class="col-sm-9">
                                            <input type="text" autocomplete="off" class="form-control" name="from_date"
                                                   id="from_date" value=""/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="">
                                        </div>

                                    </div>


                                <label for="" class="col-2">Type</label>
                                <div class="col-4">
                                    <select name="page" id="page" class="form-control select2">
                                        <option value="">Select</option>
                                    <option value="Debtor" >Debtor</option>
                                    <option value="Creditor" >Creditor</option>
                                    <option value="Employee" >Employee</option>
                                    <option value="Intercompany" >Intercompany</option>
                                    <option value="Debtors-list-and-balance ">Debtors-list-and-balance</option>


                                    </select>
                                </div>




                                <div class="col-sm-7  mt-3">
                                    <a href="javascript:;" class="btn btn-primary btn-action" onclick="getReportDetail()"><i class="fa fa-filter"></i> Export</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


</div>








@endsection
@push('after-script')
<script>
        $(window).ready(function () {

            $('#from_date').val(AD2BS('{{request()->get('eng_from_date')??date('Y-m-d')}}'));

            $('#eng_from_date').val(BS2AD($('#from_date').val()));
        });
        $(function () {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });

            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_from_date').val(BS2AD($('#from_date').val()));
                }
            });


        });


        function getReportDetail() {

            window.open("{{ route('ageing.report.export') }}?" + $('#ageing-form').serialize(), '_blank');
        }














    </script>
@endpush

