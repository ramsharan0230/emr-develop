<footer class="pdf-container sign-container">
    <div style="width: 40%;float: left;padding:10px 10px 0;">
        <!--Auto (all from database) -->
        @if(Options::get('pcr_left_signature')=='left_signature_auto')
            <div>
                @if(isset($user_prepared_first->signature_image))
                <img class="signature-image" src="data:image/jpg;base64,{{ $user_prepared_first->signature_image??'' }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;">
                @endif
                <p class="underline" style="margin-top: 5px;margin-bottom: 0;">_________________________</p>
                <p>Prepared By :<br>
                    <span class="uppercase">{{$user_prepared_first->fldcategory??''}} {{$user_prepared_first->firstname??''}} {{$user_prepared_first->middlename??''}} {{$user_prepared_first->lastname??''}}</span><br>
                    {{ $user_prepared_first->signature_title??''}}<br>
                    @if(isset($user_prepared_first->nhbc))
                    NHPC : {{$user_prepared_first->nhbc}}
                    @endif
                </p>
            </div>
        @endif
        <!--Manual (all handwritten signatrue and details)-->
        @if(Options::get('pcr_left_signature')=='left_signature_manual')
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Prepared By : <br>
                </p>
            </div>
        @endif

        <!-- Upload(both signature uplaod and details upload)-->
        @if(Options::get('pcr_left_signature')=='left_signature_upload')
         <div >
             @if(Options::get('pcr_left_signature_image'))
                <img src="{{ asset('uploads/config/'.Options::get('pcr_left_signature_image')) }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;" />
             @endif
            <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
            <p>Prepared By :</p>
            <div class="doctor-details">
                {!!Options::get('pcr_left_signature_textarea')!!}
            </div>
         </div>
         @endif 
    </div>
   

   
    <div style="width: 30%;float: right;padding:10px 10px 0;">
        <!--Auto (all from database) -->
        @if(Options::get('pcr_right_signature')=='right_signature_auto')
            <div>
                @if(isset($user_prepared_first->signature_image))
                    <img class="signature-image" src="data:image/jpg;base64,{{ $user_prepared_first->signature_image??'' }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;">
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
        @if(Options::get('pcr_right_signature')=='right_signature_manual')
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Verified By : <br>
                </p>
            </div>
        @endif

        <!-- Upload(both signature uplaod and details upload)-->
        @if(Options::get('pcr_right_signature')=='right_signature_upload')
         <div >
             @if(Options::get('pcr_right_signature_image'))
                <img src="{{ asset('uploads/config/'.Options::get('pcr_right_signature_image')) }}" alt="signature" width="100" height="50" style="margin-bottom:-10px;" />
             @endif
            <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
            <p>Verified By :</p>
            <div class="doctor-details">
                {!!Options::get('pcr_right_signature_textarea')!!}
            </div>
         </div>
         @endif 
    </div>
    <div class="clearfix"> </div>
</footer>
{{-- <footer class="pdf-container">
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

</footer><br><br><br><br><br><br> --}}
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
        <p> 
            {!! Milon\Barcode\DNS2D::getBarcodeSVG(route('qr.print.pcr', ['key' => encrypt([
                'name' => request()->get('name'),
                'encounterId' => request()->get('encounterId'),
                'category' => request()->get('category'),
                'fromdate' => request()->get('fromdate'),
                'todate' => request()->get('todate'),
                '_token' => request()->get('_token'),
                'encounter_id' => request()->get('encounter_id'),
                'sample_id' => request()->get('sample_id'),
                'category_id' => request()->get('category_id'),
                'type' => request()->get('type'),
                'encounter_sample' => request()->get('encounter_sample'),
                'status' => request()->get('status'),
                'report_category_id' => request()->get('report_category_id'),
                'test' => [request()->get('test')]
                ])]),'QRCODE',2,2)
            !!} <br>
         Please scan qr code above to verfiy the result
        </p>
    </div>
</footer>