@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Pharmacy Sales Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="js-demandform-form">
                            <div class="form-group form-row align-items-center">
                                <div class="col-sm-2">
                                    <label>From Date</label>
                                    <input type="text" name="from_date" id="from_date"
                                           value="{{ $date }}" class="form-control nepaliDatePicker">
                                </div>
                                <div class="col-sm-2">
                                    <label>To Date</label>
                                    <input type="text" name="to_date" id="to_date"
                                           value="{{ $date }}"
                                           class="form-control nepaliDatePicker">
                                </div>

                                <div class="col-sm-2">
                                   
                                    <div>
                                    <button type="button" class="btn btn-primary" id="exportBtn"><i
                                                class="ri-refresh-line"></i></button>
                                      
                                    </div>
                                </div>
                                
                            </div>

                            

                        </form>

                     
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script type="text/javascript">

$('#exportBtn').click(function () {
          
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
           
            if (from_date != '' || to_date != '') {
                var url = baseUrl + '/pharmacy-sales/pharmacy-sales-report?from_date=' + BS2AD(from_date)+'&to_date='+BS2AD(to_date)
                window.open(url, '_blank');
            } else {
                showAlert('Please enter from date and to date', 'error');
            }
        });

     

      
    </script>
@endpush
