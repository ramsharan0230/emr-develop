<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Band</title>
<style>
    @media print {
        @page {
            size: {{ Options::get('bar_code_width') ?Options::get('bar_code_width'):60 }}mm {{ Options::get('bar_code_height') ?Options::get('bar_code_height'):20 }}mm;
            margin: 0;
            padding: 0;
        }
    }
</style>
<script>
    window.onload = function () {
        window.print();
    }
</script>
<div class="print" style="margin: 0; padding: 0; font-size: 8px">
    <span style="float: left;">{{ $patient->fullname }}</span> <span style="float: right;">{{ $patient->latestEncounter->fldencounterval }}</span>
    <span style="clear: both"></span>
    <p style="margin: 0; padding: 0;">
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($patient->latestEncounter->fldencounterval, \App\Utils\Options::get('barcode_format')) }}" class="barcode" alt="barcode" style="width: 200px;"/>
    </p>
</div>

