<!DOCTYPE html>
<html>

<head>
    <title>Lab Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
      :root {
    --pd-font-size: 13px;
    --table-font-size: 13px;
    --margin-top:30mm;
    --margin-bottom:24mm;
    --margin-y:5px;

}

@page {
    margin-top: var(--margin-top);
    margin-bottom:  var(--margin-bottom);    
}

body {
    margin: 0 auto;
    padding: 10px 5px 5px;
    font-size: 12px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

.pdf-container {
    margin: 0 auto;
    width: 95%;
}

.patient-container {
    border: 1px solid #8b8989;
    font-size: var(--pd-font-size);
}

.reception-no{
  
   float:right;
   margin-top:-26px;
}


.reception-no p span{
    font-weight: bold;
}


.patient-font {
    font-size: var(--pd-font-size);
}

.details {
    display: flex;
    justify-content: space-between;
}

.patient-detail {
    width: 40%;
    float: left;
    text-align: left;

}

.patient-detail .h-label {
    min-width: 100px;
    display: inline-block;
}

.sample-detail {
    width: 60%;
    float: left;
    text-align: left;

}

.sample-detail .h-label {
    min-width: 170px;
    display: inline-block;
}

.category-header {
    text-align: center;
    background-color: #ececec;
    margin-top: 12px;
    padding: 4px;
}

.histo-container {

    font-size: var(--pd-font-size);
    padding: 4px;

}

.header {
    overflow: hidden;
}

.normal-font {
    font-weight: 400;
}

.content-body tr td,
.content-body tr th {
    padding: 5px;
}


.text-right {
    text-align: right;

}

.text-center {
    text-align: center;
}

.content-body {
    border-collapse: collapse;
    border: 1px solid #8b8989;
    margin-top: 4px;
}

/* .content-body td,
.content-body th {
    border: 1px solid #ddd;
} */

.test-content tr td,
.test-content tr td,
h4 {
    padding: 0px;
    margin: 0px;
}

.content-body thead {
    border: 1px solid #8b8989;
    text-align: left;
}

.content-body {
    font-size: var(--table-font-size);
}

p {
    margin: 0;
}

table {
    page-break-inside: auto
}

tr {
    page-break-inside: avoid;
    page-break-after: auto
}

.uppercase {
    text-transform: uppercase;
}

@page {
    margin: 5mm 0;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
  }

  .underline{
     overflow: hidden;

  }
  .doctor-details p:nth-child(1) {
    text-transform: uppercase;
  }
    </style>
</head>

<body>
    {{-- header --}}
    @if(Options::get('lab_patient_pcr_header')=='header1')
        @include('laboratory::pdf.header.lab-header-1')
    @endif
    @if(Options::get('lab_patient_pcr_header')=='header2')
        @include('laboratory::pdf.header.lab-header-2')
    @endif
    @if(Options::get('lab_patient_pcr_header')=='header3')
        @include('laboratory::pdf.header.lab-header-3')
    @endif

    {{-- section --}}
    @include('laboratory::pdf.section.lab-pcr-patient')
    {{--footer  --}}
    @if(Options::get('lab_patient_pcr_footer')=='footer1')
        @include('laboratory::pdf.footer.pcr.pcr-lab-footer-1')
    @endif
    @if(Options::get('lab_patient_pcr_footer')=='footer2')
        @include('laboratory::pdf.footer.pcr.pcr-lab-footer-2')
    @endif
    @if(Options::get('lab_patient_pcr_footer')=='footer3')
        @include('laboratory::pdf.footer.pcr.pcr-lab-footer-3')
    @endif
    @if(Options::get('lab_patient_pcr_footer')=='footer4')
        @include('laboratory::pdf.footer.pcr.pcr-lab-footer-4')
    @endif
    {{-- <footer class="pdf-container sign-container">
        <div style="width: 40%;float: left;padding:10px 10px 0;">
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Prepared By : <br>
                </p>
            </div>
        </div>
       
    
       
        <div style="width: 30%;float: right;padding:10px 10px 0;">
            <div>
                <p class="underline" style="margin-top: 35px;margin-bottom: 0;">_________________________</p>
                <p >
                    Verified By : <br>
                </p>
            </div>
        </div>
        <div class="clearfix"> </div>
    </footer><br><br><br><br><br><br> --}}

    
</body>

</html>
