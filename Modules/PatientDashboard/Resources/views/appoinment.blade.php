@extends('patient.layouts.master')
@section('content')
<div class="main-container-new">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="topspce">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mainContainer">
                                <div class="appointment_wrapper">
                                    <!-- Nav pills -->
                                    <ul class="nav nav-pills custom_tab  nav-tabs nav-tabs-bottom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="pill" href="#home">New Booking
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill" href="#menu1">Appointment</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill" href="#menu2">History</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane active">
                                            <br>
                                            <!-- Nav pills -->
                                            <ul class="nav nav-pills subtabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="pill" href="#hospital">
                                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="94.505px" height="94.505px" viewBox="0 0 94.505 94.505" style="enable-background:new 0 0 94.505 94.505;" xml:space="preserve">
                                                            <g>
                                                                <path d="M89.217,72.686c0.111-0.332,0.174-0.688,0.174-1.058V32.771c0-1.842-1.494-3.335-3.336-3.335H67.488v41.359
                                                                c0,0.664-0.12,1.299-0.332,1.891h-2.178c0.111-0.332,0.174-0.688,0.174-1.058V18.261c0-1.842-1.494-3.335-3.336-3.335H32.687
                                                                c-1.842,0-3.335,1.493-3.335,3.335v53.367c0,0.37,0.063,0.726,0.174,1.058h-2.177c-0.212-0.592-0.332-1.227-0.332-1.891V29.436
                                                                H8.895c-1.842,0-3.335,1.493-3.335,3.335v38.857c0,0.37,0.063,0.726,0.173,1.058H0v6.894h94.505v-6.894H89.217L89.217,72.686z
                                                                M15.565,59.064h-4.781v-4.78h4.781V59.064z M15.565,50.671h-4.781V45.89h4.781V50.671z M15.565,42.555h-4.781v-4.781h4.781V42.555
                                                                z M23.015,59.064h-4.781v-4.78h4.781V59.064z M23.015,50.671h-4.781V45.89h4.781V50.671z M23.015,42.555h-4.781v-4.781h4.781
                                                                V42.555z M54.674,72.463H40.637V54.951h14.037V72.463z M47.252,39.941c-6.493,0-11.757-5.264-11.757-11.757
                                                                c0-6.494,5.265-11.758,11.757-11.758c6.494,0,11.758,5.264,11.758,11.758C59.01,34.678,53.746,39.941,47.252,39.941z
                                                                M77.605,59.064h-4.78v-4.78h4.78V59.064z M77.605,50.671h-4.78V45.89h4.78V50.671z M77.605,42.555h-4.78v-4.781h4.78V42.555z
                                                                M85.054,59.064h-4.78v-4.78h4.78V59.064z M85.054,50.671h-4.78V45.89h4.78V50.671z M85.054,42.555h-4.78v-4.781h4.78V42.555z
                                                                M50.17,25.266h4.503v5.836H50.17v4.505h-5.836v-4.505h-4.503v-5.836h4.503v-4.503h5.836V25.266z" />
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                            <g>
                                                            </g>
                                                        </svg>
                                                        <br>
                                                        <label>Hospital</label>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="pill" href="#telemedicine">
                                                        <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="XMLID_125_">
                                                                <path id="XMLID_1827_" d="m310.374 15.062h-108.748c-28.26 0-51.251 22.991-51.251 51.251v9.124h30v-9.124c0-11.718 9.533-21.251 21.251-21.251h108.748c11.718 0 21.251 9.533 21.251 21.251v9.124h30v-9.124c0-28.259-22.991-51.251-51.251-51.251z" />
                                                                <path id="XMLID_1835_" d="m271.125 271.062v-45.25h-30.25v45.25c0 8.284-6.716 15-15 15h-45.25v30.25h45.25c8.284 0 15 6.716 15 15v45.25h30.25v-45.25c0-8.284 6.716-15 15-15h45.25v-30.25h-45.25c-8.284 0-15-6.715-15-15z" />
                                                                <path id="XMLID_1836_" d="m0 181.687v239.002c0 36.91 26.365 67.771 61.249 74.759v-388.521c-34.884 6.988-61.249 37.849-61.249 74.76z" />
                                                                <path id="XMLID_1837_" d="m450.751 106.927v388.521c34.884-6.988 61.249-37.849 61.249-74.76v-239.001c0-36.911-26.365-67.772-61.249-74.76z" />
                                                                <path id="XMLID_1840_" d="m420.751 105.438h-329.502v391.5h329.502zm-59.376 225.874c0 8.284-6.716 15-15 15h-45.25v45.25c0 8.284-6.716 15-15 15h-60.25c-8.284 0-15-6.716-15-15v-45.25h-45.25c-8.284 0-15-6.716-15-15v-60.25c0-8.284 6.716-15 15-15h45.25v-45.25c0-8.284 6.716-15 15-15h60.25c8.284 0 15 6.716 15 15v45.25h45.25c8.284 0 15 6.716 15 15z" />
                                                            </g>
                                                        </svg>
                                                        <br>
                                                        <label>Telemedicine</label>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div id="hospital" class="tab-pane active">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div id="Hospital" class="tabcontent">
                                                                <div class="hospital_details">
                                                                    <div class="hospital_wrapper">
                                                                        <div class="wrapper_l1">
                                                                            <div class="img_holder">
                                                                                <img class="profile-pic" src="images/nidaan.jpg" alt="User Avatar" width="110">
                                                                            </div>
                                                                            <div class="right_hosp_detais">
                                                                                <h4 class="hosp-name">Nidhan Hospital Pvt.
                                                                                    Ltd
                                                                                </h4>
                                                                                <div class="hide_mb">
                                                                                    <span class="contact_hospital"><i class="ri-map-pin-user-line"></i>Pulchok,
                                                                                        Lalitpur</span>
                                                                                    <span class="contact_hospital"><i class="ri-phone-line"></i> 01-5531333,
                                                                                        01-5531322, 01-5531311</span>
                                                                                </div>

                                                                                <div class="pt-top">
                                                                                    </h4>
                                                                                    <!-- <span class="contact_hospital   in-details hide_details"><i class="ri-map-pin-user-line"></i>Pulchok,
                                                                                        Lalitpur</span>
                                                                                    <span class="contact_hospital in-details hide_details"><i class="ri-phone-line"></i> 01-5531333,
                                                                                        01-5531322, 01-5531311</span> -->
                                                                                    <span class="in-details hide_details">Lorem ipsum dolor
                                                                                        sit amet
                                                                                        consectetur adipisicing elit. Odio
                                                                                        eaque.</span>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="show_md pt-top" style="display: none;">
                                                                            <span class="contact_hospital"><i class="ri-map-pin-user-line"></i>Pulchok,
                                                                                Lalitpur</span>
                                                                            <span class="contact_hospital"><i class="ri-phone-line"></i> 01-5531333,
                                                                                01-5531322, 01-5531311</span>
                                                                        </div>
                                                                        <div class="pt-top">

                                                                            <span class="in-details mobile_details" style="display: none;">Lorem ipsum dolor
                                                                                sit amet
                                                                                consectetur adipisicing elit. Odio
                                                                                eaque. </span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">

                                                            <div class="listof_docs">
                                                                <div class="doctor_list_wrapper">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="iq-card">
                                                                                <div class="iq-card-header d-flex justify-content-between">
                                                                                    <div class="iq-header-title">
                                                                                        <div class="row vcenter title_sec_card">
                                                                                            <div>
                                                                                                <h4 class="card-title">Doctors List</h4>
                                                                                            </div>
                                                                                            <div>
                                                                                                <div class="input-group justifyrt">
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                                                                        <label class="form-check-label" for="inlineRadio1">Doctors</label>
                                                                                                    </div>
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                                                                        <label class="form-check-label" for="inlineRadio2">Department</label>
                                                                                                    </div>
                                                                                                    <!-- <input type="text" class="form-control" placeholder="Search for doctor/department" aria-label="Example text with two button addons" aria-describedby="button-addon3"> -->
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-3">
                                                                            <div class="iq-card">
                                                                                <div class="iq-card-body    ">
                                                                                    <div class="doc-profile">
                                                                                        <img class="rounded-circle img-fluid avatar-80" src="images/sanjeet.jpg" alt="profile">
                                                                                    </div>
                                                                                    <div class="iq-doc-info mt-3">
                                                                                        <h4> Dr. Sanjeet Shrestha</h4>
                                                                                        <p class="mb-0">Pulmonologist </p>
                                                                                        <p class="mb-0 doc_charge">Rs.500 </p>

                                                                                    </div>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-3">
                                                                            <div class="iq-card">
                                                                                <div class="iq-card-body    ">
                                                                                    <div class="doc-profile">
                                                                                        <img class="rounded-circle img-fluid avatar-80" src="images/supatra.jpg" alt="profile">
                                                                                    </div>
                                                                                    <div class="iq-doc-info mt-3">
                                                                                        <h4> Dr. Suphatra Koirala </h4>
                                                                                        <p class="mb-0">Obstetrics Gynecology</p>
                                                                                        <p class="mb-0 doc_charge">Rs.500 </p>
                                                                                    </div>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-3">
                                                                            <div class="iq-card">
                                                                                <div class="iq-card-body    ">
                                                                                    <div class="doc-profile">
                                                                                        <img class="rounded-circle img-fluid avatar-80" src="images/doctor-image.jpg" alt="profile">
                                                                                    </div>
                                                                                    <div class="iq-doc-info mt-3">
                                                                                        <h4> Dr. Terry Aki</h4>
                                                                                        <p class="mb-0">Medicine Specialists</p>
                                                                                        <p class="mb-0 doc_charge">Rs.500 </p>
                                                                                    </div>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-3">
                                                                            <div class="iq-card">
                                                                                <div class="iq-card-body    ">
                                                                                    <div class="doc-profile">
                                                                                        <img class="rounded-circle img-fluid avatar-80" src="images/sanjeet.jpg" alt="profile">
                                                                                    </div>
                                                                                    <div class="iq-doc-info mt-3">
                                                                                        <h4> Dr. Poppa Cherry</h4>
                                                                                        <p class="mb-0">Family Physicians</p>
                                                                                        <p class="mb-0 doc_charge">Rs.500 </p>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="doctor_department">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4  btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/acupuncture.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Accupunture</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/syringe.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Anaesthesiology</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/ayurveda.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Ayurveda</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/cardiology.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Cardiology</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/heart.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Cardiothoracic</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/botox.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Cosmetic Surgeon</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/tooth.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Dental</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/dentist.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Dental Surgeon</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4  btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/depilation.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Dermatology</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4  btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/head.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Ear, Nose &amp; Throat</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/medical-kit.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Emergency Medicine</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/kidneys.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Endocrinology</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 btRightBorder spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/drugs.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Family Medicine</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-4 spacetop">
                                                                            <a href="javascript:void(0)" class="depart_box">
                                                                                <div class="depart_icon_wrapper">
                                                                                    <img src="images/intestine.png" alt="">
                                                                                </div>
                                                                                <div class="title_desp underline_title">
                                                                                    <h3>Gastroenterology</h3>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="telemedicine" class="tab-pane fade">
                                                    <br>
                                                    <p>Two</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="menu1" class="tab-pane fade">
                                            <div class="topspce">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>
                                                            <div class="table-responsive">
                                                                <table class="table mb-0 table_styles">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th scope="col" class="border-0">Photo</th>
                                                                            <th scope="col" class="border-0">Name</th>
                                                                            <th scope="col" class="border-0">Email </th>
                                                                            <th scope="col" class="border-0">Date</th>
                                                                            <th scope="col" class="border-0">Visited Time</th>
                                                                            <th scope="col" class="border-0">Number</th>
                                                                            <th scope="col" class="border-0">Doctor</th>
                                                                            <th scope="col" class="border-0">Injury/Condition</th>
                                                                            <th scope="col" class="border-0">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <tr>
                                                                            <td>
                                                                                <span class="ant-avatar ant-avatar-circle ant-avatar-image">
                                                                                    <img src="images/face.jpg">
                                                                                </span>
                                                                            </td>
                                                                            <td>Liam Hemsworth</td>
                                                                            <td>liam123@gmail.com</td>
                                                                            <td>18 Dec 2020</td>
                                                                            <td>10:15 - 10:30</td>
                                                                            <td>9841523657</td>
                                                                            <td>Dr.Sanjeet</td>
                                                                            <td>Chest Infection</td>
                                                                            <td>
                                                                                <a href="#" class="pdf_btn mr-1" data-toggle="modal" data-target="#exampleModal1">
                                                                                    <i class="ri-edit-box-line"></i>
                                                                                </a>
                                                                                <a href="#" class="pdf_btn">
                                                                                    <i class="ri-delete-bin-5-line"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>
                                                                                <span class="ant-avatar ant-avatar-circle ant-avatar-image">
                                                                                    <img src="images/face.jpg">
                                                                                </span>
                                                                            </td>
                                                                            <td>Liam Hemsworth</td>
                                                                            <td>liam123@gmail.com</td>
                                                                            <td>18 Dec 2020</td>
                                                                            <td>10:15 - 10:30</td>
                                                                            <td>9841523657</td>
                                                                            <td>Dr.Sanjeet</td>
                                                                            <td>Chest Infection</td>
                                                                            <td>
                                                                                <a href="#" class="pdf_btn mr-1" data-toggle="modal" data-target="#exampleModal1">
                                                                                    <i class="ri-edit-box-line"></i>
                                                                                </a>
                                                                                <a href="#" class="pdf_btn">
                                                                                    <i class="ri-delete-bin-5-line"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a type="button" class="ant-btn page-action ant-btn-primary ant-btn-circle ant-btn-lg" data-toggle="modal" data-target="#exampleModal">
                                                <i class="ri-stethoscope-fill"></i>
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Appointment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="appointment_form">
                                                                <div class="form-group"><input type="file" style="display: none;">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="ant-avatar mr-4 ant-avatar-circle ant-avatar-image" style="width: 40px; height: 40px; line-height: 40px; font-size: 18px;">
                                                                            <img src="images/face.jpg">
                                                                        </span>
                                                                        <a href="#" class="seelect_img">
                                                                            <span>Select image </span>
                                                                            <i class="ri-user-fill"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Name</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Doctor</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Email</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Date</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Number</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Injury</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary add_btn">Add Appointment</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--Edit Modal -->
                                            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">Edit Appointment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="appointment_form">
                                                                <div class="form-group"><input type="file" style="display: none;">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="ant-avatar mr-4 ant-avatar-circle ant-avatar-image" style="width: 40px; height: 40px; line-height: 40px; font-size: 18px;">
                                                                            <img src="images/face.jpg">
                                                                        </span>
                                                                        <a href="#" class="seelect_img">
                                                                            <span>Select image </span>
                                                                            <i class="ri-user-fill"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Name</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Doctor</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Email</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Date</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Number</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Injury</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary add_btn">Update Appointment</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="menu2" class="tab-pane fade">
                                            <div class="topspce">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>
                                                            <div class="table-responsive">
                                                                <table class="table mb-0 table_styles">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th scope="col" class="border-0">Photo</th>
                                                                            <th scope="col" class="border-0">Name</th>
                                                                            <th scope="col" class="border-0">Email </th>
                                                                            <th scope="col" class="border-0">Date</th>
                                                                            <th scope="col" class="border-0">Visited Time</th>
                                                                            <th scope="col" class="border-0">Number</th>
                                                                            <th scope="col" class="border-0">Doctor</th>
                                                                            <th scope="col" class="border-0">Injury/Condition</th>
                                                                            <th scope="col" class="border-0">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <tr>
                                                                            <td>
                                                                                <span class="ant-avatar ant-avatar-circle ant-avatar-image">
                                                                                    <img src="images/face.jpg">
                                                                                </span>
                                                                            </td>
                                                                            <td>Liam Hemsworth</td>
                                                                            <td>liam123@gmail.com</td>
                                                                            <td>18 Dec 2020</td>
                                                                            <td>10:15 - 10:30</td>
                                                                            <td>9841523657</td>
                                                                            <td>Dr.Sanjeet</td>
                                                                            <td>Chest Infection</td>
                                                                            <td>
                                                                                <a href="#" class="pdf_btn mr-1" data-toggle="modal" data-target="#exampleModal2">
                                                                                    <!-- <i class="ri-eye-line"></i> -->
                                                                                    <i class="ri-edit-box-line"></i>
                                                                                </a>
                                                                                <a href="#" class="pdf_btn">
                                                                                    <i class="ri-delete-bin-5-line"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>
                                                                                <span class="ant-avatar ant-avatar-circle ant-avatar-image">
                                                                                    <img src="images/face.jpg">
                                                                                </span>
                                                                            </td>
                                                                            <td>Liam Hemsworth</td>
                                                                            <td>liam123@gmail.com</td>
                                                                            <td>18 Dec 2020</td>
                                                                            <td>10:15 - 10:30</td>
                                                                            <td>9841523657</td>
                                                                            <td>Dr.Sanjeet</td>
                                                                            <td>Chest Infection</td>
                                                                            <td>
                                                                                <a href="#" class="pdf_btn mr-1" data-toggle="modal" data-target="#exampleModal2">
                                                                                    <!-- <i class="ri-eye-line"></i> -->
                                                                                    <i class="ri-edit-box-line"></i>
                                                                                </a>
                                                                                <a href="#" class="pdf_btn">
                                                                                    <i class="ri-delete-bin-5-line"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabe2" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabe2">Edit History</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="appointment_form">
                                                                <div class="form-group"><input type="file" style="display: none;">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="ant-avatar mr-4 ant-avatar-circle ant-avatar-image" style="width: 40px; height: 40px; line-height: 40px; font-size: 18px;">
                                                                            <img src="images/face.jpg">
                                                                        </span>
                                                                        <a href="#" class="seelect_img">
                                                                            <span>Select image </span>
                                                                            <i class="ri-user-fill"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Name</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Doctor</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Email</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Date</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Number</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Injury</label>
                                                                            <input type="" class="form-control" id="">
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary add_btn">Update History</button>
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
    </div>
</div>

@endsection
@push('after-script')
@endpush