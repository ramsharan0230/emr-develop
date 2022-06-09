@extends('frontend.layouts.master')
@section('content')
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @include('menu::common.major-procedure-nav-bar')
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <div class="row">
            @include('frontend.common.patientProfile')
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            @if(\App\Utils\Permission::checkPermissionFrontendAdmin( 'signin-otchecklists' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' ))
                                <li class="nav-item">
                                    <a class="nav-link active" id="ot-checklist-getDataOnClick" data-toggle="tab" href="#otchecklist" role="tab" aria-controls="otchecklist" aria-selected="true">OT Checklist</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-procedure-getDataOnClick" data-toggle="tab" href="#newproc" role="tab" aria-controls="newproc" aria-selected="false">New Procedure</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link active" id="new-procedure-getDataOnClick" data-toggle="tab" href="#newproc" role="tab" aria-controls="newproc" aria-selected="false">New Procedure</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" id="pre-anaesthesia-evaluation-getDataOnClick" data-toggle="tab" href="#pre-anaesthesia-evaluation" role="tab" aria-controls="pre-anaesthesia-evaluation" aria-selected="false">Pre-Anaesthesia Evaluation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pre-anaesthesia-getDataOnClick" data-toggle="tab" href="#pre-anaesthesia" role="tab" aria-controls="pre-anaesthesia" aria-selected="false">Pre-Anaesthesia</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="intra-operative-getDataOnClick" data-toggle="tab" href="#intraoperative" role="tab" aria-controls="intraoperative" aria-selected="false">Intra-Operative</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="pre-operative-getDataOnClick" data-toggle="tab" href="#preoperative" role="tab" aria-controls="preoperative" aria-selected="false">Pre-Operative</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" id="operation-getDataOnClick" data-toggle="tab" href="#operation" role="tab" aria-controls="operation" aria-selected="false">Operation</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="anaesthesia-getDataOnClick" data-toggle="tab" href="#anasthesia" role="tab" aria-controls="anasthesia" aria-selected="false">Anasthesia</a>
                            </li> -->
                           <!--  <li class="nav-item">
                                <a class="nav-link" id="post-operative-getDataOnClick" data-toggle="tab" href="#postoperative" role="tab" aria-controls="postoperative" aria-selected="false">Post-Operative</a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            @if(\App\Utils\Permission::checkPermissionFrontendAdmin( 'signin-otchecklists' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ) || \App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' ))
                            @include('majorprocedure::layouts.menus._ot-checklist')
                            @endif
                            @include('majorprocedure::layouts.menus._new-procedure')
                            @include('majorprocedure::layouts.menus._pre-anaesthesia')
                            @include('majorprocedure::layouts.menus._pre-anaesthesia-evaluation')
                            @include('majorprocedure::layouts.menus._intraoperative')
                            @include('majorprocedure::layouts.menus._pre-operative')
                            @include('majorprocedure::layouts.menus._operation')
                            @include('majorprocedure::layouts.menus._anaesthesia')
                            @include('majorprocedure::layouts.menus._post-operative')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="js-examination-content-modal" class="modal"></div>
    @include('majorprocedure::layouts.modal.newProcedure_variables')
    @include('majorprocedure::layouts.modal.newProcedure_freetext')
    @include('majorprocedure::layouts.modal.newProcedure_procedure')
    @include('majorprocedure::layouts.modal.newProcedureExcel')
@endsection
@push('after-script')
    <script>
        $(".gotopatient").click(function () {

            var url = $(this).attr('url');
            var encounter_id = $(this).attr('encounter_id');
            var type = $(this).attr('type');


            $.ajax({
                url: '{{ route('setsessionbed') }}',
                type: "POST",

                data: {
                    encounter_id: encounter_id,
                    type: type
                },
                success: function (test) {
                    window.open(url);
                }
            });
        });
    </script>
    <script src="{{ asset('js/major_procedure.js')}}"></script>
    <script>
        CKEDITOR.replace('newprocedure_detail',
        {
        height: '300px',
        } );
        CKEDITOR.replace('pre_operative_discussion_textarea',
        {
        height: '300px',
        } );

        CKEDITOR.replace('clinical_note_textarea',
        {
        height: '300px',
        } );
        CKEDITOR.replace('clinical_note_operation_textarea',
        {
        height: '300px',
        } );
        CKEDITOR.replace('clinical_note_ana_textarea',
        {
        height: '300px',
        } );
        CKEDITOR.replace('clinical_note_postOp_textarea',
        {
        height: '300px',
        } );

        $(document).ready(function () {
            $("document").ready(function () {
                setTimeout(function () {
                    $(".getRelatedDataNewProcedure tr:first-child").trigger('click');
                }, 1000);
            });
            // $("#newPorcedure_fldnewdate").datetimepicker({
            //     dateFormat: "yy-mm-dd",
            // });
        })
    </script>
@endpush
