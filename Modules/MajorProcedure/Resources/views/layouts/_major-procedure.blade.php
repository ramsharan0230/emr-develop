{{--patient profile--}}
@include('frontend.common.patientProfile')
{{--end patient profile--}}
<!-- Tabs Content -->
<div class="inpatient__content" id="major_procedure_form_reset">
    <div class="" style="border: none;">
        <div class="row">
            <div class="col-md-12">
                <div class="main-tab">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#new-procedure"
                            id="new-procedure-getDataOnClick">New Procedure </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pre-operative"
                            id="pre-operative-getDataOnClick">Pre Operative </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#operation" id="operation-getDataOnClick">Operation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#anaesthesia" id="anaesthesia-getDataOnClick">Anaesthesia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#post-operative"
                            id="post-operative-getDataOnClick">Post-Operative </a>
                        </li>
                    </ul>
                    <div class="tab-content content-border" id="myTabContent">
                        <!-- MEMU TAB CONTAINES -->
                        @include('majorprocedure::layouts.menus._new-procedure')
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


