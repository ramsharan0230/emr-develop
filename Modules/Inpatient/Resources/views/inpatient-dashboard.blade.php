@extends('frontend.layouts.master')
@section('content')

<style>
    :root {
        --vacant: #33cb26;
        --discharge: #6FA8FF;
        --notinservice: #c5c5c5;
        --occupied: #FF6F6F;
        --reserved: #FFCE6F;
        --vacant-op: #33cb262f;
        --discharge-op: #6FA8FF2f;
        --notinservice-op: #c5c5c52f;
        --occupied-op: #FF6F6F2f;
        --reserved-op: #FFCE6F2f;
        --black-c: #333333;
        --blue-c: #DEEFFE;
        --grey-c: #EBEBEB;
        --green-c: #00BF91;
        --green-op: #00BF912f;

    }
    /* Colors  */
    .vacant-c {
        background: var(--vacant);
    }
    .discharge-c {
        background: var(--discharge);
    }
    .notinservice-c {
        background: var(--notinservice);
    }
    .occupied-c {
        background: var(--occupied);
    }
    .reserved-c {
        background: var(--reserved);
    }
    .black-c {
        color: var(--black-c);
    }


    hr {
        margin: 0;
    }

    /* Fonts  */
    .small-font {
        font-size: 12px;
    }
    .normal {
        font-size: 13px;
    }
    .bold-text {
        font-weight: 500;
    }
    .large-font {
        font-size: 16px;
    }

    /* Sections  */
    .ipd-info {
        justify-content: space-between;
        padding: 0 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .total-bed {
        padding: 10px; 
        background: var(--blue-c);
        border-radius: 8px; 
        width: 115px;
        height: 80px;   
    }
    

    /* Progress Bar  */
    .percent {
        padding: 10px 30px;
    } 

    .bar {
        width: 180px;
        height: 5px;
        background: var(--grey-c);
    }

    .bar-chart {
        width: 100%;
        height: 5px;
        background: var(--grey-c);
    }
    .progress {
        width: 50%;
        height: 5px;
    }
    
    .progress-box {
        padding: 5px 20px;
    }

    .first-bar, .second-bar  {
        height: 5px;
    }

    /* Color Swatches  */
    .color-batch {
        display: flex;
        flex-direction: column;
    }
    .color-patch {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        margin-right: 5px;
    }
    .color-sq {
        padding: 2px;
    }


    /* Wards */
    .ward-card {
        padding: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .ward-card:hover {
        border-radius: 10px;
        background: var(--blue-c);
        cursor: pointer;
    }
    .ward-card:hover .w-icon {
        background: var(--green-c);
    }
    .ward-card:hover .w-icon i {
        color: white;
    }

    .ward-name {
        align-items: center;
        width: 30%;
    }

    .incharge, .total-beds {
        width: 20%
    }

    .bar-chart {
        width: 100%;
    }

    .align-col {
        display: flex;
        flex-direction: column;    
    }

    .align-row {
        display: flex;
        flex-direction: row;  
    }

    .w-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        min-width: 35px;
        height: 35px;
        border-radius: 50px;
        margin-right: 5px;
        background: var(--green-op);
    }
    .w-icon i {
        color: var(--green-c);
    }

    .progress-box-ward {
        padding: 5px 20px;
        width: 30%;
    }
    
    /* Modal  */
    .bed-card {
        cursor: pointer;
        margin-bottom: 20px;
    }
    .bed-card:hover {
        corser: pointer;
    }

    .bed-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 15px;
        flex-wrap: wrap;
        border-radius: 5px;
        height: 70px;
        width: 90px;
        padding: 10px;
        margin-bottom: 3px;
    }
    .bed-icon i {
        font-size: 27px;
    }


    /* Modal Bed Background Colors */
        .v-bed-icon {
            background: var(--vacant-op);
        }
        .o-bed-icon {
            background: var(--occupied-op);
        }
        .d-bed-icon {
            background: var(--discharge-op);
        }
        .r-bed-icon {
            background: var(--reserved-op);
        }
        .n-bed-icon {
            background: var(--notinservice-op);
        }

        /* Modal Bed Icon Colors   */
            .v-bed-icon i {
                color: var(--vacant);
                opacity: 1;
            }
            .o-bed-icon i {
                color: var(--occupied);
            }
            .d-bed-icon i {
                color: var(--discharge);
            }
            .r-bed-icon i {
                color: var(--reserved);
            }
            .n-bed-icon i {
                color: var(--notinservice);
            }



    /* Bed Details ToolTip  */

    .bed-details {
        display: none;
    }
    .bed-card {
        position: relative;
    }
    /* .bed-card:hover .bed-details {
        position: absolute;
        padding: 5px;
        display: block;
        background: white;
        left: -40px;
        top: -70px;
        min-width: 200px;
        font-size: 13px;
        z-index: 1;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.1);
    }  */
    

    
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h3 class="card-title">
                                    Inpatient Department (IPD)
                                </h3>
                            </div>
                        </div>


                        <!-- IPD Total Details header  -->
                        <div class="iq-card-body">
                            <div class="align-row ipd-info">
                                <div class="align-row align-items-center flex-wrap">
                                    <div class="total-bed">
                                        <div class="align-col align-items-center">
                                            <label for="">Total Beds</label>
                                            <span style="font-size: 32px; font-weight: 600; margin-top: -10px;">20</span>
                                        </div>
                                    </div>
                                    <div class="percent">
                                        <div class="row">
                                            <div class="progress-box">
                                                <div classs="align-col">
                                                    <div class="align-row justify-content-between">
                                                        <span class="normal">Vacant</span>
                                                        <span class="normal">10</span>
                                                    </div>
                                                    <div class="bar">
                                                        <div class="progress vacant-c"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        <span class="small-font">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-box">
                                                <div classs="align-col">
                                                    <div class="align-row justify-content-between">
                                                        <span class="normal">Discharge Ready</span>
                                                        <span class="normal">10</span>
                                                    </div>
                                                    <div class="bar">
                                                        <div class="progress discharge-c"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        <span class=small-fontl">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-box">
                                                <div classs="align-col">
                                                    <div class="align-row justify-content-between">
                                                        <span class="normal">Not in Service</span>
                                                        <span class="normal">10</span>
                                                    </div>
                                                    <div class="bar">
                                                        <div class="progress notinservice-c"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        <span class="small-font">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="progress-box">
                                                <div classs="align-col">
                                                    <div class="align-row justify-content-between">
                                                        <span class="normal">Occupied</span>
                                                        <span class="normal">10</span>
                                                    </div>
                                                    <div class="bar">
                                                        <div class="progress occupied-c"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        <span class="small-font">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-box">
                                                <div classs="align-col">
                                                    <div class="align-row justify-content-between">
                                                        <span class="normal">Reserved</span>
                                                        <span class="normal">10</span>
                                                    </div>
                                                    <div class="bar">
                                                        <div class="progress reserved-c"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        <span class="small-font">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Color Swatches -->
                                <div class="color-batch">
                                    <div class="color-sq">
                                        <div class="align-row align-items-center">
                                            <div class="color-patch vacant-c"></div>
                                            <span class="small-font">Vacant Beds</span>
                                        </div>
                                    </div>
                                    <div class="color-sq">
                                        <div class="align-row align-items-center">
                                            <div class="color-patch occupied-c"></div>
                                            <span  class="small-font">Occupied Beds</span>
                                        </div>
                                    </div>
                                    <div class="color-sq">
                                        <div class="align-row align-items-center">
                                            <div class="color-patch discharge-c"></div>
                                            <span  class="small-font">Discharge Ready Beds</span>
                                        </div>
                                    </div>
                                    <div class="color-sq">
                                        <div class="align-row align-items-center">
                                            <div class="color-patch reserved-c"></div>
                                            <span  class="small-font">Reserved Beds</span>
                                        </div>
                                    </div>
                                    <div class="color-sq">
                                        <div class="align-row align-items-center">
                                            <div class="color-patch notinservice-c"></div>
                                            <span  class="small-font">Not in Service Beds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h3 class="card-title">
                                    Wards
                                </h3>
                            </div>
                        </div>
                        <div class="iq-card-body">

                        <!-- Ward Cards  -->
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Medical Ward</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Mr. Rachana Hamal</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">90</span>
                                            <span class="large-font black-c">10</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 90%;"></div>
                                                <div class="second-bar occupied-c" style="width: 10%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">90%</span>
                                            <span class="small-font">10%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Holding Zone / Holding Outside</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Dr. Bisnu prasad Sukupaya</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">40</span>
                                            <span class="large-font black-c">60</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 40%;"></div>
                                                <div class="second-bar occupied-c" style="width: 60%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">40%</span>
                                            <span class="small-font">60%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Covid Ward / Covid Internal : HDU Moderate</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Mr. Rachana Hamal</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">80</span>
                                            <span class="large-font black-c">20</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 80%;"></div>
                                                <div class="second-bar occupied-c" style="width: 20%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">80%</span>
                                            <span class="small-font">20%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Medical Ward</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Mr. Rachana Hamal</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">90</span>
                                            <span class="large-font black-c">10</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 90%;"></div>
                                                <div class="second-bar occupied-c" style="width: 10%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">90%</span>
                                            <span class="small-font">10%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Holding Zone / Holding Outside</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Dr. Bisnu prasad Sukupaya</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">40</span>
                                            <span class="large-font black-c">60</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 40%;"></div>
                                                <div class="second-bar occupied-c" style="width: 60%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">40%</span>
                                            <span class="small-font">60%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="align-row ward-card" data-toggle="modal" data-target="#ward-details-modal">
                                <div class="align-row ward-name">
                                    <div class="w-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text">Covid Ward / Covid Internal : HDU Moderate</span>
                                </div>
                                <div class="incharge align-col justify-content-start">
                                    <span class="small-font">Dept. Incharge</span>
                                    <span class="normal black-c" style="margin-top: -5px;">Mr. Rachana Hamal</span>
                                </div>
                                <div class="total-beds">
                                    <div class="align-col align-items-center">
                                        <span class="small-font">Total Beds</span>
                                        <span style="font-size: 24px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="progress-box-ward">
                                    <div classs="align-col">
                                        <div class="align-row justify-content-between">
                                            <span class="large-font black-c">80</span>
                                            <span class="large-font black-c">20</span>
                                        </div>
                                        <div class="bar-chart">
                                            <div class="align-row justify-content-between">
                                                <div class="first-bar vacant-c" style="width: 80%;"></div>
                                                <div class="second-bar occupied-c" style="width: 20%;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small-font">80%</span>
                                            <span class="small-font">20%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Wards Modal  -->
    <div class="modal fade bd-example-modal-lg" id="ward-details-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Medical Ward (6th Floor)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                <!-- Ward Modal Header -->
                    <div class="iq-card-body p-0">
                        <div class="align-row ipd-info">
                            <div class="align-row align-items-center flex-wrap">
                                <div class="total-bed">
                                    <div class="align-col align-items-center">
                                        <label for="">Total Beds</label>
                                        <span style="font-size: 32px; font-weight: 600; margin-top: -10px;">20</span>
                                    </div>
                                </div>
                                <div class="percent">
                                    <div class="row">
                                        <div class="progress-box">
                                            <div classs="align-col">
                                                <div class="align-row justify-content-between">
                                                    <span class="normal">Vacant</span>
                                                    <span class="normal">10</span>
                                                </div>
                                                <div class="bar">
                                                    <div class="progress vacant-c"></div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <span class="small-font">20%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-box">
                                            <div classs="align-col">
                                                <div class="align-row justify-content-between">
                                                    <span class="normal">Discharge Ready</span>
                                                    <span class="normal">10</span>
                                                </div>
                                                <div class="bar">
                                                    <div class="progress discharge-c"></div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <span class=small-fontl">20%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-box">
                                            <div classs="align-col">
                                                <div class="align-row justify-content-between">
                                                    <span class="normal">Not in Service</span>
                                                    <span class="normal">10</span>
                                                </div>
                                                <div class="bar">
                                                    <div class="progress notinservice-c"></div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <span class="small-font">20%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="progress-box">
                                            <div classs="align-col">
                                                <div class="align-row justify-content-between">
                                                    <span class="normal">Occupied</span>
                                                    <span class="normal">10</span>
                                                </div>
                                                <div class="bar">
                                                    <div class="progress occupied-c"></div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <span class="small-font">20%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress-box">
                                            <div classs="align-col">
                                                <div class="align-row justify-content-between">
                                                    <span class="normal">Reserved</span>
                                                    <span class="normal">10</span>
                                                </div>
                                                <div class="bar">
                                                    <div class="progress reserved-c"></div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <span class="small-font">20%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="color-batch">
                                <div class="color-sq">
                                    <div class="align-row align-items-center">
                                        <div class="color-patch vacant-c"></div>
                                        <span class="small-font">Vacant Beds</span>
                                    </div>
                                </div>
                                <div class="color-sq">
                                    <div class="align-row align-items-center">
                                        <div class="color-patch occupied-c"></div>
                                        <span  class="small-font">Occupied Beds</span>
                                    </div>
                                </div>
                                <div class="color-sq">
                                    <div class="align-row align-items-center">
                                        <div class="color-patch discharge-c"></div>
                                        <span  class="small-font">Discharge Ready Beds</span>
                                    </div>
                                </div>
                                <div class="color-sq">
                                    <div class="align-row align-items-center">
                                        <div class="color-patch reserved-c"></div>
                                        <span  class="small-font">Reserved Beds</span>
                                    </div>
                                </div>
                                <div class="color-sq">
                                    <div class="align-row align-items-center">
                                        <div class="color-patch notinservice-c"></div>
                                        <span  class="small-font">Not in Service Beds</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>


                    <!-- Ward Modal Beds  -->
                    <div class="iq-card-body">
                        <h5 class="mb-2">Beds</h5>
                        <div class="align-row flex-wrap">
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon v-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 123</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon v-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 123</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon v-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 123</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon v-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 123</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon v-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 123</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                                <div class="bed-details">
                                    <span>test</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon o-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 124</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon d-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 126</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon r-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 126</span>
                                </div>
                            </div>
                            <div class="bed-card" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" data-content="Name: Bivek bashyal.<br>Date: 2025-02-09<br>Phone: 9841XXXXX<br>Doctor: Dr. Rachana Hamal">
                                <div class="align-col align-items-center">
                                    <div class="bed-icon n-bed-icon">
                                        <i class="fa fa-bed"></i>
                                    </div>
                                    <span class="normal bold-text black-c">MD - 126</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                </div> -->
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        $('#example').tooltip(options)
    </script>
@endpush
