@extends('patient.layouts.master')
@section('content')
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="topspce call_doctor">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="doctor-widget">
                                        <div class="doc-info-left">
                                            <div class="doctor-img">
                                                <img src="images/sanjeet.jpg" class="img-fluid" alt="User Image">
                                            </div>
                                            <div class="doc-info-cont">
                                                <h4 class="doc-name">Dr. Sanjeet Kumar Shrestha</h4>
                                                <p class="doc-speciality">MD, 10 years of experience</p>
                                                <p class="doc-department">Fellowship in Pulmonary & Critical Care Medicine, 15 years of experience</p>


                                                <div class="clinic-services">
                                                    <span>Pulmonologist</span>
                                                    <span>Internal Medicine</span>
                                                </div>
                                                <div class="doctor-action">

                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#voice_call">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#video_call">
                                                        <i class="fas fa-video"></i>
                                                    </a>
                                                </div>
                                                <div class="clinic-booking">
                                                    <a class="apt-btn" href="booking">Book Appointment</a>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="doctor-widget">
                                        <div class="doc-info-left">
                                            <div class="doctor-img">
                                                <img src="images/sanjeet.jpg" class="img-fluid" alt="User Image">
                                            </div>
                                            <div class="doc-info-cont">
                                                <h4 class="doc-name">Dr. Sanjeet Kumar Shrestha</h4>
                                                <p class="doc-speciality">MD, 10 years of experience</p>
                                                <p class="doc-department">Fellowship in Pulmonary & Critical Care Medicine, 15 years of experience</p>


                                                <div class="clinic-services">
                                                    <span>Pulmonologist</span>
                                                    <span>Internal Medicine</span>
                                                </div>
                                                <div class="doctor-action">

                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#voice_call">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#video_call">
                                                        <i class="fas fa-video"></i>
                                                    </a>
                                                </div>
                                                <div class="clinic-booking">
                                                    <a class="apt-btn" href="booking">Book Appointment</a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade call-modal" id="voice_call" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <!-- <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div> -->
                                                            <div class="modal-body">
                                                                <div class="call-box incoming-box">
                                                                    <div class="call-wrapper">
                                                                        <div class="call-inner">
                                                                            <div class="call-user">
                                                                                <img alt="User Image" src="images/sanjeet.jpg" class="call-avatar">
                                                                                <h4>Dr. Sanjeet Kumar Shrestha</h4>
                                                                                <span>Connecting...</span>
                                                                            </div>
                                                                            <div class="call-items">
                                                                                <a href="javascript:void(0);" class="btn call-item call-end" data-dismiss="modal" aria-label="Close"><i class="ri-phone-fill"></i></a>
                                                                                <a href="voice-call" class="btn call-item call-start"><i class="ri-phone-fill"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal -->
                                                <!-- <div class="modal fade call-modal" id="video_call" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <div class="modal-body">
                                                                <div class="call-box incoming-box">
                                                                    <div class="call-wrapper">
                                                                        <div class="call-inner">
                                                                            <div class="call-user">
                                                                                <img alt="User Image" src="images/sanjeet.jpg" class="call-avatar">
                                                                                <h4>Dr. Sanjeet Kumar Shrestha</h4>
                                                                                <span>Connecting...</span>
                                                                                <span>00:50</span>
                                                                            </div>
                                                                            <div class="call-items">
                                                                                <a href="javascript:void(0);" class="btn call-item call-end" data-dismiss="modal" aria-label="Close"><i class="ri-phone-fill"></i></a>
                                                                                <a href="voice-call" class="btn call-item call-start"><i class="ri-vidicon-fill"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div> -->

                                                <!-- Modal -->
                                                <div class="modal fade call-modal" id="video_call" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">

                                                            <div class="modal-body">
                                                                <div class="call-contents">
                                                                    <div class="call-content-wrap">
                                                                        <div class="user-video">
                                                                            <img src="images/video-call.jpg" alt="User Image">
                                                                        </div>
                                                                        <div class="my-video">
                                                                            <ul class="list-unstyled">
                                                                                <li>
                                                                                    <img src="images/sanjeet.jpg" class="img-fluid" alt="User Image">
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="call-footer">
                                                                <div class="call-icons">
                                                                    <span class="call-duration">00:59</span>
                                                                    <ul class="call-items">
                                                                        <li class="call-item">
                                                                            <a href="" title="Enable Video" data-placement="top" data-toggle="tooltip">
                                                                                <i class="fas fa-video camera"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="call-item">
                                                                            <a href="" title="Mute Audio" data-placement="top" data-toggle="tooltip">
                                                                                <i class="fa fa-microphone microphone"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="call-item">
                                                                            <a href="" title="Add User" data-placement="top" data-toggle="tooltip">
                                                                                <i class="fa fa-user-plus"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="call-item">
                                                                            <a href="" title="Full Screen" data-placement="top" data-toggle="tooltip">
                                                                                <i class="fas fa-arrows-alt-v full-screen"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="end-call">
                                                                        <a href="javascript:void(0);" class="btn call-item call-end" data-dismiss="modal" aria-label="Close"><img src="images/call-end.png"></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="doctor-widget">
                                        <div class="doc-info-left">
                                            <div class="doctor-img">
                                                <img src="images/sanjeet.jpg" class="img-fluid" alt="User Image">
                                            </div>
                                            <div class="doc-info-cont">
                                                <h4 class="doc-name">Dr. Sanjeet Kumar Shrestha</h4>
                                                <p class="doc-speciality">MD, 10 years of experience</p>
                                                <p class="doc-department">Fellowship in Pulmonary & Critical Care Medicine, 15 years of experience</p>


                                                <div class="clinic-services">
                                                    <span>Pulmonologist</span>
                                                    <span>Internal Medicine</span>
                                                </div>
                                                <div class="doctor-action">

                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#voice_call">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#video_call">
                                                        <i class="fas fa-video"></i>
                                                    </a>
                                                </div>
                                                <div class="clinic-booking">
                                                    <a class="apt-btn" href="booking">Book Appointment</a>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="doctor-widget">
                                        <div class="doc-info-left">
                                            <div class="doctor-img">
                                                <img src="images/sanjeet.jpg" class="img-fluid" alt="User Image">
                                            </div>
                                            <div class="doc-info-cont">
                                                <h4 class="doc-name">Dr. Sanjeet Kumar Shrestha</h4>
                                                <p class="doc-speciality">MD, 10 years of experience</p>
                                                <p class="doc-department">Fellowship in Pulmonary & Critical Care Medicine, 15 years of experience</p>


                                                <div class="clinic-services">
                                                    <span>Pulmonologist</span>
                                                    <span>Internal Medicine</span>
                                                </div>
                                                <div class="doctor-action">

                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#voice_call">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#video_call">
                                                        <i class="fas fa-video"></i>
                                                    </a>
                                                </div>
                                                <div class="clinic-booking">
                                                    <a class="apt-btn" href="booking">Book Appointment</a>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection