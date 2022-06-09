<form action="{{ route('setting.lab.printing.save') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="form-group col-sm-4">
            <label class="specs_label">Lab Type <span class="text-danger">*</span>:</label>
            <select name="lab_type" class="form-control select2 lab_type" required>
                <option value="">--Select--</option>
                <option value="lab_patient_type">Lab Report</option>
                <option value="lab_patient_pcr_type">PCR Report</option>
            </select>
        </div>
    </div>

    <div class="row header_type" style="display:none;">
        <div class="form-group col-sm-4">
            <label class="specs_label">Header Type <span class="text-danger">*</span>:</label>
        </div>
    </div>

    <div class="row header_type" style="display:none;">
        <div class="col-sm-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="header" id="header1" value="header1">
                <label class="form-check-label" for="header1">
                    <img src="{{ asset('new/images/lab-header-1.png') }}" alt="header1" class="img-thumbnail">
                </label>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="header" id="header2" value="header2">
                <label class="form-check-label" for="flexRadioDisabled">
                    <img src="{{ asset('new/images/lab-header-2.png') }}" alt="header2" class="img-thumbnail">
                </label>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="header" id="header3" value="header3">
                <label class="form-check-label" for="header3">
                    <img src="{{ asset('new/images/lab-header-3.png') }}" alt="header3" class="img-thumbnail">
                </label>
            </div>
        </div>
    </div>

    <div class="row mt-4 footer_all" style="display:none;">
        <div class="form-group col-sm-4">
            <label class="specs_label">Footer Type <span class="text-danger">*</span>:</label>
        </div>
    </div>

    <div class="row footer_all" style="display:none;">
        <div class="col-sm-3">
            <div class="form-check footer_all_click">
                <input class="form-check-input" type="radio" name="footer" id="footer1" value="footer1">
                <label class="form-check-label" for="footer1">
                    <img src="{{ asset('new/images/lab-footer-1.png') }}" alt="" class="img-thumbnail">
                </label>
            </div>
        </div>

        <div class="col-sm-3 footer_all_click">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="footer" id="footer2" value="footer2">
                <label class="form-check-label" for="footer2">
                    <img src="{{ asset('new/images/lab-footer-2.png') }}" alt="" class="img-thumbnail">
                </label>
            </div>
        </div>

        <div class="col-sm-3 footer_all_click">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="footer" id="footer3" value="footer3">
                <label class="form-check-label" for="footer3">
                    <img src="{{ asset('new/images/lab-footer-3.png') }}" alt="" class="img-thumbnail">
                </label>
            </div>
        </div>


        <div class="col-sm-3 footer_all_click">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="footer" id="footer4" value="footer4">
                <label class="form-check-label" for="footer4">
                    <img src="{{ asset('new/images/lab-footer-4.png') }}" alt="" class="img-thumbnail">
                </label>
            </div>
        </div>
    </div>

    <div class="row signature_all" style="display:none;">
        <div class="col-sm-4 mt-3 mb-3">
            <label class="specs_label">Signature Types <span class="text-danger">*</span>:</label>
        </div>
    </div>

    <div class="row signature_all">
        <div class="col-sm-3 left_signature" style="display:none">
            <label class="specs_label">Left Signature<span class="text-danger">*</span>:</label>
            <select name="left_signature" class="form-control select2 left_signature_select">
                <option value="">--Select--</option>
                <option value="left_signature_auto">Auto</option>
                <option value="left_signature_upload">Upload</option>
                <option value="left_signature_manual">Manual</option>
            </select>
            <div class="form-group mt-2 left_signature_upload_details" style="display:none">
                {{-- <label>Upload Signature</label> --}}
                <div> 
                    @if( Options::get('left_signature_image') != "" )
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center preview-img">
                                    <img class="left_signature_image_logo">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex form-group align-items-center preview-img">
                            <img class="left_signature_image_logo">
                        </div>
                    @endif
                </div>
                <div class="upload-btn">
                    <input type="file" name="left_signature_image" id="left_signature_image" style="display:none;"/> 
                    <a class="btn btn-primary" id="left_signature_image_upload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload Signature</a>
                </div>
            </div>

            <div class="left_signature_upload_details" style="display:none">
                <label>Upload Details</label>
                <div>
                    <textarea rows="4" name="left_signature_textarea" id="left_signature_textarea"></textarea>
                </div>
            </div> 
        </div>
        
        <div class="col-sm-3 left_center_signature" style="display:none">
            <label class="specs_label">Left Center Signature<span class="text-danger">*</span>:</label>
            <select name="left_center_signature" class="form-control select2 left_center_signature_select">
                <option value="">--Select--</option>
                <option value="left_center_signature_auto">Auto</option>
                <option value="left_center_signature_upload">Upload</option>
                <option value="left_center_signature_manual">Manual</option>
            </select>

            <div class="form-group mt-2 left_center_signature_upload_details" style="display:none">
                {{-- <label>Upload Signature</label> --}}
                <div> 
                    @if( Options::get('left_center_signature_image')!= "" )
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center preview-img">
                                    <img class="left_center_signature_image_logo">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex form-group align-items-center preview-img">
                            <img class="left_center_signature_image_logo">
                        </div>
                    @endif
                </div>
                <div class="upload-btn">
                    <input type="file" name="left_center_signature_image" id="left_center_signature_image" style="display:none;"/> 
                    <a class="btn btn-primary" id="left_center_signature_image_upload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload Signature</a>
                </div>
            </div>

            <div class="left_center_signature_upload_details" style="display:none">
                <label>Upload Details</label>
                <div>
                <textarea rows="4" class="text-area" name="left_center_signature_textarea" id="left_center_signature_textarea"></textarea>
                </div>
            </div> 
        </div>
        
        <div class="col-sm-3 center_signature" style="display:none">
            <label class="specs_label">Center Signature<span class="text-danger">*</span>:</label>
            <select name="center_signature" class="form-control select2 center_signature_select">
                <option value="">--Select--</option>
                <option value="center_signature_auto">Auto</option>
                <option value="center_signature_upload">Upload</option>
                <option value="center_signature_manual">Manual</option>
            </select>

            <div class="form-group mt-2 center_signature_upload_details" style="display:none">
                {{-- <label>Upload Signature</label> --}}
                <div> 
                    @if( Options::get('center_signature_image') != "" )
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center preview-img">
                                    <img class="center_signature_image_logo">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex form-group align-items-center preview-img">
                            <img class="center_signature_image_logo">
                        </div>
                    @endif
                </div>
                <div class="upload-btn">
                    <input type="file" name="center_signature_image" id="center_signature_image" style="display:none;"/> 
                    <a class="btn btn-primary" id="center_signature_image_upload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload Signature</a>
                </div>
            </div>

            <div class="center_signature_upload_details" style="display:none">
                <label>Upload Details</label>
                <div>
                    <textarea rows="4" name="center_signature_textarea" id="center_signature_textarea"></textarea>
                </div>
            </div>  
        </div>

        <div class="col-sm-3 right_center_signature" style="display:none">
            <label class="specs_label ">Right Center Signature<span class="text-danger">*</span>:</label>
            <select name="right_center_signature" class="form-control select2 right_center_signature_select">
                <option value="">--Select--</option>
                <option value="right_center_signature_auto">Auto</option>
                <option value="right_center_signature_upload">Upload</option>
                <option value="right_center_signature_manual">Manual</option>
            </select>
            <div class="form-group mt-2 right_center_signature_upload_details" style="display:none">
                {{-- <label>Upload Signature</label> --}}
                <div> 
                    @if( Options::get('right_center_signature_image') != "" )
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center preview-img">
                                    <img class="right_center_signature_image_logo">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex form-group align-items-center preview-img">
                            <img class="right_center_signature_image_logo">
                        </div>
                    @endif
                </div>
                <div class="upload-btn">
                    <input type="file" name="right_center_signature_image" id="right_center_signature_image" style="display:none;"/> 
                    <a class="btn btn-primary" id="right_center_signature_image_upload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload Signature</a>
                </div>
            </div>
            <div class="right_center_signature_upload_details" style="display:none">
                <label>Upload Details</label>
                <div>
                <textarea rows="4" name="right_center_signature_textarea" id="right_center_signature_textarea"></textarea>
                </div>
            </div>  
        </div>

        <div class="col-sm-3 right_signature" style="display:none">
            <label class="specs_label">Right Signature<span class="text-danger">*</span>:</label>
            <select name="right_signature" class="form-control select2 right_signature_select">
                <option value="">--Select--</option>
                <option value="right_signature_auto">Auto</option>
                <option value="right_signature_upload">Upload</option>
                <option value="right_signature_manual">Manual</option>
            </select>
            <div class="form-group mt-2 right_signature_upload_details" style="display:none">
                {{-- <label>Upload Signature</label> --}}
                <div> 
                    @if( Options::get('right_signature_image') != "" )
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-12">
                                <div class="d-flex align-items-center preview-img">
                                    <img class="right_signature_image_logo">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex form-group align-items-center preview-img">
                            <img class="right_signature_image_logo">
                        </div>
                    @endif
                </div>
                <div class="upload-btn">
                    <input type="file" name="right_signature_image" id="right_signature_image" style="display:none;"/> 
                    <a class="btn btn-primary" id="right_signature_image_upload" style="color: white;"><i class="fa fa-upload"></i>&nbsp;Upload Signature</a>
                </div>
            </div>

            <div class="right_signature_upload_details" style="display:none">
                <label>Upload Details</label>
                <div>
                <textarea rows="4" name="right_signature_textarea" id="right_signature_textarea"></textarea>
                </div>
            </div>
        </div>
    </div>
        <div class="col mt-3">
        <button type="submit" class="btn btn-primary btn-action float-right">Save</button>
        </div>
