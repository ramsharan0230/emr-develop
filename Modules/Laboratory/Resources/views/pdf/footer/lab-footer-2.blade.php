<!-- footer 2  2 prepared by 1 verified by-->
<footer  class="pdf-container sign-container">
    <div style="width: 28%;float: left;padding:10px 10px 0;">
        @if(Options::get('left_signature')=='left_signature_auto')
            <div>
                @if(isset($user_prepared_first->signature_image)) 
                    <img class="signature-image" src="data:image/jpg;base64,{{ $user_prepared_first->signature_image??'' }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;">
                @endif
                <p class="underline" style="margin-top: 5px;margin-bottom: 0;">_________________________</p>
                <p>Performed By :<br>
                    <span class="uppercase">{{$user_prepared_first->fldcategory??''}} {{$user_prepared_first->firstname??''}} {{$user_prepared_first->middlename??''}} {{$user_prepared_first->lastname??''}}</span><br>
                    {{ $user_prepared_first->signature_title??''}}<br>
                    @if(isset($user_prepared_first->nhbc))
                    NHPC : {{$user_prepared_first->nhbc??''}}
                    @endif
                </p>
            </div>
        @endif
        <!--Manual (all handwritten signatrue and details)-->
        @if(Options::get('left_signature')=='left_signature_manual')
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Performed By : <br>
                </p>
            </div>
        @endif
        <!-- Upload(both signature uplaod and details upload)-->
        @if(Options::get('left_signature')=='left_signature_upload')
         <div >
             @if(Options::get('left_signature_image'))
                <img src="{{ asset('uploads/config/'.Options::get('left_signature_image')) }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;" />
             @endif
            <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
            <p>Performed By :<br>
                <div class="doctor-details">
                    {!!Options::get('left_signature_textarea')!!}
                </div>
            </p>
         </div>
        @endif 
    </div>
   
    <div style="width:28%;float: left;padding:10px 10px 0;">
        @if(Options::get('center_signature')=='center_signature_auto')
            <div>
                @if(isset($user_prepared_second->signature_image))
                    <img class="signature-image" src="data:image/jpg;base64,{{ $user_prepared_second->signature_image??'' }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;">
                @endif
                <p class="underline" style="margin-top: 5px;margin-bottom: 0;">_________________________</p>
                <p>Performed By :<br>
                    <span class="uppercase">{{ $user_prepared_second->firstname??''}} {{$user_prepared_second->middlename??''}} {{$user_prepared_second->lastname ??''}}</span><br>
                    {{ $user_prepared_second->signature_title??''}}<br>
                    @if(isset($user_prepared_second->nhbc))
                    NHPC : {{$user_prepared_second->nhbc}}
                    @endif
                </p>
            </div>
        @endif
        <!--Manual (all handwritten signatrue and details)-->
        @if(Options::get('center_signature')=='center_signature_manual')
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Performed By : <br>
                </p>
            </div>
        @endif
        <!-- Upload(both signature uplaod and details upload)-->
        @if(Options::get('center_signature')=='center_signature_upload')
         <div>
             @if (Options::get('center_signature_image'))
                <img src="{{ asset('uploads/config/'.Options::get('center_signature_image')) }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;" />
             @endif
            <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
            <p>Performed By :</p>
            <div class="doctor-details">
                {!!Options::get('center_signature_textarea')!!}
            </div>
         </div>
         @endif 
    </div>
   
    <div style="width: 28%;float: right;padding:10px 10px 0;">
        <!--Auto (all from database) -->
        @if(Options::get('right_signature')=='right_signature_auto')
            <div>
                @if (isset($user_verified_first->signature_image))
                    <img class="signature-image" src="data:image/jpg;base64,{{ $user_verified_first->signature_image??'' }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;">
                @endif
                <p class="underline" style="margin-top: 5px;margin-bottom: 0;">_________________________</p>
                <p>Verified By :<br>
                    <span class="uppercase">{{$user_verified_first->fldcategory??''}} {{$user_verified_first->firstname??''}} {{$user_verified_first->middlename??''}} {{$user_verified_first->lastname??''}}</span><br>
                    {{ $user_verified_first->signature_title??''}}<br>
                    @if(isset($user_verified_first->nhbc))
                    NHPC : {{$user_verified_first->nhbc}}
                    @endif
                </p>
            </div>
        @endif
        <!--Manual (all handwritten signatrue and details)-->
        @if(Options::get('right_signature')=='right_signature_manual')
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Verified By : <br>
                </p>
            </div>
        @endif

        <!-- Upload(both signature uplaod and details upload)-->
        @if(Options::get('right_signature')=='right_signature_upload')
         <div>
             @if (Options::get('right_signature_image'))
                <img src="{{ asset('uploads/config/'.Options::get('right_signature_image')) }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;" />
             @endif
            <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
            <p>Verified By :</p>
            <div class="doctor-details">
                {!!Options::get('right_signature_textarea')!!}
            </div>
         </div>
         @endif 
    </div>
    <div class="clearfix"> </div>
</footer>
<footer class="pdf-container">
    <div style="width: 47%;float: left;padding:0 10px;">
        @php
            $auth_user=\Auth::guard('admin_frontend')->user();
        @endphp
        <p>Prepared By : {{$auth_user->firstname??''}} {{$auth_user->middlename??''}} {{$auth_user->lastname??''}}<br>
          Printed Date Time : {{ \Carbon\Carbon::now() }}
        </p>
    </div>

    <div style="width: 42%;float: right;padding:0 10px;text-align: right;">
        
        <p> {!! Helpers::generateQrCodeQr($encounter_data->fldencounterval) !!}   <br>
         Please scan qr code above to verfiy the result
        </p>
    </div>

</footer>