@extends('frontend.layouts.master')

@section('content')
    @include('menu::common.delivery-nav-bar')
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <div class="row">
            @include('frontend.common.patientProfile')
            <div class="col-sm-12">
                <div class="iq-card">
                    @include('delivery::tabs.tab')
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="js-newdelivery-add-item-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="text" id="js-newdelivery-flditem-input-modal" style="width: 100%;">
                        <input type="hidden" id="js-newdelivery-type-input-modal">
                    </div>
                    <div>
                        <button style="float: left;" class="btn btn-sm-in btn-primary" type="button" id="js-newdelivery-add-btn-modal"><i class="fa fa-plus"></i></button>
                        <button style="float: right;" class="btn btn-sm-in btn-primary" type="button" id="js-newdelivery-delete-btn-modal"><i class="fa fa-trash"></i></button>
                    </div>
                    <div>
                        <table id="js-newdelivery-table-modal"></table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-newdelivery-gender-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Gender</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="hidden" id="js-newdelivery-fldid-input-gender-modal">
                            <input type="hidden" id="js-newdelivery-fldpatientval-input-gender-modal">
                            <label>Gender of Baby</label>
                            <select class="form-control" id="js-newdelivery-gender-select-gender-modal">
                                <option value="">-- Select --</option>
                                @foreach($genders as $gender)
                                    <option value="{{ $gender }}">{{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button style="width: 100%;margin-bottom: 5px;" class="btn" id="js-newdelivery-add-btn-gender-modal">Ok</button>
                            <button style="width: 100%;" type="button" class="btn onclose" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('outpatient::modal.diagnosis-freetext-modal')
    @include('outpatient::modal.laboratory-radiology-modal')
    @include('inpatient::layouts.modal.demographics', ['module' => 'delivery'])
    @include('outpatient::modal.diagnosis-obstetric-modal')
@endsection

@push('after-script')
    <script>
        $(document).ready(function () {
            $('#js-newdelivery-deliverytime-input').timepicker({
                format: 'LT'
            });

            var encounter_id = $('#encounter_id').val();
            var qddata = "{{ isset($qddata) && $qddata ? 'show' : 'hide' }}";
            if (encounter_id != '' && qddata == 'show')
                obstetric.displayModal(encounter_id)
        });
    </script>
    <script src="{{ asset('js/delivery_form.js')}}"></script>
@endpush
