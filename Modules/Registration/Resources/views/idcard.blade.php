<!DOCTYPE html>
<html lang="en">

<head>
    <title>Id Card</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'trebuchet ms', 'sans serif';
        }

        .m-0 {
            margin: 0;
        }

        .m-4 {
            margin: 4px;
        }

        #holder {
            width: 325px;
            margin: 90px auto;
            display: block;
            text-align: center;
        }

        #holder .box {
            width: 325px;
            height: 204px;
            box-shadow: 3px 3px 3px 0px #ccc;
            padding-bottom:2px;
            box-sizing: border-box;

        }

        #holder .box .profile-img {
            border: 1px solid #eee;
            /* padding: 4px; */
            border-radius: 50%;
            background: #fff;
            width: 42px;
            /* margin: -8px 0px 0 0; */
        }

        #holder .box h1 {
            font-weight: 400;
            font-size: 14px;
            color: #000;
            padding:8px;
        }

        #holder .box .tie {
            background-color: #e1e9ef;
            height: 50px;
            border-bottom:1px solid #e1e9ef;
        }

        #holder .box h4.pname {
            margin: 5px 0 0 0;
            padding: 0;
            color: #144069;
        }

        #holder .box h4.patientid {
            margin: 4px;
            padding: 0;
            text-transform: uppercase;
        }

        #holder:hover {
            cursor: none;
        }

        .pname-box {
            margin-top: 10px;
            display: flex;
            width: 100%;
            padding:0 2px;
        }

        .details-pname {
            width: calc(100% - 127px);
            text-align:left;
        }

        /* .qrcode-box {
            text-align: left;
            padding-right:10px;
        } */
/* 
        .qrcode-box img {
            width: 42px;
        } */

        .profimg-box {
            width: 60px;
        }

        .pname-details {
            display: flex;
            width: 100%;
        }

        .date-box {
            width: 27.5%;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 14px;
        }

        .gender-box {
            width: 27.5%;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 14px;
        }

        .address-box {
            width: 45%;
            text-align: center;
            font-size: 14px;
        }

        .pname-h5 {
            color: #144069;
            font-weight: 400;
        }

        .mt-pname {
            margin-top: 9px;
        }
        .logo-img{
            /* margin: 5px 2px 0px 17px; */     
            height: calc(100% - 10px);
            width: 14%;   
            padding: 5px 5px 5px 15px;
        }
        .logo-holder{
            display: flex;
            height: 100%;
            align-items: center;
        }
    </style>
</head>

<body>
    <div id="holder">
        <div class="box">
            <div class="tie">
                <div class="logo-holder">
                    <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="logo" class="logo-img">                   
                    <h1>{{ (Options::get('siteconfig') && isset(Options::get('siteconfig')['system_name'])) ? Options::get('siteconfig')['system_name'] : '' }}</h1>
                </div>
            </div>
            <div class="pname-box">
                <div class="profimg-box">
                    <img src="{{ ($patientinfo->latestImage && $patientinfo->latestImage->fldpic) ? $patientinfo->latestImage->fldpic : asset('new/images/page-img/10.jpg') }}" alt="" class="profile-img">                     
                </div>
                <div class="details-pname">                    
                    <h4 class="pname m-0">{{ $patientinfo->fldfullname }}</h4>
                    <h5 class="patientid m-4">PID:{{ $patientinfo->fldpatientval }}</h5>
                </div>
                <div class="qrcode-box">
                    {!! Helpers::generateQrCode($patientinfo->fldpatientval)!!}
                </div>
            </div>
            <div class="pname-details mt-pname">
                <div class="date-box">
                    <h5 class="pname-h5 m-0">D.O.B</h5>
                    <h5 class="m-0">{{ explode(' ', $patientinfo->fldptbirday)[0] }}</h5>

                    <h5 class="pname-h5 m-0 mt-pname">Date Of Issue</h5>
                    <h5 class="m-0">{{ explode(' ', $patientinfo->fldtime)[0] }}</h5>
                </div>
                <div class="gender-box">
                    <h5 class="pname-h5 m-0">Gender</h5>
                    <h5 class="m-0">{{ $patientinfo->fldptsex }}</h5>

                    <h5 class="pname-h5 m-0 mt-pname">Contact:</h5>
                    <h5 class="m-0">{{ $patientinfo->fldptcontact }}</h5>
                </div>
                <div class="address-box">
                    <h5 class="pname-h5 m-0">Address</h5>
                    <h5 class="m-0">{{ $patientinfo->getFullAddress() }},</h5>
                    <h5 class=" m-0" style="bottom: 0;">{{ $patientinfo->fldprovince }}</h5>
                </div>
            </div>
        </div>
    </div>
</body>

</html>