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

        }

        #holder .box .profile-img {
            border: 1px solid #eee;
            padding: 4px;
            border-radius: 50%;
            background: #fff;
            width: 42px;
            margin: -8px 0px 0 0;
        }

        #holder .box h1 {
            font-weight: normal;
            font-size: 15px;
            color: #fff;
            margin:16px 0 0 11px;
        }

        #holder .box .tie {
            background-color: #144069;
            height: 50px;
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
            margin-top: 12px;
            display: flex;
            width: 100%;
        }

        .details-pname {
            width: 50%;
            text-align:left;
        }

        .qrcode-box {
            width: 20%;
            text-align: left;
        }

        .qrcode-box img {
            width: 42px;
        }

        .profimg-box {
            width: 30%;
        }

        .pname-details {
            display: flex;
            width: 100%;
        }

        .date-box {
            width: 33.3%;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 14px;
        }

        .gender-box {
            width: 31.4%;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 14px;
        }

        .address-box {
            width: 35.5%;
            text-align: center;
            font-size: 14px;
        }

        .pname-h5 {
            color: #144069;
            font-weight: bold;
        }

        .mt-pname {
            margin-top: 11px;
        }
        .logo-img{
            width: 16%;
            margin: 5px 0px 0px 17px;
        }
        .logo-holder{
            display: flex;
        }
    </style>
</head>

<body>
    <div id="holder">
        <div class="box">
            <div class="tie">
                <div class="logo-holder">
                    <img src="{{ asset('new/images/logo.png')}}" alt="" class="logo-img">
                    <h1>Chirayu International Hospital</h1>
                </div>
            </div>
            <div class="pname-box">
                <div class="profimg-box">

                    <img src="{{ asset('new/images/page-img/10.jpg')}}" alt="" class="profile-img">
                </div>
                <div class="details-pname">
                    <h4 class="pname m-0">Jasmine Doe</h4>
                    <h5 class="patientid m-4">PID:10010032</h5>
                </div>
                <div class="qrcode-box">
                    <img src="{{ asset('new/images/qr.png')}}" alt="">
                </div>
            </div>
            <div class="pname-details mt-pname">
                <div class="date-box">
                    <h5 class="pname-h5 m-0">D.O.B</h5>
                    <h5 class="m-0">2055-01-10</h5>

                    <h5 class="pname-h5 m-0 mt-pname">Date Of Issue</h5>
                    <h5 class="m-0">2055-01-10</h5>
                </div>
                <div class="gender-box">
                    <h5 class="pname-h5 m-0">Gender</h5>
                    <h5 class="m-0">Female</h5>

                    <h5 class="pname-h5 m-0 mt-pname">Contact:</h5>
                    <h5 class="m-0">9842134567</h5>
                </div>
                <div class="address-box">
                    <h5 class="pname-h5 m-0">Address</h5>
                    <h5 class="m-0">chandragiri-6,Butuwal</h5>

                    <h5 class=" m-0 mt-pname">Province-5</h5>
                </div>
            </div>
        </div>
    </div>
</body>

</html>