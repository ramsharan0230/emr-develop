@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Purchase report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="purchase_filter_data">
                            <div class="row">

                                <div class="col-lg-2 col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>

                                </div>
                                

                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        
                                        <div class="col-sm-7">
                                            <select name="department" id="" class="form-control department">
                                                <option value="">--Department--</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        @if($dept->departmentData)
                                                            <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @endif
                                                @empty

                                                @endforelse
                                            @endif
                                            <!-- <option value="Male"></option> -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                                               

                                <div class="col-sm-5">
                                    <div class="d-flex float-right">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchPurchaseDetail()"><i class="fa fa-filter"></i>&nbsp;
                                            Filter</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportPurchaseReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                            Export</a>&nbsp;

                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportPurchaseReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                        Export To Excel</a>&nbsp;

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height" id="purchase_result">

                </div>
            </div>
        </div>
    </div>
    {{-- @include('billing::modal.user-list') --}}
@endsection
@push('after-script')
    <script type="text/javascript">
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

        // $(document).on('click', '.pagination a', function (event) {
        //     event.preventDefault();
        //     var page = $(this).attr('href').split('page=')[1];
        //     searchPurchaseDetail();
        // });

        function searchPurchaseDetail() {

            var url = "{{route('searchPurchaseDetail')}}";
            
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            $.ajax({
                url: url,
                type: "get",
                data: $("#purchase_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    $('#purchase_result').empty().html(response.html);
                    $('#myTable1').bootstrapTable();
                    
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportPurchaseReport() {
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            var data = $("#purchase_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/kharid/export-purchase-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }

        function exportPurchaseReportToExcel() {
            if($('.department').val() == ""){
                alert('Please choose department');
                return false;
            }
            var data = $("#purchase_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/kharid/export-purchase-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }
        // $(function() {
        //     $('#myTable1').bootstrapTable()
        // })
    </script>
@endpush


