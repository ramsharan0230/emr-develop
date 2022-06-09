@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Service Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-sm-12">
                            <form action="{{ route('service.cost.report.search') }}" method="get">
                                @csrf
                                <div class="form-group form-row align-items-center">
                                    <!--                                    <div class="form-group col-sm-3">
                                                                            <div class=" justify-content-between">
                                                                                <label>Name</label>
                                                                                <input type="text" name="search" id="search"
                                                                                       value="" class="form-control" placeholder="search...">
                                                                            </div>
                                                                        </div>-->
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">Form:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                                <input type="hidden" name="eng_from_date" id="eng_from_date">
                                            </div>
                                            <!--  <div class="col-sm-3">
                                                 <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                             </div> -->
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">To:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                                <input type="hidden" name="eng_to_date" id="eng_to_date">
                                            </div>
                                            <!-- <div class="col-sm-2">
                                                <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-action" id="refreshBtn"><i
                                                class="ri-refresh-line"></i> Search
                                        </button>
                                        <button type="button" class="btn btn-primary btn-action" id="exportBtn"><i
                                                class="ri-code-s-slash-line "></i> Export
                                        </button>
                                    </div>
                                </div>


                            </form>
                            <div class="form-group">
                                <div class="table-sticky-th">
                                    <table class="table table-bordered table-striped table-hover mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SNo</th>
                                            <th>Bill Number</th>
                                            <th>Encounter/Patient</th>
                                            <th>Name</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Dis</th>
                                            <th>Amount</th>
                                            <th>User</th>
                                            <th>Time</th>
                                            {{--  <th>Category</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($serviceData)
                                            @foreach($serviceData as $service)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $service->BILLNO }}</td>
                                                    <td>{{ $service->PATIENTID }}</td>
                                                    <td>{{ $service->fldptnamefir .' '.$service->fldptnamelast }}</td>
                                                    <td>{{ $service->SERVICETYPE }}</td>
                                                    <td>{{ $service->QTY }}</td>
                                                    <td>{{ $service->AMOUNT }}</td>
                                                    <td>{{ $service->DISCOUNT }}</td>
                                                    <td>{{ $service->Total_Amount }}</td>
                                                    <td>{{ $service->USERNAME }}</td>
                                                    <td>{{ $service->BILLDATETIME }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    @if($serviceData)
                                    {{ $serviceData->appends($_GET)->links() }}
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script type="text/javascript">
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{ isset($eng_to_date) ? $eng_to_date : date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{isset($eng_from_date) ? $eng_from_date :date('Y-m-d')}}'));

            $('#eng_from_date').val(BS2AD($('#from_date').val()));
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
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

        $(document).ready(function () {
            $("#exportBtn").on('click', function () {
                var urlReport = "{{ route('service.cost.report.export') }}" + "?eng_from_date=" + $('#eng_from_date').val() + "&from_date=" + $('#from_date').val()  + "&eng_to_date=" + $('#eng_to_date').val()  + "&to_date=" + $('#to_date').val() + "&_token=" + "{{ csrf_token() }}";
                window.open(urlReport, '_blank');
            });
        });
    </script>
@endpush
