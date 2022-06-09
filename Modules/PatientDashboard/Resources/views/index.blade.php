@extends('patient.layouts.master')

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="hero_slider_inner">
                        <div>
                            <div class="slider_img">
                                <img src="{{ asset('patient-portal/images/banner_blue-01.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <div>
                            <div class="slider_img">
                                <img src="{{ asset('patient-portal/images/banner-01.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="services">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="services_title">Our Services</h4>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/appoinment') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/doctor.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Appointment</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ route('patient.portal.laboratory') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/lab.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Laboratory Results</h3>
                                </div>

                            </a>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/consulation-notes') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/note.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Consultation Notes</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/prescription') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/prescription.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Prescription</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="javascript:void(0)" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/x-ray.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Radiology and Imaging Results</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/medical-history') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/medical-report.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Medical History</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/immunization') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/syringe.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Immunization Schedule</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/video-conf') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/video-call.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Video Conference</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/payment-history') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/online-payment.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Bills & Payments</h3>
                                </div>

                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/chat') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/doctor_girl.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Chat with a Doctor</h3>
                                </div>

                            </a>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
                            <a href="{{ url('patient-portal/add_document') }}" class="services_box">
                                <div class="icon_wrapper">
                                    <img src="{{ asset('patient-portal/images/add-folder.png') }}" alt="">
                                </div>
                                <div class="title_desp">
                                    <h3>Add Documents</h3>
                                </div>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script src="{{ asset('patient-portal/js/slick.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".hero_slider_inner").slick({
                dots: false,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: true,
            });
        });
    </script>
@endpush
