@extends('patient.layouts.master')

@section('content')

<div class="main-content">
    <div class="main-container">
        <div class="medical-history-block">
            <div class="card">
                <h5 class="card-header">Medical History</h5>
                <div class="card-body">
                    <ul class="medical-timeline">
                        <li class="medical-history">
                            <div class="m-history-content">
                                <h4>You went for Body Checkup</h4>
                                <span class="history-date"><i class="ri-calendar-2-fill"></i> 10 Jan 2021</span>
                                <span class="history-circle"></span>
                            </div>
                            <div class="m-history-block">
                                <p><i class="ri-stethoscope-fill"></i> Dr. Bhuwan Shrestha</p>
                                <a href="#">VIEW MORE</a>
                            </div>
                        </li>
                        <li class="medical-history">
                            <div class="m-history-content">
                                <h4>You went for Vision Checkup</h4>
                                <span class="history-date"><i class="ri-calendar-2-fill"></i> 12 Dec 2020</span>
                                <span class="history-circle"></span>
                            </div>
                            <div class="m-history-block">
                                <p><i class="ri-stethoscope-fill"></i> Dr. Bhuwan Shrestha</p>
                                <a href="#">VIEW MORE</a>
                            </div>
                        </li>
                        <li class="medical-history">
                            <div class="m-history-content">
                                <h4>You went for Dental Checkup</h4>
                                <span class="history-date"><i class="ri-calendar-2-fill"></i> 07 May 2020</span>
                                <span class="history-circle"></span>
                            </div>
                            <div class="m-history-block">
                                <p><i class="ri-stethoscope-fill"></i> Dr. Bhuwan Shrestha</p>
                                <a href="#">VIEW MORE</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection