<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="custom-col-audiogram">
                    <div class="iq-card-header d-flex justify-content-between">
                        <h4>Ear</h4>
                        <div class="allergy-add">
                            <a href="javascript:;" class="iq-bg-primary"><i @if(!Session::has('ent_encounter_id')) class="ri-add-fill" @else class="ri-add-fill showComment" @endif></i></a>
                        </div>
                        <button @if(!Session::has('ent_encounter_id')) disabled @endif type="button" class="btn btn-primary btn-sm float-right save_comment {{ $disableClass }}" data-type="ear"><i class="ri-check-fill"></i></button>
                    </div>
                    <textarea  name="commentEar" class="form-control commentArea" id="commentEar" rows="3">@if(isset($exam['EntImage'])){{ $exam['EntImage']->comment_ear }}@endif</textarea>
                    <div class="ent-img mt-2 img-audiogram-ear">
                        @if(isset($exam['EntImage']) && isset($exam['EntImage']->image_ear))
                            <input type="hidden" name="image_ear" class="ear-image" value="{{ $exam['EntImage']->image_ear??'' }}">
                            <img src="{{asset('assets/images/ear.jpg')}}">
                            @if($exam['EntImage']->image_ear != "")
                                <img src="{{ $exam['EntImage']->image_ear }}">
                            @endif
                        @else
                            <input type="hidden" name="image_ear" class="ear-image" value="">
                            <img src="{{asset('assets/images/ear.jpg')}}">
                        @endif
                        <canvas id="ear-canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                    </div>
                    <div class="choose-color">
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="colorEar(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveEar()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="eraseEar()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-col-audiogram">
                    <div class="iq-card-header d-flex justify-content-between">
                         <h4>Nose</h4>
                        <div class="allergy-add">
                            <a href="javascript:;" class="iq-bg-primary"><i @if(!Session::has('ent_encounter_id')) class="ri-add-fill" @else class="ri-add-fill showComment" @endif></i></a>
                        </div>
                        <button @if(!Session::has('ent_encounter_id')) disabled @endif type="button" class="btn btn-primary btn-sm float-right save_comment {{ $disableClass }}" data-type="nose"><i class="ri-check-fill"></i></button>
                    </div>
                    <textarea  name="commentNose" class="form-control commentArea" id="commentNose" rows="3">@if(isset($exam['EntImage'])){{ $exam['EntImage']->comment_nose }}@endif</textarea>
                    <div class="ent-img mt-2 img-audiogram-nose">
                        @if(isset($exam['EntImage']) && isset($exam['EntImage']->image_nose))
                            <input type="hidden" name="image_nose" class="nose-image" value="{{ $exam['EntImage']->image_nose??'' }}">
                            <img src="{{asset('assets/images/nose.jpg')}}"/>
                            @if($exam['EntImage']->image_nose != "")
                                <img src="{{ $exam['EntImage']->image_nose }}">
                            @endif
                        @else
                            <input type="hidden" name="image_nose" class="nose-image" value="">
                            <img src="{{asset('assets/images/nose.jpg')}}"/>
                        @endif
                        <canvas id="nose-canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                    </div>
                    <div class="choose-color">
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="colorNose(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveNose()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="eraseNose()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-col-audiogram">
                    <div class="iq-card-header d-flex justify-content-between">
                         <h4>Throat</h4>
                        <div class="allergy-add">
                            <a href="javascript:;" class="iq-bg-primary"><i @if(!Session::has('ent_encounter_id')) class="ri-add-fill" @else class="ri-add-fill showComment" @endif></i></a>
                        </div>
                        <button @if(!Session::has('ent_encounter_id')) disabled @endif type="button" class="btn btn-primary btn-sm float-right save_comment {{ $disableClass }}" data-type="throat"><i class="ri-check-fill"></i></button>
                    </div>
                    <textarea name="commentThroat" class="form-control commentArea" id="commentThroat" rows="3">@if(isset($exam['EntImage'])){{ $exam['EntImage']->comment_throat }}@endif</textarea>
                    <div class="ent-img mt-2 img-audiogram-throat">
                        @if(isset($exam['EntImage']) && isset($exam['EntImage']->image_throat))
                            <input type="hidden" name="image_throat" class="throat-image" value="{{ $exam['EntImage']->image_throat??'' }}">
                            <img src="{{asset('assets/images/throat.jpg')}}"/>
                            @if($exam['EntImage']->image_throat != "")
                                <img src="{{ $exam['EntImage']->image_throat }}">
                            @endif
                        @else
                            <input type="hidden" name="image_throat" class="throat-image" value="">
                            <img src="{{asset('assets/images/throat.jpg')}}"/>
                        @endif
                        <canvas id="throat-canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                    </div>
                    <div class="choose-color">
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="colorThroat(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveThroat()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="eraseThroat()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-col-audiogram">
                    <div class="iq-card-header d-flex justify-content-between">
                         <h4>Tongue</h4>
                        <div class="allergy-add">
                            <a href="javascript:;" class="iq-bg-primary"><i @if(!Session::has('ent_encounter_id')) class="ri-add-fill" @else class="ri-add-fill showComment" @endif></i></a>
                        </div>
                        <button @if(!Session::has('ent_encounter_id')) disabled @endif type="button" class="btn btn-primary btn-sm float-right save_comment {{ $disableClass }}" data-type="tongue"><i class="ri-check-fill"></i></button>
                    </div>
                    <textarea name="commentTongue" class="form-control commentArea" id="commentTongue" rows="3">@if(isset($exam['EntImage'])){{ $exam['EntImage']->comment_tongue }}@endif</textarea>
                    <div class="ent-img mt-2 img-audiogram-tongue">
                        @if(isset($exam['EntImage']) && isset($exam['EntImage']->image_tongue))
                            <input type="hidden" name="image_tongue" class="tongue-image" value="{{ $exam['EntImage']->image_tongue??'' }}">
                            <img src="{{asset('assets/images/tongue.jpg')}}"/>
                            @if($exam['EntImage']->image_tongue != "")
                                <img src="{{ $exam['EntImage']->image_tongue }}">
                            @endif
                        @else
                            <input type="hidden" name="image_tongue" class="tongue-image" value="">
                            <img src="{{asset('assets/images/tongue.jpg')}}"/>
                        @endif
                        <canvas id="tongue-canvas-draw" height="210" width="244" class="canvas__img"></canvas>
                    </div>
                    <div class="choose-color">
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="colorTongue(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveTongue()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="eraseTongue()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
