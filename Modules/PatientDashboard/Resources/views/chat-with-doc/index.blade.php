@extends('patient.layouts.master')

@section('content')

<div class="main-content">
    <div class="patient-chat">
        <div class="row h-100">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 h-100 pr-0">
                <div class="message-left h-100">
                    <h3>Message</h3>
                    <div class="new-message">
                        <button data-toggle="modal" data-target="#newMessage"><i class="ri-message-line"></i> Write message</button>
                    </div>
                    <div class="modal message-modal fade" id="newMessage" tabindex="-1" role="dialog" aria-labelledby="newMessageLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Conversation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row align-items-center">
                                        <label for="" class="col-1 mb-0">To:</label>
                                        <div class="col-11">
                                            <input type="text" class="form-control" placeholder="Type a name of Doctor">
                                        </div>
                                    </div>
                                    <div class="message-doc-list">
                                        <div class="message-doc">
                                            <div class="message-doc-img">
                                                <img src="{{ asset('patient-portal/images/sanjeet.jpg') }}" alt="">
                                            </div>
                                            <div class="message-doc-block">
                                                <p>Dr. Bhuwan Shrestha</p>
                                                <span>Internal Medicine</span>
                                            </div>
                                        </div>
                                        <div class="message-doc">
                                            <div class="message-doc-img">
                                                <img src="{{ asset('patient-portal/images/sanjeet.jpg') }}" alt="">
                                            </div>
                                            <div class="message-doc-block">
                                                <p>Dr. Bhuwan Shrestha</p>
                                                <span>Internal Medicine</span>
                                            </div>
                                        </div>
                                        <div class="message-doc">
                                            <div class="message-doc-img">
                                                <img src="{{ asset('patient-portal/images/sanjeet.jpg') }}" alt="">
                                            </div>
                                            <div class="message-doc-block">
                                                <p>Dr. Bhuwan Shrestha</p>
                                                <span>Internal Medicine</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav flex-column nav-pills" >
                        <a class="nav-link active" id="message-1">
                            <div class="message-user">
                                <div class="message-user-img">
                                    <img src="{{ asset('patient-portal/images/sanjeet.jpg') }}" alt="">
                                </div>
                                <div class="message-content">
                                    <div class="message-user-detail">
                                        <p>Bhuwan Shrestha</p>
                                        <span>2 hours ago</span>
                                    </div>
                                    <div class="message-main-content">
                                        <p>Good Morning! How are...</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="nav-link" id="message-2">
                            <div class="message-user">
                                <div class="message-user-img">
                                    <img src="{{ asset('patient-portal/images/supatra.jpg') }}" alt="">
                                </div>
                                <div class="message-content">
                                    <div class="message-user-detail">
                                        <p>Name Surname</p>
                                        <span>Yesterday</span>
                                    </div>
                                    <div class="message-main-content unread">
                                        <p>Good Morning! How are...</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="nav-link" id="message-3">
                            <div class="message-user">
                                <div class="message-user-img">
                                    <img src="{{ asset('patient-portal/images/supatra.jpg') }}" alt="">
                                </div>
                                <div class="message-content">
                                    <div class="message-user-detail">
                                        <p>Name Surname</p>
                                        <span>1/27/2021</span>
                                    </div>
                                    <div class="message-main-content unread">
                                        <p>Good Morning! How are...</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 pl-0">
                <div class="message-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="message-1">
                            <div class="main-message-block">
                                <div class="message-block">
                                    <div class="message-conversation send">
                                        <div class="message-block-content">
                                            <p>Good Morning</p>
                                        </div>
                                    </div>
                                    <div class="message-conversation receive">
                                        <div class="message-block-content">
                                            <p>Good Morning! How are you ?</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="write-message-content">
                                <div class="write-message">
                                    <textarea name="" id="" placeholder="Write message"></textarea>
                                    <div class="message-file-upload">
                                        <label for="message-upload">
                                            <i class="ri-attachment-line"></i>
                                        </label>
                                        <input type="file" name="" id="message-upload">
                                    </div>
                                </div>
                                <div class="send-message">
                                    <button><i class="ri-send-plane-fill"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="message-2">...</div>
                        <div class="tab-pane fade" id="message-3">...</div>
                        <div class="tab-pane fade" id="message-4">...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection