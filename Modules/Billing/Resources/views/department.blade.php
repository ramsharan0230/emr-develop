@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Department Collection report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="department_billing_filter_data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>

                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchDepartmentCollectionBillingDetail()"><i class="fa fa-sync"></i>&nbsp;
                                            Refresh</a>&nbsp;
                                        <!-- <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepartmentBillingReport()"><i class="fa fa-code"></i>&nbsp;
                                            Export</a>
                                        &nbsp;
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepartmentWiseRevenue()"><i class="fa fa-code"></i>&nbsp;
                                            Department Wise Revenue Summary</a>&nbsp;
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepartmentWiseReport()"><i class="fa fa-file"></i>&nbsp;
                                            Department Wise Report</a>&nbsp; -->
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportNewReport()"><i class="fa fa-file"></i>&nbsp;
                                            New Report</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportCategorywiseReport()"><i class="fa fa-file"></i>&nbsp;Category Wise Report</a>
                                    </div>
                                </div>
                                <div class="px-3">
                                    <div class="form-group d-flex flex-row align-items-center">
                                        <div>
                                            <input type="radio" class="radio" name="summary_or_detail">
                                        </div>
                                        <label for="" class="ml-1">Summary</label>
                                    </div>
                                </div>
                                <div class="px-3">
                                    <div class="form-group d-flex flex-row align-items-center">
                                        <div>
                                            <input type="radio" class="radio" name="summary_or_detail">
                                        </div>
                                        <label for="" class="ml-1">Detail</label>
                                    </div>
                                </div>
                                <div class="px-3">
                                    <div class="form-group d-flex flex-row align-items-center">
                                         <div>
                                            <input type="radio" class="radio" value="category" name="summary_or_detail">
                                        </div>
                                        <label for="" class="ml-1">Category</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
<div id="department_billing_result"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-script')
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $(".department").select2();

            }, 1500);
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                searchDepartmentCollectionBillingDetail(page);
            });
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });


        function searchDepartmentCollectionBillingDetail(page) {
            var selected = $('input[name="summary_or_detail"]:checked').val();
            if(selected == "category"){
                var url = "{{route('department.searchCategoryWiseReport')}}";
                if (page !== undefined) {
                    url = url + "?page=" + page;
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $("#department_billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                    success: function (response) {
                    $("#department_billing_result").html(response.html);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }else{
                var url = "{{route('searchDepartmentCollectionBillingDetail')}}";
                if (page !== undefined) {
                    url = url + "?page=" + page;
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $("#department_billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                    success: function (response) {
                    $("#department_billing_result").html(response.html);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }

        function exportDepartmentBillingReport() {
            // alert('export');
            var data = $("#department_billing_filter_data").serialize();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/export-department-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');
        }

        function exportDepartmentWiseRevenue() {

            var data = $("#department_billing_filter_data").serialize();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/export-department-wise-revenue?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');
        }

        function exportDepartmentWiseReport() {
            var data = $("#department_billing_filter_data").serialize();
            var urlReport = baseUrl + "/billing/service/export-department-wise-report?" + data + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport, '_blank');
        }

        function exportNewReport() {
            var data = $("#department_billing_filter_data").serialize();
            var urlReport = baseUrl + "/billing/service/export-new-report?" + data + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport, '_blank');
        }

        function exportCategorywiseReport() {
            var billingmode = '%';
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var comp = '%';
            var selectedItem = "";
            var dateType = "entry_date";
            var itemRadio = "all_items";
            var urlReport = baseUrl + "/mainmenu/group-report/export-categorywise-report?billingmode=" + billingmode + "&from_date=" + from_date + "&to_date=" + to_date + "&comp=" + comp + "&selectedItem=" + selectedItem + "&dateType=" + dateType + "&itemRadio=" + itemRadio;
            window.open(urlReport, '_blank');
        }
    </script>
@endpush


