@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp
<nav class="navbar navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <input type="hidden" id="fldencounterval"
               value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOutcome" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">File</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownOutcome">

                    <a class="dropdown-item" href="javascript:void(0)" onclick="consultationReport.SearchEncModal()">Search(EncID)</a>

                    @if($segment == 'consultation' && $segment2 == "visit-report")
                        <a class="dropdown-item" href="javascript:void(0)" onclick="visitReport.SearchNameModal()">Search(Name)</a>
                    @endif
                    @if($segment == 'consultation' && $segment2 == "transition")
                        <a class="dropdown-item" href="javascript:void(0)" onclick="consultationTransition.SearchNameModal()">Search(Name)</a>
                    @endif

                    @if($segment == 'consultation' && $segment2 == "procedure-report")
                        <a class="dropdown-item" href="javascript:void(0)" onclick="consultationProcedureReport.SearchNameModal()">Search(Name)</a>
                    @endif

                    @if($segment == 'consultation' && $segment2 == "equipment")
                        <a class="dropdown-item" href="javascript:void(0)" onclick="consultationEquipment.SearchNameModal()">Search(Name)</a>
                    @endif

                    @if($segment == 'consultation' && $segment2 == "confinement")
                        <a class="dropdown-item" href="javascript:void(0)" onclick="consultationConfinement.SearchNameModal()">Search(Name)</a>
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOutcome" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Summary</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownOutcome">
                    @if($segment == 'consultation' && $segment2 == "visit-report")
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Gender')">Gender</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Surname')">Surnames</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('District')">Districts</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Ethnic Group')">Ethnicity</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Age Group')">Age Group</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Regd Depart')">Regd Depart</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Regd Location')">Regd Location</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Billing Group')">Billing Group</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Discount')">Discount</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Visit Type')">Visit Type</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="visitDataMenu.pdfGenerate('Last Status')">Last Status</a>
                    @endif

                    @if($segment == 'consultation' && $segment2 == "ip-events")
                        <a class="dropdown-item" href="javascript:void(0);" onclick="ipEventsMenu.pdfGenerate('Department')">Department</a>
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown">
                {{-- <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOutcome" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Date Wise</a>
 --}}
            </li>
        </ul>
    </div>
</nav>
