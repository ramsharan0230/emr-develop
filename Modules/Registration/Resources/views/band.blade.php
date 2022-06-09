<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Band</title>
    <style>
        .patDetails {
            position: absolute;
            left: 20%;
            top: 3%;
            font-size: 15px;
            font-weight: bold;
        }

        .details2 {
            position: absolute;
            left: 20%;
            top: 5%;
            font-size: 9px;
            font-weight: bold;
        }

        .barcode {
            position: absolute;
            left: 10%;
            top: 6%;
            width: 8%;
        }

        .qrcode {
            position: absolute;
            left: 42%;
            top: 6%;
            width: 5%;
        }

        .ticket {
            height: 145px;
            width: 42%;
            background-color: #fff;
            border-radius: 15px;
        }

        .form-group-band {
            width: 21%;
        }

        .details-label {
            margin-bottom: 4px;
        }

        .full-width {
            width: 100px;
        }

        table tr th {
            font-size: 12px;
            text-align: left;
            padding: 4px;
        }

        .text-center {
            text-align: center;
        }
    </style>
<body>
<div class="container-fluid">
    <div class="card mb-5" style="background-color: #e1eff1; ">
        <img src="{{ asset('new/images/band.png')}}" alt="image" style="margin-top: 8px; width: 100%;">
        <img src="{{ asset('new/images/barcode.png')}}" class="barcode" alt="image"/>

        <label class="patDetails"><strong>{{ Options::get('system_patient_rank')  == 1 ?$patient->latestEncounter->fldrank:''}} {{ $patient->fldfullname }}</strong></label>
        <div class="form-group-band details2">

            <label class="details-label ">Patient ID: {{ $patient->fldpatientval }} </label>&nbsp;
            &nbsp;
            <label class="details-label"> EncID: {{ $patient->latestEncounter->fldencounterval }}</label>
            <label class="details-label full-width">{{ $patient->latestEncounter->consultant->fldconsultname??'' }}</label><br>
            <label class="details-label ">DOB: {{ explode(' ', $patient->fldptbirday)[0] }}</label>&nbsp;
            &nbsp;
            <label class="details-label"> Admitted: {{ explode(' ', $patient->latestEncounter->flddoa)[0] }}</label><br>
            <label class="details-label  full-width">Age/Sex: {{ $patient->fldagestyle }}/{{ $patient->fldptsex }}</label><br>
            <label class="details-label">Room: {{ ($patient->latestEncounter->departmentBed) ? $patient->latestEncounter->departmentBed->fldbed : '' }}</label>&nbsp;
            &nbsp;
            <label class="details-label">Allerygy: test in case</label>
            <label class="details-label  full-width">Disease: test in case</label>
        </div>
        <div class="qrcode" style="width: 30px;">
            {!! Helpers::generateQrCode($patient->fldpatientval)!!}
        </div>

    </div>
    <div class="card mb-5" style="background-color: #e1eff1; padding: 20px;">
        <div class="ticket">
            <h6 style="text-decoration: underline;text-align:center;font-weight: bold;margin-top: 9px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h6>
            <div class="form-group p-2 form-row">
                <table>
                    <tr>
                        <th>Patient NO.: {{ $patient->fldpatientval }}</th>
                        <th class="text-center">Date: {{ explode(' ', $patient->latestEncounter->flddoa)[0] }}</th>
                    </tr>
                    <tr>
                        <th>Name.:{{ $patient->fldfullname }}</th>
                        <th class="text-center">Address: {{ $patient->fldptaddvill }}, {{ $patient->fldptadddist }}</th>
                    </tr>
                    <tr>
                        <th>Contact: {{ $patient->fldptcontact }}</th>
                        <th class="text-center">Gender.: {{ $patient->fldptsex }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
