<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="eye-custom-col-eye">
                    <h4>Right Eye</h4>
                    <div class="eye-img img-eye">
                        @if(isset($exam['EyeImage']) && isset($exam['EyeImage']->right_eye))
                        <input type="hidden" name="right_eye" class="right-image" value="{{ $exam['EyeImage']->right_eye??'' }}">
                        <img src="{{asset('assets/images/eye.jpg')}}">
                        @if($exam['EyeImage']->right_eye != "")
                        <img src="{{ $exam['EyeImage']->right_eye }}">
                        @endif
                        @else
                        <input type="hidden" name="right_eye" class="right-image" value="">
                        <img src="{{asset('assets/images/eye.jpg')}}">
                        @endif
                        <canvas id="canvasRight-draw" height="210" width="290" class="canvas__eye"></canvas>
                    </div>
                    <div class="choose-color">
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="colorRight(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveRight()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="eraseRight()">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="eye-custom-col-table">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th colspan="6">Visual Acuity</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" class="align-middle">Vsion</th>
                                    <th colspan="2">Unaided</th>
                                    <th colspan="2">Aided</th>
                                    <th colspan="2">Pinhole</th>
                                </tr>
                                <tr>
                                    <th>Distance</th>
                                    <th>Near</th>
                                    <th>Distance</th>
                                    <th>Near</th>
                                    <th>Distance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>RE-eyelid</td>
                                    <td><input type="text" name="exam[Visual_Activity][Unaided][Distance][RE]" value="{{ (isset($exam['visualActivityData']['Unaided']['Distance']['RE'])) ? $exam['visualActivityData']['Unaided']['Distance']['RE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Unaided][Near][RE]" value="{{ (isset($exam['visualActivityData']['Unaided']['Near']['RE'])) ? $exam['visualActivityData']['Unaided']['Near']['RE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Aided][Distance][RE]" value="{{ (isset($exam['visualActivityData']['Aided']['Distance']['RE'])) ? $exam['visualActivityData']['Aided']['Distance']['RE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Aided][Near][RE]" value="{{ (isset($exam['visualActivityData']['Aided']['Near']['RE'])) ? $exam['visualActivityData']['Aided']['Near']['RE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Pinhole][Distance][RE]" value="{{ (isset($exam['visualActivityData']['Pinhole']['Distance']['RE'])) ? $exam['visualActivityData']['Pinhole']['Distance']['RE'] : '' }}" class="td-input"></td>
                                </tr>
                                <tr>
                                    <td>LE-eyelid</td>
                                    <td><input type="text" name="exam[Visual_Activity][Unaided][Distance][LE]" value="{{ (isset($exam['visualActivityData']['Unaided']['Distance']['LE'])) ? $exam['visualActivityData']['Unaided']['Distance']['LE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Unaided][Near][LE]" value="{{ (isset($exam['visualActivityData']['Unaided']['Near']['LE'])) ? $exam['visualActivityData']['Unaided']['Near']['LE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Aided][Distance][LE]" value="{{ (isset($exam['visualActivityData']['Aided']['Distance']['LE'])) ? $exam['visualActivityData']['Aided']['Distance']['LE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Aided][Near][LE]" value="{{ (isset($exam['visualActivityData']['Aided']['Near']['LE'])) ? $exam['visualActivityData']['Aided']['Near']['LE'] : '' }}" class="td-input"></td>
                                    <td><input type="text" name="exam[Visual_Activity][Pinhole][Distance][LE]" value="{{ (isset($exam['visualActivityData']['Pinhole']['Distance']['LE'])) ? $exam['visualActivityData']['Pinhole']['Distance']['LE'] : '' }}" class="td-input"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @include('eye::diagnosis')
                </div>
                <div class="eye-custom-col-eye">
                    <h4>Left Eye</h4>
                    <div class="eye-img img-eye">
                        @if(isset($exam['EyeImage']) && isset($exam['EyeImage']->left_eye))
                        <input type="hidden" name="left_eye" class="left-image" value="{{ $exam['EyeImage']->left_eye??'' }}">
                        <img src="{{asset('assets/images/eye.jpg')}}">
                        @if($exam['EyeImage']->left_eye != "")
                        <img src="{{ $exam['EyeImage']->left_eye }}">
                        @endif
                        @else
                        <input type="hidden" name="left_eye" class="left-image" value="">
                        <img src="{{asset('assets/images/eye.jpg')}}">
                        @endif
                        <canvas id="canvas-draw" height="210" width="290" class="canvas__eye"></canvas>
                    </div>
                    <div class="choose-color">
                        {{--color choose for left eye--}}
                        <div class="choose-color">
                            <ul class="color-choose">
                                <li class="green" id="green" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                                <li class="blue" id="blue" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                                <li class="red" id="red" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                                <li class="yellow" id="yellow" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                                <li class="orange" id="orange" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                                <li class="black" id="black" onclick="color(this)"><i class="ri-check-double-line"></i></li>
                            </ul>
                            <div class="eye-btn mt-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="save()">Save</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="erase()">Clear</button>
                            </div>
                        </div>
                        {{--end color choose for left eye--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