</form>

<script>
//     let left_signature_value="{{ Options::get('left_signature') }}";
//   $(".left_signature_select option").each(function () {
//                 let left_signature_select = $(this).val();
//                 console.log(left_signature_select)
//                 if(left_signature_value === left_signature_select){
//                     $('.left_signature_select option[value="left_signature_upload"]').attr('selected', true);
//                 }
//             });
    CKEDITOR.replace('left_signature_textarea',
    {
        height: '350px',
    });

    CKEDITOR.replace('left_center_signature_textarea',
    {
        height: '350px',
    });

    CKEDITOR.replace('center_signature_textarea',
    {
        height: '350px',
    });

    CKEDITOR.replace('right_center_signature_textarea',
    {
        height: '350px',
    });

    CKEDITOR.replace('right_signature_textarea',
    {
        height: '350px',
    });
    $('#right_signature_image_upload').click(function(){ 
        $('#right_signature_image').trigger('click'); 
    });

    $('#right_center_signature_image_upload').click(function(){ 
        $('#right_center_signature_image').trigger('click'); 
    });

    $('#center_signature_image_upload').click(function(){ 
        $('#center_signature_image').trigger('click'); 
    });

    $('#left_center_signature_image_upload').click(function(){ 
        $('#left_center_signature_image').trigger('click'); 
    });

    $('#left_signature_image_upload').click(function(){ 
        $('#left_signature_image').trigger('click'); 
    });

    $("#right_signature_image").change(function () {
        readRightSignatureURL(this);
    });

    function readRightSignatureURL(input) {
        console.log(input)
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.right_signature_image_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#right_center_signature_image").change(function () {
        readRightCenterSignatureURL(this);
    });

    function readRightCenterSignatureURL(input) {
        console.log(input)
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.right_center_signature_image_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#center_signature_image").change(function () {
        readCenterSignatureURL(this);
    });

    function readCenterSignatureURL(input) {
        console.log(input)
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.center_signature_image_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#left_center_signature_image").change(function () {
        readLeftCenterSignatureURL(this);
    });

    function readLeftCenterSignatureURL(input) {
        console.log(input)
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.left_center_signature_image_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#left_signature_image").change(function () {
        readLeftSignatureURL(this);
    });
    function readLeftSignatureURL(input) {
        console.log(input)
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.left_signature_image_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(".lab_type").change(function() {        
        let lab_type = $(".lab_type").val();
        console.log(lab_type);
        if (lab_type === 'lab_patient_type') {
            $(".header_type").show();
            $(".signature_all").show();
            $(".footer_all").show();
        }
        else if(lab_type === 'lab_patient_pcr_type'){
            $(".header_type").show();
            $(".signature_all").show();
            $(".footer_all").show();
        } 
        else {
            $(".header_type").hide();
            $(".signature_all").hide();
            $(".footer_all").hide();
        }
        if (lab_type == "lab_patient_type") {
            let lab_type_value = "{{ Options::get('lab_patient_header') }}";
            let lab_type_total = "{{ Options::get('lab_patient_footer') }}";
            if (lab_type_value)
                $('#' + lab_type_value).prop('checked', true);
            else {
                $('#header1').prop('checked', false);
                $('#header2').prop('checked', false);
                $('#header3').prop('checked', false);
            }
            if (lab_type_total)
                $('#' + lab_type_total).prop('checked', true);
            else {
                $('#footer1').prop('checked', false);
                $('#footer2').prop('checked', false);
                $('#footer3').prop('checked', false);
                $('#footer4').prop('checked', false);
            }
        } else if (lab_type == "lab_patient_pcr_type") {
            let lab_type_value = "{{ Options::get('lab_patient_pcr_header') }}";
            let lab_type_total = "{{ Options::get('lab_patient_pcr_footer') }}";
            if (lab_type_value)
                $('#' + lab_type_value).prop('checked', true);
            else {
                $('#header1').prop('checked', false);
                $('#header2').prop('checked', false);
                $('#header3').prop('checked', false);
            }
            if (lab_type_total)
                $('#' + lab_type_total).prop('checked', true);
            else {
                $('#footer1').prop('checked', false);
                $('#footer2').prop('checked', false);
                $('#footer3').prop('checked', false);
                $('#footer4').prop('checked', false);
            }
        } else {
            $('#header1').prop('checked', false);
            $('#header2').prop('checked', false);
            $('#header3').prop('checked', false);
            $('#footer1').prop('checked', false);
            $('#footer2').prop('checked', false);
            $('#footer3').prop('checked', false);
            $('#footer4').prop('checked', false);
        }

        if (lab_type == "lab_patient_type") {
            
            console.log('patienttype')
            let lab_footer_type = "{{ Options::get('lab_patient_footer') }}";
            if(lab_footer_type==='footer1'){
                $(".left_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
                $(".center_signature").hide();          
            }else if(lab_footer_type==='footer2'){
                $(".left_signature").show();
                $(".center_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
            }else if(lab_footer_type==='footer3'){
                $(".left_signature").show();
                $(".center_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
            }else if(lab_footer_type==='footer4'){
                $(".left_signature").show();
                $(".left_center_signature").show();
                $(".right_center_signature").show();
                $(".right_signature").show();

                $(".center_signature").hide();
            }
        }else if(lab_type == "lab_patient_pcr_type"){
            console.log('pcrpatient')
            let pcr_lab_footer_type = "{{ Options::get('lab_patient_pcr_footer') }}";
            if(pcr_lab_footer_type==='footer1'){
                $(".left_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
                $(".center_signature").hide();          
            }else if(pcr_lab_footer_type==='footer2'){
                $(".left_signature").show();
                $(".center_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
            }else if(pcr_lab_footer_type==='footer3'){
                $(".left_signature").show();
                $(".center_signature").show();
                $(".right_signature").show();

                $(".left_center_signature").hide();
                $(".right_center_signature").hide();
            }else if(pcr_lab_footer_type==='footer4'){
                $(".left_signature").show();
                $(".left_center_signature").show();
                $(".right_center_signature").show();
                $(".right_signature").show();

                $(".center_signature").hide();
            }
        }
        if (lab_type == "lab_patient_type") {
            let left_signature_value = "{{ Options::get('left_signature') }}";
            let static_image="{{asset('assets/images/edit.png')}}";
            if(left_signature_value==='left_signature_upload'){
                $(".left_signature_upload_details").show();
                let left_signature_image_check="{{Options::get('left_signature_image') }}";
                if(left_signature_image_check){
                    let left_signature_image_value="{{ asset('uploads/config/'.Options::get('left_signature_image')) }}";
                    $('.left_signature_image_logo').attr("src", `${left_signature_image_value}`);
                }else{
                    $('.left_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".left_signature_upload_details").hide();
            }

            let left_center_signature_value="{{ Options::get('left_center_signature') }}";
            if(left_center_signature_value==='left_center_signature_upload'){
                $(".left_center_signature_upload_details").show();
                let left_center_signature_image_check="{{Options::get('left_center_signature_image') }}";
                
                if(left_center_signature_image_check){
                    let left_center_signature_image_value="{{ asset('uploads/config/'.Options::get('left_center_signature_image')) }}";
                    $('.left_center_signature_image_logo').attr("src", `${left_center_signature_image_value}`);
                }else{
                    $('.left_center_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".left_center_signature_upload_details").hide();
            }

            let center_signature_value="{{ Options::get('center_signature') }}";
            if(center_signature_value==='center_signature_upload'){
                $(".center_signature_upload_details").show();
                let center_signature_image_check="{{Options::get('center_signature_image') }}";
                
                if(center_signature_image_check){
                    let center_signature_image_value="{{ asset('uploads/config/'.Options::get('center_signature_image')) }}";
                    $('.center_signature_image_logo').attr("src", `${center_signature_image_value}`);
                }else{
                    $('.center_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".center_signature_upload_details").hide();
            }

            let right_center_signature_value="{{ Options::get('right_center_signature') }}";
            if(right_center_signature_value==='right_center_signature_upload'){
                $(".right_center_signature_upload_details").show();
                let right_center_signature_image_check="{{Options::get('right_center_signature_image') }}";
                
                if(right_center_signature_image_check){
                    let right_center_signature_image_value="{{ asset('uploads/config/'.Options::get('right_center_signature_image')) }}";
                    $('.right_center_signature_image_logo').attr("src", `${right_center_signature_image_value}`);
                }else{
                    $('.right_center_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".right_center_signature_upload_details").hide();
            }

            let right_signature_value="{{ Options::get('right_signature') }}";
            if(right_signature_value==='right_signature_upload'){
                $(".right_signature_upload_details").show();
                let right_signature_image_check="{{Options::get('right_signature_image') }}";
                
                if(right_signature_image_check){
                    let right_signature_image_value="{{ asset('uploads/config/'.Options::get('right_signature_image')) }}";
                    $('.right_signature_image_logo').attr("src", `${right_signature_image_value}`);
                }else{
                    $('.right_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".right_signature_upload_details").hide();
            }
            $(`.left_signature_select option[value="${left_signature_value}"]`).attr('selected', true).change();
            $(`.left_center_signature_select option[value="${left_center_signature_value}"]`).attr('selected', true).change();
            $(`.center_signature_select option[value="${center_signature_value}"]`).attr('selected', true).change();
            $(`.right_center_signature_select option[value="${right_center_signature_value}"]`).attr('selected', true).change();
            $(`.right_signature_select option[value="${right_signature_value}"]`).attr('selected', true).change();

            let right_signature_textarea_value=`{!! Options::get('right_signature_textarea') !!}`;
             CKEDITOR.instances['right_signature_textarea'].setData(right_signature_textarea_value);

             let right_center_signature_textarea_value=`{!! Options::get('right_center_signature_textarea') !!}`;
             CKEDITOR.instances['right_center_signature_textarea'].setData(right_center_signature_textarea_value);

             let center_signature_textarea_value=`{!! Options::get('center_signature_textarea') !!}`;
             CKEDITOR.instances['center_signature_textarea'].setData(center_signature_textarea_value);

             let left_center_signature_textarea_value=`{!! Options::get('left_center_signature_textarea') !!}`;
             CKEDITOR.instances['left_center_signature_textarea'].setData(left_center_signature_textarea_value);

             let left_signature_textarea_value=`{!! Options::get('left_signature_textarea') !!}`;
             CKEDITOR.instances['left_signature_textarea'].setData(left_signature_textarea_value);
            
        }else if(lab_type == "lab_patient_pcr_type"){
            let pcr_left_signature_value = "{{ Options::get('pcr_left_signature') }}";
            let static_image="{{asset('assets/images/edit.png')}}";
            if(pcr_left_signature_value==='left_signature_upload'){
                $(".left_signature_upload_details").show();
                let pcr_left_signature_image_check="{{Options::get('pcr_left_signature_image') }}";

                if(pcr_left_signature_image_check){
                    let pcr_left_signature_image_value="{{ asset('uploads/config/'.Options::get('pcr_left_signature_image')) }}";
                    $('.left_signature_image_logo').attr("src", `${pcr_left_signature_image_value}`);
                }else{
                    $('.left_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".left_signature_upload_details").hide();
            }

            let pcr_left_center_signature_value="{{ Options::get('pcr_left_center_signature') }}";
            if(pcr_left_center_signature_value==='left_center_signature_upload'){
                $(".left_center_signature_upload_details").show();

                let pcr_left_center_signature_image_check="{{Options::get('pcr_left_center_signature_image') }}";
                
                if(pcr_left_center_signature_image_check){
                    let pcr_left_center_signature_image_value="{{ asset('uploads/config/'.Options::get('pcr_left_center_signature_image')) }}";
                    $('.left_center_signature_image_logo').attr("src", `${pcr_left_center_signature_image_value}`);
                }else{
                    $('.left_center_signature_image_logo').attr("src", `${static_image}`);
                }

            }else{
                $(".left_center_signature_upload_details").hide();
            }

            let pcr_center_signature_value="{{ Options::get('pcr_center_signature') }}";
            if(pcr_center_signature_value==='center_signature_upload'){
                $(".center_signature_upload_details").show();
                let pcr_center_signature_image_check="{{Options::get('pcr_center_signature_image') }}";
                
                if(pcr_center_signature_image_check){
                    let pcr_center_signature_image_value="{{ asset('uploads/config/'.Options::get('pcr_center_signature_image')) }}";
                    $('.center_signature_image_logo').attr("src", `${pcr_center_signature_image_value}`);
                }else{
                    $('.center_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".center_signature_upload_details").hide();
            }

            let pcr_right_center_signature_value="{{ Options::get('pcr_right_center_signature') }}";
            if(pcr_right_center_signature_value==='right_center_signature_upload'){
                $(".right_center_signature_upload_details").show();
                let pcr_right_center_signature_image_check="{{Options::get('pcr_right_center_signature_image') }}";
                
                if(pcr_right_center_signature_image_check){
                    let pcr_right_center_signature_image_value="{{ asset('uploads/config/'.Options::get('pcr_right_center_signature_image')) }}";
                    $('.right_center_signature_image_logo').attr("src", `${pcr_right_center_signature_image_value}`);
                }else{
                    $('.right_center_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".right_center_signature_upload_details").hide();
            }

            let pcr_right_signature_value="{{ Options::get('pcr_right_signature') }}";
            if(pcr_right_signature_value==='right_signature_upload'){
                $(".right_signature_upload_details").show();
                let pcr_right_signature_image_check="{{Options::get('pcr_right_signature_image') }}";
                
                if(pcr_right_signature_image_check){
                    let pcr_right_signature_image_value="{{ asset('uploads/config/'.Options::get('pcr_right_signature_image')) }}";
                    $('.right_signature_image_logo').attr("src", `${pcr_right_signature_image_value}`);
                }else{
                    $('.right_signature_image_logo').attr("src", `${static_image}`);
                }
            }else{
                $(".right_signature_upload_details").hide();
            }

            $(`.left_signature_select option[value="${pcr_left_signature_value}"]`).attr('selected', true).change();
            $(`.left_center_signature_select option[value="${pcr_left_center_signature_value}"]`).attr('selected', true).change();
            $(`.center_signature_select option[value="${pcr_center_signature_value}"]`).attr('selected', true).change();
            $(`.right_center_signature_select option[value="${pcr_right_center_signature_value}"]`).attr('selected', true).change();
            $(`.right_signature_select option[value="${pcr_right_signature_value}"]`).attr('selected', true).change();

             let pcr_right_signature_textarea_value=`{!! Options::get('pcr_right_signature_textarea') !!}`;
             CKEDITOR.instances['right_signature_textarea'].setData(pcr_right_signature_textarea_value);

             let pcr_right_center_signature_textarea_value=`{!! Options::get('pcr_right_center_signature_textarea') !!}`;
             CKEDITOR.instances['right_center_signature_textarea'].setData(pcr_right_center_signature_textarea_value);

             let pcr_center_signature_textarea_value=`{!! Options::get('pcr_center_signature_textarea') !!}`;
             CKEDITOR.instances['center_signature_textarea'].setData(pcr_center_signature_textarea_value);

             let pcr_left_center_signature_textarea_value=`{!! Options::get('pcr_left_center_signature_textarea') !!}`;
             CKEDITOR.instances['left_center_signature_textarea'].setData(pcr_left_center_signature_textarea_value);

             let pcr_left_signature_textarea_value=`{!! Options::get('pcr_left_signature_textarea') !!}`;
             CKEDITOR.instances['left_signature_textarea'].setData(pcr_left_signature_textarea_value);

        }

    });

    //hide and show signature types when the footer is change
    $(".footer_all_click input[name='footer']").on('click',function(){
    // $(document).on('click', '.footer_all_click', function() {
        console.log('fdsafdf');
        let footer_type=$(this).val();
        console.log(footer_type);
        if(footer_type==='footer1'){
            $(".left_signature").show();
            $(".right_signature").show();

            $(".left_center_signature").hide();
            $(".right_center_signature").hide();
            $(".center_signature").hide();          
        }else if(footer_type==='footer2'){
            $(".left_signature").show();
            $(".center_signature").show();
            $(".right_signature").show();

            $(".left_center_signature").hide();
            $(".right_center_signature").hide();
        }else if(footer_type==='footer3'){
            $(".left_signature").show();
            $(".center_signature").show();
            $(".right_signature").show();

            $(".left_center_signature").hide();
            $(".right_center_signature").hide();
        }else if(footer_type==='footer4'){
            $(".left_signature").show();
            $(".left_center_signature").show();
            $(".right_center_signature").show();
            $(".right_signature").show();

            $(".center_signature").hide();
        }
    });

    
    $(".left_signature_select").change(function() {
        let left_signature_value=$(this).val();
        if(left_signature_value==='left_signature_upload'){
            $(".left_signature_upload_details").show();
        }else{
            $(".left_signature_upload_details").hide();
        }
    });

    $(".left_center_signature_select").change(function() {
        let left_center_signature_value=$(this).val();
        if(left_center_signature_value==='left_center_signature_upload'){
            $(".left_center_signature_upload_details").show();
        }else{
            $(".left_center_signature_upload_details").hide();
        }
    });

    $(".center_signature_select").change(function() {
        let center_signature_value=$(this).val();
        if(center_signature_value==='center_signature_upload'){
            $(".center_signature_upload_details").show();
        }else{
            $(".center_signature_upload_details").hide();
        }
    });

    $(".right_center_signature_select").change(function() {
        let right_center_signature_value=$(this).val();
        if(right_center_signature_value==='right_center_signature_upload'){
            $(".right_center_signature_upload_details").show();
        }else{
            $(".right_center_signature_upload_details").hide();
        }
    });

    $(".right_signature_select").change(function() {
        let right_signature_value=$(this).val();
        if(right_signature_value==='right_signature_upload'){
            $(".right_signature_upload_details").show();
        }else{
            $(".right_signature_upload_details").hide();
        }
    });
</script>
