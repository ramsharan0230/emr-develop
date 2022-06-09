@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Form Signature</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <form action="{{ route('setting.signature.insert') }}" method="POST" class="form-horizontal">
                                    @csrf
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Form Name*</label>
                                        <div class="col-sm-9">
                                            <select name="form_name" onchange="getFormSignatures()" class="form-control" id="form-signature-on-change">
                                                <option value="0">---select---</option>
                                                @if(count($form_name_list))
                                                    @foreach($form_name_list as $name)
                                                        <option value="{{ $name->form_name }}" {{ $name->form_name == $formName?'selected':'' }}>{{ $name->form_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('free_text')}}</small>
                                        </div>
                                    </div>
                                    @if($formName)
                                        @if($signature_left)
                                            @forelse($signature_left as $sig)
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Left Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="left_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($sig->user_id) && $sig->user_id == $user->id? 'selected':''  }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('left_signature')}}</small>
                                                    </div>
                                                    @if($loop->first)
                                                        <a href="javascript:;" onclick="addSignature('append-left-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Left Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="left_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($signature_left->user_id) && $signature_left->user_id == $user->id? 'selected':''  }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('left_signature')}}</small>
                                                    </div>

                                                    <a href="javascript:;" onclick="addSignature('append-left-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                </div>
                                            @endforelse
                                        @endif

                                        <div id="append-left-signature" class="form-group form-row align-items-center"></div>

                                        <hr>
                                        @if($signature_middle)
                                            @forelse($signature_middle as $sig)
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Middle Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="middle_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($sig->user_id) && $sig->user_id == $user->id? 'selected':''  }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('left_signature')}}</small>
                                                    </div>
                                                    @if($loop->first)
                                                        <a href="javascript:;" onclick="addSignature('append-middle-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Middle Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="middle_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($signature_middle->user_id) && $signature_middle->user_id == $user->id? 'selected':'' }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('middle_signature')}}</small>
                                                    </div>

                                                    <a href="javascript:;" onclick="addSignature('append-middle-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                </div>
                                            @endforelse
                                        @endif

                                        <div id="append-middle-signature" class="form-group form-row align-items-center"></div>

                                        <hr>
                                        @if($signature_right)
                                            @forelse($signature_right as $sig)
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Right Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="right_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($sig->user_id) && $sig->user_id == $user->id? 'selected':''  }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('left_signature')}}</small>
                                                    </div>
                                                    @if($loop->first)
                                                        <a href="javascript:;" onclick="addSignature('append-right-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Right Signature</label>
                                                    <div class="col-sm-8">
                                                        <select name="right_signature[]" class="form-control">
                                                            <option value="">--Select--</option>
                                                            @if(count($users))
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ isset($signature_middle->user_id) && $signature_middle->user_id == $user->id? 'selected':'' }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <small class="help-block text-danger">{{$errors->first('middle_signature')}}</small>
                                                    </div>

                                                    <a href="javascript:;" onclick="addSignature('append-right-signature')" class="btn btn-primary col-1"><i class="fas fa-plus"></i></a>
                                                </div>
                                            @endforelse
                                        @endif

                                        <div id="append-right-signature" class="form-group form-row align-items-center"></div>
                                    @endif
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3"></label>
                                        <div class="col-sm-9">
                                            <button class="btn btn-primary btn-action">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@push('after-script')
    <script>
        function getFormSignatures() {
            window.location.replace("{{ route('setting.signature.form') }}" + '/' + $('#form-signature-on-change').val());
        }

        function addSignature(signaturePosition) {
            $.ajax({
                url: '{{ route('setting.signature.append.select') }}',
                type: "POST",
                data: {signaturePosition: signaturePosition},
                success: function (response) {
                    console.log(response)
                    $('#' + signaturePosition).append(response);
                    // showAlert(response.message)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    </script>
@endpush
