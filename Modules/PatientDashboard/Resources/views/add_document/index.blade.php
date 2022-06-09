@extends('patient.layouts.master')
@section('content')
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="topspce">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="white_wrapper">
                                <div class="header_card">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Add Document</h4>
                                    </div>
                                </div>
                                <div class="add_doc_wrapper">
                                    <div class="row no-gutters border-bottom">
                                        <div class="file-manager-cards__dropzone w-100 p-2">
                                            <form action="/file-upload" class="dropzone dz-clickable">
                                                <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="row mt-4">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="file-manager__item card card-small mb-3">
                                        <div class="file-manager__item-preview card-body px-0 pb-0 pt-4">
                                            <img src="images/doc.jpg" alt="File Manager - Item Preview">
                                        </div>
                                        <div class="card-footer border-top">
                                            <span class="file-manager__item-icon">
                                                <i class="ri-file-3-fill"></i>
                                            </span>
                                            <h6 class="file-manager__item-title">Lorem Ipsum Document</h6>
                                            <span class="file-manager__item-size ml-auto">12kb</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="file-manager__item card card-small mb-3">
                                        <div class="file-manager__item-preview card-body px-0 pb-0 pt-4">
                                            <img src="images/doc.jpg" alt="File Manager - Item Preview">
                                        </div>
                                        <div class="card-footer border-top">
                                            <span class="file-manager__item-icon">
                                                <i class="ri-file-3-fill"></i>
                                            </span>
                                            <h6 class="file-manager__item-title">Lorem Ipsum Document</h6>
                                            <span class="file-manager__item-size ml-auto">12kb</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="file-manager__item card card-small mb-3">
                                        <div class="file-manager__item-preview card-body px-0 pb-0 pt-4">
                                            <img src="images/doc.jpg" alt="File Manager - Item Preview">
                                        </div>
                                        <div class="card-footer border-top">
                                            <span class="file-manager__item-icon">
                                                <i class="ri-file-3-fill"></i>
                                            </span>
                                            <h6 class="file-manager__item-title">Lorem Ipsum Document</h6>
                                            <span class="file-manager__item-size ml-auto">12kb</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="file-manager__item card card-small mb-3">
                                        <div class="file-manager__item-preview card-body px-0 pb-0 pt-4">
                                            <img src="images/doc.jpg" alt="File Manager - Item Preview">
                                        </div>
                                        <div class="card-footer border-top">
                                            <span class="file-manager__item-icon">
                                                <i class="ri-file-3-fill"></i>
                                            </span>
                                            <h6 class="file-manager__item-title">Lorem Ipsum Document</h6>
                                            <span class="file-manager__item-size ml-auto">12kb</span>
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