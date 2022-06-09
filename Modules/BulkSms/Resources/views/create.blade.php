@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Bulk SMS</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="tab-content" id="myTabContent-3">

                            <div class="tab-pane fade show active" id="dicom" role="tabpanel" aria-labelledby="dicom">
                                <form method="POST" action="{{ route('bulksms.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">From Date</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                                    {{-- <input type="hidden" name="from_date" id="from_date"/> --}}
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">To Date</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                                    {{-- <input type="hidden" name="to_date" id="to_date"/> --}}
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">Type:</label>
                                                <div class="col-sm-9">
                                                    <select name="fldtype" id="type" class="form-control select2">
                                                        <option value="" selected disabled>---select---</option>
                                                        <option value="All_Patient">All_Patient</option>
                                                        <option value="Province">Province</option>
                                                        <option value="District">District</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">Sub Type:</label>
                                                <div class="col-sm-10">
                                                    <select name="fldsubtype" id="subtype" class="form-control select2">
                                                        <option value="" selected disabled>---select---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">Message:</label>
                                                <div class="col-sm-10">
                                                    <textarea name="fldmessage" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2"></label>
                                                <div class="col-sm-10">
                                                    <button class="btn btn-action btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script>
        // $('#from_date').val(BS2AD($('#from_date_bs').val()));
        // $('#to_date').val(BS2AD($('#to_date_bs').val()));

        const data = {!! $addresses !!};

        $('#type').change(function() {
            let items = [{
                text: '---select---',
                value: ''
            }];
            const type = $(this).val();
            if (type == "All_Patient") {
                items = [{
                        text: 'All_Patient',
                        value: 'All_Patient'
                    }
                ];
            } else if (type == "Province") {
                $.each(data, function(province, districts) {
                    items.push({
                        text: province,
                        value: province
                    })
                });
            } else if (type == "District") {
                $.each(data, function(province, districts) {
                    $.each(districts, function(i, district) {
                        items.push({
                            text: district.flddistrict,
                            value: district.flddistrict
                        })
                    })
                });
            }
            $('#subtype').html("");
            $.each(items, function(i, item) {
                $('#subtype').append($('<option>', {
                    value: item.value,
                    text: item.text
                }));
            });
        });
    </script>
@endpush
