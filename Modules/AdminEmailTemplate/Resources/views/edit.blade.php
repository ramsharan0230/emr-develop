@extends('frontend.layouts.master')
@section('content')
    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab"
                   aria-controls="home" aria-selected="true"><span></span>Patient Reports / Visit Report</a>
            </li>
        </ul>

        <div class="patient-profile">
            <div class="container-fluid">
                <form action="{{ route('admin.emailtemplate.update') }}" method="POST"
                      class="panel-body form-horizontal form-padding">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        {{ csrf_field() }}
                        <input type="hidden" name="_id" value="{{ $email_template->id }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="admin-first-name">Title <span
                                    class="required_color">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title') ? old('title') : $email_template->title }}" placeholder="Title">
                                <small class="help-block text-danger">{{$errors->first('title')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="admin-first-name">Subject <span
                                    class="required_color">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="subject" class="form-control"
                                       value="{{ old('subject') ? old('subject') : $email_template->subject }}"
                                       placeholder="Title">
                                <small class="help-block text-danger">{{$errors->first('subject')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="admin-first-name">Preview <span
                                    class="required_color">*</span></label>
                            <div class="col-md-9">
                            <textarea name="description" class="form-control"
                                      id="ckeditor_fullpage">{!! $email_template->description !!}</textarea>
                                <small class="help-block text-danger">{{$errors->first('description')}}</small>
                                <small class="help-block text-danger" style="font-weight: bold;font-size: 13px;">
                                    Note : Texts inside [[ ]] brackets are the placeholders and system reserved and you
                                    cannot change or edit them.
                                </small>
                            </div>
                        </div>
                        <script>
                            CKEDITOR.replace('ckeditor_fullpage', { // ckeditor with full page editing : eg Email Template
                                fullPage: true,
                                allowedContent: true,
                                extraPlugins: 'docprops',
                                contentsCss: 'html {overflow:scroll;}'
                            });
                        </script>

                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-block btn-primary" name="submit" value="UPDATE">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>


@stop

@push('after-script')

@endpush
