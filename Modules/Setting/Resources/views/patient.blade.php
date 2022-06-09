@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

<section class="cogent-nav">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#discharge" role="tab" aria-controls="home" aria-selected="true">Discharge Letter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#death" role="tab">Death Certificate</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#referrral" role="tab">Referral Letter</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#lama" role="tab">LAMA Letter</a>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#admission" role="tab">Admission Report</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#birth" role="tab">Birth Report</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#baby" role="tab">Baby Report</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#opd sheet" role="tab">OPD Sheet</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#diagnostic" role="tab">Diagnostic Help</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#procedure" role="tab">Procedure Report</a>
        </li>
       

    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="sample" role="tabpanel" aria-labelledby="home-tab">
            <div class="container-fluid">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="worksheet" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 8%;">
                                <label>Print Mode:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <select name="worksheet_print_mode" id="worksheet_print_mode">
                                    <option value=""></option>
                                    <option value="Continuous" {{ Options::get('worksheet_print_mode') == 'Continuous'?'selected':'' }}>Continuous</option>
                                    <option value="Categorical" {{ Options::get('worksheet_print_mode') == 'Categorical'?'selected':'' }}>Categorical</option>
                                </select>

                            </div>
                            <div class="box__icon">
                                <a href="javascript:;" onclick="labSettings.save('worksheet_print_mode')"> <img src="{{asset('assets/images/tick.png')}}" alt=""> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="death" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="referral" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="lama" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="admission" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="birth" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="baby" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="opd" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="diagnotic" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="procedure" role="tabpanel">
            <div class="container-fluid">
                <div class="row mt-3">
                <div class="col-md-12">
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label>Report Name:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="report_name" class="report_name" value=""/>
                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Patient Info:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>

                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Body:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                        <div class="group__box half_box">
                            <div class="box__label__purchase" style="flex: 0 0 18%;">
                                <label> Report Footer:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 27%;">
                               <input name="patient_info" type="checkbox" > Default
                            </div>
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input name="patient_info" value="" id="patient_info">

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

@stop