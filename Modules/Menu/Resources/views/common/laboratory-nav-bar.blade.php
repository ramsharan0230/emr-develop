@php
$segment = Request::segment(1);
$segment = ($segment == 'radiology') ? 'radio' : 'lab';
$encounter_id_selector = ($segment == 'radio') ? 'js-sampling-encounterid-input' : 'encounter_id';
$encounter_id_selector = '#' . $encounter_id_selector;
@endphp

<div class="iq-top-navbar second-nav">
    <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif " />
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ri-menu-3-line"></i>
            </button> -->
            <!-- <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-more-fill"></i></div>
                    <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                </div> -->
                <div class="navbar-collapse">
                    <ul class="navbar-nav navbar-list">
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">File <i class="ri-arrow-down-s-line"></i></a>
                            <div class="iq-sub-dropdown navbar-scroll">
                                <ul>
                                    <li><a class="iq-sub-card" href="{{ route('laboratory.addition.reset.laboratory.encounter') }}">Blank form</a>
                                        <li><a class="iq-sub-card" href="javascript:void(0)" onclick="laboratoryTab.LastEncounterLab();">Last EncID</a>
                                            <li><a class="iq-sub-card" href="javascript:void(0)" onclick="testGroup.displayModal('{{ $segment }}', 'test', '{{ $encounter_id_selector }}')">Add Test</a>
                                                <li><a class="iq-sub-card" href="javascript:void(0)" onclick="testGroup.displayModal('{{ $segment }}', 'group', '{{ $encounter_id_selector }}')">Add Group</a>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
