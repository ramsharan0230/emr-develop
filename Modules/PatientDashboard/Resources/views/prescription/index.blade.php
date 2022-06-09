@extends('patient.layouts.master')

@section('content')
<div class="main-content">
    <div class="main-container">
        <div class="row">
            <div class="col-sm-12">
                <div class="prescription-block">
                    <div class="card">
                        <h5 class="card-header">Order/Prescription</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="true"><i class="ri-capsule-fill"></i> Order</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-prescription-tab" data-toggle="pill" href="#pills-prescription" role="tab" aria-controls="pills-prescription" aria-selected="false"><i class="ri-dossier-line"></i> Prescription</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-order" role="tabpanel" aria-labelledby="pills-order-tab">
                                    <div class="order-n-available">
                                        <img src="{{ asset('patient-portal/images/order-not-available.png') }}" alt="">
                                    </div>
                                    <div class="order-med">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <div class="order-med-block">
                                                    <div class="o-med-img">
                                                        <img src="{{ asset('patient-portal/images/infra.jpg') }}" alt="">
                                                    </div>
                                                    <div class="o-med-name">
                                                        <a href="#">Sahyog Wellness Multi Function Non-Contact Body & Object Infrared</a>
                                                    </div>
                                                    <div class="o-med-price">
                                                        <p>Rs. 1,889.79</p>
                                                    </div>
                                                    <div class="o-med-buy">
                                                        <button type="button">BUY NOW</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-prescription" role="tabpanel" aria-labelledby="pills-prescription-tab">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="valid-prescription">
                                                <h3>Valid Prescription Guide</h3>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                        <ul>
                                                            <li>Don't crop out any part of the image.</li>
                                                            <li>Avoid blurred image.</li>
                                                            <li>Include details of doctors and patients + clinic visit date.</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                        <ul>
                                                            <li>Medicine will be dispensed as per prescription.</li>
                                                            <li>Supported file type: jpeg,jpg,png.</li>
                                                            <li>Maximum allowed file size: 5MB.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="prescription-order">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                        <div class="prescription-upload">
                                                            <input type="file" name="" id="" multiple="multiple" required="required">
                                                            <div class="file-dummy">
                                                                <div class="success">Great, your files are selected.</div>
                                                                <div class="default">Drop prescription to upload or <span>Browse</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                        <div class="prescription-upload-block">
                                                            <h3>Are you ordering for your self?</h3>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" id="order-yes">
                                                                <label class="form-check-label" for="order-yes">Yes</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" id="order-no">
                                                                <label class="form-check-label" for="order-no">No</label>
                                                            </div>
                                                            <div class="form-group mb-0">
                                                                <textarea name="" id="" placeholder="Message" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="prescription-order-info">
                                                            <h3>Order Info</h3>
                                                            <p>Select how do you want to proceed with the order.</p>
                                                            <div class="form-row align-items-center">
                                                                <div class="col-auto my-1">
                                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                                        <input type="checkbox" class="custom-control-input" id="add-manually">
                                                                        <label class="custom-control-label" for="add-manually">Manually search and add medicines in the card.</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto my-1">
                                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                                        <input type="checkbox" class="custom-control-input" id="get-call">
                                                                        <label class="custom-control-label" for="get-call">Manually search and add medicines in the card.</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="prescription-btn">
                                                            <button type="submit" class="btn-submit">SUBMIT</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="prescription-work">
                                                            <h3>How it works</h3>
                                                            <div class="row">
                                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                                    <div class="prescription-work-content">
                                                                        <div class="p-work-img">
                                                                            <img src="{{ asset('patient-portal/images/file.png') }}" alt="">
                                                                        </div>
                                                                        <h4>Step 1</h4>
                                                                        <p>Click to upload image of prescription.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                                    <div class="prescription-work-content">
                                                                        <div class="p-work-img">
                                                                            <img src="{{ asset('patient-portal/images/payment.png') }}" alt="">
                                                                        </div>
                                                                        <h4>Step 2</h4>
                                                                        <p>Request for the quotation.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                                    <div class="prescription-work-content">
                                                                        <div class="p-work-img">
                                                                            <img src="{{ asset('patient-portal/images/notification.png') }}" alt="">
                                                                        </div>
                                                                        <h4>Step 3</h4>
                                                                        <p>You will receive a notification to confirm billing.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                                    <div class="prescription-work-content">
                                                                        <div class="p-work-img">
                                                                            <img src="{{ asset('patient-portal/images/shopping-cart.png') }}" alt="">
                                                                        </div>
                                                                        <h4>Step 4</h4>
                                                                        <p>Confirm orders and billing in "Orders" section.</p>
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
        </div>
    </div>
</div>
@endsection